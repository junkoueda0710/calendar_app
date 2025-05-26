<?php 

// db.php 

 

$host = 'localhost';             // 自分の環境に合わせてね 

$dbname = 'calendar_db';         // データベース名 

$user = 'root';                  // XAMPPなら多分 root 

$pass = '';                      // XAMPPなら多分パスワードなし 

 

try { 

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass); 

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

} catch (PDOException $e) { 

    echo 'DB接続エラー: ' . $e->getMessage(); 

    exit; 

} 