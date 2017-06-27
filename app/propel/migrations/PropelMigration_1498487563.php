<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1498487563.
 * Generated on 2017-06-26 16:32:43 by lionel
 */
class PropelMigration_1498487563
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

ALTER TABLE `p_m_emailing` DROP FOREIGN KEY `p_m_emailing_FK_1`;

DROP INDEX `p_m_emailing_FI_1` ON `p_m_emailing`;

ALTER TABLE `p_m_emailing`
    ADD `p_user_id` INTEGER AFTER `id`;

CREATE INDEX `p_m_emailing_FI_1` ON `p_m_emailing` (`p_user_id`);

CREATE INDEX `p_m_emailing_FI_2` ON `p_m_emailing` (`p_n_email_id`);

ALTER TABLE `p_m_emailing` ADD CONSTRAINT `p_m_emailing_FK_1`
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

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

ALTER TABLE `p_m_emailing` DROP FOREIGN KEY `p_m_emailing_FK_1`;

DROP INDEX `p_m_emailing_FI_2` ON `p_m_emailing`;

DROP INDEX `p_m_emailing_FI_1` ON `p_m_emailing`;

ALTER TABLE `p_m_emailing` DROP `p_user_id`;

CREATE INDEX `p_m_emailing_FI_1` ON `p_m_emailing` (`p_n_email_id`);

ALTER TABLE `p_m_emailing` ADD CONSTRAINT `p_m_emailing_FK_1`
    FOREIGN KEY (`p_n_email_id`)
    REFERENCES `p_n_email` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}