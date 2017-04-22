<?php 
require('../config.php');
include($CLASS_ADMIN);
$admin=new LibAdmin;

  $id=$_SESSION['lib'][0];
  $getname=$admin->fetchByField ('User_Fname','user','User_Id',$id);
  $name=$getname['User_Fname'];
  
$users=$admin->fetchUsers();
$requests=$admin->fetchRequests();

if(isset($_GET['return'])) {
  $return=$_GET['return'];
}

if(isset($_GET['approve'])) {
  $approve= $_GET['approve'];
}

if(isset($_GET['deny'])) {
  $deny= $_GET['deny'];
}

$loggedin=$admin->isLoggedIn();
?>




<html lang="en">
    <head>
    <title>Online Library - Admin</title>
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

    <div class="container">
    
    <?php if ($loggedin==0) {
      echo'<div id="legend">
      <legend class="ctitle">Please Login as Admin</legend>
      </div><div></div><div class="panel panel-default">
  <div class="panel-body"> <center>Onine Library &copy; 2016 </center></div>
    </div>'; 
    die();
    }
    ?>
     
      <div class="container">
    <div id="legend">
      <legend class="ctitle"><?php echo 'Welcome '.$name; ?></legend>
      </div> 

      <?php
        if (isset($return) and $return==1) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span></strong></div> '; }

        else if (isset($return) and $return==0) {
          echo '<div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-ok-sign"></span> User Deleted Successfully !</strong></div> '; }

          if (isset($approve) and $approve==0) {
          echo '<div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-ok-sign"></span> User approved! </strong></div> '; }

          if (isset($approve) and $approve==2) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-info-sign"></span> User with same email already exists! </strong></div> '; }

           if (isset($deny) and $deny==0) {
          echo '<div class="alert alert-warning fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><span class="glyphicon glyphicon-ok-sign"></span> User Regestration Denied! </strong></div> '; }
      ?>


<div class="col-md-3"> 
  <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="tab" href="#borrow">Regestration Requests</a></li>
  <li><a data-toggle="tab" href="#return">Current Users</a></li>
</ul>
</div>

<div class="col-md-9"> 
<div class="tab-content">
  <div id="borrow" class="tab-pane fade in active">
    <h3>Registration Requests</h3>
    <table class="table table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Request Date</th>
        <th>Approve</th>
        <th>Deny</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if ($requests==1) {
      echo '<tr><td>No Requests </td></tr>';
    }
    else{
    foreach($requests as $item) {
      echo'<tr>
      <td>'.$item['User_Fname'].'</td> 
      <td>'.$item['User_Lname'] .'</td>
      <td>'.$item['User_Email'].'</td>
      <td>'.date('M jS Y',$item['Req_Date']).'</td>
      <td> <a href="../admin/approveReq.php?rid='.$item['User_Id'].'"><button type="button" class="btn btn-default btn-md">Approve</button></a></td>

      <td> <a href="../admin/denyReq.php?rid='.$item['User_Id'].'"><button type="button" class="btn btn-danger btn-md">Deny</button></a></td>
      </tr>';
    }
  }

     ?>
    </tbody>
    </table>
  </div>
  <div id="return" class="tab-pane fade">
    <h3>User List</h3><br>
    <table class="table table-striped">
    <thead>
        <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>User Type</th>
        <th>Join Date</th>
        <th>Valid Till</th>
        <th>Delete User</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if ($users==1) {
      echo '<tr><td>No Users</td></tr>';
    }
    else{
    foreach($users as $item) {
      echo'<tr>
      <td>'.$item['User_Fname'].'</td>
      <td>'.$item['User_Lname'].'</td>
      <td>'.$item['User_Email'].'</td>
      <td>'.$item['User_Type'].'</td>
      <td>'.date('M jS Y',$item['Reg_Date']).'</td>
      <td>'.date('M jS Y',$item['Valid_Date']).'</td>
      <td> <a href="deleteUser.php?uid='.$item['User_Id'].'"><button type="button" class="btn btn-danger btn-md">Delete</button></a></td>
      </tr>';
    }
  }

     ?>
    </tbody>
    </table>

  </div>
</div>
</div>
  </div> <!-- container -->
  <div class="panel panel-default">
  <div class="panel-body"> <center>Onine Library &copy; 2016 </center></div>
</div>

  </body>
  </html>