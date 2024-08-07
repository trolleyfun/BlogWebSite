<?php
session_start();

if (isset($_SESSION['login'])) {
    $session_user_login = $_SESSION['login'];
    $session_user = getSessionInfo($session_user_login);
} else {
    header("Location: ../index.php");
}
?>