<?php
session_start();
header('Content-Type: application/json');

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "尚未登入"]);
    exit;
}
$user_id = $_SESSION['user_id'];

// 取得前端送來的答案
$input = json_decode(file_get_contents("php://input"), true);
$user_answer = trim($input["answer"] ?? '');

// 資料庫設定
$dbhost = "localhost:3308";
$dbuser = "admin";
$dbpass = "1234";
$dbname = "daily_question";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "資料庫連線失敗", "error" => $conn->connect_error]);
    exit;
}

// 查當日題目
$result = $conn->query("SELECT question_id FROM question_of_the_day WHERE date = CURDATE()");
if (!$result || $result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "今日尚未設定題目"]);
    exit;
}
$qid = $result->fetch_assoc()['question_id'];

// 查正確答案
$stmt = $conn->prepare("SELECT answer FROM questions WHERE question_id = ?");
$stmt->bind_param("i", $qid);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "找不到題目"]);
    exit;
}
$correct_answer = trim($res->fetch_assoc()['answer']);
$is_correct = ($user_answer === $correct_answer) ? 1 : 0;

// 查是否已作答
$check = $conn->prepare("
    SELECT correction FROM answered_records 
    WHERE user_id = ? AND question_id = ?
");
$check->bind_param("ii", $user_id, $qid);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    // 第一次作答
    $insert = $conn->prepare("
        INSERT INTO answered_records (user_id, question_id, correction)
        VALUES (?, ?, ?)
    ");
    $insert->bind_param("iii", $user_id, $qid, $is_correct);
    $insert->execute();

    $update = $conn->prepare("
        UPDATE users 
        SET answered_count = answered_count + 1,
            correct_count = correct_count + ?
        WHERE user_id = ?
    ");
    $update->bind_param("ii", $is_correct, $user_id);
    $update->execute();

    $message = $is_correct ? "答對了！" : "答錯了，請再試一次";

} else {
    $prev = $result->fetch_assoc();
    if ($prev['correction'] == 1) {
        // 已經答對過，保留正確紀錄
        $message = "你已經答對過這題，無需再答";
    } elseif ($is_correct) {
        // 曾答錯，這次答對 → 升級
        $updateAns = $conn->prepare("
            UPDATE answered_records 
            SET correction = 1 
            WHERE user_id = ? AND question_id = ?
        ");
        $updateAns->bind_param("ii", $user_id, $qid);
        $updateAns->execute();

        $updateStat = $conn->prepare("
            UPDATE users 
            SET correct_count = correct_count + 1
            WHERE user_id = ?
        ");
        $updateStat->bind_param("i", $user_id);
        $updateStat->execute();

        $message = "這次你答對了！已更新紀錄";
    } else {
        // 曾答錯，這次也錯
        $message = "你之前答錯過這題，這次也沒對 😢";
    }
}

echo json_encode([
    "success" => true,
    "correct" => $is_correct === 1,
    "message" => $message
]);

$conn->close();
?>
