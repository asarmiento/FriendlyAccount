ALTER TABLE closing_cash_desks
ADD user_id int UNSIGNED NOT NULL DEFAULT 0,
add validate_user_id int UNSIGNED not null default 0,
add surplus decimal(20,2) null AFTER total_sales,
add missing decimal(20,2) null AFTER surplus,
ADD CONSTRAINT closing_cash_desks_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id),
ADD CONSTRAINT closing_cash_desks_validate_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id);
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;