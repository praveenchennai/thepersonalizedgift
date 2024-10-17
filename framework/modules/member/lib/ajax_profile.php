<?php
/**
 * 
 *
 * @author Adarsh v.s
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

switch ($_REQUEST["act"])
{
	case "load_profile_question":
		$limit = 1;
		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "1";
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "load_profile_question";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
		$_SESSION['$pageNo']= $page;
		if($_SESSION['sel_qid']){
			$selQids=$_SESSION['sel_qid'];
			
		}
		list($res, $numpad, $cnt, $limitList)=$objUser->getProfileQuestionsAjax($_REQUEST['pageNo'], 6, $par, ARRAY_A, $_REQUEST['orderBy'],0,0,0,0,0);
			
		$startpoint = $pageNo+1;
		$endpoint = $pageNo+$show;
		
		if ($cnt1 < $endpoint)
		$endpoint=$cnt1; 
		$str='';
		
		if(count($res)>0)
		{
			$str.='<table width="100%"  border="0" align="left"  cellpadding="0" cellspacing="0" class="table-curve-gray">
                      <tr>
                        <td width="100%" colspan="3"  style="padding-left:20px" height="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;please select five questions</td>
                      </tr>
					  ';
					  
			   foreach($res as $k=>$val)	  
			   {
					if(in_array($val['qid'],$selQids)){
						$chkValue="checked";
					}
					else{
						$chkValue="";
					}
                 $str.='<tr>
                        <td  width="20%" height="30" align="right" class="bodytext"><input type="checkbox"  name="qid" id="qid'.$k.'" class="input" value="'.$val['qid'].'" onClick="storeQid('.$val['qid'].',this.value);" '.$chkValue.'/></td>
                        <td width="3%">&nbsp;</td>
                        <td align="left" width="73%"><span class="bodytext">'.$val['question'].'</span></td>
                      </tr>';
			   }		  
               $str.='<tr>
                        <td height="30" align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="left"><input type="image" src="'.$global["tpl_url"].'/images/submit.jpg" alt="" onClick="chooseQuestion();return false;"></td>
                      </tr>
			   		  <tr>
                        <td height="15" align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">'.$numpad.'</td>
                      </tr>
					   <tr>
                        <td height="15" align="right"><input type="hidden" name="sel_qcount" id="sel_qcount" value="'.count($_SESSION['sel_qid']).'"/></td>
                        <td>&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
					  
                    </table>';
		}
		else
		{
			$str.="<div>No item found</div>";
		}		
			echo $str;
			exit;		
		break;	
	case "add_question":
		$memberId=$_SESSION["memberid"];
		$selected_question_ids=$_SESSION['sel_qid'];
		if(count($selected_question_ids)== $global['profile_question_count'])
		{
			for($i=0; $i< count($selected_question_ids);$i++)
			{
				$row=$objUser->getProfileQuestionDetails($selected_question_ids[$i]);
				$array=array("user_id"=>$memberId,"ref_qid"=>$row['ref_qid'],"question"=>$row['question']);
				$id=$objUser->db->insert("member_profile_question", $array);
			}
			unset($_SESSION['sel_qid']);
			echo "true";
		}
		else
		{
			echo "false";
		}
		exit;
		
	break;
	case "choose_question":
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/choose_question.tpl");
		break;
	case "store_qid":
		$qid=$_REQUEST['qid'];
		
		if($_SESSION['sel_qid']){
			$array=$_SESSION['sel_qid'];
		}
		else{
			$array=array();
		}
		if(in_array($qid,$array)){
			array_pop($array);
		}
		else{
			array_push($array,$qid);
		}	
		$_SESSION['sel_qid']=$array;
		echo count($array);
		exit;
		break;	
	case "view_questions":
	
		$memberId=$_SESSION["memberid"];
		$page=$_REQUEST['page'];
	
		if (isset($page))
		{
  		  $CurPage = $page;
		}
		else
		{
  		  $CurPage = 0;
		}
		$rowspage = 1;
		$start = $CurPage * $rowspage;
		
		$rs=$objUser->getQuestionsListByUsers($memberId,$start,$rowspage);
		
		$totalrecords =$global['profile_question_count']-1;
		$lastpage = ceil($totalrecords/$rowspage);
		if ($CurPage == $lastpage)
		{
			$framework->tpl->assign("NEXT",'false');
			$_SESSION['profile_question_next']='false';
		}
		else
		{
    		$nextpage = $CurPage + 1;
			$framework->tpl->assign("NEXTPAGE",$nextpage);
			$framework->tpl->assign("NEXT",'true');
    
		}
		if ($CurPage == 0)
		{
			$framework->tpl->assign("PREV",'false');
		}
		else
		{
   			 $prevpage = $CurPage - 1;
			 $framework->tpl->assign("PREVPAGE",$prevpage);
			 $framework->tpl->assign("PREV",'true');
		}
	
		$framework->tpl->assign("QLIST",$rs[0]);
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/view_question.tpl");
		break;	
	case "submit_ans":
		$id=$_REQUEST["id"];
		$ans=$_REQUEST['ans'];
		
		$array=array("answer"=>$ans);
		$objUser->setArrData($array);
		$objUser->submitAns($id);
		
		break;	
	case "post_comment":
		$post_det = array();
		$post_det["comment"]  = $_REQUEST["comment"];
		$uid= $_REQUEST["uid"];
		$objUser->insertComment($post_det,$uid);
		exit;
		break;	
	case "comments":
		$limit = 1;
		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "3";
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "comments";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
		$_SESSION['$pageNo']= $page;
		
		list($rs1, $numpad1, $cnt1, $limitList1)=$objUser->getProfileComments($_REQUEST['pageNo'], 3, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid'],'profile',2);
		$comments_startpoint = $comments_PageNo+1;
		$comments_endpoint = $comments_PageNo+$show;
		if ($cnt1 < $comment_endpoint)
		$comment_endpoint=$cnt1; 
		$k=0;
		
		$str='<table width="656" border="0" cellpadding="8"  cellspacing="0">
				  <div  id="div_comments">';
					foreach($rs1 as $key=>$value)
					 {
					  $k++;
					  $str.=' <div id="cdiv" style="display:block">
								  <tr>
                                    <td width="59" height="79" align="center"><table width="59" border="0" cellpadding="0" cellspacing="3" class="border1" style="background-color:#FFFFFF;"  >
                                        <tr>
                                          <td width="51" height="55"><img src="'.$global['tpl_url'].'/images/nophoto1.jpg" width="51" height="55" alt="" /></td>
                                        </tr>
                                    </table></td>
                                    <td width="436" align="left" valign="middle" class="bodytext"><a href="#" class="blacktext2">'.$value['comment'].'</a></td>
                                    <td width="111" align="center" valign="middle" class="bodytext"><a href="#" class="blacktext2">Date: '.$value['pdate'].' </a></td>
                                  </tr>';
							 if(count($rs1)!==$k)
							 {
                       		  $str.=' <tr>
                                    <td height="1" colspan="3" align="center"><a href="#" class="blacktext2"></a>
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#FFFFFF;"  >
                                          <tr>
                                            <td width="51" height="1" bgcolor="#EBEBEB"></td>
                                          </tr>
                                      </table></td>
                                  </tr>';
							}
								$str.='</div>';
					}
					$str.='</div>
                               <tr>
                                   <td height="30" colspan="3" align="center"><span class="smalltext">'.$numpad1.'</span></td>
                                </tr>
						</table>';
		echo $str;							
		exit;
		break;	
	case "addfriend";
		$memid = $_SESSION["memberid"];
		$fdet = $objUser->getUsernameDetails($_REQUEST["uname"]);
		$user_det = $objUser->getUserdetails($memid);
		$msg=$objUser->addAsFriend($memid,$fdet["id"]);
		echo $msg;
		exit;
		break;	
	case "my_frnds";
	
		$memberId=$_SESSION["memberid"];
		
		$page=$_REQUEST['page'];
	
		if (isset($page))
		{
  		  $CurPage = $page;
		}
		else
		{
  		  $CurPage = 0;
		}
		$rowspage = 4;
		
		$start = $CurPage * $rowspage;
		
		$rs=$objUser->getFriendsListByUsers($memberId,$start,$rowspage);
		
		$totalrecords =$objUser->getFriendsCount($memberId);
		
		$lastpage = ceil($totalrecords/$rowspage)-1;
		
		if ($CurPage == $lastpage)
		{
			$framework->tpl->assign("NEXT_FRND",'false');
		}
		else
		{
    		$nextpage = $CurPage + 1;
			$framework->tpl->assign("NEXT_FRND_PAGE",$nextpage);
			$framework->tpl->assign("NEXT_FRND",'true');
    
		}
		if ($CurPage == 0)
		{
			$framework->tpl->assign("PREV_FRND",'false');
		}
		else
		{
   			 $prevpage = $CurPage - 1;
			 $framework->tpl->assign("PREV_FRND_PAGE",$prevpage);
			 $framework->tpl->assign("PREV_FRND",'true');
		}
		$framework->tpl->assign("FRNDS_LIST",$rs);
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/my_circle.tpl");
		exit;
		break;	
	case "updates":
		$post_det = array();
		$post_det["comment"]  = $_REQUEST["comment"];
		$cid=$_SESSION['memberid'];
		$objUser->insertComment($post_det,$cid,'updates');
		exit;
		break;
	case "updates_list":
		$limit = 1;
		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "3";
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "updates_list";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
		$_SESSION['$pageNo']= $page;
		
		list($rs1, $numpad1, $cnt1, $limitList1)=$objUser->getProfileComments($_REQUEST['pageNo'], 3, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid'],'updates',3);
		$comments_startpoint = $comments_PageNo+1;
		$comments_endpoint = $comments_PageNo+$show;
		if ($cnt1 < $comment_endpoint)
		$comment_endpoint=$cnt1; 
		$k=0;
		
		$str='<table width="657" border="0" cellpadding="8" cellspacing="0" class="table-white">';
                              
	       foreach($rs1 as $key=>$value)
			{
			$k++;	
			$str.=	'<tr>
                        <td width="59" align="center"><table width="59" border="0" cellpadding="0" cellspacing="3" class="border1" style="background-color:#FFFFFF;"  >
                           <tr>
                                   <td width="51" height="55"><img src="'.$global['tpl_url'].'/images/nophoto1.jpg" width="51" height="55" alt="" /></td>
                               </tr>
                                    </table></td>
                                    <td width="434" align="left" valign="top" class="bodytext">'.$value['comment'].'</td>
                                    <td width="114" align="center" valign="middle" class="bodytext"><a href="#" class="blacktext2">Date: '.$value['pdate'].'  </a></td>
                                  </tr>';
								  
								   if($k != $i)
								   {
                            	  $str.='<tr>
                                    <td height="1" colspan="3" align="center"><a href="#" class="blacktext2"></a>
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#FFFFFF;"  >
                                          <tr>
                                            <td width="51" height="1" bgcolor="#EBEBEB"></td>
                                          </tr>
                                      </table></td>
                                  </tr>';
								 }
								
                      }            
                         $str.=	'<tr>
                                    <td colspan="3" align="center">'.$numpad1.'</td>
                                    </tr>
                              </table>';
		echo  $str;					  
		exit;
		break;
		case "profile_list":
		
			
			$limit = 1;
			$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "6";
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "profile_list";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
			$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
			$_SESSION['$pageNo']= $page;
			
			$search_criteria=$_REQUEST['search_criteria'];
			$search_value=$_REQUEST['search_value'];
			$gender=$_REQUEST['gender'];
			$age_from=$_REQUEST['age_from'];
			$age_to=$_REQUEST['age_to'];
			$martial_status=$_REQUEST['martial_status'];
			$intention=$_REQUEST['intention'];
			$ethnicity=$_REQUEST['ethnicity'];
			$body_type=$_REQUEST['body_type'];
			$body_type=$_REQUEST['body_type'];
			
			$hrange_from_ft=$_REQUEST['height_range_from_ft'];
			$hrange_from_in=$_REQUEST['height_range_from_in'];
			
			$hrange_to_ft=$_REQUEST['height_range_to_ft'];
			$hrange_to_in=$_REQUEST['height_range_to_in'];
			$state=$_REQUEST['state'];
			$zip=$_REQUEST['zip'];
			$image_flag=$_REQUEST['image_flag'];
			$occupation=$_REQUEST['occupation'];
			$business_relation=$_REQUEST['business_relation'];
			
			
			list($rs, $numpad, $cnt, $limitList) = $objUser->profileListByAjax($_REQUEST['pageNo'], 6, $param, ARRAY_A, $_REQUEST['orderBy'],$search_criteria,$search_value,$gender,$age_from,$age_to,$martial_status,$ethnicity,$body_type='',$hrange_from_ft,$hrange_from_in,$hrange_to_ft,$hrange_to_in,$state,$zip,$image_flag,$occupation,$business_relation);
			
			$startpoint = $PageNo+1;
			$endpoint = $PageNo+$show;
			if ($cnt < $endpoint)
			$endpoint=$cnt1; 
			$str='';
			$k=0;
			$str.='<table  width="100%" border="0" cellspacing="0" cellpadding="0" >
				   <tr>';
			if(count($rs)>0)	   
			{
			foreach($rs as $key=>$value)
			{
			 $k++;
			 $str.='<td width="50%"><table width="323" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="117" height="61" align="left"><table width="59"  border="0" cellpadding="0" cellspacing="3" class="border1" style="background-color:#FFFFFF;"  >
                            <tr>
                                  <td width="51" height="55"><img src="'.$global['tpl_url'].'/images/nophoto1.jpg" width="89" height="97" alt="" /></td>
                            </tr>
                          </table></td>
                          <td width="206" align="left" valign="top" class="leftlink"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                   <td height="20" align="left" valign="top" class="leftlink"><strong>'.$value['first_name'].'&nbsp;'.$value['last_name'].'</strong></td>
                            </tr>
                            <tr>
                                   <td height="20" align="left" class="blacktext2">02/26/08</td>
                            </tr>
                            <tr>
                                  <td height="20" align="left" class="blacktext2">Message goes here</td>
                            </tr>
                            </table></td>
                            </tr>
                               <tr>
                                       <td height="19" align="left">&nbsp;</td>
                                        <td align="center">&nbsp;</td>
                                </tr>
                              <tr>
                                      <td height="19" align="left">&nbsp;</td>
                                                      <td valign="top" class="blacktext2">&nbsp;</td>
                                </tr>
                               </table></td>';
								if( $k %2 ==0){
									$str.='<tr/><tr>';
								}
			}
			}
			else
			{
					$str.=' <tr>
									
                                  <td height="20" align="center" class="blacktext2" ><strong><span  style="color:#FF0000 " > No recoed found</span></strong></td>
                            </tr>';
			}
			$str.='<tr>
                            <td height="19" align="left" colspan="2">'.$numpad.'</td>
                   </tr>';
			$str.='</tr></table>';
			echo $str;					
			exit;
			break;
		
		
		
		
	
}

?>