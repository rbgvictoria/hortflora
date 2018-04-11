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
 * Class Identification
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(indexes={@ORM\Index(columns={"is_current"})})
 */
class Identification extends ClassBase {

    /**
     * @var Occurrence
     * @ORM\ManyToOne(targetEntity="Occurrence")
     * @ORM\JoinColumn(name="occurrence_id", referencedColumnName="id")
     */
    protected $occurrence;

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="identifications")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    protected $image;

    /**
     * @var \App\Entities\Taxon
     * @ORM\ManyToOne(targetEntity="\App\Entities\Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $taxon;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="identified_by_id", referencedColumnName="id")
     */
    protected $identifiedBy;

    /**
     * @var date;
     * @ORM\Column(type="date", name="date_identified", nullable=true)
     */
    protected $dateIdentified;

    /**
     * @var string
     * @ORM\Column(type="text", name="identification_remarks", nullable=true)
     */
    protected $identificationRemarks;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isCurrent;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $verbatimScientificName;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $matchType;
}
