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

namespace App\Entities\Images;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\ClassBase;

/**
 * Class ImageAccessPoint
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="images",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="image_variant_idx",
 *         columns={"image_id", "variant_id"})})
 */
class ImageAccessPoint extends ClassBase {

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $image;

    /**
     * @var Variant
     * @ORM\ManyToOne(targetEntity="Variant")
     * @ORM\JoinColumn(name="variant_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $variant;

    /**
     * @var string
     * @ORM\Column(name="access_uri", length=128, unique=true)
     */
    protected $accessUri;

    /**
     * @var string
     * @ORM\Column(length=32)
     */
    protected $format;

    /**
     * @var int
     * @ORM\Column(type="integer", name="pixel_x_dimension", nullable=true)
     */
    protected $pixelXDimension;

    /**
     * @var int
     * @ORM\Column(type="integer", name="pixel_y_dimension", nullable=true)
     */
    protected $pixelYDimension;
}
