<?
# testshiptrack.php - test use of shiptrack class - note all shipping numbers are not valid.
	
	ini_set('display_errors', 'On');
	ini_set('display_errors', E_ALL);
	
    include "shiptrack.inc.php";
    $MyLink = new ShipTrack();
   
    $Link1	=	$MyLink->PrintLink("UPS","1234324324","1","","_blank","foobar");
    echo "$Link1<br>";
	
    $Link2	=	$MyLink->PrintLink("FEDEX","234234234","<font face=\"arial\"><B>FEDEX</B></font>","","_blank");
    echo "$Link2<br>";
	
    #$MyLink->DEBUG=1;
	$Link3	=	$MyLink->PrintLink("CCX","EI_053_12345675","1","","_blank");
	echo "$Link3<br>";

	#$MyLink->DEBUG=1;
	$Link4	=	$MyLink->PrintLink("USPS","EI_053_12345675","1","","_blank");
	echo "$Link4<br>";

?>
