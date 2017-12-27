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
 * Class TreatmentVersion
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="treatment_version_idx",
 *         columns={"treatment_id", "version"})
 * })
 */
class TreatmentVersion extends ClassBase {

    /**
     * @var Treatment
     * @ORM\ManyToOne(targetEntity="Treatment")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id",
     *     nullable=false)
     */
    protected $treatment;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $html;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_current_version", nullable=true)
     */
    protected $isCurrentVersion;

    /**
     * @ORM\Column(type="boolean", name="is_updated", nullable=true)
     * @var bool
     */
    protected $isUpdated;
}
