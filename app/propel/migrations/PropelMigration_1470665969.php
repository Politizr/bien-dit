<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1470665969.
 * Generated on 2016-08-08 16:19:29 by lionel
 */
class PropelMigration_1470665969
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

DROP TABLE IF EXISTS `p_m_legal_content`;

ALTER TABLE `p_r_badge` ADD CONSTRAINT `p_r_badge_FK_2`
    FOREIGN KEY (`p_r_badge_family_id`)
    REFERENCES `p_r_badge_family` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE `p_user_archive` CHANGE `salt` `salt` VARCHAR(255);

ALTER TABLE `p_user_archive` CHANGE `password` `password` VARCHAR(255);

CREATE TABLE `acl_classes`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `class_type` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `acl_classes_U_1` (`class_type`)
) ENGINE=InnoDB;

CREATE TABLE `acl_security_identities`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(200) NOT NULL,
    `username` TINYINT(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `acl_security_identities_U_1` (`identifier`, `username`)
) ENGINE=InnoDB;

CREATE TABLE `acl_object_identities`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `class_id` INTEGER NOT NULL,
    `object_identifier` VARCHAR(200) NOT NULL,
    `parent_object_identity_id` INTEGER,
    `entries_inheriting` TINYINT(1) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `acl_object_identities_U_1` (`class_id`, `object_identifier`),
    INDEX `acl_object_identities_I_1` (`parent_object_identity_id`),
    CONSTRAINT `acl_object_identities_FK_1`
        FOREIGN KEY (`class_id`)
        REFERENCES `acl_classes` (`id`)
        ON UPDATE CASCADE,
    CONSTRAINT `acl_object_identities_FK_2`
        FOREIGN KEY (`parent_object_identity_id`)
        REFERENCES `acl_object_identities` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `acl_object_identity_ancestors`
(
    `object_identity_id` INTEGER NOT NULL,
    `ancestor_id` INTEGER NOT NULL,
    PRIMARY KEY (`object_identity_id`,`ancestor_id`),
    INDEX `acl_object_identity_ancestors_FI_2` (`ancestor_id`),
    INDEX `acl_object_identity_ancestors_I_2` (`ancestor_id`),
    CONSTRAINT `acl_object_identity_ancestors_FK_1`
        FOREIGN KEY (`object_identity_id`)
        REFERENCES `acl_object_identities` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `acl_object_identity_ancestors_FK_2`
        FOREIGN KEY (`ancestor_id`)
        REFERENCES `acl_object_identities` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `acl_entries`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `class_id` INTEGER NOT NULL,
    `object_identity_id` INTEGER,
    `security_identity_id` INTEGER NOT NULL,
    `field_name` VARCHAR(50),
    `ace_order` INTEGER NOT NULL,
    `mask` INTEGER NOT NULL,
    `granting` TINYINT(1) NOT NULL,
    `granting_strategy` VARCHAR(30) NOT NULL,
    `audit_success` TINYINT(1) DEFAULT 0 NOT NULL,
    `audit_failure` TINYINT(1) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `acl_entries_U_1` (`class_id`, `object_identity_id`, `field_name`, `ace_order`),
    INDEX `acl_entries_I_1` (`class_id`, `object_identity_id`, `security_identity_id`),
    INDEX `acl_entries_I_2` (`class_id`),
    INDEX `acl_entries_I_3` (`object_identity_id`),
    INDEX `acl_entries_I_4` (`security_identity_id`),
    CONSTRAINT `acl_entries_FK_1`
        FOREIGN KEY (`class_id`)
        REFERENCES `acl_classes` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `acl_entries_FK_2`
        FOREIGN KEY (`object_identity_id`)
        REFERENCES `acl_object_identities` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `acl_entries_FK_3`
        FOREIGN KEY (`security_identity_id`)
        REFERENCES `acl_security_identities` (`id`)
        ON UPDATE CASCADE
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