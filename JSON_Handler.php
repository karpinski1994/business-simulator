<?php

    session_start();
    
    $json_budget = $_GET['budget'];
    $budget = json_decode($json_budget);

    $total = $budget->{'total'};
    $active = $budget->{'act'};
    $passive = $budget->{'pas'};

    print $total.'   '.$active.'     '.$passive;

    $login = $_SESSION['user'];
     //sprawdzenie czy login/email juz istnieje
        require_once "connect.php";
        
        //try catch
        //spróbuj kod przechwyc bledy
        
        //raportowanie tylko wyjatkow
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $connection = new mysqli(
                            $host,
                            $db_user,
                            $db_password,
                            $db_name
                        );
            if ( $connection->connect_errno != 0 ){
                //rzucamy wyjatkiem zeby catch sobie złapal wyjatek
                throw new Exception($mysqli_connect_errno());
            }else{

                    if($connection->query("UPDATE users SET total_budget = '$total', active_income = '$active', passive_income = '$passive' WHERE login = '$login'")){
                        header('Location: index.php');
                        
                    }else{
                        throw new Exception($connection->error);
                    }
                }
                $connection->close();
            }catch(Exception $error){
            echo '<p class="text-danger">Server\'s error. Please try again later.</p>';
            echo '<br/>Error\'s info: '.$error;
        }
    session_unset();
?>