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
 * Class TaxonTreeDefItem
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora", indexes={
 *     @ORM\Index(columns={"name"}),
 *     @ORM\Index(columns={"rank_id"})
 * })
 */
class TaxonTreeDefItem extends ClassBase {

    /**
     * @var TaxonTreeDefItem
     * @ORM\ManyToOne(targetEntity="TaxonTreeDefItem")
     * @ORM\JoinColumn(name="parent_item_id", referencedColumnName="id")
     */
    protected $parentItem;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(length=16, nullable=true)
     */
    protected $textBefore;

    /**
     * @var string
     * @ORM\Column(length=16, nullable=true)
     */
    protected $textAfter;

    /**
     * @var string
     * @ORM\Column(length=4, nullable=true)
     */
    protected $fullNameSeparator;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isEnforced;


    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isInFullName;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $rankId;

    /**
     * @return TaxonTreeDefItem
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }


    /**
     * @param TaxonTreeDefItem $parentItem
     */
    public function setParentItem($parentItem)
    {
        $this->parentItem = $parentItem;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTextBefore()
    {
        return $this->textBefore;
    }

    /**
     * @param string $prefix
     */
    public function setTextBefore($prefix)
    {
        $this->textBefore = $prefix;
    }

    /**
     * @return string
     */
    public function getTextAfter()
    {
        return $this->textAfter;
    }

    /**
     * @param string $suffix
     */
    public function setTextAfter($suffix)
    {
        $this->textAfter = $suffix;
    }

    /**
     * @return string
     */
    public function getFullNameSeparator()
    {
        return $this->fullNameSeparator;
    }

    /**
     * @param string $separator
     */
    public function setFullNameSeparator($separator)
    {
        $this->fullNameSeparator = $separator;
    }

    /**
     * @return bool
     */
    public function getIsEnforced()
    {
        return $this->isEnforced;
    }

    /**
     * @param bool $isEnforced
     */
    public function setIsEnforced($isEnforced)
    {
        $this->isEnforced = $isEnforced;
    }

    /**
     * @return bool
     */
    public function getInFullName()
    {
        return $this->isInFullName;
    }

    /**
     * @param bool $isInFullName
     */
    public function setIsInFullName($isInFullName)
    {
        $this->isInFullName = $isInFullName;
    }

    /**
     * @return [type] [description]
     */
    public function getRankId()
    {
        return $this->rankId;
    }

    /**
     * @param int $rankId
     */
    public function setRankId($rankId)
    {
        $this->rankId = $rankId;
    }
}
