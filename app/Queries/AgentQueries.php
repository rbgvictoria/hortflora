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
 * Description of AgentModel
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class AgentQueries
{

    /**
     * Gets an agent
     * @param  Uuid $id
     * @return object
     */
    public function getAgent($id)
    {
        return DB::table('public.agents as a')
                ->leftJoin('public.agent_type as at',
                        'a.agent_type_id', '=', 'at.id')
                ->select('a.id', 'at.label as agent_type', 'a.name',
                        'a.first_name', 'a.last_name', 'a.initials',
                        'a.legal_name', 'a.email', 'a.timestamp_created',
                        'a.timestamp_modified', 'a.guid', 'a.version')
                ->where('a.guid', '=', $id)
                ->first();
    }

    /**
     * @param  Uuid $userId
     * @return int
     */
    public static function findAgentByUser($userId)
    {
        return DB::table('public.agents')
                ->where('user_id', $userId)
                ->value('id');
    }
}
