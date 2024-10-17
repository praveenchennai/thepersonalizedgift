<?php
    require('html_to_pdf.inc.php');
    $htmltopdf = new HTML_TO_PDF();
  //  $htmltopdf->saveFile("abc.pdf");
	 $htmltopdf->saveFile("/var/www/html/sawitonline/pdfflyers/9.pdf");
	// $htmltopdf->downloadFile("abc.pdf");
    $result = $htmltopdf->convertURL("http://newagesme.com/sawitonline/htmlflyers/44.html");
	//$result = $htmltopdf->convertURL("http://newagesme.com/sawitonline/index.php?sess=bW9kPWZseWVyJnBnPXByZXZpZXcmYWN0PWZseWVyX3ByZXZpZXcmZmx5ZXJfaWQ9MQ==");
	
    if($result==false)
        echo $htmltopdf->error();
?> 