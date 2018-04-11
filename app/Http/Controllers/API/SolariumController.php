<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Solarium\Client;
use App\Services\SolrServices\SolrQueryService;

class SolariumController extends ApiController
{
    protected $client;
    
    public function __construct(Client $client) {
        parent::__construct();
        $this->client = $client;
    }
    
    public function ping()
    {
        // create a ping query
        $ping = $this->client->createPing();

        // execute the ping query
        try {
            $this->client->ping($ping);
            return response()->json('OK');
        } catch (\Solarium\Exception $e) {
            return response()->json($e);
        }
    }
    
    /**
     * @SWG\Get(
     *     path="/search",
     *     tags={"Search"},
     *     summary="Search **Taxa**",
     *     description="This service uses the HortFlora SOLR index. SOLR has its own query syntax, see http://www.solrtutorial.com/solr-query-syntax.html for instructions on how to use it. A list of fields that is available can be found at https://hortflora.rbg.vic.gov.au/api/solr/fields.",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *       in="query",
     *       name="q",
     *       type="string",
     *       required=true,
     *       default="*:*",
     *       description="The main search string; if left to the default &ndash; \*:\* &ndash; and no filter queries are used, the service will return all taxa."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="fq",
     *       type="array",
     *       @SWG\Items(
     *           type="string"
     *       ),
     *       collectionFormat="multi",
     *       description="Filter query; used to refine the initial search. Examples: 'taxon_name:Acacia\\ \*'; 'genus:Acacia'; '-taxonomic_status:accepted'. This parameter can be used multiple times in a query string."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="fl",
     *       type="array",
     *       @SWG\Items(
     *           type="string"
     *       ),
     *       collectionFormat="csv",
     *       default="id,taxon_rank,scientific_name,scientific_name_authorship,taxonomic_status,family,occurrence_status,establishment_means,accepted_name_usage_id,accepted_name_usage,accepted_name_usage_authorship,accepted_name_usage_taxon_rank,name_according_to,sensu,taxonomic_status,occurrence_status,establishment_means,threat_status,vernacular_name",
     *       description="List of fields to include; fields are separated by a comma (CSV)."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="sort",
     *       type="array",
     *       @SWG\Items(
     *           type="string"
     *       ),
     *       collectionFormat="multi",
     *       default="scientific_name asc",
     *       description="Field to sort on and the sort order ('asc' or 'desc'), separated by ' ' (space); this parameter may be used multiple times in a query string."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="rows",
     *       type="integer",
     *       format="int32",
     *       default=20,
     *       description="The number of results to return per page."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="page",
     *       type="integer",
     *       format="int32",
     *       default=1,
     *       description="The page of query results to return."
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *           ref="#/definitions/SearchResult"
     *         )
     *       ),
     *       description="Successful response"
     *     )
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search() 
    {
        $service = new SolrQueryService($this->client);
        $result = $service->search();
        return response()->json($result);
    }
    
    public function suggest(Request $request)
    {
        $query = $this->client->createSuggester();
        $query->setQuery($request->input('term')); //multiple terms
        $query->setDictionary('suggest');
        $query->setOnlyMorePopular(true);
        $query->setCount(10);
        $query->setCollate(true);

        // this executes the query and returns the result
        $resultset = $this->client->suggester($query);
        
        $data = [];
        foreach ($data as $term => $termResult) {
            $rec = [];
            $rec['term'] = $term;
            $rec['numFound'] = $termResult->getNumFound();
            $rec['suggestions'] = [];
            foreach ($termResult as $result) {
                $rec['suggestions'] = $result;
            }
            $data[] = $rec;
        }
        return response()->json($data);
    }
}