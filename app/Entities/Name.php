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
 * Class Name
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table( indexes={
 *     @ORM\Index(columns={"name"}),
 *     @ORM\Index(columns={"full_name"}),
 *     @ORM\Index(columns={"authorship"})
 * })
 */
class Name extends ClassBase {

    /**
     * @var string
     * @ORM\Column(type="string", length=96)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", name="full_name", length=255)
     */
    protected $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", name="authorship", length=255, nullable=true)
     */
    protected $authorship;

    /**
     * @var string
     * @ORM\Column(type="string", name="nomenclatural_note", length=255, nullable=true)
     */
    protected $nomenclaturalNote;

    /**
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="protologue_id", referencedColumnName="id")
     */
    protected $protologue;

    /**
     * @var NameType
     * @ORM\ManyToOne(targetEntity="NameType")
     * @ORM\JoinColumn(name="name_type_id", referencedColumnName="id")
     */
    protected $nameType;

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
    public function setName($name)
    {
      $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
      return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
      $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getAuthorship()
    {
      return $this->authorship;
    }

    /**
     * @param string $author
     */
    public function setAuthorship($author)
    {
      $this->authorship = $author;
    }

    /**
     * @return Reference
     */
    public function getProtologue()
    {
      return $this->protologue;
    }

    /**
     * @param Reference $protologue
     */
    public function setProtologue($protologue)
    {
      $this->protologue = $protologue;
    }

    /**
     * @return string
     */
    public function getNomenclaturalNote()
    {
      return $this->nomenclaturalNote;
    }

    /**
     * @param string $note
     */
    public function setNomenclaturalNote($note)
    {
      $this->nomenclaturalNote = $note;
    }

    /**
     * @return NameType
     */
    public function getNameType()
    {
      return $this->nameType;
    }

    /**
     * @param NameType $type
     */
    public function setNameType($type)
    {
      $this->nameType = $type;
    }
}
