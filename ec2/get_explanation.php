<?php
    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "尚未登入"]);
        exit;
    }
    $user_id = $_SESSION['user_id'];

    include("dblink.php");

    // 查今日題號
    $res = $conn->query("SELECT question_id FROM question_of_the_day WHERE date = CURDATE()");
    if (!$res || $res->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "找不到今日題目"]);
        exit;
    }
    $qid = $res->fetch_assoc()['question_id'];

    // 查是否已作答
    $check = $conn->prepare("SELECT correction FROM answered_records WHERE user_id = ? AND question_id = ?");
    $check->bind_param("ii", $user_id, $qid);
    $check->execute();
    $r = $check->get_result();
    if ($r->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "你尚未作答，不能查看解析"]);
        exit;
    }

    // 取得解析
    $stmt = $conn->prepare("SELECT explanation FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $qid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo json_encode(["success" => true, "explanation" => $row['explanation']]);
    } else {
        echo json_encode(["success" => false, "message" => "解析不存在"]);
    }
?>
