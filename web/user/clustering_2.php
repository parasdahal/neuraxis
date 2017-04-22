<?php
require_once('../config.php'); 
include($CLASS_USER);

$user=new User;
$isLoggedIn=$user->isLoggedIn();

  if(isset($_POST['feature']))
    { 
      $ins_name=$_SESSION['data']['instance'];
      
      $result=array("algorithm"=>$_SESSION['data']['algo'],"parameters"=>array("k"=>(int)$_SESSION['data']['k'],"feature_col"=>$_POST['feature']),"datasets"=>$_SESSION['data']['datasets'],"routes"=>$_SESSION['data']['route']);

      $result_json=json_encode($result);
      $addInstance=$user->addInstance($ins_name,$result_json,'STOPPED');

      if($addInstance==0) {
    header('Location:index.php?return=0');
}
  if($addInstance!=0) {
    header('Location:index.php?return=1');
  }
      }
      else {
        header('Location:logistic.php');
      }
?>


  <html lang="en">

  <head>
    <title>Neuraxis</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../stylesheet/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="page-header myhead">
      <Center>NEURAXIS</Center>
    </div>

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div>
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if (isset($_SESSION['neuraxis'])) { 
            echo '<li><a href="redirect.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';} 
            else echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
              <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Signup</a></li>'; ?>
          </ul>
        </div>
      </div>
    </nav>

    <div class="img-head">
      <img class="img-responsive" src="../assets/head.jpg">
    </div>
    <!-- --------------------END OF HEADER ----------------------------------- !-->
    <?php
 if($isLoggedIn!=1) {
    echo '<div class="container">
    <div id="legend">
      <legend class="ctitle">Please sign in first</legend>
      </div></div></body></html>';
      die();
 }
?>

      <div class="container">
        <div id="legend">
          <legend class="ctitle">Logistic Regression Instance</legend> 
       </div>    
<div class=col-sm-4></div>

<div class=col-sm-4></div>
</div>
<div class=row></div>
        <div class="panel panel-default">
          <div class="panel-body">
            <center>Neuraxis &copy; 2016 </center>
          </div>
        </div>
  </body>

  </html>