<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_m_app_exception' table.
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
class PMAppExceptionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PMAppExceptionTableMap';

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
        $this->setName('p_m_app_exception');
        $this->setPhpName('PMAppException');
        $this->setClassname('Politizr\\Model\\PMAppException');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('file', 'File', 'VARCHAR', false, 250, null);
        $this->addColumn('line', 'Line', 'INTEGER', false, null, null);
        $this->addColumn('code', 'Code', 'INTEGER', false, null, null);
        $this->addColumn('message', 'Message', 'VARCHAR', false, 1500, null);
        $this->addColumn('stack_trace', 'StackTrace', 'CLOB', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // PMAppExceptionTableMap
