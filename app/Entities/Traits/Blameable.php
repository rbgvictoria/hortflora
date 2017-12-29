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

namespace App\Entities\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait Blamees
 */
trait Blameable
{
    /**
     * @var \App\Entities\User $createdBy
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="\App\Entities\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var \App\Entities\User $modifiedBy
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="\App\Entities\User")
     * @ORM\JoinColumn(name="modified_by_id", referencedColumnName="id")
     */
    protected $modifiedBy;

    /**
     * @return \App\Entities\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \App\Entities\User $createdBy
     */
    public function setCreatedBy(\App\Entities\User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \App\Entities\User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param \App\Entities\User $modifiedBy
     */
    public function setModifiedBy(\App\Entities\User $modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }
}
