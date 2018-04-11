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

use App\Entities\Name;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Name Transformr
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Name",
 *   type="object",
 *   required={"scientificName", "nameType"}
 * )
 */
class NameTransformer extends Fractal\TransformerAbstract {

    protected $availableIncludes = [
        'nameType',
    ];

    protected $defaultIncludes = [];

    /**
     * @param \stdClass $name
     * @return array
     *
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
     * 
     * @param \App\Entities\Name $name
     */
    public function transform(Name $name)
    {
        return [
            'type' => 'Name',
            'id' => $name->getGuid(),
            'scientificName' => $name->getFullName(),
            'scientificNameAuthorship' => $name->getAuthorship(),
            'namePart' => $name->getName(),
            'nomenclaturalNote' => $name->getNomenclaturalNote(),
        ];
    }

    /**
    * @SWG\Property(
    *   property="nameType",
    *   ref="#/definitions/NameType"
    * )
     * @param  \App\Entities\Name $name [description]
     * @return Fractal\Resource\Item
     */
    public function includeNameType($name)
    {
        $type = $name->getNameType();
        if ($type) {
            return new Fractal\Resource\Item($type, new NameTypeTransformer, 'nameTypes');
        }
    }

}
