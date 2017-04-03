<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1491237397.
 * Generated on 2017-04-03 18:36:37 by lionel
 */
class PropelMigration_1491237397
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

CREATE UNIQUE INDEX `fos_group_U_1` ON `fos_group` (`name`);

CREATE UNIQUE INDEX `fos_user_U_3` ON `fos_user` (`confirmation_token`);

ALTER TABLE `p_tag`
    ADD `p_owner_id` INTEGER AFTER `p_user_id`;

CREATE INDEX `p_tag_FI_4` ON `p_tag` (`p_owner_id`);

ALTER TABLE `p_tag` ADD CONSTRAINT `p_tag_FK_4`
    FOREIGN KEY (`p_owner_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_tag_archive_I_4` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_5` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive`
    ADD `p_owner_id` INTEGER AFTER `p_user_id`;

CREATE INDEX `p_tag_archive_I_4` ON `p_tag_archive` (`p_owner_id`);

CREATE INDEX `p_tag_archive_I_5` ON `p_tag_archive` (`uuid`);

CREATE INDEX `p_tag_archive_I_6` ON `p_tag_archive` (`slug`);

CREATE TABLE `p_t_scope_p_l_c`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_tag_id` INTEGER NOT NULL,
    `p_l_city_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_t_scope_p_l_c_FI_1` (`p_tag_id`),
    INDEX `p_t_scope_p_l_c_FI_2` (`p_l_city_id`),
    CONSTRAINT `p_t_scope_p_l_c_FK_1`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_t_scope_p_l_c_FK_2`
        FOREIGN KEY (`p_l_city_id`)
        REFERENCES `p_l_city` (`id`)
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

DROP TABLE IF EXISTS `p_t_scope_p_l_c`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

DROP INDEX `fos_group_U_1` ON `fos_group`;

DROP INDEX `fos_user_U_3` ON `fos_user`;

ALTER TABLE `p_tag` DROP FOREIGN KEY `p_tag_FK_4`;

DROP INDEX `p_tag_FI_4` ON `p_tag`;

ALTER TABLE `p_tag` DROP `p_owner_id`;

DROP INDEX `p_tag_archive_I_6` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_4` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_5` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive` DROP `p_owner_id`;

CREATE INDEX `p_tag_archive_I_4` ON `p_tag_archive` (`uuid`);

CREATE INDEX `p_tag_archive_I_5` ON `p_tag_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}