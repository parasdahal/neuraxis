<?php 
require_once('../config.php'); 
include($CLASS_ADMIN);

$admin=new LibAdmin;

$requestId=$_GET['rid'];


$deny=$admin->deleteFromRequest($requestId);
header('Location:index.php?deny='.$deny);