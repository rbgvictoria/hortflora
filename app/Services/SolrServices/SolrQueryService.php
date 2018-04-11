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

use Solarium\Client;

/**
 * Description of SolrQueryService
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolrQueryService {
    
    protected $client;
    
    /**
     * Default download fields
     * 
     * @var array 
     */
    protected $defaultDownloadFields = [
        'id',
        'taxon_rank',
        'scientific_name',
        'scientific_name_authorship',
        'taxonomic_status',
        'kingdom',
        'phylum',
        'class',
        'order',
        'family',
        'genus',
        'specific_epithet',
        'infraspecific_epithet',
        'cultivar_epithet',
        'parent_name_usage_id',
        'parent_name_usage',
        'accepted_name_usage_id',
        'accepted_name_usage',
        'accepted_name_usage_authorship',
        'accepted_name_usage_taxon_rank',
        'taxonomic_status',
        'vernacular_name',
    ];
    
    protected $defaultFacetFields = [
        'entry_type',
        'name_type',
        'taxonomic_status',
        'taxon_rank',
        'kingdom',
        'phylum',
        'class',
        'order',
        'family',
        'genus',
    ];
    
    protected $defaultRows = 20;
    
    public function __construct(Client $client) {
        $this->client = $client;
    }
    
    public function search()
    {
        if (request()->getQueryString()) {
            $params = \GuzzleHttp\Psr7\parse_query(request()->getQueryString());
            if (!isset($params['q'])) {
                $params['q'] = '*:*';
            }
            if (!isset($params['rows'])) {
                $params['rows'] = $this->defaultRows;
            } 
        }
        else {
            $params = [
                'q' => '*:*',
                'rows' => $this->defaultRows
            ];
        }
        $query = $this->client->createSelect();
        $query = $this->setQuery($query, $params);
        $query = $this->setSort($query, $params);
        $query = $this->setCursor($query, $params);
        $query = $this->setFilters($query, $params);
        $this->setFacets($query, $params);
        return $this->getResult($query, $params);
    }
    
    protected function toTransformOrNotToTransform($params) 
    {
        $transform = true;
        if (isset($params['transform']) && $params['transform'] == 'false') {
            $transform = false;
        }
        return $transform;
    }
    
    protected function setQuery($query, $params)
    {
        return $query->setQuery((isset($params['q'])) ? $params['q'] : '*:*')
                ->setFields(isset($params['fl']) ? 
                    explode(',', $params['fl']) : $this->defaultDownloadFields);
    }
    
    protected function setSort($query, $params)
    {
        $sortOrder = 'asc';
        if (isset ($params['sort'])) {
            if (!is_array($params['sort'])) {
                $params['sort'] = [$params['sort']];
            }
            foreach ($params['sort'] as $sort) {
                if (strpos($sort, ' ')) {
                    if (substr($sort, strpos($sort, ' ') + 1) == 'desc') {
                        $sortOrder = 'desc';
                    };
                    $sort = substr($sort, 0, strpos($sort, ' '));
                }
                $query->addSort($sort, $sortOrder);
            }
        }
        else {
            $query->addSort('scientific_name', $sortOrder);
        }
        return $query;
    }
    
    protected function setCursor($query, $params)
    {
        $rows = 20;
        if (isset($params['rows'])) {
            $rows = $params['rows'];
        }
        $start = 0;
        if (isset($params['page'])) {
            $start = ($params['page'] - 1) * $rows;
        }
        elseif (isset($params['start'])) {
            $start = $params['start'] - ($params['start'] % $rows);
        }
        return $query->setStart($start)->setRows($rows);
    }
    
    protected function setFilters($query, $params)
    {
        if (isset($params['fq'])) {
            if (is_array($params['fq'])) {
                foreach ($params['fq'] as $index => $fq) {
                    $query->createFilterQuery('fq_' . $index)->setQuery($fq);
                }
            }
            else {
                $query->createFilterQuery('fq_' . 0)->setQuery($params['fq']);
            }
        }
        return $query;
    }
    
    public function getFacetFields($params) 
    {
        if (isset($params['facet.field']) && 
                is_array($params['facet.field'])) {
            $facetFields = $params['facet.field'];
        }
        elseif(isset($params['facet.field'])) {
            $facetFields = [$params['facet.field']];
        }
        else {
            $facetFields = $this->defaultFacetFields;
        }
        return $facetFields;
    }
    
    public function setFacets($query, $params)
    {
        if (!(isset($params['facet']) && $params['facet'] == "false")) {
            $facetFields = $this->getFacetFields($params);
            $facetSet = $query->getFacetSet();
            foreach ($facetFields as $field) {
                $facetField = $facetSet->createFacetField($field)
                        ->setField($field)
                        ->setMissing(true)
                        ->setMincount(1);
                if (isset($params['facet.sort'])) {
                    $facetField->setSort($params['facet.sort']);
                }
                if (isset($params['facet.limit']) 
                        && is_numeric($params['facet.limit'])) {
                    $facetField->setLimit($params['facet.limit']);
                }
                if (isset($params['facet.offset']) 
                        && is_numeric($params['facet.offset'])) {
                    $facetField->setOffset($params['facet.offset']);
                }
            }
        }
    }
    
    protected function getResult($query, $params)
    {
        // Result
        $resultSet = $this->client->select($query);
        $response = [];
        $response['meta']['params'] = $params;
        $response['meta']['query'] = \GuzzleHttp\Psr7\build_query($params);
        if ($params['rows']) {
            $response['meta']['pagination'] = $this->pagination($resultSet, 
                    $params);
            $response['docs'] = $this->getDocs($resultSet);
        }
        $facetFields = $this->getFacetFieldResults($resultSet, $params);
        if ($facetFields) {
            $response['facetFields'] = $facetFields;
        }
        if ($params['rows']) {
            $response['links'] = $this->pagination_links($resultSet, $params);
        }
        return $response;
    }
    
    protected function getDocs($resultSet) 
    {
        $docs = [];
        foreach ($resultSet as $document) {
            $doc = [];
            foreach ($document as $field => $value) {
                $label = camel_case($field);
                $doc[$label] = $value;
            }
            $docs[] = $doc;
        }
        return $docs;
    }
    
    protected function getFacetFieldResults($resultSet, $params) 
    {
        if (!(isset($params['facet']) && $params['facet'] == "false")) {
            $facetFields = [];
            $fields = $this->getFacetFields($params);
            $facetSet = $resultSet->getFacetSet();
            foreach ($fields as $field) {
                $facetField = [
                    'fieldName' => camel_case($field),
                    'facets' => [],
                    
                ];
                
                $facet = $facetSet->getFacet($field);
                foreach ($facet as $value => $count) {
                    if ($value != "") {
                        $facetField['facets'][] = [
                            'val' => $value,
                            'count' => $count,
                            'fq' => $field . ':' . str_replace(' ', "\ ", $value)
                        ];
                    }
                    elseif ($count > 0) {
                        $facetField['facets'][] = [
                            'val' => '(blank)',
                            'count' => $count,
                            'fq' => '-' . $field . ':*'
                        ];
                    }
                }
                $facetFields[] = $facetField;
            }
            return $facetFields;
        }
    }
    
    protected function pagination($result, $params)
    {
        $total = $result->getNumFound();
        $perPage = 20;
        if (isset($params['rows'])) {
            $perPage = $params['rows'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        elseif (isset($params['start'])) {
            $page = floor($params['start'] / $perPage);
        }
        $numPages = ceil($total / $perPage);
        $pagination = [
            'total' => $total,
            'count' => ($page < $numPages) ? $perPage : $total % $perPage,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $numPages
        ];
        return $pagination;
    }
    
    protected function pagination_links($result, $params=[])
    {
        $url = secure_url(request()->path());
        
        $perPage = 20;
        if (isset($params['rows'])) {
            $perPage = $params['rows'];
            unset($params['rows']);
        }
        $params['rows'] = $perPage;
        
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
            unset($params['page']);
        }
        elseif (isset($params['start'])) {
            $page = floor($params['start'] / $perPage);
            unset($params['start']);
        }
        
        $links = [];
        $links['self'] = $url . '?' . \GuzzleHttp\Psr7\build_query($params) . '&page=' . $page;
        $links['first'] = $url . '?' . \GuzzleHttp\Psr7\build_query($params) . '&page=1';
        if ($page > 1) {
            $links['prev'] = $url . '?' . \GuzzleHttp\Psr7\build_query($params) . '&page=' . ($page - 1);
        }
        $numPages = ceil($result->getNumFound() / $perPage);
        if ($page < $numPages) {
            $links['next'] = $url . '?' . \GuzzleHttp\Psr7\build_query($params) . '&page=' . ($page + 1);
        }
        $links['last'] = $url . '?' . \GuzzleHttp\Psr7\build_query($params) . '&page=' . $numPages;
        return $links;
    }
}
