<?

// Before working with this sample code, please be sure to read the accompanying Readme.txt file.
// It contains important information regarding the appropriate use of and conditions for this
// sample code. Also, please pay particular attention to the comments included in each individual
// code file, as they will assist you in the unique and correct implementation of this code on
// your specific platform.
//
// Copyright 2007 Authorize.Net Corp.

class ARBAuthnet extends FrameWork
{
//Function to send XML request via fsockopen
function send_request_via_fsockopen($host,$path,$content)
{
   $posturl = "ssl://" . $host;
   $header = "Host: $host\r\n";
   $header .= "User-Agent: PHP Script\r\n";
   $header .= "Content-Type: text/xml\r\n";
   $header .= "Content-Length: ".strlen($content)."\r\n";
   $header .= "Connection: close\r\n\r\n";
   $fp = fsockopen($posturl, 443, $errno, $errstr, 30);
   if (!$fp)
   {
      $response = false;
   }
   else
   {
      error_reporting(E_ERROR);
      fputs($fp, "POST $path  HTTP/1.1\r\n");
      fputs($fp, $header.$content);
      fwrite($fp, $out);
      $response = "";
      while (!feof($fp))
      {
         $response = $response . fgets($fp, 128);
      }
      fclose($fp);
      error_reporting(E_ALL ^ E_NOTICE);
   }
   return $response;
}

//Function to send XML request via curl
function send_request_via_curl($host,$path,$content)
{
   $posturl = "https://" . $host . $path;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $posturl);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   $response = curl_exec($ch);
   return $response;
}


//Function to parse Authorize.net response
function parse_return($content)
{
   $refId = $this->substring_between($content,'<refId>','</refId>');
   $resultCode =  $this->substring_between($content,'<resultCode>','</resultCode>');
   $code =  $this->substring_between($content,'<code>','</code>');
   $text =  $this->substring_between($content,'<text>','</text>');
   $subscriptionId =  $this->substring_between($content,'<subscriptionId>','</subscriptionId>');
   return array ($refId, $resultCode, $code, $text, $subscriptionId);
}

//Helper function for parsing response
function substring_between($haystack,$start,$end) 
{
   if (strpos($haystack,$start) === false || strpos($haystack,$end) === false) 
   {
      return false;
   } 
   else 
   {
      $start_position = strpos($haystack,$start)+strlen($start);
      $end_position = strpos($haystack,$end);
      return substr($haystack,$start_position,$end_position-$start_position);
   }
}

function createARBAuthSubscription($store_id,$Params)
{
	//	$host = "apitest.authorize.net"; // for testing
	$host = "api.authorize.net";
	$path = "/xml/v1/request.api";
	// $login = 'cnpdev4289';
	// $transkey = 'SR2P8g4jdEn7vFLQ';
	 // $Params['loginname']   $Params['transactionkey']

	$content =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
        "<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
        "<merchantAuthentication>".
        "<name>" . $Params['loginname'] . "</name>".
        "<transactionKey>" . $Params['transactionkey'] . "</transactionKey>".
        "</merchantAuthentication>".
        "<refId>".$Params['refId'] ."</refId>".
        "<subscription>".
        "<name>".$Params['name'] ."</name>".
        "<paymentSchedule>".
        "<interval>".
        "<length>". $Params['length']."</length>".
        "<unit>".  $Params['unit'] ."</unit>".
        "</interval>".
        "<startDate>" . $Params['startDate'] . "</startDate>".
        "<totalOccurrences>". $Params['totalOccurrences'] . "</totalOccurrences>".
        "<trialOccurrences>".  $Params['trialOccurrences'] . "</trialOccurrences>".
        "</paymentSchedule>".
        "<amount>". $Params['amount'] ."</amount>".
        "<trialAmount>" . $Params['trialAmount'] . "</trialAmount>".
        "<payment>".
        "<creditCard>".
        "<cardNumber>" . $Params['cardNumber'] . "</cardNumber>".
        "<expirationDate>" . $Params['expirationDate'] . "</expirationDate>".
		"<cardCode>" . $Params['cardCode'] . "</cardCode>".
        "</creditCard>".
        "</payment>".
        "<billTo>".
        "<firstName>". $Params['firstName'] . "</firstName>".
        "<lastName>" . $Params['lastName'] . "</lastName>".
		
		"<address>" . $Params['address'] . "</address>".
		"<city>" . $Params['city'] . "</city>".
		"<state>" . $Params['state'] . "</state>".
		"<zip>" . $Params['zip'] . "</zip>".
		"<country>" . $Params['country'] . "</country>".
		
        "</billTo>".
        "</subscription>".
        "</ARBCreateSubscriptionRequest>";
		$response = $this->send_request_via_curl($host,$path,$content);
		return $response;
}

function updateSubscription($Params)
{
//	$host = "apitest.authorize.net"; // for testing
	$host = "api.authorize.net";
	$path = "/xml/v1/request.api";
	 //$login = 'cnpdev4289';
 	// $transkey = 'SR2P8g4jdEn7vFLQ';
	  // $Params['loginname']   $Params['transactionkey']
	  
	$content =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
        "<ARBUpdateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
        "<merchantAuthentication>".
        "<name>" . $Params['loginname'] . "</name>".
        "<transactionKey>" . $Params['transactionkey'] . "</transactionKey>".
        "</merchantAuthentication>".
        "<refId>" . $Params['refId']. "</refId>".
        "<subscriptionId>" .$Params['subscriptionId'] . "</subscriptionId>".
        "<subscription>".
        "<payment>".
        "<creditCard>".
        "<cardNumber>" . $Params['cardNumber'] ."</cardNumber>".
        "<expirationDate>" . $Params['expirationDate'] . "</expirationDate>".
        "</creditCard>".
        "</payment>".
        "</subscription>".
        "</ARBUpdateSubscriptionRequest>";

	//Send the XML via curl
	$response = $this->send_request_via_curl($host,$path,$content);
	
	//If the connection and send worked $response holds the return from Authorize.Net
	/*if ($response)
	{
	 
	   list ($refId, $resultCode, $code, $text, $subscriptionId) =$this->parse_return($response);
	
	   echo " refId: $refId<br>";
	   echo " resultCode: $resultCode <br>";
	   echo " code: $code<br>";
	   echo " text: $text<br>";
	   echo " subscriptionId: $subscriptionId <br><br>";
	   exit;
	}*/
	return $response;


}

function cancelSubscription($login,$transkey,$ref,$subscription_id)
{
		//	$host = "apitest.authorize.net"; // for testing
		$host = "api.authorize.net";
		$path = "/xml/v1/request.api";
	 	//$login = 'cnpdev4289';
 		//$transkey = 'SR2P8g4jdEn7vFLQ';
		

	$content =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
        "<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
        "<merchantAuthentication>".
        "<name>" . $login . "</name>".
        "<transactionKey>" . $transkey . "</transactionKey>".
        "</merchantAuthentication>" .
        "<refId>" . $ref . "</refId>".
        "<subscriptionId>" . $subscription_id . "</subscriptionId>".
        "</ARBCancelSubscriptionRequest>";

	//Send the XML via curl
	$response = $this->send_request_via_curl($host,$path,$content);
	if ($response)
	{
	   list ($refId, $resultCode, $code, $text, $subscriptionId) =$this->parse_return($response);
		if($resultCode=="Ok")
		$this->db->query("DELETE FROM member_authorize_details WHERE subscription_id='$subscription_id'");
	 
	}
	
	return $response;
}

function saveSubscriptionDetails($user_id,$subscription_id)
{
	$this->db->query("DELETE FROM member_authorize_details WHERE user_id='$user_id'");
	$dataArray		=	array("user_id"	=>$user_id,"subscription_id"=>$subscription_id);
	$this->db->insert("member_authorize_details", $dataArray);
	return true;
}

function getSubscriptionId($user_id)
{
		$rs 	=	$this->db->get_row("Select * from member_authorize_details where user_id='$user_id'", ARRAY_A);			
		return $rs;
}

function getSubpackFee($sub_id)
{
		$amount	=	0;
		$rs 	=	$this->db->get_row("Select * from subscription_master where id ='$sub_id'", ARRAY_A);			
		$amount	=	$rs['fees'];
		if($amount=="")
		{ $amount	=	0; }
		return $amount;
}

function getArbUnits($sub_id)
{
		$rs 	=	$this->db->get_row("Select * from subscription_master where id ='$sub_id'", ARRAY_A);			
		return $rs;
}

// this function is used the update the member subscription end date when using ARB
function updateUserSubscription($user_id)
{
	$sql  = "select max(enddate) as enddate,id from member_subscription where user_id=$user_id";
	$rs   = $this->db->get_row($sql);
	if (count($rs)>0)
	{
		$sub_id			=	$rs['id'];
		$cur_enddate	=	$rs['enddate'];
		list($y,$m,$df)	=	split("-",$cur_enddate);
		$d	=	substr($df, 0, 2);
		
		$nextday  		= 	mktime(0, 0, 0, $m +12 , $d ,$y );
		$new_enddate	=	date("Y-m-d H:i:s",$nextday);
		
		$sql2	=	"UPDATE `member_subscription` SET `enddate` = '$new_enddate' where id='$sub_id' AND user_id='$user_id'";
		$this->db->query($sql2);
		return $rs;
	}
	else 
	{
		return false;
	}
}
// end of function used to update the member subscription end date when using ARB

// this function will check wether the user is subscribe for ARB
function getArbStatus($user_id)
{
		$count	=	0;
		$rs 	=	$this->db->get_row("Select * from member_authorize_details where user_id ='$user_id'", ARRAY_A);			
		$count	=	count($rs);
		return $count;

}

// this function will list all the users with ARB details
	function userList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$criteria="=") 
	{
		/*list($qry,$table_id)=$this->generateQry('member_master','d');
		if($search_fields)
		{
			list($qry_cs,$table_id_1)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'m','d');	
		}

		if($stxt=="")
		{
			$sql="SELECT m.*,ma.*,$qry,c.country_name,mad.subscription_id as arb_id,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on 
			m.id=ma.user_id  and ma.addr_type='master'   LEFT JOIN `member_authorize_details` mad on mad.user_id=m.id  left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id";

		}
		else
		{
			$sql="SELECT m.*,ma.*,$qry,c.country_name,mad.subscription_id as arb_id,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on 
			m.id=ma.user_id and ma.addr_type='master'  LEFT JOIN `member_authorize_details` mad on mad.user_id=m.id left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id where (m.`username` like '%$stxt%' 
			OR m.`first_name` like '%$stxt%' OR m.`last_name` like '%$stxt%' OR m.`email` like '%$stxt%')";

		}	
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}*/
		if($stxt=="")
		{
			$sql="SELECT m.*,mad.subscription_id as arb_id,m.id as id FROM `member_master` m  LEFT JOIN `member_authorize_details` mad on mad.user_id=m.id ";
		}
		else
		{
			$sql="SELECT m.*,mad.subscription_id as arb_id,m.id as id FROM `member_master` m  LEFT JOIN `member_authorize_details` mad on mad.user_id=m.id where (m.`username` like '%$stxt%' 
			OR m.`first_name` like '%$stxt%' OR m.`last_name` like '%$stxt%' OR m.`email` like '%$stxt%')";

		}

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
  	}
	// end user list
	
	// cron for upgrading the subscription end date
	function upgradeUserSubscriptionArb()
	{
		//$sql="SELECT m.*,mad.subscription_id as arb_id FROM `member_master` m  LEFT JOIN `member_authorize_details` mad on mad.user_id=m.id " ;
		$sql="SELECT m.*,mad.subscription_id as arb_id FROM `member_master` m , `member_authorize_details` mad where mad.user_id=m.id " ;
		$rs 	=	$this->db->get_results($sql, ARRAY_A);	
		for($i=0;$i<count($rs);$i++)
		{
			//print($rs[$i]['id']); print("<br>");
			$cur_date			=	date("Y-m-d");
			$subscriptionj_id	=	$rs[$i]['sub_pack'];
			$member_id	=	$rs[$i]['id'];
			// retriving subscription details
				$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
				$rs2   = $this->db->get_row($sql2, ARRAY_A);
				if (count($rs2)>0)
				{
					$cur_enddate	=	$rs2['enddate'];
					$old_sub_id		=	$rs2['id'];
					list($y,$m,$df)	=	split("-",$cur_enddate);
					$d	=	substr($df, 0, 2);
					$endDate	=	$y."-".$m."-".$d;
					//print($cur_date);print($endDate);
					if($endDate==$cur_date)
					{
						$duration	=	0;
						$sql3		=	"select * from subscription_master where id='$subscriptionj_id'";
						$rs3  	 	= 	$this->db->get_row($sql3, ARRAY_A);
						$type		=	$rs3['type'];
						$duration	=	$rs3['duration'];
						if($type=="Y")
						{ 
							$nextday  		= 	mktime(0, 0, 0, $m , $d ,$y + $duration ); 
						}
						elseif($type=="M")
						{ 
							$nextday  		= 	mktime(0, 0, 0, $m + $duration , $d ,$y ); 
						}
						elseif($type=="D")
						{ 
							$nextday  		= 	mktime(0, 0, 0, $m , $d + $duration ,$y ); 
						}
						else
						{
							$nextday  		= 	mktime(0, 0, 0, $m , $d  ,$y );
						}
												
						$new_enddate	=	date("Y-m-d H:i:s",$nextday);
						$sql4	=	"UPDATE `member_subscription` SET `enddate` = '$new_enddate' where id='$old_sub_id' AND user_id='$member_id'";
						$this->db->query($sql4);
						//echo " next d ".$new_enddate;
					}
				}
			// end retriving subscription details
		
		}		
		//print_r($rs); exit;
		//return $count;
	}
// cron for upgrading the subscription end date

// end of class
}

?>
