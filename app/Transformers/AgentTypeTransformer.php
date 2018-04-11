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

use App\Entities\AgentType;

/**
 * @SWG\Definition(
 *   definition="AgentType",
 *   type="object",
 *   required={"name", "label"}
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class AgentTypeTransformer extends VocabularyTermTransformer {
    /**
     * @SWG\Property(
     *   property="type",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="id",
     *   type="string",
     *   format="uri"
     * ),
     * @SWG\Property(
     *   property="name",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="label",
     *   type="string"
     * )
     *
     * @param  \App\Entities\AgentType $agentType
     * @return array
     */
    public function transform(AgentType $agentType)
    {
        return parent::transform($agentType);
    }
}
