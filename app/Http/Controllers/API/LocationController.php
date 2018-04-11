<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;

class LocationController extends ApiController
{
    /**
     * @SWG\Post(
     *     path="/locations",
     *     tags={"Locations"},
     *     summary="Creates a new Location record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Location object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Location"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the inserted Location object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Location"
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
        $loc = new \App\Entities\Location();
        $loc->setCountry($request->input('country'));
        $loc->setCountryCode($request->input('countryCode'));
        $loc->setStateProvince($request->input('stateProvince'));
        $loc->setLocality($request->input('verbatimLocality'));
        $loc->setDecimalLatitude($request->input('decimalLatitude'));
        $loc->setDecimalLongitude($request->input('decimalLongitude'));
        $this->em->persist($loc);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($loc, 
                new \App\Transformers\LocationTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', 
                $request->fullUrl() . '/' . $loc->getGuid());
    }

    /**
     * @SWG\Get(
     *     path="/locations/{location}",
     *     tags={"Locations"},
     *     summary="Gets Location record",
     *     @SWG\Parameter(
     *       in="path",
     *       name="location",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Location"
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show($id)
    {
        $loc = $this->em->getRepository('\App\Entities\Location')
                ->findOneBy(['guid' => $id]);
        if (!$loc) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $resource = new Fractal\Resource\Item($loc, 
                new \App\Transformers\LocationTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/locations/{locations}",
     *     tags={"Locations"},
     *     summary="Updates an existing Location record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="location",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Location"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Location object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Location"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Location object",
     *         @SWG\Schema(
     *             ref="#/definitions/Location"
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
        $loc = $this->em->getRepository('\App\Entities\Location')
                ->findOneBy(['guid' => $id]);
        if (!$loc) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $loc->setCountry($request->input('country'));
        $loc->setCountryCode($request->input('countryCode'));
        $loc->setStateProvince($request->input('stateProvince'));
        $loc->setLocality($request->input('verbatimLocality'));
        $loc->setDecimalLatitude($request->input('decimalLatitude'));
        $loc->setDecimalLongitude($request->input('decimalLongitude'));
        $this->em->flush();
        $resource = new Fractal\Resource\Item($loc, 
                new \App\Transformers\LocationTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/locations/{locations}",
     *     tags={"Locations"},
     *     summary="Deletes an Location record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="location",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Location"
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroy($id)
    {
        $loc = $this->em->getRepository('\App\Entities\Location')
                ->findOneBy(['guid' => $id]);
        if (!$loc) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $this->em->remove($loc);
        $this->em->flush();
        return response()->json([], 204);
    }
}
