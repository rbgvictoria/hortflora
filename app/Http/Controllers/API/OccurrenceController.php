<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use \Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;

class OccurrenceController extends ApiController
{
    
    /**
     * @SWG\Get(
     *     path="/occurrences/{occurrence}",
     *     tags={"Occurrences"},
     *     summary="Gets Occurrence record",
     *     @SWG\Parameter(
     *       in="path",
     *       name="occurrence",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Occurrence"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Occurrence"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Uuid $id)
    {
        $occurrence = $this->em->getRepository('\App\Entities\Occurrence')
                ->findOneBy(['guid' => $id]);
        if (!$occurrence) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $transformer = new \App\Transformers\OccurrenceTransformer();
        $resource = new Fractal\Resource\Item($occurrence, $transformer, 'occurrences');
        $this->fractal->parseIncludes($request->input('include') ?: []);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/occurrences",
     *     tags={"Occurrences"},
     *     summary="Creates a new Occurrence record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Occurrence object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Occurrence"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted Occurrence object in the response body and its URL in the Location header",
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $occurrence = new \App\Entities\Occurrence();
        $occurrence->setCatalogNumber($request->input('catalogNumber'));
        $occurrence->setRecordedBy($request->input('recordedBy'));
        $occurrence->setRecordNumber($request->input('recordNumber'));
        if ($request->input('event')) {
            $occurrence->setEvent($this->getValue($request->input('event'), 
                    '\App\Entities\Event'));
        }
        $this->em->persist($occurrence);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($occurrence, 
                new \App\Transformers\OccurrenceTransformer, 'occurrences');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $occurrence->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/occurrences/{occurrence}",
     *     tags={"Occurrences"},
     *     summary="Updates an existing Occurrence record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="occurrence",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Occurrence"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Occurrence object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Occurrence"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Occurrence object",
     *         @SWG\Schema(
     *             ref="#/definitions/Occurrence"
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
     * @param \Illuminate\Http\Request $request
     * @param string $id    UUID
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function update(Request $request, $id)
    {
        $occurrence = $this->em->getRepository('\App\Entities\Occurrence')
                ->findOneBy(['guid' => $id]);
        if (!$occurrence) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        if ($request->input('catalogNumber')) {
            $occurrence->setCatalogNumber($request->input('catalogNumber'));
        }
        if ($request->input('recordedBy')) {
            $occurrence->setRecordedBy($request->input('recordedBy'));
        }
        if ($request->input('recordNumber')) {
            $occurrence->setRecordNumber($request->input('recordNumber'));
        }
        if ($request->input('event')) {
            $occurrence->setEvent($this->getValue($request->input('event'), 
                    '\App\Entities\Event'));
        }
        $this->em->flush();
        $resource = new Fractal\Resource\Item($occurrence, 
                new \App\Transformers\OccurrenceTransformer, 'occurrences');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/occurrences/{occurrence}",
     *     tags={"Occurrences"},
     *     summary="Deletes an Occurrence record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="occurrence",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Occurrence"
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroy($id)
    {
        $occurrence = $this->em->getRepository('\App\Entities\Occurrence')
                ->findOneBy(['guid' => $id]);
        if (!$occurrence) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $this->em->remove($occurrence);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * @SWG\Get(
     *     path="/occurrences/{occurrence}/event",
     *     tags={"Occurrences"},
     *     summary="Gets Event for an Occurrence",
     *     @SWG\Parameter(
     *       in="path",
     *       name="occurrence",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Occurrence"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Event"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showEvent($id)
    {
        $occurrence = $this->em->getRepository('\App\Entities\Occurrence')
                ->findOneBy(['guid' => $id]);
        if (!$occurrence) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $event = $occurrence->getEvent();
        $resource = new Fractal\Resource\Item($event, 
                new \App\Transformers\EventTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
}
