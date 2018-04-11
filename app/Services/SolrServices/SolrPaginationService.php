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
 * Description of PaginationService
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolrPaginationService {
    public static function pagination_links($pagination, $params) {
        if (is_object($params)) {
            $params = (array) $params;
        }
        $base_uri = secure_url('/search?');
        $pageLinks = [];
        
        $params['page'] = 1;
        $pageLinks['first'] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        
        if ($pagination->current_page > 1) {
            $params['page'] = $pagination->current_page - 1;
            $pageLinks['prev'] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        }
        
        $params['page'] = $pagination->current_page;
        $pageLinks['self'] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        
        if ($pagination->current_page < $pagination->total_pages) {
            $params['page'] = $pagination->current_page + 1;
            $pageLinks['next'] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        }
        
        $params['page'] = $pagination->total_pages;
        $pageLinks['last'] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        
        $pageLinks['pages'] = [];
        $params['page'] = 1;
        
        $n = $pagination->total_pages;
        $p = $pagination->current_page;
        
        /*
         * first page
         */
        $pageLinks['pages'][1] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
        
        if ($n > 6 & $p > 4) {
            $pageLinks['pages'][] = null;
        }

        /*
         * pages in between
         */
        for ($i = $p - 4; $i <= $p + 4; $i++) {
            if ($i > 1 && $i < $n && (($i == $p) || ($i == $p-1) || ($i == $p+1) || ($p < 5 && $i <= 5) || ($p > $n-4 && $i >= $n-4))) {
                $params['page'] = $i;
                $pageLinks['pages'][$i] = $base_uri . \GuzzleHttp\Psr7\build_query($params);
            }
        }
        
        if ($n > 6 && $p < $n-3) {
            $pageLinks['pages'][] = null;
        }

        /*
         * last page
         */
        if ($pagination->total_pages > 1) {
            $params['page'] = $pagination->total_pages;
            $pageLinks['pages'][$pagination->total_pages] = $base_uri 
                    . \GuzzleHttp\Psr7\build_query($params);
        }
        
        return $pageLinks;
    }
}
