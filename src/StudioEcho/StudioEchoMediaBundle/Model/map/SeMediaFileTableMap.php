<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'se_media_file' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.StudioEcho.StudioEchoMediaBundle.Model.map
 */
class SeMediaFileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StudioEcho.StudioEchoMediaBundle.Model.map.SeMediaFileTableMap';

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
        $this->setName('se_media_file');
        $this->setPhpName('SeMediaFile');
        $this->setClassname('StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFile');
        $this->setPackage('src.StudioEcho.StudioEchoMediaBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('category_id', 'CategoryId', 'INTEGER', true, null, 1);
        $this->addColumn('extension', 'Extension', 'VARCHAR', false, 10, null);
        $this->addColumn('type', 'Type', 'VARCHAR', false, 250, null);
        $this->addColumn('mime_type', 'MimeType', 'VARCHAR', false, 250, null);
        $this->addColumn('size', 'Size', 'INTEGER', false, null, null);
        $this->addColumn('height', 'Height', 'INTEGER', false, null, null);
        $this->addColumn('width', 'Width', 'INTEGER', false, null, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SeObjectHasFile', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeObjectHasFile', RelationMap::ONE_TO_MANY, array('id' => 'se_media_file_id', ), 'CASCADE', 'CASCADE', 'SeObjectHasFiles');
        $this->addRelation('SeMediaFileI18n', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFileI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'SeMediaFileI18ns');
        $this->addRelation('SeMediaObject', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaObject', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'SeMediaObjects');
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
            'i18n' =>  array (
  'i18n_table' => '%TABLE%_i18n',
  'i18n_phpname' => '%PHPNAME%I18n',
  'i18n_columns' => 'title, name, description, copyright',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
),
            'online' =>  array (
  'online_column' => 'online',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // SeMediaFileTableMap
