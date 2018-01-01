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
 * Treatment Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class TreatmentQueries {

    /**
     * [getTreatments description]
     * @param  array   $queryParams Query parameters
     * @param  bool $paginate Whether to paginate the result or not
     * @param  int $pageSize page size
     * @return [type]               [description]
     */
    public function getTreatments($queryParams=[], $paginate=true, $pageSize=30)
    {
        $query = $this->baseQuery();
        if (isset($queryParams['filter'])) {
            $query = $this->filter($query, $queryParams['filter']);
        }
        return $this->pagination($query, $paginate, $pageSize);
    }

    /**
     * [getTreatment description]
     * @param  Uuid $id      UUID for the requetsed treatment
     * @param  int $version
     * @return \stdClass
     */
    public function getTreatment($id, $version)
    {
        $query = $this->baseQuery();
        return $query->where('tr.guid', $id)
                ->where('trv.version', $version)
                ->first();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function baseQuery() {
        return DB::table('flora.taxa as t')
                ->select('tr.guid', 'trv.version', 't.guid as taxon_guid',
                        'ast.guid as as_guid', 'act.guid as accepted_guid',
                        'r.guid as source_id', 'trv.html', 'tr.is_current_treatment',
                        'trv.is_current_version',
                        'trv.is_updated', 'cba.guid as created_by_agent_id',
                        'cbat.name as created_by_agent_type',
                        'cba.name as created_by_agent_name',
                        'trv.timestamp_created',
                        'mba.guid as modified_by_agent_id',
                        'mbat.name as modified_by_agent_type',
                        'mba.name as modified_by_agent_name',
                        'trv.timestamp_modified')
                ->join('flora.treatments as tr', 't.id', '=',
                        DB::raw('coalesce(tr.accepted_id, tr.taxon_id)'))
                ->join('flora.treatment_versions as trv', 'tr.id', '=', 'trv.treatment_id')
                ->leftJoin('flora.taxa as act', function($join) {
                    $join->on('tr.accepted_id', '=', 'act.id');
                    $join->on('tr.accepted_id', '!=', 'tr.taxon_id');
                    $join->on('t.id', '=', 'tr.taxon_id');
                })
                ->leftJoin('flora.taxa as ast', function($join) {
                    $join->on('tr.taxon_id', '=', 'ast.id');
                    $join->on('tr.accepted_id', '!=', 'tr.taxon_id');
                    $join->on('t.id', '=', 'tr.accepted_id');
                })
                ->leftJoin('public.references as r',
                        'tr.source_id', '=', 'r.id')
                ->leftJoin('public.agents as cba',
                        'trv.created_by_id', '=', 'cba.id')
                ->leftJoin('public.agent_type as cbat',
                        'cba.agent_type_id', '=', 'cbat.id')
                ->leftJoin('public.agents as mba',
                        'trv.modified_by_id', '=', 'mba.id')
                ->leftJoin('public.agent_type as mbat',
                        'mba.agent_type_id', '=', 'mbat.id')
                ->orderBy('trv.timestamp_created', 'desc');
    }

    /**
     * Pagination of query result
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  bool $paginate
     * @param  int $pageSize
     * @return Collection
     */
    protected function pagination($query, $paginate=true, $pageSize=20)
    {
        if ($paginate) {
            return $query->paginate($pageSize)
                    ->withPath(env('APP_URL') . '/' . request()->path());
        }
        else {
            return $query->get();
        }
    }

    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  [type] $filter
     * @return \Illuminate\Database\Query\Builder
     */
    protected function filter($query, $filter)
    {
        $query->when(isset($filter['taxonID']), function($query) use ($filter) {
            return $query->where('t.guid', '=', $filter['taxonID']);
        });
        $query->when(isset($filter['isCurrent']) && $filter['isCurrent'] == 'true',
                function($query) {
            return $query->where('tr.is_current_treatment', true);
        });
        return $query;
    }

}
