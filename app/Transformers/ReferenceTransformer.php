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

namespace App\Transformers;

use App\Entities\Reference;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of ReferenceTransformer
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 *
 * @SWG\Definition(
 *   definition="Reference",
 *   type="object",
 *   required={"referenceType", "author", "title", "publicationYear"}
 * )
 */
class ReferenceTransformer extends OrmTransformer {

    protected $defaultIncludes = [
        'author',
        'publishedIn',
        'publisher'
    ];

    /**
     *
     * @SWG\Property(
     *   property="type",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="id",
     *   type="string",
     *   format="uuid"
     * ),
     * @SWG\Property(
     *   property="referenceType",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="publicationYear",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="title",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="edition",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="volume",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="issue",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="pageStart",
     *   type="integer",
     *   format="int32"
     * ),
     * @SWG\Property(
     *   property="pageEnd",
     *   type="integer",
     *   format="int32"
     * ),
     * @SWG\Property(
     *   property="pages",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="placeOfPublication",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="url",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="isbn",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="issn",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="doi",
     *   type="string"
     * )
     * 
     * @param \App\Entities\Reference $reference
     * @return array
     */
    public function transform(Reference $reference)
    {
        return [
            'type' => 'Reference',
            'id' => $reference->getGuid(),
            'referenceType' => $reference->getReferenceType()->getName(),
            'publicationYear' => $reference->getPublicationYear(),
            'title' => $reference->getTitle(),
            'edition' => $reference->getEdition(),
            'volume' => $reference->getVolume(),
            'issue' => $reference->getIssue(),
            'pageStart' => $reference->getPageStart(),
            'pageEnd' => $reference->getPageEnd(),
            'pages' => $reference->getPages(),
            'placeOfPublication' => $reference->getPlaceOfPublication(),
            'url' => $reference->getUrl(),
            'isbn' => $reference->getIsbn(),
            'issn' => $reference->getIssn(),
            'doi' => $reference->getDoi()
        ];
    }
    
    /**
     * @SWG\Property(
     *   property="author",
     *   ref="#/definitions/Agent"
     * )
     * 
     * @param \App\Entities\Reference $reference
     * @return \League\Fractal\Resource\Item
     */
    public function includeAuthor(Reference $reference)
    {
        $author = $reference->getAuthor();
        return new Fractal\Resource\Item($author, new AgentTranformer, 'agents');
    }

    /**
     * @SWG\Property(
     *   property="publisher",
     *   ref="#/definitions/Agent"
     * )
     * 
     * @param \App\Entities\Reference $reference
     * @return \League\Fractal\Resource\Item
     */
    public function includePublisher(Reference $reference)
    {
        $publisher = $reference->getPublisher();
        return new Fractal\Resource\Item($publisher, new AgentTranformer, 
                'agents');
    }

    /**
     * @SWG\Property(
     *   property="publishedIn",
     *   ref="#/definitions/Reference"
     * )
     *
     * @param \App\Entities\Reference $reference
     * @return \League\Fractal\Resource\Item
     */
    public function includePublishedIn(Reference $reference)
    {
        $publishedIn = $reference->getParent();
        if ($publishedIn) {
            return new Fractal\Resource\Item($publishedIn, 
                    new ReferenceTransformer, 'references');
        }
    }
}
