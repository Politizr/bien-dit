<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447669558.
 * Generated on 2015-11-16 11:25:58 by lionel
 */
class PropelMigration_1447669558
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

ALTER TABLE `p_tag` DROP FOREIGN KEY `p_tag_FK_2`;

DROP INDEX `p_tag_FI_2` ON `p_tag`;

ALTER TABLE `p_tag`
    ADD `p_t_parent_id` INTEGER AFTER `p_t_tag_type_id`;

CREATE INDEX `p_tag_FI_2` ON `p_tag` (`p_t_parent_id`);

CREATE INDEX `p_tag_FI_3` ON `p_tag` (`p_user_id`);

ALTER TABLE `p_tag` ADD CONSTRAINT `p_tag_FK_2`
    FOREIGN KEY (`p_t_parent_id`)
    REFERENCES `p_tag` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_tag_archive_I_2` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_3` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive`
    ADD `p_t_parent_id` INTEGER AFTER `p_t_tag_type_id`;

CREATE INDEX `p_tag_archive_I_2` ON `p_tag_archive` (`p_t_parent_id`);

CREATE INDEX `p_tag_archive_I_3` ON `p_tag_archive` (`p_user_id`);

CREATE INDEX `p_tag_archive_I_4` ON `p_tag_archive` (`slug`);

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

ALTER TABLE `p_tag` DROP FOREIGN KEY `p_tag_FK_2`;

DROP INDEX `p_tag_FI_3` ON `p_tag`;

DROP INDEX `p_tag_FI_2` ON `p_tag`;

ALTER TABLE `p_tag` DROP `p_t_parent_id`;

CREATE INDEX `p_tag_FI_2` ON `p_tag` (`p_user_id`);

ALTER TABLE `p_tag` ADD CONSTRAINT `p_tag_FK_2`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_tag_archive_I_4` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_2` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_3` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive` DROP `p_t_parent_id`;

CREATE INDEX `p_tag_archive_I_2` ON `p_tag_archive` (`p_user_id`);

CREATE INDEX `p_tag_archive_I_3` ON `p_tag_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}