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
class CultivarQueries
{
    /**
     * Get cultivars for a taxon
     * @param  Uuid $id
     * @return Collection
     */
    public static function getCultivars($id)
    {
        $cultivars = [];
        $result = self::cultivarsQuery($id);
        foreach ($result as $row) {
            $cultivar = $row;
            if ($row->taxon_rank == 'cultivarGroup') {
                $members = self::cultivarsQuery($row->guid);
                if ($members) {
                    $cultivar->cultivars = $members;
                }
            }
            $cultivars[] = $cultivar;
        }
        return $cultivars;
    }

    public static function getCultivarGroup($id)
    {
        $result = self::cultivarsQuery($id);
        $row = $result[0];
        if ($row->taxonRank == 'cultivarGroup') {
            return $row;
        }
    }

    /**
     * Cultivar query
     * @param  Uuid $id UUID of the parent taxon
     * @return Collection
     */
    protected static function cultivarsQuery($id)
    {
        return DB::table('flora.taxa as t')
                ->join('flora.taxa as c', function($join) {
                    $join->on('t.id', '=',
                            DB::raw('CASE t.taxon_tree_def_item_id '
                                    . 'WHEN 22 THEN c.cultivar_group_id '
                                    . 'WHEN 24 THEN c.cultivar_group_id '
                                    . 'ELSE c.parent_id END'))
                            ->where('c.taxon_tree_def_item_id', 23);
                })
                ->select('c.guid')

                // Name
                ->join('flora.names as n', 'c.name_id', '=', 'n.id')
                ->addSelect('n.full_name')

                ->join('vocab.taxon_rank_vocab as tv', 'c.rank_id', '=', 'tv.id')
                ->addSelect('tv.name as taxon_rank')

                // Treatment
                ->leftJoin('flora.treatments as tr', function($join) {
                    $join->on('c.id', '=', 'tr.taxon_id')
                            ->where('tr.is_current_treatment', true);
                })
                ->leftJoin('flora.treatment_versions as trv', function($join) {
                    $join->on('tr.id', '=', 'trv.treatment_id')
                            ->where('trv.is_current_version', true);
                })
                ->addSelect('trv.html as description')

                // Cultivar group
                ->leftJoin('flora.taxa as cg', 'c.cultivar_group_id', '=', 'cg.id')
                ->leftJoin('flora.names as cgn', 'cg.name_id', '=', 'cgn.id')
                ->leftJoin('flora.treatments as cgtr', function($join) {
                    $join->on('cg.id', '=', 'cgtr.taxon_id')
                            ->where('cgtr.is_current_treatment', true);
                })
                ->leftJoin('flora.treatment_versions as cgtrv', function($join) {
                    $join->on('cgtr.id', '=', 'cgtrv.treatment_id')
                            ->where('cgtrv.is_current_version', true);
                })
                ->addSelect('cg.guid as cultivar_group_id',
                        'cgn.name as cultivar_group',
                        'cgtrv.html as cultivar_group_description')

                ->where('t.guid', $id)
                ->orderBy('n.full_name')
                ->get();
    }

}
