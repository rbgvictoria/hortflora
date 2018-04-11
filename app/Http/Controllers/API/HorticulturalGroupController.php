<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Fractal;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;

class HorticulturalGroupController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/horticultural-groups/{group}",
     *     tags={"Horticultural Groups"},
     *     summary="Gets a Horticultural Group resource",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         required=true,
     *         type="string",
     *         description="UUID of the Horticultural Group resource"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         items=@SWG\Items(
     *             type="string",
     *             enum={"creator", "modifiedBy", "treatments", "changes", "cultivars"}
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
     *             enum={"name", "acceptedNameUsage", "nameAccordingTo", "taxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Linked resources to exclude from the result; the enumerated resources are included by default, but can be excluded if they are not wanted in the result."
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response.",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
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
        $this->getHorticulturalGroup($id);
        $resource = new Fractal\Resource\Item($horticulturalGroup, 
                new \App\Transformers\HorticulturalGroupTransformer, 'horticultural-groups');
        $this->fractal->parseIncludes($includes);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/acceptedNameUsage",
     *     tags={"Horticultural Groups"},
     *     summary="Gets accepted name usage of a Horticultural Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Horticultural Group"
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
     *             ref="#/definitions/Exception"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found",
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
        $this->getHorticulturalGroup($id);
        $acceptedNameUsage = $horticulturalGroup->getAcceptedNameUsage();
        if ($acceptedNameUsage) {
            $resource = new Fractal\Resource\Item($acceptedNameUsage, 
                    new \App\Transformers\HorticulturalGroupTransformer, 
                    'horticultural-groups');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }
    
    
    /**
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/taxon",
     *     tags={"Horticultural Groups"},
     *     summary="Gets Taxon of a Horticultural Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Horticultural Group"
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
     *         description="The requested resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasTaxon($id)
    {
        $this->getHorticulturalGroup($id);
        $taxon = $horticulturalGroup->getTaxon();
        $resource = new Fractal\Resource\Item($taxon, 
                new \App\Transformers\TaxonTransformer, 'taxa');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    
    /**
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/members",
     *     tags={"Horticultural Groups"},
     *     summary="Gets members of a Horticultural Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Horticultural Group"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Successful response",
     *       @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *           ref="#/definitions/TaxonAbstract"
     *         )
     *       )
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
     *         description="The requested resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasMembers($id)
    {
        $this->getHorticulturalGroup($id);
        $cultivars = $horticulturalGroup->getMembers();
        $resource = new Fractal\Resource\Collections($cultivars, 
                new \App\Transformers\CultivarTransformer, 'cultivars');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/horticultural-groups",
     *     tags={"Horticultural Groups"},
     *     summary="Creates a new HorticulturalGroup record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="HorticulturalGroup object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted HorticulturalGroup object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
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
        $horticulturalGroup = new \App\Entities\HorticulturalGroup();
        $name = $request->input('name');
        if (is_array($name)) {
            $name = $name['id'];
        }
        $horticulturalGroup->setName($this->em->getRepository('\App\Entities\Name')
                ->findOneBy(['guid' => $name]));
        $taxon = $request->input('taxon');
        if (is_array($taxon)) {
            $taxon = $taxon['id'];
        }
        $horticulturalGroup->setTaxon($this->em->getRepository('\App\Entities\Taxon'))
                ->findOneBy(['guid' => $taxon]);
        $horticulturalGroup->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->persist($horticulturalGroup);
        $this->em->flush();
        $transformer = new \App\Transformers\HorticulturalGroupTransformer();
        $resource = new Fractal\Resource\Item($horticulturalGroup, $transformer, 
                'horticultural-groups');
        $this->fractal->parseIncludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $horticulturalGroup->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/horticultural-groups/{group}",
     *     tags={"Horticultural Groups"},
     *     summary="Updates an existing HorticulturalGroup record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the HorticulturalGroup"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="HorticulturalGroup object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated HorticulturalGroup object",
     *         @SWG\Schema(
     *             ref="#/definitions/HorticulturalGroup"
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
     * @param type $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     * @throws \App\Exceptions\CannotChangeTaxonNameException
     */
    public function update(Request $request, $id)
    {
        $this->getHorticulturalGroup($id);
        $name = $request->input('name');
        if (is_array($name)) {
            $name = $name['id'];
        }
        if ($name != $horticulturalGroup->getName()->getGuid()) {
            throw new \App\Exceptions\CannotChangeTaxonNameException();
        }
        $taxon = $request->input('taxon');
        if (is_array($taxon)) {
            $taxon = $taxon['id'];
        }
        $horticulturalGroup->setTaxon($this->em->getRepository('\App\Entities\Taxon'))
                ->findOneBy(['guid' => $taxon]);
        $horticulturalGroup->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->flush();
        $transformer = new \App\Transformers\HorticulturalGroupTransformer();
        $resource = new Fractal\Resource\Item($horticulturalGroup, $transformer, 
                'horticultural-groups');
        $this->fractal->parseIncludes(['taxon']);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/horticultural-groups/{group}",
     *     tags={"Horticultural Groups"},
     *     summary="Deletes a HorticulturalGroup record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the HorticulturalGroup"
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
        $this->getHorticulturalGroup($id);
        $this->em->remove($horticulturalGroup);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * 
     * @param type $id
     * @returns \App\Entities\HorticulturalGroup
     * @throws NotFoundHttpException
     */
    protected function getHorticulturalGroup($id): \App\Entities\HorticulturalGroup
    {
        $this->checkUuid($id);
        $horticulturalGroup = $this->em->getRepository('\App\Entities\HorticulturalGroup')
                ->findOneBy(['guid' => $id]);
        if (!$horticulturalGroup) {
            throw new NotFoundHttpException();
        }
    }
}
