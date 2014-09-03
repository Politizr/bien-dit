<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1408714934.
 * Generated on 2014-08-22 15:42:14 by lionel
 */
class PropelMigration_1408714934
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `p_o_subscription` CHANGE `price` `price` DECIMAL(10,2);

ALTER TABLE `p_order` CHANGE `price` `price` DECIMAL(10,2);

ALTER TABLE `p_order` CHANGE `promotion` `promotion` DECIMAL(10,2);

ALTER TABLE `p_order` CHANGE `total` `total` DECIMAL(10,2);

ALTER TABLE `p_order`
    ADD `subscription_begin_at` DATE AFTER `subscription_description`,
    ADD `subscription_end_at` DATE AFTER `subscription_begin_at`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `p_o_subscription` CHANGE `price` `price` DECIMAL;

ALTER TABLE `p_order` CHANGE `price` `price` DECIMAL;

ALTER TABLE `p_order` CHANGE `promotion` `promotion` DECIMAL;

ALTER TABLE `p_order` CHANGE `total` `total` DECIMAL;

ALTER TABLE `p_order` DROP `subscription_begin_at`;

ALTER TABLE `p_order` DROP `subscription_end_at`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}