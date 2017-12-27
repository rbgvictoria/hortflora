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
 * Class Reference
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table()
 */
class Reference extends ClassBase {

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $author;

    /**
     * @var string
     * @ORM\Column(type="string", name="publication_year", length=16,
     *     nullable=true)
     */
    protected $publicationYear;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $journalOrBook;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $collation;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $series;

    /**
      * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $edition;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $volume;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $part;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $page;


    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $publisher;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $placeOfPublication;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $subject;

    /**
     * @var \App\Entities\Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;
}
