<?php
/**
 * 
 *
 * @author Retheesh Kumar
 * @package defaultPackage
 */
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");

$objUser 	= 	new User();
$objAlbum 	= 	new Album();
$objExtras	=	new Extras();
$video=new Video();

$store_name	=	$_REQUEST['store_name'];

switch ($_REQUEST["act"])
{

	case "valid_store":
	
	    if(trim($store_name)=='')
		{
			echo "<strong class='form_error_message'>&nbsp;This information is required</strong>";
			exit;
		}
		if(strlen(trim($store_name)) < 5)
		{
				echo "<strong class='form_error_message'>&nbsp;Minimum 5 characters required</strong>";
				exit;
		}
		else
		{
			if ($objUser->validStore($store_name))
			{
					echo "IMG";
					exit;
			}
			else 
			{
				echo "<strong class='form_error_message'>Store URL already taken</strong>";
				exit;
			}
		}	
		break;
	case "valid_profile":
		$rs = $objUser->profileCheck($_REQUEST['profile']);
		if (count($rs)==0)
		{
			echo "<strong class='greenClass'>Profile URL is Available</strong>";
			exit;
		}
		else 
		{
			echo "<strong class='redClass'>Profile URL already taken</strong>";
			exit;
		}
		break;		
	case "valid_email":
	
	     if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_REQUEST['email']))
		{
			echo "<strong class='form_error_message'>Invalid E-mail Address</strong>";
			exit;
		}
		else{
	
			if (!$objUser->checkEmail($_REQUEST['email'],$_REQUEST['user_id'],$_REQUEST['store_id'],$_REQUEST['retailer']))
			{
				echo "IMG";
				exit;
			}
			else 
			{
				echo "<strong class='form_error_message'>Email already exists</strong>";
				exit;
			}
		}	
		break;	
	case "valid_uname":
		if(!ctype_alnum($_REQUEST['uname'])) 
		{
			echo "<strong class='redClass'>Use only alphanumeric characters</strong>";
			exit;
		}
		else 
		{
			
			
			if (!$objUser->getUsernameDetails($_REQUEST["uname"],$_REQUEST['store_id']))
			{
				echo "<strong class='greenClass'>Username Available</strong>";
				exit;
			}
			else 
			{
				echo "<strong class='form_error_message'><font color='red'>Username already exists</font></strong>";
				exit;
			}
		}
		break;
	case "valid_roomname":
			if ($objUser->getRoomnameAvailbility($_REQUEST["rname"])===true)
			{
				echo "<strong class='greenClass'>Room name Available</strong>";
				exit;
			}
			else 
			{
				echo "<strong class='redClass'><font color='red'>Room name already exists</font></strong>";
				exit;
			}
		break;
	case "update_mem":
		$objUser->setArrData($_POST);
		$objUser->update();
		echo "updated";
		exit;
		break;
	case "ajax_message_send":
		$mem_id  = $_SESSION['memberid'];
		if (mem_id)
		{
			$mem_det = $objUser->getUserdetails($mem_id);
		}	
		$to_det  = $objUser->getUserdetails($_REQUEST['to']);
		$arr  = array();
		$arr['from'] = $mem_det['username'];
		$arr['to']   = $to_det['username'];
		$arr["datetime"] = date("Y-m-d H:i:s");
		$arr['comments'] = $_REQUEST['message'];
		$arr["status"] = "U";
		$objUser->setArrData($arr);
		$objUser->sendMessage();
		echo "Message sent successfully";
		exit;
		break;					
	case "reg_pack":
		$mem_type = $_REQUEST["mem_type"];
		if ($_REQUEST["selected"]!="")
		{
			$selected = $_REQUEST["selected"];
		}
		else 
		{
			$selected="11";
		}
		$free = 1;
		if ($mem_type!='')
		{
			$reg  = $objUser->loadRegPack($mem_type);
		}
		else 
		{
			$free =0;
		}
		$cmb ="<select name='reg_pack' id='reg_pack' class='form_field_listmenu' onChange='return loadSub(this.value);' onfocus='javascript:setDropdownDefault(\"reg_pack_left\",\"reg_pack_error\",\"form_wrap\");'  >";
		$cmb.="<option value=''>--Select Package--</option>";
		for ($i=0;$i<sizeof($reg);$i++)
		{
			if ($selected==$reg[$i]->id)
			{
				$sel="selected";
			}
			else 
			{
				$sel="";
			}
			$free = 0;
			$cmb .="<option value='{$reg[$i]->id}' $sel>{$reg[$i]->package_name} ($"."{$reg[$i]->fee})</option>";
		}
		$cmb .='</select>~1';
		if ($free==1)
		{
			$cmb = "<strong class='greenClass'>FREE</strong><input type='hidden' name='reg_pack' value='0'>~0";
		}
		echo $cmb;
		exit;
		break;	
	
	case "sub_pack_dropdown":
	
			$reg_pack = $_REQUEST["reg_pack"];
			$pag		  =	$_REQUEST["pag"];
			if ($_REQUEST["selected"]!="")
			{
				$selected = $_REQUEST["selected"];
			}
			else 
			{
				$selected = "";
			}
			$free = 1;
			if($reg_pack!='')
			{
				$sub = $objUser->loadSubscriptions($reg_pack);
			}	
			else 
			{
				$free = 0;
			}
			
			
			$cmb ="<select name='sub_pack' id='sub_pack'  style='width:200px'>";
			$cmb.="<option value=''>--Select Subscription--</option>";
			for ($i=0;$i<sizeof($sub);$i++)
			{
				$free = 0;
				if ($sub[$i]->type=='Y')
				{
					($sub[$i]->duration>1)? $type = " Years" : $type = " Year";				
				}
				elseif ($sub[$i]->type=='M')
				{
					($sub[$i]->duration>1)? $type = " Months" : $type = " Month";
				}
				elseif ($sub[$i]->type=='D')
				{
					($sub[$i]->duration>1)? $type = " Days" : $type = " Day";
				}
			
				if ($selected==$sub[$i]->id)
				{
					$sel="selected";
				}
				else 
				{
					$sel="";
				}
					$cmb .="<option value='{$sub[$i]->sub_id}' $sel>{$sub[$i]->name} ({$sub[$i]->duration}$type)</option>";
			}
			$cmb .='</select>~1';
			if ($free==1)
			{
				$cmb = "<strong class='greenClass'>FREE</strong><input type='hidden' name='sub_pack' value='0'>~0";
			}
		//echo $free;
			
			echo $cmb;
			exit;
		break;
	
	case "sub_pack"	:
	
		$reg_pack = $_REQUEST["reg_pack"];
		$pag		  =	$_REQUEST["pag"];
		if ($_REQUEST["selected"]!="")
		{
			$selected = $_REQUEST["selected"];
		}
		else 
		{
			$selected = "";
		}
		$free = 1;
		if($reg_pack!='')
		{
			$sub = $objUser->loadSubscriptions($reg_pack);
		}	
		else 
		{
			$free = 0;
		}
		
		if(SHOW_FORMS=="Y"){
			$sz=sizeof($sub);
			if($sz==1){
				for ($i=0;$i<$sz;$i++)
				{
					$free = 0;
					if ($sub[$i]->type=='Y')
					{
						($sub[$i]->duration>1)? $type = " Years" : $type = " Year";				
					}
					elseif ($sub[$i]->type=='M')
					{
						($sub[$i]->duration>1)? $type = " Months" : $type = " Month";
					}
					elseif ($sub[$i]->type=='D')
					{
						($sub[$i]->duration>1)? $type = " Days" : $type = " Day";
					}
					if ($sub[$i]->fees==0){
						$sub[$i]->fees="Free";
						$pay="N";
					}else{
						$sub[$i]->fees = round($sub[$i]->fees, 1);
						$sub[$i]->fees="$".$sub[$i]->fees;
						$pay="Y";
					}
					$cmb.="<table width='80%'  border='0' bgcolor='#990000' cellspacing='1' cellpadding='1'><tr valign='top' ><td  bgcolor='#FFFFFF' style='font-size:12px' >{$sub[$i]->description}</td></tr></table><br>";
					$cmb .= "<table width='80%'  border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFCC' style='border: 1px dotted'>
							<tr valign='top'><td height='18'><input name='txt_payment' type='hidden' id='txt_payment' value='{$pay}'><strong class='greenClass'>{$sub[$i]->name} : &nbsp;</strong><font color='#336699'><b>{$sub[$i]->fees}</b></font> <input type='hidden' name='sub_pack' value='{$sub[$i]->sub_id}'/></td></tr><tr><td height='10'>&nbsp;</td></tr></table><br/>~1";
				}
				//print_r($cmb);
				//$cmb.="~1";
				// if size=1
			}else{
				$once=1;
			for ($i=0;$i<$sz;$i++)
				{
					$free = 0;
					if ($sub[$i]->type=='Y')
					{
						($sub[$i]->duration>1)? $type = " Years" : $type = " Year";				
					}
					elseif ($sub[$i]->type=='M')
					{
						($sub[$i]->duration>1)? $type = " Months" : $type = " Month";
					}
					elseif ($sub[$i]->type=='D')
					{
						($sub[$i]->duration>1)? $type = " Days" : $type = " Day";
					}
					if ($sub[$i]->fees==0)
						$sub[$i]->fees="Free";
					else{
						$sub[$i]->fees = round($sub[$i]->fees, 1);
						$sub[$i]->fees="$".$sub[$i]->fees;
					}
					$sub_id[]=$sub[$i]->id;
					$sub_name[]=$sub[$i]->name;
					if($once==1){
						$cmb.="<table width='80%'  border='0' bgcolor='#990000' cellspacing='1' cellpadding='1'><tr valign='top' ><td  bgcolor='#FFFFFF' style='font-size:12px' >{$sub[$i]->description}</td></tr></table><br>";
						$once=0;
					}
					$cmb.="<table width='80%'  border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFCC' style='border: 1px dotted'>";
					
					if($sub[$i]->sub_id == $selected) {
					$cmb.="<tr valign='top'><td height='18'><input name='txt_payment' type='hidden' id='txt_payment' value='Y'><label for='id_'.{$sub[$i]->sub_id}><input type='radio' name='sub_pack' value='{$sub[$i]->sub_id}' id='sub_pack' checked /><strong class='greenClass'>{$sub[$i]->name}:&nbsp;</strong><font color='#336699'><b>{$sub[$i]->fees}</b></font></label></td></tr><tr><td height='10'>&nbsp;</td></tr></table><br/>";
					}
					else{
					$cmb.="<tr valign='top'><td height='18'><input name='txt_payment' type='hidden' id='txt_payment' value='Y'><label for='id_'.{$sub[$i]->sub_id}><input type='radio' name='sub_pack' value='{$sub[$i]->sub_id}' id='sub_pack' /><strong class='greenClass'>{$sub[$i]->name}:&nbsp;</strong><font color='#336699'><b>{$sub[$i]->fees}</b></font></label></td></tr><tr><td height='10'>&nbsp;</td></tr></table><br/>";
					}
				}
				$cmb.="~1";
			//$smarty->assign('cust_ids',$sub_id);
			//$smarty->assign('cust_names', $sub_name);
			//$smarty->assign('customer_id',$selected);
		//	$cmb="{html_radios name='sub_pack'  id='sub_pack' class='input' values=$sub_id output=$sub_name selected=$selected separator='<br />'}~1";

			} 
			
		// End if  if(SHOW_FORMS=="Y")
		}else{
			$cmb ="<select name='sub_pack' id='sub_pack' class='form_field_listmenu' onfocus='javascript:setDropdownDefault(\"sub_pack_left\",\"sub_pack_error\",\"form_wrap\");' onchange='loadSubsPackIds(this.value)'>";
			$cmb.="<option value=''>--Select Subscription--</option>";
			for ($i=0;$i<sizeof($sub);$i++)
			{
				$free = 0;
				if ($sub[$i]->type=='Y')
				{
					($sub[$i]->duration>1)? $type = " Years" : $type = " Year";				
				}
				elseif ($sub[$i]->type=='M')
				{
					($sub[$i]->duration>1)? $type = " Months" : $type = " Month";
				}
				elseif ($sub[$i]->type=='D')
				{
					($sub[$i]->duration>1)? $type = " Days" : $type = " Day";
				}
				
				if ($selected==$sub[$i]->sub_id)
				{
					$sel="selected";
				}
				else 
				{
					$sel="";
				}
				$cmb .="<option value='{$sub[$i]->sub_id}' $sel>{$sub[$i]->name} ({$sub[$i]->duration}$type) ("."$"."{$sub[$i]->sub_fee})</option>";
			}
			$cmb .='</select>~1';
			if ($free==1)
			{
				$cmb = "<strong class='greenClass'>FREE</strong><input type='hidden' name='sub_pack' value='0'>~0";
			}
			//echo $free;
		}
		echo $cmb;
		exit;
		break;
		
		case "valid_url" :
		
		if(!ctype_alnum($_REQUEST['url'])) {
		echo "<strong class='redClass'>Use only alphanumeric characters</strong>";
		exit;
		}
			else {
			$rs = $objUser->allUsers("account_url",$_REQUEST['url']);
			//print count($rs);
			//updated by vipin on 27-12-2007
			if  (strtolower($rs[0]->account_url)==strtolower($_REQUEST['url'])){
				echo "<strong class='redClass'>Account URL already exists</strong>";
				exit;
			}else{
				echo "<strong class='redClass'>Account URL is Available</strong>";
				exit;
			}

			
		/*	if(count($rs))
			{
				echo "<strong class='redClass'>Account URL already exists</strong>";
			exit;
			}else{
			echo "sdff";
			echo "<strong class='redClass'>Account URL is Available</strong>";
			exit;
			}*/
		}//end of else line 219
			
			//}
		break;
		case "valid_author":
			 /**
		   * This  is used to check author detalis entered by user  already exit.
		   * Author   : Adarsh
		   * Created  : 21/Nov/2007
		   * Modified : 
 		   */			
			 $req=&$_REQUEST;
			 $array= array();
			 $author_array=array();
			 
			 $author     =$req['author'];
			 $institution=$req['institution'];
			 $country    =$req['country'];
			 			 
			 $arrValue=array("author"=>trim(strtolower($author)),"institution"=>trim(strtolower($institution)),"country"=>$country);
			 $rs=$objAlbum->chkDuplication($arrValue,'author_details');
			 if(is_array($rs))
			 {
				$array[]=$rs;
			 }
			 else
			{
				$authorId=$objAlbum->chkAuthorUsingTags(trim(strtolower($author)),'name');
				if (is_array($authorId))
				{
					$array[]=$authorId;
				}
				else
				{
				$authorId=$objAlbum->chkAuthorUsingTags(trim(strtolower($institution)),'institution');
					if (is_array($authorId))
					{
						$array[]=$authorId;
					}
				}	
			}  
			 for($j=0;$j< count($array);$j++)
			 {
				for($k=0;$k< count($array[$j]);$k++)
				{
					$author_array[]=$objAlbum->getAuthorDetails($array[$j][$k],false);
				}	
			 }
			 if(count($author_array) > 0)
			 {
				 $authorList='<table border=0 width=100% cellpadding="0" cellspacing="0" > 
								<tr>
									<td colspan=4 align="center" class="blackboldtext"><span style="color:#FF0000"><b>Author already listed.</b></span></td>
								</tr>';
				 $authorList.='<tr>
									<td width="2%" height="24" align="center"></td> 
									<td width="40%"  height="24" align="left" class="blacktext"><strong>Author Name</strong></td>
									<td width="40%"   height="24" align="left" class="blacktext"><strong>Institution </strong></td>
									<td width="30%"   height="24" align="left" class="blacktext"><strong>Country</strong></td>
								</tr>';
							
				for($i=0;$i< count($author_array);$i++)
				{		
					$authorList.='<tr>
								<td width="2%" height="24" align="center">&nbsp;</td> 
								<td width="40%"  height="24" align="left" class="blacktext"><a href="javascript:selAuthor('."".$author_array[$i]['id']."".","."'".$req['no']."'".')" class="footerlink">'.$author_array[$i]['author'].' </a></td>
								<td width="40%"   height="24" align="left" class="blacktext">'.$author_array[$i]['institution'].'</td>
								<td width="35%"   height="24" align="left" class="blacktext">'.$author_array[$i]['country_name'].'</td>
								</tr>';
				}										
					$authorList.='<tr>
									<td width="2%" height="24" align="center"></td> 
									<td width="40%"  height="24" align="left" class="blacktext"></td>
									<td width="40%"   height="24" align="left" class="blacktext"><a href="javascript:clearList('."'".$req['no']."'".')" class="button_class">Close</a></td>
									<td width="30%"   height="24" align="left" class="blacktext"></td>
								</tr>';
				$authorList.='</table>';
				 echo  $authorList.'|'.$req['no'];
				 exit;
			 }
			 else
			 {
			 	echo  "".'|'.$req['no'];
				exit;
			 }
			 
			
		break;
	case "author_details":
		 /**
		   * This  is used to get the author details.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */			
		 $req=&$_REQUEST;
		 $rs=$objAlbum->getAuthorDetails($req['id'],$flage=false);
		 $rs['num']=$req['num'];
		 $authors=implode(",",$rs);
		 echo $authors;
		 exit;
		break;	
	case "conference":
		 /**
		   * This  is used to check the conference article already exits.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		$req=&$_REQUEST;
		$str="";
		$country=$objAlbum->getCountryIdByname($req['conference_country']);
		$req['conference_country']=$country['country_id'];
		$arrVal=array("conference_name"=>trim($req['conference_name']),"conference_country"=>$country['country_id'],"conference_year"=>trim($req['conference_year']));
		$conferenceId=$objAlbum->chkDuplication($arrVal,'conference');
		if(is_array($conferenceId))
		$array=$conferenceId;
		else
		{
			$conferenceId=$objAlbum->chkConferenceUsingTags($req,'name');
			if(is_array($conferenceId))
			$array=$conferenceId;
		}
		if(count($array)>0)
		{
			//die("test");
			$str.='<table cellpadding="0" width="400" cellspacing="0" border="0" class="blacktext">';
			$str.='<tr><td colspan="3" align="center" class="blackboldtext"><span style="color:#FF0000">Conference already listed.</span></td></tr>';
			$str.='<tr><td  class="blackboldtext" style="height:24">Conference_name</td>';
				$str.='<td class="blackboldtext">Country</td>';
				$str.='<td class="blackboldtext">Year</td></tr>';
			for($j=0;$j< count($array);$j++)
			{
				$conference_array[]=$objAlbum->getConferenceDetails($array[$j],false);
			}
			for($i=0;$i< count($conference_array);$i++)
			{		
				$str.='<tr><td style="height:24" class="blackboldtext"><a  href="javascript:selConference('.$conference_array[$i]['id'].')" class="footerlink">'.$conference_array[$i]['conference_name'].'</a></td>';
				$str.='<td>'.$conference_array[$i]['country_name'].'</td>';
				$str.='<td>'.$conference_array[$i]['conference_year'].'</td></tr>';
			}
		
		$str.='<tr><td colspan="3" align="center"><a href="javascript:closeWindow()"  class="button_class">Close</a></td></tr>';
		$str.='</tr></table>';
		}
		echo $str;
		exit;
		break;
	case "conference_details":
		 /**
		   * This  is used to get the conference article details.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		 $req=&$_REQUEST;
		 $rs=$objAlbum->getConferenceDetails($req['id'],$flag=false);
		 if($rs)
		 {
			 $rs1=implode(",",$rs);
			 echo $rs1;
		 }
		 else
		 echo "null";
		 exit;
		break;
	case "journal":
		 /**
		   * This  is used to get the list of  journal article already exits.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		$req=&$_REQUEST;
		$str="";
		$journal_array=array();
		$arrVal=array("journal_name"=>trim(strtolower($req['journal_name'])),"journal_volume"=>trim(strtolower($req['journal_volume'])),"journal_year"=>trim(strtolower($req['journal_year'])),"journal_number"=>trim(strtolower($req['journal_number'])));
		$journalId=$objAlbum->chkDuplication($arrVal,'journals');
		if(is_array($journalId))
		$array=$journalId;
		else
		{
			$journalId=$objAlbum->chkJournalUsingTags($req,'name');
			if(is_array($journalId))
			$array=$journalId;
		}
		if(count($array))
		{
			$str.='<table cellpadding="0" width="400" cellspacing="0" border="0" class="blacktext">';
			$str.='<tr><td colspan="3" align="center" class="blackboldtext"><span style="color:#FF0000">Journal already listed.</span></td></tr>';
			$str.='<tr><td  class="blackboldtext" style="height:24">Journal name</td>';
			$str.='<td class="blackboldtext">Year</td>';
			$str.='<td class="blackboldtext">Volume</td>';
			$str.='<td class="blackboldtext">Number</td></tr>';
				
			for($j=0;$j< count($array);$j++)
			{
				$journal_array[]=$objAlbum->getJournalDetails1($array[$j],false);
			}
			for($i=0;$i< count($journal_array);$i++)
			{		
				$str.='<tr><td style="height:24" class="blackboldtext"><a  href="javascript:selJournal('.$journal_array[$i]['id'].')" class="footerlink">'.$journal_array[$i]['journal_name'].'</a></td>';
				$str.='<td>'.$journal_array[$i]['journal_year'].'</td>';
				$str.='<td>'.$journal_array[$i]['journal_volume'].'</td>';
				$str.='<td>'.$journal_array[$i]['journal_number'].'</td></tr>';
			}
		
		$str.='<tr><td colspan="4" align="center"><a href="javascript:closeWindow()"  class="button_class">Close</a></td></tr>';
		$str.='</tr></table>';
		}
		echo $str;
		exit;
		break;
	case "journal_details":
		 /**
		   * This  is used to get the journal article details.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		 $req=&$_REQUEST;
		 $rs=$objAlbum->getJournalDetails1(trim($req['id']));
		 $rs1=implode(",",$rs);
		 echo $rs1;
		 exit;
		break;
	case "book":
		 /**
		   * This  is used to get the list of  report type that article already exits.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		$req=&$_REQUEST;
		$str="";
		$book_array=array();
		$arrVal=array("book_title"=>trim(strtolower($req['name'])),"book_author"=>trim(strtolower($req['author'])),"book_year"=>trim(strtolower($req['year'])),"book_publisher"=>trim(strtolower($req['publisher'])));
		$bookId=$objAlbum->chkDuplication($arrVal,'book');
		if($bookId===true)
		{
			$bookId=$objAlbum->chkBookUsingTags(trim(strtolower($req['name'])),'title');
				if($bookId===true)
					  $bookId=$objAlbum->chkBookUsingTags(trim(strtolower($req['author'])),'author');
				if($bookId===true)
					 $bookId=$objAlbum->chkBookUsingTags(trim(strtolower($req['publisher'])),'publisher');
		}
		if(is_array($bookId))
		{
			$str.='<table cellpadding="0" width="400" cellspacing="0" border="0" class="blacktext">';
			$str.='<tr><td colspan="3" align="center" class="blackboldtext"><span style="color:#FF0000">Book already listed.</span></td></tr>';
			$str.='<tr><td  class="blackboldtext" style="height:24">Book Title</td>';
			$str.='<td class="blackboldtext">Author</td>';
			$str.='<td class="blackboldtext">Year</td>';
			$str.='<td class="blackboldtext">Publisher</td></tr>';
				
			for($j=0;$j< count($bookId);$j++)
			{
				$book_array[]=$objAlbum->getBookDetails($bookId[$j],false);
			}
			for($i=0;$i< count($book_array);$i++)
			{		
				$str.='<tr><td style="height:24" class="blackboldtext"><a  href="javascript:selBook('.$book_array[$i]['id'].')" class="footerlink">'.$book_array[$i]['book_title'].'</a></td>';
				$str.='<td>'.$book_array[$i]['book_author'].'</td>';
				$str.='<td>'.$book_array[$i]['book_year'].'</td>';
				$str.='<td>'.$book_array[$i]['book_publisher'].'</td></tr>';
			}
		
		$str.='<tr><td colspan="4" align="center"><a href="javascript:closeWindow()"  class="button_class">Close</a></td></tr>';
		$str.='</tr></table>';
		}
		echo $str;
		exit;
	case "book_details":
		 /**
		   * This  is used to get the book type  article details.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		 $req=&$_REQUEST;
		 $rs=$objAlbum->getBookDetails(trim($req['id']),$flag=false);
		 $rs1=implode(",",$rs);
		 echo $rs1;
		 exit;
		break;	
	case "report":
		 /**
		   * This  is used to get the list of  report type that  article already exits.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		$req=&$_REQUEST;
		$str="";
		$report=array();
		$reportId=$objAlbum->getReportList($req['report_name']);
		if(!is_array($reportId))
		{
			$reportId=$objAlbum->chkInstitutionUsingTags(trim(strtolower($req['report_name'])),'name');
		}
		if(is_array($reportId))
		{
			$str.='<table cellpadding="0" width="300" cellspacing="0" border="0" class="blacktext">';
			$str.='<tr><td colspan="3" align="center" class="blackboldtext"><span style="color:#FF0000">Institution already listed.</span></td></tr>';
			$str.='<tr><td  class="blackboldtext" style="height:24">Institution Name</td>';
				
			for($j=0;$j< count($reportId);$j++)
			{
				$report[]=$objAlbum->getInstitutionDetails($reportId[$j],false);
			}
			for($i=0;$i< count($report);$i++)
			{		
				$str.='<tr><td style="height:24" class="blackboldtext"><a  href="javascript:selReport('."'".$report[$i]['id']."'".')" class="footerlink">'.$report[$i]['institution_name'].'</a></td>';
			}
		
		$str.='<tr><td colspan="4" align="center"><a href="javascript:closeWindow()"  class="button_class">Close</a></td></tr>';
		$str.='</tr></table>';
		}
		echo $str;
		exit;
	case "report_details":
		 /**
		   * This  is used to get the book type  article details.
		   * Author   : Adarsh
		   * Created  : 06/Desc/2007
		   * Modified : 
 		   */	
		 $req=&$_REQUEST;
		 $rs=$objAlbum->getInstitutionDetails($req['id']);
		 $rs1=implode(",",$rs);
		 echo $rs1;
		 exit;
		break;	
	
	case "valid_cmp_name" :
		 /**
		   * This  is used to chk the uniqueness of compnay name while registering from a store[taking_art]
		   * Author   : Salim
		   * Created  : 13/Desc/2007
		   * Modified : 
 		   */	


			$rs = $objUser->allUsers("company_name",$_REQUEST['cmpname']);
			//print count($rs);
			if(count($rs)) {
				echo "<strong class='redClass'>Company name already exists</strong>";
				exit;
			}
			else{
				echo "<strong class='greenClass'>Company name is Available</strong>";
				exit;
			}
			
	case "valid_taxid" :
			
		 /**
		   * This  is used to chk the uniqueness of tax id while registering from a store[taking_art]
		   * Author   : Salim
		   * Created  : 13/Desc/2007
		   * Modified : 
 		   */	

			if(!ctype_alnum($_REQUEST['taxid'])) {
			echo "<strong class='redClass'>Use only alphanumeric characters</strong>";
			exit;
			}
			
			else {
				$rs = $objUser->allUsers("tax_id",$_REQUEST['taxid']);
				//print count($rs);
				if(count($rs)) {
					echo "<strong class='redClass'>Tax Id already exists</strong>";
					exit;
				}
				else{
					echo "<strong class='greenClass'>Tax Id is Available</strong>";
					exit;
				}
			}

	case "sub_pack_news":
		 /**
		   * This  is used to generate dropdown of subscription packages when a registration package is selected in the newletter module.
		   * Author   : Salim
		   * Created  : 17/Jan/2008
		   * Modified : 
 		   */	

		$reg_pack = $_REQUEST["reg_pack"];
		$sub = $objUser->loadSubscriptions($reg_pack);
		$regpack1 = array();
		
		$str = '<select name="sub_pack" id="sub_pack" class="input" value=""style="width:180px">';
        $str = $str.'<option value="">Select a plan</option>';
			if($sub) {
			foreach($sub as $val)
			{
				//$regpack1[$val->id] = $val->sub_id;
				//$regpack1[$val->name] = $val->name;
				$str = $str.'<option value="'.$val->sub_id.'">'.$val->name.'</option>';
			} 
		}
			$str = $str.'</select>';
		
		echo $str;
		exit;
	

		
	case 'check_promocode':
		/**
		 * @author vimson@newagesmb.com
		 * 
		 * Validating the promotion code entered by the user.
		 * 
		 */
		$PromotionCode	=	$_REQUEST['PromotionCode'];
		$UserId			=	$_REQUEST['user_id'];
		$SubPack		=	(int)$_REQUEST['sub_pack'];
		$RegPack		=	(int)$_REQUEST['reg_pack'];
		
		list($SubscriptionExists, $DiscountAmount)		=	$objExtras->validatePromotionCode($PromotionCode, $SubPack, $UserId, $objUser);
		list($RegistrationExists, $RegDiscountAmount)	=	$objExtras->validatePromotionCodeforRegPack($PromotionCode, $RegPack, $UserId, $objUser);
		if($SubscriptionExists == "YES" || $RegistrationExists == "YES")
			$CodeExists = "YES";
		else
			$CodeExists = "NO";
		$Output		=	"$CodeExists|$DiscountAmount|$RegDiscountAmount";
		print $Output;
			
		exit;
		break;
		
	case "valid_email_with_store":
		/**
		 * @author Salim
		 * 
		 * Validating the email id with store feature enabled.
		 * 
		 */
		if (!$objUser->checkEmailForMultiStores($_REQUEST['email'],'',$_REQUEST['store_id']))
		{
			echo "<strong class='greenClass'>Email Validated</strong>|0";
			exit;
		}
		else 
		{
			$link = makeLink(array("mod"=>member,"pg"=>pswd),"email=".$_REQUEST['email']);
			$link =  "<strong class=''> It looks that you have already registered with us. If you have forgotten your user name and password. <a href=$link class='redClass'> Please click here  </a>we will send you the username and password to the email address above.</strong>|1";
//			echo "<strong class='redClass'>It looks that you have already registered with us. If you have forgotten your user name and password. Please click here we will send you the username and password to the email address above.</strong>";
			echo $link;			
			exit;
		}
		break;	
		case "view_video":
			$video_id=$_REQUEST["video_id"];
			$phdet = $video->getVideoDetails($video_id);
			$rs1=implode("|",$phdet);
			echo $rs1;
			exit;
		break;	
		case "valid_store_member":
			$id=$_REQUEST['id'];
			$res=$objUser->vaildStoreMember($id);
			echo $res;
		break;
		case "store_reg_sess":
			$storedet= $_REQUEST;
			$_SESSION= $storedet;
			$sess_id=$objUser->encodeSession();
			echo $sess_id;
			break;
		case 'subs_pack_vals':
		
			$str='';
			
			$sub_pack = $_REQUEST['sub_pack'];
			$sub_det = $objUser->getSubscrDetails($sub_pack);
			$paypal_code=html_entity_decode($sub_det['paypal_code']);
			$str.=$paypal_code;
	
			/*if($sub_pack==1){
				$str='<input type="hidden" name="p1" value="1">';
				$str.='<input type="hidden" name="t1" value="M">';
	            $str.='<input type="hidden" name="a3" value="20.00">';
			    $str.='<input type="hidden" name="p3" value="1">';
	            $str.=' <input type="hidden" name="t3" value="M">';
	            $str.='<input type="hidden" name="src" value="0">';
	            $str.=' <input type="hidden" name="sra" value="">';
			}
			else if($sub_pack==2){
			
				$str.='<input type="hidden" name="p1" value="3">';
				$str.='<input type="hidden" name="t1" value="M">';
				$str.='<input type="hidden" name="a3" value="54.00">';
				$str.='<input type="hidden" name="p3" value="3">';
				$str.='<input type="hidden" name="t3" value="M">';
				$str.='<input type="hidden" name="src" value="0">';
				$str.='<input type="hidden" name="srt" value="">';
			}
			else if($sub_pack==4){
					$str.='<input type="hidden" name="p1" value="6">';
					$str.='<input type="hidden" name="t1" value="M">';
					$str.='<input type="hidden" name="a3" value="102.00">';
					$str.='<input type="hidden" name="p3" value="6">';
					$str.='<input type="hidden" name="t3" value="M">';
					$str.='<input type="hidden" name="src" value="0">';
					$str.='<input type="hidden" name="srt" value="">';
			}
			else if($sub_pack==6){
			
					$str.='<input type="hidden" name="p1" value="1">';
					$str.='<input type="hidden" name="t1" value="Y">';
					$str.='<input type="hidden" name="a3" value="192.00">';
					$str.='<input type="hidden" name="p3" value="1">';
					$str.='<input type="hidden" name="t3" value="Y">';
					$str.='<input type="hidden" name="src" value="0">';
					$str.='<input type="hidden" name="srt" value="">';
			}		*/
			
			$str .='<input type="hidden" name="subpack_amt" id="subpack_amt" value="'.$sub_det["fees"].'">';
			
			echo $str;
			
			
			break	;
			
		case 'load_reg_pack_price':
			$pck_det = $objUser->getPackageDetails($_REQUEST['id']);
			$str='<input type="hidden" name="reg_amt" id="reg_amt" value="'.$pck_det["fee"].'">';
			echo $str;
			
			break;
				
		case "validstore":
		if ($objUser->validStore($store_name))
		{
			echo "<strong class='greenClass'>Store URL Available</strong>";
			exit;
		}
		else 
		{
			echo "<strong class='redClass'>Store URL already taken</strong>";
			exit;
		}
		break;	
		
		case "validemail":
		if (!$objUser->checkEmail($_REQUEST['email'],$_REQUEST['user_id'],$_REQUEST['store_id'],$_REQUEST['retailer']))
		{
			echo "<strong class='greenClass'>Email Validated</strong>";
			exit;
		}
		else 
		{
			echo "<strong class='redClass'>Email already exists</strong>";
			exit;
		}
		break;	
		
		case "validuname":
		
		if(!ctype_alnum($_REQUEST['uname'])) 
		{
			echo "<strong class='redClass'>Use only alphanumeric characters</strong>";
			exit;
		}
		else 
		{
			
			
			if (!$objUser->getUsernameDetails($_REQUEST["uname"],$_REQUEST['store_id']))
			{
				echo "<strong class='greenClass'>Username Available</strong>";
				exit;
			}
			else 
			{
				echo "<strong class='redClass'><font color='red'>Username already exists</font></strong>";
				exit;
			}
		}
		break;
			
}

?>