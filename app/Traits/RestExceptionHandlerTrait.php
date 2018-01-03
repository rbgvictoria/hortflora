<?php

/*
 * Copyright 2017 Niels Klazenga, Royal Botanic Gardens Victoria.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\TaxonHasChildrenException;
use App\Exceptions\TaxonHasSynonymsException;
use App\Exceptions\InvalidUuidException;
use App\Exceptions\UrlHasInvalidCharactersException;

/**
 * RestExceptionHandlerTrait
 */
trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @SWG\Definition(
     *   definition="Exception",
     *   type="object",
     *   required={"status", "code"},
     *     @SWG\Property(
     *       property="status",
     *       type="integer",
     *       description="HTTP status"
     *     ),
     *     @SWG\Property(
     *       property="code",
     *       type="string",
     *       description="Application-specific error code"
     *     ),
     *     @SWG\Property(
     *       property="title",
     *       type="string",
     *       description="Title of the error"
     *     ),
     *     @SWG\Property(
     *       property="detail",
     *       type="string",
     *       description="Detail of the error"
     *     )
     * )
     * 
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(true) {
            case $this->isTaxonHasChildrenException($e):
                $retval = $this->taxonHasChildren();
                break;
            case $this->isTaxonHasSynonymsException($e):
                $retval = $this->taxonHasSynonyms();
                break;
            case $this->isNotFoundHttpException($e):
                $retval = $this->notFoundHttpException();
                break;
            case $this->isInvalidUuidException($e):
                $retval = $this->invalidUuidException();
                break;
            case $this->isUrlHasInvalidCharactersException($e):
                $retval = $this->urlHasInvalidCharactersException($e);
                break;
            case $this->isFatalErrorException($e):
                $retval = $this->fatalErrorException($e);
                break;
            default:
                $retval = $this->badRequest($e->getMessage(), 400);
        }

        return $retval;
        //return response()->json(['message' => $e->getMessage(), 'type' => get_class($e)], 422);
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message='Bad request', $statusCode=400)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the exception is a TaxonHasChildrenException
     *
     * @param Exception $e
     * @return bool
     */
    protected function isTaxonHasChildrenException(Exception $e)
    {
        return $e instanceof TaxonHasChildrenException;
    }

    /**
     * Returns JSON response for TaxonHasChildrenException exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function taxonHasChildren()
    {
        $message = [
            'errors' => [[
                'status' => "422",
                'code' => '110002',
                'title' => 'Taxon Has Children Exception',
                'detail' => 'Taxon cannot be deleted, as it has children'
            ]]
        ];
        return $this->jsonResponse($message, 422);
    }

    /**
     * Determines if the exception is a TaxonHasSynonymsException
     *
     * @param Exception $e
     * @return bool
     */
    protected function isTaxonHasSynonymsException(Exception $e)
    {
        return $e instanceof TaxonHasSynonymsException;
    }

    /**
     * Returns JSON response for TaxonHasSynonymsException exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function taxonHasSynonyms()
    {
        $message = [
            'errors' => [[
                'status' => "422",
                'code' => '110003',
                'title' => 'Taxon Has Synonyms Exception',
                'detail' => 'Taxon cannot be deleted, as it has synonyms'
            ]]
        ];
        return $this->jsonResponse($message, 422);
    }
    /**
     * Determines if the exception is a TaxonHasSynonymsException
     *
     * @param Exception $e
     * @return bool
     */
    protected function isNotFoundHttpException(Exception $e)
    {
        return $e instanceof NotFoundHttpException;
    }

    /**
     * Returns JSON response for TaxonHasSynonymsException exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundHttpException()
    {
        $message = [
            'errors' => [[
                'status' => "404",
                'code' => '11001',
                'title' => 'Not Found HTTP Exception',
                'detail' => 'The requested resource could not be found'
            ]]
        ];
        return $this->jsonResponse($message, 404);
    }

    /**
     * Determines if the exception is an InvalidUuidException
     *
     * @param Exception $e
     * @return bool
     */
    protected function isInvalidUuidException(Exception $e)
    {
        return $e instanceof InvalidUuidException;
    }

    /**
     * Returns JSON response for InvalidUuidException exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidUuidException()
    {
        $message = [
            'errors' => [
                [
                    'status' => 400,
                    'code' => '11005',
                    'title' => 'Invalid UUID Exception',
                    'detail' => 'The value provided is not a valid UUID'
                ]
            ]
        ];
        return $this->jsonResponse($message, 400);
    }

    /**
     * Checks if Exception is a FatalErrorException
     * @param  Exception $e
     * @return bool
     */
    protected function isFatalErrorException(Exception $e)
    {
        return $e instanceof FatalErrorException;
    }

    /**
     * Returns JSON response for FatalErrorException exception
     * @param FatalErrorException $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function fatalErrorException(FatalErrorException $e)
    {
        $message = [
            'errors' => [
                [
                    'status' => 500,
                    'code' => '11006',
                    'title' => 'Fatal Error',
                    'detail' => [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTrace(),
                    ]
                ]
            ]
        ];
        return $this->jsonResponse($message, '500');
    }

    /**
     * Checks if Exception is UrlHasInvalidCharactersException
     * @param  Exception $e
     * @return bool
     */
    protected function isUrlHasInvalidCharactersException(Exception $e)
    {
        return $e instanceof UrlHasInvalidCharactersException;
    }

    /**
     * Returns JSON response for UrlHasInvalidCharactersException
     * @param  Exception $e [description]
     * @return [type]       [description]
     */
    protected function urlHasInvalidCharactersException(Exception $e)
    {
        $message = [
            'errors' => [
                [
                    'status' => 400,
                    'code' => '11007',
                    'title' => 'URL Has Invalid Characters',
                    'detail' => 'Request cannot be processed, as there are invalid characters in the URL.'
                ]
            ]
        ];
        return $this->jsonResponse($message, '400');
    }
}
