<?php
  include ('admin.php');
  $admin=new LibAdmin;

  $isLoggedIn=$admin->isLoggedIn();


  if(isset($_POST['FName'])&&isset($_POST['LName'])&&isset($_POST['e-mail'])&&isset($_POST['pswd'])&&isset($_POST['pswd_again'])&&isset($_POST['utype']))
    {
       $f_name=$_POST['FName'];
       $l_name=$_POST['LName'];
       $email=$_POST['e-mail'];
       $pswd=$_POST['pswd'];
       $pswd_again=$_POST['pswd_again'];
       $utype=$_POST['utype'];

       $result=$admin->addUser($f_name,$l_name,$email,$pswd,$pswd_again,$utype);
  }
  else {
    $result=4;
  }
?>


<html lang="en">
    <head>
    <title>Online Library - Sign up</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../stylesheet/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="page-header myhead"><Center>ONLINE LIBRARY</Center></div>
    <?php $admin->nav();?>
    <div class="img-head"> 
      <img class="img-responsive" src="../assets/head.jpg">
    </div>
<!-- --------------------END OF HEADER ----------------------------------- !-->
<?php
 if($isLoggedIn==0) {
    echo '<div class="container">
    <div id="legend">
      <legend class="ctitle">Please sign in as admin</legend>
      </div></div></body></html>';
      die();
 }
?>

    <div class="container">
    <!-- <div class="ctitle">USER SIGN UP</div>  -->
    <div id="legend">
      <legend class="ctitle">REGISTER A USER</legend>
      </div>
      <div class="col-sm-8">
        <br>
        <div class="message">
        <?php 

        if(isset($result) and $result==0) {
          echo '<div class="alert alert-success fade in" >
        <a href="#" class="close" data-dismiss="alert" aria-lebel="close">&times;</a>
          <strong><span class="glyphicon glyphicon-ok-sign"></span>User added successfully!</strong> </div>" '; }

      else if (isset($result) and $result==1) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>Passwords do not match</strong></div> '; }

        else if (isset($result) and $result==2) {
          echo '<div class="alert alert-info fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>Email already registered</strong></div> '; } 
        
          else if(isset($result) and $result==3) {
          echo '<div class="alert alert-danger fade in" >
        <a href="#" class="close" data-dismiss="alert" aria-lebel="close">&times;</a>
          <strong><span class="glyphicon glyphicon-remove-sign"></span>Error adding user</strong> </div>" '; }
        
        else if(!isset($result)){
          echo '<div class="alert alert-info fade in" >
        <a href="#" class="close" data-dismiss="alert" aria-lebel="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span>All Fields required</strong> </div>" ';
        }
        ?>

        </div>  
        <form class="form-horizontal" role="form" method="post" action="signup.php">
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="FirstName">First Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="FirstName" name="FName" placeholder="Enter First Name">
    </div>
  </div>

  <div class="form-group">
            <label class="control-label col-sm-2" for="LastName">Last Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="LastName" name= "LName" placeholder="Enter Last Name">
    </div>
  </div>

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
    <label class="control-label col-sm-2" for="pwd">Retype Password</label>
    <div class="col-sm-10"> 
      <input type="password" class="form-control" id="pwd" name="pswd_again" placeholder="Enter password Again">
    </div>
  </div>

  <div class="form-group">
      <label class="control-label col-sm-2" for="utype">User Type</label> 

 <div class="col-sm-10">
  <select class="form-control" id="user_type" name="utype">
    <option value="1">Amin</option>
    <option value="2">Librarian</option>
  </select>
</div>
</div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Register</button>
    </div>
  </div>
</form>
    </div>
      <div class="col-sm-4">
      </div>
    </div>
    <div class="panel panel-default">
  <div class="panel-body"> <center>Onine Library &copy; 2016 </center></div>
    </div>
  </body>
  </html>