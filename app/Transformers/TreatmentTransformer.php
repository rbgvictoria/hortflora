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

use App\Entities\Treatment;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of ReferenceTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Treatment",
 *   type="object",
 *   required={"versions"},
 *   example=
 *   {
 *     "versions": {
 *       {
 *         "text": "<p class=""description"">Erect herb to 0.5 m tall with ribbed stems. Leaves bunched together, narrow, obovate to elliptic, about 2 cm long, 0.5 cm wide, woolly-hairy below, margins curled under, entire or with a few teeth. Flowers solitary or in few-flowered clusters, each about 1 cm long with a few grey hairs; spring.</p><p class=""note"">WA</p>",
 *         "isUpdated": true
 *       }
 *     }
 *   }
 * )
 */
class TreatmentTransformer extends OrmTransformer {

    protected $availableIncludes = [
        'forTaxon',
        'asTaxon',
        'acceptedNameUsage',
        'currentVersion',
        'versions'
    ];

    protected $defaultIncludes = [
        'author',
        'source'
    ];

    /**
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
     *   property="isCurrent",
     *   type="boolean"
     * ),
     *
     * @param \App\Entities\Treatment $treatment
     * @return array
     */
    public function transform(Treatment $treatment)
    {
        return [
            'type' => 'TaxonTreatment',
            'id' => $treatment->getGuid(),
            'isCurrent' => $treatment->getIsCurrentTreatment(),
        ];
    }

    /**
     * @SWG\Property(
     *   property="asTaxon",
     *   ref="#/definitions/TaxonAbstract"
     * ),
     *
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Item
     */
    protected function includeAsTaxon(Treatment $treatment)
    {
        if ($asTaxon = $treatment->getAsTaxon()) {
            return new Fractal\Resource\Item($asTaxon, 
                    new TaxonTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="forTaxon",
     *   ref="#/definitions/TaxonAbstract"
     * ),
     *
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Item
     */
    protected function includeForTaxon(Treatment $treatment)
    {
        if ($forTaxon = $treatment->getForTaxon()) {
            return new Fractal\Resource\Item($forTaxon, 
                    new TaxonTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="acceptedNameUsage",
     *   ref="#/definitions/TaxonAbstract"
     * ),
     *
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Item
     */
    protected function includeAcceptedNameUsage(Treatment $treatment)
    {
        $acceptedNameUsage = $treatment->getForTaxon()->getAcceptedNameUsage();
        if ($acceptedNameUsage) {
            return new Fractal\Resource\Item($acceptedNameUsage, 
                    new TaxonTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="source",
     *   ref="#/definitions/Reference"
     * ),
     *
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Item
     */
    protected function includeSource(Treatment $treatment)
    {
        $source = $treatment->getSource();
        if ($source) {
            return new Fractal\Resource\Item($source, new ReferenceTransformer, 
                    'references');
        }
    }
    
    /**
     * @SWG\Property(
     *   property="currentVersion",
     *   ref="#/definitions/TreatmentVersion"
     * ),
     * 
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Item
     */
    protected function includeCurrentVersion(Treatment $treatment)
    {
        $currentVersion = $treatment->getVersions()->filter(function($version) {
            return $version->getIsCurrentVersion();
        })->first();
        return new Fractal\Resource\Item($currentVersion, 
                new TreatmentVersionTransformer);
    }
    
    
    /**
     * @SWG\Property(
     *   property="versions",
     *     type="array",
     *     @SWG\Items(
     *       ref="#/definitions/TreatmentVersion"
     *     )
     * ),
     * 
     * @param \App\Entities\Treatment $treatment
     * @return \League\Fractal\Resource\Collection
     */
    protected function includeVersions(Treatment $treatment)
    {
        $versions = $treatment->getVersions();
        return new Fractal\Resource\Collection($versions, 
                new TreatmentVersionTransformer);
    }
    
    protected function includeAuthor(Treatment $treatment)
    {
        $author = $treatment->getAuthor();
        if ($author) {
            return new Fractal\Resource\Item($author, new AgentTransformer);
        }
    }
}
