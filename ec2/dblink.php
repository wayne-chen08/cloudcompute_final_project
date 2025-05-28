<?php
    $dbhost = "localhost:3308";
    $dbuser = "admin";
    $dbpass = "1234";
    $dbname = "daily_question";

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // 檢查連線
    if ($conn->connect_error) {
        die("資料庫連線失敗：" . $conn->connect_error);
    }
?>