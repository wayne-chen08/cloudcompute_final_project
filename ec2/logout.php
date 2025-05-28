<?php
    session_start();
    session_destroy(); // 清除所有 session 資料
    header("Location: login.html");
    exit;
?>