<?php
    session_start();
    if((isset($_SESSION['logged']))  && ($_SESSION[logged]==true)){
        //natychmiastowe przejscie 
        header('Location: game.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Enterprise</title>
    <!--biblioteki i frameworki-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
     <main class="text-center">
         <div class="jumbotron">
         <h3 class="text-center">Wellcome</h3>
         <br>
          <form action="login.php" method="POST">
               <input name="login" placeholder="Login" class="form-control" type="text" ><br>
               <input name="password" placeholder="Password" class="form-control" type="password" ><br>
              <button class="btn btn-primary"type="submit">Log in</button></br>
               <?php
               if(isset($_SESSION['error'])){
                   echo $_SESSION['error'];
               }
              ?>
          </form>
          <br>
        </div>
        <a class="text-center" href="signing.php"><p class="text-center">Sign up</a>
    </main>
     <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</body>
</html>