<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;

class ChangeController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/changes/{change}",
     *     tags={"Changes"},
     *     summary="Gets a Change resource",
     *     @SWG\Parameter(
     *       in="path",
     *       name="change",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Change"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Change"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid request"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $change = $this->getChange($id);
        $resource = new Fractal\Resource\Item($change, 
                new \App\Transformers\ChangeTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/changes/{change}",
     *     tags={"Changes"},
     *     summary="Updates an existing Change record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="change",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Change"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Agent object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Change"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Change object",
     *         @SWG\Schema(
     *             ref="#/definitions/Change"
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
     */
    public function update(Request $request, $id)
    {
        $change = $this->getChange($id);
        $change->setToTaxon($this->setValue($request->input('toTaxon', 'Taxon')));
        $change->setChangeType($this->getValue($request->input('changeType'), 
                'ChangeType', true));
        $change->setSource($request->input('source'));
        if ($change->getIsCurrent() === false 
                && $request->input('isCurrent') === true) {
            foreach ($change->getFromTaxon()->getChanges() as $ch) {
                $ch->setIsCurrent(false);
            }
            $change->setIsCurrent(true);
        }
        elseif ($change->getIsCurrent() === true 
                && $request->input('isCurrent') === false) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
        $this->em->flush();
        $resource = new Fractal\Resource\Item($change, 
                new \App\Transformers\ChangeTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/changes/{change}",
     *     tags={"Changes"},
     *     summary="Deletes a Change record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="change",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Change"
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
        $change = $this->getChange($id);
        $this->em->remove($change);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    protected function getChange($id): \App\Entities\Change
    {
        $change = $this->em->getRepository('\App\Entities\Change')
                ->findOneBy(['guid' => $id]);
        if (!$change) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $change;
    }
}
