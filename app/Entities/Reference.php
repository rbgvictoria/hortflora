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
 * @ORM\Table()
 */
class Reference extends ClassBase {

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
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
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $journalOrBook;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $collation;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $series;

    /**
      * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $edition;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $volume;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $part;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $page;


    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $publisher;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $placeOfPublication;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $subject;

    /**
     * @var \App\Entities\Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @return string
     */
    public function getReference()
    {
      return $this->reference;
    }

    /**
     * @param string $str
     */
    public function setReference($str)
    {
      $this->reference = $str;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
      return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
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
    public function getJournalOrBook()
    {
      return $this->journalOrBook;
    }

    /**
     * @param string $str
     */
    public function setJournalOrBook($str)
    {
      $this->journalOrBook = $str;
    }

    /**
     * @return string
     */
    public function getCollation()
    {
      return $this->collation;
    }

    /**
     * @param string $collation
     */
    public function setCollation($collation)
    {
      $this->collation = $collation;
    }

    /**
     * @return string
     */
    public function getSeries()
    {
      return $this->series;
    }

     /**
      * @param string $series
      */
    public function setSeries($series)
    {
      $this->series = $series;
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
     * @return string
     */
    public function getPart()
    {
      return $this->part;
    }

    /**
     * @param string $part
     */
    public function setPart($part)
    {
      $this->part = $part;
    }

    /**
     * @return string
     */
    public function getPage()
    {
      return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
      $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPublisher()
    {
      return $this->publisher;
    }

    /**
     * @param string $publisher
     */
    public function setPublisher($publisher)
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
     * @return string
     */
    public function getSubject()
    {
      return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
      $this->subject = $subject;
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
}
