<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1552042369.
 * Generated on 2019-03-08 11:52:49 by lionel
 */
class PropelMigration_1552042369
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

ALTER TABLE `p_circle`
    ADD `public_circle` TINYINT(1) DEFAULT 1 AFTER `private_access`,
    ADD `open_reaction` TINYINT(1) DEFAULT 0 AFTER `public_circle`;

ALTER TABLE `p_circle_archive`
    ADD `public_circle` TINYINT(1) DEFAULT 1 AFTER `private_access`,
    ADD `open_reaction` TINYINT(1) DEFAULT 0 AFTER `public_circle`;

ALTER TABLE `p_user` CHANGE `website` `website` VARCHAR(250);

ALTER TABLE `p_user` CHANGE `twitter` `twitter` VARCHAR(250);

ALTER TABLE `p_user` CHANGE `facebook` `facebook` VARCHAR(250);

ALTER TABLE `p_user_archive` CHANGE `website` `website` VARCHAR(250);

ALTER TABLE `p_user_archive` CHANGE `twitter` `twitter` VARCHAR(250);

ALTER TABLE `p_user_archive` CHANGE `facebook` `facebook` VARCHAR(250);

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

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

ALTER TABLE `p_circle` DROP `public_circle`;

ALTER TABLE `p_circle` DROP `open_reaction`;

ALTER TABLE `p_circle_archive` DROP `public_circle`;

ALTER TABLE `p_circle_archive` DROP `open_reaction`;

ALTER TABLE `p_user` CHANGE `website` `website` VARCHAR(150);

ALTER TABLE `p_user` CHANGE `twitter` `twitter` VARCHAR(150);

ALTER TABLE `p_user` CHANGE `facebook` `facebook` VARCHAR(150);

ALTER TABLE `p_user_archive` CHANGE `website` `website` VARCHAR(150);

ALTER TABLE `p_user_archive` CHANGE `twitter` `twitter` VARCHAR(150);

ALTER TABLE `p_user_archive` CHANGE `facebook` `facebook` VARCHAR(150);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}