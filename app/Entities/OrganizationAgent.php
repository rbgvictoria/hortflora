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
 * Class OrganizationAgent
 * @author Niels Klazenga
 * @ORM\Entity
 * @ORM\Table()
 */
class OrganizationAgent extends ClassBase
{

    /**
     * @var Agent
     * @ORM\ManyToOne(targetEntity="Agent")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var Agent
     * @ORM\ManyToOne(targetEntity="Agent")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     */
    protected $role;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @param Agent $member
     */
    public function setMember(Agent $member)
    {
      $this->member = $member;
    }

    /**
     * @return Agent
     */
    public function getGroup()
    {
      return $this->group;
    }

    /**
     * @param Agent $group
     */
    public function setGroup(Agent $group)
    {
      $this->group = $group;
    }

    /**
     * @return string
     */
    public function getRole()
    {
      return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
      $this->role = $role;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
      return $this->startDate;
    }

    /**
     * @param \DateTime $date
     */
    public function setStartDate($date)
    {
      $this->startDate = $date;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
      return $this->endDate;
    }

    /**
     * @param \DateTime $date
     */
    public function setEndDate($date)
    {
      $this->endDate = $date;
    }
}
