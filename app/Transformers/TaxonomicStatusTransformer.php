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
 * Taxonomic Status Transformer
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="TaxonomicStatus",
 *   type="object",
 *   required={"id", "name"}
 * )
 */
class TaxonomicStatusTransformer extends Fractal\TransformerAbstract
{

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
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
     * @param  \stdClass $taxonomicStatus
     * @return array
     */
    public function transform($taxonomicStatus)
    {
        return [
            'id' => $taxonomicStatus->uri,
            'name' => $taxonomicStatus->name,
            'label' => $taxonomicStatus->label
        ];
    }
}
