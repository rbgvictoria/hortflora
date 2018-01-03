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
 * Cultivar Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * @SWG\Definition(
 *   definition="Cultivar",
 *   type="object",
 *   required={"id", "scientificName"}
 * )
 */
class CultivarTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
       'cultivarGroup',
    ];

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="description",
     *   type="string"
     * ),
     * @param  object $cultivar
     * @return array
     */
    public function transform($cultivar)
    {
       return [
           'id' => $cultivar->guid,
           'scientificName' => $cultivar->full_name,
           'description' => $cultivar->description,
       ];
    }

    /**
     * @param  object $cultivar [description]
     * @return Fractal\Resource\Item
     */
    protected function includeCultivarGroup($cultivar)
    {
       if ($cultivar->cultivar_group_id) {
           return new Fractal\Resource\Item((object) [
               'guid' => $cultivar->cultivar_group_id,
               'full_name' => $cultivar->cultivar_group,
               'cultivar_group_description' => $cultivar->cultivar_group_description
           ], new CultivarGroupTransformer, 'cultivar-groups');
       }
    }
}
