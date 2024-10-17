<?php
/**
 * **********************************************************************************
 * @package    Album
 * @name       Photo
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2007 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  14-Aug-2006
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
class Photo extends FrameWork
{
    /*
    constructor
    */
    function Photo()
    {
        $this->FrameWork();

    }
	/**
    * Setting post Array Data
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function setArrData($szArrData)
    {
        $this->arrData = $szArrData;
    }
    /**
    * Setting return Array Data
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
    function getArrData()
    {
        return $this->arrData;
    }
	/**
    * Setting post error
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function setErr($szError)
    {
        $this->err .= "$szError";
    }
	/**
    * Setting return error
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
    function getErr()
    {
        return $this->err;
    }
	/**
    * To Update Photo Details
  	* Author   : Retheesh
  	* Created  : 22/December/2006
  	* Modified : 22/December/2006 By Retheesh
  	*/
	function updatePhoto($field_arr,$id,$tbname='',$typ='')
	{
			if($tbname!=''){
				$tblname=$tbname;
				$type=$typ;
			}else{
				$tblname='album_photos';
				$type='photo';
			}
			$arr = $this->splitFields($field_arr,$tblname);
					
			$arr[0]["id"] = $id;
			
			$this->db->update($tblname, $arr[0], "id='$id' AND user_id= {$_SESSION['memberid']}");
			$table_id=$arr[1]["table_id"];
			if ($table_id>0)
			{
				unset($arr[1]["table_id"]);
				if ($arr[1])
				{
					$sql_check = "select id from custom_fields_list where table_key=$id and table_id=$table_id";
					$rs_custom = $this->db->get_row($sql_check,0);
					if (count($rs_custom)>0)
					{
						$this->db->update("custom_fields_list",$arr[1] ,"table_key=$id and table_id=$table_id");
					}
					else 
					{
						$arr[1]["table_key"] = $id;
						$arr[1]["table_id"] =  $table_id;
						$this->db->insert("custom_fields_list", $arr[1]);
					}
				}	
			}	
	}
	/**
    * To Upload Photo Details
  	* Author   : Retheesh
  	* Created  : 22/December/2006
  	* Modified : 22/December/2006 By Retheesh
  	*/
	function uploadPhoto($album_id='',$crt = '',$i='',$updateid='',$tbname='',$typ='',$objAlb = '')
	{
//	print_r($updateid);exit;
		$global = $this->config;
		$arrData = $this->getArrData();
		
		
		
		$photos = array();
		$arr = array();
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		unset($arrData ["terms"]);
			if(is_numeric($i))
			{
				$imgArray = array("size" => $_FILES["photoImg"]["size"][$i],"type" => $_FILES["photoImg"]["type"][$i], "tmp_name" => $_FILES["photoImg"]["tmp_name"][$i]);
				$extension = strstr($_FILES["photoImg"]["name"][$i],".");
				
			}

			if($updateid >0)
			{
		
				/*
				Update
				*/		
				$id = $updateid;
				if($tblname=='album_photos'){
					$array = array("title" => $arrData["title"],"postdate" => $arrData["postdate"]);
				}else{
					$array = $arrData;
				}
				
				/* Created :Afsal Ismail
				/* Dated:27-12-2007
				/* Updating photos
				*/ 
				if($extension){
					$id = $updateid;
					
					
					
					if($tblname=='album_photos'){
					
					$photo_det = $objAlb->mediaDetailsGet($id,"photos");
					($photo_det['img_extension']!="") ? $img_ext = $photo_det['img_extension'] : $img_ext =".jpg";	
					
					unlink(SITE_PATH."/modules/album/photos/".$id.$img_ext);
					unlink(SITE_PATH."/modules/album/photos/thumb/".$id."_thumb2".$img_ext);
					unlink(SITE_PATH."/modules/album/photos/thumb/".$id.$img_ext);
					unlink(SITE_PATH."/modules/album/photos/resized/".$id.$img_ext);
					
					
					}
					else
					{
					
					$photo_det = $objAlb->mediaDetailsGet($id,"products");
					($photo_det['img_extension']!="") ? $img_ext = $photo_det['img_extension'] : $img_ext =".jpg";
						
					@unlink(SITE_PATH."/modules/album/products/".$id.$img_ext);
					@unlink(SITE_PATH."/modules/album/products/thumb/".$id."_thumb2".$img_ext);
					@unlink(SITE_PATH."/modules/album/products/resized/".$id."$img_ext");
					
					}
					
					
					
					$updateArray = array_merge($array,array("img_extension" => $extension));
					
				}
				else
				{
					$updateArray = $array;
					
				}
				
				$this->updatePhoto($updateArray,$updateid,$tblname,$type);	
				
			}
			else
			{
				/*
				insert
				*/
				
				
				$arrData = array_merge($arrData,array("img_extension" => $extension));
				$arr =  $this->splitFields($arrData,$tblname);
				
				
				
				 $id  =  $this->db->insert($tblname, $arr[0]);
				
				
				$arr[1]["table_key"]=$id;
				$this->db->insert("custom_fields_list", $arr[1]);
			}
			/*
			This part of realestatetube
			*/
			if($global["show_property"] == 1)
			{
				switch($crt)
				{
					case "M1":
						$type = "video";
					break;
					case "M2":
						$type = "photo";
				}
				
				$array = array("album_id"=>$album_id,"type"=>$type, "file_id"=>$id);
				
				if($updateid >0)
				{
					/*
				Update
				*/
					//$this->db->update("album_files", $array,"file_id=$updateid AND type = 'photo'");
				}
				else
				{
				/*
				insert
				*/
				
					$this->db->insert("album_files", $array);
				}
			}
			/**/
		
		
			if(sizeof($imgArray))
			{
				if($tblname=='album_photos'){
					$dir=SITE_PATH."/modules/album/photos/";
				}else{
					$dir=SITE_PATH."/modules/album/products/";
				}
				$thumbdir=$dir."thumb/";
				$redir=$dir."resized/";
				
				
				uploadImage($imgArray,$dir,"$id".$extension,1);
				chmod($dir."$id".$extension,0777);
				
				
				if ($this->config["photo_thumb1"])
				{
					$thumb_size  = explode(",",$this->config["photo_thumb1"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				}
				else 
				{
					$thumb_width = 100;
					$thum_height = 100;
				}
				
				thumbnail($dir,$thumbdir,"$id".$extension,$thumb_width,$thum_height,"","$id".$extension,0);
				chmod($thumbdir."$id".$extension,0777);
				
				//thumbnail($dir,$thumbdir,"$id.jpg",$thumb_width,$thumb_height,"",$id."_thumb2.jpg",0);
				
				if ($this->config["photo_resize"])
				{
				
					$thumb_size  = explode(",",$this->config["photo_resize"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				}
				else 
				{
					$thumb_width = 500;
					$thum_height = 500;
				}
				
				
				thumbnail($dir,$redir,"$id".$extension,$thumb_width,$thum_height,"","$id".$extension,0);
				chmod($redir."$id".$extension,0777);
				
				
				
				if ($this->config["photo_thumb2"])
				{
					$thumb_size  = explode(",",$this->config["photo_thumb2"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				
					thumbnail($dir,$thumbdir,"$id".$extension,$thumb_width,$thum_height,"","$id"."_thumb2".$extension,0);
					chmod($thumbdir."$id".$extension,0777);
				}
				if ($this->config["photo_thumb3"])
				{
					$thumb_size  = explode(",",$this->config["photo_thumb3"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				
					thumbnail($dir,$thumbdir,"$id".$extension,$thumb_width,$thum_height,"","$id"."_thumb3".$extension,0);
					chmod($thumbdir."$id".$extension,0777);
				}

				//@unlink($dir.$_FILES[$photos[$i]]['name']);
			}
			
		return true;

	}
	
	function photoList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$my=0,$alb=0,$uid=0,$tbname='',$typ='') 
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
		$album= new Album();

		$sort=$orderBy;
		$tbl   = "media_comments";
		$fldnm = "cmcnt";
		$fld   = "count(e.id)";
		if($sort=="rating desc")
		{
			if($this->config["new_album_functions"]==1){
				$tbl   = "rating";
				$fldnm = "rating";
				$fld   = "(sum(e.mark)/count(e.id))";
			}else{
				$tbl   = "media_rating";
				$fldnm = "rating";
				$fld   = "(sum(e.mark)/count(e.id))";
			}
			
		}
		elseif($sort=="favcnt desc")
		{	
			if($this->config["new_album_functions"]==1){
				$tbl   = "favourites";
				$fldnm = "favcnt";
				$fld   = "count(e.id)";
			}else{
				$tbl   = "media_favorites";
				$fldnm = "favcnt";
				$fld   = "count(e.id)";
			}
		}
		
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "d.username,d.screen_name";
		}
		else 
		{
			$member_search_fields = "d.username";
		}	
       list($qry,$table_id,$join_qry)=$this->generateQry($tblname,'cs','a');
		$sql="SELECT a.*,$fld as $fldnm,$member_search_fields,$qry FROM
		(($tblname a left join `album_files` b on (a.id=b.file_id and b.type='$type'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='photo') $join_qry"; 
		if($catid>0)
		{
			if($this->config["show_private"]!="Y"){
				$sql=$sql." where (a.cat_id=$catid)";
			}else{
				$sql=$sql." where (a.cat_id=$catid) and (a.privacy='public')";
			}
			if ($stxt!='')
			{
				$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
			}
		}
		else
		{
		
			if ($stxt!='')
			{
				$sql=$sql. " where (a.tags like '%$stxt%' or a.title like '%$stxt%')";
				if($my>0)
				{
					if($alb>0)
					{
						$sql=$sql. " and (b.id=$alb)";
					}
					else
					{
						$sql=$sql." and (d.id=$uid)";
					}
				}
				else
				{
					if($this->config["show_private"]!="Y"){
						$sql=$sql." and (a.privacy='public')";
					}
				}	
			}
			else
			{
				if($my>0)
				{
					if($alb>0)
					{
						$sql=$sql. " where (b.id=$alb)";
					}	
					else
					{
						$sql=$sql." where (d.id=$uid)";
					}
				}
				else
				{
					if($_REQUEST['user_id'] && $this->config['searchstyle']== "2"){
						$sql=$sql."where (a.privacy='public' and a.user_id=".$_REQUEST['user_id'].")";
					}else{
						if($this->config["show_private"]!="Y"){
							$sql=$sql." where (a.privacy='public')";
						}
						
					}
				}
			}
			
		}
		
		$sql=$sql." group by a.id";
	//		print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
				
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					if($this->config["new_album_functions"]==1){
						$rating=$album->GetRatingNew($tblname,$value[$i]->id,$type);
						$favcnt =$album->GetFavCntNew($tblname,$value[$i]->id,$type);
					}else{
						$rating=$this->getRating($type,$value[$i]->id);
						$favcnt =$this->getFavCnt($type,$value[$i]->id);
					}
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];
					
					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;
					$rs[0][$i]->favrcnt=$favcnt;

				}	
			}
		}
		return $rs;
  	}
	function getPhotoDetails($phid,$tbname='',$typ='')
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		//print_r($tblname);exit;
		
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "z.screen_name,z.username,z.image,z.id as user_id";
		}
		else 
		{
			$member_search_fields = "z.username,z.image,z.id as user_id";
		}	
		list($qry,$table_id,$join_qry)=$this->generateQry($tblname,'cs','x');
		$sql="SELECT x.*,$member_search_fields,$qry FROM ($tblname x left join 
		`album_files` y on x.id=y.file_id and y.type='$type') inner join `member_master` z 
		on x.user_id=z.id $join_qry where x.`id`=$phid";
		
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
	}
	
	function commentList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$phid,$tbname='',$typ='') 
	{
	if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "`member_master`.screen_name, `member_master`.image ,`member_master`.username,`member_master`.mem_type";
		}
		else 
		{
			$member_search_fields = "`member_master`.image ,`member_master`.username";
		}	
	 
	
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		$sql="SELECT `media_comments`.*,$member_search_fields FROM `media_comments`";
		$sql=$sql."inner join `member_master` on `media_comments`.user_id=`member_master`.id where file_id=$phid and media_comments.type='$type' order by postdate desc";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
		
  	}
	
	function getCommentCount($phid,$tbname='',$typ='')
	{
		
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}$sql="SELECT count(id) as cnt from  `media_comments` where `file_id`=$phid and `type`='$type'";
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
	}
	
	function postComment()
	{
		$arrData=$this->getArrData();
		//	print_r($arrData);exit;
		$this->db->insert("media_comments",$arrData);
		return true;
	}
	
	function ratePhoto($tbname='',$typ='')
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		$arrData=$this->getArrData();
		$arrData["postdate"]=date("Y-m-d H:i:s");
		$sql="Select id from media_rating where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='$type'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->db->insert("media_rating",$arrData);
			return true;
		}
		else
		{
			$this->setErr("You have already rated this File!!!");
			return false;
		}	
	}
	
	function incrementView($phid,$tbname='',$typ='')
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		$this->db->query("Update $tblname set views=views+1 where id=$phid");
		return true;
	}
	
	function addFavorite($tbname='',$typ='')
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		$arrData=$this->getArrData();
		$sql="Select id from media_favorites where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='$type'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->db->insert("media_favorites",$arrData);
			return true;
		}
		else
		{
			$this->setErr("This picture already exists in your favorites list!!!");
			return false;
		}	
	}
	
	function getCommentsCnt($type,$file_id)
	{
		
			$sql = "SELECT count(id) as cnt  FROM `media_comments`";
			$sql = $sql. " where file_id=$file_id and type='$type'";
			$RS = $this->db->get_results($sql,ARRAY_A);
			return $RS[0]['cnt'];
	}
	function getFavCnt($type,$file_id)
	{
		
			$sql = "SELECT count(id) as cnt  FROM `media_favorites`";
			$sql = $sql. " where file_id=$file_id and type='$type'";
			$RS = $this->db->get_results($sql,ARRAY_A);
			return $RS[0]['cnt'];
	}


	//This Function Retrieves the Rating for a Media(Movie/Music/Photo)
	function getRating($type,$file_id)
	{
		
			$sql = "SELECT (sum(mark)/count(mark)) as Rate,count(mark) as cnt  FROM `media_rating`";
			$sql = $sql. " where file_id=$file_id and type='$type'";
			$RS = $this->db->get_results($sql,ARRAY_A);
			$first  = substr($RS[0]['Rate'],0,1);
			$second = substr($RS[0]['Rate'],1,2);
			$second="0".$second;
			
			if($second>=0.5)
			{
				$rate=$first.".5";
			}
			else
			{
				$rate=$first;
			}
			
			$rating['rate'] = $rate;
			$rating['cnt']  = $RS[0]['cnt'];
			return $rating;

	}

	function chekUploadImageValidation()
	{

		for($i=0;$i<count($_FILES["photoImg"]["name"]);$i++)
		{
			if ($_FILES["photoImg"]["name"][$i])
			{
				if ($this->config["photo_min_size"])
				{
					
					list($width,$height) = getimagesize($_FILES['photoImg']['tmp_name'][$i]);
					
					$img_dimensions = explode(",",$this->config["photo_min_size"]);	
						
					if ( ($width<$img_dimensions[0]) || ($height<$img_dimensions[1]))
					{
					
						setMessage("Image size is too small (Mimimum size is {$img_dimensions[0]} X {$img_dimensions[1]})");
						return "error";
					}

				}
				if (!pictureFormat($_FILES["photoImg"]["type"][$i]))
				{
					setMessage("Unknown Picture Format!!");
					return "error";
					
				}
				else
				{
					//return "error";
					$photos[]=$i;
				}
			}
			
		}
		if (sizeof($photos)==0)
		{
			if (!isset($_POST['photoid']))
			{
				setMessage("Please select atleast one picture to Upload");
				return "error";
							
			}

			
		}
		return $photos;

	}
	
	### Function For Jewish Community Photo Upload Check..........
	### Author Jipson Thomas......................................
	### Dated  11 September 2007..................................
		function chekUploadImageValidation1()
	{
	foreach($_FILES as $file){
	if (!pictureFormat($file["type"]))
				{
					setMessage("Unknown Picture Format!!");
					return "error";
					
				}
				else
				{
					
					$photos[]=$file;
				}

	}
		
		if (sizeof($photos)==0)
		{
			if (!isset($_POST['photoid']))
			{
				setMessage("Please select atleast one picture to Upload");
				return "error";
							
			}

			
		}

		return $photos;

	}
	
	### End Function..............................................
	### Function For editPhoto.....................................
	### Author Jipson Thomas......................................
	### Dated  28 December 2007..................................
	function editPhoto($tblname='album_photos',$type='photo')
	{
		$arrData=$this->getArrData();
		$id=$arrData['id'];
		unset($arrData['id']);
		$this->db->update($tblname, $arrData, "id='$id' AND user_id= {$_SESSION['memberid']}");
		return true;
		

	}
	
	### End Function..............................................
	function imgExtension($id,$tbname='',$typ='')
	{
		if($tbname!=''){
			$tblname=$tbname;
			$type=$typ;
		}else{
			$tblname='album_photos';
			$type='photo';
		}
		if($id)
		{
			
			if(id)
			{
				$sql = "SELECT img_extension FROM $tblname WHERE id=$id";
				$rs =  $this->db->get_col($sql,0);
				return $rs[0];
			}
		}
	}
	
	
		/**
   * This function is used for getting photos   .
   * Author   : vinoy
   * Created  :03/Jan/2008
   * Modified : 
   */	
	
	function changePhotos($albm_id,$img_id,$imgNo,$defID)
	{	
		//$imgNo   =    $imgNo+1;
		 $sql      =  	"SELECT file_id,img_extension FROM album_photos JOIN album_files ON album_photos.id = album_files.file_id AND album_files.album_id = $albm_id and type='photo' AND album_photos.id <> $defID";
		 $res      =	mysql_query($sql);
		 $resPhoto = 	mysql_result($res,$imgNo);
		 $resPhotoext = 	mysql_result($res,$imgNo,img_extension);	
		//$resalbmid = 	mysql_result($res,$imgNo,albm_id);	
		//return $resPhoto;	
		return array($resPhoto,$resPhotoext);																						
	}
	/**
   * This function is used for getting videos   .
   * Author   : vinoy
   * Created  :08/Jan/2008
   * Modified : 
   */	
	function changeVideos($albm_id,$img_id,$vdoNo,$defID)
	{	
				
		 $sql      =  	"SELECT file_id FROM album_video JOIN album_files ON album_video.id = album_files.file_id AND album_files.album_id = $albm_id AND type='video' AND album_video.id <> $defID";
		 
		 $res      =	mysql_query($sql);
		 $resVideo = 	mysql_result($res,$vdoNo);
		 
		 return $resVideo;	
	}						
	
}//End Class	
?>