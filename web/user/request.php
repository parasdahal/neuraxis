<?php 
require_once('../config.php'); 
include($CLASS_MEMBER);

if (isset($_SESSION['lib'])) {
    $uid=$_SESSION['lib'][0];
  }

$bid=$_GET['bid'];
$member=new Member;
$request=$member->requestBook($uid,$bid);

header('Location:../catalog/book.php?id='.$bid.'&req='.$request);
?>