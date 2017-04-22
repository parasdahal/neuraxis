<?php 
@session_start();
try {
	$pdo=new pdo('mysql:host=localhost;dbname=neuraxis2','root','');
}
catch (PDOException $e) {
	exit('DB error');
}
?>