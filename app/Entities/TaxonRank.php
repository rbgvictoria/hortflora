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
 * Class TaxonomicStatus
 * Taxonomic Status vocabulary
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(name="taxon_rank_vocab", indexes={
 *     @ORM\Index(columns={"name"}),
 *     @ORM\Index(columns={"uri"}),
 *     @ORM\Index(columns={"label"}),
 * })
 */
class TaxonRank extends Vocab
{

    /**
     * @var TaxonRank
     * @ORM\ManyToOne(targetEntity="TaxonRank")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var string
     * @ORM\Column(length=16, nullable=true)
     */
    protected $textBefore;

    /**
     * @var string
     * @ORM\Column(length=16, nullable=true)
     */
    protected $textAfter;

    /**
     * @var string
     * @ORM\Column(length=4, nullable=true)
     */
    protected $fullNameSeparator;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isEnforced;


    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isInFullName;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $rankId;

}
