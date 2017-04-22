<?php 
require_once('../config.php'); 
include($CLASS_ADMIN);

$admin=new LibAdmin;

$uid=$_SESSION['lib'][0];
$id=$_GET['uid'];

$delete=$admin->deleteUser($id);
echo $delete;
header('Location:index.php?id='.$uid.'&return='.$delete);
?>