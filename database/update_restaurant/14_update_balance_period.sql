ALTER TABLE `balance_periods` CHANGE `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `balance_periods` CHANGE `period` `accounting_period_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `balance_periods` ADD CONSTRAINT `fk_accounting_period_id` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;