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

namespace App\Transformers;

use League\Fractal;
use Doctrine\ORM\EntityManager;

/**
 * Description of OrmTransformer
 *
 * @author nklazenga
 */
class OrmTransformer extends Fractal\TransformerAbstract {
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct()
    {
        $this->em = app('em');
    }
}
