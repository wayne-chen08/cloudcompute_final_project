<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include("dblink.php");

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