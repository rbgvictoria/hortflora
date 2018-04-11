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

namespace App\Transformers;

use App\Entities\HorticulturalGroup;
use League\Fractal;

/**
 * Horticultural Group Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * @SWG\Definition(
 *   definition="HorticulturalGroup",
 *   description="Extends TaxonAbstract definition with properties specific to Horticultural groups",
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/TaxonAbstract"),
 *     @SWG\Schema(
 *       required={"taxon"}
 *     )
 *   }
 * )
 */
class HorticulturalGroupTransformer extends TaxonAbstractTransformer {

    
    public function __construct() 
    {
        $this->availableIncludes = array_merge($this->availableIncludes, [
            'cultivars',
        ]);
        
        $this->defaultIncludes = array_merge($this->defaultIncludes, [
            'taxon',
        ]);
    }
    
    /**
     * 
     * @SWG\Property(
     *   property="taxon",
     *   ref="#/definitions/Taxon"
     * )
     * @param \App\Entities\HorticulturalGroup $horticulturalGroup
     * @return \League\Fractal\Resource\Item
     */
    public function includeTaxon(HorticulturalGroup $horticulturalGroup)
    {
        return new Fractal\Resource\Item($horticulturalGroup->getTaxon(), 
                new TaxonTransformer, 'taxa');
    }
    
    /**
     * 
     * @SWG\Property(
     *   property="members",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/TaxonAbstract"
     *   )
     * )
     * @param \App\Entities\HorticulturalGroup $horticulturalGroup
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(HorticulturalGroup $horticulturalGroup)
    {
        return new Fractal\Resource\Collection($horticulturalGroup->getCultivars(), 
                new TaxonAbstractTransformer);
    }
}
