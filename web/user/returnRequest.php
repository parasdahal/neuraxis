<?php 
require_once('../config.php'); 
include($CLASS_MEMBER);

$member=new Member;

$borrowId=$_GET['brid'];
$userId=$_GET['uid'];
$bookId=$_GET['bid'];
$id=$_GET['id'];

$return=$member->returnBook($borrowId,$userId,$bookId);
header('Location:index.php?id='.$id.'&return='.$return);
?>