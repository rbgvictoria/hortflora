<?php

/*
 * Copyright 2017 Royal Botanic Gardens Victoria.
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

use League\Fractal;
use App\Queries\ImageQueries;
use Swagger\Annotations as SWG;

/**
 * Description of ImageTransformer
 *
 * @SWG\Definition(
 *   definition="Image",
 *   type="object",
 *   required={"id", "taxonID", "scientificName", "subtype", "subjectCategory",
 *       "creator", "license", "title", "source", "caption", "subjectPart",
 *       "subjectOrientation", "createDate", "digitizationDate", "rights",
 *        "rating"}
 * )
 */
class ImageTransformer extends Fractal\TransformerAbstract {


    /**
     * @var ImageQueries
     */
    protected $imageModel;

    protected $defaultIncludes = [
        'accessPoints',
    ];

    protected $availableIncludes = [
        'occurrence',
        'features'
    ];

    /**
     * Constructor
     */
    public function __construct() {
        $this->imageModel = new ImageQueries();
    }

    /**
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="taxonID",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="scientificName",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="subtype",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="subjectCategory",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="creator",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="license",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="title",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="source",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="caption",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="subjectPart",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="subjectOrientation",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="createDate",
     *   type="string",
     *   format="date-time"
     * ),
     * @SWG\Property(
     *   property="digitizationDate",
     *   type="string",
     *   format="date-time"
     * ),
     * @SWG\Property(
     *   property="rights",
     *   type="string",
     * ),
     * @SWG\Property(
     *   property="rating",
     *   type="integer",
     *   format="int32"
     * ),
     * @SWG\Property(
     *   property="isHeroImage",
     *   type="boolean"
     * )
     *
     * @param object $image
     * @return array
     */
    public function transform($image)
    {
        return [
            'id' => $image->image_id,
            'taxonID' => $image->accepted_name_usage_id,
            'scientificName' => $image->accepted_name_usage,
            'verbatimScientificName' => $image->verbatim_scientific_name,
            'matchType' => $image->match_type,
            'subtype' => $image->subtype,
            'subjectCategory' => $image->subject_category,
            'creator' => $image->creator,
            'license' => $image->license,
            'title' => $image->title,
            'source' => $image->source,
            'caption' => $image->caption,
            'subjectPart' => $image->subject_part,
            'subjectOrientation' => $image->subject_orientation,
            'createDate' => $image->create_date,
            'digitizationDate' => $image->digitization_date,
            'rights' => $image->rights,
            'rating' => $image->rating ? (int) $image->rating : null,
            'isHeroImage' => (bool) $image->is_hero_image
        ];
    }

    /**
     * @SWG\Property(
     *   property="accessPoints",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/AccessPoint"
     *   )
     * )
     *
     * @param object $image
     * @return Fractal\Resource\Collection
     */
    public function includeAccessPoints($image)
    {
        $collection = $this->imageModel->getAccessPoints($image->image_id);
        return $this->collection($collection, new ImageAccessPointTransformer,
                'access-points');
    }

    /**
     * @SWG\Property(
     *   property="occurrence",
     *   ref="#/definitions/Occurrence"
     * )
     *
     * @param object $image
     * @return Fractal\Resource\Item
     */
    public function includeOccurrence($image)
    {
        if ($image->occurrence_id) {
            $occurrenceModel = new \App\Queries\OccurrenceQueries();
            $occurrence = $occurrenceModel->getOccurrence($image->occurrence_id);
            return $this->item($occurrence, new OccurrenceTransformer,
                        'occurrences');
        }
    }

    /**
     * @SWG\Property(
     *   property="features",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Feature"
     *   )
     * )
     *
     * @param object $image
     * @return Fractal\Resource\Collection
     */
    public function includeFeatures($image)
    {
        $collection = $this->imageModel->getFeatures($image->id);
        if ($collection) {
            return $this->collection($collection, new ImageFeatureTransformer,
                    'features');
        }
    }
}
