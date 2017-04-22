<?php
require_once('../config.php'); 
include($CLASS_USER);
include ($CLASS_UTILITY);

$user=new User;
$Function=new Utility;
  $isLoggedIn=$user->isLoggedIn();

  if(isset($_POST['iname'])&&isset($_POST['route'])&&isset($_FILES['dataset_file']))
    { 
       $iname=$_POST['iname'];
       $route=$_POST['route'];
       
        $r_f_name=$_FILES['dataset_file']['name'];
        $r_name = $_SESSION['neuraxis'].'_neural_'.$iname.'.gz';
        $r_temp_name = $_FILES['dataset_file']['tmp_name'];
        $r_ext = pathinfo($r_f_name, PATHINFO_EXTENSION);
        $ext_req='gz';
        $max_size = 128000000;
        $r_size = $_FILES['dataset_file']['size'];

        if(isset($_POST['sanitizer'])) {
          $sanitizer=$_POST['sanitizer'];
        }
        else {
          $sanitizer=array();
        }

      $upload_data=$Function->upload($r_name,$r_size,$r_temp_name,$r_ext,$ext_req,$max_size);
        
      if ($upload_data==2 ) {
        header('Location:neural.php?id=1');
      }
      
      else {
        $algo="FullyConnectedNN";
        $datasets=array(array("name"=>"training","path"=>$r_name,"sanitizer"=>$sanitizer,"type"=>"pkl"));
        $route=array(array("name"=>"predict","route"=>$route));

        $_SESSION['data']['instance']=$iname;
        $_SESSION['data']['algo']=$algo;
        $_SESSION['data']['datasets']=$datasets;
        $_SESSION['data']['route']=$route;  
      }
      }
      else {
        header('Location:neural.php');
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

    <script src="../includes/jquery.min.js"></script>
    <style>
            .template {
                display:none;
            }
        </style>

    <script>
        $(document).ready(function () {
            $('#addRow').click(function () {
                $('<div/>', {
                    'class' : 'layer', html: addNewLayer()
            }).hide().appendTo('#tb').slideDown('slow');
                
            });
        })
        function addNewLayer()
        {
            var len = $('.layer').length;
            var $html = $('.template').clone();
            return $html.html();    
        }
        function layerArray()
        {
            var nodes = [];
            $(".layer input").each(function() { nodes.push(parseInt($(this).val())) });
            console.log(nodes.join())
            $('input#layers').val(nodes.join());
             document.forms["form"].submit();
        }
    </script>



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
          <legend class="ctitle">Neural Network Instance</legend> 
       </div>    

<div class="col-sm-8">
<h4>Please specify the parameters</h4><br/>
 <form class="form-horizontal" role="form" method="post" action="neural_2.php" enctype="multipart/form-data" id="form">
          
  <div class="form-group">
            <label class="control-label col-sm-2" for="minisize">Minimum batch size</label>
    
    <div class="col-sm-8">
      <input type="number" class="form-control" id="btsize" name="min_batch_size"  required="true">
    </div>
</div>

<div class="form-group">
            <label class="control-label col-sm-2" for="alpha">Alpha</label>
    
    <div class="col-sm-8">
      <input type="number" class="form-control" id="alpha" name="alpha"  required="true">
    </div>
</div>

<div class="form-group">
            <label class="control-label col-sm-2" for="regparam">Regression Parameter</label>
    
    <div class="col-sm-8">
      <input type="number" step="0.01"class="form-control" id="regparam" name="regparam"  required="true">
    </div>
</div>

<div class="form-group">
            <label class="control-label col-sm-2" for="minisize">Epoches</label>
    
    <div class="col-sm-8">
      <input type="number" class="form-control" id="regparam" name="epoches"  required="true">
    </div>
</div>

<div class="form-group">
            <label class="control-label col-sm-2" for="minisize">Sizes</label>
    
    <div class="col-sm-8 template">
      <input class="form-control" placeholder="No. of nodes in layer" type="number">
        </div>

            <div class="col-sm-8 layer" id="tb">
      <input class="form-control" placeholder="No. of nodes in layer" type="number"></div>  
</div>  
      
<div class="form-group">

    <label class="control-label col-sm-2" for="regparam"></label>

<div class="col-sm-8"><a href="#tb" id="addRow">Add another layer</a> </div> 

<div class="col-sm-8">
      <input type="hidden" id="layers" name="layers" value=""></div>
</div>
</div>
</div>
<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success" onclick="layerArray()">Submit</button>
    </div>
  </div>
</form>
</div>
<div class=row></div>
        <div class="panel panel-default">
          <div class="panel-body">
            <center>Neuraxis &copy; 2016 </center>
          </div>
        </div>
  </body>

  </html>