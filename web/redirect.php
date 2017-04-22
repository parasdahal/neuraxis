<?php 
require('config.php');
include($CLASS_CONNECTION);
if($_SESSION['neuraxis']) {
            header('Location:user/index.php');
        }

        else header('Location:login.php')
               ?>            
