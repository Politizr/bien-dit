<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1448884624.
 * Generated on 2015-11-30 12:57:04 by lionel
 */
class PropelMigration_1448884624
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

DROP TABLE IF EXISTS `p_u_follow_t`;

ALTER TABLE `p_u_tagged_t`
    ADD `invisible` TINYINT(1) DEFAULT 0 AFTER `p_tag_id`;

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

ALTER TABLE `p_u_tagged_t` DROP `invisible`;

CREATE TABLE `p_u_follow_t`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_follow_t_FI_1` (`p_user_id`),
    INDEX `p_u_follow_t_FI_2` (`p_tag_id`),
    CONSTRAINT `p_u_follow_t_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_follow_t_FK_2`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}