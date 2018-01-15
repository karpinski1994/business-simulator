<?php
session_start();

include 'dbh.php';

$firstName = $_POST['first'];
$lastName = $_POST['last'];
$userId = $_POST['userid'];
$password = $_POST['password'];

$sql = "INSERT INTO user (first_name, last_name, userid, password)
VALUES ('$firstName', '$lastName', '$userId', '$password')";

$result = mysqli_query($conn, $sql);
header("Location: index.php");
?>