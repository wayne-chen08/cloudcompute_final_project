CREATE DATABASE IF NOT EXISTS daily_question;
USE user_member1;
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    answered_number decimal NOT NULL,
    correct_number decimal NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO users (user_id, username, password, answered_number, correct_number)
VALUES
(1, 'u1', 'u1', 0, 0),
(2, 'user', 'userpass', 0, 0);
