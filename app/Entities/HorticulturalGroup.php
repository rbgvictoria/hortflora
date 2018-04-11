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
 * Cultivar Group
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * 
 * @ORM\Entity();
 */
class HorticulturalGroup extends TaxonAbstract {
    
    /**
     * @ORM\ManyToOne(targetEntity="Taxon", inversedBy="horticulturalGroup")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id")
     * @var \App\Entities\Taxon
     */
    protected $taxon;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="TaxonAbstract", mappedBy="horticulturalGroup")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $members;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->cultivars = new ArrayCollection();
    }
    
    /**
     * 
     * @return \App\Entities\Taxon
     */
    public function getTaxon()
    {
        return $this->taxon;
    }
    
    /**
     * 
     * @param \App\Entities\Taxon $taxon
     */
    public function setTaxon(Taxon $taxon)
    {
        $this->taxon = $taxon;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }
    
    /**
     * 
     * @param \App\Entities\TaxonAbstract $member
     */
    public function addMember(TaxonAbstract $member)
    {
        $this->members[] = $member;
    }
}
