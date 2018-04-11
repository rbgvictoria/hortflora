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

use Doctrine\ORM\Mapping as ORM;

/**
 * Cultivar
 * @ORM\Entity()
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class Cultivar extends TaxonAbstract {
    
    /**
     * @ORM\ManyToOne(targetEntity="Taxon", inversedBy="cultivars")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id")
     * @var \App\Entities\Taxon
     */
    protected $taxon;
    
    public function __construct()
    {
        parent::__construct();
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
     * @return \App\Entities\HorticulturalGroup
     */
    public function getHorticulturalGroup()
    {
        return $this->horticulturalGroup;
    }
    
    /**
     * 
     * @param \App\Entities\HorticulturalGroup $horticulturalGroup
     */
    public function setHorticulturalGroup($horticulturalGroup)
    {
        $this->horticulturalGroup = $horticulturalGroup;
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

}
