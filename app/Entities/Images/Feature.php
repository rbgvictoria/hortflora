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
use App\Entities\Vocab;

/**
 * Class Feature
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(name="feature_vocab", schema="vocab", indexes={
 *     @ORM\Index(columns={"name"}),
 *     @ORM\Index(columns={"uri"}),
 *     @ORM\Index(columns={"label"}),
 * })
 */
class Feature extends Vocab {

    /**
     * @var Feature
     * @ORM\ManyToOne(targetEntity="Feature")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $node_number;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $highest_descendant_node_number;

}
