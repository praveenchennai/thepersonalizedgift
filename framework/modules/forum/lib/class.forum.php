<?php
/**
 * Forum Admin
 *
 * @author Ajith
 * @package defaultPackage
 */
class Forum extends FrameWork {
  var $cat_name;
  var $active;
  var $topic_name;
  var $topic_desc;
  var $errorMessage;

  function Forum($cat_name="", $active="",$topic_name="",$topic_desc="") {
	$this->cat_name = $cat_name;
	$this->active = $active;
	$this->topic_name=$topic_name;
	$this->topic_desc=$topic_desc;
    $this->FrameWork();
  }
	/**
	 * Category List
	 *
	 * @param <Page Number> $pageNo
	 */
	function CategoryList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
    $sql		= "SELECT * FROM master_category mc,module m,category_modules cm  WHERE mc.parent_id=0 and mc.category_id=cm.category_id and cm.module_id=m.id and m.folder='forum'";	
	//print_r($sql);exit;
    $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }

  /**
	 * Get Category Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function CategoryGet ($id) {
    $rs = $this->db->get_row("SELECT * FROM master_category WHERE category_id='{$id}'", ARRAY_A);
    return $rs;
  }
/**
	 * Add Edit Categories
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
  function categoryAddEdit (&$req,$file,$tmpname) {  
    extract($req);
	if ($file){							
		$dir=SITE_PATH."/modules/forum/images/category/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1);	
	}
    if(!trim($cat_name)) {
      $message = "Category name is required";
    } else {
		if ($file){
     		 $array = array("category_name"=>$cat_name,"category_description"=>$cat_desc,"category_image"=>$file,"active"=>$active);
		}else{
			$array = array("category_name"=>$cat_name,"category_description"=>$cat_desc,"active"=>$active);
		}
      if($id) {
        $array['id'] = $id;
        $this->db->update("master_category", $array, "category_id='$id'");
      } else {
        $this->db->insert("master_category", $array);
        $id = $this->db->insert_id;
      }     
      return true;
    }
    return $message;
  }
  
  //////////////////////////
  
  function ReplyAdd (&$req,$user_id) {  
   extract($req); 
   
  // print_r($req);
   //exit;  
   if($req['parent_id']=="") $parent_id=0;
    if(!trim($body_html)) {
      $message = "...Comments is required...";
        return $message;
    } else {
		$datevar=date("Y-m-d H:i:s");
        $array = array("cat_id"=>$cat_id,"subject"=>$subject,"body"=>$body_html,"topic_id"=>$id,"user_id"=>$user_id,"parent_id"=>$parent_id,"posted_date"=>$datevar);
		//print_r($array);exit;
		$this->db->insert("forum_thread", $array);
        $id = $this->db->insert_id;
       
      return true;
    }
  
  }
  
   //////////////////////////
  
  function postReplyAdd (&$req,$user_id) {  
   extract($req); 
   if($req['parent_id']=="") $parent_id=0;
    if(!trim($body_html)) {
      $message = "...Comments is required...";
        return $message;
    } else {
		$datevar=date("Y-m-d H:i:s");
        $array = array("cat_id"=>$cat_id,"subject"=>$subject,"body"=>$body_html,"topic_id"=>$topic_id,"user_id"=>$user_id,"parent_id"=>$parent_id,"posted_date"=>$datevar);
		$this->db->insert("forum_thread", $array);
        $id = $this->db->insert_id;
      return true;
    }
  
  }
  ////////////////////
 function CommentReplyAdd (&$req,$user_id) {  
   extract($req);
  
	$var='commentreply'.$myid;
	foreach($req as  $key=>$value)
	{
	if($key==$var)
	{
	$chk=$value;
	}
	
	}
	
   if(!trim($chk)) {
      $message = "...Reply is required...";
      return $message;
  } else {
		$datevar=date("Y-m-d H:i:s");
        $array = array("cat_id"=>$cat_id,"body"=>$chk,"topic_id"=>$id,"parent_id"=>$myid,"user_id"=>$user_id,"posted_date"=>$datevar);
		$this->db->insert("forum_thread", $array);
        $id = $this->db->insert_id;
        return true;
   	}
	
  } 
  ////////////////////////////////////////
	/**
	 * Delete Forum Category
	 * @param <id> $id	
	 */
  function categoryDelete ($id) {
    $this->db->query("DELETE FROM master_category WHERE category_id='$id'");
  }
  /**
	 * Getting Topic List
	 * @param <cat_id> $section
	 * @param <Page Number> $pageNo
	 */
  function TopicList ($section,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
   if($section!=""){    	
		$sql		= "SELECT a.id as id,a.topic_name,a.topic_desc,a.active,b.category_id,b.category_name,c.cat_id,c.forum_topic_id FROM forum_topic a,master_category b,forum_category c WHERE c.cat_id=b.category_id AND c.forum_topic_id=a.id AND c.cat_id=$section";
		
		
    }else{		
		$sql		= "SELECT a.id as id,a.topic_name,a.topic_desc,a.active,b.category_id,b.category_name,c.cat_id,c.forum_topic_id FROM forum_topic a,master_category b,forum_category c WHERE c.cat_id=b.category_id AND c.forum_topic_id=a.id";
	}
	if ($this->config['member_screen_name']=='Y')
				{
				$pageNo=1;
				$limit='';
				}
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }

  /////////////////////
function UserTopicList ($section,$pageNo, $limit = '', $params='', $output=OBJECT, $orderBy) {
   

  if($section!=""){    	
		$sql		= "SELECT a.id as id,a.*,b.screen_name FROM forum_topic a,member_master  b WHERE a.user_id=b.id AND a.cat_id=$section order by posted_on DESC";
	
    }
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
	foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					if($this->config["new_album_functions"]==1){
						$rating=$this->GetRatingNew('forum_topic',$value[$i]->id,'topic');
					
						//$favcnt ==$this->GetFavCntNew('forum_topic',$value[$i]->id,'topic');
					}
					
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];
					
				

				}	
			}
		}
	
	
	
	//print_r($rs);
    return $rs;
  }
  
  function GetFavCntNew($table_name,$file_id,$type){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$sql = "SELECT count(id) as cnt  FROM `rating`";
			$sql = $sql. " where file_id=$file_id and type='$type' and table_id=$tbid";
			echo $sql ;
			$RS = $this->db->get_results($sql,ARRAY_A);
			return $RS[0]['cnt'];
		}
	}
  ////////////////////////////
  function GetRatingNew($table_name,$file_id,$type){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$sql = "SELECT (sum(mark)/count(mark)) as Rate,count(mark) as cnt  FROM `rating`";
			$sql = $sql. " where file_id=$file_id and type='$type' and table_id=$tbid";
			$RS = $this->db->get_results($sql,ARRAY_A);
			//print_r($RS);
			$first  = substr($RS[0]['Rate'],0,1);
			//echo $first;
			$second = substr($RS[0]['Rate'],1,2);
			//echo $second;
			$second="0".$second;
			
			if($second>=0.5)
			{
				$rate=$first.".5";
			}
			else
			{
				$rate=$first;
			}
			//echo  $rate;
			$rating['rate'] = $rate;
			$rating['cnt']  = $RS[0]['cnt'];
			//print_r($rating);
			return $rating;
		}
		//
	}

function MyTopicList ($section,$pageNo, $limit = '', $params='', $output=OBJECT, $orderBy,$user_id) {
   

  if($section!=""){    	
		$sql		= "SELECT a.id as id,a.*,b.screen_name FROM forum_topic a,member_master  b WHERE a.user_id=$user_id AND a.user_id=b.id AND a.cat_id=$section order by  posted_on DESC ";
	
    }
	
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }  
  ////////////////////////////
  /**
	 * Get Topic for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function TopicGet ($id) {
    $rs = $this->db->get_row("SELECT * FROM forum_topic WHERE id='{$id}'", ARRAY_A);
    return $rs;
  }
/**
	 * Add Edit Topics
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function topicAddEdit (&$req,$file,$tmpname,$cat_id="",$getID="",$table_id="") {
        
    extract($req);
	
	$dispFlag==0;
	$datevar=date("Y-m-d H:i:s");
	
if ($this->config['member_screen_name']=='Y')
		     {
			 $description=$body_html;
             }
			 else
			 {
			$description=$topic_desc; 
			 }
		
	if ($file){							
		$dir=SITE_PATH."/modules/forum/images/forumtopic/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1,50,35);	
	}
	$message="";
	$dispFlag=0;
	if(cat_id=="")
	{	
	if(!trim($section_id)) {
		$message = $message."Category";
		$dispFlag=1;
    }
	}
	
    if(!trim($topic_name)) {
		if(!($message)){
			$message = $message."Topic name";
		}else{
			$message = $message.","."Topic name";
		}
		$dispFlag=1;
		
		if(cat_id!="")
	
	{
		if(!trim($body_html)) {
		$message = $message.","."Description";
		$dispFlag=1;
    }
	}
	}else {
		if($file){	
		
		
	    		//$array = array("topic_name"=>$topic_name,"user_id"=>$user_id,"topic_desc"=>$topic_desc,"image"=>$file,"active"=>$active);
			$array = array("topic_name"=>$topic_name,"topic_desc"=>$description,"image"=>$file,"active"=>$active);
		}else{
			 $array = array("topic_name"=>$topic_name,"cat_id"=>$cat_id,"table_id"=>$table_id,"file_id"=>$req['file_id'],"user_id"=>$getID,"topic_desc"=>$body_html,"active"=>'y',"posted_on"=>$datevar);
			
		}
		
			  if($id) {
				$array['id'] = $id;
				$this->db->update("forum_topic", $array, "id='$id'");
				
			  } else {
			  
			       if($cat_id!="")
				  {   
				   $datevar=date("Y-m-d H:i:s");
				   $array = array("topic_name"=>$topic_name,"cat_id"=>$cat_id,"table_id"=>$table_id,"file_id"=>$req['file_id'],"user_id"=>$getID,"topic_desc"=>$req['body_html'],"image"=>$file,"active"=>'y',"posted_on"=>$datevar);
				 }
				
			  	$this->db->insert("forum_topic", $array);
				  if($cat_id=="")
				   {
				$id = $this->db->insert_id;
				$InsertCatforum="INSERT INTO forum_category(cat_id,forum_topic_id)VALUES($section_id,$id)";
				$this->db->query($InsertCatforum);
				}
			 }     
			 return true;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
	/**
	 * Delete Forum Topics
	 *
	 * @param $id	
	 */
  function topicDelete ($id) {
    $this->db->query("DELETE FROM forum_topic WHERE id='$id'");
  }
  function topicUpdate ($id) {
    $this->db->query("update forum_topic set view_no=view_no+1 where id=$id");
  }
  /**
	 *Listing Category in combo 
	 *
	 * return array
	 */
function menuSectionList () {
        $sql		= "SELECT DISTINCT(mc.category_id), mc.category_name FROM master_category mc,module m,category_modules cm  WHERE  mc.category_id=cm.category_id and cm.module_id=m.id and m.folder='forum'";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['cat_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	 /**
	 * Getting Thread List
	 *
	 * @param <topic_id> $section
	 * @param [Page Number] $pageNo
	 * return array 
	 */
  function ThreadList ($section,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
  if($section!=""){
    	$sql		= "SELECT a.id as id,a.topic_id,a.user_id,a.subject,a.posted_date,a.active,b.topic_name FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id AND a.topic_id=$section AND a.parent_id=0";
    }else{
		$sql		= "SELECT a.id as id,a.topic_id,a.user_id,a.subject,a.posted_date,a.active,b.topic_name FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id AND a.parent_id=0";
	}
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
	 /**
	 *Listing Topics in combo 	 
	 * return  array
	 */
function menuTopicList () {
        $sql		= "SELECT id, topic_name FROM forum_topic WHERE 1";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['topic_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	/**
	 * Delete Forum Threads
	 *
	 * @param <id> $id
	 */
  function threadDelete ($id) {
    $this->db->query("DELETE FROM forum_thread WHERE id='$id'");
  }
  /**
	 * Get Thread for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function ThreadGet ($id) {  
		$rs = $this->db->get_row("SELECT a.id as id,a.topic_id,a.user_id,a.subject,a.posted_date,a.body,a.active,b.topic_name,c.category_name,d.cat_id,d.forum_topic_id FROM forum_thread a,forum_topic b,master_category c,forum_category d WHERE a.id=$id AND a.topic_id=b.id AND d.cat_id=c.category_id", ARRAY_A);
		return $rs;
	}
	 /**
	 * Getting Post List
	 *
	 * @param <Thread_id> $section
	 * @param [page_no] $pageNo
	 */
  function postList ($section,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
  		$sql		= "SELECT a.id as id,a.* FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id  AND a.parent_id=$section";
		//echo $sql;
   		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
  
  function getReply($grid)
	{ $sql= "SELECT a.id as id,a.*,c.screen_name FROM forum_thread a,forum_topic b,member_master c WHERE a.topic_id=b.id AND a.user_id=c.id  AND a.parent_id=".$grid." order by posted_date DESC";
	   $RS = $this->db->get_results($sql,ARRAY_A);
	  // print_r($RS);
		return $RS;
	}
  
  
  
  
   function postViewList ($section,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
  		$sql		= "SELECT a.id as id,a.*,c.screen_name FROM forum_thread a,forum_topic b,member_master c WHERE a.topic_id=b.id AND a.user_id=c.id AND a.topic_id=$section AND a.parent_id=0 order by posted_date DESC";
		//echo $sql;
   		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		foreach ($rs as $value)
		{     for($i=0;$i<sizeof($value);$i++)
			{
		     if($value[$i]->id>0)
				{
				
				    $reply=$this->getReply($value[$i]->id);
					$rs[0][$i]->reply=$reply;
			    }
			}
		
		}
	//print_r($rs);	
    return $rs;
  }
 /////////////////////
 function AddRating($table_name,$file_id,$type,$mark,$memberid){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$arr=array();
			$arr["table_id"]	=	$tbid;
			$arr["file_id"]		=	$file_id;
			$arr["type"]		=	$type;
			$arr["mark"]		=	$mark;
			$arr["member_id"]	=	$memberid;
			$sql="Select id from rating where file_id=$file_id and member_id=$memberid  and table_id=$tbid";
			$count=count($this->db->get_results($sql));
			if($count==0)
			{
				$this->db->insert("rating", $arr);
				$msg="Your Rating has been stored.";
			}
			else
			{
			   $msg=("You have already rated this Topic!!!");
	
			}
		}else{
			$msg="Check the existance of $table_name";
		}
		return $msg;
	}


	function AddFavourites($table_name,$file_id,$type,$memberid){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$arr=array();
			$arr["table_id"]	=	$tbid;
			$arr["file_id"]		=	$file_id;
			$arr["type"]		=	$type;
			$arr["member_id"]	=	$memberid;
			$sql="Select id from favourites where file_id=$file_id and member_id=$memberid  and table_id=$tbid";
			$count=count($this->db->get_results($sql));
			if($count==0)
			{
				$this->db->insert("favourites", $arr);
				$msg="The file is added to your favourites.";
			}
			else
			{
			   $msg=("This file already exist in your favourites list.!!!");
	
			}
		}else{
			$msg="Check the existance of $table_name";
		}
		return $msg;
	}


  
  
  /**
	 * Delete Forum Threads
	 *
	 * @param <id> $id	
	 */
  function postDelete ($id) {
    $this->db->query("DELETE FROM forum_thread WHERE id='$id'");
  }
  
  	// this function is used to insert values into table media_rating, created by Jeffy on 4th september 2007.
	function insert_val($type, $fileid, $userid, $date, $mark, $tableid){
		mysql_query("INSERT INTO media_rating 
		(type, file_id, userid, postdate, mark, table_id) 
		VALUES('$type', '$fileid', '$userid', '$date', '$mark', '$tableid') ") 
		or die(mysql_error());
	}
	
	////////////////////////////
  /**
	 * Get Topic for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function AllTopicGetbyOrd ($id) {
    $rs = $this->db->get_results("SELECT * FROM forum_topic WHERE cat_id=$id");
    return $rs;
  }
    function ListAllnotes ($section,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
  		$sql		= "SELECT a.id as id,a.* FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id AND a.topic_id=$section order by posted_date DESC";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    	return $rs;
  }
  
  # for displaying the parent  first
	function getCategoryTreeParentLevel($parent,$section) {
		$catParent	=	0;
		$catChild	=	0;
		if($parent=="0" || $parent=="")
		{ $positionStr="";}
		else{ $positionStr	=	"";}
		if($parent=="0" || $parent=="")
		{ 
			$rs = $this->db->get_results("SELECT a.id as id,a.* FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id AND a.topic_id=$section AND a.parent_id=0 order by posted_date DESC"); 
		}
		else
		{
			$rs 	= $this->db->get_results("SELECT a.id as id,a.* FROM forum_thread a,forum_topic b WHERE a.topic_id=b.id AND a.topic_id=$section AND a.parent_id=$parent order by posted_date DESC ");
		}
		if($rs) {
			foreach ($rs as $key=>$row) {
				$arr[$key]['id']	= $row->id;
				$arr[$key]['parentId'] 	= $row->parent_id;
				$arr[$key]['body'] 	= $row->body;
				$arr[$key]['posted_date'] 	= $row->posted_date;
				 
				}
		} 
		
		return $arr;
	}
	
	function getTopicThread($file_id,$table_id) {
  		
		$sql		= "SELECT T1.id as topicID,T1.* FROM  forum_topic T1 WHERE T1.file_id=$file_id AND table_id = $table_id  order by posted_on DESC";
		$rs = $this->db->get_results($sql,ARRAY_A);
		if(count($rs)>0){
			foreach ($rs as $key=>$value){
				$reply=$this->get_thread($value[topicID]);
				$rs[$key][reply]=$reply;
			}
		}
		
    return $rs;
  }
  
  function get_thread($grid)
	{ 
	 	$sql= "SELECT T1.id AS threadID,T1.*,T2.username FROM forum_thread T1 LEFT JOIN member_master T2 on (T1.user_id=T2.id) WHERE T1.topic_id=".$grid." AND T1.parent_id=0 order by posted_date ASC";
	 	$RS = $this->db->get_results($sql,ARRAY_A);
		if(count($RS)>0){
			foreach ($RS as $key=>$value)
			{    
			 if($value[threadID]>0)
				{
					 $reply=$this->get_threadReply($value[threadID]);
					 $RS[$key][reply_reply]=$reply;
				}
			
			}
		}
		return $RS;
	}
  function get_threadReply($grid)
	{ 
		$sql= "SELECT T1.id as id,T1.*,T3.username FROM forum_thread T1 LEFT jOIN member_master T3 on (T1.user_id=T3.id) WHERE  T1.parent_id=".$grid." order by posted_date ASC";
	     $RS = $this->db->get_results($sql,ARRAY_A);
	     return $RS;
	}

}//class
?>