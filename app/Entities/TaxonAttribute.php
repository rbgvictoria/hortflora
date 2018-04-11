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
 * Class TaxonAttribute
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table()
 */
class TaxonAttribute extends ClassBase {

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $taxon;

    /**
     * @var Attribute
     * @ORM\ManyToOne(targetEntity="Attribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $attribute;

    /**
     * @var string
     * @ORM\Column(name="attribute_value")
     */
    protected $value;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $remarks;

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
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @param string $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }
}
