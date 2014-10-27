<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_document_archive' table.
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
class PDocumentArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDocumentArchiveTableMap';

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
        $this->setName('p_document_archive');
        $this->setPhpName('PDocumentArchive');
        $this->setClassname('Politizr\\Model\\PDocumentArchive');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('p_user_id', 'PUserId', 'INTEGER', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('more_info', 'MoreInfo', 'LONGVARCHAR', false, null, null);
        $this->addColumn('note_pos', 'NotePos', 'INTEGER', false, null, null);
        $this->addColumn('note_neg', 'NoteNeg', 'INTEGER', false, null, null);
        $this->addColumn('nb_views', 'NbViews', 'INTEGER', false, null, null);
        $this->addColumn('published', 'Published', 'BOOLEAN', false, 1, null);
        $this->addColumn('published_at', 'PublishedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('published_by', 'PublishedBy', 'VARCHAR', false, 300, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('broadcast', 'Broadcast', 'BOOLEAN', false, 1, null);
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // PDocumentArchiveTableMap