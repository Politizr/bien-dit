<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\POEmailPeer;
use Politizr\Model\POOrderStatePeer;
use Politizr\Model\POPaymentStatePeer;
use Politizr\Model\POPaymentTypePeer;
use Politizr\Model\POSubscriptionPeer;
use Politizr\Model\POrder;
use Politizr\Model\POrderPeer;
use Politizr\Model\PUserPeer;
use Politizr\Model\map\POrderTableMap;

abstract class BasePOrderPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_order';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\POrder';

    /** the related TableMap class for this table */
    const TM_CLASS = 'POrderTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 26;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 26;

    /** the column name for the id field */
    const ID = 'p_order.id';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_order.p_user_id';

    /** the column name for the p_o_order_state_id field */
    const P_O_ORDER_STATE_ID = 'p_order.p_o_order_state_id';

    /** the column name for the p_o_payment_state_id field */
    const P_O_PAYMENT_STATE_ID = 'p_order.p_o_payment_state_id';

    /** the column name for the p_o_payment_type_id field */
    const P_O_PAYMENT_TYPE_ID = 'p_order.p_o_payment_type_id';

    /** the column name for the p_o_subscription_id field */
    const P_O_SUBSCRIPTION_ID = 'p_order.p_o_subscription_id';

    /** the column name for the subscription_title field */
    const SUBSCRIPTION_TITLE = 'p_order.subscription_title';

    /** the column name for the subscription_description field */
    const SUBSCRIPTION_DESCRIPTION = 'p_order.subscription_description';

    /** the column name for the subscription_begin_at field */
    const SUBSCRIPTION_BEGIN_AT = 'p_order.subscription_begin_at';

    /** the column name for the subscription_end_at field */
    const SUBSCRIPTION_END_AT = 'p_order.subscription_end_at';

    /** the column name for the information field */
    const INFORMATION = 'p_order.information';

    /** the column name for the price field */
    const PRICE = 'p_order.price';

    /** the column name for the promotion field */
    const PROMOTION = 'p_order.promotion';

    /** the column name for the total field */
    const TOTAL = 'p_order.total';

    /** the column name for the gender field */
    const GENDER = 'p_order.gender';

    /** the column name for the name field */
    const NAME = 'p_order.name';

    /** the column name for the firstname field */
    const FIRSTNAME = 'p_order.firstname';

    /** the column name for the phone field */
    const PHONE = 'p_order.phone';

    /** the column name for the email field */
    const EMAIL = 'p_order.email';

    /** the column name for the invoice_ref field */
    const INVOICE_REF = 'p_order.invoice_ref';

    /** the column name for the invoice_at field */
    const INVOICE_AT = 'p_order.invoice_at';

    /** the column name for the invoice_filename field */
    const INVOICE_FILENAME = 'p_order.invoice_filename';

    /** the column name for the supporting_document field */
    const SUPPORTING_DOCUMENT = 'p_order.supporting_document';

    /** the column name for the elective_mandates field */
    const ELECTIVE_MANDATES = 'p_order.elective_mandates';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_order.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_order.updated_at';

    /** The enumerated values for the gender field */
    const GENDER_MADAME = 'Madame';
    const GENDER_MONSIEUR = 'Monsieur';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of POrder objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array POrder[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. POrderPeer::$fieldNames[POrderPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PUserId', 'POOrderStateId', 'POPaymentStateId', 'POPaymentTypeId', 'POSubscriptionId', 'SubscriptionTitle', 'SubscriptionDescription', 'SubscriptionBeginAt', 'SubscriptionEndAt', 'Information', 'Price', 'Promotion', 'Total', 'Gender', 'Name', 'Firstname', 'Phone', 'Email', 'InvoiceRef', 'InvoiceAt', 'InvoiceFilename', 'SupportingDocument', 'ElectiveMandates', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pUserId', 'pOOrderStateId', 'pOPaymentStateId', 'pOPaymentTypeId', 'pOSubscriptionId', 'subscriptionTitle', 'subscriptionDescription', 'subscriptionBeginAt', 'subscriptionEndAt', 'information', 'price', 'promotion', 'total', 'gender', 'name', 'firstname', 'phone', 'email', 'invoiceRef', 'invoiceAt', 'invoiceFilename', 'supportingDocument', 'electiveMandates', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (POrderPeer::ID, POrderPeer::P_USER_ID, POrderPeer::P_O_ORDER_STATE_ID, POrderPeer::P_O_PAYMENT_STATE_ID, POrderPeer::P_O_PAYMENT_TYPE_ID, POrderPeer::P_O_SUBSCRIPTION_ID, POrderPeer::SUBSCRIPTION_TITLE, POrderPeer::SUBSCRIPTION_DESCRIPTION, POrderPeer::SUBSCRIPTION_BEGIN_AT, POrderPeer::SUBSCRIPTION_END_AT, POrderPeer::INFORMATION, POrderPeer::PRICE, POrderPeer::PROMOTION, POrderPeer::TOTAL, POrderPeer::GENDER, POrderPeer::NAME, POrderPeer::FIRSTNAME, POrderPeer::PHONE, POrderPeer::EMAIL, POrderPeer::INVOICE_REF, POrderPeer::INVOICE_AT, POrderPeer::INVOICE_FILENAME, POrderPeer::SUPPORTING_DOCUMENT, POrderPeer::ELECTIVE_MANDATES, POrderPeer::CREATED_AT, POrderPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'P_USER_ID', 'P_O_ORDER_STATE_ID', 'P_O_PAYMENT_STATE_ID', 'P_O_PAYMENT_TYPE_ID', 'P_O_SUBSCRIPTION_ID', 'SUBSCRIPTION_TITLE', 'SUBSCRIPTION_DESCRIPTION', 'SUBSCRIPTION_BEGIN_AT', 'SUBSCRIPTION_END_AT', 'INFORMATION', 'PRICE', 'PROMOTION', 'TOTAL', 'GENDER', 'NAME', 'FIRSTNAME', 'PHONE', 'EMAIL', 'INVOICE_REF', 'INVOICE_AT', 'INVOICE_FILENAME', 'SUPPORTING_DOCUMENT', 'ELECTIVE_MANDATES', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'p_user_id', 'p_o_order_state_id', 'p_o_payment_state_id', 'p_o_payment_type_id', 'p_o_subscription_id', 'subscription_title', 'subscription_description', 'subscription_begin_at', 'subscription_end_at', 'information', 'price', 'promotion', 'total', 'gender', 'name', 'firstname', 'phone', 'email', 'invoice_ref', 'invoice_at', 'invoice_filename', 'supporting_document', 'elective_mandates', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. POrderPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PUserId' => 1, 'POOrderStateId' => 2, 'POPaymentStateId' => 3, 'POPaymentTypeId' => 4, 'POSubscriptionId' => 5, 'SubscriptionTitle' => 6, 'SubscriptionDescription' => 7, 'SubscriptionBeginAt' => 8, 'SubscriptionEndAt' => 9, 'Information' => 10, 'Price' => 11, 'Promotion' => 12, 'Total' => 13, 'Gender' => 14, 'Name' => 15, 'Firstname' => 16, 'Phone' => 17, 'Email' => 18, 'InvoiceRef' => 19, 'InvoiceAt' => 20, 'InvoiceFilename' => 21, 'SupportingDocument' => 22, 'ElectiveMandates' => 23, 'CreatedAt' => 24, 'UpdatedAt' => 25, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pUserId' => 1, 'pOOrderStateId' => 2, 'pOPaymentStateId' => 3, 'pOPaymentTypeId' => 4, 'pOSubscriptionId' => 5, 'subscriptionTitle' => 6, 'subscriptionDescription' => 7, 'subscriptionBeginAt' => 8, 'subscriptionEndAt' => 9, 'information' => 10, 'price' => 11, 'promotion' => 12, 'total' => 13, 'gender' => 14, 'name' => 15, 'firstname' => 16, 'phone' => 17, 'email' => 18, 'invoiceRef' => 19, 'invoiceAt' => 20, 'invoiceFilename' => 21, 'supportingDocument' => 22, 'electiveMandates' => 23, 'createdAt' => 24, 'updatedAt' => 25, ),
        BasePeer::TYPE_COLNAME => array (POrderPeer::ID => 0, POrderPeer::P_USER_ID => 1, POrderPeer::P_O_ORDER_STATE_ID => 2, POrderPeer::P_O_PAYMENT_STATE_ID => 3, POrderPeer::P_O_PAYMENT_TYPE_ID => 4, POrderPeer::P_O_SUBSCRIPTION_ID => 5, POrderPeer::SUBSCRIPTION_TITLE => 6, POrderPeer::SUBSCRIPTION_DESCRIPTION => 7, POrderPeer::SUBSCRIPTION_BEGIN_AT => 8, POrderPeer::SUBSCRIPTION_END_AT => 9, POrderPeer::INFORMATION => 10, POrderPeer::PRICE => 11, POrderPeer::PROMOTION => 12, POrderPeer::TOTAL => 13, POrderPeer::GENDER => 14, POrderPeer::NAME => 15, POrderPeer::FIRSTNAME => 16, POrderPeer::PHONE => 17, POrderPeer::EMAIL => 18, POrderPeer::INVOICE_REF => 19, POrderPeer::INVOICE_AT => 20, POrderPeer::INVOICE_FILENAME => 21, POrderPeer::SUPPORTING_DOCUMENT => 22, POrderPeer::ELECTIVE_MANDATES => 23, POrderPeer::CREATED_AT => 24, POrderPeer::UPDATED_AT => 25, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'P_USER_ID' => 1, 'P_O_ORDER_STATE_ID' => 2, 'P_O_PAYMENT_STATE_ID' => 3, 'P_O_PAYMENT_TYPE_ID' => 4, 'P_O_SUBSCRIPTION_ID' => 5, 'SUBSCRIPTION_TITLE' => 6, 'SUBSCRIPTION_DESCRIPTION' => 7, 'SUBSCRIPTION_BEGIN_AT' => 8, 'SUBSCRIPTION_END_AT' => 9, 'INFORMATION' => 10, 'PRICE' => 11, 'PROMOTION' => 12, 'TOTAL' => 13, 'GENDER' => 14, 'NAME' => 15, 'FIRSTNAME' => 16, 'PHONE' => 17, 'EMAIL' => 18, 'INVOICE_REF' => 19, 'INVOICE_AT' => 20, 'INVOICE_FILENAME' => 21, 'SUPPORTING_DOCUMENT' => 22, 'ELECTIVE_MANDATES' => 23, 'CREATED_AT' => 24, 'UPDATED_AT' => 25, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'p_user_id' => 1, 'p_o_order_state_id' => 2, 'p_o_payment_state_id' => 3, 'p_o_payment_type_id' => 4, 'p_o_subscription_id' => 5, 'subscription_title' => 6, 'subscription_description' => 7, 'subscription_begin_at' => 8, 'subscription_end_at' => 9, 'information' => 10, 'price' => 11, 'promotion' => 12, 'total' => 13, 'gender' => 14, 'name' => 15, 'firstname' => 16, 'phone' => 17, 'email' => 18, 'invoice_ref' => 19, 'invoice_at' => 20, 'invoice_filename' => 21, 'supporting_document' => 22, 'elective_mandates' => 23, 'created_at' => 24, 'updated_at' => 25, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        POrderPeer::GENDER => array(
            POrderPeer::GENDER_MADAME,
            POrderPeer::GENDER_MONSIEUR,
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
        $toNames = POrderPeer::getFieldNames($toType);
        $key = isset(POrderPeer::$fieldKeys[$fromType][$name]) ? POrderPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(POrderPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, POrderPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return POrderPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return POrderPeer::$enumValueSets;
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
        $valueSets = POrderPeer::getValueSets();

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
     * @return int            SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = POrderPeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. POrderPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(POrderPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(POrderPeer::ID);
            $criteria->addSelectColumn(POrderPeer::P_USER_ID);
            $criteria->addSelectColumn(POrderPeer::P_O_ORDER_STATE_ID);
            $criteria->addSelectColumn(POrderPeer::P_O_PAYMENT_STATE_ID);
            $criteria->addSelectColumn(POrderPeer::P_O_PAYMENT_TYPE_ID);
            $criteria->addSelectColumn(POrderPeer::P_O_SUBSCRIPTION_ID);
            $criteria->addSelectColumn(POrderPeer::SUBSCRIPTION_TITLE);
            $criteria->addSelectColumn(POrderPeer::SUBSCRIPTION_DESCRIPTION);
            $criteria->addSelectColumn(POrderPeer::SUBSCRIPTION_BEGIN_AT);
            $criteria->addSelectColumn(POrderPeer::SUBSCRIPTION_END_AT);
            $criteria->addSelectColumn(POrderPeer::INFORMATION);
            $criteria->addSelectColumn(POrderPeer::PRICE);
            $criteria->addSelectColumn(POrderPeer::PROMOTION);
            $criteria->addSelectColumn(POrderPeer::TOTAL);
            $criteria->addSelectColumn(POrderPeer::GENDER);
            $criteria->addSelectColumn(POrderPeer::NAME);
            $criteria->addSelectColumn(POrderPeer::FIRSTNAME);
            $criteria->addSelectColumn(POrderPeer::PHONE);
            $criteria->addSelectColumn(POrderPeer::EMAIL);
            $criteria->addSelectColumn(POrderPeer::INVOICE_REF);
            $criteria->addSelectColumn(POrderPeer::INVOICE_AT);
            $criteria->addSelectColumn(POrderPeer::INVOICE_FILENAME);
            $criteria->addSelectColumn(POrderPeer::SUPPORTING_DOCUMENT);
            $criteria->addSelectColumn(POrderPeer::ELECTIVE_MANDATES);
            $criteria->addSelectColumn(POrderPeer::CREATED_AT);
            $criteria->addSelectColumn(POrderPeer::UPDATED_AT);
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
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(POrderPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 POrder
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = POrderPeer::doSelect($critcopy, $con);
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
        return POrderPeer::populateObjects(POrderPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            POrderPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

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
     * @param      POrder $obj A POrder object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            POrderPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A POrder object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof POrder) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or POrder object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(POrderPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   POrder Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(POrderPeer::$instances[$key])) {
                return POrderPeer::$instances[$key];
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
      if ($and_clear_all_references)
      {
        foreach (POrderPeer::$instances as $instance)
        {
          $instance->clearAllReferences(true);
        }
      }
        POrderPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_order
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in POEmailPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        POEmailPeer::clearInstancePool();
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
        $cls = POrderPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = POrderPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                POrderPeer::addInstanceToPool($obj, $key);
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
     * @return array (POrder object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = POrderPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = POrderPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + POrderPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = POrderPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            POrderPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Gender ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int             SQL value
     */
    public static function getGenderSqlValue($enumVal)
    {
        return POrderPeer::getSqlValueForEnum(POrderPeer::GENDER, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PUser table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POOrderState table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPOOrderState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POPaymentState table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPOPaymentState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POPaymentType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPOPaymentType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POSubscription table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPOSubscription(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Selects a collection of POrder objects pre-filled with their PUser objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol = POrderPeer::NUM_HYDRATE_COLUMNS;
        PUserPeer::addSelectColumns($criteria);

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PUserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (POrder) to $obj2 (PUser)
                $obj2->addPOrder($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with their POOrderState objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPOOrderState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol = POrderPeer::NUM_HYDRATE_COLUMNS;
        POOrderStatePeer::addSelectColumns($criteria);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = POOrderStatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = POOrderStatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    POOrderStatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (POrder) to $obj2 (POOrderState)
                $obj2->addPOrder($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with their POPaymentState objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPOPaymentState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol = POrderPeer::NUM_HYDRATE_COLUMNS;
        POPaymentStatePeer::addSelectColumns($criteria);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = POPaymentStatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = POPaymentStatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    POPaymentStatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (POrder) to $obj2 (POPaymentState)
                $obj2->addPOrder($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with their POPaymentType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPOPaymentType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol = POrderPeer::NUM_HYDRATE_COLUMNS;
        POPaymentTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = POPaymentTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = POPaymentTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    POPaymentTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (POrder) to $obj2 (POPaymentType)
                $obj2->addPOrder($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with their POSubscription objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPOSubscription(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol = POrderPeer::NUM_HYDRATE_COLUMNS;
        POSubscriptionPeer::addSelectColumns($criteria);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = POSubscriptionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = POSubscriptionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    POSubscriptionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (POrder) to $obj2 (POSubscription)
                $obj2->addPOrder($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Selects a collection of POrder objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        POOrderStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POOrderStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentStatePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentTypePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POPaymentTypePeer::NUM_HYDRATE_COLUMNS;

        POSubscriptionPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + POSubscriptionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PUser rows

            $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PUserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (PUser)
                $obj2->addPOrder($obj1);
            } // if joined row not null

            // Add objects for joined POOrderState rows

            $key3 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = POOrderStatePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = POOrderStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POOrderStatePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POOrderState)
                $obj3->addPOrder($obj1);
            } // if joined row not null

            // Add objects for joined POPaymentState rows

            $key4 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = POPaymentStatePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = POPaymentStatePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentStatePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentState)
                $obj4->addPOrder($obj1);
            } // if joined row not null

            // Add objects for joined POPaymentType rows

            $key5 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = POPaymentTypePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = POPaymentTypePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POPaymentTypePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POPaymentType)
                $obj5->addPOrder($obj1);
            } // if joined row not null

            // Add objects for joined POSubscription rows

            $key6 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = POSubscriptionPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = POSubscriptionPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    POSubscriptionPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (POrder) to the collection in $obj6 (POSubscription)
                $obj6->addPOrder($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related PUser table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POOrderState table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPOOrderState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POPaymentState table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPOPaymentState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POPaymentType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPOPaymentType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related POSubscription table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPOSubscription(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(POrderPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            POrderPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

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
     * Selects a collection of POrder objects pre-filled with all related objects except PUser.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        POOrderStatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + POOrderStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POPaymentStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentTypePeer::NUM_HYDRATE_COLUMNS;

        POSubscriptionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POSubscriptionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined POOrderState rows

                $key2 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = POOrderStatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = POOrderStatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    POOrderStatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (POOrderState)
                $obj2->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentState rows

                $key3 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = POPaymentStatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = POPaymentStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POPaymentStatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POPaymentState)
                $obj3->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentType rows

                $key4 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = POPaymentTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = POPaymentTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentType)
                $obj4->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POSubscription rows

                $key5 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = POSubscriptionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = POSubscriptionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POSubscriptionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POSubscription)
                $obj5->addPOrder($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with all related objects except POOrderState.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPOOrderState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        POPaymentStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POPaymentStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentTypePeer::NUM_HYDRATE_COLUMNS;

        POSubscriptionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POSubscriptionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PUser rows

                $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PUserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (PUser)
                $obj2->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentState rows

                $key3 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = POPaymentStatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = POPaymentStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POPaymentStatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POPaymentState)
                $obj3->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentType rows

                $key4 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = POPaymentTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = POPaymentTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentType)
                $obj4->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POSubscription rows

                $key5 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = POSubscriptionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = POSubscriptionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POSubscriptionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POSubscription)
                $obj5->addPOrder($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with all related objects except POPaymentState.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPOPaymentState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        POOrderStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POOrderStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentTypePeer::NUM_HYDRATE_COLUMNS;

        POSubscriptionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POSubscriptionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PUser rows

                $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PUserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (PUser)
                $obj2->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POOrderState rows

                $key3 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = POOrderStatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = POOrderStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POOrderStatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POOrderState)
                $obj3->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentType rows

                $key4 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = POPaymentTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = POPaymentTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentType)
                $obj4->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POSubscription rows

                $key5 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = POSubscriptionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = POSubscriptionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POSubscriptionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POSubscription)
                $obj5->addPOrder($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with all related objects except POPaymentType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPOPaymentType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        POOrderStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POOrderStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentStatePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentStatePeer::NUM_HYDRATE_COLUMNS;

        POSubscriptionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POSubscriptionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_SUBSCRIPTION_ID, POSubscriptionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PUser rows

                $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PUserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (PUser)
                $obj2->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POOrderState rows

                $key3 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = POOrderStatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = POOrderStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POOrderStatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POOrderState)
                $obj3->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentState rows

                $key4 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = POPaymentStatePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = POPaymentStatePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentStatePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentState)
                $obj4->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POSubscription rows

                $key5 = POSubscriptionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = POSubscriptionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = POSubscriptionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POSubscriptionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POSubscription)
                $obj5->addPOrder($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of POrder objects pre-filled with all related objects except POSubscription.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of POrder objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPOSubscription(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(POrderPeer::DATABASE_NAME);
        }

        POrderPeer::addSelectColumns($criteria);
        $startcol2 = POrderPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        POOrderStatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + POOrderStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentStatePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + POPaymentStatePeer::NUM_HYDRATE_COLUMNS;

        POPaymentTypePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + POPaymentTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(POrderPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_ORDER_STATE_ID, POOrderStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_STATE_ID, POPaymentStatePeer::ID, $join_behavior);

        $criteria->addJoin(POrderPeer::P_O_PAYMENT_TYPE_ID, POPaymentTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = POrderPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = POrderPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = POrderPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                POrderPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PUser rows

                $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PUserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (POrder) to the collection in $obj2 (PUser)
                $obj2->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POOrderState rows

                $key3 = POOrderStatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = POOrderStatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = POOrderStatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    POOrderStatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (POrder) to the collection in $obj3 (POOrderState)
                $obj3->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentState rows

                $key4 = POPaymentStatePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = POPaymentStatePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = POPaymentStatePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    POPaymentStatePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (POrder) to the collection in $obj4 (POPaymentState)
                $obj4->addPOrder($obj1);

            } // if joined row is not null

                // Add objects for joined POPaymentType rows

                $key5 = POPaymentTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = POPaymentTypePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = POPaymentTypePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    POPaymentTypePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (POrder) to the collection in $obj5 (POPaymentType)
                $obj5->addPOrder($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        return Propel::getDatabaseMap(POrderPeer::DATABASE_NAME)->getTable(POrderPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePOrderPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePOrderPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new POrderTableMap());
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
        return POrderPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a POrder or Criteria object.
     *
     * @param      mixed $values Criteria or POrder object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from POrder object
        }

        if ($criteria->containsKey(POrderPeer::ID) && $criteria->keyContainsValue(POrderPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.POrderPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a POrder or Criteria object.
     *
     * @param      mixed $values Criteria or POrder object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(POrderPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(POrderPeer::ID);
            $value = $criteria->remove(POrderPeer::ID);
            if ($value) {
                $selectCriteria->add(POrderPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(POrderPeer::TABLE_NAME);
            }

        } else { // $values is POrder object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_order table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(POrderPeer::TABLE_NAME, $con, POrderPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            POrderPeer::clearInstancePool();
            POrderPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a POrder or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or POrder object or primary key or array of primary keys
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
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            POrderPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof POrder) { // it's a model object
            // invalidate the cache for this single object
            POrderPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(POrderPeer::DATABASE_NAME);
            $criteria->add(POrderPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                POrderPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(POrderPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            POrderPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given POrder object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      POrder $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(POrderPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(POrderPeer::TABLE_NAME);

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

        return BasePeer::doValidate(POrderPeer::DATABASE_NAME, POrderPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return POrder
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = POrderPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(POrderPeer::DATABASE_NAME);
        $criteria->add(POrderPeer::ID, $pk);

        $v = POrderPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return POrder[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(POrderPeer::DATABASE_NAME);
            $criteria->add(POrderPeer::ID, $pks, Criteria::IN);
            $objs = POrderPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePOrderPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePOrderPeer::buildTableMap();

