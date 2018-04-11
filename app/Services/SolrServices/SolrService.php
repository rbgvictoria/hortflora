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

namespace App\Services\SolrServices;

/**
 * Description of SolrService
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolrService {
    protected $status = [
        '400' => '400: Bad request',
        '401' => '401: Unauthorised',
        '403' => '403: Forbidden',
        '404' => '404: Not found',
        '500' => '500: Server error',
        '503' => '503: Service unavailable',
        '0' => '0: Unknown',
    ];
}
