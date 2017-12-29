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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait Timestamps
 */
trait Timestamps
{
    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz", name="timestamp_created", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $timestampCreated;

    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz", name="timestamp_modified", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $timestampModified;

    /**
     * @return DateTime
     */
    public function getTimestampCreated()
    {
        return $this->timestampCreated;
    }

    /**
     * @param DateTime $timestampCreated
     */
    public function setTimestampCreated(DateTime $timestampCreated)
    {
        $this->timestampCreated = $timestampCreated;
    }

    /**
     * @return DateTime
     */
    public function getTimestampModified()
    {
        return $this->timestampModified;
    }

    /**
     * @param DateTime $timestampModified
     */
    public function setTimestampModified(DateTime $timestampModified)
    {
        $this->timestampModified = $timestampModified;
    }
}
