<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1501852222.
 * Generated on 2017-08-04 15:10:22 by lionel
 */
class PropelMigration_1501852222
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

DROP INDEX `fos_group_U_1` ON `fos_group`;

DROP INDEX `fos_user_U_3` ON `fos_user`;

ALTER TABLE `p_d_debate`
    ADD `indexed_at` DATETIME AFTER `moderated_at`;

ALTER TABLE `p_d_debate_archive`
    ADD `indexed_at` DATETIME AFTER `moderated_at`;

ALTER TABLE `p_d_reaction`
    ADD `indexed_at` DATETIME AFTER `moderated_at`;

ALTER TABLE `p_d_reaction_archive`
    ADD `indexed_at` DATETIME AFTER `moderated_at`;

ALTER TABLE `p_user`
    ADD `indexed_at` DATETIME AFTER `nb_connected_days`;

ALTER TABLE `p_user_archive`
    ADD `indexed_at` DATETIME AFTER `nb_connected_days`;

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

CREATE UNIQUE INDEX `fos_group_U_1` ON `fos_group` (`name`);

CREATE UNIQUE INDEX `fos_user_U_3` ON `fos_user` (`confirmation_token`);

ALTER TABLE `p_d_debate` DROP `indexed_at`;

ALTER TABLE `p_d_debate_archive` DROP `indexed_at`;

ALTER TABLE `p_d_reaction` DROP `indexed_at`;

ALTER TABLE `p_d_reaction_archive` DROP `indexed_at`;

ALTER TABLE `p_user` DROP `indexed_at`;

ALTER TABLE `p_user_archive` DROP `indexed_at`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}