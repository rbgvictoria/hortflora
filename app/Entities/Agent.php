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
use Laravel\Passport\Bridge\User;

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
   * @ORM\Column(length=128, nullable=true)
   * @var string
   */
  protected $ipni;

  /**
   * @var string
   * @ORM\Column(length=128, nullable=true)
   */
  protected $email;

  /**
   * @var User
   * @ORM\OneToOne(targetEntity="User", inversedBy="agent")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * @ORM\ManyToOne(targetEntity="Agent")
   * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
   * @var Agent 
   */
  protected $group;
  
  /**
   * @ORM\ManyToOne(targetEntity="Agent")
   * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
   * @var Agent
   */
  protected $organization;

  /**
   * @return AgentType
   */
  public function getAgentType()
  {
    return $this->agentType;
  }

  /**
   * @param AgentType $agentType
   */
  public function setAgentType($agentType)
  {
    $this->agentType = $agentType;
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
  function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   */
  function setFirstName($firstName)
  {
    $this->firstName = $firstName;
  }

  /**
   * @return string
   */
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   */
  function setLastName($lastName)
  {
    $this->lastName = $lastName;
  }

  /**
   * @return string
   */
  public function getInitials()
  {
    return $this->initials;
  }

  /**
   * @param string $initials
   */
  function setInitials($initials)
  {
    $this->initials = $initials;
  }

  /**
   * @return string
   */
  public function getLegalName()
  {
    return $this->legalName;
  }

  /**
   * @param string $legalName
   */
  function setLegalName($legalName)
  {
    $this->legalName = $legalName;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   */
  function setEmail($email)
  {
    $this->email = $email;
  }

  /**
   * @return User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }
  
  /**
   * 
   * @return string
   */
  public function getIpni()
  {
      return $this->ipni;
  }
  
  /**
   * 
   * @param string $ipni
   */
  public function setIpni($ipni)
  {
      $this->ipni = $ipni;
  }
  
  /**
   * 
   * @return Agent
   */
  public function getOrganization()
  {
      return $this->organization;
  }
  
  /**
   * 
   * @param Agent $organization
   */
  public function setOrganization($organization)
  {
      $this->organization = $organization;
  }
  
  /**
   * 
   * @return Agent
   */
  public function getGroup()
  {
      return $this->group;
  }
  
  /**
   * 
   * @param Agent $group
   */
  public function setGroup($group)
  {
      $this->group = $group;
  }

}
