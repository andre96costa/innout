<?php

session_start();
requireValidSession();


$user = $_SESSION['user'];
$records = WorkingHour::loadFromUserAndDate($user->id, date('Y-m-d'));

try {
    $currentTime = (new DateTime())->format('H:i:s');
    if ($_POST['forcedTime']) {
        $currentTime = $_POST['forcedTime'];
    }
    
    $records->innout($currentTime);
    addSuccessMsg('Ponto inserido com succeso!');
} catch (AppException $e) {
    addErrorMsg($e->getMessage());
}

header("Location: day_records.php");
