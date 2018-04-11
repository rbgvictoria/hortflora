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
 * Key Transformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Key",
 *   type="object",
 *   required={"id", "title"}
 * )
 */
class KeyTransformer extends Fractal\TransformerAbstract {

    /**
     * @SWG\Property(
     *   property="type",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="id",
     *   type="string",
     *   format="uri",
     *   description="URL to the JSON representation of the key"
     * ),
     * @SWG\Property(
     *   property="title",
     *   type="string",
     *   description="Title of the key"
     * ),
     * @SWG\Property(
     *   property="taxonomicScope",
     *   type="string",
     *   description="Taxonomic Group to which the key applies"
     * ),
     * @SWG\Property(
     *   property="geographicScope",
     *   type="string",
     *   description="Geographic area in which the key applies"
     * ),
     *
     * @param object $key
     * @return array
     */
    public function transform($key)
    {
        return [
            'type' => 'Key',
            'id' => 'http://data.rbg.vic.gov.au/keybase-ws/ws/key_get/' . $key->key_id,
            'title' => $key->key_title,
            'taxonomicScope' => $key->taxonomic_scope,
            'geographicScope' => $key->geographic_scope,
        ];
    }
}
