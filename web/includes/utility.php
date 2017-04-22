<?php 

include ($CLASS_CONNECTION);

class Utility {


	public function upload($name,$size,$temp_name,$ext,$ex_req,$max_size) {

	    	$ext=strtolower($ext);
	        if (($ext==$ex_req) and $size<$max_size)
	        {
	        $root='C:/Spark/spark/bin/neuraxis';	        	
	        $dir="/storage/";
	        $location=$root.$dir;
	    	move_uploaded_file($temp_name,$location.$name);
	        return $location.$name;
	        }
	        
			else
			{
				return 2;
			}
		
	}

	public function openfile($file,$ch){
		$myfile = fopen($file, "r");
		$line=fgets($myfile);
		$fields=explode($ch,$line);
		return $fields;
}

	public function opentsv($file) {
		$myfile = fopen($file, "r");
		$line=fgets($myfile);
		$fields=preg_split("/[\t]/", $line);
		return $fields;
	}
}
?>