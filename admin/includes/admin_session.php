<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $session_user_id = $_SESSION['user_id'];
    $session_user = ['login'=>"Логин", 'firstname'=>"Имя", 'lastname'=>"Фамилия", 'privilege'=>"пользователь"];
    if (userIdValidation($session_user_id)) {
        $session_user = getSessionInfo($session_user_id);
    } else {
        header("Location: ../includes/logout.php");
    }
} else {
    header("Location: ../index.php");
}
?>