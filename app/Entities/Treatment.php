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
 * Class Treatment
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="treatment_guid_idx", columns={"guid"})
 * })
 */
class Treatment extends ClassBase {

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="taxon_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $taxon;

    /**
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="accepted_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $accepted;

    /**
     * @var TaxonomicStatus
     * @ORM\ManyToOne(targetEntity="TaxonomicStatus")
     * @ORM\JoinColumn(name="taxonomic_status_id", referencedColumnName="id")
     */
    protected $taxonomicStatus;

    /**
     * @var Reference|null
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     */
    protected $source;

    /**
     * @var Agent|null
     * @ORM\ManyToOne(targetEntity="Agent")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_current_treatment", nullable=true)
     */
    protected $isCurrentTreatment;
}
