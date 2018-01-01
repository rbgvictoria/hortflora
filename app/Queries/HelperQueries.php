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

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Helper Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class HelperQueries {

    /**
     * Finds primary key for a record given a UUID
     * @param  string $table
     * @param  Uuid $guid
     * @return int
     */
    public static function getPrimaryKeyFromGuid($table, $guid)
    {
        return DB::table($table)
                ->where('guid', $guid)
                ->value('id');
    }

    /**
     * Gets ID for vocabulary term
     * @param  string $vocab
     * @param  string $term
     * @return int
     */
    public static function getVocabularyIdFromTerm($vocab, $term)
    {
        return DB::table($vocab)
                ->where('name', $term)
                ->value('id');
    }
}
