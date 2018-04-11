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
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *     @ORM\Index(columns={"cumulus_catalogue"}),
 *     @ORM\Index(columns={"cumulus_record_id"}),
 * })
 */
class CumulusImage extends ClassBase {

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    protected $image;

    /**
     * @var int
     * @ORM\Column(type="integer", name="cumulus_record_id", nullable=true)
     */
    protected $cumulusRecordID;

    /**
     * @var string
     * @ORM\Column(type="string", name="cumulus_catalogue", length=64, nullable=true)
     */
    protected $cumulusCatalogue;

    /**
     * @ORM\Column(type="string", name="cumulus_record_name", length=64, nullable=true)
     * @var string
     */
    protected $cumulusRecordName;


    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="cumulus_modified", nullable=true)
     */
    protected $cumulusModified;

    /**
     * @ORM\Column(type="integer", name="pixel_x_dimension", nullable=true)
     * @var int
     */
    protected $pixelXDimension;

    /**
     * @ORM\Column(type="integer", name="pixel_y_dimension", nullable=true)
     * @var int
     */
    protected $pixelYDimension;
}
