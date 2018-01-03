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

/**
 * Change Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class DistributionQueries {

    /**
     * [getDistributionForTaxon description]
     * @param  Uuid $id UUID of the taxon
     * @return Collection
     */
    public static function getDistributionForTaxon($id)
    {
        return DB::table('flora.taxa as t')
                ->join('flora.distribution as d', 't.id', '=', 'd.taxon_id')
                ->join('flora.regions as r', 'd.region_id', '=', 'r.id')
                ->where('t.guid', $id)
                ->select('r.guid', 'r.code', 'r.name', 'r.full_name')
                ->get();
    }


}
