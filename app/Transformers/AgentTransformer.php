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

use App\Entities\Agent;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Reference Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Agent",
 *   type="object",
 *   required={"agentType", "name"}
 * )
 */
class AgentTransformer extends OrmTransformer {
    
    protected $defaultIncludes = [
        'agentType',
        'organization',
        'group'
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
     *   property="name",
     *   type="string"
     * )
     * @SWG\Property(
     *   property="firstName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="lastName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="ipni",
     *   type="string"
     * ),
     *
     * @param \App\Entities\Agent $agent
     * @return array
     */
    public function transform(Agent $agent)
    {
        return [
            'type' => 'Agent',
            'id' => $agent->getGuid(),
            'name' => $agent->getName(),
            'firstName' => $agent->getFirstName(),
            'lastName' => $agent->getLastName(),
            'ipni' => $agent->getIpni()
        ];
    }
    
    /**
     * @SWG\Property(
     *   property="agentType",
     *   ref="#/definitions/AgentType"
     * )
     * 
     * @param \App\Entities\Agent $agent
     * @return \League\Fractal\Resource\Item
     */
    public function includeAgentType(Agent $agent)
    {
        return new Fractal\Resource\Item($agent->getAgentType(), 
                new AgentTypeTransformer);
    }
    
    /**
     * @SWG\Property(
     *   property="organization",
     *   ref="#/definitions/Agent"
     * )
     * @param Agent $agent
     * @return \League\Fractal\Resource\Item
     */
    public function includeOrganization(Agent $agent) {
        $organization = $agent->getOrganization();
        if ($organization) {
            return new Fractal\Resource\Item($organization, new AgentTransformer);
        }
    }
    
    /**
     * @SWG\Property(
     *   property="group",
     *   ref="#/definitions/Agent"
     * )
     * @param Agent $agent
     * @return \League\Fractal\Resource\Item
     */
    public function includeGroup(Agent $agent) {
        $group = $agent->getGroup();
        if ($group) {
            return new Fractal\Resource\Item($group, new AgentTransformer);
        }
    }
}
