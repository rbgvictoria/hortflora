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

namespace App\Transformers;

use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of ReferenceTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Change",
 *   type="object",
 *   required={"id", "fromTaxon", "toTaxon", "changeType"}
 * )
 */
class TaxonIncludeTransformer extends Fractal\TransformerAbstract {

    /**
     * @param  object $taxon
     * @return array
     */
    public function transform($taxon)
    {
        return [
            'id' => $taxon->guid,
            'rank' => $taxon->rank,
            'fullName' => $taxon->fullName,
        ];
    }
}
