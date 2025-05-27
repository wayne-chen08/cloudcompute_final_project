CREATE DATABASE IF NOT EXISTS daily_question;
USE daily_question;
CREATE TABLE answered_records (
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    date datetime NOT NULL DEFAULT CURDATE(),
    correction TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (user_id, date, question_id)
);