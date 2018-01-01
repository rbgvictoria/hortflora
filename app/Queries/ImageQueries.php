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

namespace App\Queries;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Image Queries
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class ImageQueries {

    /**
     * Gets images
     * @param  array  $queryParams [description]
     * @param  boolean $pagination  [description]
     * @param  integer $pageSize    [description]
     * @return \Illuminate\Pagination\Paginator
     */
    public function getImages($queryParams, $pagination=true, $pageSize=20)
    {
        $sort = isset($queryParams['sort']) ? $queryParams['sort'] : false;
        $query = $this->baseQuery();
        if (isset($queryParams['filter'])) {
            $query = $this->filter($query, $queryParams['filter']);
        }
        $query->when($sort, function($query) use ($sort) {
            return $this->sort($query, $sort);
        });
        return $this->pagination($query, $pagination, $pageSize);
    }

    /**
     * Gets the hero image
     * @param  Uuid $taxon
     * @return object
     */
    public function getHeroImage($taxon)
    {
        $nodeNumber = null;
        $highestDescendantNodeNumber = null;
        $node = DB::table('flora.taxa as t')
                ->select('tr.node_number', 'tr.highest_descendant_node_number')
                ->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                ->where('t.guid', '=', $taxon)
                ->first();
        if ($node) {
            $nodeNumber = $node->node_number;
            $highestDescendantNodeNumber = $node->highest_descendant_node_number;
        }
        $query = $this->baseQuery();
        return $query->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                ->where('tr.node_number', '>=', $nodeNumber)
                ->where('tr.highest_descendant_node_number', '<=',
                        $highestDescendantNodeNumber)
                ->join('images.image_access_points as acc', 'i.id', '=', 'acc.image_id')
                ->orderBy('i.is_hero_image', 'desc')
                ->orderBy('v_st.name', 'desc')
                ->orderBy('i.rating', 'desc')
                ->orderBy(DB::raw('random()'))
                ->first();
    }

    /**
     * Gets image access points
     * @param  Uuid $imageId
     * @return Collection
     */
    public function getAccessPoints($imageId)
    {
        return DB::table('images.image_access_points as a')
                ->join('images.images as i', 'a.image_id', '=', 'i.id')
                ->join('vocab.variant_vocab as v', 'a.variant_id', '=', 'v.id')
                ->where('i.guid', $imageId)
                ->select('a.id', 'a.guid as access_point_id', 'v.name as variant',
                        'a.access_uri', 'a.format', 'a.pixel_x_dimension',
                        'a.pixel_y_dimension')
                ->get();
    }

    /**
     * @param  Uuid $imageId
     * @return Collection
     */
    public function getFeatures($imageId)
    {
        return DB::table('images.image_features as f')
                ->join('vocab.feature_vocab as v', 'f.feature_id', '=', 'v.id')
                ->where('f.image_id', $imageId)
                ->select('v.uri', 'v.name', 'v.label')
                ->get();
    }


    /**
     * Base query: other queries can add extra joins and WHERE conditions
     * All the groupBy statements are so that a join to image_access_points
     * can be added.
     * @return \Illuminate\Database\Query\Builder
     */
    protected function baseQuery()
    {
        return DB::table('images.images as i')
                ->select('i.id', 'i.guid as image_id', 'i.title', 'i.source',
                        'i.caption', 'i.subject_part', 'i.subject_orientation',
                        'i.create_date', 'i.digitization_date', 'i.rights',
                        'i.rating', 'i.is_hero_image')
                ->groupBy('i.id')

                // Identification
                ->join('images.identifications as id',
                        'i.id', '=', 'id.image_id')
                ->addSelect('id.verbatim_scientific_name', 'id.match_type',
                        'n.full_name as scientific_name')
                ->groupBy('id.id')

                // Taxon
                ->join('flora.taxa as t', 'id.taxon_id', '=', 't.id')
                ->join('flora.names as n', 't.name_id', '=', 'n.id')
                ->addSelect('t.guid as taxon_id')
                ->groupBy('t.id', 'n.id')

                // Accepted name usage
                ->join('flora.taxa as at',
                        't.accepted_id', '=', 'at.id')
                ->join('flora.names as an',
                        'at.name_id', '=', 'an.id')
                ->addSelect('at.guid as accepted_name_usage_id',
                        'an.full_name as accepted_name_usage')
                ->groupBy('at.id', 'an.id')

                // Subtype
                ->join('vocab.subtype_vocab as v_st',
                        'i.subtype_id', '=', 'v_st.id')
                ->addSelect('v_st.name as subtype')
                ->groupBy('v_st.id')

                // Subject category
                ->leftJoin('vocab.subject_category_vocab as v_sc',
                        'i.subject_category_id', '=', 'v_sc.id')
                ->addSelect('v_sc.name as subject_category')
                ->groupBy('v_sc.id')

                // License
                ->leftJoin('vocab.license_vocab as v_l',
                        'i.license_id', '=', 'v_l.id')
                ->addSelect('v_l.uri as license')
                ->groupBy('v_l.id')

                // Creator
                ->leftJoin('public.agents as a', 'i.creator_id', '=', 'a.id')
                ->addSelect('a.name as creator')
                ->groupBy('a.id')

                // Occurrence
                ->leftJoin('images.occurrences as o', 'i.occurrence_id', '=', 'o.id')
                ->addSelect('o.guid as occurrence_id')
                ->groupBy('o.id');
    }

    /**
     * Filer
     * @param  \Illuminate\Database\Query\Builder $query  [description]
     * @param  array $filter [description]
     * @return \Illuminate\Database\Query\Builder
     */
    protected function filter($query, $filter) {
        $query->when(isset($filter['taxonID']), function($query) use ($filter) {
            $nodeNumber = null;
            $highestDescendantNodeNumber = null;
            $node = DB::table('flora.taxa as t')
                    ->select('tr.node_number', 'tr.highest_descendant_node_number')
                    ->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                    ->where('t.guid', '=', $filter['taxonID'])
                    ->first();
            if ($node) {
                $nodeNumber = $node->node_number;
                $highestDescendantNodeNumber = $node->highest_descendant_node_number;
            }
            return $query->join('flora.taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                    ->where('tr.node_number', '>=', $nodeNumber)
                    ->where('tr.highest_descendant_node_number', '<=',
                            $highestDescendantNodeNumber);
        });

        $query->when(isset($filter['taxonName']),
                function($query) use ($filter) {
            $taxonName = str_replace('*', '%', urldecode($filter['taxonName']));
            return $query->where('an.full_name', 'like', $taxonName);
        });

        $query->when(isset($filter['species']), function($query) use ($filter) {
            $species = urldecode($filter['species']);
            return $this->higherTaxonSearch($query, $species, 'species');
        });

        $query->when(isset($filter['genus']), function($query) use ($filter) {
            return $this->higherTaxonSearch($query, $filter['genus'], 'genus');
        });

        $query->when(isset($filter['family']), function($query) use ($filter) {
            return $this->higherTaxonSearch($query, $filter['family'], 'family');
        });

        $query->when(isset($filter['license']), function($query) use ($filter) {
            $license = str_replace('*', '%', urldecode($filter['license']));
            return $query->where('v_l.name', 'like', $license);
        });

        $query->when(isset($filter['subtype']), function($query) use ($filter) {
            return $query->where('v_st.name', $filter['subtype']);
        });

        $query->when(isset($filter['subjectCategory']),
                function($query) use ($filter) {
            return $query->where('v_sc.name', $filter['subjectCategory']);
        });

        $query->when(isset($filter['features']),
                function($query) use ($filter) {
            return $this->featureSearch($query, $filter['features']);
        });

        $query->when(isset($filter['minRating']), function($query)
                use ($filter) {
            $minRating = (integer) $filter['minRating'];
            return $query->whereRaw('i.rating>=' . $minRating);
        });

        $query->when(isset($filter['hero']), function($query) {
            return $query->where('i.is_hero_image', true);
        });

        $query->when(isset($filter['creator']), function($query) use ($filter) {
            $creator = str_replace('*', '%', urldecode($filter['creator']));
            return $query->where('a.name', 'like', $creator);
        });

        return $query;
    }

    /**
     * Pagination
     * @param  \Illuminate\Database\Query\Builder  $query    [description]
     * @param  bool $paginate [description]
     * @param  inte $pageSize [description]
     * @return Collection
     */
    protected function pagination($query, $paginate=true, $pageSize=20)
    {
        if ($paginate) {
            return $query->paginate($pageSize)
                    ->withPath(env('APP_URL') . '/' . request()->path());
        }
        else {
            return $query->get();
        }
    }

}
