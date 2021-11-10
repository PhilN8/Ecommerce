CREATE TABLE IF NOT EXISTS tbl_wallet(
    wallet_id INT AUTO_INCREMENT,
    customer_id INT NOT NULL,
    amount_available DOUBLE,
    created_at DATETIME,
    updated_at DATETIME,
    is_deleted INT DEFAULT 0,
    PRIMARY KEY(wallet_id)
)