<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;

class TreatmentController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/treatments/{treatment}",
     *     tags={"Treatments"},
     *     summary="Gets a Treatment",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon", "asTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Resources that are embedded by default to exclude; multiple resources can be given, separated by a comma"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Treatment"
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function show(Request $request, $id)
    {
        $treatment = $this->getTreatment($id);
        $transformer = new \App\Transformers\TreatmentTransformer();
        $resource = new Fractal\Resource\Item($treatment, $transformer,
                'treatments');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/treatments/{treatment}",
     *     tags={"Treatments"},
     *     summary="Updates an existing Treatment record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Treatment object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Treatment"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Treatment object",
     *         @SWG\Schema(
     *             ref="#/definitions/Treatment"
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
        $treatment = $this->getTreatment($id);
        $treatment->setAsScientificName($request->input('asScientificName'));
        if ($request->input('asTaxon')) {
            $treatment->setAsTaxon($this->getValue($request->input('asTaxon'), 'TaxonAbstract'));
        }
        if ($request->input('author')) {
            $treatment->setAuthor($this->getValue($request->input('author'), 'Agent'));
        }
        if ($request->input('source')) {
            $treatment->setSource($this->getValue($request->input('source'), 'Source'));
        }
        if ($request->input('forTaxon')) {
            $taxon = $this->getValue($request->input('forTaxon'), 'TaxonAbstract');
            $treatment->setForTaxon($taxon);
            $taxon->addTreatment($treatment);
            foreach ($taxon->getTreatments() as $treat) {
                $treat->setIsCurrentTreatment(false);
            }
        }
        $this->em->flush();
        $resource =  new Fractal\Resource\Item($treatment,
                new \App\Transformers\TreatmentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/treatments/{treatment}",
     *     tags={"Treatments"},
     *     summary="Deletes a Treatment record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
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
        $treatment = $this->getTreatment($id);
        foreach ($treatment->getVersions() as $version) {
            $this->em->remove($version);
        }
        $this->em->remove($treatment);
        $this->em->flush();
        return response()->json([], 204);
    }

    /**
     * @SWG\Get(
     *     path="/treatments/{treatment}/asTaxon",
     *     tags={"Treatments"},
     *     summary="Gets the Taxon for which the treatment was written",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function showAsTaxon(Request $request, Uuid $id)
    {
        $treatment = $this->getTreatment($id);
        $asTaxon = $treatment->getAsTaxon();
        $transformer = new \App\Transformers\TaxonAbstractTransformer();
        $resource = new Fractal\Resource\Item($asTaxon, $transformer, 'taxa');
        $this->fractal->parseIncludes($request->input('include'));
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/treatments/{treatment}/forTaxon",
     *     tags={"Treatments"},
     *     summary="Gets the Taxon to which the Treatment applies",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param \Ramsey\Uuid\Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function showForTaxon(Request $request, Uuid $id)
    {
        $treatment = $this->getTreatment($id);
        $forTaxon = $treatment->getForTaxon();
        $transformer = new \App\Transformers\TaxonAbstractTransformer();
        $resource = new Fractal\Resource\Item($forTaxon, $transformer, 'taxa');
        $this->fractal->parseIncludes($request->input('include'));
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/treatments/{treatment}/versions",
     *     tags={"Treatments"},
     *     summary="Gets all versions of a Treatment",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *             ref="#/definitions/TreatmentVersion"
     *           )
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
    public function showVersions($id)
    {
        $treatment = $this->getTreatment($id);
        $resource = new Fractal\Resource\Collection($treatment->getVersions(),
                new \App\Transformers\TreatmentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/treatments/{treatment}/currentVersion",
     *     tags={"Treatments"},
     *     summary="Gets current version of a Treatment",
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         format="uuid",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *           ref="#/definitions/TreatmentVersion"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param type $id
     * @return type
     */
    public function showCurrentVersion($id)
    {
        $treatment = $this->getTreatment($id);
        $currentVersion = $treatment->getVersions()->filter(function($version) {
            return $version->getIsCurrentVersion();
        })->first();
        $resource = new Fractal\Resource\Item($currentVersion,
                new \App\Transformers\TreatmentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Post(
     *     path="/treatments/{treatment}/versions",
     *     tags={"Treatments"},
     *     summary="Creates a new version of a Treatment",
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *         in="path",
     *         name="treatment",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Treatment"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Treatment object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Treatment"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successful response returns the Treatment Version",
     *         @SWG\Schema(
     *           ref="#/definitions/Treatment"
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function storeVersion(Request $request, $id)
    {
        $treatment = $this->getTreatment($id);
        $version = new \App\Entities\TreatmentVersion();
        $version->setTreatment($treatment);
        $version->setHtml($request->input('text'));
        $version->setIsUpdated($request->input('isUpdated') ?: true);
        $this->em->persist($version);
        if ($request->input('isCurrentVersion') !== false) {
            foreach ($treatment->getVersions() as $v) {
                $v->setIsCurrentVersion(false);
            }
            $version->setIsCurrentVersion(true);
        }
        else {
            $version->setIsCurrentVersion(false);
        }
        $this->em->flush();
        $resource = new Fractal\Resource\Item($version,
                new \App\Transformers\TreatmentVersionTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL')
                . '/api/treatment-versions/' . $version->getGuid());
    }

    /**
     *
     * @param string $id
     * @return \App\Entities\Treatment
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getTreatment($id): \App\Entities\Treatment
    {
        $this->checkUuid($id);
        $treatment = $this->em->getRepository('\App\Entities\Treatment')
                ->findOneBy(['guid' => $id]);
        if (!$treatment) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $treatment;
    }
}
