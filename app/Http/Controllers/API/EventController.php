<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;

class EventController extends ApiController
{
    /**
     * @SWG\Post(
     *     path="/events",
     *     tags={"Events"},
     *     summary="Creates a new Event record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Event object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Event"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted Event object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Event"
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new \App\Entities\Event();
        $eventDate = explode('/', $request->input('eventDate'));
        $event->setStartDate(new \DateTime($eventDate[0]));
        if (isset($eventDate[1])) {
            $event->setEndDate(new \DateTime($eventDate[1]));
        }
        $event->setLocation($this->getValue($request->input('location'), 
                'Location'));
        $this->em->persist($event);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($event, 
                new \App\Transformers\EventTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $event->getGuid());
    }

    /**
     * @SWG\Get(
     *     path="/events/{event}",
     *     tags={"Events"},
     *     summary="Gets Event record",
     *     @SWG\Parameter(
     *       in="path",
     *       name="event",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Event"
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show($id)
    {
        $event = $this->em->getRepository('\App\Entities\Event')
                ->findOneBy(['guid' => $id]);
        if (!$event) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $resource = new Fractal\Resource\Item($event, 
                new \App\Transformers\EventTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/events/{event}",
     *     tags={"Events"},
     *     summary="Updates an existing Event record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="event",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Event"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Event object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Event"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Event object",
     *         @SWG\Schema(
     *             ref="#/definitions/Event"
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
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function update(Request $request, $id)
    {
        $event = $this->em->getRepository('\App\Entities\Event')
                ->findOneBy(['guid' => $id]);
        if (!$event) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $eventDate = explode('/', $request->input('eventDate'));
        $event->setStartDate(new \DateTime($eventDate[0]));
        if (isset($eventDate[1])) {
            $event->setEndDate(new \DateTime($eventDate[1]));
        }
        $event->setLocation($this->getValue($request->input('location'), 
                'Location'));
        $this->em->flush();
        $resource = new Fractal\Resource\Item($event, 
                new \App\Transformers\EventTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/events/{event}",
     *     tags={"Events"},
     *     summary="Deletes an Event record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="event",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Event"
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = $this->em->getRepository('\App\Entities\Event')
                ->findOneBy(['guid' => $id]);
        if (!$event) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $this->em->remove($event);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * @SWG\Get(
     *     path="/events/{event}/location",
     *     tags={"Events"},
     *     summary="Gets Location for an Event",
     *     @SWG\Parameter(
     *       in="path",
     *       name="event",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Event"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Location"
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
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showLocation($id)
    {
        $event = $this->em->getRepository('\App\Entities\Event')
                ->findOneBy(['guid' => $id]);
        if (!$event) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $loc = $event->getLocation();
        $resource = new Fractal\Resource\Item($loc, 
                new \App\Transformers\LocationTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
}
