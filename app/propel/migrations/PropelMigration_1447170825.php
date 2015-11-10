<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447170825.
 * Generated on 2015-11-10 16:53:45 by lionel
 */
class PropelMigration_1447170825
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

ALTER TABLE `p_user`
    ADD `banned_nb_days_left` INTEGER AFTER `banned`,
    ADD `banned_nb_total` INTEGER AFTER `banned_nb_days_left`;

ALTER TABLE `p_user` DROP `banned_at`;

ALTER TABLE `p_user_archive`
    ADD `banned_nb_days_left` INTEGER AFTER `banned`,
    ADD `banned_nb_total` INTEGER AFTER `banned_nb_days_left`;

ALTER TABLE `p_user_archive` DROP `banned_at`;

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

ALTER TABLE `p_user`
    ADD `banned_at` DATETIME AFTER `banned`;

ALTER TABLE `p_user` DROP `banned_nb_days_left`;

ALTER TABLE `p_user` DROP `banned_nb_total`;

ALTER TABLE `p_user_archive`
    ADD `banned_at` DATETIME AFTER `banned`;

ALTER TABLE `p_user_archive` DROP `banned_nb_days_left`;

ALTER TABLE `p_user_archive` DROP `banned_nb_total`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}