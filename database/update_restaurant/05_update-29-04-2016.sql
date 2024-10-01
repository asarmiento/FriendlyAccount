ALTER TABLE order_salons
ADD invoice_id int UNSIGNED NULL after table_salon_id,
ADD CONSTRAINT order_salons_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES invoices(id);