<?php

/*
 * Copyright 2018 Royal Botanic Gardens Victoria.
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

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 * 
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class UserRepository extends EntityRepository {

    public function findForPassport($userId)
    {
        $userRepository = $this->getEntityManager()->getRepository('User');
        return $userRepository->findOneByEmail($userId);
    }
}
