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

use Illuminate\Http\Request;

/**
 * Description of SolrQueryStringService
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolrQueryStringService {
    
    public static function parse_query_string(Request $request) 
    {
        $params = \GuzzleHttp\Psr7\parse_query($request->getQueryString());
        if (!isset($params['q']) || !$params['q']) {
            $params['q'] = '*:*';
        }
        else {
            $params['q'] = self::prepare_query_term($params['q']);
        }
        if (!isset($params['rows'])) {
            $params['rows'] = 50;
        }
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        return $params;
    }
    
    protected static function prepare_query_term($q) 
    {
        if (substr($q, 0, 1) == '(' && substr($q, -1) == ')') {
            return $q;
        }
        if (strpos($q, '*') === false && strpos($q, ' ') == false) {
            $q .= '*';
        }
        elseif (strpos($q, ' ') && !(substr($q, 0, 1) == '"' && substr($q, -1) == '"')) {
            $q = '"' . $q . '"';
        }
        return $q;
    }
    
    public static function parse_filter_queries($params) 
    {
        $filterQueries = [];
        if (isset($params->fq)) {
            if (!is_array($params->fq)) {
                $params->fq = [$params->fq];
            }
            foreach ($params->fq as $index => $fq) {
                $filterQuery = [];
                list($field, $value) = explode(':', $fq);
                $filterQuery['fieldLabel'] = ucfirst(str_replace('_', ' ', $field));
                $filterQuery['value'] = ucfirst($value);
                $qryParams = (array) $params;
                unset($qryParams['fq'][$index]);
                $filterQuery['removeLink'] = secure_url('/search?') 
                        . \GuzzleHttp\Psr7\build_query($qryParams);
                $filterQueries[] = $filterQuery;
            }
        }
        return $filterQueries;
    }
}
