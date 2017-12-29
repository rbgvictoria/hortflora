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

namespace App\Entities\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait NestedSets
 */
trait NestedSets
{
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
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $depth;

    /**
     * @return int
     */
    public function getNodeNumber()
    {
        return $this->nodeNumber;
    }

    /**
     * @param int $nodeNumber
     */
    public function setNodeNumber($nodeNumber)
    {
        $this->nodeNumber = $nodeNumber;
    }

    /**
     * @return int
     */
    public function getHighestDescendantNodeNumber()
    {
        return $this->highestDescendantNodeNumber;
    }

    /**
     * @param int $nodeNumber
     */
    public function setHighestDescendantNodeNumber($nodeNumber)
    {
        $this->highestDescendantNodeNumber = $nodeNumber;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }
}
