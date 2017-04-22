<?php
require_once('config.php'); 
include($CLASS_CONNECTION);
session_destroy();
header('Location: index.php');
?>