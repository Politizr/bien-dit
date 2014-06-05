<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_tag' table.
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
class PTagTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PTagTableMap';

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
        $this->setName('p_tag');
        $this->setPhpName('PTag');
        $this->setClassname('Politizr\\Model\\PTag');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_t_tag_type_id', 'PTTagTypeId', 'INTEGER', 'p_t_tag_type', 'id', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 150, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
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
        $this->addRelation('PTTagType', 'Politizr\\Model\\PTTagType', RelationMap::MANY_TO_ONE, array('p_t_tag_type_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PuTaggedTPTag', 'Politizr\\Model\\PUTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PuTaggedTPTags');
        $this->addRelation('PuFollowTPTag', 'Politizr\\Model\\PUFollowT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PuFollowTPTags');
        $this->addRelation('PddTaggedTPTag', 'Politizr\\Model\\PDDTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PddTaggedTPTags');
        $this->addRelation('PuTaggedTPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTaggedTPUsers');
        $this->addRelation('PuFollowTPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowTPUsers');
        $this->addRelation('PddTaggedTPDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PddTaggedTPDDebates');
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
            'online' =>  array (
  'online_column' => 'online',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'slug',
  'slug_pattern' => '{title}',
  'replace_pattern' => '/\\W+/',
  'replacement' => '-',
  'separator' => '-',
  'permanent' => 'false',
  'scope_column' => '',
),
        );
    } // getBehaviors()

} // PTagTableMap
