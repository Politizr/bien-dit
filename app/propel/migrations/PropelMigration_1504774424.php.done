<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1504774424.
 * Generated on 2017-09-07 10:53:44 by lionel
 */
class PropelMigration_1504774424
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

ALTER TABLE `p_d_debate`
    ADD `want_boost` INTEGER DEFAULT 0 AFTER `nb_views`;

ALTER TABLE `p_d_debate_archive`
    ADD `want_boost` INTEGER DEFAULT 0 AFTER `nb_views`;

ALTER TABLE `p_d_reaction`
    ADD `want_boost` INTEGER DEFAULT 0 AFTER `nb_views`;

ALTER TABLE `p_d_reaction_archive`
    ADD `want_boost` INTEGER DEFAULT 0 AFTER `nb_views`;

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

ALTER TABLE `p_d_debate` DROP `want_boost`;

ALTER TABLE `p_d_debate_archive` DROP `want_boost`;

ALTER TABLE `p_d_reaction` DROP `want_boost`;

ALTER TABLE `p_d_reaction_archive` DROP `want_boost`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}