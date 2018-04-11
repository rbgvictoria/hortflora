<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidUuidException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Fractal;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;

/**
 * The ApiController class is the superclass of all API controllers; it contains
 * some methods that are used in all these controllers
 *
 * @SWG\Swagger(
 *   @SWG\Info(
 *     title="HortFlora API",
 *     description="",
 *     version="1.0.0",
 *     @SWG\Contact(
 *       name="Niels Klazenga, Royal Botanic Gardens Victoria",
 *       email="Niels.Klazenga@rbg.vic.gov.au"
 *     )
 *   ),
 *   host="hortflora.homestead",
 *   basePath="/api",
 *   schemes={"http"},
 *   consumes={"application/json", "multipart/form-data"},
 *   produces={"application/json", "application/vnd.api+json"},
 *   @SWG\SecurityScheme(
 *     securityDefinition="hortflora_auth",
 *     type="oauth2",
 *     authorizationUrl="http://hortflora.homestead/oauth/authorize",
 *     tokenUrl="http://hortflora.homestead/oauth/token",
 *     flow="accessCode"
 *   ),
 *   @SWG\Tag(
 *     name="Search"
 *   ),
 *   @SWG\Tag(
 *     name="Taxa"
 *   ),
 *   @SWG\Tag(
 *     name="Cultivars",
 *     description="Cultivars are treated differently from Taxa in HortFlora: Cultivars do not have ranks, or parents, or children, but they do belong to a Taxon."
 *   ),
 *   @SWG\Tag(
 *     name="Horticultural Groups"
 *   ),
 *   @SWG\Tag(
 *     name="Names",
 *     description="Names for all types of ""Taxa"""
 *   ),
 *   @SWG\Tag(
 *     name="Vernacular Names"
 *   ),
 *   @SWG\Tag(
 *     name="Treatments"
 *   ),
 *   @SWG\Tag(
 *     name="Treatment Versions"
 *   ),
 *   @SWG\Tag(
 *     name="Images"
 *   ),
 *   @SWG\Tag(
 *     name="Occurrences"
 *   ),
 *   @SWG\Tag(
 *     name="Events",
 *     description="Events where the images were taken"
 *   ),
 *   @SWG\Tag(
 *     name="Locations",
 *     description="Locations where the images were taken"
 *   ),
 *   @SWG\Tag(
 *     name="Regions"
 *   ),
 *   @SWG\Tag(
 *     name="References"
 *   ),
 *   @SWG\Tag(
 *     name="Agents"
 *   ),
 *   @SWG\Tag(
 *     name="Vocabularies"
 *   ),
 *   @SWG\Tag(
 *     name="Changes"
 *   )
 * )
 */
class ApiController extends Controller
{
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Fractal\Manager
     */
    protected $fractal;
    
    /** */
    public function __construct()
    {
        $this->middleware('cors');
        $this->em = app('em');
        $this->setFractalManager();
    }

    /**
     * Sets the Fractal manager, with the appropriate response type based on the
     * Accept header and parses the requested includes and excludes
     */
    protected function setFractalManager()
    {
        $this->fractal = new Fractal\Manager();
        $this->fractal->setSerializer(new \App\Serializers\DataArraySerializer());
        if (\request()->input('include')) {
            $this->fractal->parseIncludes(\request()->input('include'));
        }
        if (\request()->input('exclude')) {
            $this->fractal->parseExcludes(\request()->input('exclude'));
        }
    }

    /**
     * @param  Uuid $id
     * @return InvalidUuidException
     */
    public function checkUuid($id) {
        if (!Uuid::isValid($id)) {
            throw new InvalidUuidException();
        }
    }

    /**
     * Creates API documentation
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function apiDocs()
    {
        $swagger = \Swagger\scan(app_path());
        return response()->json($swagger);
    }
    
    /**
     * 
     * @param string $input
     * @param  $entity
     * @param type $vocab
     * @return type
     */
    public function getValue($input, $entity=false, $vocab=false)
    {
        if (is_array($input)) {
            if ($vocab) {
                $input = $input['name'];
            }
            else {
                $input = $input->id;
            }
        }
        if ($entity) {
            if ($vocab) {
                return $this->em->getRepository('\\App\\Entities\\' . $entity)
                        ->findOneBy(['name' => $input]);
            }
            else {
                return $this->em->getRepository('\\App\\Entities\\' . $entity)
                        ->findOneBy(['guid' => $input]);
            }
        }
        else {
            return $input;
        }
    }

}
