<?php

error_reporting(E_ALL);
include('../config.php');
include($CLASS_USER);
$user=new User;
$id = $_GET['id'];
$name=$user->fetchByField ("name","instance","id",$id); 

$command = 'start /b python C://Spark//spark//bin//neuraxis//manager.py '.$name[0]['name'].' train';
 
try{
    $wd = getcwd();
    chdir('../../');
    pclose(popen($command, 'r'));
    header('Location: /user/index.php');

} catch (Exception $e) {
echo $e->getMessage();
}