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

use App\Entities\ClassBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Image
 * @author Niels Klazenga
 * @ORM\Entity(repositoryClass="ImageRepository")
 * @ORM\Table()
 */
class Image extends ClassBase {

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var \App\Entities\Occurrence
     * @ORM\ManyToOne(targetEntity="Occurrence")
     * @ORM\JoinColumn(name="occurrence_id", referencedColumnName="id")
     */
    protected $occurrence;

    /**
     * @ORM\ManyToMany(targetEntity="TaxonAbstract", mappedBy="images")
     * @var \Doctrine\Common\Collections\ArrayCollection;
     */
    protected $taxa;

    /**
     * Scientific name as given on the image, matched to a name in VicFlora/HortFlora
     * @ORM\ManyToOne(targetEntity="Name")
     * @var \App\Entities\Name
     */
    protected $scientificName;

    /**
     * Verbatim scientific name as given on the image
     * @ORM\Column(nullable=true);
     * @var string
     */
    protected $verbatimScientificName;

    /**
     * @var \App\Entities\Reference
     * @ORM\ManyToOne(targetEntity="\App\Entities\Reference")
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $dcSource;

    /**
     * @var string
     * @ORM\Column(type="string", name="dc_type", length=64)
     */
    protected $type;

    /**
     * @var \App\Entities\Subtype
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
     * @var \App\Entities\SubjectCategory
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
     * @var \DateTime
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
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id", nullable=true)
     */
    protected $provider;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="rights_holder_id", referencedColumnName="id", nullable=true)
     */
    protected $rightsHolder;

    /**
     * @var \App\Entities\License
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

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Identification", mappedBy="image")
     */
    protected $identifications;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="ImageAccessPoint", mappedBy="image")
     */
    protected $accessPoints;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="Feature")
     * @ORM\JoinTable(name="images_features",
     *   joinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="feature_id", referencedColumnName="id")}
     * )
     */
    protected $features;

    public function __construct()
    {
        $this->taxa = new ArrayCollection();
        $this->identifications = new ArrayCollection();
        $this->accessPoints = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $occurrence
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Occurrence
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * @param \App\Entities\Occurrence $occurrence
     */
    public function setOccurrence($occurrence)
    {
        $this->occurrence = $occurrence;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTaxa()
    {
        return $this->taxa;
    }

    /**
     *
     * @param \App\Entities\TaxonAbstract $taxon
     */
    public function addTaxon(\App\Entities\TaxonAbstract $taxon)
    {
        $this->taxa[] = $taxon;
    }

    /**
     *
     * @return \App\Entities\ScientificName
     */
    public function getScientificName()
    {
        return $this->scientificName;
    }

    /**
     *
     * @param \App\Entities\Name $name
     */
    public function setScientificName(Name $name)
    {
        $this->scientificName = $name;
    }

    /**
     *
     * @return string
     */
    public function getVerbatimScientificName()
    {
        return $this->verbatimScientificName;
    }

    /**
     *
     * @param string $name
     */
    public function setVerbatimScientificName($name)
    {
        $this->verbatimScientificName = $name;
    }

    /**
     *
     * @return \App\Entities\Reference
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @param \App\Entities\Reference $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \App\Entities\Subtype
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @param \App\Entities\Subtype $subtype
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     *
     * @return SubjectCategory
     */
    public function getSubjectCategory()
    {
        return $this->subjectCategory;
    }

    /**
     *
     * @param \App\Entities\SubjectCategory $subjectCategory
     */
    public function setSubjectCategory($subjectCategory)
    {
        $this->subjectCategory = $subjectCategory;
    }

    /**
     * @return string
     */
    public function getSubjectPart()
    {
        return $this->subjectPart;
    }

    /**
     * @param string $subjectPart
     */
    public function setSubjectPart($subjectPart)
    {
        $this->subjectPart = $subjectPart;
    }

    /**
     * @return string
     */
    public function getSubjectOrientation()
    {
        return $this->subjectOrientation;
    }

    /**
     * @param string $subjectOrientation
     */
    public function setSubjectOrientation($subjectOrientation)
    {
        $this->subjectOrientation = $subjectOrientation;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return \DateTime
     */
    public function getDigitizationDate()
    {
        return $this->digitizationDate;
    }

    /**
     * @param \DateTime $digitizationDate
     */
    public function setDigitizationDate($digitizationDate)
    {
        $this->digitizationDate = $digitizationDate;
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param \App\Entities\Agent $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param \App\Entities\Agent $creator
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getRightsHolder()
    {
        return $this->rightsHolder;
    }

    /**
     * @param \App\Entities\Agent $rightsHolder
     */
    public function setRightsHolder($rightsHolder)
    {
        $this->rightsHolder = $rightsHolder;
    }

    /**
     * @return License
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param \App\Entities\Licence $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }

    /**
     * @return string
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param string $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    /**
     * @return bool
     */
    public function getIsHeroImage()
    {
        return $this->isHeroImage;
    }

    /**
     * @param bool $isHeroImage
     */
    public function setIsHeroImage($isHeroImage)
    {
        $this->isHeroImage = $isHeroImage;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getIdentifications()
    {
        return $this->identifications;
    }

    /**
     *
     * @param \App\Entities\Identification $identification
     */
    public function addIdentification(Identification $identification)
    {
        $this->identifications[] = $identification;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAccessPoints()
    {
        return $this->accessPoints;
    }

    /**
     * @param \App\Entities\ImageAccessPoint $accessPoint
     */
    public function addAccessPoint(ImageAccessPoint $accessPoint)
    {
        $this->accessPoints[] = $accessPoint;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     *
     * @param \App\Entities\Feature $feature
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
    }
}
