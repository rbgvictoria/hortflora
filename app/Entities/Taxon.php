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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Taxon
 * @author Niels Klazenga
 * @ORM\Entity(repositoryClass="TaxonRepository")
 */
class Taxon extends TaxonAbstract {

    /**
     * http://rs.tdwg.org/dwc/terms/parentNameUsageID
     *
     * An identifier for the name usage (documented meaning of the name
     * according to a source) of the direct, most proximate higher-rank parent
     * taxon (in a classification) of the most specific element of the
     * scientificName.
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;
    
    /**
     * Parent/children association has been made bidirectional, so it is 
     * possible to obtain the parent by the id of a child, using DQL, without 
     * having to include the child in the result; see 
     * TaxonRepository#getParentNameUsage.
     * 
     * @ORM\OneToMany(targetEntity="Taxon", mappedBy="parent")
     * @var Taxon
     */
    protected $children;

    /**
     * Node in the taxon tree. This makes the association with the Taxon Tree
     * bidirectional
     * @ORM\OneToOne(targetEntity="TaxonTree", mappedBy="taxon")
     * @var \App\Entities\TaxonTree
     */
    protected $node;
    
    /**
     * http://rs.tdwg.org/dwc/terms/originalNameUsageID
     *
     * An identifier for the name usage in which the scientificName was
     * originally established under the rules of the associated
     * nomenclaturalCode. (basionym)
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="basionym_id", referencedColumnName="id")
     */
    protected $basionym;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonomicStatus
     *
     * The status of the use of the scientificName as a label for a taxon.
     *
     * @var TaxonomicStatus
     * @ORM\ManyToOne(targetEntity="TaxonomicStatus")
     * @ORM\JoinColumn(name="taxonomic_status_id", referencedColumnName="id")
     */
    protected $taxonomicStatus;

    /**
     * http://rs.tdwg.org/dwc/terms/occurrenceStatus
     *
     * A statement about the presence or absence of a Taxon at a Location.
     *
     * @var OccurrenceStatus
     * @ORM\ManyToOne(targetEntity="OccurrenceStatus")
     * @ORM\JoinColumn(name="occurrence_status_id", referencedColumnName="id")
     */
    protected $occurrenceStatus;

    /**
     * http://rs.tdwg.org/dwc/terms/establishmentMeans
     *
     * The process by which the biological individual(s) represented in the
     * Occurrence became established at the location.
     *
     * @var EstablishmentMeans
     * @ORM\ManyToOne(targetEntity="EstablishmentMeans")
     * @ORM\JoinColumn(name="establishment_means_id", referencedColumnName="id")
     */
    protected $establishmentMeans;

    /**
     * http://iucn.org/terms/threatStatus
     *
     * Threat status of a taxon as defined by IUCN:
     * http://www.iucnredlist.org/static/categories_criteria_3_1#categories
     *
     * @var ThreatStatus
     * @ORM\ManyToOne(targetEntity="ThreatStatus")
     * @ORM\JoinColumn(name="threat_status_id", referencedColumnName="id")
     */
    protected $threatStatus;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isEndemic;
    
    /**
     * @ORM\OneToMany(targetEntity="Cultivar", mappedBy="taxon")
     * @var \Doctrine\Common\Collections\ArrayCollection $cultivars
     */
    protected $cultivars;
    
    /**
     * @ORM\OneToMany(targetEntity="HorticulturalGroup", mappedBy="taxon")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $horticulturalGroups;
    
    /**
     * @ORM\ManyToMany(targetEntity="Region")
     * @ORM\JoinTable(name="taxon_region")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $regions;
    
    public function __construct() {
        parent::__construct();
        $this->cultivars = new ArrayCollection();
        $this->distribution = new ArrayCollection();
    }

    /**
     * @return \App\Entities\TaxonRank
     */
    public function getTaxonRank()
    {
        return $this->taxonRank;
    }
    
    /**
     * @param \App\Entities\TaxonRank $rank
     */
    public function setTaxonRank($rank)
    {
        $this->taxonRank = $rank;
    }

    /**
     * @return Taxon
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Taxon $parent
     */
    public function setParent(Taxon $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return HorticulturalGroup
     */
    public function getHorticulturalGroup()
    {
        return $this->horticulturalGroup;
    }

    /**
     * @param HorticulturalGroup $horticulturalGroup
     */
    public function setHorticulturalGroup($horticulturalGroup)
    {
        $this->horticulturalGroup = $horticulturalGroup;
    }

    /**
     * @return TaxonomicStatus
     */
    public function getTaxonomicStatus()
    {
        return $this->taxonomicStatus;
    }

    /**
     * @param TaxonomicStatus $status
     */
    function setTaxonomicStatus($status)
    {
        $this->taxonomicStatus = $status;
    }

    /**
     * @return OccurrenceStatus
     */
    function getOccurrenceStatus()
    {
        return $this->occurrenceStatus;
    }

    /**
     * @param OccurrenceStatus $status
     */
    function setOccurrenceStatus($status)
    {
        $this->occurrenceStatus = $status;
    }

    /**
     * @return EstablishmentMeans
     */
    public function getEstablishmentMeans()
    {
        return $this->establishmentMeans;
    }

    /**
     * @param EstablishmentMeans $establishmentMeans
     */
    public function setEstablishmentMeans($establishmentMeans)
    {
        $this->establishmentMeans = $establishmentMeans;
    }

    /**
     * @return bool
     */
    public function getIsEndemic()
    {
        return $this->isEndemic;
    }

    /**
     * @param bool $isEndemic
     */
    public function setIsEndemic($isEndemic)
    {
        $this->isEndemic = $isEndemic;
    }

    /**
     * @return \App\Entities\TaxonTree
     */
    public function getNode()
    {
        return $this->node;
    }
    
    /**
     * 
     * @return \App\Entities\Taxon
     */
    public function getHybridParent1()
    {
        return $this->hybridParent1;
    }
    
    /**
     * 
     * @param \App\Entities\Taxon $hybridParent1
     */
    public function setHybridParent1(Taxon $hybridParent1)
    {
        $this->hybridParent1 = $hybridParent1;
    }
    
    /**
     * 
     * @return \App\Entities\Taxon
     */
    public function getHybridParent2()
    {
        return $this->hybridParent2;
    }
    
    /**
     * 
     * @param \App\Entities\Taxon $hybridParent2
     */
    public function setHybridParent2(Taxon $hybridParent2)
    {
        $this->hybridParent2 = $hybridParent2;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCultivars()
    {
        return $this->cultivars;
    }
    
    /**
     * 
     * @param \App\Entities\Cultivar $cultivar
     */
    public function addCultivar(Cultivar $cultivar)
    {
        $this->cultivars[] = $cultivar;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getHorticulturalGroups()
    {
        return $this->horticulturalGroups;
    }
    
    /**
     * 
     * @param \App\Entities\HorticulturalGroup $horticulturalGroup
     */
    public function addHorticulturalGroup($horticulturalGroup)
    {
        $this->horticulturalGroups[] = $horticulturalGroup;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRegions()
    {
        return $this->regions;
    }
    
    /**
     * 
     * @param \App\Entities\Region $region
     */
    public function addRegion(Region $region)
    {
        $this->regions[] = $region;
    }
    
}
