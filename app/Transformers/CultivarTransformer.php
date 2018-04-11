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

use App\Entities\Cultivar;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Cultivar Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * @SWG\Definition(
 *   definition="Cultivar",
 *   description="Extends TaxonAbstract definition with properties specific to cultivars",
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/TaxonAbstract"),
 *     @SWG\Schema(
 *       required={"taxon"},
 *     )
 *   }
 * )
 */
class CultivarTransformer extends TaxonAbstractTransformer
{
    
    public function __construct() {
        $this->defaultIncludes = array_merge($this->defaultIncludes, [
            'taxon',
            'horticulturalGroup',
        ]);
    }
    
    /**
     * @SWG\Property(
     *   property="taxon",
     *   description="A Cultivar has to belong to a Taxon",
     *   ref="#/definitions/Taxon"
     * ),
     * 
     * @param \App\Entities\Cultivar $cultivar
     */
    public function includeTaxon(Cultivar $cultivar)
    {
        return new Fractal\Resource\Item($cultivar->getTaxon(), 
                new TaxonTransformer);
    }

    /**
     * 
     * @SWG\Property(
     *   property="horticulturalGroup",
     *   ref="#/definitions/HorticulturalGroup"
     * )
     * @param  \App\Entities\Cultivar $cultivar [description]
     * @return Fractal\Resource\Item
     */
    protected function includeHorticulturalGroup(Cultivar $cultivar)
    {
       $horticulturalGroup = $cultivar->getHorticulturalGroup();
       if ($horticulturalGroup) {
           return new Fractal\Resource\Item($horticulturalGroup, 
                   new HorticulturalGroupTransformer);
       }
    }
    
    /**
     * @SWG\Property(
     *   property="hybridParent1",
     *   ref="#/definitions/Taxon"
     * )
     * 
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHybridParent1(Taxon $taxon)
    {
        $hybridParent1 = $hybrid->getHybridParent1();
        if ($hybridParent1) {
            return new Fractal\Resource\Item($hybridParent1, 
                    new TaxonTransformer, 'taxa');
        }
    }
    
    /**
     * @SWG\Property(
     *   property="hybridParent2",
     *   ref="#/definitions/Taxon"
     * )
     * 
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHybridParent2(Taxon $taxon)
    {
        $hybridParent2 = $hybrid->getHybridParent2();
        if ($hybridParent2) {
            return new Fractal\Resource\Item($hybridParent2, 
                    new TaxonTransformer, 'taxa');
        }
    }

}
