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

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of TaxonAbstract
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * 
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"abstract"="TaxonAbstract", "taxon"="Taxon", 
 *     "cultivar"="Cultivar", "horticulturalGroup"="HorticulturalGroup"})
 * @ORM\Table(name="taxa")
 */
class TaxonAbstract extends ClassBase {
    
    /**
     * http://rs.tdwg.org/dwc/terms/scientificNameID
     *
     * An identifier for the nomenclatural details of a scientific name.
     *
     * @var Name
     * @ORM\ManyToOne(targetEntity="Name")
     * @ORM\JoinColumn(name="name_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $name;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonRank
     *
     * The taxonomic rank of the most specific name in the scientificName.
     *
     * @var TaxonRank
     * @ORM\ManyToOne(targetEntity="TaxonRank")
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id",
     *   nullable=true)
     */
    protected $taxonRank;

    /**
     * http://rs.tdwg.org/dwc/terms/nameAccordingToID
     *
     * An identifier for the source in which the specific taxon concept
     * circumscription is defined or implied. See nameAccordingTo:
     * The reference to the source in which the specific taxon concept
     * circumscription is defined or implied - traditionally signified by the
     * Latin "sensu" or "sec."
     *
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="name_according_to_id", referencedColumnName="id")
     */
    protected $nameAccordingTo;

    /**
     * http://rs.tdwg.org/dwc/terms/acceptedNameUsageID
     *
     * An identifier for the name usage of the direct, most proximate
     * higher-rank parent taxon (in a classification) of the most specific
     * element of the scientificName.
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="accepted_id", referencedColumnName="id")
     */
    protected $accepted;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonRemarks
     *
     * Comments or notes about the taxon or name.
     *
     * @var string
     * @ORM\Column(type="text", name="taxon_remarks", nullable=true)
     */
    protected $taxonRemarks;

    /**
     * @ORM\ManyToMany(targetEntity="Treatment", inversedBy="forTaxon")
     * @ORM\JoinTable(name="taxon_treatment", 
     *     joinColumns={@ORM\JoinColumn(name="taxon_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="treatment_id", referencedColumnName="id", unique=true)})
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $treatments;
    
    /**
     * @ORM\ManyToMany(targetEntity="Image")
     * @ORM\JoinTable(name="taxon_image", joinColumns={
     *     @ORM\JoinColumn(name="taxon_id", referencedColumnName="id")},
     *     inverseJoinColumns={
     *     @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *     })
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $images;

    /**
     * @ORM\ManyToMany(targetEntity="VernacularName")
     * @ORM\JoinTable(name="taxon_vernacular_name", 
     *     joinColumns={@ORM\JoinColumn(name="taxon_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="vernacular_name_id", referencedColumnName="id", unique=true)})
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $vernacularNames;
    
    /**
     * @ORM\OneToMany(targetEntity="Change", mappedBy="fromTaxon")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $changes;
    
    /**
     * @ORM\OneToMany(targetEntity="TaxonReference", mappedBy="taxon")
     * @var \Doctrine\Common\Collections\ArrayCollection $taxonReferences
     */
    protected $taxonReferences;
    
    /**
     * @ORM\ManyToOne(targetEntity="HorticulturalGroup", inversedBy="cultivars")
     * @ORM\JoinColumn(name="horticultural_group_id", referencedColumnName="id")
     * @var \App\Entities\HorticulturalGroup
     */
    protected $horticulturalGroup;
    
    /**
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @var \App\Entities\Taxon $hybridParent1
     */
    protected $hybridParent1;
    
    /**
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @var \App\Entities\Taxon $hybridParent2
     */
    protected $hybridParent2;
    
    public function __construct() {
        $this->treatments = new ArrayCollection();
        $this->vernacularNames = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->changes = new ArrayCollection();
        $this->taxonReferences = new ArrayCollection();
    }
    
    /**
     * @return Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name)
    {
        $this->name = $name;
    }
    
    /**
     * @return Reference
     */
    public function getNameAccordingTo()
    {
        return $this->nameAccordingTo;
    }

    /**
     * @param Reference $sensu
     */
    public function setNameAccordingTo($sensu)
    {
        $this->nameAccordingTo = $sensu;
    }

    /**
     * @return Taxon
     */
    public function getAcceptedNameUsage()
    {
        return $this->accepted;
    }

    /**
     * @param Taxon $accepted
     */
    public function setAcceptedNameUsage(Taxon $accepted)
    {
        $this->accepted = $accepted;
    }

    /**
     * @return string
     */
    public function getTaxonRemarks()
    {
        return $this->taxonRemarks;
    }

    /**
     * @param string $remarks
     */
    public function setTaxonRemarks($remarks)
    {
        $this->taxonRemarks = $remarks;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTreatments()
    {
        return $this->treatments;
    }
    
    /**
     * 
     * @param \App\Entities\Treatment $treatment
     */
    public function addTreatment(Treatment $treatment)
    {
        $this->treatments[] = $treatment;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getVernacularNames()
    {
        return $this->vernacularNames;
    }
    
    /**
     * 
     * @param \App\Entities\VernacularName $vernacularName
     */
    public function addVernacularName(VernacularName $vernacularName)
    {
        $this->vernacularNames[] = $treatment;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * 
     * @param \App\Entities\Image $image
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChanges()
    {
        return $this->changes;
    }
    
    /**
     * 
     * @param \App\Entities\Change $change
     */
    public function addChange(Change $change)
    {
        $this->changes[] = $change;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTaxonReferences()
    {
        return $this->taxonReferences;
    }
    
    /**
     * 
     * @param \App\Entities\TaxonReference $taxonReference
     */
    public function addTaxonReference(TaxonReference $taxonReference)
    {
        $this->taxonReferences[] = $taxonReference;
    }
}
