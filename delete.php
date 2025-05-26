<?php 

require 'db.php'; 

 

$id = $_GET['id'] ?? null; 

 

if (!$id) { 

    echo 'IDがありません'; 

    exit; 

} 

 

// IDの予定データを取得 

$stmt = $pdo->prepare('SELECT * FROM events WHERE id = :id'); 

$stmt->execute([':id' => $id]); 

$event = $stmt->fetch(); 

 

if (!$event) { 

    echo 'そのIDのデータはありません'; 

    exit; 

} 

 

// event_date から year と month を取得 

$event_date = $event['event_date']; 

$year = date('Y', strtotime($event_date)); 

$month = date('m', strtotime($event_date)); 

 

// 削除処理 

$stmt = $pdo->prepare('DELETE FROM events WHERE id = :id'); 

$stmt->execute([':id' => $id]); 

 

// 元の年月に戻る 

header("Location: index.php?year=$year&month=$month"); 

exit; 

?> 