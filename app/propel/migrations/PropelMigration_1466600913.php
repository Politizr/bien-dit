<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1466600913.
 * Generated on 2016-06-22 15:08:33 by lionel
 */
class PropelMigration_1466600913
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

DROP TABLE IF EXISTS `subscriber`;

ALTER TABLE `p_d_debate`
    ADD `homepage` TINYINT(1) AFTER `online`;

ALTER TABLE `p_d_debate_archive`
    ADD `homepage` TINYINT(1) AFTER `online`;

ALTER TABLE `p_d_reaction`
    ADD `homepage` TINYINT(1) AFTER `online`;

ALTER TABLE `p_d_reaction_archive`
    ADD `homepage` TINYINT(1) AFTER `online`;

ALTER TABLE `p_user`
    ADD `homepage` TINYINT(1) AFTER `online`;

ALTER TABLE `p_user_archive`
    ADD `homepage` TINYINT(1) AFTER `online`;

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

DROP TABLE IF EXISTS `acl_classes`;

DROP TABLE IF EXISTS `acl_security_identities`;

DROP TABLE IF EXISTS `acl_object_identities`;

DROP TABLE IF EXISTS `acl_object_identity_ancestors`;

DROP TABLE IF EXISTS `acl_entries`;

ALTER TABLE `p_d_debate` DROP `homepage`;

ALTER TABLE `p_d_debate_archive` DROP `homepage`;

ALTER TABLE `p_d_reaction` DROP `homepage`;

ALTER TABLE `p_d_reaction_archive` DROP `homepage`;

ALTER TABLE `p_r_badge` DROP FOREIGN KEY `p_r_badge_FK_2`;

ALTER TABLE `p_user` CHANGE `password` `password` VARCHAR(255);

ALTER TABLE `p_user` DROP `homepage`;

ALTER TABLE `p_user_archive` DROP `homepage`;

CREATE TABLE `subscriber`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(250),
    `email` VARCHAR(250),
    `region` VARCHAR(250),
    `elected` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}