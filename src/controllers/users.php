<?php
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

requireValidSession(true);

$exception = null;
if (isset($_GET['delete'])) {
    try {
        User::deleteById($_GET['delete']);
        addSuccessMsg("Usuário foi excluído com sucesso!");
    }catch (Exception $e) {
        if (stripos($e->getMessage(), ' FOREIGN KEY')) {
            addErrorMsg("Não foi possível excluir o usuário com registro de ponto.");
        }else {
            $exception = $e;
        }
    }
}

$users = User::get();

foreach ($users as $user) {
    $user->start_date = (new DateTime($user->start_date))->format('d/m/Y');
    if ($user->end_date) {
        $user->end_date = (new DateTime($user->end_date))->format('d/m/Y');
    }
}


loadTemplateView('users', compact('users', 'exception'));
