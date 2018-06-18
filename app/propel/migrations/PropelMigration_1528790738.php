<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1528790738.
 * Generated on 2018-06-12 10:05:38 by lionel
 */
class PropelMigration_1528790738
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

DROP TABLE IF EXISTS `p_u_affinity_q_o`;

ALTER TABLE `cms_category`
    ADD `uuid` VARCHAR(50) FIRST;

ALTER TABLE `cms_content`
    ADD `uuid` VARCHAR(50) FIRST;

ALTER TABLE `cms_content_admin`
    ADD `uuid` VARCHAR(50) FIRST;

CREATE TABLE `cms_info`
(
    `uuid` VARCHAR(50),
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `description` TEXT,
    `online` TINYINT(1),
    `sortable_rank` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `cms_info_slug` (`slug`(255))
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

# app/console politizr:uuids:populate CmsCategory
# app/console politizr:uuids:populate CmsContent
# app/console politizr:uuids:populate CmsContentAdmin
# app/console politizr:uuids:populate CmsInfo
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

DROP TABLE IF EXISTS `cms_info`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

ALTER TABLE `cms_category` DROP `uuid`;

ALTER TABLE `cms_content` DROP `uuid`;

ALTER TABLE `cms_content_admin` DROP `uuid`;

CREATE TABLE `p_u_affinity_q_o`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_q_organization_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_affinity_q_o_FI_1` (`p_user_id`),
    INDEX `p_u_affinity_q_o_FI_2` (`p_q_organization_id`),
    CONSTRAINT `p_u_affinity_q_o_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_affinity_q_o_FK_2`
        FOREIGN KEY (`p_q_organization_id`)
        REFERENCES `p_q_organization` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}