<?php
    header('Content-Type: application/json');
    // 連線資料庫，記得改自己的
    $dbhost = "localhost:3308";
    $dbuser = "admin";
    $dbpass = "1234";
    $dbname = "user_member1";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn -> connect_error){
        echo json_encode(["success" => false, "message" => "資料庫連線失敗"]);

        exit;
    }

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
        // 先更新對應帳號的最後登入時間
        $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE username = ?");
        $update->bind_param("s", $user);
        $update->execute();

        // 再導向對應的網頁
        $redirect = "index.html";
        echo json_encode(["success" => true, "redirect" => $redirect]);
        
        exit;
    }
    // query沒搜到，也就是帳號或密碼有錯
    else{
        echo json_encode(["success" => false, "message" => "登入失敗，請重新輸入"]);
    }
    $conn->close();
?>