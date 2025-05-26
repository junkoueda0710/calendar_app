<?php 

 

require 'db.php'; 

 

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');  // クリックした日にち 

 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    $event_date = $date; 

    $title = $_POST['title']; 

    $category = $_POST['category']; 

    $all_day = isset($_POST['all_day']) ? 1 : 0; 

 

    $start_time = !$all_day ? $_POST['start_time'] : null; 

    $end_time = !$all_day ? $_POST['end_time'] : null; 

 

    $stmt = $pdo->prepare('INSERT INTO events (event_date, title, category, start_time, end_time, all_day, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())'); 

    $stmt->execute([$event_date, $title, $category, $start_time, $end_time, $all_day]); 

 

    // 追加した日の月へ戻る 

    header('Location: index.php?year=' . date('Y', strtotime($event_date)) . '&month=' . date('n', strtotime($event_date))); 

    exit; 

} 

?> 

 

<!DOCTYPE html> 

<html lang="ja"> 

<head> 

    <meta charset="UTF-8"> 

    <title>予定追加</title> 

</head> 

<body> 

    <h1><?php echo $date; ?> の予定を追加する</h1> 

 

    <form action="add.php?date=<?php echo $date; ?>" method="post"> 

        <input type="hidden" name="event_date" value="<?php echo $date; ?>"> 

 

        タイトル: <input type="text" name="title" required><br><br> 

 

        カテゴリ:  

        <select name="category" required> 

            <option value="赤">赤</option> 

            <option value="青">青</option> 

            <option value="緑">緑</option> 

            <option value="黄色">黄色</option> 

            <option value="オレンジ">オレンジ</option> 

        </select><br><br> 

 

        <label> 

            <input type="checkbox" name="all_day" id="all_day"> 終日 

        </label><br><br> 

 

        <div id="time_inputs"> 

            開始時間: <input type="time" name="start_time"><br> 

            終了時間: <input type="time" name="end_time"><br> 

        </div><br> 

 

        <button type="submit">追加</button> 

    </form> 

 

    <p><a href="index.php?year=<?php echo date('Y', strtotime($date)); ?>&month=<?php echo date('n', strtotime($date)); ?>">カレンダーに戻る</a></p> 

 

    <script> 

        const allDayCheckbox = document.getElementById('all_day'); 

        const timeInputs = document.querySelectorAll('#time_inputs input'); 

 

        function toggleTimeInputs() { 

            if (allDayCheckbox.checked) { 

                timeInputs.forEach(input => { 

                    input.required = false; 

                    input.disabled = true; 

                }); 

            } else { 

                timeInputs.forEach(input => { 

                    input.required = true; 

                    input.disabled = false; 

                }); 

            } 

        } 

 

        allDayCheckbox.addEventListener('change', toggleTimeInputs); 

        window.addEventListener('DOMContentLoaded', toggleTimeInputs); 

    </script> 

</body> 

</html> 