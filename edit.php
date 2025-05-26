<?php 

 

$pdo = new PDO('mysql:host=localhost;dbname=calendar_db;charset=utf8mb4', 'root', ''); 

 

// GETからid取得 

$id = $_GET['id'] ?? null; 

$date = $_GET['date'] ?? date('Y-m-d'); 

 

if (!$id) { 

    echo "IDが指定されていません。"; 

    exit; 

} 

 

// イベント取得 

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?"); 

$stmt->execute([$id]); 

$event = $stmt->fetch(PDO::FETCH_ASSOC); 

 

if (!$event) { 

    echo "予定が見つかりませんでした"; 

    exit; 

} 

 

// POSTで更新処理 

if ($_SERVER["REQUEST_METHOD"] === "POST") { 

    $title = $_POST['title']; 

    $start_time = $_POST['start_time']; 

    $end_time = $_POST['end_time']; 

    $event_date = $_POST['event_date']; 

 

    $stmt = $pdo->prepare("UPDATE events SET title = ?, start_time = ?, end_time = ?, event_date = ? WHERE id = ?"); 

    $stmt->execute([$title, $start_time, $end_time, $event_date, $id]); 

 

    header("Location: list.php?date=" . urlencode($event_date)); 

    exit; 

} 

?> 

 

<!DOCTYPE html> 

<html lang="ja"> 

<head> 

    <meta charset="UTF-8"> 

    <title>予定を編集</title> 

</head> 

<body> 

    <h2>予定を編集</h2> 

    <form method="POST"> 

        <label>タイトル: <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required></label><br><br> 

        <label>開始時間: <input type="time" name="start_time" value="<?= htmlspecialchars($event['start_time']) ?>" required></label><br><br> 

        <label>終了時間: <input type="time" name="end_time" value="<?= htmlspecialchars($event['end_time']) ?>" required></label><br><br> 

        <label>日付: <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required></label><br><br> 

        <input type="submit" value="更新"> 

    </form> 

    <p><a href="list.php?date=<?= htmlspecialchars($date) ?>">← 戻る</a></p> 

</body> 

</html> 