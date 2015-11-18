<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447825032.
 * Generated on 2015-11-18 06:37:12 by lionel
 */
class PropelMigration_1447825032
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

ALTER TABLE `p_tag`
    ADD `moderated` TINYINT(1) AFTER `title`,
    ADD `moderated_at` DATETIME AFTER `moderated`;

ALTER TABLE `p_tag` ADD CONSTRAINT `p_tag_FK_3`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_tag_archive`
    ADD `moderated` TINYINT(1) AFTER `title`,
    ADD `moderated_at` DATETIME AFTER `moderated`;

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

ALTER TABLE `p_tag` DROP FOREIGN KEY `p_tag_FK_3`;

ALTER TABLE `p_tag` DROP `moderated`;

ALTER TABLE `p_tag` DROP `moderated_at`;

ALTER TABLE `p_tag_archive` DROP `moderated`;

ALTER TABLE `p_tag_archive` DROP `moderated_at`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}