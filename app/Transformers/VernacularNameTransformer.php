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

use App\Entities\VernacularName;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of VernacularNameTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="VernacularName",
 *   type="object",
 *   required={"vernacularName", "taxon"}
 * )
 */
class VernacularNameTransformer extends Fractal\TransformerAbstract {

    protected $defaultIncludes = [];

    protected $availableIncludes = [
        'taxon'
    ];

    /**
     * @SWG\Property(
     *   property="type",
     *   type="string"
     * ),
     * @SWG\Property(
     *     property="id",
     *     type="string",
     *     format="uuid"
     * ),
     * @SWG\Property(
     *     property="vernacularName",
     *     type="string"
     * ),
     * @SWG\Property(
     *     property="vernacularNameUsage",
     *     type="string"
     * ),
     * @SWG\Property(
     *     property="isPreferredName",
     *     type="string"
     * ),
     *
     * @param \App\Entities\VernacularName $vernacularName
     * @return array
     */
    public function transform(VernacularName $vernacularName)
    {
        return [
            'type' => 'VernacularName',
            'id' => $vernacularName->getGuid(),
            'vernacularName' => $vernacularName->getVernacularName(),
            'vernacularNameUsage' => $vernacularName->getVernacularNameUsage(),
            'isPreferredName' => $vernacularName->getIsPreferredName(),
        ];
    }

    /**
     * @SWG\Property(
     *     property="taxon",
     *     ref="#/definitions/TaxonAbstract"
     * )
     *
     * @param \App\Entities\VernacularName $vernacularName
     * @return \League\Fractal\Resource\Item
     */
    protected function includeTaxon(VernacularName $vernacularName)
    {
        $taxon = $vernacularName->getTaxon();
        return new Fractal\Resource\Item($taxon, new TaxonTransformer, 'taxa');
    }
}
