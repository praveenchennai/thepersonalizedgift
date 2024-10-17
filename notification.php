<?php

//include_once("config.php");

$email = "jinsonpl@gmail.com";



// The subject

$subject = "return from google paypal";


// The message

//$data=$HTTP_RAW_POST_DATA;
$data=10;



mail($email, $subject, $data, "From: $email");



echo "The email has been sent.";


?>