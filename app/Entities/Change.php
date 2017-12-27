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
 * Class Change
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora")
 */
class Change extends ClassBase {

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="from_taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $fromTaxon;

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="to_taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $toTaxon;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(name="change_type", length=64, nullable=true)
     */
    protected $changeType;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_current", nullable=true)
     */
    protected $isCurrent;
}