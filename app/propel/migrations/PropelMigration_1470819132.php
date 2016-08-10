<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1470819132.
 * Generated on 2016-08-10 10:52:12 by lionel
 */
class PropelMigration_1470819132
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

CREATE INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors` (`ancestor_id`);

ALTER TABLE `p_user`
    ADD `p_l_city_id` INTEGER AFTER `p_u_status_id`;

CREATE INDEX `p_user_FI_2` ON `p_user` (`p_l_city_id`);

ALTER TABLE `p_user` ADD CONSTRAINT `p_user_FK_2`
    FOREIGN KEY (`p_l_city_id`)
    REFERENCES `p_l_city` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_user_archive_I_2` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_3` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_4` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_5` ON `p_user_archive`;

ALTER TABLE `p_user_archive`
    ADD `p_l_city_id` INTEGER AFTER `p_u_status_id`;

CREATE INDEX `p_user_archive_I_2` ON `p_user_archive` (`p_l_city_id`);

CREATE INDEX `p_user_archive_I_3` ON `p_user_archive` (`uuid`);

CREATE INDEX `p_user_archive_I_4` ON `p_user_archive` (`username_canonical`);

CREATE INDEX `p_user_archive_I_5` ON `p_user_archive` (`email_canonical`);

CREATE INDEX `p_user_archive_I_6` ON `p_user_archive` (`slug`);

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

ALTER TABLE `p_user` DROP FOREIGN KEY `p_user_FK_2`;

DROP INDEX `p_user_FI_2` ON `p_user`;

ALTER TABLE `p_user` DROP `p_l_city_id`;

DROP INDEX `p_user_archive_I_6` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_2` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_3` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_4` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_5` ON `p_user_archive`;

ALTER TABLE `p_user_archive` DROP `p_l_city_id`;

CREATE INDEX `p_user_archive_I_2` ON `p_user_archive` (`uuid`);

CREATE INDEX `p_user_archive_I_3` ON `p_user_archive` (`username_canonical`);

CREATE INDEX `p_user_archive_I_4` ON `p_user_archive` (`email_canonical`);

CREATE INDEX `p_user_archive_I_5` ON `p_user_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}