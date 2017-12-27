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
 * Class Image
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="images")
 */
class Image extends ClassBase {

    /**
     * @var Occurrence
     * @ORM\ManyToOne(targetEntity="Occurrence")
     * @ORM\JoinColumn(name="occurrence_id", referencedColumnName="id")
     */
    protected $occurrence;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(type="string", name="dc_type", length=64)
     */
    protected $type;

    /**
     * @var Subtype
     * @ORM\ManyToOne(targetEntity="Subtype")
     * @ORM\JoinColumn(name="subtype_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $subtype;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $caption;

    /**
     * @var SubjectCategory
     * @ORM\ManyToOne(targetEntity="SubjectCategory")
     * @ORM\JoinColumn(name="subject_category_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $subjectCategory;

    /**
     * @var string
     * @ORM\Column(type="string", name="subject_part", length=64, nullable=true)
     */
    protected $subjectPart;

    /**
     * @var string
     * @ORM\Column(type="string", name="subject_orientation", nullable=true)
     */
    protected $subjectOrientation;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", name="create_date", nullable=true)
     */
    protected $createDate;

    /**
     * @var string
     * @ORM\Column(type="date", name="digitization_date", nullable=true)
     */
    protected $digitizationDate;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=false)
     */
    protected $rightsHolder;

    /**
     * @var License
     * @ORM\ManyToOne(targetEntity="License")
     * @ORM\JoinColumn(name="license_id", referencedColumnName="id")
     */
    protected $license;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $rights;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_hero_image", nullable=true)
     */
    protected $isHeroImage;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $rating;
}
