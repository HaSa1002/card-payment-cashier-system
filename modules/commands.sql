CREATE TABLE `cpcs`.`users` ( `ausweis` BIGINT(13) UNSIGNED NOT NULL , `amount` INT(5) UNSIGNED NULL DEFAULT NULL , `pw` VARCHAR(500) NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `users` ADD PRIMARY KEY(`ausweis`);
ALTER TABLE `users` ADD `level` TINYINT(2) UNSIGNED NULL DEFAULT NULL AFTER `pw`;
ALTER TABLE `users` CHANGE `amount` `amount` FLOAT(5) UNSIGNED NULL DEFAULT NULL;
