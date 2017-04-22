<?php
$a="col1	col2	col3	col4";
// $b= explode("[\t]",$a);
$b=preg_split("/[\t]/", $a);
print_r($b);
?>