<?php
    session_start();
    header('Content-Type: application/json');

    // 檢查是否登入
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "status" => "unknown"]);
        exit;
    }
    $user_id = $_SESSION['user_id'];

    include("dblink.php");

    // 查當日題目
    $res = $conn->query("SELECT question_id FROM question_of_the_day WHERE date = CURDATE()");
    if (!$res || $res->num_rows === 0) {
        echo json_encode(["success" => false, "status" => "unknown"]);
        exit;
    }
    $qid = $res->fetch_assoc()['question_id'];

    // 查是否已作答
    $stmt = $conn->prepare("SELECT correction FROM answered_records WHERE user_id = ? AND question_id = ?");
    $stmt->bind_param("ii", $user_id, $qid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => true, "status" => "none"]);
    } else {
        $row = $result->fetch_assoc();
        echo json_encode(["success" => true, "status" => $row['correction'] ? "correct" : "wrong"]);
    }
?>
