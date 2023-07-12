-- Create table for suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    supplier_id VARCHAR(4) NOT NULL PRIMARY KEY,
    supplier_name VARCHAR(255) NOT NULL,
    supplier_address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL
);

-- Create table for products
CREATE TABLE IF NOT EXISTS products (
    product_id VARCHAR(4) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_desc VARCHAR(255) NOT NULL,
    product_price VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    product_status VARCHAR(4) NOT NULL,
    supplier_id VARCHAR(4) NOT NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
);