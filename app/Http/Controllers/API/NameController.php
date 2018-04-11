<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Taxon Controller
 */
class NameController extends ApiController
{

    /**
     * @SWG\Get(
     *     path="/names/{name}",
     *     tags={"Names"},
     *     summary="Gets a Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Name"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
     *         )
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
    public function show(Request $request, $id)
    {
        $this->checkUuid($id);
        $includes = 'nameType';
        if ($request->input('include')) {
            $includes .= ',' . $request->input('include');
        }
        $name = app('em')->getRepository('\App\Entities\Name')->findOneBy(['guid' => $id]);
        $transformer = new \App\Transformers\NameTransformer();
        $resource = new Fractal\Resource\Item($name, $transformer, 'names');
        $this->fractal->parseIncludes($includes);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/names",
     *     tags={"Names"},
     *     summary="Inserts a new Name record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Name object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted Name object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
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
     */
    public function store(Request $request)
    {
        $name = new \App\Entities\Name;
        $name->setName($request->input('namePart'));
        $name->setFullName($request->input('scientificName'));
        $name->setAuthorship($request->input('scientificNameAuthorship'));
        $name->setNomenclaturalNote($request->input('nomenclaturalNote'));
        $nameType = $request->input('nameType');
        $name->setNameType($this->em->getRepository('\App\Entities\NameType')
                ->findOneBy(['name' => $nameType['name']]));
        $this->em->persist($name);
        $guid = $name->getGuid();
        $this->em->flush();
        $transformer = new \App\Transformers\NameTransformer();
        $resource = new Fractal\Resource\Item($name, $transformer, 'names');
        $this->fractal->parseIncludes('nameType');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $guid);
    }
    
    /**
     * @SWG\Put(
     *     path="/names/{name}",
     *     tags={"Names"},
     *     summary="Updates an existing Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Name"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Name object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Name object",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
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
     */
    public function update(Request $request, $id)
    {
        $this->checkUuid($id);
        $name = $this->em->getRepository('\App\Entities\Name')
                ->findOneBy(['guid' => $id]);
        if (!$name) {
            throw new NotFoundHttpException();
        }
        $name->setName($request->input('namePart'));
        $name->setFullName($request->input('scientificName'));
        $name->setAuthorship($request->input('scientificNameAuthorship'));
        $name->setNomenclaturalNote($request->input('nomenclaturalNote'));
        $nameType = $request->input('nameType');
        $name->setNameType($this->em->getRepository('\App\Entities\NameType')
                ->findOneBy(['name' => $nameType['name']]));
        $this->em->flush();
        $transformer = new \App\Transformers\NameTransformer();
        $resource = new Fractal\Resource\Item($name, $transformer, 'names');
        $this->fractal->parseIncludes('nameType');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 200);
    }
    
    /**
     * @SWG\Delete(
     *     path="/names/{name}",
     *     tags={"Names"},
     *     summary="Delete a Name record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Name"
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
        $name = $this->em->getRepository('\App\Entities\Name')
                ->findOneBy(['guid' => $id]);
        if (!$name) {
            throw new NotFoundHttpException();
        }
        $this->em->remove($name);
        return response()->json([], 204);
    }
}
