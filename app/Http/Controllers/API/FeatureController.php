<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;

class FeatureController extends ApiController
{

    /**
     * @SWG\Get(
     *     path="/features/{feature}",
     *     tags={"Images"},
     *     summary="Gets Feature",
     *     @SWG\Parameter(
     *       in="path",
     *       name="feature",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Occurrence"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Feature"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param \Ramsey\Uuid\UUid $id
     * @return \Illuminate\Http\Response
     */
    public function show(UUid $id)
    {
        $feature = $this->em->getRepository('\App\Entities\Feature')
                ->findOneBy(['guid' => $id]);
        if (!$feature) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $transformer = new \App\Transformers\ImageFeatureTransformer();
        $resource = new Fractal\Resource\Item($feature, $transformer, 'features');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
}
