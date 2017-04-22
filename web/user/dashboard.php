<?php 
require_once('../config.php'); 
include($CLASS_USER);
$id=$_GET['instance'];
$user= new User;
$data=$user->fetch("*","instance","id",$id);
$name=$data['name'];

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
		<div class="page-header myhead"><Center>NEURAXIS</Center></div>

		<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="../about.php">About</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php if (isset($_SESSION['neuraxis'])) { 
            echo '<li><a href="../redirect.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
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
<!--------------------------------END OF HEADER------------------------------------------!-->

<div class="container">
  <div id="legend">
      <legend class="ctitle">Instance Dashboard</legend>
  </div>

  <div class=col-sm-8>
  <h4>Instance Name: <?php echo $data['name'];?></h4>
  <h4>State: <?php echo $data['state'];?></h4>
  <h4>Date Created: <?php echo $data['created_date'];?></h4>
  </div>

  <div class=col-sm-4>
  <div class="row">
    <?php echo '<a href="serve.php?id='.$id.'"><button type="button" class="btn btn-success btn-md">Serve</button></a>'?>

  <?php echo '<a href="train.php?id='.$id.'"><button type="button" class="btn btn-primary btn-md">Train</button></a>'?>

  <?php echo '<a href="stop.php?id='.$id.'"><button type="button" class="btn btn-warning btn-md">Stop</button></a>'?>

  <?php echo '<a href="delete.php?id='.$id.'"><button type="button" class="btn btn-danger btn-md">Delete</button></a>'?>
  </div>
  </div>
<div class="row"><br></div>
<div class="row"><br></div>
<div class="row"><br></div>

<div class="row">
<div class=col-sm-8>
  <!--Here goes viz-->
  </div>
</div>
      </div> <!--container!-->
      <div class="row"></div>
	    <div class="panel panel-default">
  <div class="panel-body"> <center>Neuraxis &copy; 2016 </center></div>
    </div>
  </body>

</html>