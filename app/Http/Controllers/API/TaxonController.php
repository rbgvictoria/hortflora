<?php

namespace App\Http\Controllers\API;

use App\Queries\NameQueries;
use App\Queries\TaxonQueries;
use App\Queries\ReferenceQueries;
use App\Traits\TaxonExistsTrait;
use App\Transformers\TaxonTransformer;
use App\Transformers\NameTransformer;
use App\Transformers\ReferenceTransformer;
use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;

/**
 * Taxon Controller
 */
class TaxonController extends ApiController
{

    use TaxonExistsTrait;

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}",
     *     tags={"Taxa"},
     *     summary="Gets a **Taxon** resource",
     *     @SWG\Parameter(
     *         in="path",
     *         name="taxon",
     *         required=true,
     *         type="string",
     *         description="UUID of the **Taxon** resource"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         items=@SWG\Items(
     *             type="string",
     *             enum={"creator", "modifiedBy", "hasParentNameUsage",
     *                 "classification", "siblings", "children", "synonyms",
     *                 "treatments", "changes", "bioregionDistribution",
     *                 "stateDistribution"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to include in the result; linked resources within included resources can be appended, separated by a full stop, e.g. 'treatment.as'; multiple resources can be included, separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="exclude",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"hasScientificName", "hasAcceptedNameUsage", "nameAccordingTo"}
     *         ),
     *         collectionFormat="csv",
     *         description="Linked resources to exclude from the result; the enumerated resources are included by default, but can be excluded if they are not wanted in the result."
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
     *         description="The requested resource could not be found"
     *     )
     * )
     *
     * @param Request $request
     * @param  Uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->checkUuid($id);
        $include = 'name.nameType,taxonomicStatus,acceptedNameUsage';
        if ($request->input('include')) {
            $include .= ',' . $request->input('include');
        }
        $taxon = TaxonQueries::getTaxon($id, explode(',', $include));
        $transformer = new TaxonTransformer();
        $resource = new Fractal\Resource\Item($taxon, $transformer, 'taxa');
        $this->fractal->parseIncludes($include);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/name",
     *     tags={"Taxa"},
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
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasScientificName($id)
    {
        $this->checkUuid($id);
        $taxon = $this->checkTaxon($id);
        $name = NameQueries::getName($taxon->scientific_name_id);
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
     *         name="name",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Name"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasNameAccordingTo($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        if ($taxon->name_according_to_id) {
            $nameAccordingTo = ReferenceQueries::getReference($taxon->name_according_to_id);
            $resource = new Fractal\Resource\Item($nameAccordingTo,
                    new ReferenceTransformer, 'references');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/acceptedNameUsage",
     *     tags={"Taxa"},
     *     summary="Gets accepted name usage of a Taxon",
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
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasAcceptedNameUsage($id)
    {
        $this->checkUuid($id);
        $taxon = $this->checkTaxon($id);
        if ($taxon->accepted_name_usage_id) {
            $accepted = TaxonQueries::getTaxon($taxon->accepted_name_usage_id);
            $resource = new Fractal\Resource\Item($accepted, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/parentNameUsage",
     *     tags={"Taxa"},
     *     summary="Gets parent of a Taxon",
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
     *             ref="#/definitions/Taxon"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasParentNameUsage($id)
    {
        $this->checkUuid($id);
        $taxon = $this->checkTaxon($id);
        if ($taxon->parent_name_usage_id) {
            $parent = TaxonQueries::getTaxon($taxon->parent_name_usage_id);
            $resource = new Fractal\Resource\Item($parent, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/classification",
     *     tags={"Taxa"},
     *     summary="Gets classification for a Taxon",
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
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function classification($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        $taxa = TaxonQueries::getHigherClassification($id);
        if ($taxa) {
            $resource = new Fractal\Resource\Collection($taxa, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
        return response()->json($taxa);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/children",
     *     tags={"Taxa"},
     *     summary="Gets children of a Taxon",
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
     *                 ref="#/definitions/Taxon"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function children($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        $children = TaxonQueries::getChildren($id);
        if ($children) {
            $resource = new Fractal\Resource\Collection($children, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
        return response()->json($children);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/siblings",
     *     tags={"Taxa"},
     *     summary="Gets siblings of a Taxon",
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
     *                 ref="#/definitions/Taxon"
     *             )
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
    public function siblings($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        $siblings = TaxonQueries::getSiblings($id);
        if ($siblings) {
            $resource = new Fractal\Resource\Collection($siblings, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
        return response()->json($siblings);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/synonyms",
     *     tags={"Taxa"},
     *     summary="Gets synonyms for a Taxon",
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
     *                 ref="#/definitions/Taxon"
     *             )
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
    public function synonyms($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        $synonyms = TaxonQueries::getSynonyms($id);
        if ($synonyms) {
            $resource = new Fractal\Resource\Collection($synonyms, new TaxonTransformer, 'taxa');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
        return response()->json($synonyms);
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/treatments",
     *     tags={"Taxa"},
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
    public function hasTreatments($id)
    {
        $params = ['filter' => ['taxonID' => $id]];
        $treatmentModel = new \App\Queries\TreatmentQueries();
        $treatments = $treatmentModel->getTreatments($params, false);
        $resource = new Fractal\Resource\Collection($treatments,
                new \App\Transformers\TreatmentTransformer(), 'treatments');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
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
    public function hasCurrentTreatment($id)
    {
        $params = [
            'filter' => [
                'taxonID' => $id,
                'isCurrent' => 'true'
            ]
        ];
        $treatmentModel = new \App\Queries\TreatmentQueries();
        $treatments = $treatmentModel->getTreatments($params, false);
        if (count($treatments) > 0) {
            $resource = new Fractal\Resource\Item($treatments[0],
                    new \App\Transformers\TreatmentTransformer(), 'treatments');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/vernacularNames",
     *     tags={"Taxa"},
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
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function hasVernacularNames($id)
    {
        $model = new \App\Queries\VernacularNameQueries();
        $vernacularNames = $model->getVernacularNames($id);
        $transformer = new \App\Transformers\VernacularNameTransformer();
        $resource = new Fractal\Resource\Collection($vernacularNames,
                $transformer, 'vernacular-names');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * [showCultivars description]
     * @SWG\Get(
     *     path="/taxa/{taxon}/cultivars",
     *     tags={"Cultivars"},
     *     summary="Gets cultivars for a Taxon",
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
     *                 ref="#/definitions/Cultivar"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     * @param  Uuid  $id [description]
     * @return \Illuminate\Http\Response
     */
    public function hasCultivars($id)
    {
        $this->checkUuid($id);
        $this->checkTaxon($id);
        $cultivars = \App\Queries\CultivarQueries::getCultivars($id);
        $transformer = new \App\Transformers\CultivarTransformer();
        $resource = new Fractal\Resource\Collection($cultivars, $transformer, 'cultivars');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * [hasCultivarGroup description]
     * @SWG\Get(
     *     path="/taxa/{taxon}/cultivarGroup",
     *     tags={"Cultivars"},
     *     summary="Gets cultivar group for a Taxon (if the Taxon is a cultivar)",
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
     *             ref="#/definitions/Cultivar"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * @param  Uuid  $id UUID of the Taxon
     * @return \Illuminate\Http\Response
     */
    public function hasCultivarGroup($id)
    {
        $this->checkUuid($id);
        $taxon = $this->checkTaxon($id);
        if ($taxon->cultivar_group_id) {
            $cultivarGroup = \App\Queries\CultivarQueries::getCultivarGroup($taxon->cultivar_group_id);
            $transformer = new \App\Transformers\CultivarTransformer();
            $resource = new Fractal\Resource\Item($cultivars, $transformer, 'cultivars');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/changes",
     *     tags={"Taxa"},
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
     *         response="404",
     *         description="The requested resource could not be found."
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasChanges($id)
    {
        $params = ['filter' => ['fromTaxonID' => $id]];
        $changeModel = new \App\Queries\ChangeQueries();
        $changes = $changeModel->getChanges($params, false);
        $resource = new Fractal\Resource\Collection($changes,
                new \App\Transformers\ChangeTransformer, 'changes');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * [showHeroImage description]
     * @param  Uuid $id UUID for the Taxon
     * @return \Illuminate\Http\Response
     */
    public function hasHeroImage($id)
    {
        $this->checkUuid($id);
        $imageModel = new \App\Queries\ImageQueries();
        $heroImage = $imageModel->getHeroImage($id);
        if ($heroImage) {
            $transformer = new \App\Transformers\ImageTransformer();
            $transformer->setDefaultIncludes(['accessPoints']);
            $resource = new Fractal\Resource\Item($heroImage, $transformer, 'images');
            $data = $this->fractal->createData($resource)->toArray();
            return response()->json($data);
        }
    }

    /**
     * @SWG\Get(
     *     path="/taxa/{taxon}/images",
     *     tags={"Taxa"},
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
     *       name="filter[subtype]",
     *       type="string",
     *       enum={"illustration", "photograph"},
     *       description="Filter by subtype."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[license]",
     *       type="string",
     *       description="Filter by licence; parameter can take a wildcard ('__*__'), for example '__*__' will return all images that have a licence."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[features]",
     *       type="array",
     *       @SWG\Items(
     *         type="string",
     *       ),
     *       collectionFormat="csv",
     *       description="Filter on features in the image; values from the feature vocabulary."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[hero]",
     *       type="string",
     *       enum={"true"},
     *       description="Filter on hero images; only recognised value is ""true""."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[creator]",
     *       type="string",
     *       description="Filter on creator; the wildcard character '__*__' may be used."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="exclude",
     *       type="array",
     *       @SWG\Items(
     *         type="string",
     *         enum={"accessPoints", "occurrence", "features"}
     *       ),
     *       collectionFormat="csv",
     *       description="Linked resources to exclude from the response."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="sort",
     *       type="array",
     *       @SWG\Items(
     *         type="string",
     *         enum={"scientificName", "-scientificName", "subtype", "-subtype", "subjectCategory", "-subjectCategory", "license", "-license", "rating", "-rating", "creator", "-creator", "createDate", "-createDate", "digitizationDate", "-digitizationDate"}
     *       ),
     *       collectionFormat="csv",
     *       description="Terms to sort the results by; you can sort by multiple terms at the same time; prefix a term with a '-' to sort in descending order. **Note that applying sorting appears to break the Swagger UI, but works perfectly well in other clients (there might be an AJAX issue).**"
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="pageSize",
     *       type="integer",
     *       format="int32",
     *       description="The number of results to return."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="page",
     *       type="integer",
     *       format="int32",
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
     *     )
     * )
     *
     * @param Uuid $id
     * @return \Illuminate\Http\Response
     */
    public function hasImages(Request $request, $id) {
        $imageModel = new \App\Queries\ImageQueries();

        $queryParams = array_diff_key($request->all(), array_flip(['page']));
        $params = $queryParams;
        $params['filter']['taxonID'] = $id;
        $pageSize = (isset($queryParams['pageSize']))
                ? $queryParams['pageSize'] : 20;

        $paginator = $imageModel->getImages($params, true, $pageSize);
        $paginator->appends($queryParams);
        $paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
        $images = $paginator->getCollection();
        $resource = new Fractal\Resource\Collection($images,
                new \App\Transformers\ImageTransformer, 'images');
        $resource->setPaginator($paginatorAdapter);
        $data = $this->fractal->createData($resource)->toArray();
        $data['meta']['queryParams'] = $queryParams;
        return response()->json($data);
    }
}
