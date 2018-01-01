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
class ChangeTransformer extends Fractal\TransformerAbstract {

    protected $availableIncludes = [
        'creator',
    ];

    protected $defaultIncludes = [
        'fromTaxon',
        'toTaxon'
    ];

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="source",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="changeType",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="created",
     *   type="string",
     *   format="dateTime"
     * ),
     *
     * @param object $change
     * @return array
     */
    public function transform($change)
    {
        return [
            'id' => $change->guid,
            'source' => $change->source,
            'changeType' => $change->change_type,
            'created' => $change->timestamp_created,
        ];
    }

    /**
     * @SWG\Property(
     *   property="fromTaxon",
     *   ref="#/definitions/Taxon"
     * ),
     *
     * @param object $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeFromTaxon($change)
    {
        $transformer = new TaxonIncludeTransformer();
        $fromTaxon = (object) [
            'guid' => $change->from_taxon,
            'rank' => $change->from_rank,
            'full_name' => $change->from_full_name
        ];
        return new Fractal\Resource\Item($fromTaxon, $transformer, 'taxa');
    }

    /**
     * @SWG\Property(
     *   property="toTaxon",
     *   ref="#/definitions/Taxon"
     * ),
     *
     * @param object $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeToTaxon($change)
    {
        $transformer = new TaxonIncludeTransformer();
        $toTaxon = (object) [
            'guid' => $change->to_taxon,
            'rank' => $change->to_rank,
            'full_name' => $change->to_full_name
        ];
        return new Fractal\Resource\Item($toTaxon, $transformer, 'taxa');
    }

    /**
     * @SWG\Property(
     *   property="creator",
     *   ref="#/definitions/Agent"
     * ),
     *
     * @param object $change
     * @return \League\Fractal\Resource\Item
     */
    protected function includeCreator($change)
    {
        if ($change->created_by_agent_guid) {
            $transformer = new AgentTransformer();
            $createdBy = (object) [
                'guid' => $change->created_by_agent_guid,
                'agent_type' => $change->created_by_agent_type,
                'name' => $change->created_by_agent_name,
                'first_name' => $change->created_by_agent_first_name,
                'last_name' => $change->created_by_agent_last_name
            ];
            return new Fractal\Resource\Item($createdBy, $transformer, 'agents');
        }
    }
}
