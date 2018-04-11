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

use League\Fractal;

/**
 * Vocabulary Term Transformer
 *
 * @SWG\Definition(
 *   definition="VocabularyTerm",
 *   type="object",
 *   required={"name", "label"}
 * )
 * 
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class VocabularyTermTransformer extends Fractal\TransformerAbstract {
    
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
     * @param object $entity
     * @return array
     */
    public function transform($entity)
    {
        $class = \get_class($entity);
        $type = substr($class, strrpos($class, '\\')+1);
        $vocabulary = camel_case($type);
        return [
            'type' => $type,
            'id' => env('APP_URL') . '/api/vocabularies/' . $vocabulary 
                . '/terms/' . $entity->getName(),
            'name' => $entity->getName(),
            'label' => $entity->getLabel()
        ];
    }
    
    /**
     * Transforms arrays
     * 
     * @param array $array
     * @return array
     */
    public function transformArray($array)
    {
        return [
            'id' => $array['uri'],
            'name' => $array['name'],
            'label' => $array['label']
        ];
    }
}
