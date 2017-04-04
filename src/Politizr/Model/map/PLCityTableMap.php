<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_l_city' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Politizr.Model.map
 */
class PLCityTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PLCityTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('p_l_city');
        $this->setPhpName('PLCity');
        $this->setClassname('Politizr\\Model\\PLCity');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_l_department_id', 'PLDepartmentId', 'INTEGER', 'p_l_department', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 150, null);
        $this->addColumn('name_simple', 'NameSimple', 'VARCHAR', false, 150, null);
        $this->addColumn('name_real', 'NameReal', 'VARCHAR', false, 150, null);
        $this->addColumn('name_soundex', 'NameSoundex', 'VARCHAR', false, 150, null);
        $this->addColumn('name_metaphone', 'NameMetaphone', 'VARCHAR', false, 150, null);
        $this->addColumn('zipcode', 'Zipcode', 'VARCHAR', false, 150, null);
        $this->addColumn('municipality', 'Municipality', 'VARCHAR', false, 150, null);
        $this->addColumn('municipality_code', 'MunicipalityCode', 'VARCHAR', false, 10, null);
        $this->addColumn('district', 'District', 'INTEGER', false, null, null);
        $this->addColumn('canton', 'Canton', 'VARCHAR', false, 10, null);
        $this->addColumn('amdi', 'Amdi', 'INTEGER', false, null, null);
        $this->addColumn('nb_people_2010', 'NbPeople2010', 'INTEGER', false, null, null);
        $this->addColumn('nb_people_1999', 'NbPeople1999', 'INTEGER', false, null, null);
        $this->addColumn('nb_people_2012', 'NbPeople2012', 'INTEGER', false, null, null);
        $this->addColumn('density_2010', 'Density2010', 'INTEGER', false, null, null);
        $this->addColumn('surface', 'Surface', 'FLOAT', false, null, null);
        $this->addColumn('longitude_deg', 'LongitudeDeg', 'FLOAT', false, null, null);
        $this->addColumn('latitude_deg', 'LatitudeDeg', 'FLOAT', false, null, null);
        $this->addColumn('longitude_grd', 'LongitudeGrd', 'VARCHAR', false, 10, null);
        $this->addColumn('latitude_grd', 'LatitudeGrd', 'VARCHAR', false, 10, null);
        $this->addColumn('longitude_dms', 'LongitudeDms', 'VARCHAR', false, 10, null);
        $this->addColumn('latitude_dms', 'LatitudeDms', 'VARCHAR', false, 10, null);
        $this->addColumn('zmin', 'Zmin', 'INTEGER', false, null, null);
        $this->addColumn('zmax', 'Zmax', 'INTEGER', false, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PLDepartment', 'Politizr\\Model\\PLDepartment', RelationMap::MANY_TO_ONE, array('p_l_department_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PEOScopePLC', 'Politizr\\Model\\PEOScopePLC', RelationMap::ONE_TO_MANY, array('id' => 'p_l_city_id', ), 'CASCADE', 'CASCADE', 'PEOScopePLCs');
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::ONE_TO_MANY, array('id' => 'p_l_city_id', ), 'SET NULL', 'CASCADE', 'PUsers');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::ONE_TO_MANY, array('id' => 'p_l_city_id', ), 'SET NULL', 'CASCADE', 'PDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_l_city_id', ), 'SET NULL', 'CASCADE', 'PDReactions');
        $this->addRelation('PEOperation', 'Politizr\\Model\\PEOperation', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PEOperations');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'query_cache' =>  array (
  'backend' => 'apc',
  'lifetime' => 3600,
),
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'slug',
  'slug_pattern' => '{name}',
  'replace_pattern' => '/\\W+/',
  'replacement' => '-',
  'separator' => '-',
  'permanent' => 'false',
  'scope_column' => '',
),
            'uuid' =>  array (
  'name' => 'uuid',
  'version' => '4',
  'permanent' => 'false',
  'required' => 'true',
  'unique' => 'true',
),
        );
    } // getBehaviors()

} // PLCityTableMap
