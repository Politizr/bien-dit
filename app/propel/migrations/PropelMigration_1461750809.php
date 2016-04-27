<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1461750809.
 * Generated on 2016-04-27 11:53:29 by lionel
 */
class PropelMigration_1461750809
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

ALTER TABLE `p_u_follow_d_d` DROP `notif_reaction`;

ALTER TABLE `p_u_follow_u` DROP `notif_debate`;

ALTER TABLE `p_u_follow_u` DROP `notif_reaction`;

ALTER TABLE `p_u_follow_u` DROP `notif_comment`;

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

ALTER TABLE `p_u_follow_d_d`
    ADD `notif_reaction` TINYINT(1) DEFAULT 1 AFTER `p_d_debate_id`;

ALTER TABLE `p_u_follow_u`
    ADD `notif_debate` TINYINT(1) DEFAULT 1 FIRST,
    ADD `notif_reaction` TINYINT(1) DEFAULT 1 AFTER `notif_debate`,
    ADD `notif_comment` TINYINT(1) DEFAULT 1 AFTER `notif_reaction`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}