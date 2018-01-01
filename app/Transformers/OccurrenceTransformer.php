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

namespace App\Transformers;

use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *   definition="Occurrence",
 *   type="object",
 *   required={"id", "catalogNumber", "recordedBy", "recordNumber", "eventID",
 *       "eventDate", "locationID", "country", "countryCode", "stateProvince",
 *       "verbatimLocality", "decimalLongitude", "decimalLatitude"}
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class OccurrenceTransformer extends Fractal\TransformerAbstract {

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="catalogNumber",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="recordedBy",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="recordNumber",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="eventID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="eventDate",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="locationID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="country",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="countryCode",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="stateProvince",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="verbatimLocality",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="decimalLongitude",
     *   type="number",
     *   format="double"
     * ),
     * @SWG\Property(
     *   property="decimalLatitude",
     *   type="number",
     *   format="double"
     * )
     *
     * @param object $occurrence
     * @return array
     */
    public function transform($occurrence)
    {
        return [
            'id' => $occurrence->occurrence_id,
            'catalogNumber' => $occurrence->catalog_number,
            'recordedBy' => $occurrence->recorded_by,
            'recordNumber' => $occurrence->record_number,
            'eventID' => $occurrence->event_id,
            'eventDate' => ($occurrence->end_date) ? $occurrence->start_date .
                    '/' . $occurrence->end_date : $occurrence->start_date,
            'locationID' => $occurrence->location_id,
            'country' => $occurrence->country,
            'countryCode' => $occurrence->country_code,
            'stateProvince' => $occurrence->state_province,
            'verbatimLocality' => $occurrence->locality,
            'decimalLongitude' => $occurrence->decimal_longitude
                ? (double) $occurrence->decimal_longitude : null,
            'decimalLatitude' => $occurrence->decimal_latitude
                ? (double) $occurrence->decimal_latitude : null,
        ];
    }
}
