<?php
session_start();
header('Content-Type: application/json');

$dbhost = "localhost:3308";
$dbuser = "admin";
$dbpass = "1234";
$dbname = "daily_question";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "資料庫連線失敗"]);
    exit;
}

$user = trim($_POST['account'] ?? '');
$pass = trim($_POST['password'] ?? '');

if (!$user || !$pass) {
    echo json_encode(["success" => false, "message" => "帳號密碼不得為空"]);
    exit;
}

// 檢查帳號是否已存在
$check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$check->bind_param("s", $user);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "帳號已存在"]);
    exit;
}

// 寫入新用戶
$insert = $conn->prepare("INSERT INTO users (username, password, answered_count, correct_count) VALUES (?, ?, 0, 0)");
$insert->bind_param("ss", $user, $pass);
if (!$insert->execute()) {
    echo json_encode(["success" => false, "message" => "註冊失敗，請稍後再試"]);
    exit;
}

// 自動登入
$new_id = $conn->insert_id;
$_SESSION['user_id'] = $new_id;

echo json_encode(["success" => true, "redirect" => "question.html"]);
$conn->close();
?>
