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

use App\Entities\Traits\NestedSets;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Region
 *
 * @author Niels Klazenga
 *
 * @ORM\Entity()
 * @ORM\Table( name="regions")
 */

class Region extends ClassBase {

    use NestedSets;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $fullName;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $level;
    
    /**
     * @ORM\Column(nullable=true, length=2)
     * @var string
     */
    protected $countryCode;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var string
     * @ORM\Column(type="geometry", options={"geometry_type"="MULTIPOLYGON", "srid"=4326}, nullable=true)
     */
    protected $geom;
    
    /**
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * 
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * 
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    
    /**
     * 
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
    
    /**
     * 
     * @return \App\Entities\Region
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * 
     * @param \App\Entities\Region $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    
    /**
     * 
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * 
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}
