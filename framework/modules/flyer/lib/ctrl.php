<?
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");

	$objCalendar = new CalendarTool ();
	$objFlyer	 = new Flyer();
	
	$num	= $_REQUEST['id'];
	$divid	= $_REQUEST['divid'];
	$prop_id = $_REQUEST['propid'];
	list($quantityTitle) = $objFlyer->getQuantityTitle($prop_id);
	$Flyercat	=	$objFlyer->getFlyercat($prop_id);
    $qty= $Flyercat['show_Qty'];
    

switch ($_REQUEST['act']){
	
	case "quantity":
			
		$strs	.= '<div class="divSpc"></div>';
		$strs	.= '<div class="floatleft">';
		$strs	.= '<div class="bodytext">From</div>';
		$strs   .= '<div class="floatleft"><input type="text" class="inputelement" name="fromB[]" id="txt'.$num.'" size="15" onClick="javascript:MovetoCallendar('.$num.');" readonly></div>';
		$strs	.= "</div>";
		$strs   .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),$num,array('FCase'=>'') );
	
		$num	 = ($num + 1);
		$strs	.= '<div class="floatleft">&nbsp;&nbsp;</div>';
		$strs	.= '<div class="floatleft">';
		$strs	.= '<div class="bodytext">To</div>';
		$strs   .= '<div class="floatleft"><input type="text" class="inputelement" name="toB[]" id="txt'.$num.'" size="15" onClick="javascript:MovetoCallendar('.$num.');"   readonly></div>';
		$strs	.= '</div>';
		
		$strs	.= '<div class="floatleft">&nbsp;&nbsp;</div>';
		$strs	.= '<div class="floatleft">';
		if($qty =='Y'){
		$strs	.= '<div class="bodytext">Quantity</div>';
		$strs   .= '<div class="floatleft">';
		
		$strs   .= '<select name="bqty[]">';
		$strs   .= '<option>--Select--</option>';
		foreach($quantityTitle as $rowTitle){
			
			if($row["album_quantity_title_id"] == $rowTitle["id"])
			{
		$strs   .= 	'<option value='.$rowTitle["id"].' selected>'.$rowTitle["title"].'</option>';	
			}else{
		$strs   .= 	'<option value='.$rowTitle["id"].'>'.$rowTitle["title"].'</option>';
			}
			
		}
		$strs   .= '</select>';
		$strs   .= '</div>';
		}else{
			
           $strs	.= '<div class="bodytext">&nbsp;&nbsp;</div>';
            $strs	.= '<div class="floatleft">&nbsp;</div>';	
		}
		$strs   .= '<div class="floatleft">&nbsp;</div>';
		$strs	.= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'myq'.$num.'Div\')"><img src="'.$global['tpl_url'].'/images/icon_delete.gif" border="0"></a></div>';
		$strs	.= '</div>';
		
		$strs	.= '<div style="height:8px" ><!-- --></div>';
		
		$strs   .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),$num,array('FCase'=>'') );
	
		echo $strs."|".$num."|"."q";
		exit;
		break;
		
	case "price":
		
		$strs  .= '<div class="divSpc"></div>';
		$strs  .= '<div class="floatleftwidth120">';
		$strs  .= '<div class="bodytext" style="text-align:left">From</div>';
		$strs  .= '<div>';
		$strs  .= '<input type="text" class="inputelement" name="spFrom[]" id="txtp'.$num.'" onClick="javascript:MovetoCallendar(\'p'.$num.'\');" size="15" readonly>';
		$strs  .= '</div>';
		$strs  .= '</div>';
		$strs  .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"p".$num,array('FCase'=>'') );
		
		$num	= ($num + 1);
		
		$strs  .= '<div class="floatleftwidth120">';
		$strs  .= '<div class="bodytext" style="text-align:left">To</div>';
		$strs  .= '<div><input type="text" class="inputelement" name="spTo[]" id="txtp'.$num.'" onClick="javascript:MovetoCallendar(\'p'.$num.'\');" size="15" readonly></div>';
		$strs  .= '</div>';
		$strs  .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"p".$num,array('FCase'=>'') );
		  
		$strs  .= '<div class="floatleft">';
		$strs  .= '<div class="bodytext" style="text-align:left">Price<b>$</b></div>';
		$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spPrice[]" id="spPrice" size="5"></div>';
		$strs  .= '</div>';
		  
		$strs  .= '<div class="floatleft">';
		$strs  .= '<div class="bodytext">&nbsp;</div>';
	 	$strs  .= '<div class="bodytext">&nbsp;<b>or</b>&nbsp;</div>';
		$strs  .= '</div>';
		   
		$strs  .= '<div class="floatleft" style="text-align:inherit"></div>';
		$strs  .= '<div class="floatleft">';
		$strs  .= '<div class="bodytext" style="text-align:left"><b>%</b></div>';
		$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spPerc[]" id="spPerc" size="5"></div>';
		$strs  .= '<div class="floatleft">&nbsp;</div>';
		$strs  .= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'myp'.$num.'Div\',\'1\')"><img src="'.$global['tpl_url'].'/images/icon_delete.gif" border="0"></a></div>';
		$strs  .= '</div>';
		
		echo $strs."|".$num."|"."p"."|"."1";
		exit;
		break;
		
	case "spprice":
		
		$rsSpecicArr = $objFlyer->specifiPriceForCombo();
		

		$num	= ($num + 1);
		
		$strs  .= '<div class="divSpc"></div>';
		$strs  .= '<div class="floatleftwidth120">';
		$strs  .= '<div class="bodytext">&nbsp;</div>';
		$strs  .= '<div class="floatleft">';
		$strs  .= '<select name="specific_id[]">';
		$strs  .= '<option>--Select--</option>';
		foreach ($rsSpecicArr as $row){
		$strs  .= '<option value='.$row["id"].'>'.$row["title"].'</option>';
		}
		$strs  .= '</select>';
		$strs  .= '</div>';
		$strs  .= '</div>';
		
		$strs  .= '<div class="floatleftwidth50">';
		$strs  .= '<div class="bodytext">Price<b>$</b></div>';
		$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spePrice[]"  size="5"></div>';
		$strs  .= '</div>';	
					 
		$strs  .= '<div class="floatleft">';
		$strs  .= '<div class="bodytext">&nbsp;</div>';
		$strs  .= '<div class="bodytext">&nbsp;<b>or</b>&nbsp;</div>';
		$strs  .= '</div>';
						   
		$strs  .= '<div class="floatleftwidth80">';
		$strs  .= '<div class="bodytext"><b>%</b></div>';
		$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spePerc[]" size="5"></div>';
		$strs  .= '<div class="floatleft">&nbsp;</div>';
		$strs  .= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'mysp'.$num.'Div\',\'2\')"><img src="'.$global['tpl_url'].'/images/icon_delete.gif" border="0"></a></div>';
		$strs  .= '</div>';
		
		
		echo $strs."|".$num."|"."sp"."|"."2";
		exit;
	case "auctionBid":
		
		echo $objFlyer->printBidAuctionField($divid,$objCalendar);
		exit;
		break;
	case "sendMail":
		
		$req = $_REQUEST;
		$bodyHtml .= "Name :".$req["first_name"]."<br>";
		$bodyHtml .= "Email :".$req["email"]."<br>";
		$bodyHtml .= "Primary User Type :".$req["mem_type"]."<br>";
		$toAddress = "notify@rentility.com";//$framework->config["rental_entity_email"];
		$fromAddress = $req["email"];
		$mailFrom = $req["email"];
		$mailSubject = "New Prospects";
		
		mimeMail($toAddress, $mailSubject, $bodyHtml, strip_tags($bodyHtml), '', $mailFrom);
		redirect("http://www.rentility.com/index.html?status=y");
		break;
}
?>
