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
 * Class AgentType
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(name="agent_type", indexes={
 *     @ORM\Index(columns={"name"}),
 *     @ORM\Index(columns={"uri"}),
 *     @ORM\Index(columns={"label"}),
 * })
 */
class AgentType extends Vocab {

    /**
     * @var string
     * @ORM\Column(length=64)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $uri;

    /**
     * @var string
     * @ORM\Column(length=64)
     */
    protected $label;

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
    public function getUri()
    {
      return $this->uri;
    }

    /**
     * @param string $uri
     */
    function setUri($uri)
    {
      $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
      return $this->label;
    }

    /**
     * @param string $label
     */
    function setLabel($label)
    {
      $this->label = $label;
    }

}
