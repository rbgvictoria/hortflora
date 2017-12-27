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
 * @ORM\Table(schema="flora", indexes={
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
}
