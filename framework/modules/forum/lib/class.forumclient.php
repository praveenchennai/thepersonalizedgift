<?php
/**
 * Forum Client
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
	function categoryList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	= "SELECT * FROM master_category mc,module m,category_modules cm  WHERE mc.parent_id=0 and mc.category_id=cm.category_id and cm.module_id=m.id and m.folder='forum' GROUP BY (cm.category_id)";	
		list($rs,$numpad) = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);	
			//print_r($rs);exit;
	
		if($rs){
			foreach ($rs as $key=>$row){		
				$cat_id= $row->category_id;
				$countThread=count($this->topiclistCount($cat_id));							
				$rs[$key]->topicList= $this->topicList($cat_id);
				//exit;
				if($countThread>2){				
					$rs[$key]->more=true;
				}						
			}
		}
		//print_r($rs);exit;
		return array($rs,$numpad);		
	}
	/**
	 * All Topic List
	 *
	 * @param <Page Number> $pageNo
	 */
	function topicListAll ($id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {

		$sql		= " SELECT a.id as id,
						a.cat_id,
						a.topic_name,
						a.topic_desc,
						a.thread_no,
						a.posts_no,
						a.view_no,
						a.last_postuser,
						a.image,
						DATE_FORMAT(a.last_post,'%W %M %Y') as postDate,
						DATE_FORMAT(a.last_post,'%H:%i:%s') as postTime,
						
						b.*  FROM forum_topic a,
						forum_category b
						
						WHERE b.cat_id=$id 
						AND a.id=b.forum_topic_id";	
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
	 * Get Topics Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function topicList($cat_id) {
	$query="SELECT a.id as id,
			a.cat_id,
			a.topic_name,
			a.topic_desc,
			a.thread_no,
			a.posts_no,
			a.view_no,
			a.last_postuser,
			a.image,
			DATE_FORMAT(a.last_post,'%W %M %Y') as postDate,
			DATE_FORMAT(a.last_post,'%H:%i:%s') as postTime,
			b.* FROM forum_topic a,
			forum_category b		
			WHERE b.cat_id='{$cat_id}' 
			AND a.id=b.forum_topic_id			
			LIMIT 0,2";
			//print_r($query);exit;
			
	$rs = $this->db->get_results($query);	
	return $rs;
	}
	 /**
	 * Get Topics Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function topiclistCount($cat_id) {
	$rs = $this->db->get_results("SELECT a.*,b.* FROM forum_topic a,forum_category b WHERE b.cat_id='{$cat_id}' AND b.forum_topic_id=a.id");	
	return $rs;
	}
	/**
	 * Get Topics Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getTopic($value) {
	$rs = $this->db->get_row("SELECT a.*,b.* FROM forum_topic a,forum_category b WHERE a.id='{$value}' AND a.id=b.forum_topic_id", ARRAY_A);	
	return $rs;
	}
	 /**
	 *Listing Topics in combo 
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */
function menuTopicList () {
        $sql		= "SELECT id, topic_name FROM forum_topic WHERE 1";		
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['topic_name'] = $this->db->get_col("", 1);		
        return $rs;
	}
	
	/**
	 * For Getting No of Threads
	 *
	 * @param <id> $id
	 * @return Array
	 */
	 
	function numThreads($value) {
		$rs = $this->db->get_row("SELECT * FROM forum_thread  WHERE topic_id='{$value}' AND parent_id=0", ARRAY_A);		
		$numThreads=count($rs);
		return $numThreads;
	}
	/**
	 * For listing Threads and  updating no of views
	 *
	 * @param <id> $id
	 * @return Array
	 */
	function threadList($topic_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$UpdateView="UPDATE forum_topic  SET view_no=view_no+1 WHERE id=$topic_id";			
		$this->db->query($UpdateView);
		$sql= "	SELECT a.id as id,
				a.cat_id,
				a.topic_id,
				a.user_id,
				a.parent_id,
				a.subject,
				a.body,
				DATE_FORMAT(a.posted_date,'%b %D %Y') as postDate1,
				DATE_FORMAT(a.posted_date,'%H:%i:%s %p') as postTime1,
				DATE_FORMAT(a.posted_date,'%W %M %Y') as postDate,
				DATE_FORMAT(a.posted_date,'%H:%i:%s') as postTime,
				a.post_reply, 
				a.views,a.
				last_postuser,
				a.image,
				DATE_FORMAT(a.last_post,'%W %M %Y') as lastDate,
				DATE_FORMAT(a.last_post,'%H:%i:%s') as lastTime,
				b.username as uname
				FROM forum_thread a,
				member_master b 
				WHERE a.topic_id=$topic_id
				AND a.parent_id=0
				AND a.user_id=b.id ";	
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy); 
		return $rs;
	}
	/**
	 * For listing Threads and  updating no of views by user id
	 * Author  :Jipson Thomas
	 * Dated   : 01/10/07
	 * @param <id> $uid
	 * @return Array
	 */
	function threadListUser($uid,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql= "	SELECT a.id as id,
				a.cat_id,
				a.topic_id,
				a.user_id,
				a.parent_id,
				a.subject,
				a.body,
				DATE_FORMAT(a.posted_date,'%W %M %Y') as postDate,
				DATE_FORMAT(a.posted_date,'%H:%i:%s') as postTime,
				a.post_reply, 
				a.views,a.
				last_postuser,
				a.image,
				DATE_FORMAT(a.last_post,'%W %M %Y') as lastDate,
				DATE_FORMAT(a.last_post,'%H:%i:%s') as lastTime,
				b.username as uname
				FROM forum_thread a,
				member_master b 
				WHERE a.user_id=$uid 
				AND a.user_id=b.id";	
				//print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy); 
		return $rs;
	}
	/**
	 * Get Topics Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getThread($thread_id) {
	$rs = $this->db->get_row("SELECT * FROM forum_thread WHERE id='{$thread_id}'", ARRAY_A);		
	return $rs;
	}
	/**
	 * Add Edit Threads
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
  function threadAddEdit (&$req,$file,$tmpname) {
    extract($req);
	if ($file){							
		$dir=SITE_PATH."/modules/forum/images/forumthread/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1);	
	}
	$message="";
	$dispFlag=0;	
    if(!trim($subject)) {
		if(!($message)){
			$message = $message."Subject";
		}else{
			$message = $message.","."Subject";
		}
		$dispFlag=1;
	}else {
			if($file){
			 $array = array("cat_id"=>$cat_id,"topic_id"=>$topic_id,"user_id"=>$user_id,"subject"=>$subject,"body"=>$body,"posted_date"=>$posted_date,"image"=>$file,"active"=>$active);
			 }else{
			 $array = array("cat_id"=>$cat_id,"topic_id"=>$topic_id,"user_id"=>$user_id,"subject"=>$subject,"body"=>$body,"posted_date"=>$posted_date,"active"=>$active);
			 }
			  if($id) {
				$array['id'] = $id;
				$this->db->update("forum_thread", $array, "id='$id'");
			  } else {
				$Update="UPDATE forum_topic  SET thread_no=thread_no+1 WHERE id=$topic_id";			
				$this->db->query($Update);
				$UpdateLastpost="UPDATE forum_topic  SET last_post='$posted_date',last_postuser='$last_postuser' WHERE id=$topic_id";				
				$this->db->query($UpdateLastpost);
				$this->db->insert("forum_thread", $array);
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
	 * For listing Posts and updating the Total no of views 
	 *
	 * @param <id> $id
	 * @return Array
	 */
	function postList($id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$updateThreadView="UPDATE forum_thread  SET views=views+1 WHERE id=$id";			
		$this->db->query($updateThreadView);
		$sql= "SELECT a.id as id, a.cat_id, a.topic_id, a.user_id, a.parent_id, a.subject, a.body, DATE_FORMAT(a.posted_date,'%W %M %Y') as postDate,DATE_FORMAT(a.posted_date,'%H:%i:%s') as postTime,DATE_FORMAT(a.posted_date,'%b %d, %h:%i %p') as postDate1,a.post_reply, a.views,a.last_postuser,a.image, DATE_FORMAT(a.last_post,'%W %M %Y') as lastDate,DATE_FORMAT(a.last_post,'%H:%i:%s') as lastTime,
		       b.first_name,b.last_name FROM forum_thread a INNER JOIN member_master b ON b.id=a.user_id WHERE  a.parent_id=$id";	
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy); 
		return $rs;
	}
	/**
	 * Add Edit Posts
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
  function postsAddEdit (&$req) {
    extract($req);	
	$message="";
	$dispFlag=0;	
    if(!trim($body)) {
		if(!($message)){
			$message = $message."Body";
		}else{
			$message = $message.","."Body";
		}
		$dispFlag=1;
	}else {
			$array = array("topic_id"=>$topic_id,"user_id"=>$user_id,"parent_id"=>$thread_id,"body"=>$body,"posted_date"=>$posted_date,"active"=>$active);
			 if($id) {
				$array['id'] = $id;
				$this->db->update("forum_thread", $array, "id='$id'");
			  } else {
				$UpdateTopic="UPDATE forum_topic SET posts_no=posts_no+1 WHERE id=$topic_id";			
				$this->db->query($UpdateTopic);
				$UpdateThread="UPDATE forum_thread SET post_reply=post_reply+1 WHERE id=$thread_id";			
				$this->db->query($UpdateThread);
				$UpdateLastpost="UPDATE forum_topic  SET last_post='$posted_date',last_postuser='$last_postuser' WHERE id=$topic_id";				
				$this->db->query($UpdateLastpost);
				$UpdateLastthreadPost="UPDATE forum_thread  SET last_post='$posted_date',last_postuser='$last_postuser' WHERE id=$thread_id";				
				$this->db->query($UpdateLastthreadPost);
				$this->db->insert("forum_thread", $array);
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
	 * Get Posts Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getPosts($post_id) {
	$rs = $this->db->get_row("SELECT * FROM forum_thread WHERE id='{$post_id}'", ARRAY_A);		
	return $rs;
	}
	/**
	 * Get a search Result 
	 *
	 * @param <req> $request
	 * @param <pageNo> $pageNo
	 * @return Array
	 */	
	function threadsearchList (&$req,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		extract($req);		
		$sql		= "	SELECT a.*,
						DATE_FORMAT(a.last_post,'%W %M %Y') as lastDate,
						DATE_FORMAT(a.last_post,'%H:%i:%s') as lastTime,
						b.username as uname
						FROM forum_thread a,
						member_master b,
						forum_topic c 
						WHERE a.parent_id=0
						AND a.user_id=b.id 
						AND a.topic_id=c.id ";	
		if($txtkeywords!="" && $chkExact==""){
			$txtKeyArray=explode(" ",trim($txtkeywords));
			$countArr=count($txtKeyArray);			
			$sql=$sql." AND (";			
			for($i=0;$i<$countArr;$i++){			
				if($txtKeyArray[$i]!=""){
					if($i!=$countArr-1){
						$sql=$sql."  a.subject Like '%$txtKeyArray[$i]%' OR a.body Like '%$txtKeyArray[$i]%' OR c.topic_name Like '%$txtKeyArray[$i]%' OR";
					}else{					
						$sql=$sql."  a.subject Like '%$txtKeyArray[$i]%' OR a.body Like '%$txtKeyArray[$i]%' OR c.topic_name Like '%$txtKeyArray[$i]%'";
					}					
				}	
			}	
			$sql=$sql.")";		
		}else if(($txtkeywords!="" && $chkExact!="")){
			$sql=$sql." AND ( a.subject Like '%$txtkeywords%' OR a.body Like'%$txtkeywords%')";
		}

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy); 		
		return $rs;
	}
	/**
	 * Get User Details for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function getUser($user_id) {
	$rs = $this->db->get_row("SELECT * FROM member_master  WHERE id='{$user_id}'", ARRAY_A);		
	return $rs;
	}
  //created by adrash to addd topics to from
// date 11-10-2007
function topicAddEdit (&$req,$file,$tmpname) {
  
    extract($req);
	
	if ($file){							
		$dir=SITE_PATH."/modules/forum/images/forumtopic/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1,50,35);	
	}
	$message="";
	$dispFlag=0;	
	if(!trim($cat_id)) {
		$message = $message."Category";
		$dispFlag=1;
    }
    if(!trim($topic_name)) {
		if(!($message)){
			$message = $message."Topic name";
		}else{
			$message = $message.","."Topic name";
		}
		$dispFlag=1;
	}else {
			if($file){			
			 $array = array("topic_name"=>$topic_name,"topic_desc"=>$topic_desc,"image"=>$file,"active"=>$active,"createdate"=>$createdate);
			}else{
			 $array = array("topic_name"=>$topic_name,"topic_desc"=>$topic_desc,"active"=>$active,"createdate"=>$createdate);
			}
			  if($id) {
				$array['id'] = $id;
				$this->db->update("forum_topic", $array, "id='$id'");
			  } else {
				$this->db->insert("forum_topic", $array);
				$id = $this->db->insert_id;
				$InsertCatforum="INSERT INTO forum_category(cat_id,forum_topic_id)VALUES($cat_id,$id)";
				$this->db->query($InsertCatforum);
				$groupArr=array("topic_id"=>$id,"groupname"=>$topic_name);
				$grid=$this->db->insert("group_master", $groupArr);
				$arr1["user_id"]=$req["user_id"];
				$arr1["joindate"]=$req["createdate"];
				$arr1["group_id"]=$id;
				$this->db->insert("group_members",$arr1);
			 }     
			 return $id;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
 ///addedit ends 
 /// created by adarsh 0n 12-10-2007 to get all the topic list
 
  function getTopicListDet ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$cat_id=0,$mgroup=0,$own=0,$userid=0,$stxt=0,$other='',$uid=0) {
  	
	$sort=$orderBy;
	$grpBy = false;
	if($other)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('forum_topic','d','a');
		if($other)
		{
			list($qry_cs)=$this->getCustomQry('forum_topic','aid',$other,$criteria,'a','d');	
		}
	}
	if($qry_cs)
		{
			$wh =retWhere($sql);
			$where=" $wh $qry_cs";
		}
	if($mgroup==0)
	{
		if($sort=="groupname" || $sort=="createdate" || $sort=="d.username")
		{
			$tbl   = "member_master";
			$fldnm = "members";
			$fld   = "count(b.id)";
			$sql = "SELECT a.*,c.category_name FROM forum_topic a INNER JOIN forum_category b on a.id=b.forum_topic_id INNER JOIN master_category c on  b.cat_id= c.category_id LEFT JOIN member_master d ON d.id=a.user_id ";
			if ($stxt!='')
				{
					$sql=$sql. " and  a.topic_name like '%$stxt%'";
				}
			$grpBy = true;
		}
		else{
		if($other)
		{
			$sql=  "SELECT a.*,c.category_name,$qry FROM forum_topic a INNER JOIN forum_category b on a.id=b.forum_topic_id INNER JOIN master_category c on  b.cat_id= c.category_id $join_qry 	$where ";
		}
		else
		$sql=  "SELECT a.*,c.category_name FROM forum_topic a INNER JOIN forum_category b on a.id=b.forum_topic_id INNER JOIN master_category c on  b.cat_id= c.category_id";
		}	
		if($cat_id>0)
		{
					
			$sql=$sql." WHERE b.cat_id=$cat_id";
				if ($stxt!='')
				{
					$sql=$sql. " and  a.topic_name like '%$stxt%'";
				}
		 }
		else
		{
			if ($stxt!='')
			{
				 $sql=$sql. " and  a.topic_name like '%$stxt%'";
			}
		}
	}
	else
	{
			$sql="SELECT a.*,c.category_name FROM forum_topic a INNER JOIN forum_category b on a.id=b.forum_topic_id INNER JOIN master_category c on  b.cat_id= c.category_id";
			$sql =$sql. " INNER JOIN group_master  d on a.id=d.topic_id ";
			$sql = $sql. "INNER JOIN group_members e ON e.group_id=d.topic_id   where " ;
			$sql = $sql. " e.user_id='$userid' and e.active='Y'";
			if ($stxt!='')
				{
					$sql=$sql. " and  a.topic_name like '%$stxt%'";
				}
	}	
	//echo $sql;
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
 //getTopicListDet ends
//created by adarsh on 12-10-2007 to get the details of topics
 function TopicGet ($id) {
    $rs = $this->db->get_row("SELECT * FROM forum_topic WHERE id='{$id}'", ARRAY_A);
    return $rs;
  }
 // TopicGet ends
 
 ////Function: createArticle ///Store the article id
/// Author adarsh v s
/// Date 30-10-2007
	function createArticle($arrData,$id) {

		$arr=$this->splitFields($arrData,'forum_topic');	
		if ($arr[1]["table_id"]>0)
		{
			$arr[1]["table_key"] = $id;
			$this->db->insert("custom_fields_list", $arr[1]);
		}
		
		return $id;
	}
////----------End-------------///
 /**
   * This is used to get the posting to a thread.
   * Author   : adarsh
   * Created  : 21/Desc/2007
   * Modified : 
   */
  function getPostList($id) {
		$sql= "SELECT a.id as id, a.cat_id, a.topic_id, a.user_id, a.parent_id, a.subject, a.body, DATE_FORMAT(a.posted_date,'%W %M %Y') as postDate,DATE_FORMAT(a.posted_date,'%H:%i:%s') as postTime,a.post_reply, a.views,a.last_postuser,a.image, DATE_FORMAT(a.last_post,'%W %M %Y') as lastDate,DATE_FORMAT(a.last_post,'%H:%i:%s') as lastTime  FROM forum_thread a WHERE  a.parent_id=$id ORDER BY posted_date DESC LIMIT 3";	
		$rs = $this->db->get_results($sql); 
		return $rs;
	}
  	
}// End of class
?>