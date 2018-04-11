<?php

/*
 * Copyright 2018 Royal Botanic Gardens Victoria.
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

use App\Entities\Location;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of LocationTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * 
 * @SWG\Definition(
 *   definition="Location",
 *   type="object",
 *   required={"country", "stateProvince", "verbatimLocality"}
 * )
 */
class LocationTransformer extends Fractal\TransformerAbstract {
    
    /**
     * 
     * @SWG\Property(
     *   property="type",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="id",
     *   type="string",
     *   format="uuid"
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
     * @param \App\Entities\Location $location
     * @return array
     */
    public function transform(Location $location)
    {
        return [
            'type' => 'Location',
            'id' => $location->getGuid(),
            'country' => $location->getCountry(),
            'countryCode' => $location->getCountryCode(),
            'stateProvince' => $location->getStateProvince(),
            'verbatimLocality' => $location->getLocality(),
            'decimalLongitude' => $location->getDecimalLatitude()
               ? (double) $location->getDecimalLatitude() : null,
            'decimalLatitude' => $location->getDecimalLongitude()
               ? (double) $location->getDecimalLongitude() : null,
        ];
    }
}
