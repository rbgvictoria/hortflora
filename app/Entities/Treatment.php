<?php

/*
 * Copyright 2017 Niels Klazenga, Royal Botanic Gardens Victoria.
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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Treatment
 * @author Niels Klazenga
 * @ORM\Entity(repositoryClass="TreatmentRepository")
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="treatment_guid_idx", columns={"guid"})
 * })
 */
class Treatment extends ClassBase {

    /**
     * @var Reference|null
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     */
    protected $source;

    /**
     * @var Agent|null
     * @ORM\ManyToOne(targetEntity="Agent")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_current_treatment", nullable=true)
     */
    protected $isCurrentTreatment;
    
    /**
     * @ORM\OneToMany(targetEntity="TreatmentVersion", mappedBy="treatment")
     * @var ArrayCollection
     */
    protected $versions;
    
    /**
     * @ORM\ManyToMany(targetEntity="TaxonAbstract", mappedBy="treatments")
     * @var \Doctrine\Common\Collections\ArrayCollection;
     */
    protected $forTaxon;
    
    /**
     * Taxon for which the treatment was made
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @var \App\Entities\Taxon
     */
    protected $asTaxon;
    
    /**
     * Name of the taxon for which the treatment was made (if taxon is not in 
     * VicFlora/HortFlora)
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $asScientificName;
    

    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->forTaxon = new ArrayCollection();
    }
    
    /**
     * @return \App\Entities\TaxonAbstract
     */
    public function getAsTaxon()
    {
        return $this->asTaxon;
    }

    /**
     * @param \App\Entities\TaxonAbstract $taxon
     */
    public function setAsTaxon(\App\Entities\TaxonAbstract $taxon)
    {
        $this->asTaxon = $taxon;
    }

    /**
     * @return Reference
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Reference $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return Agent
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Agent $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return bool
     */
    public function getIsCurrentTreatment()
    {
        return $this->isCurrentTreatment;
    }

    /**
     * @param bool $isCurrentTreatment
     */
    public function setIsCurrentTreatment($isCurrentTreatment)
    {
        $this->isCurrentTreatment = $isCurrentTreatment;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getVersions()
    {
        return $this->versions;
    }
    
    /**
     * 
     * @param \App\Entities\TreatmentVersion $version
     */
    public function addVersion(TreatmentVersion $version)
    {
        $this->versions[] = $version;
    }
    
    /**
     * 
     * @return \App\Entities\TaxonAbstract
     */
    public function getForTaxon()
    {
        return $this->forTaxon[0];
    }
    
    /**
     * 
     * @param \App\Entities\TaxonAbstract $taxon
     */
    public function setForTaxon(\App\Entities\TaxonAbstract $taxon)
    {
        $this->forTaxon[0] = $taxon;
    }
    
    /**
     * 
     * @return string
     */
    public function getAsScientificName()
    {
        return $this->asScientificName;
    }
    
    /**
     * 
     * @param string $name
     */
    public function setAsScientificName($name)
    {
        $this->asScientificName = $name;
    }
    
    
}
