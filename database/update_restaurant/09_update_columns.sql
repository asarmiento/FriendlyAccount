-- Table invoices
ALTER TABLE order_salons
ADD split_user_id varchar(255) NULL after status;