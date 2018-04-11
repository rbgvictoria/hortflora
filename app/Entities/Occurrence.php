<?php

/*
 * Copyright 2017 Royal Botanic Gardens Victoria.
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
use App\Entities\ClassBase;

/**
 * Class Occurrence
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table()
 */
class Occurrence extends ClassBase {

    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     * @ORM\JoinColumn(name="recorded_by_id", referencedColumnName="id")
     */
    protected $recordedBy;

    /**
     * @var string
     * @ORM\Column(type="string", name="catalog_number", length=32,
     *     nullable=true)
     */
    protected $catalogNumber;

    /**
     * @var string
     * @ORM\Column(type="string", name="record_number", length=32,
     *     nullable=true)
     */
    protected $recordNumber;
    
    /**
     * 
     * @return \App\Entities\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    /**
     * 
     * @param \App\Entities\Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
    
    
    public function getRecordedBy()
    {
        return $this->recordedBy;
    }
    
    /**
     * 
     * @param \App\Entities\Agent $recordedBy
     */
    public function setRecordedBy($recordedBy)
    {
        $this->recordedBy = $recordedBy;
    }
    
    /**
     * 
     * @return string
     */
    public function getRecordNumber()
    {
        return $this->recordNumber;
    }
    
    /**
     * 
     * @param string $recordNumber
     */
    public function setRecordNumber($recordNumber)
    {
        $this->recordNumber = $recordNumber;
    }
    
    /**
     * 
     * @return string
     */
    public function getCatalogNumber()
    {
        return $this->catalogNumber;
    }
    
    
    public function setCatalogNumber($catalogNumber)
    {
        $this->catalogNumber = $catalogNumber;
    }
}
