<?php

namespace App\Entities\Traits;

use App\Entities\User;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Description of UsesPasswordGrant
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
trait UsesPasswordGrant {
    
    /**
     * 
     * @param EntityManager $em
     * @param string $userId
     * @return User
     */
    public function findForPassport($userId)
    {
        $userRepository = EntityManager::getRepository(get_class($this));
        return $userRepository->findOneByEmail($userId);
    }
    
}
