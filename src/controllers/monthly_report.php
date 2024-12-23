<?php
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

requireValidSession();

$currentDate = new DateTime();

$user = $_SESSION['user'];
$selectedUserId = $user->id;
$users = [];
if ($user->is_admin) {
    $users = User::get();
    $selectedUserId = $_POST['user'] ? $_POST['user'] : $user->id;
}

$selectedPeriod = $_POST['period'] ? $_POST['period'] : $currentDate->format('Y-m');
$periods = [];

for ($yearDiff = 0; $yearDiff <= 2 ; $yearDiff++) { 
    $year = date('Y') - $yearDiff;
    for ($month = 12; $month >= 1 ; $month--) { 
        $date = new DateTime("$year-$month-1");
        $periods[$date->format('Y-m')] = strftime('%B de %Y', $date->getTimestamp()); 
    }
}

$registries =  WorkingHour::getMonthlyReport($selectedUserId, $selectedPeriod);

$report = [];
$workday = 0;
$sumOfWorkedTime = 0;
$lastday = getlastdayofmonth($selectedPeriod)->format('d');

for ($day=1; $day <= $lastday; $day++) { 
    $date = $selectedPeriod . '-' . sprintf('%02d', $day);
    $registry = '';
    if (isset($registries[$date])) {
        $registry = $registries[$date];
    }
    
    if (isPastWorkday($date)) $workday++;
    
    if ($registry) {
        $sumOfWorkedTime += $registry->worked_time;
        array_push($report, $registry);
    }else {
        array_push($report, new WorkingHour([
            'work_date' => $date,
            'worked_time' => 0
        ]));
    }
}

$expectedTime = $workday * DAILY_TIME;
$balance = getTimeStringFromSeconds(abs($sumOfWorkedTime - $expectedTime));
$sing = $sumOfWorkedTime >= $expectedTime ? "+" : "-";
$balance = "$sing$balance";
$sumOfWorkedTime = getTimeStringFromSeconds($sumOfWorkedTime);

loadTemplateView('monthly_report', compact('report', 'sumOfWorkedTime', 'balance', 'periods', 'users', 'selectedPeriod', 'selectedUserId'));