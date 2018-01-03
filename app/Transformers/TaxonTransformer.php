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

use App\Queries\ChangeQueries;
use App\Queries\CultivarQueries;
use App\Queries\ImageQueries;
use App\Queries\NameQueries;
use App\Queries\ReferenceQueries;
use App\Queries\TaxonQueries;
use App\Queries\TreatmentQueries;
use App\Queries\VernacularNameQueries;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 * Description of ReferenceTransformer
 *
 * @author Niels Klazenga
 *
 * @SWG\Definition(
 *   definition="Taxon",
 *   type="object",
 *   required={"id", "taxonRank", "scientificName"}
 * )
 */
class TaxonTransformer extends Fractal\TransformerAbstract
{

    protected $availableIncludes = [
        'acceptedNameUsage',
        'parentNameUsage',
        'classification',
        'siblings',
        'children',
        'synonyms',
        'treatments',
        'currentTreatment',
        'changes',
        'heroImage',
        'vernacularNames',
        'cultivars',
        'key',
        'taxonomicStatus',
        'cultivarGroup'
    ];

    protected $defaultIncludes = [
        'name',
        'nameAccordingTo',
        'taxonRank'
    ];

    /**
     * @param \stdClass $taxon
     * @return array
     *
     * @SWG\Property(
     *   property="id",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="isEndemic",
     *   type="string"
     * ),
     * @SWG\Property(
     *   property="taxonRemarks",
     *   type="string"
     * )
     */
    public function transform($taxon)
    {
        return [
            'id' => $taxon->guid,
            'taxonRemarks' => (isset($taxon->taxon_remarks)) ? $taxon->taxon_remarks : null,
            'isEndemic' => $taxon->is_endemic
        ];
    }

    /**
     * @param object $taxon
     * @return Fractal\Resource\Item
     *
     * @SWG\Property(
     *   property="name",
     *   ref="#/definitions/Name"
     * )
     */
    public function includeName($taxon)
    {
        if (isset($taxon->scientific_name_id)) {
            $name = NameQueries::getName($taxon->scientific_name_id);
            if ($name) {
                $transformer = new NameTransformer();
                return new Fractal\Resource\Item($name, $transformer, 'names');
            }
        }
    }

    /**
     * @param \stdClass $taxon
     * @return Fractal\Resource\Item
     *
     * @SWG\Property(
     *   property="nameAccordingTo",
     *   ref="#/definitions/Reference"
     * )
     *
     */
    public function includeNameAccordingTo($taxon)
    {
        if (isset($taxon->name_according_to_id) && $taxon->name_according_to_id) {
            $nameAccordingTo = ReferenceQueries::getReference($taxon->name_according_to_id);
            if ($nameAccordingTo) {
                $transformer = new ReferenceTransformer();
                return new Fractal\Resource\Item($nameAccordingTo, $transformer, 'references');
            }
        }
    }

    /**
     * @SWG\Property(
     *   property="acceptedNameUsage",
     *   ref="#/definitions/Taxon"
     * )
     *
     * @param object $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeAcceptedNameUsage($taxon)
    {
        if (isset($taxon->accepted_name_usage_id)
                && $taxon->accepted_name_usage_id) {
            $accepted = TaxonQueries::getTaxon($taxon->accepted_name_usage_id);
            if ($accepted) {
                $transformer = new TaxonTransformer();
                return new Fractal\Resource\Item($accepted, $transformer, 'taxa');
            }
        }
    }

    /**
     * @SWG\Property(
     *   property="parentNameUsage",
     *   ref="#/definitions/Taxon"
     * )
      *
      * @param object $taxon
      * @return \League\Fractal\Resource\Item
      */
    public function includeParentNameUsage($taxon)
    {
        $parent = TaxonQueries::getTaxon($taxon->parent_name_usage_id);
        if ($parent) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Item($parent, $transformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="classification",
     *   @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           ref="#/definitions/Taxon"
     *       )
     *  )
     * )
     *
     * @param object $taxon
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClassification($taxon)
    {
        $classification = TaxonQueries::getHigherClassification($taxon->guid);
        if ($classification) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Collection($classification, $transformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="siblings",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Taxon"
     *   )
     * )
     *
     * @param object $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeSiblings($taxon)
    {
        $siblings = TaxonQueries::getSiblings($taxon->guid);
        if ($siblings) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Collection($siblings, $transformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="children",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Taxon"
     *   )
     * )
     *
     * @param object $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeChildren($taxon)
    {
        $children = TaxonQueries::getChildren($taxon->guid);
        if ($children) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Collection($children, $transformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="synonyms",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Taxon"
     *   )
     * )
     *
     * @param object $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeSynonyms($taxon)
    {
        $synonyms = TaxonQueries::getSynonyms($taxon->guid);
        if ($synonyms) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Collection($synonyms, $transformer, 'taxa');
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
     * @param object $taxon
     * @return \League\Fractal\Resource\Collection
     */
    protected function includeTreatments($taxon)
    {
        $params = ['filter' => ['taxonID' => $taxon->guid]];
        $treatmentQueries = new TreatmentQueries();
        $treatments = $treatmentQueries->getTreatments($params, false);
        return new Fractal\Resource\Collection($treatments, new TreatmentTransformer,
                'treatments');
    }

    /**
     * @param  object $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeCurrentTreatment($taxon)
    {
        $params = ['filter' => ['taxonID' => $taxon->guid, 'isCurrent' => true]];
        $treatmentQueries = new TreatmentQueries();
        $treatments = $treatmentQueries->getTreatments($params, false);
        if (count($treatments) > 0) {
            $transformer = new TreatmentTransformer();
            return new Fractal\Resource\Item($treatments[0], $transformer, 'treatments');
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
     * @param object $taxon
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChanges($taxon)
    {
        $changeQueries = new ChangeQueries();
        $params = ['filter' => ['fromTaxonID' => $taxon->guid]];
        $changes = $changeQueries->getChanges($params, false);
        if ($changes) {
            $transformer = new ChangeTransformer();
            return new Fractal\Resource\Collection($changes, $transformer,
                    'changes');
        }
    }

    /**
     * @SWG\Property(
     *     property="heroImage",
     *     ref="#/definitions/Image"
     * )
     *
     * @param object $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHeroImage($taxon)
    {
        if ($taxon->taxonomic_status_name == 'accepted' &&
                in_array($taxon->taxon_rank_name, ['family', 'genus', 'species',
                    'subspecies', 'variety',
                    'subvariety', 'forma', 'subforma', 'nothosubspecies',
                    'nothovariety'])) {
            $imageModel = new ImageQueries();
            $heroImage = $imageModel->getHeroImage($taxon->guid);
            if ($heroImage) {
                $transformer = new ImageTransformer();
                $transformer->setDefaultIncludes(['accessPoints']);
                return new Fractal\Resource\Item($heroImage, $transformer, 'images');
            }
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
     * @param object $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeVernacularNames($taxon)
    {
        $vernacularNameQueries = new VernacularNameQueries();
        $names = $vernacularNameQueries->getVernacularNames($taxon->guid);
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
     * @param  \stdClass $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeTaxonomicStatus($taxon)
    {
        if (isset($taxon->taxonomic_status_uri) && $taxon->taxonomic_status_uri) {
            return new Fractal\Resource\Item((object) [
                'uri' => $taxon->taxonomic_status_uri,
                'name' => $taxon->taxonomic_status_name,
                'label' => $taxon->taxonomic_status_label
            ], new TaxonomicStatusTransformer, 'taxonomicStatus');
        }
    }


    /**
     * @SWG\Property(
     *   property="taxonRank",
     *   ref="#/definitions/TaxonRank"
     * )
     *
     * @param  \stdClass $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeTaxonRank($taxon)
    {
        return new Fractal\Resource\Item((object) [
            'uri' => $taxon->taxon_rank_uri,
            'name' => $taxon->taxon_rank_name,
            'label' => $taxon->taxon_rank_label
        ], new TaxonomicStatusTransformer, 'taxonomicStatus');
    }

    /**
     * @SWG\Property(
     *   property="cultivars",
     *   type="array",
     *   items=@SWG\Schema(
     *       ref="#/definitions/Cultivar"
     *   )
     * )
     * @param  \stdClass $taxon
     * @return Fractal\Resource\Collection
     */
    protected function includeCultivars($taxon)
    {
        $cultivars = CultivarQueries::getCultivars($taxon->guid);
        if ($cultivars) {
            return new Fractal\Resource\Collection($cultivars,
                    new CultivarTransformer, 'cultivars');
        }
    }

    /**
     * @SWG\Property(
     *   property="cultivarGroup",
     *   ref="#/definitions/Cultivar"
     * )
     * @param  \stdClass $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeCultivarGroup($taxon)
    {
        if ($taxon->cultivar_group_id) {
            $cultivarGroup = CultivarQueries::getCultivarGroup($taxon->cultivar_group_id);
            if ($cultivarGroup) {
                return new Fractal\Resource\Item($cultivarGroup, new CultivarTransformer, 'cultivarGroups');
            }
        }
    }
}
