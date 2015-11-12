<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447334271.
 * Generated on 2015-11-12 14:17:51 by lionel
 */
class PropelMigration_1447334271
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

ALTER TABLE `p_m_d_comment_historic`
    ADD `p_user_id` INTEGER AFTER `p_d_d_comment_id`;

CREATE INDEX `p_m_d_comment_historic_FI_2` ON `p_m_d_comment_historic` (`p_user_id`);

ALTER TABLE `p_m_d_comment_historic` ADD CONSTRAINT `p_m_d_comment_historic_FK_2`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_m_debate_historic`
    ADD `p_user_id` INTEGER AFTER `p_d_debate_id`;

CREATE INDEX `p_m_debate_historic_FI_2` ON `p_m_debate_historic` (`p_user_id`);

ALTER TABLE `p_m_debate_historic` ADD CONSTRAINT `p_m_debate_historic_FK_2`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_m_r_comment_historic`
    ADD `p_user_id` INTEGER AFTER `p_d_r_comment_id`;

CREATE INDEX `p_m_r_comment_historic_FI_2` ON `p_m_r_comment_historic` (`p_user_id`);

ALTER TABLE `p_m_r_comment_historic` ADD CONSTRAINT `p_m_r_comment_historic_FK_2`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_m_reaction_historic`
    ADD `p_user_id` INTEGER AFTER `p_d_reaction_id`;

CREATE INDEX `p_m_reaction_historic_FI_2` ON `p_m_reaction_historic` (`p_user_id`);

ALTER TABLE `p_m_reaction_historic` ADD CONSTRAINT `p_m_reaction_historic_FK_2`
    FOREIGN KEY (`p_user_id`)
    REFERENCES `p_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

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

ALTER TABLE `p_m_d_comment_historic` DROP FOREIGN KEY `p_m_d_comment_historic_FK_2`;

DROP INDEX `p_m_d_comment_historic_FI_2` ON `p_m_d_comment_historic`;

ALTER TABLE `p_m_d_comment_historic` DROP `p_user_id`;

ALTER TABLE `p_m_debate_historic` DROP FOREIGN KEY `p_m_debate_historic_FK_2`;

DROP INDEX `p_m_debate_historic_FI_2` ON `p_m_debate_historic`;

ALTER TABLE `p_m_debate_historic` DROP `p_user_id`;

ALTER TABLE `p_m_r_comment_historic` DROP FOREIGN KEY `p_m_r_comment_historic_FK_2`;

DROP INDEX `p_m_r_comment_historic_FI_2` ON `p_m_r_comment_historic`;

ALTER TABLE `p_m_r_comment_historic` DROP `p_user_id`;

ALTER TABLE `p_m_reaction_historic` DROP FOREIGN KEY `p_m_reaction_historic_FK_2`;

DROP INDEX `p_m_reaction_historic_FI_2` ON `p_m_reaction_historic`;

ALTER TABLE `p_m_reaction_historic` DROP `p_user_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}