<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447135833.
 * Generated on 2015-11-10 07:10:33 by lionel
 */
class PropelMigration_1447135833
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

ALTER TABLE `p_d_d_comment`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_d_comment_archive`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_debate`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_debate_archive`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_r_comment`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_r_comment_archive`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_reaction`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_d_reaction_archive`
    ADD `moderated` TINYINT(1) AFTER `online`,
    ADD `moderated_partial` TINYINT(1) AFTER `moderated`;

ALTER TABLE `p_user`
    ADD `banned` TINYINT(1) AFTER `online`,
    ADD `banned_at` DATETIME AFTER `banned`,
    ADD `abuse_level` INTEGER AFTER `banned_at`;

ALTER TABLE `p_user_archive`
    ADD `banned` TINYINT(1) AFTER `online`,
    ADD `banned_at` DATETIME AFTER `banned`,
    ADD `abuse_level` INTEGER AFTER `banned_at`;

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

ALTER TABLE `p_d_d_comment` DROP `moderated`;

ALTER TABLE `p_d_d_comment` DROP `moderated_partial`;

ALTER TABLE `p_d_d_comment_archive` DROP `moderated`;

ALTER TABLE `p_d_d_comment_archive` DROP `moderated_partial`;

ALTER TABLE `p_d_debate` DROP `moderated`;

ALTER TABLE `p_d_debate` DROP `moderated_partial`;

ALTER TABLE `p_d_debate_archive` DROP `moderated`;

ALTER TABLE `p_d_debate_archive` DROP `moderated_partial`;

ALTER TABLE `p_d_r_comment` DROP `moderated`;

ALTER TABLE `p_d_r_comment` DROP `moderated_partial`;

ALTER TABLE `p_d_r_comment_archive` DROP `moderated`;

ALTER TABLE `p_d_r_comment_archive` DROP `moderated_partial`;

ALTER TABLE `p_d_reaction` DROP `moderated`;

ALTER TABLE `p_d_reaction` DROP `moderated_partial`;

ALTER TABLE `p_d_reaction_archive` DROP `moderated`;

ALTER TABLE `p_d_reaction_archive` DROP `moderated_partial`;

ALTER TABLE `p_user` DROP `banned`;

ALTER TABLE `p_user` DROP `banned_at`;

ALTER TABLE `p_user` DROP `abuse_level`;

ALTER TABLE `p_user_archive` DROP `banned`;

ALTER TABLE `p_user_archive` DROP `banned_at`;

ALTER TABLE `p_user_archive` DROP `abuse_level`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}