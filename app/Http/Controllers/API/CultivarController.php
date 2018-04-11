<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CultivarController extends TaxonAbstractController
{
    /**
     * @SWG\Get(
     *     path="/cultivars/{cultivar}",
     *     tags={"Cultivars"},
     *     summary="Gets a Cultivar resource",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         required=true,
     *         type="string",
     *         description="UUID of the Cultivar resource"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         items=@SWG\Items(
     *             type="string",
     *             enum={"creator", "modifiedBy", "treatments", "changes", "horticulturalGroup"}
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
     *             ref="#/definitions/Cultivar"
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
     * @param  \Ramsey\Uuid\Uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Uuid $id)
    {
        $this->checkUuid($id);
        $includes = 'horticulturalGroup,' . $request->input('include');
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        $resource = new Fractal\Resource\Item($cultivar, 
                new \App\Transformers\CultivarTransformer, 'cultivars');
        $this->fractal->parseIncludes($includes);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/acceptedNameUsage",
     *     tags={"Cultivars"},
     *     summary="Gets accepted name usage of a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Cultivar"
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
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasAcceptedNameUsage(Uuid $id)
    {
        $this->checkUuid($id);
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        if (!$cultivar) {
            throw new NotFoundHttpException();
        }
        $acceptedNameUsage = $cultivar->getAcceptedNameUsage();
        if ($acceptedNameUsage) {
            $resource = new Fractal\Resource\Item($acceptedNameUsage, 
                    new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }
    
    /**
     * 
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/taxon",
     *     tags={"Cultivars"},
     *     summary="Gets Taxon of a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
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
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function hasTaxon(Uuid $id)
    {
        $this->checkUuid($id);
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        if (!$cultivar) {
            throw new NotFoundHttpException();
        }
        $taxon = $cultivar->getTaxon();
        $resource = new Fractal\Resource\Item($taxon, 
                new \App\Transformers\TaxonTransformer, 'cultivars');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * 
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/horticulturalGroup",
     *     tags={"Cultivars"},
     *     summary="Gets Cultivar Group of a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
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
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function hasHorticulturalGroup(Uuid $id)
    {
        $this->checkUuid($id);
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        if (!$cultivar) {
            throw new NotFoundHttpException();
        }
        $horticulturalGroup = $cultivar->getHorticulturalGroup();
        if ($horticulturalGroup) {
            $resource = new Fractal\Resource\Item($horticulturalGroup, 
                    new \App\Transformers\HorticulturalGroupTransformer);
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }
    
    /**
     * @SWG\Post(
     *     path="/cultivars",
     *     tags={"Cultivars"},
     *     summary="Creates a new Cultivar record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Cultivar object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Cultivar"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted Cultivar object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Cultivar"
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
        $cultivar = new \App\Entities\Cultivar();
        $name = $request->input('name');
        if (is_array($name)) {
            $name = $name['id'];
        }
        $cultivar->setName($this->em->getRepository('\App\Entities\Name')
                ->findOneBy(['guid' => $name]));
        $taxon = $request->input('taxon');
        if (is_array($taxon)) {
            $taxon = $taxon['id'];
        }
        $cultivar->setTaxon($this->em->getRepository('\App\Entities\Taxon'))
                ->findOneBy(['guid' => $taxon]);
        $horticulturalGroup = $request->input('horticulturalGroup');
        if (is_array($horticulturalGroup)) {
            $horticulturalGroup = $horticulturalGroup['id'];
        }
        $cultivar->setHorticulturalGroup($this->em
                ->getRepository('\App\Entities\HorticulturalGroup'))
                ->findOneBy(['guid' => $horticulturalGroup]);
        $cultivar->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->persist($cultivar);
        $this->em->flush();
        $transformer = new \App\Transformers\CultivarTransformer();
        $resource = new Fractal\Resource\Item($cultivar, $transformer, 'taxa');
        $this->fractal->parseIncludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $cultivar->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/cultivars/{cultivar}",
     *     tags={"Cultivars"},
     *     summary="Updates an existing Cultivar record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Cultivar object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Cultivar"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Cultivar object",
     *         @SWG\Schema(
     *             ref="#/definitions/Cultivar"
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
        $this->checkUuid($id);
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        if (!$cultivar) {
            throw new NotFoundHttpException();
        }
        $name = $request->input('name');
        if (is_array($name)) {
            $name = $name['id'];
        }
        if ($name != $cultivar->getName()->getGuid()) {
            throw new \App\Exceptions\CannotChangeTaxonNameException();
        }
        $taxon = $request->input('taxon');
        if (is_array($taxon)) {
            $taxon = $taxon['id'];
        }
        $cultivar->setTaxon($this->em->getRepository('\App\Entities\Taxon'))
                ->findOneBy(['guid' => $taxon]);
        $horticulturalGroup = $request->input('horticulturalGroup');
        if (is_array($horticulturalGroup)) {
            $horticulturalGroup = $horticulturalGroup['id'];
        }
        $cultivar->setHorticulturalGroup($this->em
                ->getRepository('\App\Entities\HorticulturalGroup'))
                ->findOneBy(['guid' => $horticulturalGroup]);
        $cultivar->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->flush();
        $transformer = new \App\Transformers\CultivarTransformer();
        $resource = new Fractal\Resource\Item($cultivar, $transformer, 'cultivars');
        $this->fractal->parseIncludes(['taxon']);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/cultivars/{cultivar}",
     *     tags={"Cultivars"},
     *     summary="Deletes a Cultivar record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
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
        $cultivar = $this->em->getRepository('\App\Entities\Cultivar')
                ->findOneBy(['guid' => $id]);
        if (!$cultivar) {
            throw new NotFoundHttpException();
        }
        $this->em->remove($cultivar);
        $this->em->flush();
        return response()->json([], 204);
    }
}
