<?php
header('Content-Type: application/json');

// 資料庫連線設定
$dbhost = "localhost:3308";
$dbuser = "admin";
$dbpass = "1234";
$dbname = "daily_question";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "資料庫連線失敗"]);
  exit;
}

// 查詢今天的題目
$sql = "SELECT q.question_id, q.description, q.pic
        FROM questions q
        JOIN question_of_the_day t ON q.question_id = t.question_id
        WHERE t.date = CURDATE()
        LIMIT 1";

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
  echo json_encode([
    "success" => true,
    "question_id" => $row['question_id'],
    "description" => $row['description'],
    "pic" => $row['pic'] ?? null
  ]);
} else {
  echo json_encode(["success" => false, "message" => "今日尚無題目"]);
}

$conn->close();
?>
