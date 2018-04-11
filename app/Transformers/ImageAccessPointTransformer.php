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

use App\Entities\ImageAccessPoint;
use Swagger\Annotations as SWG;

/**
 * Image Transformer
 *
 *
 * @SWG\Definition(
 *   definition="AccessPoint",
 *   type="object",
 *   required={"variant", "accessURI", "format", "pixelXDimension",
 *       "pixelYDimension"}
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class ImageAccessPointTransformer extends OrmTransformer {

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
     *   property="variant",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="accessURI",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="format",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="pixelXDimension",
     *   type="integer",
     *   format="int64"
     * ),
     * @SWG\Property(
     *   property="pixelYDimension",
     *   type="integer",
     *   format="int64"
     * )
     *
     * @param \App\Entities\ImageAccessPoint $accessPoint
     * @return array
     */
    public function transform(ImageAccessPoint $accessPoint)
    {
        return [
            'type' => 'AccessPoint',
            'id' => $accessPoint->getGuid(),
            'variant' => $accessPoint->getVariant()->getName(),
            'accessURI' => $accessPoint->getAccessUri(),
            'format' => $accessPoint->getFormat(),
            'pixelXDimension' => (int) $accessPoint->getPixelXDimension(),
            'pixelYDimension' => (int) $accessPoint->getPixelYDimension(),
        ];
    }
}
