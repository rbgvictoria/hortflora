<?php

namespace App\Http\Controllers\API;

use App\Transformers\TaxonTransformer;
use Illuminate\Http\Request;
use League\Fractal;
use Solarium\Client;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Taxon Controller
 */
class TaxonController extends TaxonAbstractController
{
    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}",
     *     tags={"Taxa"},
     *     summary="Gets a Taxon resource",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         required=true,
     *         type="string",
     *         description="UUID of the Taxon resource"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         items=@SWG\Items(
     *             type="string",
     *             enum={"creator", "modifiedBy", "parentNameUsage",
     *                 "classification", "siblings", "children", "synonyms",
     *                 "treatments", "changes"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include in the result; linked resources within included resources can be appended, separated by a full stop, e.g. 'treatment.as'; multiple resources can be included, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"name", "acceptedNameUsage", "nameAccordingTo"}
     *         ),
     *         collectionFormat="csv",
     *         description="Linked resources to exclude from the result; the enumerated resources are included by default, but can be excluded if they are not wanted in the result."
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response.",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->checkUuid($id);
        $includes = 'taxonomicStatus,acceptedNameUsage,name.nameType';
        if ($request->input('include')) {
            $includes .= ',' . $request->input('include');
        }
        $taxon = $this->em->getRepository('\App\Entities\Taxon')->getTaxon($id);
        //$taxon = app('em')->getRepository('\App\Entities\Taxon')->findOneBy(['guid' => $id]);
        $transformer = new TaxonTransformer();
        $resource = new Fractal\Resource\Item($taxon, $transformer, 'taxa');
        $this->fractal->parseIncludes($includes);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/acceptedNameUsage",
     *     tags={"Taxa"},
     *     summary="Gets accepted name usage of a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasAcceptedNameUsage($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $acceptedNameUsage = $taxon->getAcceptedNameUsage();
        if ($acceptedNameUsage) {
            $resource = new Fractal\Resource\Item($acceptedNameUsage, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/parentNameUsage",
     *     tags={"Taxa"},
     *     summary="Gets parent of a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasParentNameUsage($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $parent = $taxon->getParent();
        if ($parent) {
            $resource = new Fractal\Resource\Item($parent, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/classification",
     *     tags={"Taxa"},
     *     summary="Gets classification for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function classification($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $classification = $this->em->getRepository('\App\Entities\Taxon')
                ->getHigherClassification($taxon);
        if ($classification) {
            $resource = new Fractal\Resource\Collection($classification, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/children",
     *     tags={"Taxa"},
     *     summary="Gets children of a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function children($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $children = $this->em->getRepository('\App\Entities\Taxon')
                ->getChildren($taxon);
        if ($children) {
            $resource = new Fractal\Resource\Collection($children, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/siblings",
     *     tags={"Taxa"},
     *     summary="Gets siblings of a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function siblings($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $siblings = $this->em->getRepository('\App\Entities\Taxon')
                ->getSiblings($taxon);
        if ($siblings) {
            $resource = new Fractal\Resource\Collection($siblings, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/synonyms",
     *     tags={"Taxa"},
     *     summary="Gets synonyms for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function synonyms($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $synonyms = $this->em->getRepository('\App\Entities\Taxon')
                ->getSynonyms($taxon);
        if ($synonyms) {
            $resource = new Fractal\Resource\Collection($synonyms, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * 
     * @SWG\Get(
     *     path="/taxa/{taxon}/cultivars",
     *     tags={"Taxa", "Cultivars"},
     *     summary="Gets cultivars for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Cultivar"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     * @param  string  $id [description]
     * @return \Illuminate\Http\Response
     */
    public function hasCultivars($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $cultivars = $taxon->getCultivars();
        $resource = new Fractal\Resource\Collection($cultivars, 
                new \App\Transformers\CultivarTransformer, 'cultivars');
        $this->fractal->parseExcludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * 
     * @SWG\Get(
     *     path="/taxa/{taxon}/horticulturalGroups",
     *     tags={"Taxa", "Horticultural Groups"},
     *     summary="Gets horticultural groups that are assigned to a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Exception"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     * @param  string  $id UUID of the Taxon
     * @return \Illuminate\Http\Response
     */
    public function hasHorticulturalGroups($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $horticulturalGroups = $taxon->getHorticulturalGroups();
        if ($horticulturalGroups) {
            $transformer = new \App\Transformers\HorticulturalGroupTransformer();
            $resource = new Fractal\Resource\Collection($horticulturalGroups, 
                    new \App\Transformers\HorticulturalGroupTransformer);
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }


    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/key",
     *     tags={"Taxa"},
     *     summary="Gets Key for a Taxon",
     *     description="Gets the key from KeyBase.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Key"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested Taxon resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasKey($id)
    {
        $data = false;
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://data.rbg.vic.gov.au/keybase-ws/ws/'
        ]);
        $response = $client->request('GET', 'search_items', [
            'query' => [
                'term' => $taxon->getName()->getFullName(),
                'project' => 33,
            ]
        ]);

        if ($response->hasHeader('Content-Length')) {
            $length = $response->getHeader("Content-Length");
            if ($length[0] > '0') {
                $body = json_decode($response->getBody());
                if (is_array($body) && is_object($body[0])) {
                    $key = $body[0];
                    $resource = new Fractal\Resource\Item($key, new \App\Transformers\KeyTransformer, 'keys');
                    $data = $this->fractal->createData($resource)->toArray();
                }
            }
        }
        return response()->json($data);
    }

    /**
     * 
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function hasRegions($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $distribution = $taxon->getDistribution();
        $resource = new Fractal\Resource\Collection($distribution, 
                new \App\Transformers\RegionTransformer, 'regions');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * [hasDistributionMapUrl description]
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function hasDistributionMap($id)
    {
        $this->getTaxon($id);
        $regions = $taxon->getRegions();
        if ($regions) {
            $query = [
                'service' => 'WMS',
                'version' => '1.1.0',
                'request' => 'GetMap',
                'layers' => 'world:level3,hortflora:distribution_view',
                'styles' => 'polygon,red_polygon',
                'bbox' => '-180.00005538,-55.9197235107422,180.0,83.6236064951172',
                'width' => 851,
                'height' => 330,
                'srs' => 'EPSG:4326',
                'format' => 'image/svg',
                'cql_filter' => "INCLUDE;taxon_id='$id'"
            ];
            return response()->json(['url' => env('GEOSERVER_URL')
                    . '?' . \GuzzleHttp\Psr7\build_query($query)]);
        }
        else {
            return response()->json(null);
        }
    }
    
    /**
     * @SWG\Post(
     *     path="/taxa",
     *     tags={"Taxa"},
     *     summary="Creates a new Taxon record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Taxon object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted Taxon object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $taxon = new \App\Entities\Taxon();
        $taxon->setName($this->getValue($request->input('name'), 
                'Name'));
        $taxon->setTaxonRank($this->getValue($request->input('taxonRank'), 
                'TaxonRank', true));
        $taxon->setParent($this->getValue($request->input('parentNameUsage'), 
                'Taxon'));
        if ($request->input('acceptedNameUsage')) {
            $taxon->setAcceptedNameUsage($this->getValue($request
                    ->input('acceptedNameUsage'), 'Taxon'));
            if ($request->input('taxonomicStatus')) {
                $taxon->setTaxonomicStatus($this->getValue($request
                        ->input('taxonomicStatus'), 'TaxonomicStatus', true));
            }
        }
        else {
            $taxon->setTaxonomicStatus(
                    $this->em->getRepository('\App\Entities\TaxonomicStatus')
                    ->findOneBy(['name' => 'accepted']));
            $taxon->setAcceptedNameUsage($taxon);
        }
        $taxon->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->persist($taxon);
        $this->em->flush();
        $transformer = new \App\Transformers\TaxonTransformer();
        $resource = new Fractal\Resource\Item($taxon, $transformer, 'taxa');
        $this->fractal->parseIncludes(['parentNameUsage', 'acceptedNameUsage', 
                'taxonomicStatus']);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $taxon->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/taxa/{taxon}",
     *     tags={"Taxa"},
     *     summary="Updates an existing Taxon record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Taxon object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Taxon object",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     * @throws \App\Exceptions\CannotChangeTaxonNameException
     */
    public function update(Request $request, $id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $name = $request->input('name');
        if (is_array($name)) {
            $name = $name['id'];
        }
        if ($name != $taxon->getName()->getGuid()) {
            throw new \App\Exceptions\CannotChangeTaxonNameException();
        }
        if ($request->input('taxonRank')) {
            $taxon->setTaxonRank($this->getValue($request->input('taxonRank'), 
                    'TaxonRank', true));
        }
        if ($request->input('parentNameUsage')) {
            $taxon->setParent($this->getValue($request->input('parentNameUsage'),
                    'Taxon'));
        }
        if ($request->input('acceptedNameUsage')) {
            $taxon->setAcceptedNameUsage($this->getValue($request
                    ->input('acceptedNameUsage'), 'Taxon'));
            $taxon->setTaxonomicStatus($this->getValue($request
                    ->input('taxonomicStatus'), 'TaxonomicStatus', 
                    true));
        }
        $taxon->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->flush();
        $transformer = new \App\Transformers\TaxonTransformer();
        $resource = new Fractal\Resource\Item($taxon, $transformer, 'taxa');
        $this->fractal->parseIncludes(['parentNameUsage', 'acceptedNameUsage', 'taxonomicStatus']);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/taxa/{taxon}",
     *     tags={"Taxa"},
     *     summary="Deletes a Taxon record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="Successful delete returns empty response body"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function destroy($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $this->em->remove($taxon);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/regions",
     *     tags={"Taxa", "Regions"},
     *     summary="Gets WGS Regions a Taxon naturally occurs in",
     *     @SWG\Parameter(
     *       in="path",
     *       name="taxon",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Successful response",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Region"
     *           )
     *       )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function showRegions($id)
    {
        $taxon = $this->getTaxon($id);
        $resource = new Fractal\Resource\Collection($taxon->getRegions(), 
                new \App\Transformers\TaxonTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * 
     * @param string $id
     * @return \App\Entities\Taxon
     * @throws NotFoundHttpException
     */
    protected function getTaxon($id): \App\Entities\Taxon
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        return $taxon;
    }

    /**
     * @SWG\Post(
     *     path="/taxa/{taxon}/regions",
     *     tags={"Taxa", "Regions"},
     *     summary="Adds WGS Regions to a Taxon",
     *     description="Route takes an array of region codes in the body; a region won't be added if the Taxon already has it; an error will be thrown if a region doesn't exist.",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Regions to add to the Taxon",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Feature"
     *           )
     *       ),
     *       description="Successful response returns the updated list of Regions for the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $image
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addRegions(Request $request, $id)
    {
        $taxon = $this->getTaxon($id);
        $regions = $request->getContent();
        if ($regions) {
            foreach ($regions as $reg) {
                if (!$taxon->getRegions()->filter(function($region) 
                        use ($reg) {
                    return $region->getRegionCode == $reg;
                })) {
                    $region = $this->em->getRepository('\App\Entities\Region')
                            ->findOneBy(['regionCode' == $reg]);
                    if (!$reqion) {
                        throw new NotFoundHttpException();
                    }
                    $taxon->addRegion($region);
                }
            }
            $this->em->flush();
        }
        $resource = new Fractal\Resource\Collection($taxon->getRegions(), 
                new \App\Transformers\RegionTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/taxa/{taxon}/regions",
     *     tags={"Taxa", "Regions"},
     *     summary="Removes Regions from a Taxon",
     *     description="Route takes an array of region codes in the body; an error will be thrown if the Taxon doesn't have the region or if a region doesn't exist.",
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *       in="path",
     *       name="taxon",
     *       type="string",
     *       format="uuid",
     *       required=true,
     *       description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Regions to remove from the Taxon",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Successful response ",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Region"
     *           )
     *       ),
     *       description="Successful response returns the updated list of Regions for the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeRegions(Request $request, $id)
    {
        $taxon = $this->getTaxon($id);
        $regions = $request->getContent();
        if ($regions) {
            foreach ($regions as $item) {
                $region = $taxon->getRegions()->filter(function($region) 
                        use ($item) {
                    return $region->getRegionCode() == $item;
                });
                if (!$region) {
                    throw new NotFoundHttpException();
                }
                $this->em->remove($region);
            }
            $this->em->flush();
        }
        $resource = new Fractal\Resource\Collection($taxon->getRegions(), 
                new \App\Transformers\RegionTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/taxa/{taxon}/changes",
     *     tags={"Taxa", "Changes"},
     *     summary="Adds a Change to a Taxon",
     *     @SWG\Parameter(
     *       in="path",
     *       name="taxon",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Change to add to the Taxon",
     *         @SWG\Schema(
     *             ref="#/definitions/Change"
     *         )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Feature"
     *           )
     *       ),
     *       description="Successful response returns the updated list of Regions for the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function storeChange(Request $request, $id)
    {
        $taxon = $this->getTaxon($id);
        $change = new \App\Entities\Taxon();
        $change->setFromTaxon($taxon);
        $change->setToTaxon($this->setValue($request->input('toTaxon', 'Taxon')));
        $change->setChangeType($request->input('changeType'));
        $change->setSource($request->input('source'));
        $this->em->persist($change);
        $changes = $taxon->getChanges();
        if ($changes) {
            foreach ($changes as $ch) {
                $ch->setIsCurrent(false);
            }
        }
        $change->setIsCurrent(true);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($change, 
                new \App\Transformer\ChangeTransformer());
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', 
                $request->fullUrl() . '/' . $change->getGuid());
    }

}
