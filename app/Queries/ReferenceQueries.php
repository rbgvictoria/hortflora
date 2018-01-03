<?php

/*
 * Copyright 2017 Niels Klazenga, Royal Botanic Gardens Victoria.
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

/**
 * Referemce Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class ReferenceQueries {

    /**
     * Get requested Reference
     * @param  Uuid $id UUID for the requested Reference
     * @return \stdClass
     */
    public static function getReference($id)
    {
        return DB::table('public.references as r')
                ->leftJoin('public.agents as ca', 'r.created_by_id', '=', 'ca.id')
                ->leftJoin('public.agents as ma', 'r.modified_by_id', '=', 'ma.id')
                ->leftJoin('public.references as pr', 'r.parent_id', '=', 'pr.id')
                ->select('r.id', 'r.reference', 'r.author', 'r.publication_year',
                        'r.title', 'r.journal_or_book', 'r.collation',
                        'r.series', 'r.edition', 'r.volume', 'r.part', 'r.page',
                        'r.publisher', 'r.place_of_publication', 'r.subject',
                        'r.timestamp_created', 'r.timestamp_modified', 'r.guid',
                        'r.version', 'ca.guid as creator',
                        'ma.guid as modified_by', 'pr.guid as published_in')
                ->where('r.guid', '=', $id)
                ->first();
    }

    /**
     * Get references for a taxon
     * @param  Uuid $taxon UUID of the taxon
     * @return Collection
     */
    public function getTaxonReferences($taxon)
    {
        return DB::table('public.references as r')
                ->join('flora.taxon_references as tr', 'r.id', '=', 'tr.reference_id')
                ->join('flora.taxa as t', 'tr.taxon_id', '=', 't.id')
                ->leftJoin('public.agents as ca', 'r.created_by_id', '=', 'ca.id')
                ->leftJoin('public.agents as ma', 'r.modified_by_id', '=', 'ma.id')
                ->leftJoin('public.references as pr', 'r.parent_id', '=', 'pr.id')
                ->select('r.id', 'r.reference', 'r.author', 'r.publication_year',
                        'r.title', 'r.journal_or_book', 'r.collation',
                        'r.series', 'r.edition', 'r.volume', 'r.part', 'r.page',
                        'r.publisher', 'r.place_of_publication', 'r.subject',
                        'r.timestamp_created', 'r.timestamp_modified', 'r.guid',
                        'r.version', 'ca.guid as creator',
                        'ma.guid as modified_by', 'pr.guid as published_in')
                ->where('t.guid', '=', $taxon)
                ->get();
    }
}
