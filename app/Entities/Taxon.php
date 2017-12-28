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
 * Class Taxon
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table(schema="flora", name="taxa")
 */
class Taxon extends ClassBase {

    /**
     * http://rs.tdwg.org/dwc/terms/scientificNameID
     *
     * An identifier for the nomenclatural details of a scientific name.
     *
     * @var Name
     * @ORM\ManyToOne(targetEntity="Name")
     * @ORM\JoinColumn(name="name_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $name;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonRank
     *
     * The taxonomic rank of the most specific name in the scientificName.
     *
     * @var TaxonTreeDefItem
     * @ORM\ManyToOne(targetEntity="TaxonTreeDefItem")
     * @ORM\JoinColumn(name="taxon_tree_def_item_id", referencedColumnName="id",
     *   nullable=false)
     */
    protected $taxonTreeDefItem;

    /**
     * http://rs.tdwg.org/dwc/terms/acceptedNameUsageID
     *
     * An identifier for the name usage of the direct, most proximate
     * higher-rank parent taxon (in a classification) of the most specific
     * element of the scientificName.
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="accepted_id", referencedColumnName="id")
     */
    protected $accepted;

    /**
     * http://rs.tdwg.org/dwc/terms/parentNameUsageID
     *
     * An identifier for the name usage (documented meaning of the name
     * according to a source) of the direct, most proximate higher-rank parent
     * taxon (in a classification) of the most specific element of the
     * scientificName.
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * Cultivar Group
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="cultivar_group_id", referencedColumnName="id")
     */
    protected $cultivarGroup;

    /**
     * http://rs.tdwg.org/dwc/terms/originalNameUsageID
     *
     * An identifier for the name usage in which the scientificName was
     * originally established under the rules of the associated
     * nomenclaturalCode. (basionym)
     *
     * @var Taxon
     * @ORM\ManyToOne(targetEntity="Taxon")
     * @ORM\JoinColumn(name="basionym_id", referencedColumnName="id")
     */
    protected $basionym;

    /**
     * http://rs.tdwg.org/dwc/terms/nameAccordingToID
     *
     * An identifier for the source in which the specific taxon concept
     * circumscription is defined or implied. See nameAccordingTo:
     * The reference to the source in which the specific taxon concept
     * circumscription is defined or implied - traditionally signified by the
     * Latin "sensu" or "sec."
     *
     * @var Reference
     * @ORM\ManyToOne(targetEntity="Reference")
     * @ORM\JoinColumn(name="name_according_to_id", referencedColumnName="id")
     */
    protected $nameAccordingTo;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonomicStatus
     *
     * The status of the use of the scientificName as a label for a taxon.
     *
     * @var TaxonomicStatus
     * @ORM\ManyToOne(targetEntity="TaxonomicStatus")
     * @ORM\JoinColumn(name="taxonomic_status_id", referencedColumnName="id")
     */
    protected $taxonomicStatus;

    /**
     * http://rs.tdwg.org/dwc/terms/occurrenceStatus
     *
     * A statement about the presence or absence of a Taxon at a Location.
     *
     * @var OccurrenceStatus
     * @ORM\ManyToOne(targetEntity="OccurrenceStatus")
     * @ORM\JoinColumn(name="occurrence_status_id", referencedColumnName="id")
     */
    protected $occurrenceStatus;

    /**
     * http://rs.tdwg.org/dwc/terms/establishmentMeans
     *
     * The process by which the biological individual(s) represented in the
     * Occurrence became established at the location.
     *
     * @var EstablishmentMeans
     * @ORM\ManyToOne(targetEntity="EstablishmentMeans")
     * @ORM\JoinColumn(name="establishment_means_id", referencedColumnName="id")
     */
    protected $establishmentMeans;

    /**
     * http://iucn.org/terms/threatStatus
     *
     * Threat status of a taxon as defined by IUCN:
     * http://www.iucnredlist.org/static/categories_criteria_3_1#categories
     *
     * @var ThreatStatus
     * @ORM\ManyToOne(targetEntity="ThreatStatus")
     * @ORM\JoinColumn(name="threat_status_id", referencedColumnName="id")
     */
    protected $threatStatus;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isEndemic;

    /**
     * http://rs.tdwg.org/dwc/terms/taxonRemarks
     *
     * Comments or notes about the taxon or name.
     *
     * @var string
     * @ORM\Column(type="text", name="taxon_remarks", nullable=true)
     */
    protected $taxonRemarks;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="do_not_index", nullable=true)
     */
    protected $doNotIndex;
}
