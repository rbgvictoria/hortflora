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

use App\Entities\Event;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Event Transformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * 
 * @SWG\Definition(
 *   definition="Event",
 *   type="object",
 *   required={"eventDate", "location"}
 * )
 */
class EventTransformer extends OrmTransformer {
    
    protected $defaultIncludes = [
        'location',
    ];
    
    /**
     * 
     * @SWG\Property(
     *   property="type",
     *   type="string",
     *   format="uuid"
     * ),
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="eventDate",
     *   type="string"
     * ),
     * @param \App\Entities\Event $event
     */
    public function transform(Event $event)
    {
        return [
            'type' => 'Event',
            'id' => $event->getGuid(),
            'eventDate' => $event->getStartDate() . (($event->getEndDate()) 
                    ? '/' . $event->getEndDate()->format('Y-m-d') : ''),
        ];
    }
    
    /**
     * 
     * @SWG\Property(
     *     property="location",
     *     ref="#/definitions/Location"
     * )
     * @param \App\Entities\Event $event
     * @return \League\Fractal\Resource\Item
     */
    public function includeLocation(Event $event)
    {
        $location = $event->getLocation();
        if ($location) {
            return new Fractal\Resource\Item($location, new LocationTransformer, 
                    'locations');
        }
    }
}
