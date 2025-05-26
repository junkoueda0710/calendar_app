<?php 

require_once 'db.php'; 

 

if (!empty($_POST['id']) && !empty($_POST['title'])) { 

    $stmt = $pdo->prepare("UPDATE schedules SET title = ?, color = ? WHERE id = ?"); 

    $stmt->execute([$_POST['title'], $_POST['color'], $_POST['id']]); 

    echo "更新しました"; 

} else { 

    echo "IDとタイトルは必須です"; 

} 

?> 