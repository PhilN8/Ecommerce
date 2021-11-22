CREATE TABLE IF NOT EXISTS tbl_productimages(
    productimages_id INT AUTO_INCREMENT,
    product_image VARCHAR(40) NOT NULL,
    product_id INT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    added_by INT NOT NULL,
    is_deleted INT DEFAULT 0,
    PRIMARY KEY(productimages_id),
    FOREIGN KEY(product_id) REFERENCES tbl_products(product_id),
    FOREIGN KEY(added_by) REFERENCES tbl_users(`user_id`)
)