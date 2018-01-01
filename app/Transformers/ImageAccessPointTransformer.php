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
 * Description of ImageTransformer
 *
 *
 * @SWG\Definition(
 *   definition="AccessPoint",
 *   type="object",
 *   required={"id", "variant", "accessURI", "format", "pixelXDimension",
 *       "pixelYDimension"}
 * )
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class ImageAccessPointTransformer extends Fractal\TransformerAbstract {

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
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
     * @param object $accessPoint
     * @return array
     */
    public function transform($accessPoint)
    {
        return [
            'id' => $accessPoint->access_point_id,
            'variant' => $accessPoint->variant,
            'accessURI' => $accessPoint->access_uri,
            'format' => $accessPoint->format,
            'pixelXDimension' => (int) $accessPoint->pixel_x_dimension,
            'pixelYDimension' => (int) $accessPoint->pixel_y_dimension,
        ];
    }
}
