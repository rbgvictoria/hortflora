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

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\ClassBase;

/**
 * Class ImageAccessPoint
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="image_variant_idx",
 *     columns={"image_id", "variant_id"})})
 */
class ImageAccessPoint extends ClassBase {

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="accessPoints")
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
    
    /**
     * 
     * @return \App\Entities\Image
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * 
     * @param \App\Entities\Image $image
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
    }
    
    /**
     * 
     * @return string
     */
    public function getAccessUri()
    {
        return $this->accessUri;
    }
    
    /**
     * 
     * @param string $uri
     */
    public function setAccessUri($uri)
    {
        $this->accessUri = $uri;
    }
    
    /**
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * 
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    /**
     * 
     * @return int
     */
    public function getPixelXDimension()
    {
        return $this->pixelXDimension;
    }
    
    /**
     * 
     * @param int $int
     */
    public function setPixelXDimension($int)
    {
        $this->pixelXDimension = $int;
    }
    
    /**
     * 
     * @return int
     */
    public function getPixelYDimension()
    {
        return $this->pixelYDimension;
    }
    
    /**
     * 
     * @param int $int
     */
    public function setPixelYDimension($int)
    {
        $this->pixelYDimension = $int;
    }
    
    /**
     * 
     * @return \App\Entities\Variant
     */
    public function getVariant()
    {
        return $this->variant;
    }
    
    /**
     * 
     * @param \App\Entities\Variant $variant
     */
    public function setVariant(Variant $variant)
    {
        $this->variant = $variant;
    }
}
