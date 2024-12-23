<?php


Database::executeSql('DELETE FROM working_hours');
Database::executeSql('DELETE FROM users WHERE id > 5');

function getDayTeplatesByOdds($regularRate, $extraRate, $lazyRate) {
    $regularDayTemplate = [
        "time1" => "08:00:00",
        "time2" => "12:00:00",
        "time3" => "13:00:00",
        "time4" => "17:00:00",
        "worked_time" => DAILY_TIME
    ];
    
    $extraHourDayTemplate = [
        "time1" => "08:00:00",
        "time2" => "12:00:00",
        "time3" => "13:00:00",
        "time4" => "18:00:00",
        "worked_time" => DAILY_TIME + 3600
    ];
    
    $lazyDayTemplate = [
        "time1" => "08:30:00",
        "time2" => "12:00:00",
        "time3" => "13:00:00",
        "time4" => "17:00:00",
        "worked_time" => DAILY_TIME - 1800
    ];

    $value = rand(0,100);
    if ($value <= $regularRate) {
        return $regularDayTemplate;
    }else if($value <= $regularRate + $extraRate){
        return $extraHourDayTemplate;
    }else {
        return $lazyDayTemplate;
    }
}

function populateWorkingHour($userId, $initialDate, $regularRate, $extraRage, $lazyRate)
{
    $currentDate = $initialDate;
    $yesterday = new DateTime();
    $yesterday->modify('-2 day');
    $columns = ['user_id' => $userId, 'work_date' => $currentDate];

    while (isBefore($currentDate, $yesterday)) {
        if (!isWeekend($currentDate)) {
            $template = getDayTeplatesByOdds($regularRate, $extraRage, $lazyRate);
            $columns = array_merge($columns, $template);
            $workingHour = new WorkingHour($columns);
            $workingHour->insert();
        }
        $currentDate = getNextDay($currentDate)->format('Y-m-d');
        $columns['work_date'] = $currentDate;
    }
}

$lasMonth = strtotime('first day of last month');
populateWorkingHour(1, date('Y-m-d',$lasMonth), 70, 20, 10);
populateWorkingHour(3, date('Y-m-d', $lasMonth ), 20, 75, 5);
populateWorkingHour(4, date('Y-m-d', $lasMonth ), 20, 10, 70);

echo "Tudo certo";