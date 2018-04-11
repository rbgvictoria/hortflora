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

/**
 * Class TaxonReference
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table()
 */
class TaxonReference extends ClassBase {

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="TaxonAbstract", inversedBy="taxonReferences")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $taxon;

    /**
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $reference;
    
    /**
     *
     * @var string
     * @ORM\Column(nullable=true, length=32)
     */
    protected $page;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     * @var \DateTime
     */
    protected $dateAccessed;

    /**
     * @return Taxon
     */
    public function getTaxon()
    {
        return $this->taxon;
    }

    /**
     * @param Taxon $taxon
     */
    public function setTaxon(Taxon $taxon)
    {
        $this->taxon = $taxon;
    }

    /**
     * @return Reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param Reference $reference
     */
    public function setReference(Reference $reference)
    {
        $this->reference = $reference;
    }
    
    /**
     * 
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * 
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateAccessed()
    {
        return $this->dateAccessed;
    }
    
    /**
     * 
     * @param \DateTime $dateAccessed
     */
    public function setDateAccessed($dateAccessed)
    {
        $this->dateAccessed = $dateAccessed;
    }
    
    
}
