<?php
session_start();

if (isset($_SESSION['login'])) {
    $session_user_login = $_SESSION['login'];
} else {
    $session_user_login = "Пользователь";
}
if (isset($_SESSION['firstname'])) {
    $session_user_firstname = $_SESSION['firstname'];
} else {
    $session_user_firstname = "Имя";
}
if (isset($_SESSION['lastname'])) {
    $session_user_lastname = $_SESSION['lastname'];
} else {
    $session_user_lastname = "Фамилия";
}
if (isset($_SESSION['privilege'])) {
    $session_user_privilege = $_SESSION['privilege'];
} else {
    $session_user_privilege = "пользователь";
}

if ($session_user_privilege != "администратор") {
    header("Location: ../index.php");
}
?>