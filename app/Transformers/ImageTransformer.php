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

use App\Entities\Image;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Image Transformer
 *
 * @SWG\Definition(
 *   definition="Image",
 *   type="object",
 *   required={"scientificName", "creator", "license", "title", "rights"}
 * )
 */
class ImageTransformer extends OrmTransformer {


    protected $defaultIncludes = [
        'accessPoints',
        'creator',
        'source'
    ];

    protected $availableIncludes = [
        'occurrence',
        'features'
    ];

    /**
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
     *   property="license",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="title",
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
     * @param \App\Entities\Image $image
     * @return array
     */
    public function transform($image)
    {
        return [
            'type' => 'Image',
            'id' => $image->getGuid(),
            'taxonID' => $image->getTaxa()->first()->getGuid(),
            'scientificName' => $image->getScientificName()->getFullName(),
            'verbatimScientificName' => $image->getVerbatimScientificName(),
            'subtype' => $image->getSubtype()->getName(),
            'subjectCategory' => $image->getSubjectCategory()->getName(),
            'license' => $image->getLicense()->getUri(),
            'title' => $image->getTitle(),
            'caption' => $image->getCaption(),
            'subjectPart' => $image->getSubjectPart(),
            'subjectOrientation' => $image->getSubjectOrientation(),
            'createDate' => $image->getCreateDate()->format('Y-m-d'),
            'digitizationDate' => 
                    $image->getDigitizationDate()->format('Y-m-d'),
            'providerLiteral' => $image->getProvider()->getName(),
            'rights' => $image->getRights(),
            'rating' => $image->getRating(),
            'isHeroImage' => (bool) $image->getIsHeroImage(),
        ];
    }

    /**
     * @SWG\Property(
     *   property="creator",
     *   ref="#/definitions/Agent"
     * )
     * 
     * @param \App\Entities\Image $image
     * @return \League\Fractal\Resource\Item
     */
    public function includeCreator(Image $image)
    {
        return new Fractal\Resource\Item($image->getCreator(), 
                new AgentTransformer, 'agents');
    }
    
    /**
     * @SWG\Property(
     *   property="source",
     *   ref="#/definitions/Reference"
     * )
     * 
     * @param \App\Entities\Image $image
     * @return \League\Fractal\Resource\Item
     */
    public function includeSource(Image $image)
    {
        $source = $image->getSource();
        if ($source) {
            return new Fractal\Resource\Item($source, new ReferenceTransformer, 
                    'references');
        }
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
     * @param \App\Entities\Image $image
     * @return Fractal\Resource\Collection
     */
    public function includeAccessPoints(Image $image)
    {
        $accessPoints = $image->getAccessPoints();
        return $this->collection($accessPoints, new ImageAccessPointTransformer,
                'access-points');
    }

    /**
     * @SWG\Property(
     *   property="occurrence",
     *   ref="#/definitions/Occurrence"
     * )
     *
     * @param \App\Entities\Image $image
     * @return Fractal\Resource\Item
     */
    public function includeOccurrence(Image $image)
    {
        $occurrence = $image->getOccurrence();
        if ($occurrence) {
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
