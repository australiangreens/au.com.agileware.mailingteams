DROP TABLE IF EXISTS `civicrm_team_mailing`;
DROP TABLE IF EXISTS `civicrm_team_mailing_group`;
DROP TABLE IF EXISTS `civicrm_team_mailing_email`;

-- /*******************************************************
-- *
-- * civicrm_team_mailing_email
-- *
-- * Relationship between a Team and a "From Email Address" it can use.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_team_mailing_email` (
     `id`                    int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique TeamMailingFromAddress ID',
     `team_id`               int unsigned NOT NULL                COMMENT 'FK to civicrm_team',
     `from_email_address_id` int unsigned NOT NULL                COMMENT 'From Email Address option value',
     PRIMARY KEY (`id`),
     UNIQUE INDEX `UI_team_mailing_from_address_option_id`(`team_id`, `from_email_address_id`),
     CONSTRAINT FK_civicrm_team_mailing_email_team_id FOREIGN KEY (`team_id`) REFERENCES `civicrm_team`(`id`) ON DELETE CASCADE  
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- /*******************************************************
-- *
-- * civicrm_team_mailing_group
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_team_mailing_group` (
     `id`       int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique TeamMailingGroup ID',
     `team_id`  int unsigned NOT NULL                COMMENT 'FK to civicrm_team',
     `group_id` int unsigned NOT NULL                COMMENT 'FK to Contact',
     `role`     varchar(64)                          COMMENT 'Roles the team performs with the Mailing.',
     PRIMARY KEY (`id`),
     UNIQUE INDEX `UI_team_group_role`(`team_id`, `group_id`, `role`),
     CONSTRAINT FK_civicrm_team_mailing_group_team_id  FOREIGN KEY (`team_id`)  REFERENCES `civicrm_team`(`id`)  ON DELETE CASCADE,
     CONSTRAINT FK_civicrm_team_mailing_group_group_id FOREIGN KEY (`group_id`) REFERENCES `civicrm_group`(`id`) ON DELETE CASCADE  
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- /*******************************************************
-- *
-- * civicrm_team_mailing
-- *
-- * Relationship between a Team and Mailing, for restricted access permission.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_team_mailing` (
     `id`         int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique TeamMailing ID',
     `team_id`    int unsigned NOT NULL                COMMENT 'FK to civicrm_team',
     `mailing_id` int unsigned NOT NULL                COMMENT 'FK to civicrm_mailing',
     PRIMARY KEY (`id`),
     UNIQUE INDEX `UI_team_mailing`(`team_id`, `mailing_id`),
     CONSTRAINT FK_civicrm_team_mailing_team_id    FOREIGN KEY (`team_id`)    REFERENCES `civicrm_team`(`id`)    ON DELETE CASCADE,
     CONSTRAINT FK_civicrm_team_mailing_mailing_id FOREIGN KEY (`mailing_id`) REFERENCES `civicrm_mailing`(`id`) ON DELETE CASCADE  
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
