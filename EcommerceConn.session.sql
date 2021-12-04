CREATE TABLE IF NOT EXISTS tbl_userlogins(
    userlogin_id INT AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    user_ip VARCHAR(25) NOT NULL,
    login_time DATETIME,
    logout_time DATETIME,
    is_deleted INT DEFAULT 0,
    PRIMARY KEY(userlogin_id),
    FOREIGN KEY(`user_id`) REFERENCES tbl_users(`user_id`)
)