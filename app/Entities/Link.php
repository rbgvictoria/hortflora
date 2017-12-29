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
 * Description of Link
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @ORM\Entity
 * @ORM\Table(schema="flora")
 */
class Link extends ClassBase {

    /**
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id", nullable=false)
     *
     * @var Taxon
     */
    protected $taxon;

    /**
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     *
     * @var Reference
     */
    protected $source;

    /**
     * @ORM\Column(length=64)
     * @var string
     */
    protected $baseUrl;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $path;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $query;

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
    public function setSource(Reference $source)
    {
      $this->source = $source;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
      return $this->baseUrl;
    }

    /**
     * @param string $base
     */
    public function setBaseUrl($base)
    {
      $this->baseUrl = $base;
    }

    /**
     * @return string
     */
    public function getPath()
    {
      return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
      $this->path = $path;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
      return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
      $this->path = $path;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
      return $this->baseUrl . $this->path;
    }
}
