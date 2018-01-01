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
 * Name Transformr
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Name",
 *   type="object",
 *   required={"id", "scientificName"}
 * )
 */
class NameTransformer extends Fractal\TransformerAbstract {

    protected $availableIncludes = [
        'namePublishedIn',
        'nameType',
    ];

    protected $defaultIncludes = [
    ];

    /**
     * @param \stdClass $name
     * @return array
     *
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificNameAuthorship",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="namePart",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="nomenclaturalNote",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="created",
     *   type="string",
     *   format="dateTime"
     * ),
     * @SWG\Property(
     *   property="modified",
     *   type="string",
     *   format="dateTime"
     * ),
     */
    public function transform($name)
    {
        return [
            'id' => $name->guid,
            'scientificName' => $name->full_name,
            'scientificNameAuthorship' => $name->authorship,
            'namePart' => $name->name,
            'nomenclaturalNote' => $name->nomenclatural_note,
            'created' => $name->timestamp_created,
            'modified' => $name->timestamp_modified
        ];
    }

    /**
    * @SWG\Property(
    *   property="nameType",
    *   ref="#/definitions/NameType"
    * )
     * @param  \stdClass $name [description]
     * @return Fractal\Resource\Item
     */
    public function includeNameType($name)
    {
        return new Fractal\Resource\Item((object) [
            'uri' => $name->name_type_uri,
            'name' => $name->name_type_name,
            'label' => $name->name_type_label
        ], new NameTypeTransformer, 'nameTypes');
    }

}
