<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends ApiController
{

    /**
     * @SWG\Get(
     *     path="/images",
     *     tags={"Images"},
     *     summary="List Images",
     *     produces={"application/json", "application/vnd.api+json"},
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[taxonID]",
     *       type="string",
     *       description="Filter by taxon ID; the taxon ID is a UUID; the result will include images for subordinate taxa as well, e.g. 'filter[taxonID]=dfe52d12-cc3f-4620-8a9b-ab8361322615' will include images of all species and infraspecific taxa of *Acacia*."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[taxonName]",
     *       type="string",
     *       description="Filter by taxon name; the wildcard character '__*__' can be used, for example 'Eucalyptus*' will return images for all species and infraspecific taxa of _Eucalyptus_."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[species]",
     *       type="string",
     *       description="Filter by species."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[genus]",
     *       type="string",
     *       description="Filter by genus."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="filter[family]",
     *       type="string",
     *       description="Filter by family."
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
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"occurrence", "features"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to embed in the result; multiple resources can be given separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="exclude",
     *       type="array",
     *       @SWG\Items(
     *         type="string",
     *         enum={"accessPoints"}
     *       ),
     *       collectionFormat="csv",
     *       description="Resources that are embedded by default to exclude from the result."
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
     *       response="200",
     *       @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *           ref="#/definitions/Image"
     *         )
     *       ),
     *       description="Successful response"
     *     ),
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $queryParams = array_diff_key($request->all(), array_flip(['page']));
        $perPage = (isset($queryParams['perPage'])) 
                ? $queryParams['perPage'] : 20;
        $page = $request->input('page') ?: 1;
        $paginator = $this->em->getRepository('\App\Entities\Image')->search($queryParams, $perPage, $page);
        $paginator->appends($queryParams);
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
     *     path="/images/{image}",
     *     tags={"Images"},
     *     summary="Gets an Image** resource",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="array",
     *         @SWG\Items(
     *             type="string",
     *             enum={"occurrence", "features"}
     *         ),
     *         collectionFormat="csv",
     *         description="Extra linked resources to embed in the result; multiple resources can be given separated by a comma."
     *     ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="exclude",
     *       type="array",
     *       @SWG\Items(
     *         type="string",
     *         enum={"accessPoints"}
     *       ),
     *       collectionFormat="csv",
     *       description="Resources that are embedded by default to exclude from the result."
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Image"
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
     */
    public function show($id)
    {
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        $transformer = new \App\Transformers\ImageTransformer();
        $resource = new Fractal\Resource\Item($image, $transformer, 'images');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Get(
     *     path="/images/{image}/occurrence",
     *     tags={"Images"},
     *     summary="Gets Occurrence for an Image",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function showOccurrence($id)
    {
        $data = [];
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        $occurrence = $image->getOccurrence();
        if ($occurrence) {
            $transformer = new \App\Transformers\OccurrenceTransformer();
            $resource = new Fractal\Resource\Item($occurrence, $transformer, 'occurrences');
            $data = $this->fractal->createData($resource)->toArray();
        }
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/images",
     *     tags={"Images"},
     *     summary="Creates a new Image record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Image object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Image"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted Image object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Image"
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
        $image = new \App\Entities\Image();
        $image->setScientificName($request->input('scientificName'));
        $image->setSubtype($this->getValue($request->input('subtype'), 'Subtype',
                true));
        $image->setSubjectCategory($this->getValue($request
                ->input('subjectCategory'), 'SubjectCategory', true));
        $image->setLicense($this->getValue($request->input('license'), 'License', 
                true));
        $image->setTitle($request->input('title'));
        $image->setCaption($request->input('caption'));
        $image->setSubjectPart($request->input('subjectPart'));
        $image->setSubjectOrientation($request->input('subjectOrientation'));
        $image->setCreateDate(new \DateTime($request->input('createDate')));
        $image->setDigitizationDate(new \DateTime($request
                ->input('digitizationDate')));
        $image->setRights($request->input('rights'));
        $image->setRating($request->input('rating'));
        $image->setIsHeroImage($request->input('isHeroImage'));
        $image->setCreator($this->getValue($request->input('creator'), 'Agent'));
        $image->setSource($this->getValue($request->input('source'), 
                'Reference'));
        $this->em->persist($image);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($image, 
                new \App\Transformers\ImageTransformer, 'images');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $image->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/images/{image}",
     *     tags={"Images"},
     *     summary="Updates an existing Image record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="image",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Image object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Image"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Image object",
     *         @SWG\Schema(
     *             ref="#/definitions/Image"
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function update(Request $request, $id)
    {
        $this->checkUuid($id);
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        if ($request->input('scientificName')) {
            $image->setScientificName($request->input('scientificName'));
        }
        if ($request->input('subtype')) {
            $image->setSubtype($this->getValue($request->input('subtype'), 
                    'Subtype', true));
        }
        if ($request->input('subjectCategory')) {
            $image->setSubjectCategory($this->getValue($request
                    ->input('subjectCategory'), 'SubjectCategory', true));
        }
        if ($request->input('license')) {
            $image->setLicense($this->getValue($request->input('license'), 'License', 
                    true));
        }
        if ($request->input('title')) {
            $image->setTitle($request->input('title'));
        }
        if ($request->input('caption')) {
            $image->setCaption($request->input('caption'));
        }
        if ($request->input('subjectPart')) {
            $image->setSubjectPart($request->input('subjectPart'));
        }
        if ($request->input('subjectOrientation')) {
            $image->setSubjectOrientation($request->input('subjectOrientation'));
        }
        if ($request->input('createDate')) {
            $image->setCreateDate(new \DateTime($request->input('createDate')));
        }
        if ($request->input('digitizationDate')) {
            $image->setDigitizationDate(new \DateTime($request
                    ->input('digitizationDate')));
        }
        if ($request->input('rights')) {
            $image->setRights($request->input('rights'));
        }
        if ($request->input('rating')) {
            $image->setRating($request->input('rating'));
        }
        if ($request->input('isHeroImage')) {
            $image->setIsHeroImage($request->input('isHeroImage'));
        }
        if ($request->input('creator')) {
            $image->setCreator($this->getValue($request->input('creator'), 'Agent'));
        }
        if ($request->input('source')) {
            $image->setSource($this->getValue($request->input('source'), 
                    'Reference'));
        }
        $this->em->flush();
        $resource = new Fractal\Resource\Item($image, 
                new \App\Transformers\ImageTransformer, 'images');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $image->getGuid());
        
    }
    
    /**
     * @SWG\Delete(
     *     path="/images/{image}",
     *     tags={"Images"},
     *     summary="Deletes an Image record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="image",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Image"
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
        $this->checkUuid($id);
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        $this->em->remove($image);
        $this->em->flush();
        return response()->json([], 204);
    }
    
    /**
     * @SWG\Get(
     *     path="/images/{image}/access-points",
     *     tags={"Images"},
     *     summary="Gets Access Points for an Image",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/AccessPoint"
     *           )
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
     */
    public function listAccessPoints($id)
    {
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        $transformer = new \App\Transformers\ImageAccessPointTransformer();
        $resource = new Fractal\Resource\Collection($image->getAccessPoints(), $transformer, 'accessPoints');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Get(
     *     path="/images/{image}/access-points/{access_point}",
     *     tags={"Images"},
     *     summary="Gets a particular Access Point for an Image",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *       in="path",
     *       name="access_point",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Access Point"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/AccessPoint"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param type $image
     * @param type $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAccessPoint($image, $id)
    {
        $this->checkUuid($id);
        $this->checkUuid($image);
        $accessPoint = $this->em->getRepository('\App\Entities\AccessPoint')
                ->findOneBy(['guid' => $id]);
        if (!$accessPoint || $accessPoint->getImage()->getGuid() != $id) {
            throw new NotFoundHttpException();
        }
        $resource = new Fractal\Resource\Item($accessPoint, 
                new \App\Transformers\ImageAccessPointTransformer, 
                'access-points');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/images/{image}/access-points",
     *     tags={"Images"},
     *     summary="Creates a new Access Point for an image",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="AccessPoint object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/AccessPoint"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted AccesPoint object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/AccessPoint"
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
     * @param type $image
     * @return type
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function storeAccessPoint(Request $request, $image)
    {
        $img = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $image]);
        if (!$img) {
            throw new NotFoundHttpException();
        }
        $accessPoint = new \App\Entities\ImageAccessPoint();
        $accessPoint->setImage($img);
        $accessPoint->setVariant($this->getValue($request->input('variant'), 
                '\App\Entities\Variant', true));
        $accessPoint->setAccessUri($request->input('accessUri'));
        $accessPoint->setFormat($request->input('format'));
        $accessPoint->setPixelXDimension($request->input('pixelXDimension'));
        $accessPoint->setPixelYDimension($request->input('pixelYDimension'));
        $this->em->persist($accessPoint);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($accessPoint, 
                new \App\Transformers\ImageAccessPointTransformer, 
                'access-points');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') 
                . '/' . $request->path() . '/' . $accessPoint->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/images/{image}/access-points/{access_point}",
     *     tags={"Images"},
     *     summary="Updates an existing Access Point record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="image",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="path",
     *         name="access_point",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Access Point"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="AccessPoint object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/AccessPoint"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated AccessPoint object",
     *         @SWG\Schema(
     *             ref="#/definitions/AccessPoint"
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
     * @param string $image                           UUID of the Image
     * @param string $id                              UUID of the AccessPoint
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAccessPoint(Request $request, $image, $id)
    {
        $this->checkUuid($id);
        $this->checkUuid($image);
        $accessPoint = $this->em->getRepository('\App\Entities\AccessPoint')
                ->findOneBy(['guid' => $id]);
        if (!$accessPoint || $accessPoint->getImage()->getGuid() != $id):
            throw new NotFoundHttpException();
        endif;
        if ($request->input('variant')):
            $accessPoint->setVariant($this->getValue($request->input('variant'), 
                    '\App\Entities\Variant', true));
        endif;
        if ($request->input('accessUri')):
            $accessPoint->setAccessUri($request->input('accessUri'));
        endif;
        if ($request->input('format')):
            $accessPoint->setFormat($request->input('format'));
        endif;
        if ($request->input('pixelXDimension')):
            $accessPoint->setPixelXDimension($request->input('pixelXDimension'));
        endif;
        if ($request->input('pixelYDimension')):
            $accessPoint->setPixelYDimension($request->input('pixelYDimension'));
        endif;
        $this->em->flush();
        $resource = new Fractal\Resource\Item($accessPoint, 
                new \App\Transformers\ImageAccessPointTransformer, 
                'access-points');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/images/{image}/access-points/{access_point}",
     *     tags={"Images"},
     *     summary="Deletes an Access Point of an Image",
     *     @SWG\Parameter(
     *         in="path",
     *         name="image",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="path",
     *         name="access_point",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Access Point"
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
     * @param type $image
     * @param type $id
     * @return type
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroyAccessPoint($image, $id)
    {
        $this->checkUuid($id);
        $this->checkUuid($image);
        $accessPoint = $this->em->getRepository('\App\Entities\AccessPoint')
                ->findOneBy(['guid' => $id]);
        if (!$accessPoint || $accessPoint->getImage()->getGuid() != $id):
            throw new NotFoundHttpException();
        endif;
        $this->em->remove($accessPoint);
        return response()->json([], 204);
    }

    
    /**
     * @SWG\Get(
     *     path="/images/{image}/features",
     *     tags={"Images"},
     *     summary="Gets Features for an Image",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Successful response",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Feature"
     *           )
     *       )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function showFeatures($id)
    {
        $image = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $id]);
        if (!$image) {
            throw new NotFoundHttpException();
        }
        $transformer = new \App\Transformers\ImageFeatureTransformer();
        $resource = new Fractal\Resource\Collection($image->getFeatures(), $transformer, 'features');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/images/{image}/features",
     *     tags={"Images"},
     *     summary="Adds Features to an Image",
     *     description="Route takes an array of features (strings) in the body; a feature won't be added if the image already has it; an error will be thrown if a feature doesn't exist.",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Features to add to the image",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Feature"
     *           )
     *       ),
     *       description="Successful response returns the updated list of Features for the Image"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $image
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addFeatures(Request $request, $image)
    {
        $this->checkUuid($image);
        $img = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $image]);
        if (!$img) {
            throw new NotFoundHttpException();
        }
        $features = $request->input();
        if ($features) {
            foreach ($features as $item) {
                if (!$img->getFeatures()->filter(function($feature) 
                        use ($item) {
                    return $feature->getName() == $item;
                })) {
                    $feature = $this->em->getRepository('\App\Entities\Feature')
                            ->findOneBy(['name' == $item]);
                    if (!$feature) {
                        throw new NotFoundHttpException();
                    }
                    $img->addFeature($feature);
                }
            }
            $this->em->flush();
        }
        $transformer = new \App\Transformers\ImageFeatureTransformer();
        $resource = new Fractal\Resource\Collection($img->getFeatures(), 
                $transformer, 'features');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/images/{image}/features",
     *     tags={"Images"},
     *     summary="Removes Features from an Image",
     *     description="Route takes an array of features (strings) in the body; an error will be thrown if the image doesn't have the feature or if a feature doesn't exist.",
     *     @SWG\Parameter(
     *       in="path",
     *       name="image",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Features to remove from the image",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       description="Successful response ",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/Feature"
     *           )
     *       ),
     *       description="Successful response returns the updated list of Features for the Image"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $image
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeFeatures(Request $request, $image)
    {
        $this->checkUuid($image);
        $img = $this->em->getRepository('\App\Entities\Image')
                ->findOneBy(['guid' => $image]);
        if (!$img) {
            throw new NotFoundHttpException();
        }
        $features = $request->input();
        if ($features) {
            foreach ($features as $item) {
                $feature = $img->getFeatures()->filter(function($feature) 
                        use ($item) {
                    return $feature->getName() == $item;
                });
                if (!$feature) {
                    throw new NotFoundHttpException();
                }
                $this->em->remove($feature);
            }
            $this->em->flush();
        }
        $transformer = new \App\Transformers\ImageFeatureTransformer();
        $resource = new Fractal\Resource\Collection($img->getFeatures(), 
                $transformer, 'features');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
}
