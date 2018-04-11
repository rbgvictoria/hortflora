<?php

/*
 * Copyright 2018 Royal Botanic Gardens Victoria.
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

namespace App\Entities;

use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromParams;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ImageRepository
 *
 * @author nklazenga
 */
class ImageRepository extends EntityRepository {
    
    use PaginatesFromParams;
    
    protected $parameters;
    
    public function search($params, $perPage=20, $page=1) //: LengthAwarePaginator
    {
        $this->parameters = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('i, ap, id, sub, cat, sc, c, lic, t, tr, acc, tt, f')
                ->from('\App\Entities\Image', 'i')
                ->join('i.accessPoints', 'ap')
                ->join('i.identifications', 'id')
                ->leftJoin('i.subtype', 'sub')
                ->leftJoin('i.subjectCategory', 'cat')
                ->join('i.scientificName', 'sc')
                ->join('i.creator', 'c')
                ->leftJoin('i.license', 'lic')
                ->leftJoin('i.features', 'f')
                ->join('i.taxa', 't')
                ->leftJoin('t.taxonRank', 'tr')
                ->join('t.accepted', 'acc')
                ->join('acc.node', 'tt');
        
        if (isset($params['filter'])) {
            $qb = $this->searchCriteria($qb, $params['filter']);
        }
        
        if (isset($params['sort'])) {
            $qb = $this->searchSort($qb, $params['sort']);
        }
        
        $query = $qb->getQuery();
        if ($this->parameters) {
            $query->setParameters($this->parameters);
        }
        $query->setParameters($this->parameters);
        
        return $this->paginate($query, $perPage, $page);
    }
    
    protected function searchCriteria(QueryBuilder $qb, $filters)
    {
        if (isset($filters['taxonName'])) {
            $taxonName = str_replace('*', '%', urldecode($filters['taxonName']));
            $qb->andWhere($qb->expr()->like('sc.fullName', ':taxonName'));
            $this->parameters['taxonName'] = $taxonName;
        }
        if (isset($filters['family'])) {
            $qb = $this->higherTaxonSearch($qb, $filters['family'], 'family');
        }
        if (isset($filters['genus'])) {
            $qb = $this->higherTaxonSearch($qb, $filters['genus'], 'genus');
        }
        if (isset($filters['species'])) {
            $qb = $this->higherTaxonSearch($qb, $filters['species'], 'species');
        }
        if (isset($filters['taxonID'])) {
            $taxon = $this->getEntityManager()
                    ->getRepository('\App\Entities\Taxon')
                    ->findOneBy(['guid' => $filters['taxonID']]);
            $node = $this->getEntityManager()
                    ->getRepository('\App\Entities\TaxonTree')
                    ->findOneBy(['taxon' => $taxon]);
            $qb->andWhere($qb->expr()->andX(
                        $qb->expr()->gte('tt.nodeNumber', ':nodeNumber'),
                        $qb->expr()->lte('tt.nodeNumber', ':highestDescendantNodeNumber')
                    ));
            $this->parameters['nodeNumber'] = $node->getNodeNumber();
            $this->parameters['highestDescendantNodeNumber'] = $node->getHighestDescendantNodeNumber();
        }
        if (isset($filters['license'])) {
            $qb->andWhere('lic.name', ':license');
            $this->parameters['license'] = $filters['license'];
        }
        if (isset($filters['subtype'])) {
            $qb->andWhere('sub.name', ':subtype');
            $this->parameters['subtype'] = $filters['subtype'];
        }
        if (isset($filters['subjectCategory'])) {
            $qb->andWhere('cat.name', ':subjectCategory');
            $this->parameters['subjectCategory'] = $filters['subjectCategory'];
        }
        if (isset($filters['features'])) {
            $features = $filters['features'];
            if (!is_array($features)) {
                $features = explode(',', $features);
            }
            $qb->andWhere('i.features', ':features');
            $this->parameters['features'] = $features;
        }
        if (isset($filters['minRating'])) {
            $qb->andWhere($qb->expr()->gte('i.rating', ':minRating'));
            $this->parameters['minRating'] = $filters['minRating'];
        }
        if (isset($filters['hero'])) {
            $qb->andWhere('i.isHeroImage', true);
        }
        if (isset($filters['creator'])) {
            $creator = str_replace('*', '%', urldecode($filters['creator']));
            $qb->andWhere('c.name', ':creator');
            $this->parameters['creator'] = $creator;
        }
        return $qb;
    }
    
    protected function searchSort(QueryBuilder $qb, $sort)
    {
        $sorts = [
            'scientificName' => 'sc.fullName',
            'subtype' => 'sub.name',
            'rating' => 'i.rating',
            'license' => 'lic.name',
            'subject_category' => 'cat.name',
            'creator' => 'i.creator',
            'createDate' => 'i.createDate',
            'digitizationDate' => 'i.digitizationDate'
        ];
        $params = explode(',', $sort);
        foreach ($params as $param) {
            $dir = 'ASC';
            if (substr($param, 0, 1) == '-') {
                $param = substr($param, 1);
                $dir = 'DESC';
            }
            if (isset($sorts['param'])) {
                $qb->addOrderBy($param, $dir);
            }
        }
        return $qb;
    }
    
    protected function higherTaxonSearch(QueryBuilder $qb, $name, $rank=false)
    {
        $taxon = $this->getEntityManager()->getRepository('\App\Entities\Taxon')
                ->findTaxonByName($name);
        if (!$taxon) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->gte('tt.nodeNumber', ':nodeNumber'),
                    $qb->expr()->lte('tt.nodeNumber', ':highestDescendantNodeNumber')
                ));
        $this->parameters['nodeNumber'] = $taxon->getNode()->getNodeNumber();
        $this->parameters['highestDescendantNodeNumber'] = $taxon->getNode()->getHighestDescendantNodeNumber();
        return $qb;
    }
    
    public function getImagesForTaxon($taxon, $perPage=20, $page=1): LengthAwarePaginator
    {
        $node = $this->getEntityManager()
                ->getRepository('\App\Entities\TaxonTree')
                ->findOneBy(['taxon' => $taxon]);
        $nodeNumber = $node->getNodeNumber();
        $highestDescendantNodeNumber = $node->getHighestDescendantNodeNumber();
        $dql = "SELECT i, ap, sc
            FROM \App\Entities\Image i 
            JOIN i.accessPoints ap 
            JOIN i.identifications id 
            JOIN i.subtype sub
            JOIN i.scientificName sc
            JOIN i.taxa t 
            JOIN t.accepted acc
            JOIN acc.node tt
            WHERE tt.nodeNumber>=:nodeNumber 
              AND tt.nodeNumber<=:highestDescendantNodeNumber
            ORDER BY i.isHeroImage DESC, sub.name DESC, i.rating DESC";
        $query = $this->getEntityManager()->createQuery($dql)
                ->setParameters([
                    ':nodeNumber' => $nodeNumber,
                    ':highestDescendantNodeNumber' => $highestDescendantNodeNumber
                ]);
        return $this->paginate($query, $perPage, $page);
    }
    
    public function getHeroImageForTaxon($taxon)
    {
        $images = $this->getImagesForTaxon($taxon);
        if ($images) {
            return $images[0];
        }
        return false;
    }
}
