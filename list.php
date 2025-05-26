<?php 

require 'db.php'; 

 

$date = $_GET['date'] ?? date('Y-m-d'); 

 

// データ取得 

$stmt = $pdo->prepare('SELECT * FROM events WHERE event_date = :event_date ORDER BY start_time'); 

$stmt->execute([':event_date' => $date]); 

$events = $stmt->fetchAll(PDO::FETCH_ASSOC); 

?> 

<!DOCTYPE html> 

<html lang="ja"> 

<head> 

    <meta charset="UTF-8"> 

    <title><?= htmlspecialchars($date) ?> の予定一覧</title> 

    <style> 

        table { 

            border-collapse: collapse; 

            width: 100%; 

        } 

        th, td { 

            border: 1px solid #ccc; 

            padding: 8px; 

            text-align: center; 

        } 

        th { 

            background-color: #f0f0f0; 

        } 

        .actions a { 

            margin: 0 5px; 

        } 

    </style> 

</head> 

<body> 

    <h2><?= htmlspecialchars($date) ?> の予定一覧</h2> 

    <p> 

               
        | <a href="index.php?year=<?= date('Y', strtotime($date)) ?>&month=<?= date('m', strtotime($date)) ?>">カレンダーに戻る</a> 

        <a href="add.php?date=<?= urlencode($date) ?>">新規追加</a> 

    </p> 

 

    <?php if (count($events) > 0): ?> 

        <table> 

            <tr> 

                <th>タイトル</th> 

                <th>カテゴリ</th> 

                <th>終日</th> 

                <th>開始時間</th> 

                <th>終了時間</th> 

                <th>メモ</th> 

                <th>操作</th> 

            </tr> 

            <?php foreach ($events as $event): ?> 

                <tr> 

                    <td><?= htmlspecialchars($event['title']) ?></td> 

                    <td><?= htmlspecialchars($event['category']) ?></td> 

                    <td><?= $event['all_day'] ? '○' : '' ?></td> 

                    <td><?= htmlspecialchars($event['start_time']) ?></td> 

                    <td><?= htmlspecialchars($event['end_time']) ?></td> 

                    <td><?= nl2br(htmlspecialchars($event['memo'])) ?></td> 

                    <td class="actions"> 

                        <a href="edit.php?id=<?= $event['id'] ?>">編集</a> | 

                        <a href="delete.php?id=<?= $event['id'] ?>" onclick="return confirm('削除してもよいですか？');">削除</a> 

                    </td> 

                </tr> 

            <?php endforeach; ?> 

        </table> 

    <?php else: ?> 

        <p>予定はありません。</p> 

    <?php endif; ?> 

</body> 

</html> 