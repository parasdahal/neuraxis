<?php
require_once('../config.php'); 
include($CLASS_USER);
include($CLASS_UTILITY);

$utility=new Utility;
$user=new User;
  $isLoggedIn=$user->isLoggedIn();
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

    <div class="container">
         <?php
 if($isLoggedIn!=1) {
    echo '<div class="container">
    <div id="legend">
      <legend class="ctitle">Please sign in first</legend>
      </div></div></body></html>';
      die();
 }
?>
    <div id="legend">
      <legend class="ctitle">Neural Network Instance</legend>
      </div>
      <div class="col-sm-8">
        <br>
        <div class="message">
        <?php 
      if (isset($_GET['id']) and $_GET['id']==1) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span> Please choose a valid gz file !</strong></div> '; } 
          ?>

        </div>  
        <form class="form-horizontal" role="form" method="post" action="neural_1.php" enctype="multipart/form-data">
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="Name">Instance Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="iname" name="iname" placeholder="Enter Instance Name" required="true">
    </div>
  </div>

            <div class="form-group">
            <label class="control-label col-sm-2" for="Route">API end point</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="route" name="route" placeholder="Eg: /best" required="true">
    </div>
  </div>

    
    <div class="form-group">
            <label class="control-label col-sm-2" for="dataset_file">Dataset file (gz)</label>
    
    <div class="col-sm-10">
      <input type="file" name="dataset_file" required="true">
  </div>
</div>

 <!-- checkbox start -->
<div class="form-group">
  <label class="control-label col-sm-2" for="sanitizer">Sanitizer Functitions<br/><br/></label>
  
  <div class=sm-10>
  <div class="checkbox">
  <label><input type="checkbox" value="encode_labels" name=sanitizer[]>Encode Labels
    </label>
</div>

<div class="checkbox">
  <label><input type="checkbox" value="int_to_double" name=sanitizer[]>Convert Int to Double
    </label>
</div>

<div class="checkbox">
  <label><input type="checkbox" value="normalize_features" name=sanitizer[]>Normalize Features
    </label>
</div>
</div>
</div>
         
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Next</button>
    </div>
  </div>
</form>
</div>
</div>

  <div class="panel panel-default">
  <div class="panel-body"> <center>Neuraxis &copy; 2016</center></div>
    </div>
  </body>
  </html>