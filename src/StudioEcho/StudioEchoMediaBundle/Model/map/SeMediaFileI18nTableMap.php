<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'se_media_file_i18n' table.
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
class SeMediaFileI18nTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StudioEcho.StudioEchoMediaBundle.Model.map.SeMediaFileI18nTableMap';

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
        $this->setName('se_media_file_i18n');
        $this->setPhpName('SeMediaFileI18n');
        $this->setClassname('StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFileI18n');
        $this->setPackage('src.StudioEcho.StudioEchoMediaBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'se_media_file', 'id', true, null, null);
        $this->addPrimaryKey('locale', 'Locale', 'VARCHAR', true, 5, 'en_US');
        $this->addColumn('title', 'Title', 'VARCHAR', false, 250, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 250, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('copyright', 'Copyright', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SeMediaFile', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFile', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // SeMediaFileI18nTableMap
