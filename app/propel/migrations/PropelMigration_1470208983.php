<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1470208983.
 * Generated on 2016-08-03 09:23:03 by lionel
 */
class PropelMigration_1470208983
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

CREATE TABLE `p_u_track_u`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `p_user_id_source` INTEGER NOT NULL,
    `p_user_id_dest` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`p_user_id_source`,`p_user_id_dest`),
    INDEX `FI_ser_id_source` (`p_user_id_source`),
    INDEX `FI_ser_id_dest` (`p_user_id_dest`),
    CONSTRAINT `p_user_id_source`
        FOREIGN KEY (`p_user_id_source`)
        REFERENCES `p_user` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `p_user_id_dest`
        FOREIGN KEY (`p_user_id_dest`)
        REFERENCES `p_user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

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

DROP TABLE IF EXISTS `p_u_track_u`;

DROP TABLE IF EXISTS `acl_classes`;

DROP TABLE IF EXISTS `acl_security_identities`;

DROP TABLE IF EXISTS `acl_object_identities`;

DROP TABLE IF EXISTS `acl_object_identity_ancestors`;

DROP TABLE IF EXISTS `acl_entries`;

ALTER TABLE `p_r_badge` DROP FOREIGN KEY `p_r_badge_FK_2`;

ALTER TABLE `p_user_archive` CHANGE `salt` `salt` VARCHAR(255) NOT NULL;

ALTER TABLE `p_user_archive` CHANGE `password` `password` VARCHAR(255) NOT NULL;

CREATE TABLE `p_m_legal_content`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}