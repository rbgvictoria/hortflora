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

namespace App\Traits;

use App\Queries\TaxonQueries;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Taxon Exists Trait
 * @author Niels Klazenga
 */
trait TaxonExistsTrait {

    /**
     * Checks if taxon with the requested UUID exists; returns the taxon or a
     * NotFoundHttpException
     * @param  [type] $id [description]
     * @return \stdClass|NotFoundHttpException
     */
    public function checkTaxon($id)
    {
        $taxon = TaxonQueries::getTaxon($id);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        return $taxon;
    }
}
