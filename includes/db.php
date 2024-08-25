<?php 
/* Database Parameters */
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "BlogWebSite";

/* Connection to database */
if (!$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
    die("Connection failed");
}
?>