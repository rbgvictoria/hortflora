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
 * Class Region
 *
 * @author Niels Klazenga
 *
 * @ORM\Entity()
 * @ORM\Table(schema="flora", name="regions")
 */

class Region extends ClassBase {

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
     * @var Region
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $nodeNumber;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $highestDescendantNodeNumber;

    /**
     * @var string 
     * @ORM\Column(type="geometry", options={"geometry_type"="MULTIPOLYGON", "srid"=4326}, nullable=true)
     */
    protected $geom;
}
