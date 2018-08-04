<?php

/*
 * Copyright 2018 nklazenga.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Services;

use App\Services\Encoding;
use App\Services\UUID;
use Illuminate\Support\Facades\DB;

/**
 * Description of ImageLoaderService
 *
 * @author nklazenga
 */
class ImageLoaderService {

  protected $catalogue;
  protected $handle;
  protected $fields;
  public $items;

  protected $licenseVocab;
  protected $subtypeVocab;
  protected $subjectCategoryVocab;
  protected $variants;
  protected $features;

  public function __construct($file, $catalogue)
  {
    $this->handle = fopen($file, 'r');
    $this->catalogue = $catalogue;
    $this->setFields();
    $this->setVocabs();
  }

  public function setFields()
  {
    $this->fields = fgetcsv($this->handle);
  }

  public function getFields()
  {
    return $this->header;
  }

  public function parseFile() {
    while (!feof($this->handle)) {
      $row = fgetcsv($this->handle);
      $item = $this->parseRow($row);
      $item->ImageID = null;
      $this->items[] = $item;
    }
    fclose($this->handle);
  }

  public function processItem($item)
  {
    $cumulusImg = $this->findCumulusImage($item->CumulusCatalogue, $item->CumulusRecordID);
    if (!$cumulusImg || !$cumulusImg->imageId) {
      if ($item->VerbatimScientificName && $item->PixelXDimension > 862
          && $item->PixelYDimension > 862 && $item->Creator) {
        $taxon = $this->matchTaxon($item->VerbatimScientificName);
        if ($taxon) {
          $item = (object) array_merge((array) $item, (array) $taxon);
          $item->GUID = UUID::v4();
          $item->ImageID = $this->insertImage($item);
        }
      }
      if (!$cumulusImg) {
        $this->insertCumulusImage($item);
      }
      else {
        $this->updateCumulusImage($cumulusImg->id, $item);
      }
    }
    else {
      $identification = $this->getIdentification($cumulusImg->imageId);
      if ($item->VerbatimScientificName && $identification
          && $item->VerbatimScientificName != $identification->verbatimScientificName
          && $item->PixelXDimension > 862
          && $item->PixelYDimension > 862 && $item->Creator) {
        $taxon = $this->matchTaxon($item->VerbatimScientificName);
        if ($taxon) {
          $item->ImageID = $cumulusImg->imageId;
          $item->currentIdentificationId = $identification->id;
          $item->occurrenceId = $identification->occurrence_id;
          $item->taxonId = $taxon->TaxonID;
          $this->addIdentification($item);
        }
      }
      $item->ImageID = $cumulusImg->imageId;
      $this->updateCumulusImage($cumulusImg->id, $item);
    }
    return false;
  }

  protected function parseRow($row)
  {
    $item = [];
    foreach ($this->fields as $index => $field) {
      $item[$field] = isset($row[$index]) ? Encoding::toUTF8($row[$index])
          : null;
    }
    return $this->convert($item);
  }

  protected function convert($item) {
    $converted = new \stdClass();
    $convert = [
      'CumulusRecordID' => 'CumulusRecordID',
      'CumulusRecordName' => 'CumulusRecordName',
      'CumulusCatalog' => 'CumulusCatalogue',
      'dcterms:title' => 'Title',
      'dc:source' => 'Source',
      'dcterms:modified' => 'Modified',
      'dcterms:type' => 'DCType',
      'ac:subtype' => 'Subtype',
      'ac:caption' => 'Caption',
      'iptc:CVTerm' => 'SubjectCategory',
      'ac:subjectPart' => 'SubjectPart',
      'ac:subjectOrientation' => 'SubjectOrientation',
      'xmp:CreateDate' => 'CreateDate',
      'ac:digitizationDate' => 'DigitizationDate',
      'dcterms:creator' => 'Creator',
      'dcterms:rightsHolder' => 'RightsHolder',
      'dcterms:license' => 'License',
      'dc:rights' => 'rights',
      'dwc:scientificName' => 'VerbatimScientificName',
      'dwc:catalogNumber' => 'CatalogNumber',
      'dwc:recordedBy' => 'RecordedBy',
      'dwc:recordNumber' => 'RecordNumber',
      'dwc:country' => 'Country',
      'dwc:countryCode' => 'CountryCode',
      'dwc:stateProvince' => 'StateProvince',
      'dwc:locality' => 'Locality',
      'dwc:decimalLatitude' => 'Latitude',
      'dwc:decimalLongitude' => 'Longitude',
      'exif:PixelXDimension' => 'PixelXDimension',
      'exif:PixelYDimension' => 'PixelYDimension',
      'HeroImage' => 'HeroImage',
      'xmp:Rating' => 'Rating',
      'ThumbnailUrlEnabled' => 'ThumbnailUrlEnabled',
      'PreviewUrlEnabled' => 'PreviewUrlEnabled',
      'FileFormat' => 'FileFormat'
    ];
    foreach ($convert as $key => $value) {
      $converted->$value = $item[$key] ?: null;
    }
    return $converted;
  }

  public function matchTaxon($verbatimScientificName)
  {
    $select = "SELECT t.id, t.guid, n.full_name, t.name_id
      FROM taxa t
      JOIN names n ON t.name_id=n.id
      WHERE n.full_name=?";
    $taxon = new \stdClass();
    $result = DB::select($select, [$verbatimScientificName]);
    if ($result) {
      $row = $result[0];
      $taxon->TaxonID = $row->id;
      $taxon->ScientificName = $row->full_name;
      $taxon->ScientificNameID = $row->name_id;
      $taxon->MatchType = 'exactMatch';
      return $taxon;
    }
    return false;
  }

  protected function findCumulusImage($cumulusCatalogue, $cumulusRecordId)
  {
    $select = "SELECT id, image_id
        FROM cumulus_images
        WHERE cumulus_catalogue=? AND cumulus_record_id=?";
    $images = DB::select($select, [$cumulusCatalogue, $cumulusRecordId]);
    if ($images) {
      $img = $images[0];
      return (object) [
        'id' => $img->id,
        'imageId' => $img->image_id,
      ];
    }
    return false;
  }

  protected function getIdentification($imageId)
  {
    $select = "SELECT id.id, id.occurrence_id,
          id.verbatim_scientific_name as \"verbatimScientificName\"
        FROM images img
        JOIN identifications id ON img.id=id.image_id
        WHERE img.id=? AND is_current=true";
    $result = DB::select($select, [$imageId]);
    if ($result) {
      return $result[0];
    }
    return false;
  }

  protected function insertCumulusImage($data)
  {
    $insert = "INSERT INTO cumulus_images (id, image_id,
        created_by_id, cumulus_record_id, cumulus_catalogue,
        cumulus_record_name, cumulus_modified, pixel_x_dimension, pixel_y_dimension,
        timestamp_created, version)
      VALUES (nextval('cumulus_images_id_seq'), ?, 1, ?, ?, ?, ?,
        ?, ?, now(), 1)";

    $insertData = [
      $data->ImageID ?: null,
      $data->CumulusRecordID,
      $data->CumulusCatalogue,
      strlen($data->CumulusRecordName) <= 64 ? $data->CumulusRecordName : substr($data->CumulusRecordName, 0, 64),
      $data->Modified,
      $data->PixelXDimension,
      $data->PixelYDimension,
    ];

    DB::insert($insert, $insertData);
  }

  protected function updateCumulusImage($cumulusRecordId, $data)
  {
    $update = "UPDATE cumulus_images
        SET image_id=?, cumulus_record_name=?, cumulus_modified=?,
          pixel_x_dimension=?, pixel_y_dimension=?, timestamp_modified=now(),
          modified_by_id=1, version=version+1
        WHERE id=?";
    $updateData = [
      $data->ImageID ?: null,
      strlen($data->CumulusRecordName) <= 64 ? $data->CumulusRecordName : substr($data->CumulusRecordName, 0, 64),
      $data->Modified,
      $data->PixelXDimension,
      $data->PixelYDimension,
      $cumulusRecordId
    ];
    DB::update($update, $updateData);
  }

  protected function addIdentification($data)
  {
    $update = "UPDATE identifications
      SET is_current=false, version=version+1, timestamp_modified=now(),
        modified_by_id=1
      WHERE id=?";
    DB::update($update, [$data->currentIdentificationId]);
    $insert = "INSERT INTO identifications (occurrence_id, image_id,
        taxon_id, created_by_id, is_current, timestamp_created, guid,
        version, verbatim_scientific_name, match_type)
      VALUES (?, ?, ?, 1, true, now(), ?, 1, ?, 'exactMatch')";
    $insertData = [
      $data->occurrenceId,
      $data->ImageID,
      $data->taxonId,
      UUID::v4(),
      $data->VerbatimScientificName
    ];
    DB::insert($insert, $insertData);
  }

  protected function insertImage($data)
  {
    $insert = "INSERT INTO images (id, occurrence_id,
        creator_id, license_id, created_by_id,
        modified_by_id, title, dc_source, dc_type, subtype_id, caption,
        subject_category_id, subject_part, subject_orientation,
        create_date, digitization_date, rights, is_hero_image, rating,
        verbatim_scientific_name, scientific_name_id,
        timestamp_created, timestamp_modified, guid, version)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        now(), now(), ?, 1)";

    $creator = null;
    if ($data->Creator) {
      if (in_array($data->Creator, ['unknown'])) {
        $data->Creator = 'Royal Botanic Gardens Victoria';
      }
      $creator = $this->insertCreator($data->Creator);
    }

    $license = null;
    if ($data->License != 'All rights reserved') {
      $license = array_search('CC BY-NC-SA 4.0', $this->licenseVocab);
    }

    $subtype = array_search('photograph', $this->subtypeVocab);
    if ($data->Subtype) {
      $subtype = array_search(strtolower($data->Subtype), $this->subtypeVocab);
    }

    $subjectCategory = array_search('occurrenceImage',
        $this->subjectCategoryVocab);
    if ($data->SubjectCategory == 'glossary') {
      $subjectCategory = array_search('glossaryTermIllustration',
          $this->subjectCategoryVocab);
    }
    elseif ($data->SubjectCategory == 'Botanical art, home page') {
      $subjectCategory = array_search('botanicalArt',
          $this->subjectCategoryVocab);
    }
    elseif (in_array($data->SubjectCategory, ['Flora of Victoria Figure',
      'Flora of the Otway Plain and Ranges plate'])) {
      $subjectCategory = array_search('floraIllustration',
          $this->subjectCategoryVocab);
    }
    elseif ($data->SubjectCategory == 'GPI image') {
      $subjectCategory = array_search('specimenImage',
          $this->subjectCategoryVocab);
    }

    $occurrence = null;
    if ($subjectCategory == array_search('occurrenceImage',
        $this->subjectCategoryVocab)) {
      $occurrence = $this->insertOccurrence($data);
    }

    if (!$data->ImageID) {
      $data->ImageID = $this->nextVal('images_id_seq');;
    }

    $insertData = [
      $data->ImageID,
      $occurrence ?: null,
      $creator ?: null,
      $license ?: null,
      1,
      null,
      $data->Title ?: null,
      $data->Source ?: null,
      $data->DCType ?: null,
      $subtype ?: null,
      $data->Caption ?: null,
      $subjectCategory ?: null,
      null,
      $data->SubjectOrientation ?: null,
      $data->CreateDate ?: null,
      $data->DigitizationDate ?: null,
      $data->rights ?: null,
      $this->convertStringToBoolean($data->HeroImage),
      $data->Rating ?: null,
      $data->VerbatimScientificName,
      $data->ScientificNameID,
      $data->GUID,
    ];

    DB::insert($insert, $insertData);

    $this->insertIdentification($data->ImageID, $occurrence, $data->TaxonID,
        $data->VerbatimScientificName, $data->MatchType);
    $this->insertTaxonImage($data->TaxonID, $data->ImageID);

    $this->accessPoints($data);

    if ($data->SubjectPart) {
      $this->insertFeatures($data->ImageID, $data->SubjectPart);
    }

    return $data->ImageID;
  }

  protected function accessPoints($data)
  {
    $insert = "INSERT INTO image_access_points (id, image_id,
        variant_id, created_by_id, access_uri, format,
        pixel_x_dimension, pixel_y_dimension, timestamp_created, guid,
        version)
      VALUES (nextval('image_access_points_id_seq'), ?, ?, 1, ?,
        'image/jpeg', ?, ?, now(), ?, 1)";

    //
    //thumbnail
    //
    $variant = array_search('thumbnail', $this->variants);
    $accessUri = 'https://data.rbg.vic.gov.au/cip/preview/thumbnail/'
        . $this->catalogue . '/' . $data->CumulusRecordID;

    $thumbnailSize = 256;

    if ($data->PixelXDimension >= $data->PixelYDimension) {
      $width = $thumbnailSize;
      $height = round(($data->PixelYDimension /
          $data->PixelXDimension) * $thumbnailSize);
    }
    else {
      $height = $thumbnailSize;
      $width = round(($data->PixelXDimension /
          $data->PixelYDimension) * $thumbnailSize);
    }
    DB::insert($insert, [
      $data->ImageID,
      $variant ?: null,
      $accessUri,
      $width,
      $height,
      UUID::v4(),
    ]);

    //
    //preview
    //

    $previewSize = 1024;
    $variant = array_search('preview', $this->variants);
    $accessUri = 'https://data.rbg.vic.gov.au/cip/preview/image/'
        . $this->catalogue . '/' . $data->CumulusRecordID
        . '?maxsize=' . $previewSize;

    $width = $data->PixelXDimension;
    $height = $data->PixelYDimension;

    if (strtolower($data->Subtype) == 'illustration') {
      $width = $width / 2;
      $height = $height / 2;
    }

    if ($width >= $height) {
      if ($width > $previewSize) {
        $previewWidth = $previewSize;
        $previewHeight = $height * ($previewSize / $width);
      }
      else {
        $previewWidth = $width;
        $previewHeight = $height;
      }
    }
    else {
      if ($height > $previewSize) {
        $previewHeight = $previewSize;
        $previewWidth = $width * ($previewSize / $height);
      }
      else {
        $previewWidth = $width;
        $previewHeight = $height;
      }
    }

    DB::insert($insert, [
      $data->ImageID,
      $variant ?: null,
      $accessUri,
      round($previewWidth),
      round($previewHeight),
      UUID::v4(),
    ]);
  }

  protected function insertCreator($creator)
  {
    $insert = "INSERT INTO agents (id, name, timestamp_created, guid, version)
        VALUES (?, ?, now(), ?, 1)";

    $select = "SELECT id FROM agents WHERE name=?";
    $result = DB::select($select, [$creator]);
    if ($result) {
      $row = $result[0];
      return $row->id;
    }
    else {
      $next = $this->nextVal('agents_id_seq');
      DB::insert($insert, [
        $next,
        $creator,
        UUID::v4()
      ]);
      return $next;
    }
  }

  protected function insertOccurrence($data)
  {
    $insert = "INSERT INTO occurrences (id, event_id, recorded_by_id,
        created_by_id, catalog_number, record_number, timestamp_created,
        guid, version)
      VALUES (?, ?, ?, 1, ?, ?, now(), ?, 1)";

    $next = $this->nextVal('occurrences_id_seq');

    $recordedBy = null;
    if ($data->RecordedBy) {
      $recordedBy = $this->insertCreator($data->RecordedBy);
    }
    elseif ($data->Creator) {
      $recordedBy = $this->insertCreator($data->Creator);
    }

    $event = null;
    if ($data->Country || $data->CountryCode || $data->StateProvince ||
        $data->Locality || ($data->Latitude && $data->Longitude)) {
      $event = $this->insertEvent($data);
    }

    DB::insert($insert, [
      $next,
      $event,
      $recordedBy,
      $data->CatalogNumber,
      $data->RecordNumber,
      UUID::v4()
    ]);
    return $next;
  }

  protected function insertEvent($data)
  {
    $insert = "INSERT INTO events (id, location_id, created_by_id,
        timestamp_created, guid, version)
      VALUES (?, ?, 1, now(), ?, 1)";
    $next = $this->nextVal('events_id_seq');
    $location = $this->insertLocation($data);
    DB::insert($insert, [
      $next,
      $location,
      UUID::v4()
    ]);
    return $next;
  }

  protected function insertLocation($data)
  {
    $insert = "INSERT INTO locations (id, created_by_id, country,
        country_code, state_province, locality, decimal_longitude,
        decimal_latitude, timestamp_created, guid, version)
      VALUES (?, 1, ?, ?, ?, ?, ?, ?, now(), ?, 1)";
    $next = $this->nextVal('locations_id_seq');
    DB::insert($insert, [
      $next,
      $data->Country,
      $data->CountryCode,
      $data->StateProvince,
      $data->Locality,
      $data->Longitude,
      $data->Latitude,
      UUID::v4()
    ]);
    return $next;
  }

  protected function insertIdentification($imageId, $occurrenceId, $taxonId,
      $verbatimScientificName=null, $matchType=null)
  {
    $insert = "INSERT INTO identifications (id, occurrence_id,
        image_id, taxon_id, created_by_id, is_current,
        timestamp_created, guid, version, verbatim_scientific_name, match_type)
      VALUES (nextval('identifications_id_seq'), ?, ?, ?, 1, true,
        now(), ?, 1, ?, ?)";
    DB::insert($insert, [
      $occurrenceId,
      $imageId,
      $taxonId,
      UUID::v4(),
      $verbatimScientificName,
      $matchType
    ]);
  }

  protected function insertTaxonImage($taxonId, $imageId)
  {
    $insert = "INSERT INTO taxon_image (taxon_id, image_id)
        VALUES (?, ?)";
    DB::insert($insert, [$taxonId, $imageId]);
  }

  protected function nextVal($sequence)
  {
    $result = DB::select("SELECT nextval('$sequence') as \"nextVal\"");
    return $result[0]->nextVal;
  }

  public function setVocabs()
  {
    $this->setLicenseVocab();
    $this->setSubtypeVocab();
    $this->setSubjectCategoryVocab();
    $this->setVariants();
    $this->setFeatures();
  }

  protected function setLicenseVocab()
  {
    $this->licenseVocab = [];
    $result = DB::select("SELECT id, name FROM license_vocab");
    foreach ($result as $row) {
      $this->licenseVocab[$row->id] = $row->name;
    }
  }

  protected function setSubtypeVocab()
  {
    $this->subtypeVocab = [];
    $result = DB::select("SELECT id, name FROM subtype_vocab");
    foreach ($result as $row) {
      $this->subtypeVocab[$row->id] = $row->name;
    }
  }

  protected function setSubjectCategoryVocab()
  {
    $this->subjectCategoryVocab = [];
    $result = DB::select("SELECT id, name FROM subject_category_vocab");
    foreach ($result as $row) {
      $this->subjectCategoryVocab[$row->id] = $row->name;
    }
  }

  protected function setVariants()
  {
    $result = DB::select("SELECT id, name FROM variant_vocab");
    $this->variants = [];
    foreach ($result as $row) {
      $this->variants[$row->id] = $row->name;
    }
  }

  protected function setFeatures()
  {
    $result = DB::select("SELECT id, label FROM features");
    $this->features = [];
    foreach ($result as $row) {
      $this->features[$row->id] = $row->label;
    }
  }

  protected function convertStringToBoolean($str)
  {
    switch ($str) {
      case 'true':
        return true;
      case 'false':
        return null;
      default:
        return null;
    }
  }
}
