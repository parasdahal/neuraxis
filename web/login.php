<?php
require_once('config.php'); 
include($CLASS_USER);
  $user=new User;
      if ($user->isLoggedIn()==1) {
       header('Location: user/index.php'); 
      }      
  if(isset($_POST['e-mail'])&&isset($_POST['pswd']))
    {
       $email=$_POST['e-mail'];
       $pswd=md5($_POST['pswd']);

       $result=$user->logIn($email,$pswd);
      if($result==0) {
        header('Location: user/index.php');
      }
      }
  
?>
<html lang="en">
  <head>
    <title>Neuraxis</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="stylesheet/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="page-header myhead"><Center>NEURAXIS</Center></div>

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
      <img class="img-responsive" src="assets/head.jpg">
    </div>
<!-- --------------------END OF HEADER ----------------------------------- !-->

    <div class="container">
    <div id="legend">
      <legend class="ctitle">Login</legend>
      </div>
      <div class="col-sm-8">
        <br>
        <div class="message">
        <?php 
       if (isset($result) and $result==1) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>email / Password does not match</strong></div> '; }

        else if (isset($result) and $result==2) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>email / Password cant be blank</strong></div> '; }
?>
        </div>  
        <form class="form-horizontal" role="form" method="post" action="login.php">
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="email" name="e-mail" placeholder="Enter email">
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">Password</label>
    <div class="col-sm-10"> 
      <input type="password" class="form-control" id="pwd" name="pswd" placeholder="Enter password">
    </div>
  </div>

  
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Login</button>
    </div>
  </div>
</form>
    </div>
    </div>
          <div class="panel panel-default">
         <div class="panel-body"> <center>Neuraxis &copy; 2016 </center></div>
    </div>
  </body>
  </html>
  