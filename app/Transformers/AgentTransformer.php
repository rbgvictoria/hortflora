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

use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Reference Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Agent",
 *   type="object",
 *   required={"id", "agentType", "name"}
 * )
 */
class AgentTransformer extends Fractal\TransformerAbstract {

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="agentType",
     *   type="string"
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
     *
     * @param object $agent
     * @return array
     */
    public function transform($agent)
    {
        $ret = [
            'id' => $agent->guid,
            'agentType' => $agent->agent_type,
            'name' => $agent->name,
        ];
        if (isset($agent->first_name)) {
            $ret['firstName'] = $agent->first_name;
        }
        if (isset($agent->last_name)) {
            $ret['lastName'] = $agent->last_name;
        }
        return $ret;
    }
}
