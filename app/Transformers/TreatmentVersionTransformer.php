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

use App\Entities\TreatmentVersion;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of TreatmentVersionTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="TreatmentVersion",
 *   type="object",
 *   required={"isCurrentVersion", "text"}
 * )
 */
class TreatmentVersionTransformer extends OrmTransformer {

    protected $availableIncludes = [];

    protected $defaultIncludes = [];

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
     *   property="isCurrentVersion",
     *   type="boolean"
     * ),
     * @SWG\Property(
     *   property="text",
     *   type="string",
     *   format="html"
     * ),
     * @SWG\Property(
     *   property="created",
     *   type="string",
     *   format="date"
     * ),
     *
     * @param \App\Entities\TreatmentVersion $treatmentVersion
     * @return array
     */
    public function transform(TreatmentVersion $treatmentVersion)
    {
        return [
            'type' => 'TreatmentVersion',
            'id' => $treatmentVersion->getGuid(),
            'isCurrentVersion' => $treatmentVersion->getIsCurrentVersion(),
            'text' => $treatmentVersion->getHtml(),
            'created' => $treatmentVersion->getTimestampCreated()
                ->format('Y-m-d')
        ];
    }

}
