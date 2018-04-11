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

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Reference
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table("`references`")
 */
class Reference extends ClassBase {
    
    /**
     * @ORM\ManyToOne(targetEntity="\App\Entities\ReferenceType")
     * @var \App\Entities\ReferenceType
     */
    protected $referenceType;

    /**
     * @var \App\Entities\Agent
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     */
    protected $author;

    /**
     * @var string
     * @ORM\Column(type="string", name="publication_year", length=16,
     *     nullable=true)
     */
    protected $publicationYear;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $title;

    /**
     * The name defining a special edition of a document. Normally its a literal 
     * value composed of a version number and words.
     * http://purl.org/ontology/bibo/edition
     * 
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $edition;

    /**
     * A volume number
     * http://purl.org/ontology/bibo/volume
     * 
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $volume;

    /**
     * An issue number
     * http://purl.org/ontology/bibo/issue
     * 
     * @var string
     * @ORM\Column(length=32, nullable=true)
     */
    protected $issue;

    /**
     * Starting page number within a continuous page range.
     * http://purl.org/ontology/bibo/pages
     * 
     * @var string
     * @ORM\Column(length=32, nullable=true)
     */
    protected $pages;
    
    /**
     * Starting page number within a continuous page range.
     * http://purl.org/ontology/bibo/pageStart
     * 
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $pageStart;


    /**
     * Ending page number within a continuous page range.
     * http://purl.org/ontology/bibo/pageEnd
     * 
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $pageEnd;


    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="\App\Entities\Agent")
     */
    protected $publisher;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $placeOfPublication;

    /**
     * Parent publication; can be a Journal, or Book (with Chapters, Multi-Volume Book,
     * or Series
     * 
     * @var \App\Entities\Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     */
    protected $parent;
    
    /**
     * @ORM\Column(length=64, nullable=true)
     * @var string
     */
    protected $isbn;
    
    /**
     * @ORM\Column(length=64, nullable=true)
     * @var string
     */
    protected $issn;
    
    /**
     * @ORM\Column(length=64, nullable=true)
     * @var string
     */
    protected $doi;
    
    /**
     * @ORM\Column(length=128, nullable=true)
     * @var string
     */
    protected $url;
    
    /**
     * 
     * @return \App\Entities\ReferenceType
     */
    public function getReferenceType()
    {
        return $this->referenceType;
    }
    
    /**
     * 
     * @param \App\Entities\ReferenceType $referenceType
     */
    public function setReferenceType(ReferenceType $referenceType)
    {
        $this->referenceType = $referenceType;
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getAuthor()
    {
      return $this->author;
    }

    /**
     * @param \App\Entities\Agent $author
     */
    public function setAuthor(Agent $author)
    {
      $this->author = $author;
    }

    /**
     * @return string
     */
    public function getPublicationYear()
    {
      return $this->publicationYear;
    }

    /**
     * @param string $year
     */
    public function setPublicationYear($year)
    {
      $this->publicationYear = $year;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
      return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
      $this->title = $title;
    }

    /**
     * @return string
     */
    public function getEdition()
    {
      return $this->edition;
    }

    /**
     * @param string $edition
     */
    public function setEdition($edition)
    {
      $this->edition = $edition;
    }

    /**
     * @return string
     */
    public function getVolume()
    {
      return $this->volume;
    }

    /**
     * @param string $volume
     */
    public function setVolume($volume)
    {
      $this->volume = $volume;
    }
    
    /**
     * 
     * @return string
     */
    public function getIssue()
    {
        return $this->issue;
    }
    
    /**
     * 
     * @param string $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }

    /**
     * 
     * @return int
     */
    public function getPageStart()
    {
        return $this->pageStart;
    }
    
    /**
     * 
     * @param int $pageStart
     */
    public function setPageStart($pageStart)
    {
        $this->pageStart = $pageStart;
    }

    /**
     * 
     * @return int
     */
    public function getPageEnd()
    {
        return $this->pageEnd;
    }
    
    /**
     * 
     * @param int $pageEnd
     */
    public function setPageEnd($pageEnd)
    {
        $this->pageEnd = $pageEnd;
    }

    /**
     * @return string
     */
    public function getPages()
    {
      return $this->pages;
    }

    /**
     * @param string $pages
     */
    public function setPages($pages)
    {
      $this->page = $pages;
    }

    /**
     * @return \App\Entities\Agent
     */
    public function getPublisher()
    {
      return $this->publisher;
    }

    /**
     * @param \App\Entities\Agent $publisher
     */
    public function setPublisher(Agent $publisher)
    {
      $this->publisher = $publisher;
    }

    /**
     * @return string
     */
    public function getPlaceOfPublication()
    {
      return $this->placeOfPublication;
    }

    /**
     * @param string $place
     */
    public function setPlaceOfPublication($place)
    {
      $this->placeOfPublication = $place;
    }

    /**
     * @return Reference
     */
    public function getParent()
    {
      return $this->parent;
    }

    /**
     * @param Reference $parent
     */
    public function setParent($parent)
    {
      $this->parent = $parent;
    }
    
    /**
     * 
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }
    
    /**
     * 
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }
    
    /**
     * 
     * @return string
     */
    public function getIssn()
    {
        return $this->issn;
    }
    
    /**
     * 
     * @param string $issn
     */
    public function setIssn($issn)
    {
        $this->issn = $issn;
    }
    
    /**
     * 
     * @return string
     */
    public function getDoi()
    {
        return $this->doi;
    }
    
    /**
     * 
     * @param string $isbn
     */
    public function setDoi($doi)
    {
        $this->doi = $doi;
    }
    
    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
