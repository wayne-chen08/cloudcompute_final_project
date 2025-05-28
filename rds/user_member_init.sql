CREATE DATABASE IF NOT EXISTS daily_question;
USE daily_question;
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    answered_count INT NOT NULL,
    correct_count INT NOT NULL,
    PRIMARY KEY (user_id)
);
INSERT INTO users (user_id, username, password, answered_count, correct_count)
VALUES
(1, 'u1', 'u1', 0, 0),
(2, 'user', 'userpass', 0, 0),
(3, 'admin', 'adminpass', 0, 0);
