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

use League\Fractal;

/**
 * Description of SolariumTransformer
 *
 * @SWG\Definition(
 *   definition="SearchResult",
 *   type="object"
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolariumTransformer extends Fractal\TransformerAbstract {
    
    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="taxonRank",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificNameID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificNameAuthorship",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="namePublishedInID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="namePublishedIn",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="namePublishedInYear",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="nameAccordingTo",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="kingdom",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="phylum",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="class",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="order",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="family",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="genus",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="specificEpithet",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="infraspecificEpithet",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="parentNameUsageID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="parentNameUsage",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="acceptedNameUsageID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="acceptedNameUsage",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="nomenclaturalStatus",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="taxonomicStatus",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="taxonRemarks",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="vernacularName",
     *   type="string"
     * )
     * 
     * @param type $rec
     * @return type
     */
    public function transform($rec) {
        $ret = [];
        foreach ($rec as $key => $value) {
            $ret[camel_case($key)] = $value;
        }
        return $transformed;
    }
}
