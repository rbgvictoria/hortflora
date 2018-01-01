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

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Name Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class NameQueries {

    /**
     * [getName description]
     * @param  Uuid $id UUID of the requested name
     * @return \stdClass
     */
    public static function getName($id)
    {
        return DB::table('flora.names as n')
                ->select('n.id', 'n.guid', 'n.name', 'n.full_name', 'n.authorship',
                        'n.timestamp_created', 'n.timestamp_modified', 'n.version')

                // Name type
                ->leftJoin('vocab.name_type_vocab as vnt',
                        'n.name_type_id', '=', 'vnt.id')
                ->addSelect('vnt.uri as name_type_uri',
                    'vnt.name as name_type_name',
                    'vnt.label as name_type_label')

                // Name published in
                ->leftJoin('public.references as r',
                        'n.protologue_id', '=', 'r.id')
                ->addSelect('r.guid as name_published_in', 'n.nomenclatural_note',
                        'r.guid as protologue')

                // Blameable
                ->leftJoin('public.agents as ca',
                        'n.created_by_id', '=', 'ca.id')
                ->leftJoin('public.agents as ma',
                        'n.modified_by_id', '=', 'ma.id')
                ->addSelect('ca.guid as creator',
                        'ma.guid as modified_by')

                ->where('n.guid', '=', $id)
                ->first();
    }
}
