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

use App\Entities\TaxonReference;
use League\Fractal;

/**
 * Description of TaxonReferenceTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * @SWG\Definition(
 *   definition="TaxonCitation",
 *   type="object",
 *   required={"reference"}
 * )
 */
class TaxonReferenceTransformer extends Fractal\TransformerAbstract {
    
    protected $defaultIncludes = ['reference'];
    
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
     *   property="page",
     *   type="string",
     *   description="Page in the reference where the taxon is cited"
     * ),
     * @SWG\Property(
     *   property="dateAccessed",
     *   type="string",
     *   format="date",
     *   description="Date a web page was accessed"
     * ),
     * @param TaxonReference $taxonReference
     * @return array
     */
    public function transform(TaxonReference $taxonReference)
    {
        return [
            'type' => 'TaxonReference',
            'id' => $taxonReference->getGuid(),
            'page' => $taxonReference->getPage(),
            'dateAccessed' => $taxonReference->getDateAccessed()
        ];
    }
    
    /**
     * 
     * @SWG\Property(
     *   property="reference",
     *   ref="#/definitions/Reference"
     * )
     * @param TaxonReference $taxonReference
     * @return \League\Fractal\Resource\Item
     */
    public function includeReference(TaxonReference $taxonReference)
    {
        return new Fractal\Resource\Item($taxonReference->getReference(), 
                new ReferenceTransformer, 'references');
    }
}
