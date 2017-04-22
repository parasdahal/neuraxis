<?php
include('../config.php');
include($CLASS_USER);

$user=new User;
$id=$_GET['id'];
$sql=$user->delete('instance',$id);
if($sql==0){
	header('Location:index.php?delete=0');	
}

else {
	// header('Location:index.php?delete=1');
	print_r($sql);	
}	
?>