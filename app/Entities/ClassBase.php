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
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Ramsey\Uuid\Uuid;

/**
 * Class ClassBase
 * Base class containing common properties and methods for almost all other
 * entities
 * @author Niels Klazenga
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class ClassBase
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="uuid", nullable=true, unique=true)
     */
    protected $guid;

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    protected $version;

    /**
     * @var \App\Entities\Agent $createdBy
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var \App\Entities\Agent $modifiedBy
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="modified_by_id", referencedColumnName="id")
     */
    protected $modifiedBy;

    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz", name="timestamp_created", nullable=false)
     */
    protected $timestampCreated;

    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz", name="timestamp_modified", nullable=true)
     */
    protected $timestampModified;

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
     * @ORM\PrePersist
     */
    public function setVersion()
    {
        $this->version = 1;
    }
    
    /**
     * @return int
     * @ORM\PreUpdate
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
     * @ORM\PrePersist
     */
    public function setGuid()
    {
        $this->guid = Uuid::uuid4()->toString();
    }
    
    /**
     * @return \App\Entities\Agent
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \App\Entities\Agent $createdBy
     * @ORM\PrePersist
     */
    public function setCreatedBy()
    {
        $this->createdBy = EntityManager::getRepository('\App\Entities\Agent')
                ->findOneBy(['user' => Auth::User()]);
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param \App\Entities\Agent $modifiedBy
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setModifiedBy()
    {
        $this->modifiedBy = EntityManager::getRepository('\App\Entities\Agent')
                ->findOneBy(['user' => Auth::User()]);
    }
    
    /**
     * @return DateTime
     */
    public function getTimestampCreated()
    {
        return $this->timestampCreated;
    }

    /**
     * @param DateTime $timestampCreated
     * @ORM\PrePersist
     */
    public function setTimestampCreated()
    {   
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone(env('APP_DEFAULT_TIMEZONE', 'Australia/Melbourne')));
        $this->timestampCreated = $date;
    }

    /**
     * @return DateTime
     */
    public function getTimestampModified()
    {
        return $this->timestampModified;
    }

    /**
     * @param DateTime $timestampModified
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setTimestampModified()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone(env('APP_DEFAULT_TIMEZONE', 'Australia/Melbourne')));
        $this->timestampModified = new $date;
    }
}
