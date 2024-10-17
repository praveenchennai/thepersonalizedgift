<?php

/**
 * @desc The following class abstracts the operation of broker module
 *
 * 
 * 
 * 
 */

class Broker extends FrameWork
{
	
	var $BrokerSearchTotalCount;
	var $UserObj;
	var $FlyerObj;
	
	
	/**
	 * Constructor
	 *
	 * @return Broker
	 */
	function Broker($UserObj, $FlyerObj)
	{
		$this->FrameWork();
		$this->UserObj		=	$UserObj;
		$this->FlyerObj		=	$FlyerObj;
		
		$this->BrokerSearchTotalCount	=	0;
	}
	
	
	/**
	 * The following method returns an associative array which contains broker searched details
	 *
	 * @param string $SearchCriteria [Search criterias concatenated using the comma string]
	 * @return Array, $this->BrokerSearchTotalCount class variable contains the total number of rows returned during search
	 * 
	 */
	function getBrokerSearchDetails($SearchCriteria,$pageNo,$limit,$params,$orderBy = '',$MemberId, $commisionminimum, $commisionmaximum)
	{
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
   $objAlbum    =	new Album();
		$this->BrokerSearchTotalCount	=	0;
		
		/*$SearchQuery	=	"SELECT T1.id AS id 
							FROM member_master AS T1
							LEFT JOIN custom_fields_list AS T2 ON T2.table_key = T1.id WHERE 1 AND T2.field_2='Y' AND T2.table_id = 1 AND T1.id != $MemberId ";*/
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','T2','T1');
		$SearchQuery = "Select T1.*,$qry from member_master T1 $join_qry";	

		$search_fields	=	"first_name,last_name,email,mem_type,isbroker,broker_commision,broker_commision";
		$search_values	=	"$SearchCriteria,$SearchCriteria,$SearchCriteria,0,Y,$commisionminimum,$commisionmaximum";
		$criteria		=	"like,like,like,!=,=,>=,<=";
		$CustomFilter	=	$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'T1','T2',"OR,OR,OR,AND,AND,AND,AND");
		
		$SearchQuery	=	$SearchQuery.' Where '.$CustomFilter[0]." AND T1.id != $MemberId ";
		list($IdArrays,$numpad,$cnt_rs, $limitList)	 = $this->db->get_results_pagewise($SearchQuery, $pageNo, $limit, $params, ARRAY_A, $orderBy);
		 
		
		$BrokerDetails	=	array();
		$ArrIndx		=	0;
		$arindx =0;
		$type="broker";
		foreach($IdArrays as $IdArray) {
			$Id		=	$IdArray['id'];
			$BrokerDetails[$ArrIndx]	=	$this->UserObj->getUserDetails($Id);
			  $bmrate=$objAlbum->getBrokerRate($Id,'Broker');
			  $rate_show= $bmrate['rate']*20;
			 	$BrokerDetails[$ArrIndx][invoice_type]	= "BROKER_COMMISION";
				//$BrokerDetails[$ArrIndx][year_earn]	=$year_earn['year_amt'];
				$BrokerDetails[$ArrIndx][brrate]	=$bmrate['rate'];
				$BrokerDetails[$ArrIndx][bprate]	=$rate_show;
			$ArrIndx++;
			
		}
		return array($BrokerDetails,$numpad,$cnt_rs,$limitList);
		
	}
	
	
	
	/**
	 * The following method assign a property to a broker
	 *
	 * @param POST Array $_POST
	 * @return boolean
	 * 
	 */
	function assignPropertyToBroker($POST, $MemberId)
	{
		extract($POST);
		
		$Now			=	date('Y-m-d H:i:s');
		$InsertArray	=	array(
								'property_id' 			=> 	$PropertyId,
								'property_owner_id' 	=> 	$MemberId,
								'assigned_user_id'		=> 	$BrokerId,
								'assigned_role'			=>	'PROP_BROKER',
								'request_time'			=>	$Now,
								'request_description'	=>	$request_description
							);
		$this->db->insert('propertyassign_relation', $InsertArray);
		
		return true;
	}
	
	
	
	
	/**
	 * The following method returns the assigned properties of a broker
	 *
	 * Author 	:	vimson@newagesmb.com
	 * Craete 	:	20/19/2007
	 * 
	 * @param int $MemberId
	 * 
	 * @return Array containing propety details
	 * Modified by Retheesh on 17/Jan/2008
	 * Addded t1.assigned_role in query
	 */
	function getPropertiesWaitingForApproval($MemberId, $AlbumObj = '', $PhotoObj = '')
	{
		$MyAssignedProperties		=	array();
		
			$Qry1	=	"SELECT 	
						T1.property_id,
						T2.flyer_id,
						T1.relation_id,
						T1.request_description,
						T1.assigned_role,
						T2.*, T3.*, T4.*, 
						T5.form_title AS form_title,
						T6.username AS PropOwnerUsername,
						T6.first_name AS PropOwnerfirst_name,
						T6.last_name AS PropOwnerlast_name
					FROM propertyassign_relation AS T1 
					LEFT JOIN flyer_data_basic AS T2 ON T2.album_id = T1.property_id 
					LEFT JOIN flyer_data_contact AS T3 ON T3.flyer_id = T2.flyer_id 
					LEFT JOIN member_master AS T4 ON T4.id = T2.user_id 
					LEFT JOIN flyer_form_master AS T5 ON T5.form_id = T2.form_id 
					LEFT JOIN member_master AS T6 ON T6.id = T1.property_owner_id 
					WHERE assigned_user_id = '$MemberId'  AND accepted = 'N' AND 
					declined = 'N' ORDER BY T1.property_id DESC ";
		$AssignedProperties	=	$this->db->get_results($Qry1, ARRAY_A);
		
		$ArrIndx	=	0;
		foreach($AssignedProperties as $AssignedProperty) {
			$AlbumId			=	$AssignedProperty['album_id'];
			
			$AlbumDetails		=	$AlbumObj->getAlbumDetails($AlbumId); 
			$DefaultImage		=	$AlbumDetails["default_img"];
			$DefImageExtn		=	$PhotoObj->imgExtension($AlbumDetails["default_img"]);
			$DefaultImgName		=	$DefaultImage.''.$DefImageExtn;
						
			$MyAssignedProperties[$ArrIndx]					=	$AssignedProperty;
			$MyAssignedProperties[$ArrIndx]['DefaultImage']	=	$DefaultImgName;
			$ArrIndx++;
		}
		
		return $MyAssignedProperties;
	}
	
	
	
	/**
	 * The following method accepts the property assignment request to a broker
	 * 
	 * Author 	:	vimson@newagesmb.com
	 * Created	:	20/12/2007
	 * 
	 * @param int $relation_id
	 * @return Array
	 */
	function acceptPropertyAssignRequest($relation_id, $Action, $DeclineDescription)
	{
		$now = date('Y-m-d H:i:s');
		if($Action == 'Accept')
		
			$Qry1	=	"UPDATE propertyassign_relation SET accepted = 'Y' , accept_time = '$now' WHERE relation_id = '$relation_id'";
		
		if($Action == 'Decline')
			$Qry1	=	"UPDATE propertyassign_relation SET declined = 'Y', decline_description = '$DeclineDescription' WHERE relation_id = '$relation_id'";
		
		$this->db->query($Qry1);
		return true;
	}
	
	
	
	/**
	 * The following method returns the properties assigned and approved the request by a broker
	 * 
	 * @param int $MemberId
	 * @param int $pageNo
	 * @param int $limit
	 * @param string $params
	 * @param string $orderBy
	 * @param Object $AlbumObj
	 * @param Object $PhotoObj
	 * @return Array
	 */
	function getAssignedPropertiesOfBroker($MemberId, $pageNo, $limit, $params, $orderBy = '', $AlbumObj = '', $PhotoObj = '')
	{
		$BrokerProperties	=	array();
		
		$Qry1	=	"SELECT 	
						T1.property_id,
						T2.flyer_id,
						T1.relation_id,
						T1.request_description,
						T1.assigned_role,
						T2.*, T3.*, T4.*, 
						T5.form_title AS form_title,
						T6.username AS PropOwnerUsername,
						T6.first_name AS PropOwnerfirst_name,
						T6.last_name AS PropOwnerlast_name
					FROM propertyassign_relation AS T1 
					LEFT JOIN flyer_data_basic AS T2 ON T2.album_id = T1.property_id 
					LEFT JOIN flyer_data_contact AS T3 ON T3.flyer_id = T2.flyer_id 
					LEFT JOIN member_master AS T4 ON T4.id = T2.user_id 
					LEFT JOIN flyer_form_master AS T5 ON T5.form_id = T2.form_id 
					LEFT JOIN member_master AS T6 ON T6.id = T1.property_owner_id 
					WHERE assigned_user_id = '$MemberId'  AND accepted = 'Y' AND 
					declined = 'N' ORDER BY T1.property_id DESC ";
		list($AssignedProperties,$numpad,$cnt_rs, $limitList)	=	$this->db->get_results_pagewise($Qry1, $pageNo, $limit, $params, ARRAY_A, $orderBy);
				
		$ArrIndx	=	0;
		foreach($AssignedProperties as $AssignedProperty) {
			$AlbumId			=	$AssignedProperty['album_id'];
			
			$AlbumDetails		=	$AlbumObj->getAlbumDetails($AlbumId); 
			$DefaultImage		=	$AlbumDetails["default_img"];
			$DefImageExtn		=	$PhotoObj->imgExtension($AlbumDetails["default_img"]);
			$DefaultImgName		=	$DefaultImage.'_thumb2'.$DefImageExtn;
			
			if(!file_exists(SITE_PATH.'/modules/album/photos/thumb/'.$DefaultImgName))
				$DefaultImgName		=	'';
						
			$BrokerProperties[$ArrIndx]						=	$AssignedProperty;
			$BrokerProperties[$ArrIndx]['DefaultImage']		=	$DefaultImgName;
			$ArrIndx++;
		}
		
		return array($BrokerProperties,$numpad,$cnt_rs, $limitList);
	}
	
	
	
	/**
	 * The following method returns the property requests that are declined by the brokers
	 *
	 * @param Int $MemberId
	 * @return Array containg declined property requests
	 */
	function getDeclinedPropertiesOfUser($MemberId)
	{
		$DeclProps	=	array();
		
		$Qry		=	"SELECT 
							T1.relation_id,
							T1.property_id AS property_id, 
							T1.decline_description,
							T2.first_name,
							T2.last_name 
						FROM propertyassign_relation AS T1 
						LEFT JOIN member_master AS T2 ON T2.id = T1.assigned_user_id
						WHERE T1.accepted = 'N' AND T1.declined = 'Y' AND T1.property_owner_id = '$MemberId'";
		$DeclProps	=	$this->db->get_results($Qry, ARRAY_A);
						
		return $DeclProps;
	}
	
	
	
	/**
	 * The following method removes the Property assign request from a user to a broker after the complete request/Response life cycle has been completed
	 *
	 * @param Int $RelationId
	 * @return Array
	 */
	function removePropertyAssignRequestToBroker($RelationId)
	{
		$Qry	=	"DELETE FROM propertyassign_relation WHERE relation_id = '$RelationId'";
		$this->db->query($Qry);
		return true;
	}
	
	
	
	/**
	 * The following method returns the properties of a user assigned to the brokers 
	 * 
	 * @param int $MemberId
	 * @param int $pageNo
	 * @param int $limit
	 * @param string $params
	 * @param string $orderBy
	 * @param Object $AlbumObj
	 * @param Object $PhotoObj
	 * @return Array
	 */
	function getMyAssignedPropertiesOfBroker($MemberId, $pageNo, $limit, $params, $orderBy = '', $AlbumObj = '', $PhotoObj = '', $objUser = '')
	{
		$BrokerProperties	=	array();
		
		 $Qry1	=	"SELECT 	
						T1.property_id,
						T1.relation_id,
						T1.request_description,
						T1.accepted,
						T1.declined,
						T1.assigned_user_id,
						T2.*, T3.*, T4.*, 
						T5.form_title AS form_title,
						T6.username AS PropOwnerUsername,
						T6.first_name AS PropOwnerfirst_name,
						T6.last_name AS PropOwnerlast_name,
						T4.username AS AssignedUsername,
						T4.first_name AS Assignedfirst_name,
						T4.last_name AS Assignedlast_name 
					FROM propertyassign_relation AS T1 
					LEFT JOIN flyer_data_basic AS T2 ON T2.album_id = T1.property_id 
					LEFT JOIN flyer_data_contact AS T3 ON T3.flyer_id = T2.flyer_id 
					LEFT JOIN member_master AS T4 ON T4.id = T1.assigned_user_id  
					LEFT JOIN flyer_form_master AS T5 ON T5.form_id = T2.form_id 
					LEFT JOIN member_master AS T6 ON T6.id = T1.property_owner_id 
					
					WHERE property_owner_id = '$MemberId' AND assigned_role = 'PROP_BROKER' ORDER BY T1.property_id DESC "; #AND accepted = 'Y' AND declined = 'N'
		list($AssignedProperties,$numpad,$cnt_rs, $limitList)	=	$this->db->get_results_pagewise($Qry1, $pageNo, $limit, $params, ARRAY_A, $orderBy);
				//print_r($AssignedProperties);
		$ArrIndx	=	0;
		foreach($AssignedProperties as $AssignedProperty) {
			$BrokerDetails		=	$objUser->getUserdetails($AssignedProperty['assigned_user_id']);
			
			$brokerrate=$AlbumObj->getBrokerRate($AssignedProperty['assigned_user_id'],'broker');
			$brate=$brokerrate['rate'];
		  //$framework->tpl->assign("PRORATE",$prrate['rate']);
		    $rate_show= $brokerrate['rate']*20;
						
			$AlbumId			=	$AssignedProperty['album_id'];
			$AlbumDetails		=	$AlbumObj->getAlbumDetails($AlbumId);
			$DefaultImage		=	$AlbumDetails["default_img"];
			$DefImageExtn		=	$PhotoObj->imgExtension($AlbumDetails["default_img"]);
			$DefaultImgName		=	$DefaultImage.'_thumb2'.$DefImageExtn;
			
			if(!file_exists(SITE_PATH.'/modules/album/photos/thumb/'.$DefaultImgName))
				$DefaultImgName		=	'';
						
			$BrokerProperties[$ArrIndx]							=	$AssignedProperty;
			$BrokerProperties[$ArrIndx]['DefaultImage']			=	$DefaultImgName;
			$BrokerProperties[$ArrIndx]['BrokerDetails']		=	$BrokerDetails;
			$BrokerProperties[$ArrIndx]['BrokerPrate']		    =	$rate_show;
			$BrokerProperties[$ArrIndx]['BrokerRate']			=   $brate;
			$ArrIndx++;
		}
						//print_r($BrokerProperties);
		return array($BrokerProperties,$numpad,$cnt_rs, $limitList);
	}
	
	
	
	/**
	 * The following method returns the Roles assigned to a particular member. It may be Broker, Manager etc..
	 *
	 * @param Int $MemberId
	 * @return Array which contains the roles associated with a particular member
	 */
	function getUserRoles($MemberId)
	{
		$Roles		=	array();
		
		$Qry		=	"SELECT 
							T2.field_2 AS isbroker,
							T2.field_4 AS ispopertymanager 
						FROM member_master AS T1 
						LEFT JOIN custom_fields_list AS T2 ON T2.table_key = T1.id 
						WHERE T2.table_id = 1 AND T1.id = '$MemberId' ";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		
		$Roles['Broker']	=	($Row['isbroker'] == 'Y') ? 'Yes' : 'No';
		$Roles['Manager']	=	($Row['ispopertymanager'] == 'Y') ? 'Yes' : 'No';
		return $Roles;
	}
	
	
	
	/**
	 * The following method returns TRUE if Member already paid deposi fee for that user, else return FALSE
	 *
	 * @author vimson@newagesmb.com
	 * 
	 * @param Int $BrokerId
	 * @param Int $MemberId
	 * @return Boolean
	 */
	function whetherDepositFeePaidToBroker($BrokerId, $MemberId)
	{
		
		$Qry		=	"SELECT COUNT(*) AS RowCount FROM member_deposits WHERE depositor_id = '$MemberId' AND receiver_id = '$BrokerId'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$RowCount	=	$Row['RowCount'];
		
		if($RowCount > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	
	
	/**
	 * The following method makes the deposite payments for Broker.
	 *
	 * @param Array $POST
	 * @param Int $BrokerId
	 * @param Int $MemberId
	 * @return Boolean
	 */
	function makeDepositPayment($POST,$BrokerId, $MemberId)
	{
		$LogFileName	=	SITE_PATH.'/tmp/logs/'.'paypal_'.date('Y').date('m').'.log';

		$req = 'cmd=_notify-validate';
		foreach ($POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($this->config['paypal_socket_address'], 80, $errno, $errstr, 30);

		$item_name 			=	$POST['item_name'];
		$item_number 		= 	$POST['item_number'];
		$payment_status 	= 	$POST['payment_status'];
		$payment_amount 	= 	$POST['mc_gross'];
		$payment_currency 	= 	$POST['mc_currency'];
		$txn_id 			= 	$POST['txn_id'];
		$receiver_email 	= 	$POST['receiver_email'];
		$payer_email 		= 	$POST['payer_email'];
		
		if (!$fp) {
			;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res	= 	fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					$fpp = fopen($LogFileName, "a+");
					fwrite($fpp, $MemberId."VERIFIED:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
					fclose($fpp);
					
					if ($payment_status == 'Completed') {
						$DateTime	=	date('Y-m-d H:i:s');
						$TransDescr	=	'Payment Of '.$item_name;
						
						$DepositInsArray	=	array(
													'depositor_id'			=>	$MemberId,
													'receiver_id'			=>	$BrokerId,
													'deposit_amount'		=>	$payment_amount,
													'deposite_date'			=>	$DateTime,
													'deposite_for'			=>	'BROKER'
												);
						$this->db->insert('member_deposits', $DepositInsArray);
						
						
						$TransInsArray	=	array(
												'member_id'			=>	$MemberId,
												'sentto_id'			=>	$BrokerId,
												'transaction_date'	=>	$DateTime,
												'trans_description'	=>	$item_name,
												'trans_amount'		=>	$payment_amount,
												'transaction_id'	=>	$txn_id
											);
						$this->db->insert('member_transactiondetails', $TransInsArray);
						
					} #Close if Completed
					
				} else if (strcmp ($res, "INVALID") == 0) {
					$fpp = fopen($LogFileName, "a+");
					$PostStr	=	var_export($POST, true)."\n".date('Y-m-d H:i:s')."\n".$res."\n";
					fwrite($fpp, $PostStr);
					fclose($fpp);
				}
			}
			fclose ($fp);
		}
		
		return TRUE;
	}
	
	

	/**
	 * following method returns the HTML for sending back the HTML for asynchronous request
	 *
	 * @author vimson@newagesmb.com
	 * 
	 * @param unknown_type $SearchCriteria
	 * @param unknown_type $pageNo
	 * @param unknown_type $limit
	 * @param unknown_type $params
	 * @param unknown_type $orderBy
	 * @param unknown_type $MemberId
	 * @param unknown_type $commisionminimum
	 * @param unknown_type $commisionmaximum
	 */
	function getAsynchronousOutputForBrokerSearchResults($SearchCriteria,$pageNo,$limit,$params,$orderBy = '',$MemberId, $commisionminimum, $commisionmaximum)
	{
		list($BrokerDetails,$numpad,$cnt_rs,$limitList)	=	$this->getBrokerSearchDetails($SearchCriteria,$pageNo,$limit,$params,$orderBy, $MemberId, $commisionminimum, $commisionmaximum);
		$BrokerCount	=	count($BrokerDetails);
		
		$Start	=	($pageNo - 1) * $limit + 1;
		$end	=	($pageNo - 1) * $limit + $limit;
		if($end > $cnt_rs)
			$end	=	$cnt_rs;
		
		if($BrokerCount > 0)
			$TmpOutPut	=	'<div class="blocktitle13px" style="width:910px;" align="center">Broker Search Results <span class="bodytext">'.$Start.'-'.$end.' of '.$BrokerCount.'</span></div>';
		$OutPut	=	'<div style="height:10px;"><!-- --></div>'.$TmpOutPut.'<div style="height:10px;"><!-- --></div>';
		$TplURL	=	SITE_URL."/templates/".$this->config['curr_tpl'];
				
		foreach($BrokerDetails as $Broker) {
			
			$TmpOutPut	=	'';
			if($Broker['image'] == 'Y')
				$TmpOutPut	=	'<img src="'.SITE_URL.'/modules/member/images/userpics/thumb/'.$Broker['id'].'.jpg?'.time().'" border="1" class="border" width="98px" height="98px">';
			else
				$TmpOutPut	=	'<img src="'.SITE_URL.'/templates/blue/images/photo.jpg" border="1" class="border" width="98px" height="98px">';
					

			$brokerdescription	=	($Broker['brokerdescription'] == '') ? 'No Details Available' : $Broker['brokerdescription'];
			$broker_commision	=	((float)$Broker['broker_commision'] <= 0) ? 'NIL' : $Broker['broker_commision'].'%';
			$broker_depositfee	=	((float)$Broker['broker_depositfee'] <= 0) ? 'NIL' : '$'.$Broker['broker_depositfee'];
			$PostLink			=	makeLink(array("mod"=>"flyer", "pg"=>"broker"), "act=assign_property");
			
			$OutPut	.=	'<div align="center">
							<div class="border" style="background:url('.$TplURL.'/images/box_bg.jpg);width:900px;background-repeat:repeat-x;text-align:left;padding:5px;">
								<div style="float:left;width:120px;">
									'.$TmpOutPut.'
								</div>
								<div style="float:right;width:770px;">
									<div style="float:left;width:65%;">
										<div class="bodytextbold">'.$Broker['first_name'].' '.$Broker['last_name'].'</div>
										<div class="bodytext" align="justify">'.$brokerdescription.'</div>
										<div class="blocktitle" align="justify"><font style="font-size:11px;">Broker Commision: '.$broker_commision.'</font></div>
										<div class="blocktitle" align="justify"><font style="font-size:11px;">Deposit Fee: '.$broker_depositfee.'</font></div>
									</div>
									<div style="float:right;width:30%;">
										<div class="bodytextbold">Contact Broker </div>
										<div class="bodytext">Name: '.$Broker['first_name'].' '.$Broker['last_name'].'</div>
										<div class="bodytext">Address: '.$Broker['address1'].' '.$Broker['address2'].'</div>
										<div class="bodytext">Phone Number: '.$Broker['telephone'].'</div>
										<div class="bodytext">Email: '.$Broker['email'].'</div>
										<div style="height:8px;"><!-- --></div>
										<div class="bodytext">
											<form style="margin:0px;" name="form'.$Broker['id'].'" method="post" action="'.$PostLink.'">
												<input type="hidden" name="BrokerId" value="'.$Broker['id'].'" />
												<input type="submit" name="Submit" style="width:220px;" value="Assign My Properties to this Broker" class="button_class" />
											</form>
										</div>
										
									</div>
									<div style="clear:both;"><!-- --></div>	
								</div>
								<div style="clear:both;"><!-- --></div>	
							</div>
						</div>
						<br />';
		}
		
		if($BrokerCount == 0)
			$OutPut	.=	'<div style="height:250px;text-align:center;" align="center" class="blocktitle">No Brokers Found.</div>';
		
		if($BrokerCount > 0)
			$OutPut	.=	'<div align="center" class="bodytext"><strong>'.$numpad.'</strong></div>';
		
		return $OutPut;
	
		
	}
	
	# The following method insert the total invoices of a broker or manager
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	function InvoiceAdd($tablename,$InsertArray)
	{
		$insert_id = $this->db->insert($tablename, $InsertArray);
		return $insert_id;
	}
	
	# The following method return the broker/manager info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getBrokerManagerInfo()
	  {
		 $Qry	=	"SELECT  assigned_user_id,property_id,date(accept_time) as accept_time,property_owner_id,assigned_role FROM propertyassign_relation WHERE accepted = 'Y' AND assigned_role in ('PROP_BROKER','PROP_MANAGER') ";
								
		$BrokerList	= $this->db->get_results($Qry, ARRAY_A);
		//$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
		
		return $BrokerList;
	  
	  }
	  
	  # The following method return the broker/manager invoice info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getInvoiceInfo($memberId,$assignedType,$interval)
	  {
		 $Qry	=	"SELECT ADDDATE(date(T1.to_date), INTERVAL 1 DAY) as to_date,ADDDATE(date(T1.to_date), INTERVAL $interval DAY) as end_date from property_bill_detail AS T1   WHERE T1.sentto_id ='$memberId' AND T1.invoice_type = '$assignedType'  order by T1.prop_bill_id desc ";
								
		$InvoiceList	=	$this->db->get_results($Qry, ARRAY_A);
		//$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
	
		return $InvoiceList;
	  
	  }
	  
	   # The following method return the broker/manager detailed invoice info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getAllInvoiceInfo($pageNo, $limit, $Params, $output, $orderBy, $MemberId)
	  {
		
		$Qry	=	"SELECT date(T1.from_date) as from_date,date(T1.to_date) as to_date,T1.prop_bill_id,T1.Invoice_amount,T1.invoice_type,T1.bill_status,T1.sentto_id,T1.sentby_id,T2.first_name,T3.paypal_account AS paypal_account from property_bill_detail AS T1, member_master AS T2,member_paymentdetails AS T3 WHERE T1.sentto_id ='$MemberId' and T1.sentby_id = T2.id and T3.memberid = T1.sentby_id   ";
								
		$Invoice	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
	
		return $Invoice;
	  
	  }
	  
	   # The following method return the broker/manager detailed invoice info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getAllInvoiceInfo_received_broker($pageNo, $limit, $Params, $output, $orderBy, $broker_manager_id)
	  {
		
		$Qry	=	"SELECT date(T1.from_date) as from_date,date(T1.to_date) as to_date,T1.prop_bill_id,T1.Invoice_amount,T1.invoice_type,T1.bill_status,T1.sentto_id,T1.sentby_id,T2.first_name from property_bill_detail AS T1, member_master AS T2 WHERE T1.sentby_id ='$broker_manager_id' and T1.sentto_id = T2.id and T1.bill_status='PAID' ";
								
		$Invoice	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
	
		return $Invoice;
	  
	  }
	   # The following method return the broker/manager total properties amount info of a user
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getAllpropInvoiceInfo($pageNo, $limit, $Params, $output, $orderBy, $MemberId,$propbill_id,$sentbyid)
	  {
		  
		$Qry	= "SELECT T1.invoice_amount As invoice_amount,T1.album_id, T2.flyer_name, T3.date_booked, date(T1.created_time) AS created_time from invoices AS T1 LEFT JOIN flyer_data_basic AS T2 ON T1.album_id=T2.album_id LEFT JOIN album_booking AS T3 ON T1.album_id = T3.album_id WHERE  T1.invoice_interval = '$propbill_id' and T1.invoice_status='PENDING' and (T3.broker_id = $sentbyid or T3.manager_id = $sentbyid)";					
		$Invoice	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
	
		return $Invoice;
	  
	  }
	  
	   # The following method return the broker/manager total properties amount info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	  function getAllpropInvoicedetailbyid($propbill_id)
	  {
		  $Qry	=	"SELECT Invoice_amount, bill_status, date(from_date) as from_date, date(to_date) as to_date,invoice_type,sentby_id  FROM property_bill_detail WHERE prop_bill_id = $propbill_id ";
								
		$Invoice	=	$this->db->get_results($Qry, ARRAY_A);
	
		return $Invoice;
	  
	  }
	# The following method return the broker/manager total properties amount info
	/**
      * Author   : Vipin	 
      * Created  : 18/April/2008
      * 
      */
	function getBrokerOrManagerInvoiceSum($broker_id,$invoice_type)
	  {	
		$Qry = "SELECT sum(T1.Invoice_amount) as Total_amount from property_bill_detail as T1 where T1.bill_status = 'PAID' and T1.invoice_type = '$invoice_type' AND T1.sentby_id = '$broker_id' group by T1.sentby_id";
		 $Invoice	=	$this->db->get_row($Qry,ARRAY_A);
		 
		return $Invoice;
	  }	  
	  
	function getBrokerOrManagerYearlySum($broker_id,$invoice_type)
	  {	
		$Qry = "SELECT sum(Invoice_amount) as year_amount from property_bill_detail   where bill_status = 'PAID' and invoice_type = '$invoice_type' AND sentby_id = '$broker_id' and DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= date_paid group by sentby_id";
		 $Invoice	=	$this->db->get_row($Qry,ARRAY_A);
		 
		return $Invoice;
	  }	  

	 /*
		Created:Vinoy
		Date:15-April-2008
		get the total deposute of a broker
	  */
	  function getBrokerDeposite($bid,$type)
	  {
	   $SQL="select sum(deposit_amount) as totamt from member_deposits where receiver_id='$bid' and deposite_for='$type'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	  
	  }
	  function getBrokerYearlyDeposite($bid,$type)
	  {
	   $SQL="select sum(deposit_amount) as yearamt from member_deposits where  DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= deposite_date AND receiver_id='$bid' and deposite_for='$type'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	  }




      /*
    Created:Vinoy
    Date:15-April-2008
    get the total earnings of a seller
	  */
	function getSellerEarnings($sid)
	{
	  //echo $SQL="select sum(amountpaid) from album_booking where start_date (CURDATE(),INTERVAL 360 DAY) AND album_id='$propid'";
	   $SQL="select sum(amountpaid) as totamt from album_booking where owner_id='$sid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	 /*
    Created:Vinoy
    Date:15-April-2008
    get the Yearly earnings of a seller
	  */
	function getSellerYearlyEarnings($sid)
	{
	   $SQL="select sum(amountpaid) as yearamt from album_booking where  DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= date_booked AND owner_id='$sid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}

	  
	function Updateinvoice($startdate,$now,$sentto_id,$sentby_id,$insert_id)
	{
		$Qry = "Update invoices set invoice_interval = '$insert_id' where sentby_id = '$sentby_id' and sentto_id = '$sentto_id' and date(created_time) between '$startdate' and '$now'";
		$this->db->query($Qry);
		//print $Qry;
		return true;
		
	}
	  
}


?>