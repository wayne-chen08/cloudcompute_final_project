<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔹 資料庫連線資訊
    $dbhost = "localhost:3308";
    $dbuser = "admin";
    $dbpass = "1234";
    $dbname = "daily_question";

    // 🔹 建立資料庫連線
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("連線失敗：" . $conn->connect_error);
    }

    // 🔹 取得表單資料
    $description = $_POST['description'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $explanation = $_POST['explanation'] ?? '';

    // 🔹 寫入資料庫
    $stmt = $conn->prepare("INSERT INTO questions (description, answer, explanation) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $description, $answer, $explanation);

    if ($stmt->execute()) {
        echo "題目新增成功！";
    } else {
        echo "新增失敗：" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>