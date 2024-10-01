ALTER TABLE invoices
ADD user_auth_id int UNSIGNED NULL after table_salon_id,
ADD dues int UNSIGNED NULL after user_auth_id,
ADD CONSTRAINT invoices_user_auth_id_foreign FOREIGN KEY (user_auth_id) REFERENCES users(id);