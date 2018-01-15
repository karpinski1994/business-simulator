<?php

    //php session id - losowy identyfikator przesylany za pomoca ciasteczek by get - odczytywany jest konkretny plik z danymi sesji
    session_start();

    if(!(isset($_POST['login'])) || !(isset($_POST['password']))){
        header('Location: index.php');
        exit();
    }

    require_once'connect.php';
    //require inczej obsluguje bledy
    //gdy include nie moze dolaczyc pliku generuje jedynie ostrzezenie a skrypt bedzie wykonywal sie dalej
    //require wygeneruje blad krytyczny i dalsze wykonywanie zostanie wstrzymane
    //_once -> te same funkcje, ale once sprawdza czy plik zostal juz wczytany,
    //jesli nie to dolaczy jesli juz zostal to nie 


    //laczenie z baza danych
    //mysql_ - zdeprecjonowane

    //mysqli - konstruktor

    //@ - wyciszanie bledow

    

    $connection = @new mysqli(
        $host,
        $db_user,
        $db_password,
        $db_name
    );

    if ( $connection->connect_errno != 0 ){
        echo "Error: ".$connection->connect_errno;/*."Desc: ".$connection->connect_error;*/
    }else{
        
        $login = $_POST['login'];

        $password = $_POST['password'];
        //zapytanie cudzyslow, zmienne apostrofy!
        //jesli zapytanie przeszlo (nie bylo w nim bledow)
        //wez zapytanie i wykonaj 
        $sql_user_query = "SELECT * FROM users WHERE login='$login' AND password='$password'";
        if ( $result = @$connection->query($sql_user_query)){
            //how many users are returned by db num_rows ilosc wierszy
            $users = $result->num_rows;
            if($users > 0){
                
                
                //tablica asocjacyjna - (po nazwach a nie indexach) $row['email]
                $row = $result->fetch_assoc();
                $_SESSION['logged'] = true;
                $_SESSION['user'] = $row['login'];

                $_SESSION['password'] = $row['password'];
                $_SESSION['total_budget'] = $row['total_budget'];
                $_SESSION['active_income'] = $row['active_income'];
                $_SESSION['passive_income'] = $row['passive_income'];


                unset($_SESSION['error']);
                $result->free_result();
                header('Location: game.php');
            }else{
                $_SESSION['error'] = '<span style="color:red"> Wrong login or password.</span>';
                header('Location: index.php');

            }
        }
        //zamykanie polaczenia z baza po wykonaniu kodu
        $connection->close();
    }

?>

