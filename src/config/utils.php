<?php

function addSuccessMsg($message){
    $_SESSION['message'] = [
        'type' => 'success',
        'message' => $message
    ];
}

function addErrorMsg($message){
    $_SESSION['message'] = [
        'type' => 'error',
        'message' => $message
    ];
}