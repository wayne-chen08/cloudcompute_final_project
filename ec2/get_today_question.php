<?php
header('Content-Type: application/json');

// 資料庫設定（請依實際情況修改）
$dbhost = "localhost:3308";
$dbuser = "admin";
$dbpass = "1234";
$dbname = "daily_question";

// 建立連線
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "資料庫連線失敗", "error" => $conn->connect_error]);
    exit;
}

// 取得今天日期的題目 ID
$sql = "SELECT question_id FROM question_of_the_day WHERE date = CURDATE()";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $qid = $row['question_id'];

    // 拿到題目內容
    $stmt = $conn->prepare("SELECT description FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $qid);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $q = $res->fetch_assoc();
        echo json_encode(["success" => true, "question_id" => $qid, "description" => $q['description']]);
    } else {
        echo json_encode(["success" => false, "message" => "找不到對應題目"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "今日尚未指定題目"]);
}

$conn->close();
?>
