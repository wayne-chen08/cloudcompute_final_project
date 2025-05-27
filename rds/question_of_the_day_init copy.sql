CREATE DATABASE IF NOT EXISTS daily_question;
USE daily_question;
CREATE TABLE question_of_the_day (
    date datetime NOT NULL DEFAULT CURDATE(),
    question_id INT NOT NULL,
    PRIMARY KEY (date, question_id)
);
