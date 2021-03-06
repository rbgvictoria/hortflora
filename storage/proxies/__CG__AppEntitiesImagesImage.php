<?php

namespace DoctrineProxies\__CG__\App\Entities\Images;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Image extends \App\Entities\Images\Image implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'occurrence', 'title', 'source', 'type', 'subtype', 'caption', 'subjectCategory', 'subjectPart', 'subjectOrientation', 'createDate', 'digitizationDate', 'creator', 'rightsHolder', 'license', 'rights', 'isHeroImage', 'rating', 'identifications', 'accessPoints', 'id', 'guid', 'version', 'timestampCreated', 'timestampModified', 'createdBy', 'modifiedBy'];
        }

        return ['__isInitialized__', 'occurrence', 'title', 'source', 'type', 'subtype', 'caption', 'subjectCategory', 'subjectPart', 'subjectOrientation', 'createDate', 'digitizationDate', 'creator', 'rightsHolder', 'license', 'rights', 'isHeroImage', 'rating', 'identifications', 'accessPoints', 'id', 'guid', 'version', 'timestampCreated', 'timestampModified', 'createdBy', 'modifiedBy'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Image $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getOccurrence()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOccurrence', []);

        return parent::getOccurrence();
    }

    /**
     * {@inheritDoc}
     */
    public function setOccurrence($occurrence)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOccurrence', [$occurrence]);

        return parent::setOccurrence($occurrence);
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', []);

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTitle', [$title]);

        return parent::setTitle($title);
    }

    /**
     * {@inheritDoc}
     */
    public function getSource()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSource', []);

        return parent::getSource();
    }

    /**
     * {@inheritDoc}
     */
    public function setSource($source)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSource', [$source]);

        return parent::setSource($source);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', []);

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setType', [$type]);

        return parent::setType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubtype()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubtype', []);

        return parent::getSubtype();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubtype($subtype)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubtype', [$subtype]);

        return parent::setSubtype($subtype);
    }

    /**
     * {@inheritDoc}
     */
    public function getCaption()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCaption', []);

        return parent::getCaption();
    }

    /**
     * {@inheritDoc}
     */
    public function setCaption($caption)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCaption', [$caption]);

        return parent::setCaption($caption);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubjectCategory()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubjectCategory', []);

        return parent::getSubjectCategory();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubjectCategory($subjectCategory)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubjectCategory', [$subjectCategory]);

        return parent::setSubjectCategory($subjectCategory);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubjectPart()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubjectPart', []);

        return parent::getSubjectPart();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubjectPart($subjectPart)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubjectPart', [$subjectPart]);

        return parent::setSubjectPart($subjectPart);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubjectOrientation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubjectOrientation', []);

        return parent::getSubjectOrientation();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubjectOrientation($subjectOrientation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubjectOrientation', [$subjectOrientation]);

        return parent::setSubjectOrientation($subjectOrientation);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreateDate', []);

        return parent::getCreateDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreateDate($createDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreateDate', [$createDate]);

        return parent::setCreateDate($createDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getDigitizationDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDigitizationDate', []);

        return parent::getDigitizationDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setDigitizationDate($digitizationDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDigitizationDate', [$digitizationDate]);

        return parent::setDigitizationDate($digitizationDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreator()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreator', []);

        return parent::getCreator();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreator($creator)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreator', [$creator]);

        return parent::setCreator($creator);
    }

    /**
     * {@inheritDoc}
     */
    public function getRightsHolder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRightsHolder', []);

        return parent::getRightsHolder();
    }

    /**
     * {@inheritDoc}
     */
    public function setRightsHolder($rightsHolder)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRightsHolder', [$rightsHolder]);

        return parent::setRightsHolder($rightsHolder);
    }

    /**
     * {@inheritDoc}
     */
    public function getLicense()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLicense', []);

        return parent::getLicense();
    }

    /**
     * {@inheritDoc}
     */
    public function setLicense($license)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLicense', [$license]);

        return parent::setLicense($license);
    }

    /**
     * {@inheritDoc}
     */
    public function getRights()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRights', []);

        return parent::getRights();
    }

    /**
     * {@inheritDoc}
     */
    public function setRights($rights)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRights', [$rights]);

        return parent::setRights($rights);
    }

    /**
     * {@inheritDoc}
     */
    public function getIsHeroImage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIsHeroImage', []);

        return parent::getIsHeroImage();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsHeroImage($isHeroImage)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsHeroImage', [$isHeroImage]);

        return parent::setIsHeroImage($isHeroImage);
    }

    /**
     * {@inheritDoc}
     */
    public function getRating()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRating', []);

        return parent::getRating();
    }

    /**
     * {@inheritDoc}
     */
    public function setRating($rating)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRating', [$rating]);

        return parent::setRating($rating);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifications()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdentifications', []);

        return parent::getIdentifications();
    }

    /**
     * {@inheritDoc}
     */
    public function addIdentification(\App\Entities\Images\Identification $identification)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addIdentification', [$identification]);

        return parent::addIdentification($identification);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessPoints()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAccessPoints', []);

        return parent::getAccessPoints();
    }

    /**
     * {@inheritDoc}
     */
    public function addAccessPoint(\App\Entities\Images\ImageAccessPoint $accessPoint)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addAccessPoint', [$accessPoint]);

        return parent::addAccessPoint($accessPoint);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVersion', []);

        return parent::getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function setVersion($version)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVersion', [$version]);

        return parent::setVersion($version);
    }

    /**
     * {@inheritDoc}
     */
    public function incrementVersion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'incrementVersion', []);

        return parent::incrementVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function getGuid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGuid', []);

        return parent::getGuid();
    }

    /**
     * {@inheritDoc}
     */
    public function setGuid($guid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGuid', [$guid]);

        return parent::setGuid($guid);
    }

    /**
     * {@inheritDoc}
     */
    public function getTimestampCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTimestampCreated', []);

        return parent::getTimestampCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setTimestampCreated(\DateTime $timestampCreated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTimestampCreated', [$timestampCreated]);

        return parent::setTimestampCreated($timestampCreated);
    }

    /**
     * {@inheritDoc}
     */
    public function getTimestampModified()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTimestampModified', []);

        return parent::getTimestampModified();
    }

    /**
     * {@inheritDoc}
     */
    public function setTimestampModified(\DateTime $timestampModified)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTimestampModified', [$timestampModified]);

        return parent::setTimestampModified($timestampModified);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedBy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedBy', []);

        return parent::getCreatedBy();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedBy(\App\Entities\User $createdBy)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedBy', [$createdBy]);

        return parent::setCreatedBy($createdBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getModifiedBy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getModifiedBy', []);

        return parent::getModifiedBy();
    }

    /**
     * {@inheritDoc}
     */
    public function setModifiedBy(\App\Entities\User $modifiedBy)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setModifiedBy', [$modifiedBy]);

        return parent::setModifiedBy($modifiedBy);
    }

}
