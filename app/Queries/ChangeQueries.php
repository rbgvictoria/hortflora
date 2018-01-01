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

/**
 * Change Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class ChangeQueries {

    /**
     * Get changes
     * @param  array   $queryParams
     * @param  bool $paginate
     * @param  int $pageSize
     * @return \Illuminate\Database\Query\Builder
     */
    public function getChanges($queryParams=[], $paginate=true, $pageSize=30)
    {
        $query = $this->baseQuery();
        if (isset($queryParams['filter'])) {
            $query = $this->filter($query, $queryParams['filter']);
        }
        return $this->pagination($query, $paginate, $pageSize);
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function baseQuery()
    {
        return DB::table('flora.changes as c')
                ->select('c.guid',
                        'ft.guid as from_taxon', 'fi.name as from_rank',
                        'fn.full_name as from_full_name',
                        'tt.guid as to_taxon', 'ti.name as to_rank',
                        'tn.full_name as to_full_name',
                        'c.source', 'c.change_type',
                        'ca.guid as created_by_agent_guid',
                        'ca.name as created_by_agent_name',
                        'ca.first_name as created_by_agent_first_name',
                        'ca.last_name as created_by_agent_last_name',
                        'at.label as created_by_agent_type',
                        'c.timestamp_created')
                ->join('flora.taxa as ft', 'c.from_taxon_id', '=', 'ft.id')
                ->join('flora.names as fn', 'ft.name_id', '=', 'fn.id')
                ->join('flora.taxon_tree_def_items as fi',
                        'ft.taxon_tree_def_item_id', '=', 'fi.id')
                ->join('flora.taxa as tt', 'c.to_taxon_id', '=', 'tt.id')
                ->join('flora.names as tn', 'tt.name_id', '=', 'tn.id')
                ->join('flora.taxon_tree_def_items as ti',
                        'tt.taxon_tree_def_item_id', '=', 'ti.id')
                ->leftJoin('public.agents as ca',
                        'c.created_by_id', '=', 'ca.id')
                ->leftJoin('public.agent_type as at',
                        'ca.agent_type_id', '=', 'at.id');
    }

    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  array $filter [description]
     * @return \Illuminate\Database\Query\Builder
     */
    protected function filter($query, $filter) {
        $query->when(isset($filter['from']), function($query) use ($filter) {
            return $query->where('c.timestamp_created', '>', $filter['from']);
        });
        $query->when(isset($filter['fromTaxonID']), function($query) use ($filter) {
            return $query->where('ft.guid', $filter['fromTaxonID']);
        });
        $query->when(isset($filter['toTaxonID']), function($query) use ($filter) {
            return $query->where('tt.guid', $filter['toTaxonID']);
        });
        return $query;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  bool $paginate [description]
     * @param  int $pageSize [description]
     * @return Collection
     */
    protected function pagination($query, $paginate=true, $pageSize=20)
    {
        if ($paginate) {
            return $query->paginate($pageSize)
                    ->withPath('https://vicflora.rbg.vic.gov.au/' .
                            request()->path());
        }
        else {
            return $query->get();
        }
    }

}
