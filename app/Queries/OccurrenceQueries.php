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

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Description of OccurrenceModel
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class OccurrenceQueries {

    /**
     * @param  Uuid $id
     * @return object
     */
    public function getOccurrence($id)
    {
        return DB::table('images.occurrences as o')
                ->select('o.guid as occurrence_id', 'a.name as recorded_by',
                        'o.catalog_number', 'o.record_number',
                        'e.guid as event_id', 'e.start_date', 'e.end_date',
                        'l.guid as location_id', 'l.country', 'l.country_code',
                        'l.state_province', 'l.locality', 'l.decimal_longitude',
                        'l.decimal_latitude')
                ->leftJoin('images.events as e', 'o.event_id', '=', 'e.id')
                ->leftJoin('images.locations as l',
                        'e.location_id', '=', 'l.id')
                ->leftJoin('agents as a', 'o.recorded_by_id', '=', 'a.id')
                ->where('o.guid', $id)
                ->first();
    }
}
