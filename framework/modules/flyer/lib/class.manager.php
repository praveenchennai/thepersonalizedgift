<?php
/**
 * **********************************************************************************
 * @package    Flyer
 * @name       Manager Class
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2008 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  15-Jan-2008
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
class Manager extends FrameWork
{
	var $album;
	function Manager($album_obj)
	{
		$this->FrameWork();
		$this->album = $album_obj;
	}
	
	/**
    * Setting post Array Data
  	* Author   : Retheesh
  	* Created  : 31/Mar/2008
  	* Modified : 31/Mar/2008 By Retheesh
  	*/
	function setArrData($szArrData)
	{
		$this->arrData = $szArrData;
	}

	/**
    * Return post Array Data
  	* Author   : Retheesh
  	* Created  : 31/Mar/2008
  	* Modified : 31/Mar/2008 By Retheesh
  	*/
	function getArrData()
	{
		return $this->arrData;
	}

	/**
    * Setting error text
  	* Author   : Retheesh
  	* Created  : 31/Mar/2008
  	* Modified : 31/Mar/2008 By Retheesh
  	*/
	function setErr($szError)
	{
		$this->err .= "$szError";
	}

	/**
    * Getting error text
  	* Author   : Retheesh
  	* Created  : 31/Mar/2008
  	* Modified : 31/Mar/2008 By Retheesh
  	*/
	function getErr()
	{
		return $this->err;
	}
	
	/**
  	 * This functions retrives the manager details
  	 * Author   : Retheesh
  	 * Created  : 15/Jan/2008
  	 * Modified : 15/Jan/2008 By Retheesh
  	 * Modified : 21/Jan/2008 By Retheesh
  	 * Added no. of property managing
  	 */
	function getManagerSearchDetails($pageNo,$limit,$params,$orderBy = '',$search_str,$MemberId, $commisionminimum, $commisionmaximum)
	{

		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','cs','a');
		$sql = "Select a.*,$qry from member_master a $join_qry";
		//if(trim($search_str) != '') {
			$search_fields	=	"first_name,last_name,email,ispopertymanager,mem_type,manager_commision,manager_commision";
			$search_values	=	"$search_str,$search_str,$search_str,Y,0,$commisionminimum,$commisionmaximum";
			$criteria		=	"like,like,like,=,!=,>=,<=";
			list($qry_cs)	=	$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'a','cs',"OR,OR,OR,AND,AND,AND,AND");
			
			$sql	.=	" Where ".$qry_cs."and a.id!=$MemberId";
		//}
	
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, ARRAY_A, $orderBy);
		
		
		
		if ( count($rs[0]) > 0 ) {
			foreach ($rs as $value)
			{
			
				for($i=0;$i<sizeof($value);$i++)
				{
					$role_cnt = $this->getAssignedRoleCount('PROP_MANAGER',$rs[0][$i]['id']);
					$rate = $this->album->GetRatingNew('member_master',$rs[0][$i]['id'],'Manager','media_rating');
					$rs[0][$i]['role_cnt'] = $role_cnt[0]->cnt;
					$rs[0][$i]['rate']= $rate['rate'];
					$rs[0][$i]['rate_show'] = $rate['rate'] * 20;
				}
			}	
		}
		
		
		return $rs;
	}
	
	/**
  	 * This functions assign a property to Managers
  	 * Author   : Retheesh
  	 * Created  : 17/Jan/2008
  	 * Modified : 17/Jan/2008 By Retheesh
  	 */
	function assignPropertyToManager($arr)
	{	
		$this->db->insert('propertyassign_relation', $arr);
		return true;
	}
	
	/**
  	 * This functions fetches the total count of an Assigned Role
  	 * Author   : Retheesh
  	 * Created  : 21/Jan/2008
  	 * Modified : 21/Jan/2008 By Retheesh
  	 */
	function getAssignedRoleCount($role,$user_id)
	{
		$sql = "select count(relation_id) as cnt from propertyassign_relation 
		where assigned_role='$role' and  assigned_user_id=$user_id and accepted='Y'";
		
		
		$rs  = $this->db->get_results($sql);
		return $rs;
	}
	
	
	
	
	
	/**
	 * following method returns the HTML for sending back the HTML for asynchronous request
	 *
	 * @author aneesh.aravindan@@newagesmb.com
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
	function getAsynchronousOutputForManagerSearchResults($SearchCriteria,$pageNo,$limit,$params,$orderBy = '',$MemberId, $commisionminimum, $commisionmaximum)
	{
		
		list($ManagerDetails,$numpad,$cnt_rs,$limitList)	=	$this->getManagerSearchDetails($pageNo,$limit,$params,$orderBy,$SearchCriteria,$MemberId, $commisionminimum, $commisionmaximum);
		
		
		$ManagerCount	=	count($ManagerDetails);
		
		$Start	=	($pageNo - 1) * $limit + 1;
		$end	=	($pageNo - 1) * $limit + $limit;
		if($end > $cnt_rs)
		$end	=	$cnt_rs;
		
		
		
		
		if($ManagerCount > 0) {
			$TmpOutPut	=	'<div class="blocktitle13px" style="width:910px;" align="center">Manager Search Results <span class="bodytext">'.$Start.'-'.$end.' of '.$ManagerCount.'</span></div>';
			
			$OutPut	=	'<div style="height:10px;"><!-- --></div>'.$TmpOutPut.'<div style="height:10px;"><!-- --></div>';
			$TplURL	=	SITE_URL."/templates/".$this->config['curr_tpl'];
			
			
			foreach($ManagerDetails as $Manager) {
				$TmpOutPut	=	'';
				
				if($Manager['image'] == 'Y')
				$TmpOutPut	=	'<img src="'.SITE_URL.'/modules/member/images/userpics/thumb/'.$Manager['id'].'.jpg?'.time().'" border="1" class="border" width="98px" height="98px">';
				else
				$TmpOutPut	=	'<img src="'.SITE_URL.'/templates/blue/images/photo.jpg" border="1" class="border" width="98px" height="98px">';
				
				
				$manager_description	=	($Manager['propmanagerdescription'] == '') ? 'No Details Available' : $Manager['propmanagerdescription'];
				$manager_commision	=	((float)$Manager['manager_commision'] <= 0) ? 'NIL' : $Manager['manager_commision'].'%';
				$manager_depositfee	=	((float)$Manager['manager_depositfee'] <= 0) ? 'NIL' : '$'.$Manager['manager_depositfee'];
				$PostLink			=	makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=assign_property");
				
				$manager_numproperty=   ($Manager['role_cnt']>0) ? $Manager['role_cnt'] : 0;
				$manager_rate_show  =   ($Manager['rate_show']>0) ? $Manager['rate_show'] : 0;
				
				$OutPut	.=	'<div align="center">
							 <div class="border" style="background:url('.$TplURL.'/images/box_bg.jpg);width:900px;background-repeat:repeat-x;text-align:left;padding:5px;">
								<div style="float:left;width:120px;">
									'.$TmpOutPut.'
								</div>
								
								
								
								<div style="float:right;width:770px;">
									<div style="float:left;width:65%;">
										<div class="bodytextbold">'.$Manager['first_name'].' '.$Manager['last_name'].'</div>
										<div class="bodytext" align="justify">'.$manager_description.'</div>
										<div class="blocktitle" align="justify"><font style="font-size:11px;">Manager Commision: '.$manager_commision.'</font></div>
										<div class="blocktitle" align="justify"><font style="font-size:11px;">Deposit Fee: '.$manager_depositfee.'</font></div>
										<div class="blocktitle" align="justify"><font style="font-size:11px;">No. of Properties Managing: '. $manager_numproperty .'</font></div>
									</div>
									<div style="float:right;width:30%;">
										<div class="bodytextbold">Contact Manager </div>
										<div class="bodytext">Name: '.$Manager['first_name'].' '.$Manager['last_name'].'</div>
										<div class="bodytext">Address: '.$Manager['address1'].' '.$Manager['address2'].'</div>
										<div class="bodytext">Phone Number: '.$Manager['telephone'].'</div>
										<div class="bodytext">Email: '.$Manager['email'].'</div>
										<div style="height:8px;"><!-- --></div>
										
										<div class="bodytext">
										 <ul class="star-rating small-star">
                                   		 <li class="current-rating" style="width:'.$manager_rate_show.'%">Currently '.$manager_rate_show.'/5 Stars.</li>
                                     	 <li><a href="'.makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=rating&rate=1&rating_user_id={$Manager['id']}").'" title="1 star out of 5" class="one-star">1</a></li>
                                    	 <li><a href="'.makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=rating&rate=2&rating_user_id={$Manager['id']}").'" title="2 stars out of 5" class="two-stars">2</a></li>
                                    	 <li><a href="'.makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=rating&rate=3&rating_user_id={$Manager['id']}").'" title="3 stars out of 5" class="three-stars">3</a></li>
                                    	 <li><a href="'.makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=rating&rate=4&rating_user_id={$Manager['id']}").'" title="4 stars out of 5" class="four-stars">4</a></li>
                                    	 <li><a href="'.makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=rating&rate=5&rating_user_id={$Manager['id']}").'" title="5 stars out of 5" class="five-stars">5</a></li>
                                  	     </ul>
                                  	    </div>
										
										
										<div style="height:8px;"><!-- --></div>
										<div class="bodytext">
											<form style="margin:0px;" name="form'.$Manager['id'].'" method="post" action="'.$PostLink.'">
												<input type="hidden" name="manager_id" value="'.$Manager['id'].'" />
												<input type="submit" name="Submit" style="width:220px;" value="Assign My Properties to this Manager" class="button_class"/>
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
		}
		
		if($ManagerCount == 0)
			$OutPut	.=	'<div style="height:250px;text-align:center;" align="center" class="blocktitle">No Managers Found.</div>';
		
		if($ManagerCount > 0)
			$OutPut	.=	'<div align="center" class="bodytext"><strong>'.$numpad.'</strong></div>';
		
		return $OutPut;
		
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
	function getMyAssignedPropertiesOfManager($MemberId, $pageNo, $limit, $params, $orderBy = '', $AlbumObj = '', $PhotoObj = '', $objUser = '')
	{
		$ManagerProperties	=	array();
		
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
					
					WHERE property_owner_id = '$MemberId' AND assigned_role = 'PROP_MANAGER' ORDER BY T1.property_id DESC "; #AND accepted = 'Y' AND declined = 'N'
		list($AssignedProperties,$numpad,$cnt_rs, $limitList)	=	$this->db->get_results_pagewise($Qry1, $pageNo, $limit, $params, ARRAY_A, $orderBy);
				//print_r($AssignedProperties);
		$ArrIndx	=	0;
		foreach($AssignedProperties as $AssignedProperty) {
			$BrokerDetails		=	$objUser->getUserdetails($AssignedProperty['assigned_user_id']);
			
			$brokerrate=$AlbumObj->getBrokerRate($AssignedProperty['assigned_user_id'],'manager');
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
	

}
?>