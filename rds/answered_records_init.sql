CREATE DATABASE IF NOT EXISTS daily_question;
USE daily_question;
CREATE TABLE answered_records (
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_time TIMESTAMP NOT NULL DEFAULT NOW(),
    correction BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (user_id, question_id)
);