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
 * Class VernacularName
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora", indexes={
 *     @ORM\Index(columns={"vernacular_name"}),
 *     @ORM\Index(columns={"is_preferred_name"}),
 * })
 */
class VernacularName extends ClassBase {

    /**
     * http://rs.tdwg.org/dwc/terms/vernacularName
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $taxon;

    /**
     * http://purl.org/dc/terms/source
     *
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(length=64)
     */
    protected $vernacularName;

    /**
     * http://rs.gbif.org/terms/1.0/isPreferredName
     *
     * This term is true if the source citing the use of this vernacular name
     * indicates the usage has some preference or specific standing over other
     * possible vernacular names used for the species.
     *
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isPreferredName;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $vernacularNameUsage;

    /**
     * @var string
     * @ORM\Column(length=2, nullable=true)
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     */
    protected $organismPart;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $taxonRemarks;
}
