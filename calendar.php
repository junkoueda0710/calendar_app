<?php 

error_reporting(E_ALL); 

ini_set('display_errors', 1); 

require_once 'db.php'; 

require_once 'holidays.php'; 

 

// 年月取得（ゼロ埋めあり） 

$year = $_GET['year'] ?? date('Y'); 

$month = str_pad($_GET['month'] ?? date('m'), 2, '0', STR_PAD_LEFT); 

 

$start_day = "$year-$month-01"; 

$end_day = date("Y-m-t", strtotime($start_day)); 

 

$holidays = getHolidays($year); 

 

// 予定取得 

$stmt = $pdo->prepare("SELECT * FROM schedules WHERE schedule_date BETWEEN ? AND ?"); 

$stmt->execute([$start_day, $end_day]); 

$schedules = []; 

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 

    $schedules[$row['schedule_date']][] = $row; 

} 

?> 

<!DOCTYPE html> 

<html lang="ja"> 

<head> 

    <meta charset="UTF-8"> 

    <title>スケジュールカレンダー</title> 

    <link rel="stylesheet" href="style.css?v=2"> 
    <script src="script.js"></script>

</head> 

<body> 

<div class="calendar-container"> 

    <div class="calendar-header"> 

        <button onclick="changeMonth(-1)">&lt;</button> 

        <span id="calendar-title"><?= $year ?>年<?= $month ?>月</span> 

        <button onclick="changeMonth(1)">&gt;</button> 

    </div> 

    <table class="calendar"> 

        <thead> 

            <tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr> 

        </thead> 

        <tbody> 

            <tr> 

            <?php 

            $first_day_week = date('w', strtotime($start_day)); 

            $total_days = date('t', strtotime($start_day)); 

 

            for ($i = 0; $i < $first_day_week; $i++) echo "<td></td>"; 

 

            for ($day = 1; $day <= $total_days; $day++) { 

                $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT); 

                $w = date('w', strtotime($date)); 

 

                $holidayClass = ''; 

                $holidayName = ''; 

                if (isset($holidays[$date])) { 

                    $holidayClass = 'sunday'; 

                    $holidayName = $holidays[$date]; 

                } elseif ($w == 0) { 

                    $holidayClass = 'sunday'; 

                } elseif ($w == 6) { 

                    $holidayClass = 'saturday'; 

                } 

 

                echo "<td class='$holidayClass' data-date='$date'>"; 

                echo "<div class='day-number'>$day</div>"; 

                if ($holidayName) echo "<div class='holiday-name'>$holidayName</div>"; 

 

                // 予定表示 

                if (isset($schedules[$date])) { 

                    foreach ($schedules[$date] as $schedule) { 

                        echo "<div class='schedule' style='color: {$schedule['color']}' data-id='{$schedule['id']}'>" . htmlspecialchars($schedule['title']) . "</div>"; 

                    } 

                } 

                echo "</td>"; 

                if ($w == 6) echo "</tr><tr>"; 

            } 

            ?> 

            </tr> 

        </tbody> 

    </table> 

</div> 

 

<!-- モーダル --> 

<div id="modal" class="modal"> 

    <div class="modal-content"> 

        <span class="close-button" onclick="closeModal()">&times;</span> 

        <h2 id="modal-title">予定追加</h2> 

        <form id="schedule-form"> 

            <input type="hidden" name="id" id="schedule-id"> 

            <input type="hidden" name="schedule_date" id="schedule-date"> 

            <label>タイトル: <input type="text" name="title" id="title"></label><br> 

            <label>色:  

                <select name="color" id="color"> 

                    <option value="red">赤</option> 

                    <option value="orange">オレンジ</option> 

                    <option value="green">緑</option> 

                    <option value="blue">青</option> 

                    <option value="black">黒</option> 

                </select> 

            </label><br> 

            <button type="submit">保存</button> 

            <button type="button" id="delete-button" style="display:none;">削除</button> 

        </form> 

    </div> 

</div> 

 

<script src="script.js"></script> 

<script> 

function changeMonth(diff) { 

    const currentYear = <?= $year ?>; 

    const currentMonth = <?= (int)$month ?>; 

    let newMonth = currentMonth + diff; 

    let newYear = currentYear; 

    if (newMonth < 1) { newMonth = 12; newYear--; } 

    else if (newMonth > 12) { newMonth = 1; newYear++; } 

    location.href = "?year=" + newYear + "&month=" + newMonth; 

} 

</script> 

</body> 

</html> 