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
 * Class Agent
 * @author Niels Klazenga
 * @ORM\Entity
 * @ORM\Table()
 */
class Agent extends ClassBase
{

    /**
     * @var AgentType
     * @ORM\ManyToOne(targetEntity="AgentType")
     * @ORM\JoinColumn(name="agent_type_id", referencedColumnName="id")
     */
    protected $agentType;

    /**
     * @var string
     * @ORM\Column(length=128)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(length=32, nullable=true)
     */
    protected $initials;

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     */
    protected $legalName;

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     */
    protected $email;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

}
