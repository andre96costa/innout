<?php
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

requireValidSession(true);

$exception = null;

$userData = [];

if (count($_POST) === 0 && isset($_GET['update'])) {
    $user = User::getOne(['id' => $_GET['update']]);
    $userData = $user->getValues();
    unset($userData['password']);
} else if (count($_POST) > 0) {
    try {
        $newUser = new User($_POST);
        if ($newUser->id) {
            $newUser->update();
            addSuccessMsg('Usuário alterado com sucesso!');
            header("Location: users.php");
            exit();
        }else {
            $newUser->insert();
            addSuccessMsg('Usuário cadastrado com sucesso!');
        }
        $_POST = [];
    } catch (Exception $e) {
        $exception = $e;
    }finally {
        $userData = $_POST;
    }
}

$dados = $userData + ['exception' => $exception];
loadTemplateView('save_user', $dados);