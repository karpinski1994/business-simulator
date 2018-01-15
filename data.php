<?php
    session_start();



    $login = $_SESSION['user'];

    $password = $_SESSION['password'];

    $conn = mysqli_connect('localhost', 'root', '','game');


    $sql_user_query = "SELECT * FROM users WHERE login='$login' AND password='$password'";

    $result = mysqli_query($conn, $sql_user_query);


    $row = mysqli_fetch_assoc($result);
    $data = $row;
    

    echo json_encode($data);
?>