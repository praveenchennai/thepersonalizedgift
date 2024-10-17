<?php

phpinfo();

/*

ini_set('display_errors', 'on');
error_reporting(E_ALL ^ E_NOTICE);

mysql_connect("localhost", "theperson", "perso213gift") or
    die("Could not connect: " . mysql_error());
mysql_select_db("thepersonalizedgift_new");


$result = mysql_query("select  * from  order_session_value order by id desc  limit 200");

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo $row['id']."<br>";

	echo base64_decode($row['sess_value'])."<br>";
	
}
*/




?>
