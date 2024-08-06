<?php session_start(); ?>

<?php 
$_SESSION['user_id'] = null;
$_SESSION['login'] = null;
$_SESSION['firstname'] = null;
$_SESSION['lastname'] = null;
$_SESSION['email'] = null;
$_SESSION['image'] = null;
$_SESSION['privilege'] = null;

header("Location: ../index.php");
?>