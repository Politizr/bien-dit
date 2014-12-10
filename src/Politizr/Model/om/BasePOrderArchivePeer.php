<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\POrderArchive;
use Politizr\Model\POrderArchivePeer;
use Politizr\Model\map\POrderArchiveTableMap;

abstract class BasePOrderArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_order_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\POrderArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\POrderArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 27;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 27;

    /** the column name for the id field */
    const ID = 'p_order_archive.id';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_order_archive.p_user_id';

    /** the column name for the p_o_order_state_id field */
    const P_O_ORDER_STATE_ID = 'p_order_archive.p_o_order_state_id';

    /** the column name for the p_o_payment_state_id field */
    const P_O_PAYMENT_STATE_ID = 'p_order_archive.p_o_payment_state_id';

    /** the column name for the p_o_payment_type_id field */
    const P_O_PAYMENT_TYPE_ID = 'p_order_archive.p_o_payment_type_id';

    /** the column name for the p_o_subscription_id field */
    const P_O_SUBSCRIPTION_ID = 'p_order_archive.p_o_subscription_id';

    /** the column name for the subscription_title field */
    const SUBSCRIPTION_TITLE = 'p_order_archive.subscription_title';

    /** the column name for the subscription_description field */
    const SUBSCRIPTION_DESCRIPTION = 'p_order_archive.subscription_description';

    /** the column name for the subscription_begin_at field */
    const SUBSCRIPTION_BEGIN_AT = 'p_order_archive.subscription_begin_at';

    /** the column name for the subscription_end_at field */
    const SUBSCRIPTION_END_AT = 'p_order_archive.subscription_end_at';

    /** the column name for the information field */
    const INFORMATION = 'p_order_archive.information';

    /** the column name for the price field */
    const PRICE = 'p_order_archive.price';

    /** the column name for the promotion field */
    const PROMOTION = 'p_order_archive.promotion';

    /** the column name for the total field */
    const TOTAL = 'p_order_archive.total';

    /** the column name for the gender field */
    const GENDER = 'p_order_archive.gender';

    /** the column name for the name field */
    const NAME = 'p_order_archive.name';

    /** the column name for the firstname field */
    const FIRSTNAME = 'p_order_archive.firstname';

    /** the column name for the phone field */
    const PHONE = 'p_order_archive.phone';

    /** the column name for the email field */
    const EMAIL = 'p_order_archive.email';

    /** the column name for the invoice_ref field */
    const INVOICE_REF = 'p_order_archive.invoice_ref';

    /** the column name for the invoice_at field */
    const INVOICE_AT = 'p_order_archive.invoice_at';

    /** the column name for the invoice_filename field */
    const INVOICE_FILENAME = 'p_order_archive.invoice_filename';

    /** the column name for the supporting_document field */
    const SUPPORTING_DOCUMENT = 'p_order_archive.supporting_document';

    /** the column name for the elective_mandates field */
    const ELECTIVE_MANDATES = 'p_order_archive.elective_mandates';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_order_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_order_archive.updated_at';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_order_archive.archived_at';

    /** The enumerated values for the gender field */
    const GENDER_MADAME = 'Madame';
    const GENDER_MONSIEUR = 'Monsieur';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of POrderArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array POrderArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. POrderArchivePeer::$fieldNames[POrderArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PUserId', 'POOrderStateId', 'POPaymentStateId', 'POPaymentTypeId', 'POSubscriptionId', 'SubscriptionTitle', 'SubscriptionDescription', 'SubscriptionBeginAt', 'SubscriptionEndAt', 'Information', 'Price', 'Promotion', 'Total', 'Gender', 'Name', 'Firstname', 'Phone', 'Email', 'InvoiceRef', 'InvoiceAt', 'InvoiceFilename', 'SupportingDocument', 'ElectiveMandates', 'CreatedAt', 'UpdatedAt', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pUserId', 'pOOrderStateId', 'pOPaymentStateId', 'pOPaymentTypeId', 'pOSubscriptionId', 'subscriptionTitle', 'subscriptionDescription', 'subscriptionBeginAt', 'subscriptionEndAt', 'information', 'price', 'promotion', 'total', 'gender', 'name', 'firstname', 'phone', 'email', 'invoiceRef', 'invoiceAt', 'invoiceFilename', 'supportingDocument', 'electiveMandates', 'createdAt', 'updatedAt', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (POrderArchivePeer::ID, POrderArchivePeer::P_USER_ID, POrderArchivePeer::P_O_ORDER_STATE_ID, POrderArchivePeer::P_O_PAYMENT_STATE_ID, POrderArchivePeer::P_O_PAYMENT_TYPE_ID, POrderArchivePeer::P_O_SUBSCRIPTION_ID, POrderArchivePeer::SUBSCRIPTION_TITLE, POrderArchivePeer::SUBSCRIPTION_DESCRIPTION, POrderArchivePeer::SUBSCRIPTION_BEGIN_AT, POrderArchivePeer::SUBSCRIPTION_END_AT, POrderArchivePeer::INFORMATION, POrderArchivePeer::PRICE, POrderArchivePeer::PROMOTION, POrderArchivePeer::TOTAL, POrderArchivePeer::GENDER, POrderArchivePeer::NAME, POrderArchivePeer::FIRSTNAME, POrderArchivePeer::PHONE, POrderArchivePeer::EMAIL, POrderArchivePeer::INVOICE_REF, POrderArchivePeer::INVOICE_AT, POrderArchivePeer::INVOICE_FILENAME, POrderArchivePeer::SUPPORTING_DOCUMENT, POrderArchivePeer::ELECTIVE_MANDATES, POrderArchivePeer::CREATED_AT, POrderArchivePeer::UPDATED_AT, POrderArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'P_USER_ID', 'P_O_ORDER_STATE_ID', 'P_O_PAYMENT_STATE_ID', 'P_O_PAYMENT_TYPE_ID', 'P_O_SUBSCRIPTION_ID', 'SUBSCRIPTION_TITLE', 'SUBSCRIPTION_DESCRIPTION', 'SUBSCRIPTION_BEGIN_AT', 'SUBSCRIPTION_END_AT', 'INFORMATION', 'PRICE', 'PROMOTION', 'TOTAL', 'GENDER', 'NAME', 'FIRSTNAME', 'PHONE', 'EMAIL', 'INVOICE_REF', 'INVOICE_AT', 'INVOICE_FILENAME', 'SUPPORTING_DOCUMENT', 'ELECTIVE_MANDATES', 'CREATED_AT', 'UPDATED_AT', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'p_user_id', 'p_o_order_state_id', 'p_o_payment_state_id', 'p_o_payment_type_id', 'p_o_subscription_id', 'subscription_title', 'subscription_description', 'subscription_begin_at', 'subscription_end_at', 'information', 'price', 'promotion', 'total', 'gender', 'name', 'firstname', 'phone', 'email', 'invoice_ref', 'invoice_at', 'invoice_filename', 'supporting_document', 'elective_mandates', 'created_at', 'updated_at', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. POrderArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PUserId' => 1, 'POOrderStateId' => 2, 'POPaymentStateId' => 3, 'POPaymentTypeId' => 4, 'POSubscriptionId' => 5, 'SubscriptionTitle' => 6, 'SubscriptionDescription' => 7, 'SubscriptionBeginAt' => 8, 'SubscriptionEndAt' => 9, 'Information' => 10, 'Price' => 11, 'Promotion' => 12, 'Total' => 13, 'Gender' => 14, 'Name' => 15, 'Firstname' => 16, 'Phone' => 17, 'Email' => 18, 'InvoiceRef' => 19, 'InvoiceAt' => 20, 'InvoiceFilename' => 21, 'SupportingDocument' => 22, 'ElectiveMandates' => 23, 'CreatedAt' => 24, 'UpdatedAt' => 25, 'ArchivedAt' => 26, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pUserId' => 1, 'pOOrderStateId' => 2, 'pOPaymentStateId' => 3, 'pOPaymentTypeId' => 4, 'pOSubscriptionId' => 5, 'subscriptionTitle' => 6, 'subscriptionDescription' => 7, 'subscriptionBeginAt' => 8, 'subscriptionEndAt' => 9, 'information' => 10, 'price' => 11, 'promotion' => 12, 'total' => 13, 'gender' => 14, 'name' => 15, 'firstname' => 16, 'phone' => 17, 'email' => 18, 'invoiceRef' => 19, 'invoiceAt' => 20, 'invoiceFilename' => 21, 'supportingDocument' => 22, 'electiveMandates' => 23, 'createdAt' => 24, 'updatedAt' => 25, 'archivedAt' => 26, ),
        BasePeer::TYPE_COLNAME => array (POrderArchivePeer::ID => 0, POrderArchivePeer::P_USER_ID => 1, POrderArchivePeer::P_O_ORDER_STATE_ID => 2, POrderArchivePeer::P_O_PAYMENT_STATE_ID => 3, POrderArchivePeer::P_O_PAYMENT_TYPE_ID => 4, POrderArchivePeer::P_O_SUBSCRIPTION_ID => 5, POrderArchivePeer::SUBSCRIPTION_TITLE => 6, POrderArchivePeer::SUBSCRIPTION_DESCRIPTION => 7, POrderArchivePeer::SUBSCRIPTION_BEGIN_AT => 8, POrderArchivePeer::SUBSCRIPTION_END_AT => 9, POrderArchivePeer::INFORMATION => 10, POrderArchivePeer::PRICE => 11, POrderArchivePeer::PROMOTION => 12, POrderArchivePeer::TOTAL => 13, POrderArchivePeer::GENDER => 14, POrderArchivePeer::NAME => 15, POrderArchivePeer::FIRSTNAME => 16, POrderArchivePeer::PHONE => 17, POrderArchivePeer::EMAIL => 18, POrderArchivePeer::INVOICE_REF => 19, POrderArchivePeer::INVOICE_AT => 20, POrderArchivePeer::INVOICE_FILENAME => 21, POrderArchivePeer::SUPPORTING_DOCUMENT => 22, POrderArchivePeer::ELECTIVE_MANDATES => 23, POrderArchivePeer::CREATED_AT => 24, POrderArchivePeer::UPDATED_AT => 25, POrderArchivePeer::ARCHIVED_AT => 26, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'P_USER_ID' => 1, 'P_O_ORDER_STATE_ID' => 2, 'P_O_PAYMENT_STATE_ID' => 3, 'P_O_PAYMENT_TYPE_ID' => 4, 'P_O_SUBSCRIPTION_ID' => 5, 'SUBSCRIPTION_TITLE' => 6, 'SUBSCRIPTION_DESCRIPTION' => 7, 'SUBSCRIPTION_BEGIN_AT' => 8, 'SUBSCRIPTION_END_AT' => 9, 'INFORMATION' => 10, 'PRICE' => 11, 'PROMOTION' => 12, 'TOTAL' => 13, 'GENDER' => 14, 'NAME' => 15, 'FIRSTNAME' => 16, 'PHONE' => 17, 'EMAIL' => 18, 'INVOICE_REF' => 19, 'INVOICE_AT' => 20, 'INVOICE_FILENAME' => 21, 'SUPPORTING_DOCUMENT' => 22, 'ELECTIVE_MANDATES' => 23, 'CREATED_AT' => 24, 'UPDATED_AT' => 25, 'ARCHIVED_AT' => 26, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'p_user_id' => 1, 'p_o_order_state_id' => 2, 'p_o_payment_state_id' => 3, 'p_o_payment_type_id' => 4, 'p_o_subscription_id' => 5, 'subscription_title' => 6, 'subscription_description' => 7, 'subscription_begin_at' => 8, 'subscription_end_at' => 9, 'information' => 10, 'price' => 11, 'promotion' => 12, 'total' => 13, 'gender' => 14, 'name' => 15, 'firstname' => 16, 'phone' => 17, 'email' => 18, 'invoice_ref' => 19, 'invoice_at' => 20, 'invoice_filename' => 21, 'supporting_document' => 22, 'elective_mandates' => 23, 'created_at' => 24, 'updated_at' => 25, 'archived_at' => 26, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        POrderArchivePeer::GENDER => array(
            POrderArchivePeer::GENDER_MADAME,
            POrderArchivePeer::GENDER_MONSIEUR,
        ),
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = POrderArchivePeer::getFieldNames($toType);
        $key = isset(POrderArchivePeer::$fieldKeys[$fromType][$name]) ? POrderArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(POrderArchivePeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, POrderArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return POrderArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return POrderArchivePeer::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = POrderArchivePeer::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new PropelException(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = POrderArchivePeer::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. POrderArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(POrderArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(POrderArchivePeer::ID);
            $criteria->addSelectColumn(POrderArchivePeer::P_USER_ID);
            $criteria->addSelectColumn(POrderArchivePeer::P_O_ORDER_STATE_ID);
            $criteria->addSelectColumn(POrderArchivePeer::P_O_PAYMENT_STATE_ID);
            $criteria->addSelectColumn(POrderArchivePeer::P_O_PAYMENT_TYPE_ID);
            $criteria->addSelectColumn(POrderArchivePeer::P_O_SUBSCRIPTION_ID);
            $criteria->addSelectColumn(POrderArchivePeer::SUBSCRIPTION_TITLE);
            $criteria->addSelectColumn(POrderArchivePeer::SUBSCRIPTION_DESCRIPTION);
            $criteria->addSelectColumn(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT);
            $criteria->addSelectColumn(POrderArchivePeer::SUBSCRIPTION_END_AT);
            $criteria->addSelectColumn(POrderArchivePeer::INFORMATION);
            $criteria->addSelectColumn(POrderArchivePeer::PRICE);
            $criteria->addSelectColumn(POrderArchivePeer::PROMOTION);
            $criteria->addSelectColumn(POrderArchivePeer::TOTAL);
            $criteria->addSelectColumn(POrderArchivePeer::GENDER);
            $criteria->addSelectColumn(POrderArchivePeer::NAME);
            $criteria->addSelectColumn(POrderArchivePeer::FIRSTNAME);
            $criteria->addSelectColumn(POrderArchivePeer::PHONE);
            $criteria->addSelectColumn(POrderArchivePeer::EMAIL);
            $criteria->addSelectColumn(POrderArchivePeer::INVOICE_REF);
            $criteria->addSelectColumn(POrderArchivePeer::INVOICE_AT);
            $criteria->addSelectColumn(POrderArchivePeer::INVOICE_FILENAME);
            $criteria->addSelectColumn(POrderArchivePeer::SUPPORTING_DOCUMENT);
            $criteria->addSelectColumn(POrderArchivePeer::ELECTIVE_MANDATES);
            $criteria->addSelectColumn(POrderArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(POrderArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(POrderArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.p_user_id');
            $criteria->addSelectColumn($alias . '.p_o_order_state_id');
            $criteria->addSelectColumn($alias . '.p_o_payment_state_id');
            $criteria->addSelectColumn($alias . '.p_o_payment_type_id');
            $criteria->addSelectColumn($alias . '.p_o_subscription_id');
            $criteria->addSelectColumn($alias . '.subscription_title');
            $criteria->addSelectColumn($alias . '.subscription_description');
            $criteria->addSelectColumn($alias . '.subscription_begin_at');
            $criteria->addSelectColumn($alias . '.subscription_end_at');
            $criteria->addSelectColumn($alias . '.information');
            $criteria->addSelectColumn($alias . '.price');
            $criteria->addSelectColumn($alias . '.promotion');
            $criteria->addSelectColumn($alias . '.total');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.firstname');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.invoice_ref');
            $criteria->addSelectColumn($alias . '.invoice_at');
            $criteria->addSelectColumn($alias . '.invoice_filename');
            $criteria->addSelectColumn($alias . '.supporting_document');
            $criteria->addSelectColumn($alias . '.elective_mandates');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.archived_at');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(POrderArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return POrderArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = POrderArchivePeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return POrderArchivePeer::populateObjects(POrderArchivePeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            POrderArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(POrderArchivePeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param POrderArchive $obj A POrderArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            POrderArchivePeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A POrderArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof POrderArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or POrderArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(POrderArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return POrderArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(POrderArchivePeer::$instances[$key])) {
                return POrderArchivePeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (POrderArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        POrderArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_order_archive
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = POrderArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = POrderArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = POrderArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                POrderArchivePeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (POrderArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = POrderArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = POrderArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + POrderArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = POrderArchivePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            POrderArchivePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Gender ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getGenderSqlValue($enumVal)
    {
        return POrderArchivePeer::getSqlValueForEnum(POrderArchivePeer::GENDER, $enumVal);
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(POrderArchivePeer::DATABASE_NAME)->getTable(POrderArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePOrderArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePOrderArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\POrderArchiveTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return POrderArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a POrderArchive or Criteria object.
     *
     * @param      mixed $values Criteria or POrderArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from POrderArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(POrderArchivePeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a POrderArchive or Criteria object.
     *
     * @param      mixed $values Criteria or POrderArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(POrderArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(POrderArchivePeer::ID);
            $value = $criteria->remove(POrderArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(POrderArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(POrderArchivePeer::TABLE_NAME);
            }

        } else { // $values is POrderArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(POrderArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_order_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(POrderArchivePeer::TABLE_NAME, $con, POrderArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            POrderArchivePeer::clearInstancePool();
            POrderArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a POrderArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or POrderArchive object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            POrderArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof POrderArchive) { // it's a model object
            // invalidate the cache for this single object
            POrderArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(POrderArchivePeer::DATABASE_NAME);
            $criteria->add(POrderArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                POrderArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(POrderArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            POrderArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given POrderArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param POrderArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(POrderArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(POrderArchivePeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(POrderArchivePeer::DATABASE_NAME, POrderArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return POrderArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = POrderArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(POrderArchivePeer::DATABASE_NAME);
        $criteria->add(POrderArchivePeer::ID, $pk);

        $v = POrderArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return POrderArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(POrderArchivePeer::DATABASE_NAME);
            $criteria->add(POrderArchivePeer::ID, $pks, Criteria::IN);
            $objs = POrderArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePOrderArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePOrderArchivePeer::buildTableMap();

