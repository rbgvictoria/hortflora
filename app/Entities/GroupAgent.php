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
 * Class GroupAgent
 * @author Niels Klazenga [Niels.Klazenga@rbg.vic.gov.au]
 * @ORM\Entity
 * @ORM\Table()
 */
class GroupAgent extends ClassBase
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
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $sequence;

    /**
     * @return Agent
     */
    public function getMember()
    {
      return $this->member;
    }

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
}
