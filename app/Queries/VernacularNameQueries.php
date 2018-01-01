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
 * VernacularNameModel
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class VernacularNameQueries
{
    /**
     * Gets vernacular names for a taxon
     * @param  Uuid|bool $taxonId [description]
     * @return Collection
     */
    public function getVernacularNames($taxonId=false) {
        return DB::table('flora.vernacular_names as v')
                ->join('flora.taxa as t', 'v.taxon_id', '=', 't.id')
                ->select('v.guid', 't.guid as taxon_id', 'v.vernacular_name',
                        'v.vernacular_name_usage', 'v.is_preferred_name')
                ->when($taxonId, function($query) use ($taxonId) {
                    return $query->where('t.guid', $taxonId);
                })
                ->get();
    }

    /**
     * @param  Uuid $id
     * @return object
     */
    public function getVernacularName($id)
    {
        return DB::table('flora.vernacular_names as v')
                ->join('flora.taxa as t', 'v.taxon_id', '=', 't.id')
                ->select('v.guid', 't.guid as taxon_id', 'v.vernacular_name',
                        'v.vernacular_name_usage', 'v.is_preferred_name')
                ->where('v.guid', $id)
                ->first();
    }

    /**
     * Updates a vernacular name
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateVernacularName($id, $data)
    {
        if ($data['isPreferredName']) {
            DB::table('flora.vernacular_names')
                    ->where('taxon_id', HelperQueries::getPrimaryKeyFromGuid('flora.taxa', $data['taxonID']))
                    ->update(['is_preferred_name' => false]);
        }
        $update = [
            'vernacular_name' => $data['vernacularName'],
            'is_preferred_name' => $data['isPreferredName'],
            'vernacular_name_usage' => $data['vernacularNameUsage'] ?: null,
            'timestamp_modified' => date('Y-m-d H:i:s'),
            'modified_by_id' => AgentQueries::findAgentByUser($data['userId']),
        ];
        DB::table('flora.vernacular_names')
                ->where('guid', $id)
                ->increment('version', 1, $update);
        return 'Record has been updated';
    }

    /**
     * Stores a new vernacular name in the database
     * @param  array $data
     * @return Uuid UUID for the new vernacular name
     */
    public function storeVernacularName($data)
    {
        $guid = Uuid::uuid4();
        if ($data['isPreferredName']) {
            DB::table('flora.vernacular_names')
                    ->where('taxon_id', HelperQueries::getPrimaryKeyFromGuid('flora.taxa', $data['taxonID']))
                    ->update(['is_preferred_name' => false]);
        }
        $insert = [
            'guid' => $guid,
            'taxon_id' => HelperQueries::getPrimaryKeyFromGuid('flora.taxa', $data['taxonID']),
            'vernacular_name' => $data['vernacularName'],
            'is_preferred_name' => $data['isPreferredName'],
            'vernacular_name_usage' => $data['vernacularNameUsage'],
            'created_by_id' => AgentQueries::findAgentByUser($data['userId']),
            'timestamp_created' => date('Y-m-d H:i:s'),
            'version' => 1,
        ];
        DB::table('flora.vernacular_names')->insert($insert);
        return $guid;
    }

    /**
     * Deletes a vernacular name from the database
     * @param  Uuid $id
     * @return Uuid
     */
    public function destroyVernacularName($id)
    {
        DB::table('flora.vernacular_names')
                ->where('guid', $id)
                ->delete();
        return $id;
    }
}
