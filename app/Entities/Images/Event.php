<?php

/*
 * Copyright 2017 Royal Botanic Gardens Victoria.
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

namespace App\Entities\Images;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\ClassBase;

/**
 * @ORM\Entity()
 * @ORM\Table(schema="images")
 */
class Event extends ClassBase {

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @var \App\Entities\Images\Location
     */
    protected $location;

    /**
     * @ORM\Column(type="date", name="start_date", nullable=true)
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @ORM\Column(type="date", name="end_date", nullable=true)
     * @var \DateTime
     */
    protected $endDate;
}
