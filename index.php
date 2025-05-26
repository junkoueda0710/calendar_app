<?php 

require 'db.php'; 

require 'vendor/autoload.php'; 

 

use Yasumi\Yasumi; 

 

// 月の取得 

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); 

$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m'); 

$first_day = date('w', strtotime("$year-$month-01")); 

$last_day = date('t', strtotime("$year-$month-01")); 

 

// 前後の月 

$prev_year = $month == 1 ? $year - 1 : $year; 

$prev_month = $month == 1 ? 12 : $month - 1; 

$next_year = $month == 12 ? $year + 1 : $year; 

$next_month = $month == 12 ? 1 : $month + 1; 

 

// 祝日 

$holidays = Yasumi::create('Japan', $year,'ja_JP'); 

?> 

<!DOCTYPE html> 

<html lang="ja"> 

<head> 

    <meta charset="UTF-8"> 

    <title><?php echo $year; ?>年<?php echo $month; ?>月 カレンダー</title> 

    <style> 

        table { border-collapse: collapse; width: 100%; } 

        th, td { border: 1px solid #ccc; width: 14%; height: 100px; text-align: left; vertical-align: top; position: relative; } 

        th { background-color: #eee; } 

        .sunday { background-color: #fdd; color: red; } 

        .saturday { background-color: #ddf; color: blue; } 

        .holiday { background-color: #fdd; color: red; } 

        .weekday { background-color: #fff; color: black; } 

        .event { display: block; padding: 2px 4px; margin: 2px 0; border-radius: 4px; color: #fff; font-size: 12px; } 

        .赤 { background-color: #e74c3c; } 

        .青 { background-color: #3498db; } 

        .緑 { background-color: #2ecc71; } 

        .黄色 { background-color: #f1c40f; } 

        .オレンジ { background-color: #e67e22; } 

        a { text-decoration: none; color: inherit; } 

        a:hover { text-decoration: underline; } 

    </style> 

</head> 

<body> 

    <h1><?php echo $year; ?>年<?php echo $month; ?>月 カレンダー</h1> 

 

    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;"> 

 

        <a href="?year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>">前の月</a> 

        <a href="?year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>">次の月</a> 

    </div> 



    <table> 

        <tr> 

            <th class="sunday">日</th> 

            <th>月</th> 

            <th>火</th> 

            <th>水</th> 

            <th>木</th> 

            <th>金</th> 

            <th class="saturday">土</th> 

        </tr> 

        <?php 

        $day = 1; 

        for ($week = 0; $week < 6; $week++) { 

            echo '<tr>'; 

            for ($week_day = 0; $week_day < 7; $week_day++) { 

                if ($week === 0 && $week_day < $first_day) { 

                    echo '<td></td>'; 

                } elseif ($day > $last_day) { 

                    echo '<td></td>'; 

                } else { 

                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day); 

                    $dateObj = new DateTimeImmutable($date); 

                    $isHoliday = $holidays->isHoliday($dateObj); 

                    $holidayName = ''; 

 

                    // 祝日の名前を取得（エラーなし・バージョン対応） 

                    if ($isHoliday) { 

                        foreach ($holidays->getHolidays() as $holiday) { 

                            if ($holiday->format('Y-m-d') === $date) { 

                                $holidayName = $holiday->getName(); 

                                break; 

                            } 

                        } 

                    } 

 

                    $class = ''; 

                    if ($isHoliday) { 

                        $class = 'holiday'; 

                    } elseif ($week_day == 0) { 

                        $class = 'sunday'; 

                    } elseif ($week_day == 6) { 

                        $class = 'saturday'; 

                    } else { 

                        $class = 'weekday'; 

                    } 

 

                    echo '<td class="' . $class . '">'; 

                    echo '<a href="list.php?date=' . $date . '">' . $day . '</a><br>'; 

 

                    if ($holidayName) { 

                        echo '<span style="font-size:10px; color:red;">' . htmlspecialchars($holidayName) . '</span>'; 

                    } 

 

                    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_date = ? ORDER BY start_time"); 

                    $stmt->execute([$date]); 

                    $events = $stmt->fetchAll(); 

                    foreach ($events as $event) { 

                        echo '<span class="event ' . htmlspecialchars($event['category']) . '">' . htmlspecialchars($event['title']) . '</span>'; 

                    } 

 

                    echo '</td>'; 

                    $day++; 

                } 

            } 

            echo '</tr>'; 

            if ($day > $last_day) { 

                break; 

            } 

        } 

        ?> 

    </table> 

</body> 

</html> 