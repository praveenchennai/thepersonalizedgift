<?php
print "<br>";
# testshiptrack.php - test use of shiptrack class - note all shipping numbers are not valid.
    include "shiptrack.inc";
    $MyLink = new ShipTrack();
    $MyLink->PrintLink("UPS","1Z963AR51241954778","1","","_blank","foobar");
    echo "<br>";
	$MyLink->DEBUG=1;
    $MyLink->PrintLink("FEDEX","999999999999","<font face=\"arial\"><B>FEDEX</B></font>","","_blank");
    echo "<br>";
    $MyLink->DEBUG=0;
    $MyLink->PrintLink("USPS","9999 9999 9999 9999 9999 99","1","","_blank","foobar");

?>
