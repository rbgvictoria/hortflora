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

use App\Models\ReferenceQueries;
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
 *   required={"id", "title"}
 * )
 */
class ReferenceTransformer extends Fractal\TransformerAbstract {

    protected $defaultIncludes = [
        'publishedIn'
    ];

    /**
     * @param object $reference
     * @return array
     *
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="author",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="publicationYear",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="journalOrBook",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="collation",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="series",
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
     *   property="part",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="placeOfPublication",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="subject",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="created",
     *   type="string",
     *   format="dateTime"
     * ),
     * @SWG\Property(
     *   property="modified",
     *   type="string",
     *   format="dateTime"
     * )
     */
    public function transform($reference)
    {
        return [
            'id' => $reference->guid,
            'author' => $reference->author,
            'publicationYear' => $reference->publication_year,
            'title' => $reference->title,
            'journalOrBook' => $reference->journal_or_book,
            'collation' => $reference->collation,
            'series' => $reference->series,
            'edition' => $reference->edition,
            'volume' => $reference->volume,
            'part' => $reference->part,
            'page' => $reference->page,
            'publisher' => $reference->publisher,
            'placeOfPublication' => $reference->place_of_publication,
            'subject' => $reference->subject,
            'created' => $reference->timestamp_created,
            'modified' => $reference->timestamp_modified,
        ];
    }

    /**
     * @SWG\Property(
     *   property="publishedIn",
     *   ref="#/definitions/Reference"
     * )
     *
     * @param object $reference
     * @return \League\Fractal\Resource\Item
     */
    public function includePublishedIn($reference)
    {
        if ($reference->published_in) {
            $publishedIn =  ReferenceQueries::getReference($reference->published_in);
            if ($publishedIn) {
                $transformer = new ReferenceTransformer();
                return new Fractal\Resource\Item($publishedIn, $transformer, 'references');
            }
        }
    }
}
