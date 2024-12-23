<?php

session_start();

requireValidSession();


$date = (new DateTime())->getTimestamp();
$today = (new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE,'America/Sao_Paulo'))->format($date);

loadTemplateView('day_records', compact('today'));