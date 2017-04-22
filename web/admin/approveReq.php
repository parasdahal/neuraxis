<?php 
require_once('../config.php'); 
include($CLASS_ADMIN);

$admin=new LibAdmin;

$requestId=$_GET['rid'];


$approve=$admin->approveRequest($requestId);
header('Location:index.php?approve='.$approve);
?>