<?php
    session_start();
    
    if(!isset($_SESSION['logged'])){
        header('Location: index.php');
        exit();
    }
    
    /*echo'wellcome '.$_SESSION['user'].'!</br>';
    echo'total : '.$_SESSION['total_budget'].'</br>';
    echo'active : '.$_SESSION['active_income'].'</br>';
    echo'passive : '.$_SESSION['passive_income'].'</br>';*/

    //sesja php

    //pojemnik na zmienne do wykorzystania dla programisty
    //

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAIN</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills float-right">
            <li class="nav-item">
              <a class="nav-link active" href="#">FUTURE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">SKELETON</a>
            </li>
          </ul>
        </nav>
        <h3 class="text-muted text-center"><?php echo $_SESSION['user'].'\'s' ?>  enterprise</h3>
      </div>

      <div class="jumbotron text-center">
        <h3 class="display-3">data</h3>
        <p class="lead"><strong>budget: <span id="current-budget"></span>$</strong></p>
        <p class="lead" >active income: <span id="current-active-income"></span></p>
        <p class="lead">passive income: <span id="current-passive-income"></span></p>
      </div>
      <p><a class="btn btn-lg btn-success center-block" id="work-btn">Work</a></p>
      <div class="row" id="income-container">
        <div class="col-xs-6 quals-container">
         <h3 class="text-muted text-center">Active income</h3>
          <!--<div class="card">
            <div class="card-block">
                <div class="btn btn-s btn-info center-block" id="time-management-btn">Time<br>management</div>
                <p class="text-center">Lvl: 0</p>
                <p class="text-center">Cost: 30$ dla przykladu 10</p>
                <div class="jumbotron jumbotron-fluid">
                    <p class="text-center">This qualification lets you grow up your active income by 1 for every click</p>
                </div>
            </div>
          </div>-->
        </div>
        
        
        <div class="col-xs-6 invs-container">
         <h3 class="text-muted text-center">Passive income</h3>
          <!--<div class="card">
            <div class="card-block">
                <div class="btn btn-s btn-info center-block" id="manual-labourer">Manual<br>Labourer</div>
                <p class="text-center">Lvl: 0</p>
                <p class="text-center">Cost: 1000$ dla przykładu 100</p>
                <div class="jumbotron jumbotron-fluid">
                    <p class="text-center">This investment lets you gain your income every 2 seconds</p>
                </div>
            </div>
          </div>-->
        </div>
      </div>
       
        
      <footer class="footer">
        <p>&copy; Company 2017</p>
      </footer>

    </div> <!-- /container -->
            <!--<form action="logout.php">-->
                <button id="logout-btn" class="btn center-block">Log out</button>
            <!--</form>-->
            <script src="jquery.js"></script>
            <script src="main.js"></script>
           
</body>
</html>