<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;

class ReferenceController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/references/{reference}",
     *     tags={"References"},
     *     summary="Gets a Reference record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="reference",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Reference"
     *     ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="include",
     *         type="string",
     *         required=false,
     *         description="Nested resources to include in result; only option for Reference is `publishedIn`."
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
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(Request $request, $id) 
    {
        $this->checkUuid($id);
        $reference = $this->em->getRepository('\App\Entities\Reference')
                ->findOneBy(['guid' => $id]);
        if (!$reference) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $transformer = new \App\Transformers\ReferenceTransformer();
        $resource = new Fractal\Resource\Item($reference, $transformer, 'references');
        $this->fractal->parseIncludes($request->input('include') ?: []);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/references",
     *     tags={"References"},
     *     summary="Creates a new Reference record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Reference object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted Reference object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
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
        $reference = new \App\Entities\Reference();
        $refType = $request->input('referenceTye');
        if (is_array($refType)) {
            $refType = $refType['name'];
        }
        $reference->setReferenceType($this->em
                ->getRepository('\App\Entities\ReferenceType')
                ->findOneBy(['name' => $refType]));
        $author = $request->input('author');
        if (is_array($author)) {
            $author = $author['id'];
        }
        $reference->setAuthor($this->em
                ->getRepository('\App\Entities\Agent')
                ->findOneBy(['guid' => $author]));
        $reference->setPublicationYear($request->input('publicationYear'));
        $reference->setTitle($request->input('title'));
        $reference->setEdition($request->input('edition'));
        $reference->setVolume($request->input('volume'));
        $reference->setIssue($request->input('issue'));
        $reference->setPageStart($request->input('pageStart'));
        $reference->setPageEnd($request->input('pageEnd'));
        $reference->setPages($request->input('pages'));
        $reference->setPlaceOfPublication($request->input('placeOfPublication'));
        $reference->setUrl($request->input('url'));
        $reference->setIsbn($request->input('isbn'));
        $reference->setIssn($request->input('issn'));
        $reference->setDoi($request->input('doi'));
        if ($request->input('publisher')) {
            $publisher = $request->input('publisher');
            if (is_array($publisher)) {
                $publisher = $publisher['id'];
            }
            $reference->setPublisher($this->em->getRepository('\App\Entities\Agent')
                    ->findOneBy(['guid' => $publisher]));
        }
        if ($request->input('publishedIn')) {
            $publishedIn = $request->input('publishedIn');
            if (is_array($publishedIn)) {
                $publishedIn = $publishedIn['id'];
            }
            $reference->setParent($this->em->getRepository('\App\Entities\Reference')
                    ->findOneBy(['guid' => $publishedIn]));
        }
        $this->em->persist($reference);
        $this->em->flush();
        $transformer = new \App\Transformers\ReferenceTransformer();
        $resource = new Fractal\Resource\Item($reference, $transformer, 
                'references');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)
                ->header('Location', env('APP_URL') . '/' . $request->path() 
                        . '/' . $reference->getGuid());
    }
    
    /**
     * @SWG\Put(
     *     path="/references/{reference}",
     *     tags={"References"},
     *     summary="Updates an existing Reference record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="reference",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Reference"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Reference object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Reference object",
     *         @SWG\Schema(
     *             ref="#/definitions/Reference"
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
        $reference = $this->em->getRepository('\App\Entities\Reference')
                ->findOneBy(['guid' => $id]);
        if (!$reference) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        if ($request->input('referenceType')) {
            $refType = $request->input('referenceTye');
            if (is_array($refType)) {
                $refType = $refType['name'];
            }
            $reference->setReferenceType($this->em
                    ->getRepository('\App\Entities\ReferenceType')
                    ->findOneBy(['name' => $refType]));
        }
        if ($request->input('author')) {
            $author = $request->input('author');
            if (is_array($author)) {
                $author = $author['id'];
            }
            $reference->setAuthor($this->em
                    ->getRepository('\App\Entities\Agent')
                    ->findOneBy(['guid' => $author]));
        }
        if ($request->input('publicationYear')) {
            $reference->setPublicationYear($request->input('publicationYear'));
        }
        if ($request->input('title')) {
            $reference->setTitle($request->input('title'));
        }
        if ($request->input('edition')) {
            $reference->setEdition($request->input('edition'));
        }
        if ($request->input('volume')) {
            $reference->setVolume($request->input('volume'));
        }
        if ($request->input('issue')) {
            $reference->setIssue($request->input('issue'));
        }
        if ($request->input('pageStart')) {
            $reference->setPageStart($request->input('pageStart'));
        }
        if ($request->input('pageEnd')) {
            $reference->setPageEnd($request->input('pageEnd'));
        }
        if ($request->input('pages')) {
            $reference->setPages($request->input('pages'));
        }
        if ($request->input('placeOfPublication')) {
            $reference->setPlaceOfPublication($request->input('placeOfPublication'));
        }
        if ($request->input('url')) {
            $reference->setUrl($request->input('url'));
        }
        if ($request->input('isbn')) {
            $reference->setIsbn($request->input('isbn'));
        }
        if ($request->input('issn')) {
            $reference->setIssn($request->input('issn'));
        }
        if ($request->input('doi')) {
            $reference->setDoi($request->input('doi'));
        }
        if ($request->input('publisher')) {
            $publisher = $request->input('publisher');
            if (is_array($publisher)) {
                $publisher = $publisher['id'];
            }
            $reference->setPublisher($this->em->getRepository('\App\Entities\Agent')
                    ->findOneBy(['guid' => $publisher]));
        }
        if ($request->input('publishedIn')) {
            $publishedIn = $request->input('publishedIn');
            if (is_array($publishedIn)) {
                $publishedIn = $publishedIn['id'];
            }
            $reference->setParent($this->em->getRepository('\App\Entities\Reference')
                    ->findOneBy(['guid' => $publishedIn]));
        }
        $this->em->flush();
        $transformer = new \App\Transformers\ReferenceTransformer();
        $resource = new Fractal\Resource\Item($reference, $transformer, 
                'references');
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 200);
    }
    
    /**
     * @SWG\Delete(
     *     path="/references/{reference}",
     *     tags={"References"},
     *     summary="Deletes a Reference record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="reference",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Reference"
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
        $reference = $this->em->getRepository('\App\Entities\Reference')
                ->findOneBy(['guid' => $id]);
        if (!$reference) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $this->em->remove($reference);
        return response()->json([], 204);
    }
}
