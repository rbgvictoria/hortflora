<?php

namespace App\Http\Controllers\API;

use App\Transformers\NameTransformer;
use App\Transformers\ReferenceTransformer;
use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Swagger\Annotations as SWG;

class TaxonAbstractController extends ApiController
{

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/name",
     *     tags={"Taxa", "Names"},
     *     summary="Gets the Name record for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/name",
     *     tags={"Cultivars", "Names"},
     *     summary="Gets the Name record for a Cultivar",
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
     *             ref="#/definitions/Name"
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
     *         description="The requested Cultivar resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/name",
     *     tags={"Horticultural Groups", "Names"},
     *     summary="Gets the Name record for a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Name"
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
     *         description="The requested Cultivar Group resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasScientificName($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $name = $taxon->getName();
        $resource = new Fractal\Resource\Item($name, new NameTransformer, 'names');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/nameAccordingTo",
     *     tags={"Taxa"},
     *     summary="Gets the Reference for a Name Usage (""sensu"")",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/nameAccordingTo",
     *     tags={"Cultivars"},
     *     summary="Gets the Reference for a Name Usage (""sensu"")",
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
     *             ref="#/definitions/Reference"
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/nameAccordingTo",
     *     tags={"Horticultural Groups"},
     *     summary="Gets the Reference for a Name Usage (""sensu"")",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
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
     *         description="The requested Cultivar Group resource could not be found",
     *         @SWG\Schema(
     *             ref="#/definitions/Exception"
     *         )
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasNameAccordingTo($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $nameAccordingTo = $taxon->getNameAccordingTo();
        if ($nameAccordingTo) {
            $resource = new Fractal\Resource\Item($nameAccordingTo,
                    new ReferenceTransformer, 'references');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/treatments",
     *     tags={"Taxa", "Treatments"},
     *     summary="Gets descriptions for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/treatments",
     *     tags={"Cultivars", "Treatments"},
     *     summary="Gets descriptions for a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/treatments",
     *     tags={"Horticultural Groups", "Treatments"},
     *     summary="Gets descriptions for a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasTreatments($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $treatments = $taxon->getTreatments();
        if ($treatments) {
            $resource = new Fractal\Resource\Collection($treatments,
                    new \App\Transformers\TreatmentTransformer(), 'treatments');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/currentTreatment",
     *     tags={"Taxa"},
     *     summary="Gets current description for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
     *         ),
     *         collectionFormat="csv",
     *         description="Resources that are embedded by default to exclude; multiple resources can be given, separated by a comma"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Treatment"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/currentTreatment",
     *     tags={"Cultivars"},
     *     summary="Gets current description for a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
     *         ),
     *         collectionFormat="csv",
     *         description="Resources that are embedded by default to exclude; multiple resources can be given, separated by a comma"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Treatment"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/currentTreatment",
     *     tags={"Horticultural Groups"},
     *     summary="Gets current description for a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"forTaxon"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include; multiple resources can be added, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"asTaxon", "acceptedNameUsage", "creator", "modifiedBy"}
     *         ),
     *         collectionFormat="csv",
     *         description="Resources that are embedded by default to exclude; multiple resources can be given, separated by a comma"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Treatment"
     *             )
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasCurrentTreatment($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $currentTreatment = $taxon->getTreatments()
                ->filter(function($treatment) {
                    return $treatment->getIsCurrentTreatment();
                })->first();
        if ($currentTreatment) {
            $resource = new Fractal\Resource\Item($currentTreatment,
                    new \App\Transformers\TreatmentTransformer(), 'treatments');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/vernacularNames",
     *     tags={"Taxa", "Vernacular Names"},
     *     summary="Gets vernacular names for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/VernacularName"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/vernacularNames",
     *     tags={"Cultivars", "Vernacular Names"},
     *     summary="Gets vernacular names for a Cultivars",
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
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/VernacularName"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/vernacularNames",
     *     tags={"Horticultural Groups", "Vernacular Names"},
     *     summary="Gets vernacular names for a Horticultural Groups",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/VernacularName"
     *             )
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasVernacularNames($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $vernacularNames = $taxon->getVernacularNames();
        if ($vernacularNames) {
            $resource = new Fractal\Resource\Collection($vernacularNames,
                    new \App\Transformers\VernacularNameTransformer, 
                    'vernacular-names');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }
    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/changes",
     *     tags={"Taxa", "Changes"},
     *     summary="Gets changes for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Change"
     *             )
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
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasChanges($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $changes = $taxon->getChanges();
        $resource = new Fractal\Resource\Collection($changes,
                new \App\Transformers\ChangeTransformer, 'changes');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * 
     * @SWG\Get(
     *     path="/taxa/{taxon}/heroImage",
     *     tags={"Taxa"},
     *     summary="Gets the hero image for a Taxon",
     *     description="If a taxon has images, it will always have a hero image; if no hero image has been set, it will be one of the images with the highest rating.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/heroImage",
     *     tags={"Cultivars"},
     *     summary="Gets the hero image for a Cultivar",
     *     description="If a taxon has images, it will always have a hero image; if no hero image has been set, it will be one of the images with the highest rating.",
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
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/heroImage",
     *     tags={"Horticultural Groups"},
     *     summary="Gets the hero image for a Cultivar Group",
     *     description="If a taxon has images, it will always have a hero image; if no hero image has been set, it will be one of the images with the highest rating.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * @param  Uuid $id UUID for the taxon
     * @return \Illuminate\Http\Response
     */
    public function hasHeroImage($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $heroImage = $this->em->getRepository('\App\Entities\Image')
                ->getHeroImageForTaxon($taxon);
        if ($heroImage) {
            $transformer = new \App\Transformers\ImageTransformer();
            $transformer->setDefaultIncludes(['accessPoints']);
            $resource = new Fractal\Resource\Item($heroImage, 
                    $transformer, 'images');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/images",
     *     tags={"Taxa", "Images"},
     *     summary="Gets images for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="perPage",
     *       type="integer",
     *       format="int32",
     *       default=20,
     *       description="The number of results to return."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="page",
     *       type="integer",
     *       format="int32",
     *       default=1,
     *       description="The page of query results to return."
     *     ),
      *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/images",
     *     tags={"Cultivars", "Images"},
     *     summary="Gets images for a Cultivar",
     *     @SWG\Parameter(
     *         in="path",
     *         name="cultivar",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar"
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="perPage",
     *       type="integer",
     *       format="int32",
     *       default=20,
     *       description="The number of results to return."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="page",
     *       type="integer",
     *       format="int32",
     *       default=1,
     *       description="The page of query results to return."
     *     ),
      *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/images",
     *     tags={"Horticultural Groups", "Images"},
     *     summary="Gets images for a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="perPage",
     *       type="integer",
     *       format="int32",
     *       default=20,
     *       description="The number of results to return."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="page",
     *       type="integer",
     *       format="int32",
     *       default=1,
     *       description="The page of query results to return."
     *     ),
      *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Image"
     *             )
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
     * @param \Illuminate\Http\Request $request
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasImages(Request $request, $id) {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $perPage = $request->input('perPage') ?: 20;
        $page = $request->input('page') ?: 1;
        $paginator = $this->em->getRepository('App\Entities\Image')
                ->getImagesForTaxon($taxon, $perPage, $page);
        $paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
        $images = $paginator->getCollection();
        $resource = new Fractal\Resource\Collection($images,
                new \App\Transformers\ImageTransformer, 'images');
        $resource->setPaginator($paginatorAdapter);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/creator",
     *     tags={"Taxa"},
     *     summary="Gets creator of a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
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
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/creator",
     *     tags={"Cultivars"},
     *     summary="Gets creator of a Cultivar",
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
     *             ref="#/definitions/Agent"
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/creator",
     *     tags={"Horticultural Groups"},
     *     summary="Gets creator of a Cultivar Group resource",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
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
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasCreator($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $agent = $taxon->getCreatedBy();
        $resource = new Fractal\Resource\Item($agent, 
                new \App\Transformers\AgentTransformer, 'agents');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/modifiedBy",
     *     tags={"Taxa"},
     *     summary="Gets Agent who last modified a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
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
     * ),
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/modifiedBy",
     *     tags={"Cultivars"},
     *     summary="Gets Agent who last modified a Cultivar resource",
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
     *             ref="#/definitions/Agent"
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/modifiedBy",
     *     tags={"Horticultural Groups"},
     *     summary="Gets Agent who last modified a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
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
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasModifiedBy($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $agent = $taxon->getModifiedBy();
        if ($agent) {
            $resource = new Fractal\Resource\Item($agent, 
                    new \App\Transformers\AgentTransformer, 'agents');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }
    
    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/references",
     *     tags={"Taxa", "References"},
     *     summary="Gets references for a Taxon",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/TaxonCitation"
     *             )
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
     * @SWG\Get(
     *     path="/cultivars/{cultivar}/references",
     *     tags={"Cultivars", "References"},
     *     summary="Gets references for a Cultivar",
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
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/TaxonCitation"
     *             )
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
     * ),
     * @SWG\Get(
     *     path="/horticultural-groups/{group}/references",
     *     tags={"Horticultural Groups", "References"},
     *     summary="Gets references for a Cultivar Group",
     *     @SWG\Parameter(
     *         in="path",
     *         name="group",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Cultivar Group"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/TaxonCitation"
     *             )
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
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasReferences($id)
    {
        $this->checkUuid($id);
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $references = $taxon->getTaxonReferences();
        $transformer = new \App\Transformers\ReferenceTransformer();
        $resource = new Fractal\Resource\Collection($references, $transformer, 'references');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/taxa/{taxon}/treatments",
     *     tags={"Taxa", "Treatments"},
     *     summary="Creates a new Treatment for a taxon",
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Taxon"
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
     *         description="Successful response returns the treatments for the Taxon",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 ref="#/definitions/Treatment"
     *             )
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
     * @param string $id   UUID of the taxon
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function storeTreatment(Request $request, $id)
    {
        $taxon = $this->em->getRepository('\App\Entities\TaxonAbstract')
                ->findOneBy(['guid' => $id]);
        if (!$taxon) {
            throw new NotFoundHttpException();
        }
        $treatment = new \App\Entities\Treatment();
        $treatment->setAsTaxon($request->input('asTaxon') 
                ?  $this->getValue($this->input('asTaxon'), 'TaxonAbstract')
                : $taxon);
        $treatment->setForTaxon($taxon);
        $treatment->setAsScientificName($request->input('asScientificName'));
        if ($request->input('author')) {
            $treatment->setAuthor($this->getValue($request->input('author'), 
                    'Agent'));
        }
        if ($request->input('source')) {
            $treatment->setSource($this->getValue($request->input('source'), 
                    'Reference'));
        }
        $this->em->persist($treatment);
        $taxon->addTreatment($treatment);
        foreach ($taxon->getTreatments() as $treat) {
            $treat->setIsCurrentTreatment(false);
        }
        $treatment->setIsCurrentTreatment(true);
        $versions = $request->input('versions.data') ?: $this->input('versions');
        $text = $versions[0]['text'];
        $treatmentVersion = new \App\Entities\TreatmentVersion();
        $treatmentVersion->setTreatment($treatment);
        $treatmentVersion->setHtml($text);
        $treatmentVersion->setIsCurrentVersion(true);
        $treatmentVersion->setIsUpdated((isset($versions[0]['isUpdated'])) 
                ? $versions[0]['isUpdated'] : false);
        $this->em->persist($treatmentVersion);
        $this->em->flush();
        $resource = new Fractal\Resource\Collection($taxon->getTreatments(), 
                new \App\Transformers\TreatmentTransformer);
        $this->fractal->parseIncludes('treatments');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', 
                $request->fullUrl());
    }
}
