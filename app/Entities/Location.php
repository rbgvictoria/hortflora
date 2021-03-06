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

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\ClassBase;

/**
 * Class Location
 * @author Niels Klazenga
 * @ORM\Entity()
 * @ORM\Table()
 */
class Location extends ClassBase {

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $country;

    /**
     * @var string
     * @ORM\Column(type="string", name="country_code", length=2, nullable=true)
     */
    protected $countryCode;

    /**
     * @var string
     * @ORM\Column(type="string", name="state_province", length=64, nullable=true)
     */
    protected $stateProvince;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $locality;

    /**
     * @var string
     * @ORM\Column(type="decimal", name="decimal_longitude", scale=10,
     *     precision=13, nullable=true)
     */
    protected $decimalLongitude;

    /**
     * @var string
     * @ORM\Column(type="decimal", name="decimal_latitude", scale=10,
     *     precision=13, nullable=true)
     */
    protected $decimalLatitude;
    
    /**
     * 
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * 
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
    /**
     * 
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    
    /**
     * 
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }
    
    /**
     * 
     * @return string
     */
    public function getDecimalLatitude()
    {
        return $this->decimalLatitude;
    }
    
    /**
     * 
     * @param string $latitude
     */
    public function setDecimalLatitude($latitude)
    {
        $this->decimalLatitude = $latitude;
    }
    
    /**
     * 
     * @return string
     */
    public function getDecimalLongitude()
    {
        return $this->decimalLongitude;
    }
    
    /**
     * 
     * @param string $longitude
     */
    public function setDecimalLongitude($longitude)
    {
        $this->decimalLongitude = $longitude;
    }
    
    /**
     * 
     * @return string
     */
    public function getStateProvince()
    {
        return $this->stateProvince;
    }
    
    /**
     * 
     * @param string $stateProvince
     */
    public function setStateProvince($stateProvince)
    {
        $this->stateProvince = $stateProvince;
    }
    
    /**
     * 
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }
    
    /**
     * 
     * @param string $locality
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }
}
