<?php

/*
 * Copyright 2017 Royal Botanic Gardens Victoria.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Queries;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Taxon Queries
 * @author Niels Klazenga
 */
class TaxonQueries {

    /**
     * Gets the requested taxon
     * @param  Uuid $id UUID of the requested taxon
     * @param  array $includes
     * @return \stdClass|NotFoundHttpException
     */
    public static function getTaxon($id)
    {
        $query = self::baseQuery();
        $taxon = $query->where('t.guid', $id)
                ->first();
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        return $taxon;
    }

    /**
     * Get classification for requested Taxon
     * @param  Uuid $id UUID of requested Taxon
     * @return Collection
     */
    public static function getHigherClassification($id)
    {
        $ret = [];
        $nodeNumber = DB::table('flora.taxa as t')
                ->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                ->where('t.guid', '=', $id)
                ->value('tr.node_number');

        if ($nodeNumber) {
            $query = self::baseQuery();
            $ret = $query->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                    ->join('flora.taxon_tree_def_items as tdi',
                            't.taxon_tree_def_item_id', '=', 'tdi.id')
                    ->where('tr.node_number', '<', $nodeNumber)
                    ->where('tr.highest_descendant_node_number', '>=', $nodeNumber)
                    ->whereNotIn('tdi.id', [22, 24])
                    ->orderBy('tdi.rank_id')
                    ->get();
        }
        return $ret;
    }

    /**
     * Get siblings of requested Taxon
     * @param  Uuid $id UUID of requested Taxon
     * @return Collection
     */
    public static function getSiblings($id)
    {
        $parent = DB::table('flora.taxa')
                ->where('guid', $id)
                ->value('parent_id');

        $query = self::baseQuery();
        return $query->where('pt.id', '=', $parent)
                ->where('vts.name', '=', 'accepted')
                ->orderBy('n.full_name')
                ->get();
    }

    /**
     * Get children of requested Taxon
     * @param  Uuid $id UUID of requested Taxon
     * @return Collection
     */
    public static function getChildren($id)
    {
        $query = self::baseQuery();
        return $query->where('pt.guid', '=', $id)
                ->where('vts.name', '=', 'accepted')
                ->whereNotIn('td.id', [22, 24])
                ->orderBy('n.full_name')
                ->get();
    }

    /**
     * Get synonyms of requested Taxon
     * @param  Uuid $id UUID of requested Taxon
     * @return Collection
     */
    public static function getSynonyms($id)
    {
        $query = self::baseQuery();
        return $query->where('at.guid', '=', $id)
                ->whereColumn('at.id', '!=', 't.id')
                ->orderBy('n.full_name')
                ->get();
    }

    /**
     * Base query: other queries might use this and add extra joins and/Or
     * WHERE conditions.
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function baseQuery()
    {
        return DB::table('flora.taxa as t')
                ->select('t.id', 't.guid', 't.taxon_remarks', 't.is_endemic',
                        't.timestamp_created', 't.timestamp_modified')

                // Name
                ->join('flora.names as n', 't.name_id', '=', 'n.id')
                ->addSelect('n.guid as scientific_name_id', 'n.full_name',
                        'n.authorship')

                // Taxon rank
                ->join('vocab.taxon_rank_vocab as trv',
                        't.rank_id', '=', 'trv.id')
                ->addSelect('trv.uri as taxon_rank_uri',
                        'trv.name as taxon_rank_name',
                        'trv.label as taxon_rank_label')

                // Name according to
                ->leftJoin('public.references as r',
                        't.name_according_to_id', '=', 'r.id')
                ->addSelect('r.guid as name_according_to_id')

                // Accepted name usage
                ->leftJoin('flora.taxa as at',
                        't.accepted_id', '=', 'at.id')
                ->addSelect('at.guid as accepted_name_usage_id')

                // Parent name usage
                ->leftJoin('flora.taxa as pt',
                        't.parent_id', '=', 'pt.id')
                ->addSelect('pt.guid as parent_name_usage_id')

                // Taxonomic Status
                ->leftJoin('vocab.taxonomic_status_vocab as vts',
                                't.taxonomic_status_id', '=', 'vts.id')
                ->addSelect('vts.uri as taxonomic_status_uri',
                        'vts.name as taxonomic_status_name',
                        'vts.label as taxonomic_status_label')

                // Occurrence Status
                ->leftJoin('vocab.occurrence_status_vocab as vos',
                        't.occurrence_status_id', '=', 'vos.id')
                ->addSelect('vos.uri as occurrence_status_uri',
                        'vos.name as occurrence_status_name',
                        'vos.label as occurrence_status_label')

                // Establishment means
                ->leftJoin('vocab.establishment_means_vocab as vem',
                        't.establishment_means_id', '=', 'vem.id')
                ->addSelect('vem.uri as establishment_means_uri',
                        'vem.name as establishment_means_name',
                        'vem.label as establishment_means_label')

                // Threat Status
                ->leftJoin('vocab.threat_status_vocab as vth',
                        't.threat_status_id', '=', 'vth.id')
                ->addSelect('vth.uri as threat_status_uri',
                        'vth.name as threat_status_name',
                        'vth.label as threat_status_label')

                // Blameable
                ->leftJoin('public.agents as ca',
                        't.created_by_id', '=', 'ca.id')
                ->leftJoin('public.agents as ma',
                        't.modified_by_id', '=', 'ma.id')
                ->addSelect('ca.guid as creator', 'ma.guid as modified_by')

                // Cultivar group
                ->leftJoin('flora.taxa as cg', 't.cultivar_group_id', '=', 'cg.id')
                ->addSelect('cg.guid as cultivar_group_id')
                ;
    }
}
