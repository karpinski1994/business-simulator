
<?php
    session_start();
   /* if((isset($_SESSION['logged']))  && ($_SESSION[logged]==true)){
        //natychmiastowe przejscie 
        header('Location: game.php');
        exit();
    }*/
    //jesli jest ust. 1 zmienna to wiadomo ze zostal clickniety submit
    //jesli pole jest puste isset sprawdza czy zostal ustawiony pojemnik (ram) na wartosc
    //pusta wartosc tez rezerwuje pojemnik w pamieci
       
    if (isset($_POST['email'])){
          //Udana walidacja zalozmy ze tak
        $validation_complete = true;
        //sprawdzenie loginu
        $login = $_POST['login'];
        $first_name = $_POST['first'];
        $last_name = $_POST['last'];
        
        //sprawdzenie długosci loginu
        if ((strlen($login)<5) || (strlen($login)>20)) {
            $validation_complete = false;
            $_SESSION['e_login'] = 'Login has to be between 5 and 20 characters long.';
        }
        //alfanumerycznosc loginu
        if(ctype_alnum($login) == false){
            $validation_complete = false;
            $_SESSION['e_login'] = 'Login can contain only letters and numbers.';
        }
        
        //email
        $email = $_POST['email'];
        $email_safe = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($email_safe, FILTER_VALIDATE_EMAIL) == false) || ($email_safe != $email)){
            $validation_complete = false;
            $_SESSION['e_email'] = 'Use correct email, please.';
        }
        
        
        //password 8-20 letters and same 
        $password_first = $_POST['password-first'];
        $password_second = $_POST['password-second'];
        if ((strlen($password_first)<8) || (strlen($password_first)>20)) {
            $_SESSION['e_password'] = 'Password has to be between 5 and 20 characters long.';
        }
        if ($password_first != $password_second) {
            $validation_complete = false;
            $_SESSION['e_password'] = 'Both passwords have to be the same.';
        }
        
        //hashed passwords
        /*$password_hash = password_hash($password_first, PASSWORD_DEFAULT);
        echo $password_hash; exit();*/
        
        
        if(!isset($_POST['terms'])){
            $validation_complete = false;
            $_SESSION['e_terms'] = 'Terms and conditions have to be accepted.';
        }
        
        //bot or not
        
        $secret_code = '6Lfz6DoUAAAAAEIDn9X0LpVdQyHwbCGJ7b8uZm03';
        
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_code.'&response='.$_POST['g-recaptcha-response']);
        
        
        $response = json_decode($check);
        
        if($response->success == false){
            $validation_complete = false;
            $_SESSION['e_capcha'] = 'Please verify capcha.';
        }
        
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
                //czy email jest w bazie
                $result = $connection->query("SELECT user_id FROM users WHERE email='$email'");
                if(!$result) throw new Exception($connection->error);
                
                $mail_counter = $result->num_rows;
                if($mail_counter > 0){
                    $validation_complete = false;
                    $_SESSION['e_mail'] = 'Terms and conditions have to be accepted.';
                }
                //czy login jest w bazie
                $result = $connection->query("SELECT user_id FROM users WHERE login='$login'");
                if(!$result) throw new Exception($connection->error);
                
                $login_counter = $result->num_rows;
                if($login_counter > 0){
                    $validation_complete = false;
                    $_SESSION['e_login'] = 'This login has been already taken.';
                }
                if($validation_complete == true) {
                    //walidacja zakonczyla sie powodzeniem
                    //dodaj uzytkownika do bazy1
                    //insert
                    if($connection->query("INSERT INTO users VALUES(NULL, '$login', '$password_first', '$first_name', '$last_name', '$email', 1 , 0 , 1 , 0 )")){
                        $_SESSION['validation_confirmed'] = true;
                        header('Location: wellcome.php');
                        
                    }else{
                        throw new Exception($connection->error);
                    }
                }
                $connection->close();
            }

        }catch(Exception $error){
            echo '<p class="text-danger">Server\'s error. Please try again later.</p>';
            echo '<br/>Error\'s info: '.$error;
        }
        
        
        
        
        
        
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAIN</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <!--what we need to run-->
          <main>
              <div class="jumbotron text-center">
               <h3>Sign up</h3>
               <br>
               <form method="POST"> <!--invisible-->
                   <input class="form-control" type="text" name="login" placeholder="Login"><br>
                   <?php
                    if(isset($_SESSION['e_login'])){
                        echo '<p class="text-danger">'.$_SESSION['e_login'].'</p>';
                        unset($_SESSION['e_login']);
                    }
                   ?>
                   <input class="form-control" type="password" name="password-first" placeholder="Password"><br>
                   <input class="form-control" type="password" name="password-second" placeholder="Repeat password"><br>
                   <?php
                    if(isset($_SESSION['e_password'])){
                        echo '<p class="text-danger">'.$_SESSION['e_password'].'</p>';
                        unset($_SESSION['e_password']);
                    }
                   ?>
                  <input class="form-control" type="email" name="email" placeholder="Email"><br>
                   <?php
                    if(isset($_SESSION['e_email'])){
                        echo '<p class="text-danger">'.$_SESSION['e_email'].'</p>';
                        unset($_SESSION['e_email']);
                    }
                   ?>
                   <input class="form-control" type="text" name="first" placeholder="Firstname"><br>
                   <input class="form-control" type="text" name="last" placeholder="Lastname"><br>
                   
                   <label>
                        Accept terms and conditions.
                       <input class="form-control" type="checkbox" name="terms">
                       <?php
                    if(isset($_SESSION['e_terms'])){
                        echo '<p class="text-danger">'.$_SESSION['e_terms'].'</p>';
                        unset($_SESSION['e_terms']);
                    }
                   ?>
                   </label><br><br>
                   <div class="g-recaptcha" data-sitekey="6Lfz6DoUAAAAAAz6zlvIMl3_kG72jsANKExM8N_p"></div>
                   <?php
                    if(isset($_SESSION['e_capcha'])){
                        echo '<p class="text-danger">'.$_SESSION['e_capcha'].'</p>';
                        unset($_SESSION['e_capcha']);
                    }
                   ?>
                   <br><br>
                   
                   
                   
                   
                   <br>
                   <button class="btn btn-primary"type="submit">Sign up</button>
               </form>
               </div>
               <a class="text-center" href="index.php"><p class="text-center">Back</a>
           </main>
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</body>
</html>
