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
 * Class Distribution
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 * @ORM\Entity()
 * @ORM\Table( name="distribution")
 */
class Distribution extends ClassBase {

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $taxon;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $region;

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
     * @return Region
     */
    public function getRegion()
    {
      return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion(Region $region)
    {
      $this->region = $region;
    }
}
