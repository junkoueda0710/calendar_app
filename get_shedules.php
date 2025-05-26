<?php 

header('Content-Type: application/json'); 

$pdo = new PDO('mysql:host=localhost;dbname=calendar_db;charset=utf8mb4', 'root', ''); 

$date = $_GET['date'] ?? date('Y-m-d'); 

$stmt = $pdo->prepare("SELECT * FROM schedules WHERE schedule_date = ?"); 

$stmt->execute([$date]); 

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 