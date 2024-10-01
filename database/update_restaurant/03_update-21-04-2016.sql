ALTER TABLE `auxiliary_seats` ADD `seating_id` INT(10) NULL AFTER `type_seat_id`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;