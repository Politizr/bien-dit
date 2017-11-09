<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_c_topic_archive' table.
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
class PCTopicArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PCTopicArchiveTableMap';

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
        $this->setName('p_c_topic_archive');
        $this->setPhpName('PCTopicArchive');
        $this->setClassname('Politizr\\Model\\PCTopicArchive');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addColumn('p_circle_id', 'PCircleId', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('force_geoloc_type', 'ForceGeolocType', 'VARCHAR', false, 100, null);
        $this->addColumn('force_geoloc_id', 'ForceGeolocId', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // PCTopicArchiveTableMap
