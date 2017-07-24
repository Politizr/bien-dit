# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `p_u_subscribe_email`;

DROP TABLE IF EXISTS `p_u_subscribe_screen`;

CREATE TABLE `p_n_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(50),
    `title` VARCHAR(250),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_n_email_U_1` (`uuid`)
) ENGINE=InnoDB;

CREATE TABLE `p_u_subscribe_p_n_e`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_n_email_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_p_n_e_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_p_n_e_FI_2` (`p_n_email_id`),
    CONSTRAINT `p_u_subscribe_p_n_e_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_p_n_e_FK_2`
        FOREIGN KEY (`p_n_email_id`)
        REFERENCES `p_n_email` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_m_emailing`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_n_email_id` INTEGER,
    `title` VARCHAR(150),
    `html_body` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_emailing_FI_1` (`p_n_email_id`),
    CONSTRAINT `p_m_emailing_FK_1`
        FOREIGN KEY (`p_n_email_id`)
        REFERENCES `p_n_email` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

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

ALTER TABLE `p_m_emailing`
    ADD `target_email` VARCHAR(150) AFTER `html_body`;

ALTER TABLE `p_m_emailing` ADD CONSTRAINT `p_m_emailing_FK_2`
    FOREIGN KEY (`p_n_email_id`)
    REFERENCES `p_n_email` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_m_emailing` CHANGE `html_body` `html_body` LONGTEXT;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
