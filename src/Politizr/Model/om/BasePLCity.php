<?php

namespace Politizr\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityPeer;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PTScopePLC;
use Politizr\Model\PTScopePLCQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePLCity extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PLCityPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PLCityPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the p_l_department_id field.
     * @var        int
     */
    protected $p_l_department_id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the name_simple field.
     * @var        string
     */
    protected $name_simple;

    /**
     * The value for the name_real field.
     * @var        string
     */
    protected $name_real;

    /**
     * The value for the name_soundex field.
     * @var        string
     */
    protected $name_soundex;

    /**
     * The value for the name_metaphone field.
     * @var        string
     */
    protected $name_metaphone;

    /**
     * The value for the zipcode field.
     * @var        string
     */
    protected $zipcode;

    /**
     * The value for the municipality field.
     * @var        string
     */
    protected $municipality;

    /**
     * The value for the municipality_code field.
     * @var        string
     */
    protected $municipality_code;

    /**
     * The value for the district field.
     * @var        int
     */
    protected $district;

    /**
     * The value for the canton field.
     * @var        string
     */
    protected $canton;

    /**
     * The value for the amdi field.
     * @var        int
     */
    protected $amdi;

    /**
     * The value for the nb_people_2010 field.
     * @var        int
     */
    protected $nb_people_2010;

    /**
     * The value for the nb_people_1999 field.
     * @var        int
     */
    protected $nb_people_1999;

    /**
     * The value for the nb_people_2012 field.
     * @var        int
     */
    protected $nb_people_2012;

    /**
     * The value for the density_2010 field.
     * @var        int
     */
    protected $density_2010;

    /**
     * The value for the surface field.
     * @var        double
     */
    protected $surface;

    /**
     * The value for the longitude_deg field.
     * @var        double
     */
    protected $longitude_deg;

    /**
     * The value for the latitude_deg field.
     * @var        double
     */
    protected $latitude_deg;

    /**
     * The value for the longitude_grd field.
     * @var        string
     */
    protected $longitude_grd;

    /**
     * The value for the latitude_grd field.
     * @var        string
     */
    protected $latitude_grd;

    /**
     * The value for the longitude_dms field.
     * @var        string
     */
    protected $longitude_dms;

    /**
     * The value for the latitude_dms field.
     * @var        string
     */
    protected $latitude_dms;

    /**
     * The value for the zmin field.
     * @var        int
     */
    protected $zmin;

    /**
     * The value for the zmax field.
     * @var        int
     */
    protected $zmax;

    /**
     * The value for the uuid field.
     * @var        string
     */
    protected $uuid;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * @var        PLDepartment
     */
    protected $aPLDepartment;

    /**
     * @var        PropelObjectCollection|PTScopePLC[] Collection to store aggregation of PTScopePLC objects.
     */
    protected $collPTScopePLCs;
    protected $collPTScopePLCsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUsers;
    protected $collPUsersPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPDDebates;
    protected $collPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;
    protected $collPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPTags;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTScopePLCsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [p_l_department_id] column value.
     *
     * @return int
     */
    public function getPLDepartmentId()
    {

        return $this->p_l_department_id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [name_simple] column value.
     *
     * @return string
     */
    public function getNameSimple()
    {

        return $this->name_simple;
    }

    /**
     * Get the [name_real] column value.
     *
     * @return string
     */
    public function getNameReal()
    {

        return $this->name_real;
    }

    /**
     * Get the [name_soundex] column value.
     *
     * @return string
     */
    public function getNameSoundex()
    {

        return $this->name_soundex;
    }

    /**
     * Get the [name_metaphone] column value.
     *
     * @return string
     */
    public function getNameMetaphone()
    {

        return $this->name_metaphone;
    }

    /**
     * Get the [zipcode] column value.
     *
     * @return string
     */
    public function getZipcode()
    {

        return $this->zipcode;
    }

    /**
     * Get the [municipality] column value.
     *
     * @return string
     */
    public function getMunicipality()
    {

        return $this->municipality;
    }

    /**
     * Get the [municipality_code] column value.
     *
     * @return string
     */
    public function getMunicipalityCode()
    {

        return $this->municipality_code;
    }

    /**
     * Get the [district] column value.
     *
     * @return int
     */
    public function getDistrict()
    {

        return $this->district;
    }

    /**
     * Get the [canton] column value.
     *
     * @return string
     */
    public function getCanton()
    {

        return $this->canton;
    }

    /**
     * Get the [amdi] column value.
     *
     * @return int
     */
    public function getAmdi()
    {

        return $this->amdi;
    }

    /**
     * Get the [nb_people_2010] column value.
     *
     * @return int
     */
    public function getNbPeople2010()
    {

        return $this->nb_people_2010;
    }

    /**
     * Get the [nb_people_1999] column value.
     *
     * @return int
     */
    public function getNbPeople1999()
    {

        return $this->nb_people_1999;
    }

    /**
     * Get the [nb_people_2012] column value.
     *
     * @return int
     */
    public function getNbPeople2012()
    {

        return $this->nb_people_2012;
    }

    /**
     * Get the [density_2010] column value.
     *
     * @return int
     */
    public function getDensity2010()
    {

        return $this->density_2010;
    }

    /**
     * Get the [surface] column value.
     *
     * @return double
     */
    public function getSurface()
    {

        return $this->surface;
    }

    /**
     * Get the [longitude_deg] column value.
     *
     * @return double
     */
    public function getLongitudeDeg()
    {

        return $this->longitude_deg;
    }

    /**
     * Get the [latitude_deg] column value.
     *
     * @return double
     */
    public function getLatitudeDeg()
    {

        return $this->latitude_deg;
    }

    /**
     * Get the [longitude_grd] column value.
     *
     * @return string
     */
    public function getLongitudeGrd()
    {

        return $this->longitude_grd;
    }

    /**
     * Get the [latitude_grd] column value.
     *
     * @return string
     */
    public function getLatitudeGrd()
    {

        return $this->latitude_grd;
    }

    /**
     * Get the [longitude_dms] column value.
     *
     * @return string
     */
    public function getLongitudeDms()
    {

        return $this->longitude_dms;
    }

    /**
     * Get the [latitude_dms] column value.
     *
     * @return string
     */
    public function getLatitudeDms()
    {

        return $this->latitude_dms;
    }

    /**
     * Get the [zmin] column value.
     *
     * @return int
     */
    public function getZmin()
    {

        return $this->zmin;
    }

    /**
     * Get the [zmax] column value.
     *
     * @return int
     */
    public function getZmax()
    {

        return $this->zmax;
    }

    /**
     * Get the [uuid] column value.
     *
     * @return string
     */
    public function getUuid()
    {

        return $this->uuid;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PLCityPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_l_department_id] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setPLDepartmentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_department_id !== $v) {
            $this->p_l_department_id = $v;
            $this->modifiedColumns[] = PLCityPeer::P_L_DEPARTMENT_ID;
        }

        if ($this->aPLDepartment !== null && $this->aPLDepartment->getId() !== $v) {
            $this->aPLDepartment = null;
        }


        return $this;
    } // setPLDepartmentId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PLCityPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [name_simple] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNameSimple($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_simple !== $v) {
            $this->name_simple = $v;
            $this->modifiedColumns[] = PLCityPeer::NAME_SIMPLE;
        }


        return $this;
    } // setNameSimple()

    /**
     * Set the value of [name_real] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNameReal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_real !== $v) {
            $this->name_real = $v;
            $this->modifiedColumns[] = PLCityPeer::NAME_REAL;
        }


        return $this;
    } // setNameReal()

    /**
     * Set the value of [name_soundex] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNameSoundex($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_soundex !== $v) {
            $this->name_soundex = $v;
            $this->modifiedColumns[] = PLCityPeer::NAME_SOUNDEX;
        }


        return $this;
    } // setNameSoundex()

    /**
     * Set the value of [name_metaphone] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNameMetaphone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_metaphone !== $v) {
            $this->name_metaphone = $v;
            $this->modifiedColumns[] = PLCityPeer::NAME_METAPHONE;
        }


        return $this;
    } // setNameMetaphone()

    /**
     * Set the value of [zipcode] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setZipcode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zipcode !== $v) {
            $this->zipcode = $v;
            $this->modifiedColumns[] = PLCityPeer::ZIPCODE;
        }


        return $this;
    } // setZipcode()

    /**
     * Set the value of [municipality] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setMunicipality($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->municipality !== $v) {
            $this->municipality = $v;
            $this->modifiedColumns[] = PLCityPeer::MUNICIPALITY;
        }


        return $this;
    } // setMunicipality()

    /**
     * Set the value of [municipality_code] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setMunicipalityCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->municipality_code !== $v) {
            $this->municipality_code = $v;
            $this->modifiedColumns[] = PLCityPeer::MUNICIPALITY_CODE;
        }


        return $this;
    } // setMunicipalityCode()

    /**
     * Set the value of [district] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setDistrict($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->district !== $v) {
            $this->district = $v;
            $this->modifiedColumns[] = PLCityPeer::DISTRICT;
        }


        return $this;
    } // setDistrict()

    /**
     * Set the value of [canton] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setCanton($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->canton !== $v) {
            $this->canton = $v;
            $this->modifiedColumns[] = PLCityPeer::CANTON;
        }


        return $this;
    } // setCanton()

    /**
     * Set the value of [amdi] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setAmdi($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->amdi !== $v) {
            $this->amdi = $v;
            $this->modifiedColumns[] = PLCityPeer::AMDI;
        }


        return $this;
    } // setAmdi()

    /**
     * Set the value of [nb_people_2010] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNbPeople2010($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_people_2010 !== $v) {
            $this->nb_people_2010 = $v;
            $this->modifiedColumns[] = PLCityPeer::NB_PEOPLE_2010;
        }


        return $this;
    } // setNbPeople2010()

    /**
     * Set the value of [nb_people_1999] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNbPeople1999($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_people_1999 !== $v) {
            $this->nb_people_1999 = $v;
            $this->modifiedColumns[] = PLCityPeer::NB_PEOPLE_1999;
        }


        return $this;
    } // setNbPeople1999()

    /**
     * Set the value of [nb_people_2012] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setNbPeople2012($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_people_2012 !== $v) {
            $this->nb_people_2012 = $v;
            $this->modifiedColumns[] = PLCityPeer::NB_PEOPLE_2012;
        }


        return $this;
    } // setNbPeople2012()

    /**
     * Set the value of [density_2010] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setDensity2010($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->density_2010 !== $v) {
            $this->density_2010 = $v;
            $this->modifiedColumns[] = PLCityPeer::DENSITY_2010;
        }


        return $this;
    } // setDensity2010()

    /**
     * Set the value of [surface] column.
     *
     * @param  double $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setSurface($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->surface !== $v) {
            $this->surface = $v;
            $this->modifiedColumns[] = PLCityPeer::SURFACE;
        }


        return $this;
    } // setSurface()

    /**
     * Set the value of [longitude_deg] column.
     *
     * @param  double $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLongitudeDeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->longitude_deg !== $v) {
            $this->longitude_deg = $v;
            $this->modifiedColumns[] = PLCityPeer::LONGITUDE_DEG;
        }


        return $this;
    } // setLongitudeDeg()

    /**
     * Set the value of [latitude_deg] column.
     *
     * @param  double $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLatitudeDeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->latitude_deg !== $v) {
            $this->latitude_deg = $v;
            $this->modifiedColumns[] = PLCityPeer::LATITUDE_DEG;
        }


        return $this;
    } // setLatitudeDeg()

    /**
     * Set the value of [longitude_grd] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLongitudeGrd($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->longitude_grd !== $v) {
            $this->longitude_grd = $v;
            $this->modifiedColumns[] = PLCityPeer::LONGITUDE_GRD;
        }


        return $this;
    } // setLongitudeGrd()

    /**
     * Set the value of [latitude_grd] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLatitudeGrd($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->latitude_grd !== $v) {
            $this->latitude_grd = $v;
            $this->modifiedColumns[] = PLCityPeer::LATITUDE_GRD;
        }


        return $this;
    } // setLatitudeGrd()

    /**
     * Set the value of [longitude_dms] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLongitudeDms($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->longitude_dms !== $v) {
            $this->longitude_dms = $v;
            $this->modifiedColumns[] = PLCityPeer::LONGITUDE_DMS;
        }


        return $this;
    } // setLongitudeDms()

    /**
     * Set the value of [latitude_dms] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setLatitudeDms($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->latitude_dms !== $v) {
            $this->latitude_dms = $v;
            $this->modifiedColumns[] = PLCityPeer::LATITUDE_DMS;
        }


        return $this;
    } // setLatitudeDms()

    /**
     * Set the value of [zmin] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setZmin($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->zmin !== $v) {
            $this->zmin = $v;
            $this->modifiedColumns[] = PLCityPeer::ZMIN;
        }


        return $this;
    } // setZmin()

    /**
     * Set the value of [zmax] column.
     *
     * @param  int $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setZmax($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->zmax !== $v) {
            $this->zmax = $v;
            $this->modifiedColumns[] = PLCityPeer::ZMAX;
        }


        return $this;
    } // setZmax()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PLCityPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PLCity The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PLCityPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PLCity The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PLCityPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PLCity The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PLCityPeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->p_l_department_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->name_simple = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->name_real = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->name_soundex = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->name_metaphone = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->zipcode = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->municipality = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->municipality_code = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->district = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->canton = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->amdi = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->nb_people_2010 = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->nb_people_1999 = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->nb_people_2012 = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->density_2010 = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->surface = ($row[$startcol + 17] !== null) ? (double) $row[$startcol + 17] : null;
            $this->longitude_deg = ($row[$startcol + 18] !== null) ? (double) $row[$startcol + 18] : null;
            $this->latitude_deg = ($row[$startcol + 19] !== null) ? (double) $row[$startcol + 19] : null;
            $this->longitude_grd = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->latitude_grd = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->longitude_dms = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->latitude_dms = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->zmin = ($row[$startcol + 24] !== null) ? (int) $row[$startcol + 24] : null;
            $this->zmax = ($row[$startcol + 25] !== null) ? (int) $row[$startcol + 25] : null;
            $this->uuid = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->created_at = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->updated_at = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->slug = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 30; // 30 = PLCityPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PLCity object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aPLDepartment !== null && $this->p_l_department_id !== $this->aPLDepartment->getId()) {
            $this->aPLDepartment = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PLCityPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPLDepartment = null;
            $this->collPTScopePLCs = null;

            $this->collPUsers = null;

            $this->collPDDebates = null;

            $this->collPDReactions = null;

            $this->collPTags = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PLCityQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PLCityPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PLCityPeer::NAME)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PLCityPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PLCityPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PLCityPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PLCityPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPLDepartment !== null) {
                if ($this->aPLDepartment->isModified() || $this->aPLDepartment->isNew()) {
                    $affectedRows += $this->aPLDepartment->save($con);
                }
                $this->setPLDepartment($this->aPLDepartment);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->pTagsScheduledForDeletion !== null) {
                if (!$this->pTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PTScopePLCQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pTagsScheduledForDeletion = null;
                }

                foreach ($this->getPTags() as $pTag) {
                    if ($pTag->isModified()) {
                        $pTag->save($con);
                    }
                }
            } elseif ($this->collPTags) {
                foreach ($this->collPTags as $pTag) {
                    if ($pTag->isModified()) {
                        $pTag->save($con);
                    }
                }
            }

            if ($this->pTScopePLCsScheduledForDeletion !== null) {
                if (!$this->pTScopePLCsScheduledForDeletion->isEmpty()) {
                    PTScopePLCQuery::create()
                        ->filterByPrimaryKeys($this->pTScopePLCsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pTScopePLCsScheduledForDeletion = null;
                }
            }

            if ($this->collPTScopePLCs !== null) {
                foreach ($this->collPTScopePLCs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUsersScheduledForDeletion !== null) {
                if (!$this->pUsersScheduledForDeletion->isEmpty()) {
                    foreach ($this->pUsersScheduledForDeletion as $pUser) {
                        // need to save related object because we set the relation to null
                        $pUser->save($con);
                    }
                    $this->pUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPUsers !== null) {
                foreach ($this->collPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDebatesScheduledForDeletion !== null) {
                if (!$this->pDDebatesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDDebatesScheduledForDeletion as $pDDebate) {
                        // need to save related object because we set the relation to null
                        $pDDebate->save($con);
                    }
                    $this->pDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPDDebates !== null) {
                foreach ($this->collPDDebates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDReactionsScheduledForDeletion !== null) {
                if (!$this->pDReactionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDReactionsScheduledForDeletion as $pDReaction) {
                        // need to save related object because we set the relation to null
                        $pDReaction->save($con);
                    }
                    $this->pDReactionsScheduledForDeletion = null;
                }
            }

            if ($this->collPDReactions !== null) {
                foreach ($this->collPDReactions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = PLCityPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PLCityPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PLCityPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PLCityPeer::P_L_DEPARTMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_department_id`';
        }
        if ($this->isColumnModified(PLCityPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(PLCityPeer::NAME_SIMPLE)) {
            $modifiedColumns[':p' . $index++]  = '`name_simple`';
        }
        if ($this->isColumnModified(PLCityPeer::NAME_REAL)) {
            $modifiedColumns[':p' . $index++]  = '`name_real`';
        }
        if ($this->isColumnModified(PLCityPeer::NAME_SOUNDEX)) {
            $modifiedColumns[':p' . $index++]  = '`name_soundex`';
        }
        if ($this->isColumnModified(PLCityPeer::NAME_METAPHONE)) {
            $modifiedColumns[':p' . $index++]  = '`name_metaphone`';
        }
        if ($this->isColumnModified(PLCityPeer::ZIPCODE)) {
            $modifiedColumns[':p' . $index++]  = '`zipcode`';
        }
        if ($this->isColumnModified(PLCityPeer::MUNICIPALITY)) {
            $modifiedColumns[':p' . $index++]  = '`municipality`';
        }
        if ($this->isColumnModified(PLCityPeer::MUNICIPALITY_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`municipality_code`';
        }
        if ($this->isColumnModified(PLCityPeer::DISTRICT)) {
            $modifiedColumns[':p' . $index++]  = '`district`';
        }
        if ($this->isColumnModified(PLCityPeer::CANTON)) {
            $modifiedColumns[':p' . $index++]  = '`canton`';
        }
        if ($this->isColumnModified(PLCityPeer::AMDI)) {
            $modifiedColumns[':p' . $index++]  = '`amdi`';
        }
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_2010)) {
            $modifiedColumns[':p' . $index++]  = '`nb_people_2010`';
        }
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_1999)) {
            $modifiedColumns[':p' . $index++]  = '`nb_people_1999`';
        }
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_2012)) {
            $modifiedColumns[':p' . $index++]  = '`nb_people_2012`';
        }
        if ($this->isColumnModified(PLCityPeer::DENSITY_2010)) {
            $modifiedColumns[':p' . $index++]  = '`density_2010`';
        }
        if ($this->isColumnModified(PLCityPeer::SURFACE)) {
            $modifiedColumns[':p' . $index++]  = '`surface`';
        }
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_DEG)) {
            $modifiedColumns[':p' . $index++]  = '`longitude_deg`';
        }
        if ($this->isColumnModified(PLCityPeer::LATITUDE_DEG)) {
            $modifiedColumns[':p' . $index++]  = '`latitude_deg`';
        }
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_GRD)) {
            $modifiedColumns[':p' . $index++]  = '`longitude_grd`';
        }
        if ($this->isColumnModified(PLCityPeer::LATITUDE_GRD)) {
            $modifiedColumns[':p' . $index++]  = '`latitude_grd`';
        }
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_DMS)) {
            $modifiedColumns[':p' . $index++]  = '`longitude_dms`';
        }
        if ($this->isColumnModified(PLCityPeer::LATITUDE_DMS)) {
            $modifiedColumns[':p' . $index++]  = '`latitude_dms`';
        }
        if ($this->isColumnModified(PLCityPeer::ZMIN)) {
            $modifiedColumns[':p' . $index++]  = '`zmin`';
        }
        if ($this->isColumnModified(PLCityPeer::ZMAX)) {
            $modifiedColumns[':p' . $index++]  = '`zmax`';
        }
        if ($this->isColumnModified(PLCityPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PLCityPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PLCityPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PLCityPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `p_l_city` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`p_l_department_id`':
                        $stmt->bindValue($identifier, $this->p_l_department_id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`name_simple`':
                        $stmt->bindValue($identifier, $this->name_simple, PDO::PARAM_STR);
                        break;
                    case '`name_real`':
                        $stmt->bindValue($identifier, $this->name_real, PDO::PARAM_STR);
                        break;
                    case '`name_soundex`':
                        $stmt->bindValue($identifier, $this->name_soundex, PDO::PARAM_STR);
                        break;
                    case '`name_metaphone`':
                        $stmt->bindValue($identifier, $this->name_metaphone, PDO::PARAM_STR);
                        break;
                    case '`zipcode`':
                        $stmt->bindValue($identifier, $this->zipcode, PDO::PARAM_STR);
                        break;
                    case '`municipality`':
                        $stmt->bindValue($identifier, $this->municipality, PDO::PARAM_STR);
                        break;
                    case '`municipality_code`':
                        $stmt->bindValue($identifier, $this->municipality_code, PDO::PARAM_STR);
                        break;
                    case '`district`':
                        $stmt->bindValue($identifier, $this->district, PDO::PARAM_INT);
                        break;
                    case '`canton`':
                        $stmt->bindValue($identifier, $this->canton, PDO::PARAM_STR);
                        break;
                    case '`amdi`':
                        $stmt->bindValue($identifier, $this->amdi, PDO::PARAM_INT);
                        break;
                    case '`nb_people_2010`':
                        $stmt->bindValue($identifier, $this->nb_people_2010, PDO::PARAM_INT);
                        break;
                    case '`nb_people_1999`':
                        $stmt->bindValue($identifier, $this->nb_people_1999, PDO::PARAM_INT);
                        break;
                    case '`nb_people_2012`':
                        $stmt->bindValue($identifier, $this->nb_people_2012, PDO::PARAM_INT);
                        break;
                    case '`density_2010`':
                        $stmt->bindValue($identifier, $this->density_2010, PDO::PARAM_INT);
                        break;
                    case '`surface`':
                        $stmt->bindValue($identifier, $this->surface, PDO::PARAM_STR);
                        break;
                    case '`longitude_deg`':
                        $stmt->bindValue($identifier, $this->longitude_deg, PDO::PARAM_STR);
                        break;
                    case '`latitude_deg`':
                        $stmt->bindValue($identifier, $this->latitude_deg, PDO::PARAM_STR);
                        break;
                    case '`longitude_grd`':
                        $stmt->bindValue($identifier, $this->longitude_grd, PDO::PARAM_STR);
                        break;
                    case '`latitude_grd`':
                        $stmt->bindValue($identifier, $this->latitude_grd, PDO::PARAM_STR);
                        break;
                    case '`longitude_dms`':
                        $stmt->bindValue($identifier, $this->longitude_dms, PDO::PARAM_STR);
                        break;
                    case '`latitude_dms`':
                        $stmt->bindValue($identifier, $this->latitude_dms, PDO::PARAM_STR);
                        break;
                    case '`zmin`':
                        $stmt->bindValue($identifier, $this->zmin, PDO::PARAM_INT);
                        break;
                    case '`zmax`':
                        $stmt->bindValue($identifier, $this->zmax, PDO::PARAM_INT);
                        break;
                    case '`uuid`':
                        $stmt->bindValue($identifier, $this->uuid, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PLCityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getPLDepartmentId();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getNameSimple();
                break;
            case 4:
                return $this->getNameReal();
                break;
            case 5:
                return $this->getNameSoundex();
                break;
            case 6:
                return $this->getNameMetaphone();
                break;
            case 7:
                return $this->getZipcode();
                break;
            case 8:
                return $this->getMunicipality();
                break;
            case 9:
                return $this->getMunicipalityCode();
                break;
            case 10:
                return $this->getDistrict();
                break;
            case 11:
                return $this->getCanton();
                break;
            case 12:
                return $this->getAmdi();
                break;
            case 13:
                return $this->getNbPeople2010();
                break;
            case 14:
                return $this->getNbPeople1999();
                break;
            case 15:
                return $this->getNbPeople2012();
                break;
            case 16:
                return $this->getDensity2010();
                break;
            case 17:
                return $this->getSurface();
                break;
            case 18:
                return $this->getLongitudeDeg();
                break;
            case 19:
                return $this->getLatitudeDeg();
                break;
            case 20:
                return $this->getLongitudeGrd();
                break;
            case 21:
                return $this->getLatitudeGrd();
                break;
            case 22:
                return $this->getLongitudeDms();
                break;
            case 23:
                return $this->getLatitudeDms();
                break;
            case 24:
                return $this->getZmin();
                break;
            case 25:
                return $this->getZmax();
                break;
            case 26:
                return $this->getUuid();
                break;
            case 27:
                return $this->getCreatedAt();
                break;
            case 28:
                return $this->getUpdatedAt();
                break;
            case 29:
                return $this->getSlug();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['PLCity'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PLCity'][$this->getPrimaryKey()] = true;
        $keys = PLCityPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPLDepartmentId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getNameSimple(),
            $keys[4] => $this->getNameReal(),
            $keys[5] => $this->getNameSoundex(),
            $keys[6] => $this->getNameMetaphone(),
            $keys[7] => $this->getZipcode(),
            $keys[8] => $this->getMunicipality(),
            $keys[9] => $this->getMunicipalityCode(),
            $keys[10] => $this->getDistrict(),
            $keys[11] => $this->getCanton(),
            $keys[12] => $this->getAmdi(),
            $keys[13] => $this->getNbPeople2010(),
            $keys[14] => $this->getNbPeople1999(),
            $keys[15] => $this->getNbPeople2012(),
            $keys[16] => $this->getDensity2010(),
            $keys[17] => $this->getSurface(),
            $keys[18] => $this->getLongitudeDeg(),
            $keys[19] => $this->getLatitudeDeg(),
            $keys[20] => $this->getLongitudeGrd(),
            $keys[21] => $this->getLatitudeGrd(),
            $keys[22] => $this->getLongitudeDms(),
            $keys[23] => $this->getLatitudeDms(),
            $keys[24] => $this->getZmin(),
            $keys[25] => $this->getZmax(),
            $keys[26] => $this->getUuid(),
            $keys[27] => $this->getCreatedAt(),
            $keys[28] => $this->getUpdatedAt(),
            $keys[29] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPLDepartment) {
                $result['PLDepartment'] = $this->aPLDepartment->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPTScopePLCs) {
                $result['PTScopePLCs'] = $this->collPTScopePLCs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUsers) {
                $result['PUsers'] = $this->collPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDebates) {
                $result['PDDebates'] = $this->collPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PLCityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setPLDepartmentId($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setNameSimple($value);
                break;
            case 4:
                $this->setNameReal($value);
                break;
            case 5:
                $this->setNameSoundex($value);
                break;
            case 6:
                $this->setNameMetaphone($value);
                break;
            case 7:
                $this->setZipcode($value);
                break;
            case 8:
                $this->setMunicipality($value);
                break;
            case 9:
                $this->setMunicipalityCode($value);
                break;
            case 10:
                $this->setDistrict($value);
                break;
            case 11:
                $this->setCanton($value);
                break;
            case 12:
                $this->setAmdi($value);
                break;
            case 13:
                $this->setNbPeople2010($value);
                break;
            case 14:
                $this->setNbPeople1999($value);
                break;
            case 15:
                $this->setNbPeople2012($value);
                break;
            case 16:
                $this->setDensity2010($value);
                break;
            case 17:
                $this->setSurface($value);
                break;
            case 18:
                $this->setLongitudeDeg($value);
                break;
            case 19:
                $this->setLatitudeDeg($value);
                break;
            case 20:
                $this->setLongitudeGrd($value);
                break;
            case 21:
                $this->setLatitudeGrd($value);
                break;
            case 22:
                $this->setLongitudeDms($value);
                break;
            case 23:
                $this->setLatitudeDms($value);
                break;
            case 24:
                $this->setZmin($value);
                break;
            case 25:
                $this->setZmax($value);
                break;
            case 26:
                $this->setUuid($value);
                break;
            case 27:
                $this->setCreatedAt($value);
                break;
            case 28:
                $this->setUpdatedAt($value);
                break;
            case 29:
                $this->setSlug($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = PLCityPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPLDepartmentId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setNameSimple($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setNameReal($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNameSoundex($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNameMetaphone($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setZipcode($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMunicipality($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setMunicipalityCode($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setDistrict($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCanton($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setAmdi($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setNbPeople2010($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setNbPeople1999($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNbPeople2012($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setDensity2010($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setSurface($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setLongitudeDeg($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setLatitudeDeg($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setLongitudeGrd($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setLatitudeGrd($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setLongitudeDms($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setLatitudeDms($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setZmin($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setZmax($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setUuid($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setCreatedAt($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setUpdatedAt($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setSlug($arr[$keys[29]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PLCityPeer::DATABASE_NAME);

        if ($this->isColumnModified(PLCityPeer::ID)) $criteria->add(PLCityPeer::ID, $this->id);
        if ($this->isColumnModified(PLCityPeer::P_L_DEPARTMENT_ID)) $criteria->add(PLCityPeer::P_L_DEPARTMENT_ID, $this->p_l_department_id);
        if ($this->isColumnModified(PLCityPeer::NAME)) $criteria->add(PLCityPeer::NAME, $this->name);
        if ($this->isColumnModified(PLCityPeer::NAME_SIMPLE)) $criteria->add(PLCityPeer::NAME_SIMPLE, $this->name_simple);
        if ($this->isColumnModified(PLCityPeer::NAME_REAL)) $criteria->add(PLCityPeer::NAME_REAL, $this->name_real);
        if ($this->isColumnModified(PLCityPeer::NAME_SOUNDEX)) $criteria->add(PLCityPeer::NAME_SOUNDEX, $this->name_soundex);
        if ($this->isColumnModified(PLCityPeer::NAME_METAPHONE)) $criteria->add(PLCityPeer::NAME_METAPHONE, $this->name_metaphone);
        if ($this->isColumnModified(PLCityPeer::ZIPCODE)) $criteria->add(PLCityPeer::ZIPCODE, $this->zipcode);
        if ($this->isColumnModified(PLCityPeer::MUNICIPALITY)) $criteria->add(PLCityPeer::MUNICIPALITY, $this->municipality);
        if ($this->isColumnModified(PLCityPeer::MUNICIPALITY_CODE)) $criteria->add(PLCityPeer::MUNICIPALITY_CODE, $this->municipality_code);
        if ($this->isColumnModified(PLCityPeer::DISTRICT)) $criteria->add(PLCityPeer::DISTRICT, $this->district);
        if ($this->isColumnModified(PLCityPeer::CANTON)) $criteria->add(PLCityPeer::CANTON, $this->canton);
        if ($this->isColumnModified(PLCityPeer::AMDI)) $criteria->add(PLCityPeer::AMDI, $this->amdi);
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_2010)) $criteria->add(PLCityPeer::NB_PEOPLE_2010, $this->nb_people_2010);
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_1999)) $criteria->add(PLCityPeer::NB_PEOPLE_1999, $this->nb_people_1999);
        if ($this->isColumnModified(PLCityPeer::NB_PEOPLE_2012)) $criteria->add(PLCityPeer::NB_PEOPLE_2012, $this->nb_people_2012);
        if ($this->isColumnModified(PLCityPeer::DENSITY_2010)) $criteria->add(PLCityPeer::DENSITY_2010, $this->density_2010);
        if ($this->isColumnModified(PLCityPeer::SURFACE)) $criteria->add(PLCityPeer::SURFACE, $this->surface);
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_DEG)) $criteria->add(PLCityPeer::LONGITUDE_DEG, $this->longitude_deg);
        if ($this->isColumnModified(PLCityPeer::LATITUDE_DEG)) $criteria->add(PLCityPeer::LATITUDE_DEG, $this->latitude_deg);
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_GRD)) $criteria->add(PLCityPeer::LONGITUDE_GRD, $this->longitude_grd);
        if ($this->isColumnModified(PLCityPeer::LATITUDE_GRD)) $criteria->add(PLCityPeer::LATITUDE_GRD, $this->latitude_grd);
        if ($this->isColumnModified(PLCityPeer::LONGITUDE_DMS)) $criteria->add(PLCityPeer::LONGITUDE_DMS, $this->longitude_dms);
        if ($this->isColumnModified(PLCityPeer::LATITUDE_DMS)) $criteria->add(PLCityPeer::LATITUDE_DMS, $this->latitude_dms);
        if ($this->isColumnModified(PLCityPeer::ZMIN)) $criteria->add(PLCityPeer::ZMIN, $this->zmin);
        if ($this->isColumnModified(PLCityPeer::ZMAX)) $criteria->add(PLCityPeer::ZMAX, $this->zmax);
        if ($this->isColumnModified(PLCityPeer::UUID)) $criteria->add(PLCityPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PLCityPeer::CREATED_AT)) $criteria->add(PLCityPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PLCityPeer::UPDATED_AT)) $criteria->add(PLCityPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PLCityPeer::SLUG)) $criteria->add(PLCityPeer::SLUG, $this->slug);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PLCityPeer::DATABASE_NAME);
        $criteria->add(PLCityPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of PLCity (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPLDepartmentId($this->getPLDepartmentId());
        $copyObj->setName($this->getName());
        $copyObj->setNameSimple($this->getNameSimple());
        $copyObj->setNameReal($this->getNameReal());
        $copyObj->setNameSoundex($this->getNameSoundex());
        $copyObj->setNameMetaphone($this->getNameMetaphone());
        $copyObj->setZipcode($this->getZipcode());
        $copyObj->setMunicipality($this->getMunicipality());
        $copyObj->setMunicipalityCode($this->getMunicipalityCode());
        $copyObj->setDistrict($this->getDistrict());
        $copyObj->setCanton($this->getCanton());
        $copyObj->setAmdi($this->getAmdi());
        $copyObj->setNbPeople2010($this->getNbPeople2010());
        $copyObj->setNbPeople1999($this->getNbPeople1999());
        $copyObj->setNbPeople2012($this->getNbPeople2012());
        $copyObj->setDensity2010($this->getDensity2010());
        $copyObj->setSurface($this->getSurface());
        $copyObj->setLongitudeDeg($this->getLongitudeDeg());
        $copyObj->setLatitudeDeg($this->getLatitudeDeg());
        $copyObj->setLongitudeGrd($this->getLongitudeGrd());
        $copyObj->setLatitudeGrd($this->getLatitudeGrd());
        $copyObj->setLongitudeDms($this->getLongitudeDms());
        $copyObj->setLatitudeDms($this->getLatitudeDms());
        $copyObj->setZmin($this->getZmin());
        $copyObj->setZmax($this->getZmax());
        $copyObj->setUuid($this->getUuid());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPTScopePLCs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPTScopePLC($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDReaction($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return PLCity Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return PLCityPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PLCityPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PLDepartment object.
     *
     * @param                  PLDepartment $v
     * @return PLCity The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLDepartment(PLDepartment $v = null)
    {
        if ($v === null) {
            $this->setPLDepartmentId(NULL);
        } else {
            $this->setPLDepartmentId($v->getId());
        }

        $this->aPLDepartment = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLDepartment object, it will not be re-added.
        if ($v !== null) {
            $v->addPLCity($this);
        }


        return $this;
    }


    /**
     * Get the associated PLDepartment object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLDepartment The associated PLDepartment object.
     * @throws PropelException
     */
    public function getPLDepartment(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLDepartment === null && ($this->p_l_department_id !== null) && $doQuery) {
            $this->aPLDepartment = PLDepartmentQuery::create()->findPk($this->p_l_department_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLDepartment->addPLCities($this);
             */
        }

        return $this->aPLDepartment;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PTScopePLC' == $relationName) {
            $this->initPTScopePLCs();
        }
        if ('PUser' == $relationName) {
            $this->initPUsers();
        }
        if ('PDDebate' == $relationName) {
            $this->initPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
    }

    /**
     * Clears out the collPTScopePLCs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLCity The current object (for fluent API support)
     * @see        addPTScopePLCs()
     */
    public function clearPTScopePLCs()
    {
        $this->collPTScopePLCs = null; // important to set this to null since that means it is uninitialized
        $this->collPTScopePLCsPartial = null;

        return $this;
    }

    /**
     * reset is the collPTScopePLCs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPTScopePLCs($v = true)
    {
        $this->collPTScopePLCsPartial = $v;
    }

    /**
     * Initializes the collPTScopePLCs collection.
     *
     * By default this just sets the collPTScopePLCs collection to an empty array (like clearcollPTScopePLCs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPTScopePLCs($overrideExisting = true)
    {
        if (null !== $this->collPTScopePLCs && !$overrideExisting) {
            return;
        }
        $this->collPTScopePLCs = new PropelObjectCollection();
        $this->collPTScopePLCs->setModel('PTScopePLC');
    }

    /**
     * Gets an array of PTScopePLC objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLCity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PTScopePLC[] List of PTScopePLC objects
     * @throws PropelException
     */
    public function getPTScopePLCs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPTScopePLCsPartial && !$this->isNew();
        if (null === $this->collPTScopePLCs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPTScopePLCs) {
                // return empty collection
                $this->initPTScopePLCs();
            } else {
                $collPTScopePLCs = PTScopePLCQuery::create(null, $criteria)
                    ->filterByPLCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPTScopePLCsPartial && count($collPTScopePLCs)) {
                      $this->initPTScopePLCs(false);

                      foreach ($collPTScopePLCs as $obj) {
                        if (false == $this->collPTScopePLCs->contains($obj)) {
                          $this->collPTScopePLCs->append($obj);
                        }
                      }

                      $this->collPTScopePLCsPartial = true;
                    }

                    $collPTScopePLCs->getInternalIterator()->rewind();

                    return $collPTScopePLCs;
                }

                if ($partial && $this->collPTScopePLCs) {
                    foreach ($this->collPTScopePLCs as $obj) {
                        if ($obj->isNew()) {
                            $collPTScopePLCs[] = $obj;
                        }
                    }
                }

                $this->collPTScopePLCs = $collPTScopePLCs;
                $this->collPTScopePLCsPartial = false;
            }
        }

        return $this->collPTScopePLCs;
    }

    /**
     * Sets a collection of PTScopePLC objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTScopePLCs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLCity The current object (for fluent API support)
     */
    public function setPTScopePLCs(PropelCollection $pTScopePLCs, PropelPDO $con = null)
    {
        $pTScopePLCsToDelete = $this->getPTScopePLCs(new Criteria(), $con)->diff($pTScopePLCs);


        $this->pTScopePLCsScheduledForDeletion = $pTScopePLCsToDelete;

        foreach ($pTScopePLCsToDelete as $pTScopePLCRemoved) {
            $pTScopePLCRemoved->setPLCity(null);
        }

        $this->collPTScopePLCs = null;
        foreach ($pTScopePLCs as $pTScopePLC) {
            $this->addPTScopePLC($pTScopePLC);
        }

        $this->collPTScopePLCs = $pTScopePLCs;
        $this->collPTScopePLCsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PTScopePLC objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PTScopePLC objects.
     * @throws PropelException
     */
    public function countPTScopePLCs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPTScopePLCsPartial && !$this->isNew();
        if (null === $this->collPTScopePLCs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPTScopePLCs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPTScopePLCs());
            }
            $query = PTScopePLCQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPLCity($this)
                ->count($con);
        }

        return count($this->collPTScopePLCs);
    }

    /**
     * Method called to associate a PTScopePLC object to this object
     * through the PTScopePLC foreign key attribute.
     *
     * @param    PTScopePLC $l PTScopePLC
     * @return PLCity The current object (for fluent API support)
     */
    public function addPTScopePLC(PTScopePLC $l)
    {
        if ($this->collPTScopePLCs === null) {
            $this->initPTScopePLCs();
            $this->collPTScopePLCsPartial = true;
        }

        if (!in_array($l, $this->collPTScopePLCs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPTScopePLC($l);

            if ($this->pTScopePLCsScheduledForDeletion and $this->pTScopePLCsScheduledForDeletion->contains($l)) {
                $this->pTScopePLCsScheduledForDeletion->remove($this->pTScopePLCsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PTScopePLC $pTScopePLC The pTScopePLC object to add.
     */
    protected function doAddPTScopePLC($pTScopePLC)
    {
        $this->collPTScopePLCs[]= $pTScopePLC;
        $pTScopePLC->setPLCity($this);
    }

    /**
     * @param	PTScopePLC $pTScopePLC The pTScopePLC object to remove.
     * @return PLCity The current object (for fluent API support)
     */
    public function removePTScopePLC($pTScopePLC)
    {
        if ($this->getPTScopePLCs()->contains($pTScopePLC)) {
            $this->collPTScopePLCs->remove($this->collPTScopePLCs->search($pTScopePLC));
            if (null === $this->pTScopePLCsScheduledForDeletion) {
                $this->pTScopePLCsScheduledForDeletion = clone $this->collPTScopePLCs;
                $this->pTScopePLCsScheduledForDeletion->clear();
            }
            $this->pTScopePLCsScheduledForDeletion[]= clone $pTScopePLC;
            $pTScopePLC->setPLCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PTScopePLCs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PTScopePLC[] List of PTScopePLC objects
     */
    public function getPTScopePLCsJoinPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTScopePLCQuery::create(null, $criteria);
        $query->joinWith('PTag', $join_behavior);

        return $this->getPTScopePLCs($query, $con);
    }

    /**
     * Clears out the collPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLCity The current object (for fluent API support)
     * @see        addPUsers()
     */
    public function clearPUsers()
    {
        $this->collPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUsers($v = true)
    {
        $this->collPUsersPartial = $v;
    }

    /**
     * Initializes the collPUsers collection.
     *
     * By default this just sets the collPUsers collection to an empty array (like clearcollPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUsers($overrideExisting = true)
    {
        if (null !== $this->collPUsers && !$overrideExisting) {
            return;
        }
        $this->collPUsers = new PropelObjectCollection();
        $this->collPUsers->setModel('PUser');
    }

    /**
     * Gets an array of PUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLCity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUser[] List of PUser objects
     * @throws PropelException
     */
    public function getPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUsersPartial && !$this->isNew();
        if (null === $this->collPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUsers) {
                // return empty collection
                $this->initPUsers();
            } else {
                $collPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPLCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUsersPartial && count($collPUsers)) {
                      $this->initPUsers(false);

                      foreach ($collPUsers as $obj) {
                        if (false == $this->collPUsers->contains($obj)) {
                          $this->collPUsers->append($obj);
                        }
                      }

                      $this->collPUsersPartial = true;
                    }

                    $collPUsers->getInternalIterator()->rewind();

                    return $collPUsers;
                }

                if ($partial && $this->collPUsers) {
                    foreach ($this->collPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPUsers[] = $obj;
                        }
                    }
                }

                $this->collPUsers = $collPUsers;
                $this->collPUsersPartial = false;
            }
        }

        return $this->collPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLCity The current object (for fluent API support)
     */
    public function setPUsers(PropelCollection $pUsers, PropelPDO $con = null)
    {
        $pUsersToDelete = $this->getPUsers(new Criteria(), $con)->diff($pUsers);


        $this->pUsersScheduledForDeletion = $pUsersToDelete;

        foreach ($pUsersToDelete as $pUserRemoved) {
            $pUserRemoved->setPLCity(null);
        }

        $this->collPUsers = null;
        foreach ($pUsers as $pUser) {
            $this->addPUser($pUser);
        }

        $this->collPUsers = $pUsers;
        $this->collPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUser objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUser objects.
     * @throws PropelException
     */
    public function countPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUsersPartial && !$this->isNew();
        if (null === $this->collPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUsers());
            }
            $query = PUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPLCity($this)
                ->count($con);
        }

        return count($this->collPUsers);
    }

    /**
     * Method called to associate a PUser object to this object
     * through the PUser foreign key attribute.
     *
     * @param    PUser $l PUser
     * @return PLCity The current object (for fluent API support)
     */
    public function addPUser(PUser $l)
    {
        if ($this->collPUsers === null) {
            $this->initPUsers();
            $this->collPUsersPartial = true;
        }

        if (!in_array($l, $this->collPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUser($l);

            if ($this->pUsersScheduledForDeletion and $this->pUsersScheduledForDeletion->contains($l)) {
                $this->pUsersScheduledForDeletion->remove($this->pUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUser $pUser The pUser object to add.
     */
    protected function doAddPUser($pUser)
    {
        $this->collPUsers[]= $pUser;
        $pUser->setPLCity($this);
    }

    /**
     * @param	PUser $pUser The pUser object to remove.
     * @return PLCity The current object (for fluent API support)
     */
    public function removePUser($pUser)
    {
        if ($this->getPUsers()->contains($pUser)) {
            $this->collPUsers->remove($this->collPUsers->search($pUser));
            if (null === $this->pUsersScheduledForDeletion) {
                $this->pUsersScheduledForDeletion = clone $this->collPUsers;
                $this->pUsersScheduledForDeletion->clear();
            }
            $this->pUsersScheduledForDeletion[]= $pUser;
            $pUser->setPLCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUsersJoinPUStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUserQuery::create(null, $criteria);
        $query->joinWith('PUStatus', $join_behavior);

        return $this->getPUsers($query, $con);
    }

    /**
     * Clears out the collPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLCity The current object (for fluent API support)
     * @see        addPDDebates()
     */
    public function clearPDDebates()
    {
        $this->collPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDDebates($v = true)
    {
        $this->collPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPDDebates collection.
     *
     * By default this just sets the collPDDebates collection to an empty array (like clearcollPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPDDebates = new PropelObjectCollection();
        $this->collPDDebates->setModel('PDDebate');
    }

    /**
     * Gets an array of PDDebate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLCity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     * @throws PropelException
     */
    public function getPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                // return empty collection
                $this->initPDDebates();
            } else {
                $collPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPLCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDebatesPartial && count($collPDDebates)) {
                      $this->initPDDebates(false);

                      foreach ($collPDDebates as $obj) {
                        if (false == $this->collPDDebates->contains($obj)) {
                          $this->collPDDebates->append($obj);
                        }
                      }

                      $this->collPDDebatesPartial = true;
                    }

                    $collPDDebates->getInternalIterator()->rewind();

                    return $collPDDebates;
                }

                if ($partial && $this->collPDDebates) {
                    foreach ($this->collPDDebates as $obj) {
                        if ($obj->isNew()) {
                            $collPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPDDebates = $collPDDebates;
                $this->collPDDebatesPartial = false;
            }
        }

        return $this->collPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLCity The current object (for fluent API support)
     */
    public function setPDDebates(PropelCollection $pDDebates, PropelPDO $con = null)
    {
        $pDDebatesToDelete = $this->getPDDebates(new Criteria(), $con)->diff($pDDebates);


        $this->pDDebatesScheduledForDeletion = $pDDebatesToDelete;

        foreach ($pDDebatesToDelete as $pDDebateRemoved) {
            $pDDebateRemoved->setPLCity(null);
        }

        $this->collPDDebates = null;
        foreach ($pDDebates as $pDDebate) {
            $this->addPDDebate($pDDebate);
        }

        $this->collPDDebates = $pDDebates;
        $this->collPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDDebate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDDebate objects.
     * @throws PropelException
     */
    public function countPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDDebates());
            }
            $query = PDDebateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPLCity($this)
                ->count($con);
        }

        return count($this->collPDDebates);
    }

    /**
     * Method called to associate a PDDebate object to this object
     * through the PDDebate foreign key attribute.
     *
     * @param    PDDebate $l PDDebate
     * @return PLCity The current object (for fluent API support)
     */
    public function addPDDebate(PDDebate $l)
    {
        if ($this->collPDDebates === null) {
            $this->initPDDebates();
            $this->collPDDebatesPartial = true;
        }

        if (!in_array($l, $this->collPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDebate($l);

            if ($this->pDDebatesScheduledForDeletion and $this->pDDebatesScheduledForDeletion->contains($l)) {
                $this->pDDebatesScheduledForDeletion->remove($this->pDDebatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to add.
     */
    protected function doAddPDDebate($pDDebate)
    {
        $this->collPDDebates[]= $pDDebate;
        $pDDebate->setPLCity($this);
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to remove.
     * @return PLCity The current object (for fluent API support)
     */
    public function removePDDebate($pDDebate)
    {
        if ($this->getPDDebates()->contains($pDDebate)) {
            $this->collPDDebates->remove($this->collPDDebates->search($pDDebate));
            if (null === $this->pDDebatesScheduledForDeletion) {
                $this->pDDebatesScheduledForDeletion = clone $this->collPDDebates;
                $this->pDDebatesScheduledForDeletion->clear();
            }
            $this->pDDebatesScheduledForDeletion[]= $pDDebate;
            $pDDebate->setPLCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLCountry', $join_behavior);

        return $this->getPDDebates($query, $con);
    }

    /**
     * Clears out the collPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLCity The current object (for fluent API support)
     * @see        addPDReactions()
     */
    public function clearPDReactions()
    {
        $this->collPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPDReactionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDReactions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDReactions($v = true)
    {
        $this->collPDReactionsPartial = $v;
    }

    /**
     * Initializes the collPDReactions collection.
     *
     * By default this just sets the collPDReactions collection to an empty array (like clearcollPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDReactions($overrideExisting = true)
    {
        if (null !== $this->collPDReactions && !$overrideExisting) {
            return;
        }
        $this->collPDReactions = new PropelObjectCollection();
        $this->collPDReactions->setModel('PDReaction');
    }

    /**
     * Gets an array of PDReaction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLCity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     * @throws PropelException
     */
    public function getPDReactions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDReactionsPartial && !$this->isNew();
        if (null === $this->collPDReactions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDReactions) {
                // return empty collection
                $this->initPDReactions();
            } else {
                $collPDReactions = PDReactionQuery::create(null, $criteria)
                    ->filterByPLCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDReactionsPartial && count($collPDReactions)) {
                      $this->initPDReactions(false);

                      foreach ($collPDReactions as $obj) {
                        if (false == $this->collPDReactions->contains($obj)) {
                          $this->collPDReactions->append($obj);
                        }
                      }

                      $this->collPDReactionsPartial = true;
                    }

                    $collPDReactions->getInternalIterator()->rewind();

                    return $collPDReactions;
                }

                if ($partial && $this->collPDReactions) {
                    foreach ($this->collPDReactions as $obj) {
                        if ($obj->isNew()) {
                            $collPDReactions[] = $obj;
                        }
                    }
                }

                $this->collPDReactions = $collPDReactions;
                $this->collPDReactionsPartial = false;
            }
        }

        return $this->collPDReactions;
    }

    /**
     * Sets a collection of PDReaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLCity The current object (for fluent API support)
     */
    public function setPDReactions(PropelCollection $pDReactions, PropelPDO $con = null)
    {
        $pDReactionsToDelete = $this->getPDReactions(new Criteria(), $con)->diff($pDReactions);


        $this->pDReactionsScheduledForDeletion = $pDReactionsToDelete;

        foreach ($pDReactionsToDelete as $pDReactionRemoved) {
            $pDReactionRemoved->setPLCity(null);
        }

        $this->collPDReactions = null;
        foreach ($pDReactions as $pDReaction) {
            $this->addPDReaction($pDReaction);
        }

        $this->collPDReactions = $pDReactions;
        $this->collPDReactionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDReaction objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDReaction objects.
     * @throws PropelException
     */
    public function countPDReactions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDReactionsPartial && !$this->isNew();
        if (null === $this->collPDReactions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDReactions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDReactions());
            }
            $query = PDReactionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPLCity($this)
                ->count($con);
        }

        return count($this->collPDReactions);
    }

    /**
     * Method called to associate a PDReaction object to this object
     * through the PDReaction foreign key attribute.
     *
     * @param    PDReaction $l PDReaction
     * @return PLCity The current object (for fluent API support)
     */
    public function addPDReaction(PDReaction $l)
    {
        if ($this->collPDReactions === null) {
            $this->initPDReactions();
            $this->collPDReactionsPartial = true;
        }

        if (!in_array($l, $this->collPDReactions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDReaction($l);

            if ($this->pDReactionsScheduledForDeletion and $this->pDReactionsScheduledForDeletion->contains($l)) {
                $this->pDReactionsScheduledForDeletion->remove($this->pDReactionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to add.
     */
    protected function doAddPDReaction($pDReaction)
    {
        $this->collPDReactions[]= $pDReaction;
        $pDReaction->setPLCity($this);
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to remove.
     * @return PLCity The current object (for fluent API support)
     */
    public function removePDReaction($pDReaction)
    {
        if ($this->getPDReactions()->contains($pDReaction)) {
            $this->collPDReactions->remove($this->collPDReactions->search($pDReaction));
            if (null === $this->pDReactionsScheduledForDeletion) {
                $this->pDReactionsScheduledForDeletion = clone $this->collPDReactions;
                $this->pDReactionsScheduledForDeletion->clear();
            }
            $this->pDReactionsScheduledForDeletion[]= $pDReaction;
            $pDReaction->setPLCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDReactions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPDReactions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

        return $this->getPDReactions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

        return $this->getPDReactions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PLCity is new, it will return
     * an empty collection; or if this PLCity has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PLCity.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPLCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLCountry', $join_behavior);

        return $this->getPDReactions($query, $con);
    }

    /**
     * Clears out the collPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLCity The current object (for fluent API support)
     * @see        addPTags()
     */
    public function clearPTags()
    {
        $this->collPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPTags collection.
     *
     * By default this just sets the collPTags collection to an empty collection (like clearPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPTags()
    {
        $this->collPTags = new PropelObjectCollection();
        $this->collPTags->setModel('PTag');
    }

    /**
     * Gets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_t_scope_p_l_c cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLCity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPTags) {
                // return empty collection
                $this->initPTags();
            } else {
                $collPTags = PTagQuery::create(null, $criteria)
                    ->filterByPLCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPTags;
                }
                $this->collPTags = $collPTags;
            }
        }

        return $this->collPTags;
    }

    /**
     * Sets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_t_scope_p_l_c cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLCity The current object (for fluent API support)
     */
    public function setPTags(PropelCollection $pTags, PropelPDO $con = null)
    {
        $this->clearPTags();
        $currentPTags = $this->getPTags(null, $con);

        $this->pTagsScheduledForDeletion = $currentPTags->diff($pTags);

        foreach ($pTags as $pTag) {
            if (!$currentPTags->contains($pTag)) {
                $this->doAddPTag($pTag);
            }
        }

        $this->collPTags = $pTags;

        return $this;
    }

    /**
     * Gets the number of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_t_scope_p_l_c cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PTag objects
     */
    public function countPTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPTags) {
                return 0;
            } else {
                $query = PTagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPLCity($this)
                    ->count($con);
            }
        } else {
            return count($this->collPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_t_scope_p_l_c cross reference table.
     *
     * @param  PTag $pTag The PTScopePLC object to relate
     * @return PLCity The current object (for fluent API support)
     */
    public function addPTag(PTag $pTag)
    {
        if ($this->collPTags === null) {
            $this->initPTags();
        }

        if (!$this->collPTags->contains($pTag)) { // only add it if the **same** object is not already associated
            $this->doAddPTag($pTag);
            $this->collPTags[] = $pTag;

            if ($this->pTagsScheduledForDeletion and $this->pTagsScheduledForDeletion->contains($pTag)) {
                $this->pTagsScheduledForDeletion->remove($this->pTagsScheduledForDeletion->search($pTag));
            }
        }

        return $this;
    }

    /**
     * @param	PTag $pTag The pTag object to add.
     */
    protected function doAddPTag(PTag $pTag)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pTag->getPLCities()->contains($this)) { $pTScopePLC = new PTScopePLC();
            $pTScopePLC->setPTag($pTag);
            $this->addPTScopePLC($pTScopePLC);

            $foreignCollection = $pTag->getPLCities();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PTag object to this object
     * through the p_t_scope_p_l_c cross reference table.
     *
     * @param PTag $pTag The PTScopePLC object to relate
     * @return PLCity The current object (for fluent API support)
     */
    public function removePTag(PTag $pTag)
    {
        if ($this->getPTags()->contains($pTag)) {
            $this->collPTags->remove($this->collPTags->search($pTag));
            if (null === $this->pTagsScheduledForDeletion) {
                $this->pTagsScheduledForDeletion = clone $this->collPTags;
                $this->pTagsScheduledForDeletion->clear();
            }
            $this->pTagsScheduledForDeletion[]= $pTag;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_l_department_id = null;
        $this->name = null;
        $this->name_simple = null;
        $this->name_real = null;
        $this->name_soundex = null;
        $this->name_metaphone = null;
        $this->zipcode = null;
        $this->municipality = null;
        $this->municipality_code = null;
        $this->district = null;
        $this->canton = null;
        $this->amdi = null;
        $this->nb_people_2010 = null;
        $this->nb_people_1999 = null;
        $this->nb_people_2012 = null;
        $this->density_2010 = null;
        $this->surface = null;
        $this->longitude_deg = null;
        $this->latitude_deg = null;
        $this->longitude_grd = null;
        $this->latitude_grd = null;
        $this->longitude_dms = null;
        $this->latitude_dms = null;
        $this->zmin = null;
        $this->zmax = null;
        $this->uuid = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collPTScopePLCs) {
                foreach ($this->collPTScopePLCs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUsers) {
                foreach ($this->collPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDebates) {
                foreach ($this->collPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPTags) {
                foreach ($this->collPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPLDepartment instanceof Persistent) {
              $this->aPLDepartment->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPTScopePLCs instanceof PropelCollection) {
            $this->collPTScopePLCs->clearIterator();
        }
        $this->collPTScopePLCs = null;
        if ($this->collPUsers instanceof PropelCollection) {
            $this->collPUsers->clearIterator();
        }
        $this->collPUsers = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        $this->aPLDepartment = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PLCityPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     PLCity The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PLCityPeer::UPDATED_AT;

        return $this;
    }

    // sluggable behavior

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug()
    {
        return '' . $this->cleanupSlugPart($this->getname()) . '';
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param     string $slug        the text to slugify
     * @param     string $replacement the separator used by slug
     * @return    string               the slugified text
     */
    protected static function cleanupSlugPart($slug, $replacement = '-')
    {
        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/\W+/', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accommodate the column size
     *
     * @param    string $slug                   the slug to check
     * @param    int    $incrementReservedSpace the number of characters to keep empty
     *
     * @return string                            the truncated slug
     */
    protected static function limitSlugSize($slug, $incrementReservedSpace = 3)
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (255 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 255 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param    string $slug            the slug to check
     * @param    string $separator       the separator used by slug
     * @param    int    $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return   string                   the unique slug
     */
    protected function makeSlugUnique($slug, $separator = '-', $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;
        }

         $query = PLCityQuery::create('q')
        ->where('q.Slug ' . ($alreadyExists ? 'REGEXP' : '=') . ' ?', $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        // Already exists
        $object = $query
            ->addDescendingOrderByColumn('LENGTH(slug)')
            ->addDescendingOrderByColumn('slug')
        ->findOne();

        // First duplicate slug
        if (null == $object) {
            return $slug2 . '1';
        }

        $slugNum = substr($object->getSlug(), strlen($slug) + 1);
        if ('0' === $slugNum[0]) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
    }

    // uuid behavior
    /**
    * Create UUID if is NULL Uuid*/
    public function preInsert(PropelPDO $con = NULL) {

        if(is_null($this->getUuid())) {
            $this->setUuid(\Ramsey\Uuid\Uuid::uuid4()->__toString());
        } else {
            $uuid = $this->getUuid();
            if(!\Ramsey\Uuid\Uuid::isValid($uuid)) {
                throw new \InvalidArgumentException('UUID: ' . $uuid . ' in not valid');
                return false;
            }
        }
        return true;
    }
    /**
    * If permanent UUID, throw exception p_l_city.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

}
