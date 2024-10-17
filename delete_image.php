<?php

include_once("config.php");
/***************** Delete one day old files ****************/
$dir = SITE_PATH.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."ajax_editor".DIRECTORY_SEPARATOR."images";
$thumbdir = SITE_PATH.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."ajax_editor".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."thumb";

 
 if (is_dir($dir)) 
 {
	 if ($dh = opendir($dir)) {
		 while ($file = readdir($dh))
		  {
			 if(!is_dir($dir.$file)) {
			 
				 if (filemtime($dir.DIRECTORY_SEPARATOR.$file) <     strtotime('-1 days')) { //if 1 days old, delete
					// echo "Deleting $dir.$file (old)\n"."<br>";
					// echo strlen($file)."<br>";
					 if(strlen($file)>35)
					 {
						 unlink($dir.DIRECTORY_SEPARATOR.$file);
					 }	 
				 }
			 }
		 }
	 }
	 else
	 {
			echo "ERROR. Could not open directory: $dir\n";
	 }
 } 
 else
 {
	 echo "ERROR. Specified path is not a directory: $dir\n";
 }
 
 closedir($dh);
 
// Above script is platform independent.</div>



?>
