<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;

class VocabularyController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/vocabularies/{vocabulary}/terms",
     *     tags={"Vocabularies"},
     *     summary="Lists Terms in a Vocabulary",
     *     @SWG\Parameter(
     *       in="path",
     *       name="vocabulary",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           type="array",
     *           @SWG\Items(
     *               ref="#/definitions/VocabularyTerm"
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
     * @param string $vocabulary
     * @return \Illuminate\Http\Response
     */
    public function listTerms($vocabulary)
    {
        $entity = '\\App\\Entities\\' . ucfirst($vocabulary);
        $terms = $this->em->getRepository($entity)->findAll();
        $transformer = '\\App\\Transformers\\' . ucfirst($vocabulary) . 'Transformer';
        $resource = new Fractal\Resource\Collection($terms, new $transformer, $vocabulary);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Get(
     *     path="/vocabularies/{vocabulary}/terms/{term}",
     *     tags={"Vocabularies"},
     *     summary="Gets Vocabulary Term",
     *     @SWG\Parameter(
     *       in="path",
     *       name="vocabulary",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary"
     *     ),
     *     @SWG\Parameter(
     *       in="path",
     *       name="term",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary Term"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/VocabularyTerm"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     * 
     * @param string $vocab
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    function showTerm($vocab, $name)
    {
        $entity = '\\App\\Entities\\' . ucfirst($vocab);
        $term = $this->em->getRepository($entity)->findOneBy(['name' => $name]);
        
        $transformer = '\\App\\Transformers\\' . ucfirst($vocab) . 'Transformer';
        $resource = new Fractal\Resource\Item($term, new $transformer, $vocab);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Post(
     *     path="/vocabularies/{vocabulary}/terms",
     *     tags={"Vocabularies"},
     *     summary="Creates new Vocabulary Term",
     *     @SWG\Parameter(
     *       in="path",
     *       name="vocabulary",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary"
     *     ),
     *     @SWG\Parameter(
     *       in="body",
     *       name="body",
     *       required=true,
     *       description="VocabularyTerm object to be inserted",
     *       @SWG\Schema(
     *           ref="#/definitions/VocabularyTerm"
     *       )
     *     ),
     *     @SWG\Response(
     *       response="201",
     *       @SWG\Schema(
     *           ref="#/definitions/VocabularyTerm"
     *       ),
     *       description="Successful response returns the new TaxonomicStatus object in the response body and its URL in the Location header"
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
     * @param Request $request
     * @param type $vocab
     * @return type
     */
    public function storeTerm(Request $request, $vocab)
    {
        $entity = '\\App\\Entities\\' . ucfirst($vocab);
        $term = new $entity();
        $term->setName($request->input('name'));
        $term->setLabel($request->input('label'));
        $term->setUri($request->input('uri') ?: $request->input('name'));
        $this->em->persist($term);
        $this->em->flush();
        $transformer = '\\App\\Transformers\\' . ucfirst($vocab) . 'Transformer';
        $resource = new Fractal\Resource\Item($term, new $transformer, $vocab);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', env('APP_URL') . '/' . $request->path() . '/' . $term->getName());
    }
    
    /**
     * @SWG\Put(
     *     path="/vocabularies/{vocabulary}/terms/{term}",
     *     tags={"Vocabularies"},
     *     summary="Updates Vocabulary Term",
     *     @SWG\Parameter(
     *       in="path",
     *       name="vocabulary",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary"
     *     ),
     *     @SWG\Parameter(
     *       in="path",
     *       name="term",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary Term"
     *     ),
     *     @SWG\Parameter(
     *       in="body",
     *       name="body",
     *       required=true,
     *       description="VocabularyTerm object to be inserted",
     *       @SWG\Schema(
     *           ref="#/definitions/VocabularyTerm"
     *       )
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/VocabularyTerm"
     *       ),
     *       description="Successful response returns the updated VocabularyTerm object"
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
     * @param string $vocab
     * @param string $termName
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateTerm(Request $request, $vocab, $termName)
    {
        $entity = '\\App\\Entities\\' . ucfirst($vocab);
        $term = $this->em->getRepository($entity)->findOneBy(['name' => $termName]);
        if (!$term) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        if ($request->input('label')) {
            $term->setLabel($request->input('label'));
        }
        if ($request->input('uri')) {
            $term->setUri($request->input('uri'));
        }
        $this->em->flush();
        $transformer = '\\App\\Transformers\\' . ucfirst($vocab) . 'Transformer';
        $resource = new Fractal\Resource\Item($term, new $transformer, $vocab);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }
    
    /**
     * @SWG\Delete(
     *     path="/vocabularies/{vocabulary}/terms/{term}",
     *     tags={"Vocabularies"},
     *     summary="Removes Term from Vocabulary",
     *     @SWG\Parameter(
     *       in="path",
     *       name="vocabulary",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary"
     *     ),
     *     @SWG\Parameter(
     *       in="path",
     *       name="term",
     *       type="string",
     *       required=true,
     *       description="Name of the Vocabulary Term"
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
     * @param string $vocab
     * @param string $termName
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroyTerm($vocab, $termName)
    {
        $entity = '\\App\\Entities\\' . ucfirst($vocab);
        $term = $this->em->getRepository($entity)->findOneBy(['name' => $termName]);
        if (!$term) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $this->em->remove($term);
        return response()->json([], 204);
    }
}
