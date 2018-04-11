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

use App\Entities\Change;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of ReferenceTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Change",
 *   type="object",
 *   required={"id", "fromTaxon", "toTaxon", "changeType"}
 * )
 */
class ChangeTransformer extends OrmTransformer {

    protected $availableIncludes = [
        'creator',
        'source'
    ];

    protected $defaultIncludes = [
        'fromTaxon',
        'toTaxon'
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
     *   property="created",
     *   type="string",
     *   format="dateTime"
     * ),
     *
     * @param \App\Entities\Change $change
     * @return array
     */
    public function transform(Change $change)
    {
        return [
            'type' => 'Change',
            'id' => $change->getGuid(),
            'source' => $change->getSource(),
            'changeType' => $change->getChangeType(),
            'created' => $change->getTimestampCreated()->format('Y-m-d H:i:sP')
        ];
    }

    /**
     * @SWG\Property(
     *   property="fromTaxon",
     *   ref="#/definitions/TaxonAbstract"
     * ),
     *
     * @param \app|entities\Change $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeFromTaxon(Change $change)
    {
        $fromTaxon = $change->getFromTaxon();
        return new Fractal\Resource\Item($fromTaxon, new TaxonTransformer, 'taxa');
    }

    /**
     * @SWG\Property(
     *   property="toTaxon",
     *   ref="#/definitions/TaxonAbstract"
     * ),
     *
     * @param \App\Entities\Change $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeToTaxon(Change $change)
    {
        $toTaxon = $change->getToTaxon();
        return new Fractal\Resource\Item($toTaxon, new TaxonTransformer, 'taxa');
    }
    
    /**
     * @SWG\Property(
     *   property="changeType",
     *   ref="#/definitions/ChangeType"
     * ),
     * 
     * @param \App\Entities\Change $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeChangeType(Change $change)
    {
        return new Fractal\Resource\Item($change->getChangeType(), 
                new ChangeTypeTransformer);
    }

    /**
     * @SWG\Property(
     *   property="creator",
     *   ref="#/definitions/Agent"
     * ),
     *
     * @param \App\Entities\Change $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeCreator(Change $change)
    {
        $creator = $change->getCreatedBy();
        return new Fractal\Resource\Item($creator, new AgentTransformer, 'agents');
    }
    
    /**
     * @SWG\Property(
     *   property="source",
     *   ref="#/definitions/Reference"
     * ),
     * 
     * @param \App\Entities\Change $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeSource(Change $change)
    {
        return new Fractal\Resource\Item($change->getSource(), 
                new ReferenceTransformer);
    }
}
