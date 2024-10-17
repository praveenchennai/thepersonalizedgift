<?
	ob_start();
	session_start();
	include_once("config.php");
	include_once("includes/functions.php");
	_dbconnect();	
	include_once("includes/session_check_office.php");
	$user_id = $_SESSION['forumuser1_id'];
	if(isset($_REQUEST['last_id'])){
		$id =$_REQUEST['last_id'];
	}
	if($_REQUEST['ren']){
		$ren=$_REQUEST['ren'];
	}
	//Current Date
	$today=date("Y-m-d");
	$cur_date=$today;
	//For Getting Expery date
	$expQuery="SELECT * FROM ban_banner WHERE id='$id'";
	$rsExp=mysql_query($expQuery);	
	if($rowExp=mysql_fetch_array($rsExp)){
		$expDate=$rowExp['end_date'];		
	}
	//Function for Loadnig card type
	function SelectCardType($slct=""){   
		$fields      =   array("VISA","MC","AMEX SE","DISCOVERS","DINERS/DB","JCB");
		$fieldsCap   =   array("VISA","MASTER CARD","AMEX SE","DISCOVERS","DINERS/DB","JCB");
		echo "<option value=\"0\">----Select---</option>";
		for($i=0;$i< count( $fields); $i++ ){ 	
			$id      =   trim($fields[$i]);
			$name    =   trim($fieldsCap[$i]);
			if($id==$slct)$slctd  =   " selected";
			else $slctd           =   "";
			echo "<option value=\"$id\"$slctd>$name</option>\n";
		}
	}
	 $sysid          =   $_SESSION['sessmemid'];
	//This is for displaying Exchange plans	
	$banner ="SELECT a.id as ban_id,a.plan_id,a.banner_name,DATE_FORMAT(a.start_date,'%m -%d -%Y') as start_date ,DATE_FORMAT(a.end_date,'%m -%d -%Y') as end_date ,a.module,b.* FROM ban_banner a,ban_plans b WHERE a.plan_id=b.id AND a.id=$id";
	$rs=mysql_query($banner);
	if($row=mysql_fetch_array($rs)){
		$ban_id		 = $row['ban_id'];
		$ban_name 	 = $row['banner_name'];
		$plan_name 	 = $row['plan_name'];
		$duration 	 = $row['duration'];
		$planAmount  = $row['plan_price'];
		$startDate	 = $row['start_date'];
		$endDate	 = $row['end_date']; 
		$module		 = $row['module'];
		if($module=='O'){
			$dispBan_id="BO".$ban_id;
		}else if($module=='E'){
			$dispBan_id="BE".$ban_id;
		}
	}
	//Getting  Userdetails	
	$sql                =   mysql_query("select * from user where usr_id = '$user_id'");
	$result            	=	mysql_fetch_array($sql);			
	$sesId              =   session_id();  
	$fname				=	$result["usr_fname"];
	$lname				=	$result["usr_lname"];
	$name				=	$fname." ".$lname;
	$x_address			=	$result["usr_addr1"];
	$x_city				=	$result["usr_city"];
	$x_state			=	$result["usr_state"];
	$x_country			=	$result["usr_country"];
	$x_zip				=	$result["usr_zip"];
	$x_email			=	$result["usr_email"];
	$user_email 		=	$result['usr_email'];
	$x_phone			=	$result["usr_phone"];
	$x_cust_id			=	$result["usr_id"];
	
	$SelCountry="SELECT * FROM countries WHERE country_id='$country_id'";
	$rsCountry=mysql_query($SelCountry);
		if($rowCountry=mysql_fetch_array($rsCountry)){
			$countryName=$rowCountry['country_name'];
		}
		$SelState="SELECT * FROM countries WHERE country_id='$x_state'";
		$rsState=mysql_query($SelState);
		if($rowState=mysql_fetch_array($rsState)){
			$stateName=$rowState['country_name'];
		}
		$SelCity="SELECT * FROM countries WHERE country_id='$x_city'";
		$rsCity=mysql_query($SelCity);
		if($rowCity=mysql_fetch_array($rsCity)){
			$cityName=$rowCity['country_name'];
		}		
	if((isset($_REQUEST['btn_save']))&&($_REQUEST['btn_save']!="")){
		$x_amount			=	$_REQUEST["amount"];
		$x_card_num			=	$_REQUEST["creditCard"];
		$x_exp_month		=	$_REQUEST["expMonth"];
		$x_exp_year			=	$_REQUEST["expYear"];
		$x_exp_date			=	$x_exp_month."".$x_exp_year;
		$cvvCode			=	$_REQUEST["cvc"];
		$duration  			=	$_REQUEST['duration'];
		$id					=   $_REQUEST['id'];
			
		$DEBUGGING			= 	1;				# Display additional information to track down problems
		$TESTING			= 	1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES		= 	2;				# Number of transactions to post if soft errors occur	
			
		$auth_net_login_id			= "286pUAuj2";     //"benamram202";
		$auth_net_tran_key			= "5vY5x66PaX2dDe3v";	
		$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
		
		$authnet_values				= array
		(
			"x_login"				=> 	$auth_net_login_id,
			"x_version"				=> 	"3.1",
			"x_delim_char"			=> 	"|",
			"x_delim_data"			=> 	"TRUE",
			"x_url"					=> 	"FALSE",
			"x_type"				=> 	"AUTH_CAPTURE",
			"x_method"				=> 	"CC",
			"x_tran_key"			=> 	$auth_net_tran_key,
			"x_relay_response"		=> 	"FALSE",
			"x_card_num"			=> 	$x_card_num,
			"x_exp_date"			=> 	$x_exp_date,
			"x_amount"				=> 	$x_amount,
			"x_first_name"			=> 	$fname,
			"x_last_name"			=> 	$lname,
			"x_address"				=> 	$x_address,
			"x_city"				=> 	$x_city,
			"x_state"				=> 	$x_state,
			"x_zip"					=> 	$x_zip,
			"x_invoice_num"			=>	$sesId,
			"x_cust_id"				=>	$x_cust_id,
			"x_test_request"		=>	"FALSE",				
		 );	   	
		 $fields = "";
		 foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		 ///////////////////////////////////////////////////////////		
		if($x_amount!="" && $x_amount!="0"){						
				 $ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); // URL of gateway for cURL to post to
				 curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // Returns response data instead of TRUE(1)
				 curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields, "& ")); // use HTTP POST to send form data
				 $resp = curl_exec($ch); //execute post and get results
				 curl_close ($ch);				
				 $strArry	=	explode("|",$resp);					
				 $reason		=	$strArry[3];
				if($strArry[0]==1 || $strArry[0]==111){
					if($ren){
							$msg="Account Successfully Renewed";												
						if($expDate>$cur_date){			
							$updateQuery = mysql_query("update ban_banner set end_date=DATE_ADD(end_date, INTERVAL $duration day) where id=".$id);
						}else if($expDate<$cur_date || $expDate==""){
							$updateQuery = mysql_query("update ban_banner set end_date=DATE_ADD(curdate(), INTERVAL $duration day) where id=".$id);
						}
					}else{
						$msg="Transaction successfully completed";
						$updateQuery = mysql_query("update ban_banner set start_date='$today',status ='A',end_date=DATE_ADD(start_date, INTERVAL $duration day) where id=".$id);
					}	
					mysql_query($updateQuery);
					//For getting expareDate
					$bannerExpaire ="SELECT DATE_FORMAT(start_date,'%m -%d -%Y') as start_date ,DATE_FORMAT(end_date,'%m -%d -%Y') as end_date  FROM ban_banner  WHERE id=$id";
					$rsExpire=mysql_query($bannerExpaire);
					if($rowExpaire=mysql_fetch_array($rsExpire)){
						$startdate	=	$rowExpaire['start_date'];
						$expdate	=	$rowExpaire['end_date'];
					}
					//Sending Mail
					$fromemail = getfrommail();
						if($ren==""){
							$mailSubject	= "24/7 Main Street Transaction Confirmation";
							$head			="24/7 Main Street Transaction Confirmation";
						}else{
						 	$mailSubject	= "24/7 Main Street Renewal Confirmation";
							$head="24/7 Main Street Renewal Confirmation";
						}
 						$strEmail  = "";
						$strEmail .= '<html>';
						$strEmail .= '<head>';
						$strEmail .= '<title>Welcome to 24/7 Main Street!</title>';
						$strEmail .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
						$strEmail .= '<style type="text/css">';
						$strEmail .= '<!--';
						$strEmail .= 'body,td,th {';
						$strEmail .= 'font-family: Verdana, Arial, Helvetica, sans-serif;';
						$strEmail .= 'font-size: 12px;';
						$strEmail .= 'color: #000000;';
						$strEmail .= '}';
						$strEmail .= 'body {';
						$strEmail .= 'background-color: #C0CBDC;';
						$strEmail .= 'margin-left: 0px;';
						$strEmail .= 'margin-top: 20px;';
						$strEmail .= '}';
						$strEmail .= 'h1,h2,h3,h4,h5,h6 {';
						$strEmail .= 'font-weight: bold;';
						$strEmail .= '}';
						$strEmail .= 'h1 {';
						$strEmail .= 'font-size: 12px;';
						$strEmail .= '}';
						$strEmail .= '-->';
						$strEmail .= '</style>';
						$strEmail .= '</head>';
						$strEmail .= '<body>';
						$strEmail .= '<table width="600" border="1"  align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';						
						$strEmail .= '<tr>';
						$strEmail .= '<td><table width="100%" border="0" cellspacing="9" cellpadding="0">';
						$strEmail .= '<tr>';
						$strEmail .= '<td bgcolor="#CCCC99" height="20"> <strong>'.$head.' </strong> </td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Dear &nbsp;'.$name.', </td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Thank you for participating in 247mainstreet.com. Your files have been successfully uploaded and your banners is now ready for viewing.</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td align="left">Below is a summary of your transaction: </td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td> Banner ID Number: &nbsp;&nbsp;'.$dispBan_id.' </td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Banner Name:&nbsp;&nbsp;'.$ban_name.'</td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Plan Name:&nbsp;&nbsp;'.$plan_name.'</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Start Date:&nbsp;&nbsp;'.$startdate.'</td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td> Expiration Date:&nbsp;&nbsp;'.$expdate.'</td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Transaction Total:&nbsp;&nbsp;$'.$planAmount.'</td>';
						$strEmail .= ' </tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>&nbsp;</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td bgcolor="#CCCC99" height="20"> <strong>Billing Information</strong> </td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Name:&nbsp;&nbsp;'.$name.'</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Company:&nbsp;&nbsp;'.$tenentName.'</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td>Address:&nbsp;&nbsp;'.$x_address.'<br>'.$cityName.'<br>'.$stateName.'<br>'.$countryName.'<br>'.$zip.'</td>';
						$strEmail .= '</tr>';
						$strEmail .= '<tr>';
						$strEmail .= '<td align="left"> Again, thank you for participating.  </td>';
						$strEmail .= '</tr>';						
						$strEmail .= '<tr>';
						$strEmail .= '<td align="left">Sincerely,</td>';
						$strEmail .= '</tr>';						
						$strEmail .= '<tr>';
						$strEmail .= '<td align="left"> <strong>24/7 Main Street Team</strong> </td>';
						$strEmail .= ' </tr>';
						$strEmail .= '</table></td>';
						$strEmail .= '</tr>';
						$strEmail .= '</table>';
						$strEmail .= '</body>';
						$strEmail .= '</html>';																	
						$headers   = "From: ".$fromemail . " \r\n";
						$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";						
						$ok = @mail($user_email,$mailSubject,$strEmail,$headers);
			
				}
				if($ok){
					_redirect("myaccount_office.php?userid=".$user_id."&msg=1");
				}  
			} 
	} 	
?>
<html>
<head>
<title>home</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Page-Enter" content="blendTrans(Duration=1.0)">
<META   HTTP-EQUIV="Page-Exit" CONTENT = "blendTrans(Duration=1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #334b72;
}
-->
</style>
<link href="css/style_office.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {COLOR: #132c43; FONT-FAMILY: Verdana; TEXT-DECORATION: none; font-size: 11px;}
-->
</style>
</head>
<link href="css/style_office.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (home.psd) -->
<table width="800" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><table width="100%" height="180"  border="0" cellpadding="0" cellspacing="0" background="images/top_headerbg.jpg">
      <tr valign="top">
        <td width="10" align="left"><img src="images/top_headerleft.jpg" width="10" height="180" alt=""></td>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="130">
				<?
				$page_name = "OtherSitePage";
				if(isset($_REQUEST['selCategory'])){
					$catId	= $_REQUEST['selCategory'];
				}else{		
					$catId	= 0;
				}
				$module	=	'O';
				$val=getBanners($page_name,3,0,$module);
			?>
			<? include_once("includes/header_logo_user.php");?>
			</td>
          </tr>
          <tr>
            <td height="28" align="right"><? include_once("header_office_home.php");?></td>
          </tr>
          <tr>
		 	<td height="18" align="right" valign="middle" class="blueblod"> 
			 <? include_once("header_advertise.php");?>
			</td>	
          </tr>
        </table></td>
        <td width="10" align="right"><img src="images/top_headerright.jpg" width="10" height="180" alt=""></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="800" height="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="162" height="100%" valign="top"><? include_once("left_office.php");?></td>
        <td width="638" height="100%" valign="top"><table width="634" height="483"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td width="10" height="10" align="left" valign="top"><img src="images/blackbox_topleft.jpg" width="10" height="10" alt=""></td>
            <td width="615" background="images/blackbox_topborder.jpg"></td>
            <td width="10" align="right" valign="top" background="images/blackbox_topborder.jpg"><img src="images/blackbox_topright.jpg" width="10" height="10" alt=""></td>
          </tr>
          <tr>
            <td background="images/blackbox_leftborder.jpg"></td>
            <td align="center" valign="top">
			<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="2%">&nbsp;</td>
                  <td width="96%">&nbsp;</td>
                  <td width="2%">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td rowspan="2">                   
				   <form action="" name="formadd" method="POST" enctype="multipart/form-data">
				     <table width="98%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5ECF2"class="border3" >
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">&nbsp;</td>
                         <td class="bodytext">&nbsp;</td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td width="44%" align="right" class="bodytext">Banner Name : </td>
                         <td class="bodytext"> <? echo $ban_name;?></td>
                       </tr>
                       <tr>
                         <td width="2%" height="25">&nbsp;</td>
                         <td align="right" class="bodytext"> Plan Name : </td>
                         <td width="54%" class="bodytext"><? echo $plan_name;?></td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td  class="bodytext" align="right">Duration : </td>
                         <td class="bodytext"><? echo $duration;?>(Days)
                             <input type="hidden" name="current_date" value="<?=$today?>">
                             <input type="hidden" name="duration" value="<?=$duration?>">
                             <input type="hidden" name="id" value="<?=$id;?>">
                             <input name="ren" type="hidden" id="ren" value="<?=$ren?>">
                         </td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td class="bodytext" align="right">Total ($) : </td>
                         <td class="bodytext"><?=$planAmount;?>
&nbsp;</td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">Credit Card Type : </td>
                         <td class="bodytext"><select name="cardType" class="input">
                             <?=SelectCardType('');?>
                         </select></td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">&nbsp;</td>
                         <td class="bodytext"><img src="images/image002.jpg" width="197" height="70" border="0"></td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">Name on Card : </td>
                         <td class="bodytext"><input name="nameonCard" type="text" class="input" size="28"></td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">Credit Card Number : </td>
                         <td class="bodytext"><input name="creditCard" type="text" class="input" id="creditCard2" size="28"></td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">CVV Code : </td>
                         <td class="bodytext"><input name="cvc" type="text" class="input" id="cvc2" size="28">
                             <img src="images/help.jpg" border="0" onClick="window.open('showcvv.php?id=<?=$videopath['id']?>','','width=500,height=500')"> </td>
                       </tr>
                       <tr>
                         <td height="25">&nbsp;</td>
                         <td align="right" class="bodytext">Credit Card Expiration : </td>
                         <td class="bodytext"><select name="expMonth" class="input">
                             <option value="">MM</option>
                             <option value="1">1</option>
                             <option value="2">2</option>
                             <option value="3">3</option>
                             <option value="4">4</option>
                             <option value="5">5</option>
                             <option value="6">6</option>
                             <option value="7">7</option>
                             <option value="8">8</option>
                             <option value="9">9</option>
                             <option value="10">10</option>
                             <option value="11">11</option>
                             <option value="12">12</option>
                           </select>
                             <select name="expYear" class="input">
                               <option value="">YYYY</option>
                               <?
							$currYear	=	date("Y");
							$yearLimit	=	$currYear+10;
							for($i=$currYear;$i<=$yearLimit;$i++)
							{								
						?>
                               <option value="<?=$i?>">
                               <?=$i?>
                               </option>
                               <?
							}
						?>
                             </select>
                         </td>
                       </tr>
                       <tr>
                         <td height="27">&nbsp;</td>
                         <td colspan="2" align="center" class="bodytext">
                           <input type="hidden" name="id" value="<?=$id?>">
                           <input type="hidden" name="x_country" value="US">
                           <input type="hidden" name="amount" value="<?=$planAmount?>">
                           <INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">
                           <INPUT type="hidden" name="x_test_request" value="TRUE">
                           <input type="hidden" name="x_relay_response" value="TRUE">
                           <INPUT TYPE=HIDDEN NAME="x_relay_url" VALUE="http://newagesme.com/avi/view_plan.php">
                           <input type="hidden" name="x_ship_to_country" value="US">
                         </td>
                       </tr>
                       <tr>
                         <td height="73">&nbsp;</td>
                         <td colspan="2" align="center" class="bodytext"><input type="submit" name="btn_save" value="Submit"></td>
                       </tr>
                     </table>
				     </form>
				  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="391">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
			  </td>
            <td background="images/blackbox_leftborder.jpg">&nbsp;</td>
          </tr>
          <tr>
            <td height="10" align="left" valign="bottom"><img src="images/blackbox_bottomleft.jpg" width="10" height="10" alt=""></td>
            <td background="images/blackbox_bottomborder.jpg"></td>
            <td align="right" valign="bottom"><img src="images/blackbox_bottomright.jpg" width="10" height="10" alt=""></td>
          </tr>
		  <?php  $footval=getBanners($page_name,10,0,$module); ?>
		  <? include_once("includes/advt.php");?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><? include_once("footer_office.php");?></td>
  </tr>
</table>
<!-- End ImageReady Slices -->
</body>
</html>