<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<?php 

/**
* Simple Example Landing Page.
*
* To work around a nasty problem with MSIE generated Page Expired errors we are
* posting to a separate page. If we post back to the same page MSIE 6.0 generates
* Page Expired or Operation Aborted errors on some servers after a seemingly
* random number of iterations.
*/

// Commented out for the time being. This line seems related to the 
// Page Expired and Operation Aborted errors we were seeing on some 
// servers when loaded from MSIE 6
//
//  

?>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Simple Example of AreaEdit Landing page.</title>

  <link rel="stylesheet" href="examples.css" />

</head>

<body>

<h1>AreaEdit Simple Example Results</h1>

<hr>

	<?php

	// for this example we'll just display the content of the form as submitted.

	print( stripslashes($_POST["TextArea1"]) );


	?>

<hr>

  <a href="./simple_example.php">Back to Simple Example</a>

  <br>
  <br>

  <a href="../index.html">Back to Index</a>

<br>
</body>
</html>
