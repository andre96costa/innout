<?php
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

requireValidSession(true);

$activeUsersCount = User::getActiveUsersCount();
$absentUsers = WorkingHour::getAbsentUsers();
$yearAndMonth = (new DateTime())->format('Y-m');
$seconds = WorkingHour::getWorkedTimeInMonth($yearAndMonth);

$hoursInMonth = explode(':', getTimeStringFromSeconds($seconds))[0];

loadTemplateView('manager_report', compact('activeUsersCount', 'absentUsers', 'hoursInMonth'));
