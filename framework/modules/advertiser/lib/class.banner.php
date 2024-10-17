<?php
class Banner extends FrameWork {
  var $ban_name;
  var $active;
  var $content;
  var $plan_id; 
  var $user_id; 
  function Banner($ban_name="", $active="",$content="",$plan_id="") {
	$this->ban_name = $ban_name;
	$this->active = $active;
	$this->content=$content;
	$this->plan_id=$plan_id;
	$this->user_id=$_SESSION['memberid'];	
    $this->FrameWork();
  }
	/**
	 * Plan List
	 *
	 * @param <Page Number> $pageNo
	 */
	function planList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
    $sql	= 	"SELECT a.id as plan_id,a.*,b.* FROM ban_plans a,ban_campaigns b  WHERE  a.camp_id=b.id AND a.active='Y'";		
    $rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }

  /**
	 * Get Plan Details  for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getPlans ($id) {
    $rs = $this->db->get_row("SELECT a.*,b.camp_name,b.camp_width,b.camp_height FROM  ban_plans a,ban_campaigns b WHERE a.id='{$id}' AND a.camp_id=b.id", ARRAY_A);
    return $rs;
  }
/**
	 * Add Edit Plans
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function planAddEdit(&$req,$file,$tmpname) {
    extract($req);	
	$sqlMax 	=	mysql_query("SELECT max( id ) as number FROM ban_plans");
	while($row=mysql_fetch_array($sqlMax)){ 						
		$number		=	$row["number"]+1;
	}
	if ($file){	
		$file_name=	$number."_".$file;							
		$dir=SITE_PATH."/modules/banner/images/plans/";
		$thumbdir=$dir."thumb/";
		$resource_file=$dir.$file_name;
		_upload($dir,$file_name,$tmpname,1);	
	}
	$file_type=$jpg.",".$gif.",".$swf.",".$png.",".$agif.",".$txt.",".$html.",".$flv;	
	$message="";
	if($file_name!=""){
		$plan_example=$file_name;
	}else if($dmo_content!=""){	
		$plan_example	=	$dmo_content;
	}else{
		$plan_example	=	$content;
	}
	if($dmo_uline==""){	
		$dmo_uline="N";
	}else{
		$dmo_uline="Y";
	}
	$dispFlag=0;	
	if(!trim($camp_id)) {
		if(!($message)){
			$message = $message."Campaigns";
		}else{
			$message = $message.","."Campaigns";
		}		
	}else {	
			$array = array("camp_id"=>$camp_id,"plan_name"=>$plan_name,"plan_desc"=>$plan_desc,
					"plan_price"=>$plan_price,"file_types"=>$file_type,"duration"=>$duration,"duration_type"=>$duration_type,
					"dmo_filetype"=>$dmo_filetype,"dmo_bg"=>$dmo_bg,"dmo_border"=>$dmo_border,
					"dmo_borderclr"=>$dmo_borderclr,"dmo_font"=>$dmo_font,"dmo_fontclr"=>$dmo_fontclr, 
					"dmo_fontsize"=>$dmo_fontsize,"dmo_linkcolor"=>$dmo_linkcolor,"dmo_uline"=>$dmo_uline,"plan_example"=>$plan_example,"dmo_url"=>$dmo_url);		
			if($id) {
				//$array['id'] = $id;
				$updateQuery=mysql_query("UPDATE ban_plans SET active='N' WHERE id='$id'");
				$this->db->insert("ban_plans", $array);
			  }else {					
				$this->db->insert("ban_plans", $array);
				$id = $this->db->insert_id;				
			 }     
			 return true;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
	/**
	 * Delete Banner plans
	 *
	 * @param $id	
	 */
  function bannerplanDelete ($id) {
    $this->db->query("UPDATE ban_plans set  active ='N' WHERE id='$id'");
  }
  /**
	 *Listing Campaigns in combo 
	 *
	 * return array
	 */
function campSectionList () {
        $sql		= "SELECT id, camp_name FROM  ban_campaigns WHERE active='Y'";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['camp_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	/**
	 * Campaign List
	 *
	 * @param <Page Number> $pageNo
	 */
	function campaignList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
    $sql	= 	"SELECT * FROM ban_campaigns  WHERE active='Y'";		
    $rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
  /**
	 * Delete Campaign
	 *
	 * @param $id	
	 */
  function campaignDelete($id) {
    $this->db->query("UPDATE  ban_campaigns set  active ='N' WHERE id='$id'");
  }
   /**
	 * Get campaign Details  for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getCampaign ($id) {
    $rs = $this->db->get_row("SELECT * FROM  ban_campaigns WHERE id='{$id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Add Edit Campaigns
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function campaignAddEdit(&$req) {
    extract($req);		
	$message="";
	$dispFlag=0;	
	if(!trim($camp_name)) {
		if(!($message)){
			$message = $message."Campaign Name";
		}else{
			$message = $message.","."Campaign Name";
		}		
	}
	  if(!trim($camp_width)){
		  if(!($message)){
				$message = $message."Campaign Width";
			}else{
				$message = $message.","."Campaign Width";
			}		
	  }
	  if(!trim($camp_height)){
	  	if(!($message)){
				$message = $message."Campaign Height";
			}else{
				$message = $message.","."Campaign Height";
			}		
	  }else {	
			$array = array("camp_name"=>$camp_name,"camp_width"=>$camp_width,"camp_height"=>$camp_height,"camp_limit"=>$camp_limit);
			$arrayUpdate = array("camp_name"=>$camp_name,"camp_width"=>$camp_width,"camp_height"=>$camp_height,"camp_limit"=>$camp_limit);
			if($id) {
				$array['id'] = $id;				
				$updateQuery=mysql_query("UPDATE ban_campaigns SET active='N' WHERE id='$id'");
				$this->db->insert("ban_campaigns", $arrayUpdate);
			} else {					
				$this->db->insert("ban_campaigns", $array);
				$id = $this->db->insert_id;				
			 }     
			 return true;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
  /**
	 * ads List
	 *
	 * @param <Page Number> $pageNo
	 */
	function adsList($user_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
	if($user_id!=""){
		$sql	= 	"SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
					 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id AND a.user_id='$user_id'";	
	}else{
		 $sql	= 	"SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
					 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id";
	}			
		$rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
   /**
	 *LListing Files in combo 
	 *
	 * return array
	 */
function fileSectionList () {
        $sql		= "SELECT type_val,type_display FROM  ban_filetypes WHERE 1";
        $rs['type_val'] = $this->db->get_col($sql, 0);
        $rs['type_display'] = $this->db->get_col("", 1);
        return $rs;
    }
	/**
	 *LListing Plans in combo 
	 *
	 * return array
	 */
function planSectionList ($camp_id,$file_types) {
        $sql		= "SELECT id,plan_name FROM  ban_plans WHERE active='Y' AND camp_id=$camp_id AND file_types Like '%$filetypes%'";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['plan_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	 /**
	 *LListing Files in combo 
	 *
	 * return array
	 */
function fontSectionList () {
        $sql		= "SELECT id,font_name FROM  font_list WHERE 1";
        $rs['font_name'] = $this->db->get_col($sql, 0);
        $rs['font_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	/**
	 * Add Edit Ads
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function adsAddEdit(&$req,$file,$tmpname) {
    extract($req);
	$cur_date=date("Y-m-d H:i:s");
	if(!$user_id)  
	$user_id=$_SESSION['memberid'];
	
	$sqlMax 	=	mysql_query("SELECT max( id ) as number FROM ban_banner");
	while($row=mysql_fetch_array($sqlMax)){ 						
		 $number		=	$row["number"]+1;
	}
	if ($file){	
		$save_path="/modules/advertiser/images/ads/thumb/";
		$file_name=	$number."_".$file;						
		$dir=SITE_PATH."/modules/advertiser/images/ads/";	
		$insFile=	$save_path.$number."_".$file;		
		$resource_file=$dir.$file_name;
		_upload($dir,$file_name,$tmpname,1,948,98);			
	}
	if($insFile){
		$content=$insFile;
	}else{
		$content=$content;
	}		
	$message="";
	$dispFlag=0;	
	if(!trim($camp_id)) {
		if(!($message)){
			$message = $message."Campaigns";
		}else{
			$message = $message.","."Campaigns";
		}	
		$dispFlag=1;	
	}
	if(!trim($file_type)){
		if(!($message)){
			$message = $message."File Type";
		}else{
			$message = $message.","."File Type";
		}
		$dispFlag=1;	
	}
	
	if(!trim($plan_id)){
		if(!($message)){
				$message = $message."Plan";
			}else{
				$message = $message.","."Plan";
			}
		$dispFlag=1;	
	}else {		
			$array = array("camp_id"=>$camp_id,"plan_id"=>$plan_id,"user_id"=>$user_id,"company_name"=>$company_name,
						"file_type"=>$file_type,"content"=>$content,"bg_color"=>$bg_color,"border"=>$border,					 
						"border_color"=>$border_color,"text_font"=>$text_font,"text_color"=>$text_color,
						"text_color"=>$text_color,"text_font_size"=>$text_font_size,"url"=>$url,"txtlink_nrl"=>$txtlink_nrl,
						"txtlink_nrl_uline"=>$txtlink_nrl_uline,"admin_banner"=>$admin_banner,"start_date"=>$cur_date);		
			$arrayupdate = array("camp_id"=>$camp_id,"company_name"=>$company_name,
						"file_type"=>$file_type,"content"=>$content,"bg_color"=>$bg_color,"border"=>$border,					 
						"border_color"=>$border_color,"text_font"=>$text_font,"text_color"=>$text_color,
						"text_color"=>$text_color,"text_font_size"=>$text_font_size,"url"=>$url,"txtlink_nrl"=>$txtlink_nrl,
						"txtlink_nrl_uline"=>$txtlink_nrl_uline);		
		
		if($id) {							
				$array['id'] = $id;			
				
				$this->db->update("ban_banner", $arrayupdate,"id=$id");
			  } else {					
				$this->db->insert("ban_banner", $array);
				$id = $this->db->insert_id;				
			 }     
			 return true;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
   /**
	 * Get UserDetails for Given user_id
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getUser ($id) {
 	 $query="SELECT m.*,c.country_name,ma.* FROM member_master m LEFT JOIN `member_address` ma on 
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) WHERE m.id='{$id}'";
    $rs = $this->db->get_row($query, ARRAY_A);
    return $rs;
  }
 /**
	 * Change the status
	 *
	 * @param $id	
	 */
  function bannerSuspent($id,$status) {
    $updateStatus=0;
	$errorMessage="";
    if($status=='A'){
		  $userID    =  $this->db->get_row("SELECT * FROM ban_banner WHERE id='$id'", ARRAY_A);
		  $uid	=	$userID['user_id'];
		  $userStat  =  $this->db->get_row("SELECT * FROM member_master WHERE id='$uid'", ARRAY_A);		 
		  if($userStat['active']=='N'){
		  	$updateStatus=1;		  	
		  }else{
		  	 $updateStatus=0;
		  }
	}
	if($updateStatus==0){
		$this->db->query("UPDATE  ban_banner set  status ='$status' WHERE id='$id'");
	}else{
	 	$errorMessage="Please activate the user before activating the Ads";
		return $errorMessage;
	}
  }  
  /**
	 * Delete Ads From the List
	 *
	 * @param $id	
	 */
  function banneradsDelete($id) {
  	$ads=$this->getAds($id);
	print_r($ads);
	$path=$ads['content'];
	$array=explode('/',$path);
	unset($array[5]);
	$path=implode('/',$array);
	$thumbdir=  SITE_PATH.$ads['content'];
	$dir=SITE_PATH.$path;
	if (file_exists($thumbdir)) {
	  unlink($thumbdir);
	   unlink($dir);	
	} 
    $this->db->query("DELETE FROM ban_banner  WHERE id='$id'");
  }  
  /**
	 * For Getting particular banner details
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getAds($id) {
    $rs = $this->db->get_row("SELECT a.*,b.plan_name,b.file_types FROM  ban_banner a,ban_plans b WHERE a.id='{$id}' AND a.plan_id=b.id", ARRAY_A);
    return $rs;
  }
   /**
	 * For searching Ads
	 * @param <POST/GET Array> $req
	 */
 	function adssearchList(&$req,$active,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		extract($req);		
		$message="";
		if(!trim($camp_id)) {
			if(!($message)){
				$message = $message."Campaigns";
			}else{
				$message = $message.","."Campaigns";
			}
			return $message;
		}else{		
			 $sql	= 	"SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
					 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id AND a.camp_id='$camp_id' AND a.status='A'";		
			if($file_type){
			 $sql	=	$sql." AND a.file_type='$file_type'";
			}
			
			if($txtkeyword!="" && $chkExact==""){
				$txtKeyArray=explode(" ",trim($txtkeyword));
				$countArr=count($txtKeyArray);			
				$sql=$sql." AND (";			
				for($i=0;$i<$countArr;$i++){			
					if($txtKeyArray[$i]!=""){
						if($i!=$countArr-1){
							$sql=$sql."  a.company_name Like '%$txtKeyArray[$i]%' OR a.url Like '%$txtKeyArray[$i]%' OR";
										
						}else{					
							$sql=$sql."  a.company_name Like '%$txtKeyArray[$i]%' OR a.url Like '%$txtKeyArray[$i]%'"; 
						}					
					}	
				}	
				$sql=$sql.")";		
			}						
			 $rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			 return $rs;
		}
	}	
	 /**
	 * For Getting particular banner details with campaigns and plan info
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getBanners($id) {  
    $rs = $this->db->get_row("SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
				 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id AND a.id='$id'", ARRAY_A);
    return $rs;
  }
  /**
	 * For updating no of click to particular ads
	 *
	 * @param <id> $ban_id
	 * @return Array
	 */
  function updateClick($ban_id) {
  	 $this->db->query("UPDATE ban_banner SET click_count=click_count+1 WHERE id=$ban_id");
  }
   /**
	 * Myaccount List
	 *
	 * @param <Page Number> $pageNo
	 */
	function accountList($pageNo,$limit = 10, $params='', $output=OBJECT, $orderBy) {
	$user_id=$_SESSION['memberid'];
    $sql	= 	"SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
				 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id AND a.admin_banner='N' AND d.id='$user_id'";		
    $rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  } 
   /**
	 * For Extending Expiry Date
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	
	 */
  function extendDate($req) {
  extract($req);
  $message="";
  	 $stat=$this->db->query("UPDATE ban_banner SET end_date='$exp_date' WHERE id='$ban_id'");
	 if($stat){
	 	$message	=	"Expiry Date Updated";
	 }
	 return $message;
  }
	/**
	 * For Renewing account
	 *
	 * @param <id> $ban_id
	 * @param <end_date> $ban_date
	 * @return  Message
	 */
	function renewAccount($ban_id,$ban_date,$noDays){		
		$stat	=	$this->db->query("update ban_banner set end_date=DATE_ADD('".$ban_date."', INTERVAL $noDays day) where id= $ban_id");
		if($stat){
			$message	=	"Account Renewed";		
		}
		return $message;
	}
	/**
	 * For Renewing account
	 *
	 * @param <id> $ban_id	
	 */
	function updateView($ban_id){	
	 $this->db->query("UPDATE ban_banner SET view_count=view_count +1 WHERE id='$ban_id'");
	}
	/**
	 * For generating Scripts
	 * @param <POST/GET Array> $req
	 */
 	function adsscriptList(&$req,$active,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			 extract($req);
			$sql	= 	"SELECT a.id as ban_id,a.*,c.* FROM ban_banner a,ban_campaigns c WHERE  a.camp_id='$camp_id' AND a.status='A' AND a.camp_id=c.id";
			 if($file_type){
					 $sql	=	$sql." AND a.file_type='$file_type'";
				}
		$sql	=	$sql."  ORDER BY RAND()";				
		$rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	/**
	 * For generating Scripts
	 * @param <POST/GET Array> $req
	 */
 	function adsscriptListall(&$req,$active,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		extract($req);
		$sql	= 	"SELECT a.id as ban_id,a.*,c.* FROM ban_banner a,ban_campaigns c WHERE  a.camp_id='$camp_id' AND a.status='A' AND a.camp_id=c.id";
			if($jpg!="" || $gif!="" || $swf!="" || $txt!="" || $png!="" || $agif!="" || $html!=""){
					 $sql	=	$sql." AND (file_type='$jpg' OR file_type='$gif' OR file_type='$png' OR file_type='$swf' OR file_type='$txt' OR file_type='$html')";
			}
		$sql	=	$sql."  ORDER BY RAND()";				
		$rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	 function getBannerRandom() {  
    $rs = $this->db->get_row("SELECT a.id as ban_id,b.id as plan_id,d.id as user_id,a.*,b.*,c.*,d.* FROM ban_banner a,ban_plans b,ban_campaigns c, member_master d
				 WHERE  a.camp_id=c.id AND a.plan_id =b.id AND a.user_id=d.id  ORDER BY RAND()", ARRAY_A);
    return $rs;
  }
}
?>