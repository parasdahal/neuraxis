<?php
require_once('../config.php'); 
include($CLASS_USER);
include ($CLASS_UTILITY);

$user=new User;
$Function=new Utility;
  $isLoggedIn=$user->isLoggedIn();

  if(isset($_POST['iname'])&&isset($_FILES['dataset_file']))
    { 
       $iname=$_POST['iname'];
       $route=$_POST['route'];
       
        $r_f_name=$_FILES['dataset_file']['name'];
        $r_name = $_SESSION['neuraxis'].'_sen_'.$iname.'.tsv';
        $r_temp_name = $_FILES['dataset_file']['tmp_name'];
        $r_ext = pathinfo($r_f_name, PATHINFO_EXTENSION);
        $ext_req='tsv';
        $max_size = 128000000;
        $r_size = $_FILES['dataset_file']['size'];

      $upload_data=$Function->upload($r_name,$r_size,$r_temp_name,$r_ext,$ext_req,$max_size);
        
      if ($upload_data==2 ) {
        header('Location:sentiment.php?id=1');
      }
      
      else {
        $algo="SentimentClassifier";
        $datasets=array(array("name"=>"training","path"=>$r_name));
        $route=array(array("name"=>"predict","route"=>$route));

        $_SESSION['data']['instance']=$iname;
        $_SESSION['data']['algo']=$algo;
        $_SESSION['data']['datasets']=$datasets;
        $_SESSION['data']['route']=$route;

        $dataset_fields=$Function->opentsv($upload_data);
        
      }
      }
      else {
        header('Location:sentiment.php');
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
          <legend class="ctitle">Sentiment Classifier Instance</legend> 
       </div>    

<div class="col-sm-8">
<h4>Please Select label Column dataset</h4><br/>
 <form class="form-horizontal" role="form" method="post" action="sentiment_2.php" enctype="multipart/form-data">
          
  <div class="form-group">
            <label class="control-label col-sm-2" for="labelCol">Review Column</label>
    
    <div class="col-sm-10">
      <select name="reviewCol" required="true" class=form-cotrol>
      <?php for($i=0;$i<count($dataset_fields);$i++) echo '<option>'. $dataset_fields[$i] .'</option>'; ?>
      </select>
  </div>
</div>

  <div class="form-group">
            <label class="control-label col-sm-2" for="labelCol">Label Column</label>
    
    <div class="col-sm-10">
      <select name="labelCol" required="true" class=form-cotrol>
      <?php for($i=0;$i<count($dataset_fields);$i++) echo '<option>'. $dataset_fields[$i] .'</option>'; ?>
      </select>
  </div>
</div>

<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </div>
</form>
</div>
</div>
<div class=row></div>
        <div class="panel panel-default">
          <div class="panel-body">
            <center>Neuraxis &copy; 2016 </center>
          </div>
        </div>
  </body>

  </html>