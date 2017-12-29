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

use App\Entities\Traits\Blameable;
use App\Entities\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ClassBase
 * Base class containing common properties and methods for almost all other
 * entities
 * @author Niels Klazenga
 * @ORM\MappedSuperclass
 */
class ClassBase
{
    use Timestamps, Blameable;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Ramsey\Uuid\Uuid
     * @ORM\Column(type="uuid", nullable=true, unique=true)
     */
    protected $guid;

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    protected $version;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function incrementVersion() {
        $this->version++;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }


    /**
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }
}
