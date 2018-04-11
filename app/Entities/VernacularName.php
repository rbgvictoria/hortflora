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
 * Class VernacularName
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table( indexes={
 *     @ORM\Index(columns={"vernacular_name"}),
 *     @ORM\Index(columns={"is_preferred_name"}),
 * })
 */
class VernacularName extends ClassBase {

    /**
     * http://rs.tdwg.org/dwc/terms/vernacularName
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $taxon;

    /**
     * http://purl.org/dc/terms/source
     *
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(length=64)
     */
    protected $vernacularName;

    /**
     * http://rs.gbif.org/terms/1.0/isPreferredName
     *
     * This term is true if the source citing the use of this vernacular name
     * indicates the usage has some preference or specific standing over other
     * possible vernacular names used for the species.
     *
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isPreferredName;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $vernacularNameUsage;

    /**
     * @var string
     * @ORM\Column(length=2, nullable=true)
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $organismPart;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $taxonRemarks;

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
     * @return string
     */
    public function getVernacularName()
    {
        return $this->vernacularName;
    }

    /**
     * @param string $vernacularName
     */
    public function setVernacularName($vernacularName)
    {
        $this->vernacularName = $vernacularName;
    }

    /**
     * @return bool
     */
    public function getIsPreferredName()
    {
        return $this->isPreferredName;
    }

    /**
     * @param bool $isPreferredName
     */
    public function setIsPreferredName($isPreferredName)
    {
        $this->isPreferredName = $isPreferredName;
    }

    /**
     * @return string
     */
    public function getVernacularNameUsage()
    {
        return $this->vernacularNameUsage;
    }

    /**
     * @param string $usage
     */
    public function setVernacularNameUsage($usage)
    {
        $this->vernacularNameUsage = $usage;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getOrganismPart()
    {
        return $this->organismPart;
    }

    /**
     * @param string $organismPart
     */
    public function setOrganismPart($organismPart)
    {
        $this->organismPart = $organismPart;
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
}
