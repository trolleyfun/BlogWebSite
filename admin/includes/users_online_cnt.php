<?php 
/* For JavaScript function showUsersOnlineCnt() (instant counting users online) */
include "../../includes/db.php";
include "admin_functions.php";
ob_start();
session_start();
echo usersOnlineCnt();
?>