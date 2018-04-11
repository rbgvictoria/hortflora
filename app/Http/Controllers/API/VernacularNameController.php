<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VernacularNameController extends ApiController
{

    /**
     * @SWG\Get(
     *     path="/vernacular-names/{vernacular_name}",
     *     tags={"Vernacular Names"},
     *     summary="Gets a Vernacular Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="vernacular_name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Vernacular Name"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/VernacularName"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->checkUuid($id);
        $name = $this->em->getRepository('\App\Entities\VernacularName')
                ->findOneBy(['guid' => $id]);
        if (!$name) {
            throw new NotFoundHttpException();
        }
        $transformer = new \App\Transformers\VernacularNameTransformer();
        $resource = new Fractal\Resource\Item($name, $transformer, 
                'vernacular-names');
        $this->fractal->parseIncludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Put(
     *     path="/vernacular-names/{vernacular_name}",
     *     tags={"Vernacular Names"},
     *     summary="Updates an existing Vernacular Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="vernacular_name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Vernacular Name"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="VernacularName object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/VernacularName"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated VernacularName object",
     *         @SWG\Schema(
     *             ref="#/definitions/VernacularName"
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
     * @param string $id    Uuid
     * @throws NotFoundHttpException
     */
    public function update(Request $request, $id)
    {
        $vernacularName = $this->em->getRepository('\App\Entities\VernacularName')
                ->findOneBy(['guid' => $id]);
        if (!$vernacularName) {
            throw new NotFoundHttpException();
        }
        $vernacularName->setVernacularName($request->input('vernacularName'));
        $vernacularName->setIsPreferredName($request->input('isPreferredName'));
        $vernacularName->setVernacularNameUsage($request->input('vernacularNameUsage'));
        $vernacularName->setLanguage($request->input('language'));
        $vernacularName->setOrganismPart($request->input('organismPart'));
        $vernacularName->setSource($request->input('source'));
        $vernacularName->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->flush();
        
        $transformer = new \App\Transformers\VernacularNameTransformer();
        $resource = new Fractal\Resource\Item($vernacularName, $transformer, 'vernacular-names');
        $this->fractal->parseIncludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/vernacular-names",
     *     tags={"Vernacular Names"},
     *     summary="Inserts a new Vernacular Name record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="VernacularName object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/VernacularName"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted VernacularName object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/VernacularName"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param Request $request
     * @return type
     * @throws NotFoundHttpException
     */
    public function store(Request $request)
    {
        $taxon = $this->em->getRepository('\App\Entities\Taxon')
                ->findOneBy(['guid' => $request->input('taxon')]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $vernacularName = new \App\Entities\VernacularName();
        $vernacularName->setTaxon($taxon);
        $vernacularName->setVernacularName($request->input('vernacularName'));
        $vernacularName->setIsPreferredName($request->input('isPreferredName'));
        $vernacularName->setVernacularNameUsage($request->input('vernacularNameUsage'));
        $vernacularName->setLanguage($request->input('language'));
        $vernacularName->setOrganismPart($request->input('organismPart'));
        $vernacularName->setSource($request->input('source'));
        $vernacularName->setTaxonRemarks($request->input('taxonRemarks'));
        $this->em->persist($vernacularName);
        $guid = $vernacularName->getGuid();
        $this->em->flush();
        
        $transformer = new \App\Transformers\VernacularNameTransformer();
        $resource = new Fractal\Resource\Item($vernacularName, $transformer, 'vernacular-names');
        $this->fractal->parseIncludes('taxon');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') . '/vernacular-names/' . $guid);
    }
    
    /**
     * @SWG\Delete(
     *     path="/vernacular-names/{vernacular_name}",
     *     tags={"Vernacular Names"},
     *     summary="Delete a Vernacular Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="vernacular_name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Vernacular Name"
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
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function destroy($id)
    {
        $vernacularName = $this->em->getRepository('\App\Entities\VernacularName')
                ->findOneBy(['guid' => $id]);
        if (!$vernacularName) {
            throw new NotFoundHttpException();
        }
        $this->em->remove($vernacularName);
        $this->em->flush();
        return response()->json([], 204);
    }
}
