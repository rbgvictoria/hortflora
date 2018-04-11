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

use App\Entities\Occurrence;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *   definition="Occurrence",
 *   type="object"
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class OccurrenceTransformer extends OrmTransformer {
    
    protected $defaultIncludes = [
        'event'
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
     *   property="catalogNumber",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="recordedBy",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="recordNumber",
     *   type="string"
     * )
     *
     * @param \App\Entities\Occurrence $occurrence
     * @return array
     */
    public function transform(Occurrence $occurrence)
    {
        return [
            'type' => 'Occurrence',
            'id' => $occurrence->getGuid(),
            'catalogNumber' => $occurrence->getCatalogNumber(),
            'recordedBy' => $occurrence->getRecordedBy()->getName(),
            'recordNumber' => $occurrence->getRecordNumber(),
        ];
    }
    
    /**
     * 
     * @SWG\Property(
     *     property="event",
     *     ref="#/definitions/Event"
     * )
     * @param \App\Entities\Occurrence $occurrence
     */
    public function includeEvent(Occurrence $occurrence) 
    {
        $event = $occurrence->getEvent();
        if ($event) {
            return new Fractal\Resource\Item($event, new EventTransformer, 'events');
        }
    }
}
