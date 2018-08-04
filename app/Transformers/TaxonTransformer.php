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

use App\Entities\Taxon;
use League\Fractal;
use Swagger\Annotations as SWG;

/**
 *
 * @author Niels Klazenga
 *
 * @SWG\Definition(
 *   definition="Taxon",
 *   description="Extends TaxonAbstract definition with properties specific to &quot;botanical&quot; taxa",
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/TaxonAbstract")
 *   }
 * )
 */
class TaxonTransformer extends TaxonAbstractTransformer
{
    protected $em;

    public function __construct() {
        $this->em = app('em');

        $this->availableIncludes = array_merge($this->availableIncludes, [
            'parentNameUsage',
            'classification',
            'siblings',
            'children',
            'synonyms',
            'cultivars',
            'horticulturalGroup'
        ]);

        $this->defaultIncludes = array_merge($this->defaultIncludes, [
            'taxonRank',
        ]);
    }

    /**
     *  @SWG\Property(
     *    property="parentNameUsage",
     *    ref="#/definitions/Taxon"
     *  )
     *
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeParentNameUsage(Taxon $taxon)
    {
        $parent = $taxon->getParent();
        if ($parent) {
            return new Fractal\Resource\Item($parent, new TaxonTransformer,
                    'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="classification",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Taxon"
     *   )
     * ),
     *
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClassification(Taxon $taxon)
    {
        $classification = $this->em->getRepository('\App\Entities\Taxon')
                ->getHigherClassification($taxon);
        if ($classification) {
            return new Fractal\Resource\Collection($classification,
                    new TaxonTransformer, 'classification');
        }

    }

    /**
     * @SWG\Property(
     *   property="siblings",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Taxon"
     *   )
     * )
     *
     * @param \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeSiblings(Taxon $taxon)
    {
        $siblings = $this->em->getRepository('\App\Entities\Taxon')
                ->getSiblings($taxon);
        if ($siblings) {
            return new Fractal\Resource\Collection($siblings,
                    new TaxonTransformer, 'siblings');
        }
    }

    /**
     * @SWG\Property(
     *   property="children",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Taxon"
     *   )
     * ),
     *
     * @param \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeChildren(Taxon $taxon)
    {
        $children = $this->em->getRepository('\App\Entities\Taxon')
                ->getChildren($taxon);
        if ($children) {
            return new Fractal\Resource\Collection($children,
                    new TaxonTransformer, 'children');
        }
    }

    /**
     * @SWG\Property(
     *   property="synonyms",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Taxon"
     *   )
     * ),
     *
     * @param \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Collection
     */
    public function includeSynonyms(Taxon $taxon)
    {
        $synonyms = $this->em->getRepository('\App\Entities\Taxon')
                ->getSynonyms($taxon);
        if ($synonyms) {
            $transformer = new TaxonTransformer();
            return new Fractal\Resource\Collection($synonyms, $transformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="taxonRank",
     *   ref="#/definitions/TaxonRank"
     * ),
     *
     * @param  \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeTaxonRank(Taxon$taxon)
    {
        $rank = $taxon->getTaxonRank();
        if ($rank) {
            return new Fractal\Resource\Item($rank, new TaxonRankTransformer, 'taxonRank');
        }
    }

    /**
     * @SWG\Property(
     *   property="hybridParent1",
     *   ref="#/definitions/Taxon"
     * ),
     *
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHybridParent1(Taxon $taxon)
    {
        $hybridParent1 = $hybrid->getHybridParent1();
        if ($hybridParent1) {
            return new Fractal\Resource\Item($hybridParent1,
                    new TaxonTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="hybridParent2",
     *   ref="#/definitions/Taxon"
     * ),
     *
     * @param \App\Entities\Taxon $taxon
     * @return \League\Fractal\Resource\Item
     */
    public function includeHybridParent2(Taxon $taxon)
    {
        $hybridParent2 = $hybrid->getHybridParent2();
        if ($hybridParent2) {
            return new Fractal\Resource\Item($hybridParent2,
                    new TaxonTransformer, 'taxa');
        }
    }

    /**
     * @SWG\Property(
     *   property="cultivars",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/Cultivar"
     *   )
     * ),
     *
     * @param  \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Collection
     */
    protected function includeCultivars(Taxon $taxon)
    {
        $cultivars = $taxon->getCultivars();
        if ($cultivars) {
            return new Fractal\Resource\Collection($cultivars,
                    new CultivarTransformer, 'cultivars');
        }
    }

    /**
     * @SWG\Property(
     *   property="horticulturalGroups",
     *   type="array",
     *   @SWG\Items(
     *     ref="#/definitions/HorticulturalGroup"
     *   )
     * )
     *
     * @param  \App\Entities\Taxon $taxon
     * @return Fractal\Resource\Item
     */
    protected function includeHorticulturalGroups(Taxon $taxon)
    {
        $horticulturalGroups = $taxon->getHorticulturalGroups();
        if ($horticulturalGroups) {
            return new Fractal\Resource\Collection($horticulturalGroups,
                    new HorticulturalGroupTransformer);
        }
    }
}
