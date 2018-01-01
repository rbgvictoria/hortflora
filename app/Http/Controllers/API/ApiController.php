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
 *     title="VicFlora API",
 *     description="",
 *     version="1.0.0",
 *     @SWG\Contact(
 *       name="Niels Klazenga, Royal Botanic Gardens Victoria",
 *       email="Niels.Klazenga@rbg.vic.gov.au",
 *       url="https://vicflora.rbg.vic.gov.au"
 *     )
 *   ),
 *   host="vicflora.rbg.vic.gov.au",
 *   basePath="/api",
 *   schemes={"https"},
 *   consumes={"application/json", "multipart/form-data"},
 *   produces={"application/json", "application/vnd.api+json"}
 * )
 */
class ApiController extends Controller
{
    /**
     * @var Fractal\Manager
     */
    protected $fractal;

    /** */
    public function __construct()
    {
        $this->middleware('cors');
        $this->setFractalManager();
    }

    /**
     * Sets the Fractal manager, with the appropriate response type based on the
     * Accept header and parses the requested includes and excludes
     */
    protected function setFractalManager()
    {
        $this->fractal = new Fractal\Manager();
        if (strpos(\request()->header('accept'), 'application/vnd.api+json') !== false) {
            $baseUrl = url('api');
            $this->fractal->setSerializer(new \League\Fractal\Serializer\JsonApiSerializer($baseUrl));
        }
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
     * @return \Illuminate\View\View
     */
    public function apiDocs(Request $request)
    {
        $swagger = \Swagger\scan(app_path());
        if ($request->header('accept') == 'application/json'
                || $request->input('format') === 'json') {
            return response()->json($swagger);
        }
        else {
            return view('api', [
                'swagger' => json_encode($swagger)
            ]);
        }
    }


}
