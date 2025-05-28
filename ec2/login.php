<?php
    session_start();
    header('Content-Type: application/json');
    
    include("dblink.php");

    // 取得輸入的帳號跟密碼
    $user = $_POST['account'];
    $pass = $_POST['password'];

    // 避免SQL injection，進到資料庫找可登入的帳號
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $stmt->bind_param("ss", $user, $pass); // "ss" 表示兩個都是 string
    $stmt->execute();
    $result = $stmt->get_result();
    
    // query有搜到，也就是帳號和密碼有對應的資料
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id']; // 儲存登入者 ID
        
        if ($user === 'admin') {
            $redirect = "admin.html"; // 進入題目管理頁面
        } 
        else {
            $redirect = "question.html"; // 一般使用者答題頁
        }

        // 再導向對應的網頁
        echo json_encode(["success" => true, "redirect" => $redirect]);
        
        exit;
    }
    // query沒搜到，也就是帳號或密碼有錯
    else{
        echo json_encode(["success" => false, "message" => "登入失敗，請重新輸入"]);
    }
    $conn->close();
?>