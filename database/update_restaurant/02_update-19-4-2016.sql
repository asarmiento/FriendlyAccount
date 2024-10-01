ALTER TABLE `invoices` ADD `surplus` decimal(20,2) NULL AFTER `changing`,
ADD `missing` decimal(20,2) NULL AFTER `surplus`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;