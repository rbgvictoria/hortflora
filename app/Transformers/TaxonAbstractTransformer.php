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

use App\Entities\TaxonAbstract;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Taxon Abstract Transformer
 *
 * @author Niels Klazenga 
 * 
 * @SWG\Definition(
 *   definition="TaxonAbstract",
 *   description="Base definition with common properties for taxa, cultivars and horticultural groups",
 *   type="object",
 *   required={"name"}
 * )
 */
class TaxonAbstractTransformer extends Fractal\TransformerAbstract
{

    protected $availableIncludes = [
        'acceptedNameUsage',
        'treatments',
        'currentTreatment',
        'changes',
        'heroImage',
        'vernacularNames',
        'taxonomicStatus',
    ];

    protected $defaultIncludes = [
        'name',
        'nameAccordingTo',
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
     *   property="taxonRemarks",
     *   type="string"
     * )
     * @param \App\Entities\TaxonAbstract $taxon
     * @return array
     */
    public function transform(TaxonAbstract $taxon)
    {
        $class = \get_class($taxon);
        $type = substr($class, strrpos($class, "\\")+1);
        return [
            'type' => $type,
            'id' => $taxon->getGuid(),
            'taxonRemarks' => $taxon->getTaxonRemarks(),
        ];
    }

    /**
     *
     * @SWG\Property(
     *   property="name",
     *   ref="#/definitions/Name"
     * )
     * @param \App\Entities\TaxonAbstract $taxon
     * @return Fractal\Resource\Item
     */
    public function includeName(TaxonAbstract $taxon)
    {
        $name = $taxon->getName();
        return new Fractal\Resource\Item($name, new NameTransformer, 'names');
    }

    /**
     *
     * @SWG\Property(
     *   property="nameAccordingTo",
     *   ref="#/definitions/Reference"
     * )
     * @param \App\Entities\TaxonAbstract $taxon
     * @return Fractal\Resource\Item
     */
    public function includeNameAccordingTo(TaxonAbstract $taxon)
    {
        $nameAccordingTo = $taxon->getNameAccordingTo();
        if ($nameAccordingTo) {
            return new Fractal\Resource\Item($nameAccordingTo, 
                    new ReferenceTransformer, 'references');
        }
    }

    /**
     * @SWG\Property(
     *   property="acceptedNameUsage",
     *   ref="#/definitions/TaxonAbstract"
     * )
     *
     * @param \App\Entities\TaxonAbstract $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeAcceptedNameUsage(TaxonAbstract $taxon)
    {
        $acceptedNameUsage = $taxon->getAcceptedNameUsage();
        if ($acceptedNameUsage) {
            return new Fractal\Resource\Item($acceptedNameUsage, 
                    new TaxonAbstractTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="treatments",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Treatment"
     *   )
     * )
     *
     * @param \App\Entities\TaxonAbstract $taxon
     * @return \League\Fractal\Resource\Collection
     */
    protected function includeTreatments(TaxonAbstract $taxon)
    {
        $treatments = $taxon->getTreatments();
        return new Fractal\Resource\Collection($treatments, 
                new TreatmentTransformer, 'treatments');
    }

    /**
     * 
     * @SWG\Property(
     *     property="currentTreatment",
     *     ref="#/definitions/Treatment"
     * )
     * @param  \App\Entities\TaxonAbstract $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeCurrentTreatment(TaxonAbstract $taxon)
    {
        $treatment = $taxon->getTreatments()->filter(function($treatment) {
            return $treatment->getIsCurrentTreatment();
        })->first();
        if ($treatment) {
            return new Fractal\Resource\Item($treatment, 
                    new TreatmentTransformer, 'treatments');
        }
    }

    /**
     * @SWG\Property(
     *   property="changes",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Change"
     *   )
     * )
     *
     * @param \App\Entities\TaxonAbstract $taxon
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChanges(TaxonAbstract $taxon)
    {
        $changes = $taxon->getChanges();
        if ($changes) {
            return new Fractal\Resource\Collection($changes, 
                    new ChangeTransformer, 'changes');
        }
    }

    /**
     * @SWG\Property(
     *     property="heroImage",
     *     ref="#/definitions/Image"
     * )
     *
     * @param \App\Entities\TaxonAbstract $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHeroImage(TaxonAbstract $taxon)
    {
        $heroImage = app('em')->getRepository('\App\Entities\Image')
                ->getHeroImageForTaxon($taxon);
        if ($heroImage) {
            $transformer = new ImageTransformer();
            $transformer->setDefaultIncludes(['accessPoints']);
            return new Fractal\Resource\Item($heroImage, $transformer, 'images');
        }
    }

    /**
     * @SWG\Property(
     *   property="vernacularNames",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/VernacularName"
     *   )
     * )
     *
     * @param \App\Entities\TaxonAbstract $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeVernacularNames(TaxonAbstract $taxon)
    {
        $names = app('em')->getRepository('\App\Entities\VernacularName')->findBy(['guid' => $taxon->getGuid()]);
        if ($names) {
            return new Fractal\Resource\Collection($names, new VernacularNameTransformer,
                    'vernacular-names');
        }
    }

    /**
     * @SWG\Property(
     *   property="taxonomicStatus",
     *   ref="#/definitions/TaxonomicStatus"
     * )
     *
     * @param  \App\Entities\TaxonAbstract $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeTaxonomicStatus(TaxonAbstract $taxon)
    {
        $status = $taxon->getTaxonomicStatus();
        if ($status) {
            return new Fractal\Resource\Item($status, new TaxonomicStatusTransformer, 'taxonomicStatus');
        }
    }
    
    /**
     * @SWG\Property(
     *   property="images",
     *   type="array",
     *   items=@SWG\Schema(
     *     ref="#/definitions/Image"
     *   )
     * )
     * 
     * @param \App\Entities\TaxonAbstract $taxon
     * @return \League\Fractal\Resource\Collection
     */
    protected function includeImages(TaxonAbstract $taxon)
    {
        $images = $taxon->getImages();
        if ($images) {
            return new Fractal\Resource\Collection($images, new ImageTransformer,
                    'images');
        }
    }

}
