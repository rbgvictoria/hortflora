<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

class TreatmentVersionController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/versions/{version}",
     *     tags={"Treatment Versions"},
     *     summary="Gets a Treatment Version",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment_version",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment Version"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/TreatmentVersion"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input."
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
        $version = $this->getTreatmentVersion($id);
        $resource = new Fractal\Resource\Item($version, 
                new \App\Transformers\TreatmentVersionTransformer);
        $data = $this->fractal->createData($resource);
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/versions/{version}",
     *     tags={"Treatment Versions"},
     *     summary="Updates an existing Treatment Version",
     *     @SWG\Parameter(
     *         in="path",
     *         name="version",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment Version"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="TreatmentVersion object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Treatment"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated TreatmentVersion object",
     *         @SWG\Schema(
     *             ref="#/definitions/TreatmentVersion"
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $version = $this->getTreatmentVersion($id);
        $version->setHtml($request->input('text'));
        $version->setIsUpdated($request->input('isUpdated'));
        $this->em->persist($version);
        if ($request->input('isCurrentVersion') === true) {
            foreach ($version->getTreatment()->getVersions() as $v) {
                $v->setIsCurrentVersion(false);
            }
            $version->setIsCurrentVersion(true);
        }
        $this->em->flush();
        $resource = new Fractal\Resource\Item($version, 
                new \App\Transformers\TreatmentVersionTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/versions/{version}",
     *     tags={"Treatment Versions"},
     *     summary="Deletes a Treatment Version",
     *     @SWG\Parameter(
     *         in="path",
     *         name="version",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment Version"
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
        $version = $this->getTreatmentVersion($id);
        $this->em->remove($version);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * 
     * @param string $id
     * @return \App\Entities\TreatmentVersion
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getTreatmentVersion($id): \App\Entities\TreatmentVersion
    {
        $this->checkUuid($id);
        $version = $this->em->getRepository('\App\Entities\TreatmentVersion')
                ->findOneBy(['guid' => $id]);
        if (!$version) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $version;
    }
    
    
}
