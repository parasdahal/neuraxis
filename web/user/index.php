<?php 
require('../config.php');
include($CLASS_USER);
$user=new user;

$loggedin=$user->isLoggedIn();
if(isset($_GET['return'])) {
$return=$_GET['return'];  
}
  $uid=$_SESSION['neuraxis'];
  $instances=$user->fetchByField('*','instance','user_id',$uid);
?>

<html lang="en">
    <head>
    <title>Neuraxis - User </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../stylesheet/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="page-header myhead"><Center>NEURAXIS</Center></div>

    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../about.php">About</a></li>
        </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php if (isset($_SESSION['neuraxis'])) { 
            echo '<li><a href="../redirect.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';} 
            else echo '<li><a href="../login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>'; ?>
      </ul>
    </div>
  </div>
</nav>
    <div class="img-head" > 
      <img class="img-responsive" src="../assets/head.jpg">
    </div>
<!-- --------------------END OF HEADER ----------------------------------- !-->

    <div class="container">

    <?php if ($loggedin==0) {
      echo'<div id="legend">
      <legend class="ctitle">Please Login</legend>
      </div><div></div><div class="panel panel-default">
  <div class="panel-body"> <center>Onine Library &copy; 2016 </center></div>
    </div>'; 
    die();
    } ?>

    <div id="legend">
      <legend class="ctitle">CONTROL CENTER</legend>
      </div> 

      <?php
        if (isset($return) and $return==1) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>Could not create Instance</strong></div> '; }

        else if (isset($return) and $return==0) {
          echo '<div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-ok-sign"></span> Instance Created !</strong></div> '; }
      ?>


<div class="col-md-3"> 
  <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="tab" href="#new">Create New Instance</a></li>
  <li ><a data-toggle="tab" href="#current">Current Instances</a></li>
</ul>
</div>

<div class="col-md-9"> 
<div class="tab-content">
  <div id="new" class="tab-pane fade in active">
    <h3>Create New Instances</h3>
    <table class="table table-striped">
    <thead>
      <tr>
        <th>Applications</th>
        <th>Create</th>
      </tr>
    </thead>
    <tbody>
    <tr>
      <td>Recommendation</td> 
      <td> <a href="recommendation.php"><button type="button" class="btn btn-default btn-md">Create</button></a></td>
      </tr>
    
    <tr>
      <td>Sentiment Classification</td> 
      <td> <a href="sentiment.php"><button type="button" class="btn btn-default btn-md">Create</button></a></td>
    </tr>

      <tr>
      <td>Logistic Regression</td> 
      <td> <a href="logistic.php"><button type="button" class="btn btn-default btn-md">Create</button></a></td>
      </tr>

      <tr>
      <td>Fully Connected Neural Network </td> 
      <td> <a href="neural.php"><button type="button" class="btn btn-default btn-md">Create</button></a></td>
    </tr>

    <tr>
      <td>Clustering </td> 
      <td> <a href="Clustering.php"><button type="button" class="btn btn-default btn-md">Create</button></a></td>
    </tr>

    </tbody>
    </table>
  </div>
  <div id="current" class="tab-pane fade">
    <h3>Current Instances</h3><br>
    <table class="table table-striped">
    <thead>
        <tr>
        <th>Instance Name</th>
        <th>Status</th>
        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($instances==1) {
      echo '<tr><td>No Instances</td></tr>';
    }
    else{
    foreach($instances as $item) {
      echo'<tr>
      <td><a class="dash" href="dashboard.php?instance='.$item['id'].'">'.$item['name'].'</a></td>
      <td>'.$item['state'].'</td>
      <td> <a href="serve.php?id='.$item['id'].'"><button type="button" class="btn btn-default btn-md">Serve</button></a></td>
      <td> <a href="train.php?id='.$item['id'].'"><button type="button" class="btn btn-default btn-md">Train</button></a></td>
      <td> <a href="stop.php?id='.$item['id'].'"><button type="button" class="btn btn-default btn-md">Stop</button></a></td>
      </tr>';
    }
  }

     ?>
    </tbody>
    </table>
  </div>
</div>
</div>
  </div>
   
<div class="panel panel-default">
  <div class="panel-body"> <center>Neuraxis &copy; 2016 </center></div>
    </div>
  </body>
  </html>