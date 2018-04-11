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

use App\Entities\Taxon;
use Doctrine\ORM\EntityRepository;

/**
 * Description of TaxonRepository
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class TaxonRepository extends EntityRepository {
    
    public function getTaxon($guid)
    {
        $dql = "SELECT t, n, a, an, nat, 
                    partial tr.{id, uri, name, label}, 
                    partial ts.{id, uri, name, label}
                FROM \App\Entities\Taxon t 
                JOIN t.name n 
                LEFT JOIN t.accepted a 
                LEFT JOIN a.name an 
                LEFT JOIN t.nameAccordingTo nat 
                LEFT JOIN t.taxonRank tr 
                LEFT JOIN t.taxonomicStatus ts 
                WHERE t.guid=:guid";
        $query = $this->getEntityManager()->createQuery();
        $result = $query->setDQL($dql)
                ->setParameter('guid', $guid)
                ->getResult();
        if (!$result) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $result[0];
    }
    
    public function findTaxonByName($name)
    {
        $dql = "SELECT t
            FROM \App\Entities\Taxon t
            JOIN t.name n
            JOIN t.taxonomicStatus ts
            WHERE n.fullName=:name AND ts.name='accepted'";
        $query = $this->getEntityManager()->createQuery($dql);
        $result = $query->setParameter('name', $name)
                ->getResult();
        if (!$result) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $result[0];
    }
    
    public function getParentNameUsage($taxon)
    {
        $dql = "SELECT t, n, partial nt.{id, uri, name, label}, 
                partial r.{id, uri, name, label}
            FROM \App\Entities\Taxon t 
            JOIN t.name n
            JOIN n.nameType nt
            JOIN t.taxonRank r
            JOIN t.children c
            WHERE c.id=:id";
        $query = $this->getEntityManager()->createQuery();
        return $query->setDQL($dql)
                ->setParameter(':id', $taxon)
                ->getResult();
    }
    
    public function getChildren($taxon) 
    {
        $dql = "SELECT t, n, 
                partial nt.{id, uri, name, label}, 
                partial r.{id, uri, name, label}
            FROM \App\Entities\Taxon t
            JOIN t.name n
            JOIN n.nameType nt
            JOIN t.taxonRank r
            WHERE t.parent=:id";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->setParameter(':id', $taxon)
                ->getResult();
    }
    
    public function getSiblings($taxon) {
        $parent = $this->getEntityManager()
                ->find('\App\Entities\Taxon', $taxon)
                ->getParent()->getId();
        $dql = "SELECT t, n,
                partial nt.{id, uri, name, label}, 
                partial r.{id, uri, name, label}
            FROM \App\Entities\Taxon t
            JOIN t.name n
            JOIN n.nameType nt
            JOIN t.taxonRank r
            WHERE t.parent=:parent AND t.id!=:id";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->setParameters([
                    ':parent' => $parent,
                    ':id' => $taxon,
                ])->getResult();
    }
    
    public function getHigherClassification($taxon) {
        $classification = [];
        $node = $this->getEntityManager()
                ->getRepository('\App\Entities\TaxonTree')
                ->findOneBy(['taxon' => $taxon])->getNodeNumber();
        $dql = "SELECT tr, t, partial r.{id, uri, name, label}, n, nt
            FROM \App\Entities\TaxonTree tr
            JOIN tr.taxon t
            JOIN t.name n
            JOIN t.taxonRank r
            JOIN n.nameType nt
            WHERE tr.nodeNumber<:nodeNumber AND tr.highestDescendantNodeNumber>=:nodeNumber";
        $query = $this->getEntityManager()->createQuery($dql);
        $result = $query->setParameter(':nodeNumber', $node)->getResult();
        foreach($result as $row) {
            $classification[] = $row->getTaxon();
        }
        return $classification;
    }
    
    public function getSynonyms($taxon)
    {
        $dql = "SELECT t, n,
                partial nt.{id, uri, name, label}, 
                partial r.{id, uri, name, label}
            FROM \App\Entities\Taxon t
            JOIN t.name n
            JOIN n.nameType nt
            JOIN t.taxonRank r
            WHERE t.accepted=:id AND t.id!=:id";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->setParameter(':id', $taxon)->getResult();
    }
    
}
