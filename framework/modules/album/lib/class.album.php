<?php
class Album extends FrameWork {
	var $id;
	var $category;
	var $name;
	var $Description;

	function Album($id="",$category="", $name="",$Description="") {
		$this->id = $id;
		$this->category = $category;
		$this->name = $name;
		$this->Description=$Description;
		$this->FrameWork();
	}
	function setArrData($szArrData)
    {
        $this->arrData = $szArrData;
    }
    /*
    End function setArrDate
    Return Post array data
    */
    function getArrData()
    {
        return $this->arrData;
    }
	/**
	 *Listing Category in combo 
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */

	function menuSectionList () {
	
		$sql		= "SELECT category_id AS id, category_name AS cat_name FROM master_category WHERE 1";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['cat_name'] = $this->db->get_col("", 1);
		return $rs;
	}

	/**
	 *Listing Category in combo 
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */

	function menuSectionList1 () {
		$sql		= "SELECT category_id AS id, category_name AS cat_name FROM master_category WHERE 1";
		$rs = $this->db->get_results($sql);
		return $rs;
	}

	/**
	 *Listing Albums in combo 
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */

	function albumSectionList () {
		$cid=$_SESSION['memberid'];
		$sql		= "SELECT category_id AS id , category_name AS cat_name FROM master_category";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['album_name'] = $this->db->get_col("", 1);
		return $rs;
	}

	/**
	 *Listing Albums in combo 
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */

	function albumSectionList1 () {
		$cid=$_SESSION['memberid'];
		$sql		= "SELECT id, album_name FROM album WHERE user_id=$cid";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	/**
	 * save album
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */

	function createAlbum($arrData) {

		if ($this->config["show_property"] == 1){
			$arr = $this->propertyVal($arrData);
			
			$arrData  = $arr[0];
			$catArray = $arr[1];
		}
		
		$arr=$this->splitFields($arrData,'album');	
		$id = $this->db->insert("album", $arr[0]);
		
		if ($arr[1]["table_id"]>0)
		{
			$arr[1]["table_key"] = $id;
			$this->db->insert("custom_fields_list", $arr[1]);
		}
		if ($this->config["show_property"] == 1)			
		$this->addEditPropCategory($catArray,$id);
		return $id;
	}

	function getAlbumDetails($id)
	{
		if($id>0)
		{
			list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
			$sql = "SELECT a.*,$qry FROM album a $join_qry WHERE a.id='$id'";
			$rs = $this->db->get_row($sql, ARRAY_A);
				//realestate tube
				if($this->config["show_property"] == 1)
				{
					$rs["prop_list_type"] = $this->getAlbumCategory($id,1);
					$rs["prop_type"]      = $this->getAlbumCategory($id,11);
					$rs["prop_sale_type"]  = $this->getAlbumCategory($id,21);
					$rs["features"]       = $this->getAlbumCategory($id,39,'arr');
				}
				
			return $rs;
		
		}
	}
	
	function getAlbumByFields($search_fields='',$search_values='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
		}
		$sql = "SELECT a.*,$qry FROM album a $join_qry ";
		
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		
		$rs = $this->db->get_results($sql, ARRAY_A);
	
		return $rs;
		
	}
	
	function updateAlbum($field_arr,$id,$memberid,$views='')
	{
		if ($id)
		{
		
			if ($this->config["show_property"] == 1){ // realestatetube
			
				$arrup 	  = $this->propertyVal($field_arr);
				$field_arr   = $arrup[0];
				$catArray = $arrup[1];
				
			}

			$arr = $this->splitFields($field_arr,"album");
			$arr[0]["id"] = $id;
			
			
			if($views == 1)
			$this->db->update("album", $arr[0], "id='$id'");
			else
			$this->db->update("album", $arr[0], "id='$id' AND user_id= $memberid");
			
			
			if ($this->config["show_property"] == 1)
			{	
			    if($_REQUEST['act'] != "propdView")
				$this->deleteAlbumCategory($id);
				
				$this->addEditPropCategory($catArray,$id);
			}

			$table_id=$arr[1]["table_id"];
			
			
				if ($table_id>0)
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
						$this->db->insert("custom_fields_list", $arr[1]);
					}
				}	
		}	
	}
	
	/**
	 * get file extension
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */

	function file_extension($filename)
	{
		$path_info = pathinfo($filename);
		return $path_info['extension'];
	}

	/**
	 * video upload
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */

	function insertVideoDetails()
	{
		$arr = $this->getArrData();
		
		$video_image=basename($_FILES['video_image']['name']);

		$date=date("Y-m-d H:i:s");
		if ($this->config['video_flv']==1)
		{
		$arr['time'] = time();
		}
		
		if ($this->config['video_thumb']=="Y")
		{
			if ($video_image)
			{
				$arr['video_thumb'] = "Y";
			}
			else 
			{
				$arr['video_thumb'] = "N";
			}
		}	
		$active='Y';
		$arr["postdate"] = $date;
		$arr["active"]   = $active;
		$commission = $arr["commission"];
		$price      = $arr["price"];
		if($arr["commission"])
		{
			$cat_id = $arr["cat_id"];
			$commission = $this->getCommission($cat_id, "video");
			$commission = $commission ? $commission : 0;
			$tot_price = $price * (1 + $commission/100);
			$arr["total_price"] = $tot_price;
		}
		else
		{
			$arr["total_price"] = $price;
		}	
		
		if($arr['home_appearance']=='Y')
		{
			$this->updateMediaDetByState($arr['state']);
		}
		
		unset($arr["commission"],$arr["total"],$arr["terms"],$arr['default_vdo']);
		$arr=$this->splitFields($arr,'album_video');		
		$id = $this->db->insert("album_video", $arr[0]);
		$arr[1]["table_key"]=$id;
		$arr[1]["field_3"]=".".$path_parts['extension'];
		//print_r($arr[1]);exit;
		$this->db->insert("custom_fields_list", $arr[1]);
				
		if ($video_image)
		{
			
			$dir   = SITE_PATH."/modules/album/video/";
			$thumbdir = $dir."thumb/";
			if ($this->config["video_thumb"])
			{
				$thumb_size   = explode(",",$this->config["member_image_thumb1"]);
				$thumb_width  = $thumb_size[0];
				$thumb_height = $thumb_size[1];	
			}
			else 
			{
				$thumb_width  = 100;
				$thumb_height = 100;	
			}
			
			uploadImage($_FILES["video_image"],$dir,$id.".jpg",1);
			chmod($dir."$id.jpg",0777);
			thumbnail($dir,$thumbdir,"$id.jpg",$thumb_width,$thumb_height,"",$id.".jpg",0);
			chmod($thumbdir.$id.".jpg",0777);
			@unlink($dir."$id.jpg");
		}
		
		if($this->config["show_property"] == 1)//realestatetube
		{
			$this->movetoAlbum($id,"M1",$_REQUEST['propid']);
		}
		//show_property
		return $id;
		
	}

	function insertMusicDetails()
	{
		$arr = $this->getArrData();
	
		$date=date("Y-m-d H:i:s");
		
		$active='Y';
		$arr["postdate"] = $date;
		$arr["active"]   = $active;
		$commission = $arr["commission"];
		$price      = $arr["price"];
		if($arr["commission"])
		{
			$cat_id = $arr["cat_id"];
			$commission = $this->getCommission($cat_id, "music");
			$commission = $commission ? $commission : 0;
			$tot_price = $price * (1 + $commission/100);
			$arr["total_price"] = $tot_price;
		}
		else
		{
			$arr["total_price"] = $price;
		}	
		unset($arr["commission"],$arr["total"]);
		
		$arr=$this->splitFields($arr,'album_music');		
		$id = $this->db->insert("album_music", $arr[0]);
		$arr[1]["table_key"]=$id;
		$this->db->insert("custom_fields_list", $arr[1]);
		return $id;
	}
	
	function editMediaDetails ($id,$table_name,$item_type='',$time='') {
		$arr = $this->getArrData();
		if($time!="")
		{
		$arr['time']=$time;
		}
		$commission = $arr["commission"];
		$price      = $arr["price"];
		if($arr["commission"])
		{
			$cat_id = $arr["cat_id"];
			$commission = $this->getCommission($cat_id, $item_type);
			$commission = $commission ? $commission : 0;
			$tot_price = $price * (1 + $commission/100);
			$arr["total_price"] = $tot_price;
		}
		else
		{
			$arr["total_price"] = $price;
		}	
		unset($arr["commission"],$arr["total"]);
		$arr = $this->splitFields($arr,$table_name);
		$table_id=$this->getCustomId($table_name);
		$arr[0]["id"] = $id;
		$this->db->update($table_name,$arr[0] ,"id=$id");
		$this->db->update("custom_fields_list",$arr[1] ,"table_key=$id and table_id=$table_id");
		return true;
	}

	function getMediaCount($uid=0,$tbl_name,$privacy='')
	{
		if($privacy=='')
		{
			if($uid!=0)
			{
				$sql="SELECT count(a.id) as cnt FROM `$tbl_name` a  where a.user_id=$uid ";
			}
			else
			{
				$sql="SELECT count(a.id) as cnt FROM `$tbl_name` a";
			}	
		}
		else
		{
			if($uid!=0)
			{
				$sql="SELECT count(a.id) as cnt FROM `$tbl_name` a where a.user_id=$uid and a.privacy='$privacy' ";
			}
			else
			{
				$sql="SELECT count(a.id) as cnt FROM `$tbl_name` a where a.privacy='$privacy' ";
			}	
		}	

		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0]['cnt'];
	}
	function getFavMediaCount($uid,$type)
	{
		$sql="select count(id) as cnt from media_favorites where type='$type' and userid=$uid";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0]['cnt'];
	}

	function getPurchasedMediaCount($uid,$type)
	{
		$sql="SELECT COUNT(order_id) AS cnt FROM shop_order_details d, shop_order o WHERE d.order_id = o.id AND d.item_type='$type' AND o.member_id='$uid'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0]['cnt'];
	}

	function getAlbums($uid,$type) {
		
		$sql="SELECT a.id,a.album_name,count(b.id) as cnt FROM `album` a left join";
		$sql=$sql." `album_files` b on (b.album_id=a.id and b.type='$type') where a.user_id=$uid  group by a.id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	function getAlbumList($uid) {
		
		$sql		= "SELECT id, album_name FROM album a where a.user_id=$uid";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['album_name'] = $this->db->get_col($sql, 1);
		return $rs;
	}

	function mediaFavList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$uid=0)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs','a');
		$sql="Select a.*,count(c.id),$qry from (`$tbl_name` a inner join `media_favorites` b ";
		$sql=$sql."on (a.id=b.file_id and b.type='$type'))left join `media_comments` c on ";
		$sql=$sql."(a.id=c.file_id and c.type='$type') $join_qry  where b.userid=$uid";

		if ($stxt!='')
		{
			$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
		}

		$sql=$sql." group by a.id";
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;
					
					$udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
				}
			}
		}
		//print_r($rs);exit;
		return $rs;
	}

	function updateShopDetails($id)
	{
		$arr = $this->getArrData();

		$this->db->update("shop_order_details",$arr ,"id=$id");
		//return true;
	}
	
	function mediaPurchasedList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$uid=0)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs','a');
		$sql="Select a.*,b.id as sh_id,b.download_status,$qry from (`$tbl_name` a inner join `shop_order_details` b ";
		$sql=$sql."on (a.id=b.item_id and b.item_type='$type'))inner join `shop_order` c on ";
		$sql=$sql."(b.order_id=c.id) $join_qry  where c.member_id='$uid'";

		if ($stxt!='')
		{
			$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
		}

		$sql=$sql." ";
		//print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;
					
					$udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
				}
			}
		}
		return $rs;
	}

	function mediaList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$alb=0,$uid=0,$privacy='',$search_fields='',$search_values='',$mem_type='',$criteria='=')
	{
		$tbl   = "media_comments";
		$fldnm = "cmcnt";
		$fld   = "count(e.id)";
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs','a');
		if($search_fields)
		{
		list($qry_cs)=$this->getCustomQry($tbl_name,$search_fields,$search_values,$criteria,'a','cs');	
		}
		//list($qry,$table_id)=$this->generateQry($tbl_name,'cs');
		//$sql = "SELECT a.*,$qry1 FROM album a $join_qry WHERE a.id='$id'";
		//$rs = $this->db->get_row($sql, ARRAY_A);
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "d.username,d.screen_name";
		}
		else 
		{
			$member_search_fields = "d.username";
		}	
		
		$sql="SELECT a.*,$fld as $fldnm,$member_search_fields,$qry FROM
		((`$tbl_name` a left join `album_files` b on (a.id=b.file_id and b.type='$type'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='$type') $join_qry"; 

		if ($stxt!='')
		{
			$sql=$sql. " where (a.tags like '%$stxt%' or a.title like '%$stxt%')";

			if($alb>0)
			{
				$sql=$sql. " and (b.album_id=$alb)";
			}
			else
			{	
				if($uid!=0)
				{
					$sql=$sql." and d.id=$uid";
				}	
			}
			if($privacy!='')
			{
				$sql=$sql." and a.privacy='public'";
			}
		}
		else
		{
			if($alb>0)
			{
				$sql=$sql. " where b.album_id=$alb";
			}
			else
			{
				if($uid!=0)
				{
					$sql=$sql." where d.id=$uid";
				}	
			}
			if($privacy!='')
			{
				$sql=$sql." and a.privacy='public'";
			}
		}
		
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		
		$sql=$sql." group by a.id";
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{

					if($this->config["new_album_functions"]==1){
						$rating=$this->GetRatingNew($tbl_name,$value[$i]->id,$type);
						$favcnt =$this->GetFavCntNew($tbl_name,$value[$i]->id,$type);
					
					}else{
						$rating=$this->getRating($type,$value[$i]->id);
						$favcnt =$this->getFavCnt($type,$value[$i]->id);
					}
					//$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					//$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;
					
					$udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
					
					$rs[0][$i]->featured_id=$udet['featured_song_id'];
				
			
				}
			}
		}
		//print_r($rs);exit;
		return $rs;
	}
	///for link54
	function mymediaDelete($id, $crt,$var) {
	
	
		switch ($crt) {
			case "M1":
		
				$tbl_name = "album_video";
				$type = "video";
				@unlink(SITE_PATH."/modules/album/video/".$id.".flv");
				@unlink(SITE_PATH."/modules/album/video/thumb/".$var.".jpg");
				break;
			
			default:
				$tbl_name = "album_music";
				$type = "music";
				@unlink(SITE_PATH."/modules/album/music/".$id.".flv");
				@unlink(SITE_PATH."/modules/album/music/thumb/".$id.".jpg");
				break;
		}
		
	}
///for link54	
	
	function mediaDelete($id, $crt) {
	
		switch ($crt) {
			case "M1":
		
				$tbl_name = "album_video";
				$type = "video";
				@unlink(SITE_PATH."/modules/album/video/".$id.".flv");
				@unlink(SITE_PATH."/modules/album/video/thumb/".$id.".jpg");
				
				break;
			case "M2":
				$tbl_name = "album_photos";
				$type = "photo";
				$photo_det = $this->mediaDetailsGet($id,"photos");
				($photo_det['img_extension']!="") ? $img_ext = $photo_det['img_extension'] : $img_ext =".jpg";
				@unlink(SITE_PATH."/modules/album/photos/".$id."$img_ext");
				@unlink(SITE_PATH."/modules/album/photos/thumb/".$id."$img_ext");
				@unlink(SITE_PATH."/modules/album/photos/resized/".$id."$img_ext");
				break;
			case "M5":
				$tbl_name = "album_products";
				$type = "product";
				$photo_det = $this->mediaDetailsGet($id,"products");
				($photo_det['img_extension']!="") ? $img_ext = $photo_det['img_extension'] : $img_ext =".jpg";
				@unlink(SITE_PATH."/modules/album/products/".$id."$img_ext");
				@unlink(SITE_PATH."/modules/album/products/thumb/".$id."$img_ext");
				@unlink(SITE_PATH."/modules/album/products/resized/".$id."$img_ext");
				break;
				
			default:
				$tbl_name = "album_music";
				$type = "music";
				@unlink(SITE_PATH."/modules/album/music/".$id.".flv");
				@unlink(SITE_PATH."/modules/album/music/thumb/".$id.".jpg");
				break;
		}
		$this->db->query("DELETE FROM media_comments WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM media_favorites WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM media_rating WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM album_files WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM competition_member_files WHERE file_id='$id' AND `type` = '$type'");
	
		$this->db->query("DELETE FROM `$tbl_name` WHERE id='$id'");
		 
		$this->db->query("DELETE FROM message_file WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM favourites WHERE file_id='$id' AND `type` = '$type'");
		$this->db->query("DELETE FROM rating WHERE file_id='$id' AND `type` = '$type'");
	}
	function propertyDelete($propid)
	{
		$sql = "SELECT type,file_id FROM album_files WHERE album_id=$propid";
		$rs  = $this->db->get_row($sql);
		
		foreach($rs as $prop)
		{
			if($prop->type == "video")
			 $crt = "M1";
			elseif($prop->type == "photo")
			 $crt = "M2";
			 
			$this->mediaDelete($prop->file_id, $crt);
		}
		$this->albumDelete($propid);
	}
	function mediaRemoveFavorite($id, $crt) {
		switch ($crt) {
			case "M1":
				$type = "video";
				break;
			case "M2":
				$type = "photo";
				break;
			case "M3":
				$type = "album";
				break;
			default:
				$type = "music";
				break;
		}
		
		$this->db->query("DELETE FROM media_favorites WHERE file_id='$id' AND `type` = '$type'");
		
		if($this->config["show_property"] == 1)
		setMessage("Property has been removed",MSG_INFO);
	}
	
	function movetoAlbum($file_id,$crt,$album_id)
	{
		switch ($crt) 
		{
			case "M1":
				$type = "video";
				break;
			case "M2":
				$type = "photo";
				break;
			case "M5":
				$type = "product";
				break;
			default:
				$type = "music";
				break;
		}
		$array = array("album_id"=>$album_id,"type"=>$type, "file_id"=>$file_id);
		$sql="Select id from album_files where file_id=$file_id and type='$type'";
		$count=count($this->db->get_results($sql));
		if ($count==0)
		{
			
			$this->db->insert("album_files", $array);

		}
		else
		{
			$rs['id']=$this->db->get_col($sql,0);
			$this->db->update("album_files", $array, "id=".$rs['id'][0]);

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

	function getAlbumName($albid)
	{
		$sql="select album_name from album where id=$albid";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0]['album_name'];
	}

	function getCommission($catId, $type="video") {
		$rs = $this->db->get_row("SELECT commission_rate FROM media_commission WHERE cat_id='$catId' AND type='$type'");
		if($rs->commission_rate=="") $rs->commission_rate=0;
		return $rs->commission_rate;
	}
	
    function commissionList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
        $sql		= "SELECT * FROM media_commission m, master_category c WHERE c.category_id = m.cat_id";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
    }

    function commissionAddEdit (&$req) {

        extract($req);
        
        $rs = $this->db->get_row("SELECT cat_id FROM media_commission WHERE cat_id='$cat_id' AND type='$type'");
        if($rs->cat_id) {
        	$edit = true;
        } else {
        	$edit = false;
        }

        if(!trim($cat_id)) {
            $message = "Category is required";
        } elseif (!trim($type)) {
            $message = "Type is required";
        } elseif (!trim($commission_rate)) {
            $message = "Commission Rate is required";
        } else {
            $array = array("cat_id"=>$cat_id, "type"=>$type, "commission_rate"=>$commission_rate);
            if($edit) {
                $this->db->update("media_commission", $array, "cat_id='$cat_id' AND type='$type'");
            } else {
                $this->db->insert("media_commission", $array);
            }
            return true;
        }
        return $message;
    }

    function commissionGet ($cat_id, $type) {
        $sql		= "SELECT * FROM media_commission WHERE cat_id = '$cat_id' AND `type`='$type'";

        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    function commissionDelete ($cat_id, $type) {
        $this->db->query("DELETE FROM media_commission WHERE cat_id = '$cat_id' AND `type`='$type'");
    }

    function mediaDetailsGet ($media_id, $type="video") {
    	$table = "album_".$type;
    	
    	$sql		= "SELECT * FROM $table WHERE id = '$media_id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }

    function addToCart($memberID, $item_id, $item_type, $item_price, $item_cat,$file_type='') {
    	$rs = $this->db->get_row("SELECT id FROM shop_cart WHERE member_id = '$memberID' AND item_id = '$item_id' AND item_type = '$item_type'");
    	if (!$rs) {
    		$commission = $this->getCommission($item_cat, $item_type);
	    	$this->db->insert("shop_cart", array("member_id"=>$memberID, "item_id"=>$item_id, "item_type"=>$item_type, "item_price"=>$item_price, "item_commission"=>$commission,"file_type"=>$file_type));
    	}
    }

    function removeFromCart($memberID, $id) {
    	$this->db->query("DELETE FROM shop_cart WHERE id = '$id' AND member_id = '$memberID'");
    }

    function cartList($memberID) {
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	$objUser     =	new User();
    	$rs = $this->db->get_results("SELECT * FROM shop_cart WHERE member_id = '$memberID'");
	//	print_r($rs);exit;
    	if ($rs) {
    		$count = count($rs);
    		for ($i=0; $i<$count; $i++) {
    			$table = "album_".$rs[$i]->item_type;
				if($rs[$i]->file_type!="album"){
					$rs[$i]->unit_price=$this->config['track_price'];
					$tprice=$tprice+$this->config['track_price'];	
				}
				//$sp="SELECT * FROM $table f, member_master m WHERE f.user_id = m.id AND f.id='{$rs[$i]->item_id}'";
				
    			$media_rs = $this->db->get_row("SELECT * FROM $table f, member_master m WHERE f.user_id = m.id AND f.id='{$rs[$i]->item_id}'");
    			$rs[$i]->title = $media_rs->title;
    			$rs[$i]->owner = $media_rs->first_name." ".$media_rs->last_name;
    			$rs[$i]->audio_type = $media_rs->audio_type;
				$we="select user_id from $table where id=".$rs[$i]->item_id;
				$sd=$this->db->get_row($we,ARRAY_A);
				$memd=$objUser->getUserdetails($sd[user_id]);
				//print_r($memd);exit;
				$rs[$i]->owner_nick=$memd["nick_name"];
    		}
			$sm="SELECT count(DISTINCT a.user_id) as num FROM album_music a INNER JOIN shop_cart s ON a.id = s.item_id AND s.member_id =$memberID AND s.file_type = 'album' LIMIT 0 , 30";
			$rr = $this->db->get_results($sm,ARRAY_A);
			$rsp=$rr[0][num]*$this->config['album_price'];	
			//print_r($rsp);exit;
			$tprice=$tprice+$rsp;
			$rs['amount_to_pay']=$tprice;
    	}
		//print_r($rs);exit;
    	return $rs;
    }

    function cartTotal($memberID) {
    	$rs = $this->db->get_row("SELECT SUM(item_price) AS total_price FROM shop_cart WHERE member_id = '$memberID'");
    	return number_format($rs->total_price * (1 + $this->config['sales_tax']/100), 2);
    }

    function orderItems($req, $gateWay, $memberID) {
    	$rs = $this->db->get_row("SELECT SUM(item_price - item_price/(1+item_commission/100)) AS comm FROM shop_cart WHERE member_id = '$memberID'");
    	$array = array("member_id"=>$memberID, 
    				   "transaction_id"=>$gateWay['TransactionID'], 
    				   "avs_code"=>$gateWay['AVSCode'],
    				   "cvv2_code"=>$gateWay['CVV2Code'],
    				   "total_price"=>$gateWay['Amount'],
    				   "total_commission"=>$rs->comm,
    				   "sales_tax"=>$this->config['sales_tax'],
    				   "order_date"=>date("Y-m-d H:i:s"),
    				   "gateway_response"=>$gateWay['response']);
    	$order_id = $this->db->insert("shop_order", $array);
    	$rs = $this->db->query("INSERT INTO shop_order_details(order_id, item_id, item_type, item_price, item_commission) 
    							SELECT '$order_id', item_id, item_type, item_price, item_commission FROM shop_cart WHERE member_id='$memberID'");
    	$this->db->query("DELETE FROM shop_cart WHERE member_id = '$memberID'");
    }
	/**
    * This function is used to delete the album detalis .
  	* Author   : 
  	* Created  : 
  	* Modified : 18:12:2007
  	*/
	function albumDelete($albid) {
	     $table_name='album';	
		 $tbid=$this->getCustomId($table_name);
		 $this->db->query("DELETE from album where id=$albid");
		 $this->db->query("DELETE FROM custom_fields_list WHERE table_id='$tbid' AND table_key='$albid'");
		 $this->db->query("DELETE from album_files where album_id=$albid");
	}

	function orderList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT o.*, m.first_name, m.last_name, m.username FROM shop_order o, member_master m WHERE m.id = o.member_id";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	function orderUserList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql_1	   = "INSERT INTO shop_pending_payments(member_id, item_type, name, total_price)
						   SELECT m.id, 'music', CONCAT(m.first_name, ' ', m.last_name, ' [', m.username, ']'), SUM(item_price/(1+item_commission/100)) 
							 FROM member_master m, shop_order_details o, album_music a 
							WHERE m.id = a.user_id 
							  AND a.id = o.item_id 
							  AND o.funds_transfered='N' 
							  AND o.item_type = 'music' 
							GROUP BY m.id";
		$sql_2	   = "INSERT INTO shop_pending_payments(member_id, item_type, name, total_price)
						   SELECT m.id, 'video', CONCAT(m.first_name, ' ', m.last_name, ' [', m.username, ']'), SUM(item_price/(1+item_commission/100)) 
							 FROM member_master m, shop_order_details o, album_video a 
							WHERE m.id = a.user_id 
							  AND a.id = o.item_id 
							  AND o.funds_transfered='N' 
							  AND o.item_type = 'video' 
							GROUP BY m.id";

		$this->db->query("DELETE FROM shop_pending_payments");
		$this->db->query($sql_1);
		$this->db->query($sql_2);
		
		$sql = "SELECT member_id, name, SUM(total_price) AS total_price FROM shop_pending_payments GROUP BY member_id";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function orderUserDetails ($memberID) {
		$sql = "   SELECT a.id as item_id, 'music' as item_type, a.title, (item_price/(1+item_commission/100)) AS item_price_original, item_commission, o.id as oid
					 FROM member_master m, shop_order_details o, album_music a 
					WHERE m.id = a.user_id 
					  AND a.id = o.item_id 
					  AND o.funds_transfered='N' 
					  AND o.item_type = 'music' 
					  AND m.id='$memberID'
				 
				UNION ALL
				   SELECT a.id as item_id, 'video' as item_type, a.title, (item_price/(1+item_commission/100)) AS item_price_original, item_commission, o.id as oid
					 FROM member_master m, shop_order_details o, album_video a 
					WHERE m.id = a.user_id 
					  AND a.id = o.item_id 
					  AND o.funds_transfered='N' 
					  AND o.item_type = 'video' 
					  AND m.id='$memberID'
				 ";
		$rs = $this->db->get_results($sql);
		return $rs;
	}

	function orderDetails ($order_id) {
		$sql		= "SELECT * FROM shop_order_details d, shop_order o WHERE d.order_id = o.id AND o.id='$order_id'";
		if ($_REQUEST['orderBy']) {
			$sql .= " ORDER BY ".str_replace(":", " ", $_REQUEST['orderBy']);
		}
		$rs = $this->db->get_results($sql);
		
		for($i=0; $i<count($rs); $i++) {
			$type = $rs[$i]->item_type;
			$itemRS = $this->db->get_row("SELECT * 
											FROM album_$type m, member_master o
										   WHERE o.id = m.user_id 
										     AND m.id='{$rs[$i]->item_id}'");
			$rs[$i]->title = $itemRS->title;
			$rs[$i]->name  = $itemRS->first_name." ".$itemRS->last_name." [".$itemRS->username."]";
			$rs[$i]->owner_id  = $itemRS->user_id;
			$rs[$i]->item_price_original = $rs[$i]->item_price / (1 + $rs[$i]->item_commission/100);
		}
		
		return $rs;
	}
	
	function orderDetailsUpdate($memberID, $item_id) {
		$item_id = implode(",", $item_id);
		$this->db->query("UPDATE shop_order_details SET funds_transfered = 'Y' WHERE  id IN ($item_id)");
	}
	
	function getBestSeller($item_type) {
//		if($item_type == "music") {
//			$sql_fix = " AND audio_type = 'V' ";
//		}

		$rs = $this->db->get_results("SELECT item_id, item_price, COUNT(*) as num, user_id FROM shop_order_details d, album_$item_type a WHERE a.id = d.item_id AND item_type = '$item_type' $sql_fix GROUP BY item_id ORDER BY num DESC LIMIT 4", ARRAY_A);
		return $rs;
	}
	
	function getBestSeller_music($item_type) {
//		if($item_type == "music") {
//			$sql_fix = " AND audio_type = 'V' ";
//		}
//echo "SELECT item_id, item_price, COUNT(*) as num, user_id, image FROM shop_order_details d, album_$item_type a, member_master m WHERE a.id = d.item_id AND item_type = '$item_type' AND a.user_id = m.id $sql_fix GROUP BY item_id ORDER BY num DESC LIMIT 4";


		$rs = $this->db->get_results("SELECT item_id, item_price, COUNT(*) as num, user_id, image FROM shop_order_details d, album_music a, member_master m WHERE a.id = d.item_id AND item_type = 'music' AND a.user_id = m.id GROUP BY user_id ORDER BY num DESC LIMIT 4", ARRAY_A);
		return $rs;
		
	}
	function salesTaxEdit ($sales_tax) {
		$this->db->update("config", array("value"=>$sales_tax), "field='sales_tax'");
	}
	/*
	Afsal
	*/
	function propertyCategoryName($id,$type = '')
	{
		if($type == '')
		{
			$rs 	= $this->getAlbumDetails($id);
			$sql    = "SELECT category_name FROM master_category
					   WHERE category_id IN(select cat_id FROM album_category WHERE album_id=$id AND cat_id_parent IN(1,11,21))";
			$rscat  = $this->db->get_results($sql);
			
			return $rscat;
		}
		elseif($type == 'features')
		{
			$rs 	= $this->getAlbumDetails($id);
			if($rs['features'] > 0)
			{
				$sql    = "SELECT category_name FROM master_category
						   WHERE category_id IN(select cat_id FROM album_category WHERE album_id=$id AND cat_id_parent IN(39))";
				$rscat  = $this->db->get_results($sql);
				return $rscat;
			}

		}
		
	}
	function propertyCountryName($id)
	{
		$rs 	= $this->getAlbumDetails($id);
		if($rs['prop_country'] > 0){
			$sql	= "SELECT country_name FROM country_master WHERE country_id = {$rs['prop_country']}";
			$rsCoun = $this->db->get_row($sql,ARRAY_A);
			return $rsCoun["country_name"];
		}
		
	}
	function videoPopUp($tbl_name,$type,$stxt=0,$alb=0,$uid=0,$privacy='',$search_fields='',$search_values='',$mem_type='',$criteria='=')
	{
	    $tbl   = "media_comments";
		$fldnm = "cmcnt";
		$fld   = "count(e.id)";
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry($tbl_name,$search_fields,$search_values,$criteria,'a','cs');	
		}
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs');
		$sql="SELECT a.*,$fld as $fldnm,d.username,$qry FROM
		((`$tbl_name` a left join `album_files` b on (a.id=b.file_id and b.type='$type'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='$type') $join_qry"; 
	
		if ($stxt!='')
		{
			$sql=$sql. " where (a.tags like '%$stxt%' or a.title like '%$stxt%')";

			if($alb>0)
			{
				$sql=$sql. " and (b.album_id=$alb)";
			}
			else
			{	
				if($uid!=0)
				{
					$sql=$sql." and d.id=$uid";
				}	
			}
			if($privacy!='')
			{
				$sql=$sql." and a.privacy='public'";
			}
		}
		else
		{
				if($alb>0 && $uid >0)
				{
					$sql=$sql. " where b.album_id=$alb ";
				}
				elseif($alb>0)
				{
					$sql=$sql. " where b.album_id=$alb";
				}
				else
				{
					if($uid!=0)
					{
						$sql=$sql." where d.id=$uid";
					}	
				}
				if($privacy!='')
				{
					$sql=$sql." and a.privacy='public'";
				}
		}
				
				$sql=$sql." group by a.id";	
				
		
		$rs = $this->db->get_results($sql,ARRAY_A,$orderBy);
		
		
		return $rs;


	
	}
	function propertyList($tbl_name,$type,$stxt=0,$alb=0,$uid=0,$privacy='',$search_fields='',$search_values='',$mem_type='',$criteria='=')
	{
		$tbl   = "media_comments";
		$fldnm = "cmcnt";
		$fld   = "count(e.id)";
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry($tbl_name,$search_fields,$search_values,$criteria,'a','cs');	
		}
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs');
		$sql="SELECT a.*,$fld as $fldnm,d.username,$qry FROM
		((`$tbl_name` a left join `album_files` b on (a.id=b.file_id and b.type='$type'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='$type') $join_qry"; 
		
		if ($stxt!='')
		{
			$sql=$sql. " where (a.tags like '%$stxt%' or a.title like '%$stxt%')";

			if($alb>0)
			{
				$sql=$sql. " and (b.album_id=$alb)";
			}
			else
			{	
				if($uid!=0)
				{
					$sql=$sql." and d.id=$uid";
				}	
			}
			if($privacy!='')
			{
				$sql=$sql." and a.privacy='public'";
			}
		}
		else
		{
				if($alb>0 && $uid >0)
				{
					$sql=$sql. " where b.album_id=$alb AND d.id=$uid";
				}
				elseif($alb>0)
				{
					$sql=$sql. " where b.album_id=$alb";
				}
				else
				{
					if($uid!=0)
					{
						$sql=$sql." where d.id=$uid";
					}	
				}
				if($privacy!='')
				{
					$sql=$sql." and a.privacy='public'";
				}
		}
		
		
		
		
		$sql=$sql." group by a.id";	
		
		
		$rs = $this->db->get_results($sql,ARRAY_A,$orderBy);
		/*
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{

					$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;

				}
			}
		}*/
		
		return $rs;

	}
	
	
	
	
	function secs2hms($secs) {
		if ($secs<0) return false;
		$m = (int)($secs / 60); $s = $secs % 60;
		$h = (int)($m / 60); $m = $m % 60;
		return array($h, $m, $s);
	}
/*
afsal
*/
	function propertySearch($pageNo, $limit=15, $params, $output, $orderBy,$search_fields='',$search_values='',$type='',$criteria='=',$category='',$listAllSub='no',$group_id='',$favrite='')
	{
	
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		
		if($category > 0)
		{
		$sql2 = " INNER JOIN album_category As c ON a.id = c.album_id AND c.cat_id=$category";
		$qry2  = ",c.cat_id";
		}
		if($listAllSub == 'yes')
		{
		$subSql = " INNER JOIN `my_subscriptions` AS sub ON sub.subscribed_id = a.user_id WHERE sub.member_id={$_SESSION['memberid']}";
		}
		//This join group_master table for Retrieving matching record
		if($group_id > 0)
		{
			$subSql = " INNER JOIN `group_album` AS ga ON  ga.file_id = a.id WHERE ga.group_id=$group_id";
		}
		
		// Get the list of favorite properties
		if($favrite == 'yes')
		{
			$subSql = "INNER JOIN `media_favorites` f ON f.file_id = a.id";
		}
		
		$sql = "SELECT a.*,co.country_name,m.username,$qry $qry2 FROM album a $join_qry INNER JOIN";
		$sql.= " country_master AS co ON co.country_id = d.field_2";
		$sql.= " INNER JOIN `member_master` m on m.id=a.user_id $subSql";
		
		if($category > 0)
		{
		$sql.= $sql2;
		}
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
			
		}
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		$rs = $this->db->get_results_pagewise($sql,$pageNo, $limit, $params, $output, $orderBy);
		$i = 0;
		
			if (count($rs[0]) > 0)
			{
				foreach ($rs[0] as $value)
				{
						if($value["id"]>0)
						{
						
							$rating=$this->getRating("album",$value["id"]);
							$rs[0][$i]["rate"] = $rating["rate"];
							$rs[0][$i]["cnt"]  = $rating["cnt"];
							
							//For this All Videos Listing
							
							if($type == "video")
							{
								if ($value['default_vdo'] > 0)
								{
								
								$sql = "SELECT title,description,id From album_video WHERE id = {$value['default_vdo']}";
								
								$rsv = $this->db->get_row($sql,ARRAY_A);
								$rs[0][$i]["video_photo_title"] 	    = $rsv["title"];
								$rs[0][$i]["video_photo_description"] = $rsv["description"];
								$rs[0][$i]["video_photo_id"] = $rsv["id"];
						
								}
							}
							elseif($type == "photo")
							{
								if ($value['default_img'] > 0)
								{
								
									$sql = "SELECT title,description,id From album_photos WHERE id = {$value['default_img']}";
									$rsv = $this->db->get_row($sql,ARRAY_A);
									$rs[0][$i]["video_photo_title"] 	   = $rsv["title"];
								   //$rs[0][$i]["video_photo_description"] = $rsv["description"];
									$rs[0][$i]["video_photo_id"] = $rsv["id"];
						
								}

							}
							//All Videos Listing
						}
				$i++;
				}
				return $rs;
			}
	}
	
	function incrementProView($propid)
	{
		if($propid > 0)
		{
			$rs = $this->getAlbumdetails($propid);
			$views = $rs["prop_views"];
			$views = $views + 1 ;
			$this->updateAlbum(array("prop_views" => $views),$propid,1);
			
		}
	}
	function getCountryName($country_id)
	{
		if($country_id > 0)
		{
			$sql = "SELECT * FROM country_master WHERE country_id =$country_id";
			$rs = $this->db->get_row($sql);
			return $rs;
		}
	}
	//afsal 
	function  getSelectedOptions($id)
  	{
	$rs = $this->db->get_row("select * from  profile_options where user_id={$id}", ARRAY_A);
	return $rs;
	
  	}
	function setStatusDefault($id)
	 {
		//////////////////
		$sql="insert into profile_options (user_id,rap_sheet,basic_info,news,feature_media,";
		$sql.="studio_wall,about_me,education,movies,music,recent_friends) value({$id},'Y','Y','Y','Y','Y','Y','Y','Y','Y','Y')";
		$this->db->query($sql);
 	 }
	 //afsal for realestatetube
	 function getAlbumCategory($propid,$cat_p_id,$ret_type='')//$ret_type :- return as get_row or get_results
	 {
	 	if($propid > 0 && $cat_p_id > 0)
		{
	 		$sql = "SELECT cat_id FROM album_category WHERE album_id =$propid AND cat_id_parent=$cat_p_id";
			
			if($ret_type == 'arr'){
			
				 $rs  = $this->db->get_results($sql,ARRAY_A);
				 $arrCat = array();
				 
				 if (count($rs))
				 {
					 foreach($rs as $val)
					 {
						$arrCat[] = $val["cat_id"] ;
					 }
			 		return $arrCat ;
	
				 }
			}
			else
			{
			 $rs  = $this->db->get_row($sql);
			 return $rs->cat_id;
			}
		}
	 }
	 function propertyVal($arrData)// # RealEstate Property #
	 {
		if ($this->config["show_property"] == 1) //realestate tube
		{
			$catArray = array("prop_list_type" => $arrData["prop_list_type"],"prop_type" => $arrData["prop_type"],
						"prop_sale_type" => $arrData["prop_sale_type"],"prop_list_type_parent" => $arrData["prop_list_type_parent"],
						"prop_type_parent" =>  $arrData["prop_type_parent"],"prop_sale_type_parent" => $arrData["prop_sale_type_parent"],
						"features" => $arrData["features"],"feature_parent" => $arrData["feature_parent"],
						"search_tags" => $arrData["search_tags"],"prop_zip" => "prop_zip");
						
			unset($arrData["prop_list_type"],$arrData["prop_type"],$arrData["prop_sale_type"],$arrData["prop_list_type_parent"],
				  $arrData["prop_type_parent"],$arrData["prop_sale_type_parent"],$arrData["features"],$arrData["feature_parent"]);
			return array($arrData,$catArray);
		}
	 }
	 function addEditPropCategory($catArray,$id)
	 {
			if ($this->config["show_property"] == 1) //realestate tube
			{
				if($id > 0 && $catArray['prop_list_type'] > 0)
				{
				  $array_cat = array("cat_id" => $catArray['prop_list_type'],"album_id" => $id,"cat_id_parent" => $catArray['prop_list_type_parent']);
				  $this->db->insert("album_category",$array_cat);
				}
				if($id > 0 && $catArray['prop_type'] > 0)
				{
				  $array_cat = array("cat_id" => $catArray['prop_type'],"album_id" => $id,"cat_id_parent" => $catArray['prop_type_parent']);
				  $this->db->insert("album_category",$array_cat);
				}
				if($id > 0 && $catArray['prop_sale_type'] > 0)
				{
					$array_cat = array("cat_id" => $catArray['prop_sale_type'],"album_id" => $id,"cat_id_parent" => $catArray['prop_sale_type_parent']);
					$this->db->insert("album_category",$array_cat);
				}
	
				if($catArray['features'] !="")
				{
					$arrFeatures = explode(",",$catArray['features']);
					foreach($arrFeatures as $feature)
					{
						$array_cat = array("cat_id" => $feature,"album_id" => $id,"cat_id_parent" => $catArray['feature_parent']);
						$this->db->insert("album_category",$array_cat);
					}
				}
			}
	 }
	 function deleteAlbumCategory($id)
	 {
	 	if($id >0)
	 	$this->db->query("DELETE FROM album_category WHERE album_id=$id");
	 }
	 
	 ### Function for Subscribe a user............
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 30 August 2007................
	 
	 function Subscribe($uid,$subid,$type)
	 {
	 	if($uid==$subid){
			$this->setErr("You Can't Subscribe Yourself.");
			return false;
		}else{
			$sql = "select * from my_subscriptions  where member_id=$uid and subscribed_id=$subid and type='$type'";
			$rs  = $this->db->get_row($sql);
			if(count($rs)<1)
			{
				$arr=array();
				$arr["member_id"]		=	$uid;
				$arr["subscribed_id"]	=	$subid;
				$arr["date"]			=	date("Y-m-d h:i:s");
				$arr["type"]			=	$type;
				$this->db->insert("my_subscriptions", $arr);
				return true;
			}
			else
			{
				$this->setErr("You Are Already Subscribed This User.");
				return false;
			}
			
		}
	 		
	 }
	  /* 
	   Setting the Error
    */
    function setErr($szError)
    {
        $this->err .= "$szError";
    }
    /*
    End function setErr 
	    Returns the error
    */
    function getErr()
    {
        return $this->err;
    }

    /*
    End function getErr
	*/
	
	/*
	Afsal 4 realestatetube
	*/
	function addFavorite()
    {
        $arrData=$this->getArrData();
        $sql="Select id from media_favorites where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='album'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
            $this->db->insert("media_favorites",$arrData);
            return true;
        }
        else
        {
            $this->setErr("This property already exists in your favorites list!!!");
            return false;
        }
    }
	 ### Function for Checking Subscription.......
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 10 September 2007.............
	 
	 function chkSubscribe($uid,$subid,$type)
	 {
	 	if($uid==$subid){
			return false;
		}else{
			$sql = "select * from my_subscriptions  where member_id=$uid and subscribed_id=$subid and type='$type'";
			$rs  = $this->db->get_row($sql);
			if(count($rs)<1)
			{
				$res="N";
			return $res;
			}
			else
			{
				$res="Y";
			return $res;
			}
			
		}
	 		
	 }
	 
	 
	 /*
		 BEGIN FUNCTION ALBUM BOOKING
	 */ 
	 
	 function addEditPropBooking (&$req) {
     	
		extract($req);
		$message = true;
		
		if ( date($req['txtcalendarTo'])-date($req['txtcalendarFrom']) < 0 ) {
			$message = "Booking Start Date should be less than End Date";
		}
		
		return $message;
	 }
	 
	 /*
		 END FUNCTION ALBUM BOOKING
	 */ 




	 
	 ### Function for unsubscription.............
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 10 September 2007.............
	 
	 function Unsubscribe($uid,$subid,$type)
	 {
	
	 	if($uid ==$subid){
			$this->setErr("You Can't unsubscribe Yourself.");
			return false;
		}else{
			$sql = "DELETE FROM my_subscriptions  WHERE member_id=$uid AND subscribed_id=$subid AND type='$type'";
			
			$this->db->query($sql);
			return true;
		}
	 		
	 }
	
	 function getAlbumSubscriptionDetails($pageNo, $limit, $params,$output=ARRAY_A,$orderBy, $uid)
	 {
	 	if($uid > 0)
		{
			$searchFields = "user_id";
			$listAll	  = "no";
		}
		else
		{
			$searchFields = '';
			$listAll      = "yes"; // first list all records when come to the suscription window if that member has subscribed at least one member
		}
	 	$rs = $this->propertySearch($pageNo,$limit, $params,$output, $orderBy,$searchFields,$uid,'','','',$listAll);
		return $rs;
	 }
	
	 function getSubscriberDetails($uid,$type)
	 {
		$sql="SELECT ms.`subscribed_id` as id,mm.username,mm.image FROM my_subscriptions ms 
		      INNER JOIN member_master mm on ms.subscribed_id=mm.id WHERE ms.`member_id` =$uid AND ms.`type` = '$type'";
		$rs=$this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	 function musicList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$tp='',$shop=0)
    {
        $sort=$orderBy;
        $tbl   = "media_comments";
        $fldnm = "cmcnt";
        $fld   = "count(e.id)";
        if($sort=="rating desc")
        {
            $tbl   = "media_rating";
            $fldnm = "rating";
            $fld   = "(sum(e.mark)/count(e.id))";
        }
        elseif($sort=="favcnt desc")
        {
            $tbl   = "media_favorites";
            $fldnm = "favcnt";
            $fld   = "count(e.id)";
        }
       list($qry,$table_id)=$this->generateQry('album_music','cs');
		
		$sql="SELECT a.*,$fld as $fldnm,d.username,$qry FROM
		((`album_music` a left join `album_files` b on (a.id=b.file_id and b.type='music'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='music') left join `custom_fields_list` cs 
		on a.id=cs.table_key and cs.table_id=$table_id"; 
		//print_r($sql);exit;
		if($catid>0)
		{
			
			$sql=$sql." where (a.cat_id=$catid) and (a.privacy='public')";
			if ($stxt!='')
			{
				$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
			}
			if($tp!='')
			{
				if ($tp!='All')
				{
					$sql=$sql. " and audio_type='$tp'";
				}
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
					$sql=$sql." and (a.privacy='public')";
				}	
				
				if($tp!='')
				{
					if ($tp!='All')
					{
						$sql=$sql. " and audio_type='$tp'";
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
					$sql=$sql." where (a.privacy='public')";
				}
				if($tp!='')
				{
					if ($tp!='All')
					{
						$sql=$sql. " and audio_type='$tp'";
					}
				}
				
			}
			
		}
		
		if ($shop == 1) {
			$sql=$sql." and a.price > 0 ";
		}

        $sql=$sql." group by a.id";
        //print $sql;
        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

        foreach ($rs as $value)
        {
            for($i=0;$i<sizeof($value);$i++)
            {
                if($value[$i]->id>0)
                {
                    $rating=$this->getRating('music',$value[$i]->id);
                    $rs[0][$i]->rate=$rating["rate"];
                    $rs[0][$i]->cnt=$rating["cnt"];

                    $cmcnt =$this->getCommentsCnt('music',$value[$i]->id);
                    $rs[0][$i]->cmtcnt=$cmcnt;

                    $favcnt =$this->getFavCnt('music',$value[$i]->id);
                    $rs[0][$i]->favrcnt=$favcnt;

                }
            }
        }
        //		print_r($rs);
        return $rs;
    }

	function postComment()
    {
        $arrData=$this->getArrData();
        $this->db->insert("media_comments",$arrData);
        return true;
    }
	function commentList1($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$vid)
    {
        $sql="    SELECT `media_comments`.*,`member_master`.image ,`member_master`.username 
                    FROM `media_comments`
              INNER JOIN `member_master` ON `media_comments`.user_id=`member_master`.id 
                   WHERE file_id='$vid' 
                     AND `media_comments`.type = 'album'
                ORDER BY postdate DESC";
        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
        return $rs;
    }
    
    //Fetching Details from Member Registration Table
    function getUserdetails($id)
    {
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','m');
		$sql="SELECT m.*,c.country_name,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on 
		      m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) $join_qry WHERE m.id='$id'";
		$RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
    }
    
    /**
	 * This function generates a playlist as XML for MP3 player
	 * Author   : Retheesh
	 * Created  : 14/Sep/2007
	 * Modified : 14/Sep/2007 By Retheesh
	 */
	
	function generatePlayListXML($req)
	{		
		ob_start();
		header("Content-Type:text/xml");
		$_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .="<xml>\r\n";
		$user_id = $req["user_id"];
		
		if ($_SESSION["xml_arr"])
		{
			$rs = $_SESSION["xml_arr"];
			
		}
		
		if ($user_id)
		{
			$user_det = $this->getUserdetails($user_id);
			
			if($user_det['mem_type']==1)
			{
				($req["pageNo"]) ? $pageNo = $req["pageNo"] : $pageNo=1;
				$rs = $this->getAllFavourites($user_id,$pageNo);
			}
			else 
			{
				if ($req['menu']=='mybox')
				{
					($req["pageNo"]) ? $pageNo = $req["pageNo"] : $pageNo=1;
					$rs = $this->getAllFavourites($user_id,$pageNo);
				}
				else 
				{
					($req["pageNo"]) ? $pageNo = $req["pageNo"] : $pageNo=1;
					$rs = $this->getAllTracks($user_id,$pageNo);
				}
			}	
			
		}
	
		if($req['restart']==0)
		{
			$_xml .= "<playlist sngcurrent='{$req['current']}' sngplaymode='{$req['play_mode']}' restart='{$req['restart']}'>";
		}else
		{
			$_xml .= "<playlist sngcurrent='{$req['current']}' sngplaymode='{$req['play_mode']}'>";
		}
		
		
		for($i=0;$i<sizeof($rs);$i++)
		{
			if ($rs[$i]->user_id>0)
			{
				$udet = $this->getUserdetails($rs[$i]->user_id);
			}	
			if ($udet["nick_name"]!="")
			{
				$rs[$i]->first_name = $udet["nick_name"];
			}
			else 
			{
				$rs[$i]->first_name =  $udet["first_name"];
			}
			$start = $rs[$i]->start_preview;
			if ($start=="") $start=0;
			if ($rs[$i]->play_duration>0)
			{
				$stop   = $start + $rs[$i]->play_duration;
			}
			else 
			{
				$stop = "";
			}
			if ($stop==0)
			{
				$start="";
				$stop="";
			}
			if ($rs[$i]->media_encrypt_name!="")
			{
				$file_name = $rs[$i]->media_encrypt_name;
				if ($rs[$i]->featured=="Y")
				{
					$file_name.="_featured";
				}
				else 
				{
					$file_name.="_other";
				}
				$start="";
				$stop="";
			}
			else 
			{
				$file_name = $rs[$i]->id;
			}
			//$title = htmlspecialchars($rs[$i]->title,ENT_QUOTES);
			$title = htmlspecialchars(utf8_encode($rs[$i]->title), ENT_QUOTES);
			$first_name = htmlspecialchars(utf8_encode($rs[$i]->first_name), ENT_QUOTES);
			$_xml .= "<track sngsrc='".SITE_URL."/modules/album/music/".$file_name.".mp3' sngartistname='{$first_name}' sngtrackname='{$title}' sngstart='$start' sngstop='$stop' sngvolume='100' />";
			
		}
			
		$_xml .= "</playlist>";	
		
		$_xml .="</xml>";
		
	
		
		ob_end_clean();
		
		return $_xml;	
	}
	
	/**
	 * This function retrieves all music tracks for a particular user id
	 * Created : 24/Sep/2007
	 * Author  : Retheesh
	 * @param numeric $user_id
	 */
	
	function getAllTracks($user_id,$pageNo=1,$ALL=0)
	{
		if ($pageNo==1)
		{
			$limit =0;
		}
		else 
		{
			$limit = ($pageNo-1) * 10;
		}
		$sql = "select * from album_music where user_id='$user_id' ";
		if($ALL==0)
		{
			$sql.="limit $limit,10";
		}	
		$rs  = $this->db->get_results($sql);
		return $rs;		
	}
	
	function getAllFavourites($uid,$pageNo=1,$ALL=0)
	{
		if ($pageNo==1)
		{
			$limit =0;
		}
		else 
		{
			$limit = ($pageNo-1) * 10;
		}
		$sql="Select a.* from (`album_music` a inner join `media_favorites` b ";
		$sql=$sql."on (a.id=b.file_id and b.type='music')) where b.userid=$uid order by a.title ";
		if($ALL==0)
		{
			$sql.="limit $limit,10";
		}	
		$rs  = $this->db->get_results($sql);
		return $rs;	
	}
	// Afsal created 17-09-2007
	// For realestatetube
	function addTogroup($group_id,$file_id)
	{
		if($this->checkGroupAlbm($group_id,$file_id))
		{
			$date=date("Y-m-d H:i:s");
			$array = array("group_id" => $group_id,"file_id" =>$file_id,"adate" =>$date);
			$this->db->insert("group_album",$array);
		}
	}
	function checkGroupAlbm($group_id,$file_id)
	{
		$sql 	= "SELECT file_id FROM group_album WHERE file_id=$file_id AND group_id=$group_id";
		$rs		= $this->db->get_row($sql);
		
		if(count($rs))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function countGroupProperties($group_id)
	{
		if($group_id > 0)
		{
			$sql = "SELECT count(group_id) AS properties FROM group_album WHERE group_id=$group_id";
			$rs = $this->db->get_row($sql,ARRAY_A);
			return $rs["properties"];
		}
	}
	function rateAlbum()
    {
        $arrData=$this->getArrData();
		
		$arrData["postdate"]=date("Y-m-d H:i:s");
        $sql="Select id from media_rating where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='album'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
		
            $this->db->insert("media_rating",$arrData);
            return true;
        }
        else
        {
            $this->setErr("You have already rated this video!!!");
            return false;
        }
    }
	//25-09-2007 today
	function favoritePropDelete($propid,$userid)
	{
		if($propid && $userid)
		$this->db->query("DELETE FROM media_favorites WHERE file_id=$propid AND userid=$userid");
	}
	
	//-----------function createEmailAlert() created by Jeffy on 26th sep 2007----------
	function createEmailAlert($req) {

		//insert the values to table
		$id = $this->db->insert("prop_search", $req);
		
		//search for the matching values
		$sql = "SELECT * from custom_fields_list where field_2='$req[prop_country]' and field_11='$req[cont_zip]'";
//		$sql .= "or field_3='$req[prop_region]' or field_1='$req[prop_city]' or field_13='$req[prop_bedrooms]' or field_12='$req[prop_bathrooms]' or field_14='$req[bld_sqft]' or field_15='$req[lot_sqft]' or field_16='$req[price]'";
		$rs = $this->db->get_results($sql);

		//email the search results
		$sql_email = "SELECT * from member_master where id=$req[user_id]";
		$rs_email = $this->db->get_results($sql_email);
		foreach($rs as $key => $value){
			$sql2 = "SELECT * from custom_fields_list where id=$value->id";
			$rs2 = $this->db->get_results($sql2);
			$to  = $rs_email[0]->email;
			$subject = 'Bayard Email Alerts';
			$message = "<html><head><title>Bayard Email Alerts</title></head><body><p align='center'><b>Bayard Email Alerts</b></p><table cellpadding='10' align='center' border='1'>";
			$message .= "<tr><th>Ad Title</th><th>City</th><th>Price</th></tr>";
			$message .= "<tr><td>".$rs2[0]->field_5."</td><td>".$rs2[0]->field_1."</td><td>".$rs2[0]->field_16."</td></tr></table></body></html>";
			$mail_from = "emailalerts@bayard.com";
			$headers = "From: ".$mail_from."\n";    
			$headers .= "X-Mailer: PHP/" . phpversion()."\n";
			$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
			echo "<br>email id: ".$to."<br>";
			die($message);
			mail($to, $subject, $message, $headers);
		}
		return $id;
	}
	//-------------------End------------------------
	
	//function to get property list in admin side, created by Jeffy on 3rd Oct 2007
	function propertyView($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt='')
		{
			list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
			if($search_fields)
			{
				list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
			}
			$sql = "SELECT a.*,$qry FROM album a $join_qry ";
	
			if($qry_cs)
			{
				$wh =retWhere($sql);
				$sql=$sql." $wh $qry_cs";
			}
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	//-------------------End------------------------		
	
	function insertAuthorDetails()
	{
		
		$arr = $this->getArrData();
		$id=$this->db->insert("author_details",$arr);
		return $id;
	}
	function updateAuthorDetails($id)
	{
		$arr = $this->getArrData();
		$id=$this->db->update("author_details",$arr,"id='$id'");
		return $id;
	}
/// function  chkAlbum checks the album already exitsts. Created by adarsh 16-10-2007
	function chkAlbum($name,$id='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		$sql = "SELECT a.*,$qry FROM album a $join_qry WHERE a.album_name='$name'";
		if($id)
		{
		$sql .= "and a.id !='$id'";
		}
		$rs = $this->db->get_row($sql, ARRAY_A);
		if(count($rs))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
///------------chkAlbum end-----------------///

##### Function to get the trade details of track to artist.....................
##### Author : Jipson Thomas...................................................
##### Dated  : 16October 2007..................................................

	function getSolidDetailsArtist($pageNo, $limit = 10, $params='', $output=OBJECT,$uid){
		$sql="SELECT m. * , so.order_date, sod.item_id, am. * 
FROM member_master m
INNER JOIN shop_order so ON m.id = so.member_id
INNER JOIN shop_order_details sod ON ( sod.order_id = so.id
AND sod.item_type = 'music' ) 
INNER JOIN album_music am ON sod.item_id = am.id
WHERE am.user_id =$uid and so.trade_type!='album'
ORDER BY so.order_date DESC 
";
//print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output);
		return $rs;
	}

##### End of Function  getSolidDetailsArtist...................................
##### Function to get the trade details of albums to artist....................
##### Author : Jipson Thomas...................................................
##### Dated  : 17October 2007..................................................

	function getSolidDetailsArtistAlbum($pageNo, $limit = 10, $params='', $output=OBJECT,$uid){
		$sql="Select DISTINCT s.id from shop_order As s INNER JOIN shop_order_details As sh ON s.id = sh.order_id INNER JOIN album_music As m ON sh.item_id = m.id AND m.user_id = 126 AND s.trade_type ='album'";
		$rs = $this->db->get_results($sql,ARRAY_A);
		//print_r($rs);
	//	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	//	$objUser     = new User();
		$mp=array();
		foreach ($rs as $re){
			$st="select id,member_id,order_date from shop_order where id=$re[id]";
			$ps = $this->db->get_results($st,ARRAY_A);
			}
		$mp[]=$ps;
		return $mp;

}

##### End of Function  getSolidDetailsArtistAlbum................................
	/**
	 * This function reports abuse about a particular media
	 * Author   : Retheesh
	 * Created  : 16/Oct/2007
	 * Modified : 16/Oct/2007 by Retheesh
	 *  
	 * @return unknown
	 */
	function reportAbuse()
	{
		$arr = $this->getArrData();
		$sql = "select * from abuse_list where user_id=".$arr['user_id']." and file_id=".
		$arr["file_id"]." and file_type='".$arr['file_type']."'";
				
		$rs = $this->db->get_row($sql);
		if (count($rs)>0)
		{
			$this->setErr("You have already reported abuse about this file");
			return false;
		}
		else 
		{
			$this->db->insert("abuse_list",$arr);
			return true;
		}	
	}

///Function used to get all the details fo album and authors. Created by adarsh on 17-10-2007

	function getAlbumAll($pageNo, $limit=10, $params='', $output=OBJECT, $orderBy,$search_fields,$search_values	,$id='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		
		$sql = "SELECT a.*,$qry FROM album a $join_qry ";
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
		}
		$sql = "SELECT a.* country_name ,$qry FROM album a $join_qry ";
		
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		if($id)
		{
			$sql.=" WHERE a.user_id='$id'";
		}
		$rs = $this->db->get_results_pagewise($sql,$pageNo, $limit, $params, $output, $orderBy);
		if(count($rs[0]))
		{
			foreach($rs[0] as $key=>$value)
			{
				if($value['conference_id']){
					$rs2=$this->getConferenceDetails($value['conference_id'],false);
					$rs[0][$key]['conference_id']=$rs2;
				}
				else if($value['journal_id'])
				{
					$rs2=$this->getJournalDetails1($value['journal_id'],false);
					$rs[0][$key]['journal_id']=$rs2;
				}
				else if($value['book_id'])
				{
					$rs2=$this->getBookDetails($value['book_id'],false);
					$rs[0][$key]['book_id']=$rs2;
				}
				else if($value['report_id'])
				{
					$rs2=$this->getInstitutionDetails($value['report_id'],false);
					$rs[0][$key]['report_id']=$rs2;
				}
				if($value['author_id'])
				{
					$authorId=explode(',',$value['author_id']);
					for($i=0;$i< count($authorId);$i++)
					{
						$rs3=$this->getAuthorDetails($authorId[$i],false);
						$author[$i]=$rs3;
					}
					$rs[0][$key]['author_id']=$author;
				}
				
			}
		}	

		return $rs;
		
	}	
///------------------end-----------------/////	

/// Function used to get authors of an article . Created by adarsh on 17-10-2007
	function getAuthors($id)
	{
		$sql = "select * from author_details where article_id='$id'";
		$rs  = $this->db->get_results($sql);
		return $rs;		
		
	}
///---------------getAuthors end-------------///	
	
/// Function used to get album details and  author detaills. Created by adarsh on 17-10-2007	
	function getAlbumDet($id)
	{
		if($id>0)
		{
			list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
			$sql = "SELECT a.*,$qry FROM album a $join_qry WHERE a.id='$id'";
			$rs = $this->db->get_row($sql, ARRAY_A);
				
				
			return $rs;
		
		}
	}
///---------------getAlbumDet end-------------///		
///Function used to get all the details fo album and authors. Created by adarsh on 17-10-2007

	function getAlbumByUsers($pageNo, $limit, $params='',$output=OBJECT, $orderBy,$id)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		
		$sql = "SELECT a.*,$qry FROM album a $join_qry ";
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
		}
		$sql = "SELECT a.* country_name ,$qry FROM album a $join_qry WHERE a.user_id='$id'";
		$rs = $this->db->get_results_pagewise($sql,$pageNo, $limit, $params, $output, $orderBy);
		if(count($rs[0]))
		{
			foreach($rs[0] as $key=>$value)
			{
				if($value['conference_id']){
					$rs2=$this->getConferenceDetails($value['conference_id'],false);
					$rs[0][$key]['conference_id']=$rs2;
				}
				else if($value['journal_id'])
				{
					$rs2=$this->getJournalDetails1($value['journal_id'],false);
					$rs[0][$key]['journal_id']=$rs2;
				}
				else if($value['book_id'])
				{
					$rs2=$this->getBookDetails($value['book_id'],false);
					$rs[0][$key]['book_id']=$rs2;
				}
				else if($value['report_id'])
				{
					$rs2=$this->getInstitutionDetails($value['report_id'],false);
					$rs[0][$key]['report_id']=$rs2;
				}
				if($value['author_id'])
				{
					$authorId=explode(',',$value['author_id']);
					for($i=0;$i< count($authorId);$i++)
					{
						$rs3=$this->getAuthorDetails($authorId[$i],false);
						$author[$i]=$rs3;
					}
					$rs[0][$key]['author_id']=$author;
				}
				
			}
		}	
		
		return $rs;
		
	}	
///------------------end-----------------/////	

///Function used to select different article type. Created by salim on 22-10-2007
	function articleType()
	{
		$rs = $this->db->get_results("SELECT DISTINCT field_2 FROM custom_fields_list",ARRAY_A);
		return $rs;
		
	}
///--------------------End-------------////////

///Function used to get comments posted to an article . Created by adarsh on 22-10-2007
function commentList2($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$vid)
    {
        $sql="    SELECT `media_comments`.*,`member_master`.image ,`member_master`.username 
                    FROM `media_comments`
              INNER JOIN `member_master` ON `media_comments`.user_id=`member_master`.id 
                   WHERE file_id='$vid' 
                     
                ORDER BY postdate DESC";
        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

        return $rs;
    }
//-------------------------End------------------///	

  /**
   * This function is used to insert the review of a particular the article .
   * Author   : Adarsh
   * Created  : 24/10/2007
   * Modified : 19/12/2007 By adarsh v s 
   */				
	function insertMemberReview($req)
	{
		$arr = $this->getArrData();
		$array=array();
		$this->db->insert("article_review",$arr);
		$id = $this->db->insert_id;
		for($i=0;$i< count($req['questid']);$i++)
		{
			$array['review_id']=$id;
			$array['option_id'] 	=	$req[opt_.$req["questid"][$i]];
			$array['pos'] 	=	$req["pos"][$i];
			$this->db->insert("article_review_options",$array);
		}
	}
///-------------End---------------///	
	/**
   * This function get the review of an article .
   * Author   : Adarsh
   * Created  : 24-10-2007
   * Modified : by adarsh on 26-12-2007
   */			
	function getMemberReview($id,$userid)
	{
		$sql="SELECT id FROM article_review  WHERE article_id='$id' AND user_id='$userid'";
		$rs = $this->db->get_row($sql, ARRAY_A);
		if(count($rs)<=0)
			return "false";
			
		$review_id=$rs['id'];
		$sql="SELECT b.value,b.qid,c.value AS qvalue,c.topic_id,a.pos  FROM  article_review_options a INNER JOIN review_option b ON  a.option_id=b.id INNER JOIN review_questions c ON  c.id=b.qid  WHERE a.review_id='$review_id' ";
		$rs1 = $this->db->get_results($sql,ARRAY_A);
		$curtotal="";
		$total="";
		$curSum=0;
		for($i=0;$i< count($rs1);$i++)
		{
			$currentID=$rs1[$i]['topic_id'];
			if($rs1[$i]['pos']==0){
				//$sum1=$rs1[$i]['value']*$rs1[$i]['qvalue'];
				$sum1=$this->Fx($rs1[$i]['value'],$rs1[$i]['qvalue']);
				$curSum= $curSum+$sum1;
			}
			else{
				$currentID=$rs1[$i]['topic_id'];
				$sum= $rs1[$i]['value']*$rs1[$i]['qvalue'];
				$curtotal= $curtotal+$sum;
			 }
		}
		
		$var= bcmul($curSum,$curtotal,10);
		$avg= bcdiv($var,100,10);
		$var2=bcmul($avg,20,10);
		return round($var2);
		//return $var;
	}
///-------------End---------------///	
	
####	:Function GetRating     // the function using to get the rating of photo, video, music, product or anything...............
####	:Author Jipson Thomas.....................
####	:Dated  30October2007..........................
	function GetRatingNew($table_name,$file_id,$type,$rate_table='rating'){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$sql = "SELECT (sum(mark)/count(mark)) as Rate,count(mark) as cnt  FROM `$rate_table`";
			$sql = $sql. " where file_id=$file_id and type='$type' and table_id=$tbid";
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
		//
	}

####	:End of function GetRating.............................
	
####	:Function AddRating     // the function using for rating photo, video, music, product or anything...............
####	:Author Jipson Thomas.....................
####	:Dated  25October2007..........................
	function AddRating($table_name,$file_id,$type,$mark,$memberid,$file_dup=0,$rate_table='rating')
	{
		
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			
			$arr=array();
			$arr["table_id"]	=	$tbid;
			$arr["file_id"]		=	$file_id;
			$arr["type"]		=	$type;
			$arr["mark"]		=	$mark;
			$arr["member_id"]	=	$memberid;
			
			$sql="Select id from $rate_table where file_id=$file_id and member_id=$memberid  and table_id=$tbid";
			if ($file_dup==1)
			{
				$sql.= " and type='$type'";
			}
			$count=count($this->db->get_results($sql));
			if($count==0)
			{
				$this->db->insert($rate_table, $arr);
				$msg="Your Rating has been stored.";
			}
			else
			{
			   $msg=("You have already rated this file!!!");
	
			}
		}else{
			$msg="Check the existance of $table_name";
		}
		return $msg;
	}

####	:End of function AddRating.............................


####	:Function AddFavourites     // the function using for storing photo, video, music, product or anything as my favourites.............
####	:Author Jipson Thomas.....................
####	:Dated  25October2007..........................
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

####	:End of function AddFavourites.............................

####	:Function GetFavCntNew     // the function using for getting the count of added to favourite of a photo, video, music, product or anything .............
####	:Author Jipson Thomas.....................
####	:Dated  30_October2007..........................
	function GetFavCntNew($table_name,$file_id,$type){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$sql = "SELECT count(id) as cnt  FROM `favourites`";
			$sql = $sql. " where file_id=$file_id and type='$type' and table_id=$tbid";
			$RS = $this->db->get_results($sql,ARRAY_A);
			return $RS[0]['cnt'];
		}
	}

####	:End of function AddFavourites.............................
function getFavCount($uid,$type,$table_name)
	{
		$tbid=$this->getCustomId($table_name);
		//print_r($tbid);exit;
		if($tbid){
			$sql="select count(id) as cnt from favourites where type='$type' and member_id=$uid and table_id=$tbid";
			//print_r($sql);exit;
			$RS = $this->db->get_results($sql,ARRAY_A);
			return $RS[0]['cnt'];
		}
	}
/**

	 * This function retrieves all Favourites  a particular user id for video or photo or any type...
	 * Created : 02/Nov/2007
	 * Author  : Jipson Thomas
	 */
	 
	 function FavList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$uid=0)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs','a');
		$sql="Select a.*,count(c.id),$qry from (`$tbl_name` a inner join `favourites` b ";
		$sql=$sql."on (a.id=b.file_id and b.type='$type'and b.table_id=$table_id))left join `media_comments` c on ";
		$sql=$sql."(a.id=c.file_id and c.type='$type') $join_qry  where b.member_id=$uid";
		if ($stxt!='')
		{
			$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
		}

		$sql=$sql." group by a.id";
		//print_r($sql);exit;

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$rating=$this->GetRatingNew($tbl_name,$value[$i]->id,$type);
					$favcnt =$this->GetFavCntNew($tbl_name,$value[$i]->id,$type);
					//print_r($favcnt);exit;
					//$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					//$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;
					
					$udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
				}
			}
		}
		//print_r($rs);exit;
		return $rs;
	}
	 
	 
	 
/**
         End of the function................*/
		
	####	:Function RemoveFavourites     // the function using for deleting photo, video, music, product or anything from my favourites.............
####	:Author Jipson Thomas.....................
####	:Dated  20ODecember2007..........................
	 function RemoveFavorite($table_name,$file_id,$type,$memberid) {
		 $tbid=$this->getCustomId($table_name);
		 if($tbid){
		 	$sql="DELETE FROM favourites WHERE file_id='$file_id' AND `type` = '$type' AND member_id='$memberid' and table_id=$tbid";
			//print_r($sql);exit;
			$this->db->query($sql);
			return true;
		}
		
	}

####	:End of function AddFavourites.............................	 
####	:Function AddMessageFile     // the function to store the message id based on file id..........
####	:Author Jipson Thomas.....................
####	:Dated  25October2007..........................
	function AddMessageFile($table_name,$file_id,$type,$msgid){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$arr=array();
			$arr["table_id"]	=	$tbid;
			$arr["file_id"]		=	$file_id;
			$arr["type"]		=	$type;
			$arr["msg_id"]		=	$msgid;
			$this->db->insert("message_file", $arr);
			$msg="The Message is stored successfully.";
		}else{
			$msg="Check the existance of $table_name";
		}
		return $msg;
	}

####	:End of function AddMessageFile.............................
####	:Function getmessageOnFile     // the function to gete the message  based on file id..........
####	:Author Jipson Thomas.....................
####	:Dated  08November2007..........................
	function getmessageOnFile($pageNo,$limit,$params,$output,$orderBy,$fid,$table_name,$type){
		$tbid=$this->getCustomId($table_name);
		if($tbid){
			$sql="SELECT m. * FROM message m INNER JOIN message_file mf ON m.id = mf.msg_id AND mf.type = '$type' AND mf.table_id =$tbid AND mf.file_id =$fid ORDER BY m.datetime desc";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}else{
			$msg="Check the existance of $table_name";
		}
	}

####	:End of function getmessageOnFile.............................

///Function used to insert member review . Created by adarsh on 24-10-2007	
	function updateMemberReview($qid,$user_id,$aid)
	{
		$arr = $this->getArrData();
		$this->db->update("member_review",$arr,"qid='$qid' and user_id='$user_id'and article_id='$aid'");
	
	}
///-------------End---------------///	
///Function used to add review topic name . Created by adarsh on 26-10-2007	
	function addEditReviewTopic (&$req) {

        extract($req);
        if(!trim($topic_name)) {
            $message = "Topic  Name is required";
        } elseif (!trim($position) || !ctype_digit($position) ) {
            $message = "Position is required";
        } else {
            if($id) {
                $array = array("topic_name"=>$topic_name, "position"=>$position);
                $this->db->update("review_topic", $array, "id='$id'");
            } else {
                $array = array("topic_name"=>$topic_name, "position"=>$position);
                $this->db->insert("review_topic", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }
//---------------end-------//	
///Function used to get all review topics . Created by adarsh on 26-10-2007	
	function reviewTopicList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT * FROM review_topic";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
//---------------end-------//	
///Function used to get all review topics . Created by adarsh on 26-10-2007			
	function getReviewTopicDetails ($id) {
        $sql		= "SELECT * FROM review_topic WHERE id = '$id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
//---------------end-------//		
///Function used to delete review topics . Created by adarsh on 26-10-2007			
	function reviewTopicDelete ($id) {
        $this->db->query("DELETE FROM review_topic WHERE id='$id'");
    }
//---------------end-------//		
///Function used to get all review topics . Created by adarsh on 26-10-2007	
	function getReviewTopics() {
		$sql= "SELECT * FROM review_topic  ORDER BY position ASC";
		$rs=$this->db->get_results($sql);
		return $rs;
		}
//---------------end-------//	
///Function used to add review qusetion . Created by adarsh on 26-10-2007	
	function addEditReviewQuestion(&$req) {

        extract($req);
        if(!trim($topic_id)) {
            $message = "Topic Name is required";
        } elseif (!trim($question)) {
            $message = "Question is required";
        } elseif (!trim($value)) {
            $message = "Value is required";
        } elseif (!trim($position) || !ctype_digit($position) ) {
            $message = "Position is required";
        }  else {
            if($id) {
                $array = array("topic_id"=>$topic_id,"question"=>$question,"value"=>$value, "position"=>$position);
                $this->db->update("review_questions", $array, "id='$id'");
            } else {
                $array = array("topic_id"=>$topic_id,"question"=>$question,"value"=>$value, "position"=>$position);
                $this->db->insert("review_questions", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }
//---------------end-------//	
///Function used to get all review question . Created by adarsh on 26-10-2007	
	function reviewQuestionList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT a.*,b.topic_name FROM review_questions a LEFT JOIN review_topic b on a.topic_id=b.id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
//---------------end-------//	
///Function used to get all review topics . Created by adarsh on 26-10-2007			
	function getReviewQuestionDet($id) {
        $sql		= "SELECT * FROM review_questions WHERE id = '$id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
//---------------end-------//	
///Function used to delete review quection . Created by adarsh on 26-10-2007			
	function reviewQuestionDelete($id) {
        $this->db->query("DELETE FROM review_questions WHERE id='$id'");
    }
//---------------end-------//			
///Function used to get all review topics . Created by adarsh on 26-10-2007	
	function getReviewQuestions() {
		$sql= "SELECT * FROM review_questions";
		$rs=$this->db->get_results($sql);
		return $rs;
		}
//---------------end-------//	
///Function used to add review qusetion . Created by adarsh on 26-10-2007	
	function addEditReviewOption(&$req) {

        extract($req);
        if(!trim($qid)) {
            $message = "Question is required";
        } elseif (!trim($option)) {
            $message = "Option is required";
	    } elseif (!trim($value)  ) {
            $message = "Vaule is required";
        } elseif (!trim($position) || !ctype_digit($position) ) {
            $message = "Position is required";
        }  else {
            if($id) {
                $array = array("qid"=>$qid,"option"=>$option,"value"=>$value,"position"=>$position);
                $this->db->update("review_option", $array, "id='$id'");
            } else {
                $array = array("qid"=>$qid,"option"=>$option,"value"=>$value,"position"=>$position);
                $this->db->insert("review_option", $array);
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }
//---------------end-------//	
///Function used to get all options of review  . Created by adarsh on 26-10-2007	
	function reviewOptionList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT a.*,b.question FROM review_option  a LEFT JOIN review_questions b on a.qid=b.id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
//---------------end-------//	
///Function used to get the option details . Created by adarsh on 26-10-2007			
	function getReviewOptionDet($id) {
        $sql		= "SELECT * FROM review_option WHERE id ='$id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
//---------------end-------//	
///Function used to delete the option in the review . Created by adarsh on 26-10-2007			
	function reviewOptionDelete($id) {
        $this->db->query("DELETE FROM review_option WHERE id='$id'");
    }
//---------------end-------//			
///Function used to get all review topics . Created by adarsh on 26-10-2007			
	function getReiviewQuest($id) {
        $sql	= "SELECT * FROM review_questions WHERE topic_id ='$id' ORDER BY position ASC";
        $rs = $this->db->get_results($sql, ARRAY_A);
        return $rs;
    }
//---------------end-------//	
///Function used to get all review otpion . Created by adarsh on 26-10-2007			
	function getOptions($id) {
        $sql	= "SELECT * FROM review_option WHERE qid ='$id' ORDER BY position ASC";
        $rs = $this->db->get_results($sql, ARRAY_A);
        return $rs;
    }
//---------------end-------//
	function changesharemode($arr,$id,$crt='M2')
	{
		if($crt=='M1'){
			$tbname="album_video";
		}elseif($crt=='M2'){
			$tbname="album_photos";
		}elseif($crt=='M3'){
			$tbname="album_music";
		}elseif($crt=='M5'){
			$tbname="album_products";
		}else{
			$tbname="album_photos";
		}
		$this->db->update($tbname, $arr, "id='$id'");
	}
	/// Function getArticleByFields // get forum toic with article details.
	/// Author : Adarsh v.s
	// Created : 30-10-2007
	
	function getArticleByFields($search_fields='',$search_values='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('forum_topic','d','a');
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('forum_topic',$search_fields,$search_values,$criteria,'a','d');	
		}
		$sql = "SELECT a.*,$qry FROM forum_topic a $join_qry ";

		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		$rs = $this->db->get_results($sql, ARRAY_A);
		
		return $rs;
		
	}
	/////-----End----------///
	/// Function getArticleByFields // formaat the article array.
	/// Author : Adarsh v.s
	// Created : 30-10-2007
	function getArticleArray($article)
	{
		$articleArr=array();
		 for($i=0;$i< count($article); $i++)
		 {
		 	for($j=0;$j< count($article[$i]["child"]);$j++)
			{
				if($j==0)
				{
					foreach($article[$i]["child"][$j]['child'] as $key=> $val)
					{
						$articleArr[strtolower($val['name'])]  =$val['content'];
					}
				}
				else
				{	
					
					foreach($article[$i]["child"][$j]['child'] as $key=> $val)
					{
						$articleArr[strtolower($val['name'])][$j]  =$val['content'];
					}
							
				}	
			}
		 
		 }
		 return $articleArr;
	}
	/////-----End----------///
	/// Function getArticleByFields // searc artile list.
	/// Author : Adarsh v.s
	// Created : 30-10-2007
	function articleSearchList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$stxt1='',$stxt2='',$alb=0,$uid=0,$privacy='',$search_fields='',$search_values='',$mem_type='',$criteria='=')
	{
		
		$path = SITE_PATH."/modules/album/article/";
		list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'a','d');	
		}
		$sql= "SELECT a.*,$qry FROM album a $join_qry  ";		
			
		if ($stxt!='')
		{
			$sql=$sql. " where (a.album_name like '%$stxt%' )";
		}
		
		if($stxt1 !='')
		{
			$res = $this->db->get_results($sql,ARRAY_A);
			$sql_qry="select id from author_details where author like '%$stxt1%'";
			$rs1 = $this->db->get_results($sql_qry);
			
			for($j=0;$j< count($rs1);$j++)
			{ 
				$authorIds[]=$rs1[$j]->id;
			}
			for($i=0;$i< count($res);$i++)
			{
				$array_author=explode(',',$res[$i]['author_id']);
				
				for($j=0;$j< count($array_author);$j++)
				{
					for($m=0;$m < count($authorIds);$m++)
					{ 
						if($array_author[$j]==$authorIds[$m])
						{
							$array[]=$res[$i]['id'];
						}
					}	
				}
			}
			
		}
		if($stxt2)
		{
				$rs = $this->db->get_results($sql,ARRAY_A);
				$qry1="select id from conference where conference_name like '%$stxt2%' or conference_acronym like '%$stxt2%' or conference_sponsors like '%$stxt2%'
				or conference_publisher like '%$stxt2%' or conference_url like '%$stxt2%' or conference_isbn like '%$stxt2%'";
				$rs1 = $this->db->get_results($qry1);
				for($j=0;$j< count($rs1);$j++)
				{ 
					$conferenceIds[]=$rs1[$j]->id;
				}
				$qry2="select id from journals where journal_name like '%$stxt2%' or journal_acronym like '%$stxt2%' or journal_publisher like '%$stxt2%'
				or journal_url like '%$stxt2%' or journal_isbn like '%$stxt2%'";
				$rs2 = $this->db->get_results($qry2);
				
				for($j=0;$j< count($rs2);$j++)
				{ 
					$journalIds[]=$rs2[$j]->id;
				}
				 $qry3="select id from book where book_title like '%$stxt2%' or book_author like '%$stxt2%' or book_publisher like '%$stxt2%'
				or book_url like '%$stxt2%' or book_isbn like '%$stxt2%'";
				$rs3 = $this->db->get_results($qry3);
				for($j=0;$j< count($rs3);$j++)
				{ 
					$bookIds[]=$rs3[$j]->id;
				}
				
				$qry4="select id from institution where institution_name like '%$stxt2%' or institution_city like '%$stxt2%' or institution_email like '%$stxt2%'";
				$rs3 = $this->db->get_results($qry4);
				for($j=0;$j< count($rs4);$j++)
				{ 
					$reportIds[]=$rs4[$j]->id;
				}
				$sql_qry="select id from author_details where author like '%$stxt2%'";
				$rs1 = $this->db->get_results($sql_qry);
				for($j=0;$j< count($rs1);$j++)
				{ 
					$authorIds[]=$rs1[$j]->id;
				}
				for($i=0;$i< count($rs);$i++)
				{
					$array_author=explode(',',$rs[$i]['author_id']);
					for($j=0;$j< count($array_author);$j++)
					{
						for($m=0;$m < count($authorIds);$m++)
						{ 
							if($array_author[$j]==$authorIds[$m])
							{
								$array[]=$rs[$i]['id'];
							}
						}	
					}
				}
				for($i=0;$i< count($rs);$i++)
				{
					//echo $rs[$i]['conference_id'].'<br>';
					for($j=0;$j < count($conferenceIds);$j++)
					{
						if($rs[$i]['conference_id']==$conferenceIds[$j])
						{
							$array[]=$rs[$i]['id'];
						}
					}
					for($j=0;$j < count($journalIds);$j++)
					{
						if($rs[$i]['journal_id']==$journalIds[$j])
						{
							$array[]=$rs[$i]['id'];
						}
					}
					for($j=0;$j < count($bookIds);$j++)
					{
						if($rs[$i]['book_id']==$bookIds[$j])
						{
							$array[]=$rs[$i]['id'];
						}
					}
					for($j=0;$j < count($reportIds);$j++)
					{
						if($rs[$i]['report_id']==$reportIds[$j])
						{
							$array[]=$rs[$i]['id'];
						}
					}
				}
				
		}
		//print_r($arry);
		if($stxt2 || $stxt1)
		{
			if(count($array))
			{
				$str=implode(',',$array);
			}
			else
			$str="''";
			if($stxt)
			{
				$sql.= "or  a.id in ($str)";
			}
			else
			$sql.= "where a.id in ($str) ";
			
			if ($stxt2!='')
			{
				list($qry_cs2)=$this->getCustomQry('album','abstract',$stxt2,'like','a','d');
				list($qry_cs3)=$this->getCustomQry('album','doi',$stxt2,'like','a','d');	
				$sql=$sql. " or (a.album_name like '%$stxt2%') or $qry_cs2 or $qry_cs3 ";
			}	
		}
		if($qry_cs)
		{
			 $wh =retWhere($sql);
			 $sql=$sql." $wh $qry_cs";
		}
		$sql=$sql." group by a.album_name";	
		$rs='';
		//echo $sql;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		if(count($rs[0]))
		{
			foreach($rs[0] as $key=>$value)
			{
				if($value['conference_id']){
					$rs2=$this->getConferenceDetails($value['conference_id'],false);
					$rs[0][$key]['conference_id']=$rs2;
				}
				else if($value['journal_id'])
				{
					$rs2=$this->getJournalDetails1($value['journal_id'],false);
					$rs[0][$key]['journal_id']=$rs2;
				}
				else if($value['book_id'])
				{
					$rs2=$this->getBookDetails($value['book_id'],false);
					$rs[0][$key]['book_id']=$rs2;
				}
				else if($value['report_id'])
				{
					$rs2=$this->getInstitutionDetails($value['report_id'],false);
					$rs[0][$key]['report_id']=$rs2;
				}
				if($value['author_id'])
				{
					$authorId=explode(',',$value['author_id']);
					for($i=0;$i< count($authorId);$i++)
					{
						$rs3=$this->getAuthorDetails($authorId[$i],false);
						$author[$i]=$rs3;
					}
					$rs[0][$key]['author_id']=$author;
				}
				if($value['id'])
				{
				 $filename=$path.$value['id'].".".$value['file_type'];
				 
				 if(file_exists($filename))
		         {
					$url=$path.$value['id'].".".$value['file_type'];
					$rs[0][$key]['url']=$url;
		         }
				 else if($value['link_to_paper'])
				 {
				 	$rs[0][$key]['url']=$value['link_to_paper'];
				 }
				 else if($value['link_pointing_to_paper'])
				 {
				 	$$rs[0][$key]['url']=$value['link_pointing_to_paper'];
				 }
			}
				//print_r($this->getRateingNumber($value['id']));
				
			}
			
		}	
		//print_r($rs);
		return $rs;
	}
//End	
////// Function addEditConference // Add Edit the conference article data.
	/// Author : Adarsh v.s
	// Created : 06-11-2007
	function addEditConference(&$req) {
		
        extract($req);
        if(!trim($conference_name)) {
            $message = "Full conference name is required";
        } elseif (!trim($conference_acronym)) {
            $message = "Acronym is required";
	    } elseif (!trim($conference_country)) {
            $message = "country is required";
        } elseif (!trim($conference_month)) {
            $message = "Month is required";
        } elseif (!trim($conference_year)) {
            $message = "Year is required";
        } elseif (!trim($conference_sponsors) ) {
            $message = "Sponsors is required";
        } elseif (!trim($conference_quality_rating) ) {
            $message = "Quality rating is required";
        }  else {
			$arrVal=array("conference_name"=>$conference_name,"conference_country"=>$conference_country,"conference_year"=>$conference_year);
			$arr=$this->chkDuplication($arrVal,'conference',$req['id']);
			if($arr=== true)				 
			{
				$conferenceId=$this->chkConferenceUsingTags($req,'name',$req['id']);
				if($conferenceId===true)
				{
					$array =$this->getArrData();
					$tags_array=explode('|',$array['tag']);
					unset($array['tag']);
					if($id) {
						$var=$this->db->update("conference", $array, "id='$id'");
						$this->db->query("DELETE FROM conference_tags WHERE conid='$id'");
					} else {
						$this->db->insert("conference", $array);
						$id = $this->db->insert_id;
					}
					for($i=0;$i< count($tags_array);$i++)
					{
						if($tags_array[$i])
						{
							$tag =array('conid'=>$id,'tag'=>trim(strtolower($tags_array[$i])),'type'=>'name');
							$this->db->insert("conference_tags", $tag);
						}
					}
					return true;
				}
				else{	
					return $conferenceId;
					
				}
			}
			else{
			 return $arr;
			 }
				
        }
       return $message;
    }
	
//---------------end-------//	
 ///Function reviewOptionList//  list all conference.
	/// Author : Adarsh v.s
	// Created : 06-11-2007
	function getConferenceList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT a.*,b.country_name FROM conference a LEFT JOIN country_master b ON a.conference_country=b.country_id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
//---------------end-------//	
///Function chkDuplication//  chk for dulication of number of fields in conference journal etc.
	/// Author : Adarsh v.s
	// Created : 06-11-2007
	function chkDuplication($array,$tbl,$id='')
	{
		if(is_array($array))
		{
			$i=0;
			foreach($array as $key =>$val)
			{
				$i++;
				if(count($array)!=$i)
				$str=" AND ";
				else
				$str="";
				$chkValue.=$key." = "."'".$val."'" .$str;
			}
			if($id)
			{
				$chkValue.=" AND id != {$id}";
			}
			  $sql="SELECT id FROM $tbl where {$chkValue}";
			
			 $rs=$this->db->get_results($sql);
			 if(count($rs)==0)
				return true;
			 else
			 {
				 $chkId=array();
				 for($i=0;$i< count($rs);$i++)
				 {
					$chkId[] .= $rs[$i]->id;
				 }
				 return $chkId;
			 }
		 }
	}
	
//---------------end-------//		
	/**
          * This function is used to get the get tails of conference article.
          * Author   : Adarsh
          * Created  : 14/Nov/2007
          * Modified : 
    */
	function getConferenceDetails($id,$flag=true) {
         $sql = "SELECT a.*,b.country_name FROM conference a LEFT JOIN country_master b ON b.country_id= a.conference_country WHERE a.id ='$id'";
         $rs  = $this->db->get_row($sql, ARRAY_A);
		if($flag===true)
		{
			$sql = "SELECT tag FROM conference_tags WHERE conid='$id' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['tag'] .= $pipe.$rs1[$i]->tag;
			}
		}
        return $rs;
    }
	//---------------end-------//	
 	/**
   * This function is used to delete the conference article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function conferenceDelete($id) {
        $this->db->query("DELETE FROM conference WHERE id='$id'");
    }
	//---------------end-------//
	/**
   * This function is used to add jourrnal article article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function addEditJournal(&$req) {
		
        extract($req);
        if(!trim($journal_name)) {
            $message = "Full journal name is required";
        } elseif (!trim($journal_acronym)) {
            $message = "Acronym is required";
	    }  elseif (!trim($journal_year)) {
            $message = "Year is required";
        } elseif (!trim($journal_volume) ) {
            $message = "Volume is required";
        } elseif (!trim($journal_number) ) {
            $message = "Number is required";
        } elseif (!trim($journal_publisher) ) {
            $message = "Publisher is required";
        }elseif (!trim($journal_quality_rating) ) {
            $message = "Quality rating is required";
        }  else {
			$arrVal=array("journal_name"=>$journal_name,"journal_year"=>$journal_year,"journal_volume"=>$journal_volume,"journal_number"=>$journal_number);
			$dupJournal=$this->chkDuplication($arrVal,'journals',$req['id']);
			
			if($dupJournal===true)				 
			{
				$journalId=$this->chkJournalUsingTags($req,'name',$req['id']);
				if($journalId===true)
				{
					$array =$this->getArrData();
					$tags_array=explode('|',$array['tag']);
					unset($array['tag']);
					if($id) {
						 $this->db->update("journals", $array, "id='$id'");
						 $this->db->query("DELETE FROM journal_tags WHERE journal_id='$id'");
					} else {
						$this->db->insert("journals", $array);
						$id = $this->db->insert_id;
					}
					for($i=0;$i< count($tags_array);$i++)
					{
						if($tags_array[$i])
						{
							$tag =array('journal_id'=>$id,'tag'=>trim(strtolower($tags_array[$i])),'type'=>'name');
							$this->db->insert("journal_tags", $tag);
						}	
					}
					return true;
				}
				else
					 return $journalId;
			}
			else
			 return $dupJournal;
				
        }
        return $message;
    }
	
//---------------end-------//		
	/**
	   * This function is used to get jourrnal article article.
	   * Author   : Adarsh
	   * Created  : 14/Nov/2007
	   * Modified : 
	   */			
		function getJournalList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
				$sql		= "SELECT * FROM journals";
				$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
				return $rs;
			}
//---------------end-------//	
 /**
   * This function is used to get the get detaila of journal article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */
	function getJournalDetails1($id,$flag=true) {
        $sql = "SELECT * FROM journals  WHERE id ='$id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);
		
		if($flag===true)
		{
			$sql = "SELECT tag FROM journal_tags WHERE journal_id='$id' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['tag'] .= $pipe.$rs1[$i]->tag;
			}
		}	
        return $rs;
    }		
 /**
   * This function is used to delete the journal article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function journalDelete($id) {
        $this->db->query("DELETE FROM journals WHERE id='$id'");
    }
	/**
   * This function is used to add book article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function addEditBook(&$req) {
		
        extract($req);
        if(!trim($book_title)) {
            $message = "Book title is required";
        } elseif (!trim($book_author)) {
            $message = "Author is required";
	    }  elseif (!trim($book_year)) {
            $message = "Year is required";
        } elseif (!trim($book_publisher) ) {
            $message = "Publisher is required";
        } else {
			$arrVal=array("book_title"=>$book_title,"book_author"=>$book_author,"book_year"=>$book_year,"book_publisher"=>$book_publisher);
			$bookArr=$this->chkDuplication($arrVal,'book',$req['id']);
			
			if($bookArr===true)				 
			{
				$bookId=$this->chkBookUsingTags(trim(strtolower($book_title)),'title',$req['id']);
				if($bookId===true)
					  $bookId=$this->chkBookUsingTags(trim(strtolower($book_author)),'author',$req['id']);
				if($bookId===true)
					 $bookId=$this->chkBookUsingTags($book_publisher,'publisher',$req['id']);	
					
				if($bookId===true)
				{
					$title_tag_array=explode('|',$book_title_tag);
					$author_tag_array=explode('|',$book_author_tag);
					$publisher_tag_array=explode('|',$book_publisher_tag);
					$array=$this->getArrData();
					unset($array['book_title_tag']);
					unset($array['book_author_tag']);
					unset($array['book_publisher_tag']);
						if($id) {
						$this->db->update("book", $array, "id='$id'");
						$this->db->query("DELETE FROM book_tags WHERE book_id='$id'");
					} else {
						$this->db->insert("book", $array); 	 
						$id = $this->db->insert_id;
					}
					for($i=0;$i< count($title_tag_array);$i++)
					{
						if($title_tag_array[$i])
						{
							$tag =array('book_id'=>$id,'tag'=>trim(strtolower($title_tag_array[$i])),'type'=>'title');
							$this->db->insert("book_tags", $tag);
						}	
					}
					for($i=0;$i< count($author_tag_array);$i++)
					{
						if($author_tag_array[$i])
						{
							$tag =array('book_id'=>$id,'tag'=>trim(strtolower($author_tag_array[$i])),'type'=>'author');
							$this->db->insert("book_tags", $tag);
						}	
					}
					for($i=0;$i< count($publisher_tag_array);$i++)
					{
						if($publisher_tag_array[$i])
						{
							$tag =array('book_id'=>$id,'tag'=>trim(strtolower($publisher_tag_array[$i])),'type'=>'publisher');
							$this->db->insert("book_tags", $tag);
						}	
					}
					return true;
				}
				else
					return $bookId;
				
			}
			else
			 return $bookArr;
				
        }
        return $message;
    }
/**
   * This function is used to get jourrnal article article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function getBookList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT * FROM book";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}	
 /**
   * This function is used to get the get detaila of journal article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */
	function getBookDetails($id,$flag=true) {
        $sql = "SELECT * FROM book WHERE id ='$id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);
		if($flag===true)
		{
			$sql = "SELECT tag FROM book_tags WHERE book_id='$id' AND type='title' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['book_title_tag'] .= $pipe.$rs1[$i]->tag;
			}
			$sql = "SELECT tag FROM book_tags WHERE book_id='$id' AND type='author' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['book_author_tag'] .= $pipe.$rs1[$i]->tag;
			}
			$sql = "SELECT tag FROM book_tags WHERE book_id='$id' AND type='publisher' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['book_publisher_tag'] .= $pipe.$rs1[$i]->tag;
			}
		}	
        return $rs;
    }
   /**
   /**
   * This function is used to delete the book article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function bookDelete($id) {
        $this->db->query("DELETE FROM book WHERE id='$id'");
    }
	
	/**
   * This function is used to add institution type article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function addEditInstitution(&$req) {
		
        extract($req);
        if(!trim($institution_name)) {
            $message = "Institution name is required";
        } elseif (!trim($institution_city)) {
            $message = "City is required";
	    }  elseif (!trim($institution_state)) {
            $message = "State is required";
        } elseif (!trim($institution_country) ) {
            $message = "Country is required";
        } else {
			$arrVal=array("institution_name"=>$institution_name,"institution_city"=>$institution_city,"institution_state"=>$institution_state,"institution_country"=>$institution_country);
			$institutionArr=$this->chkDuplication($arrVal,'institution',$req['id']);
			
			if($institutionArr===true)				 
			{
				$institutionId=$this->chkInstitutionUsingTags(trim(strtolower($institution_name)),'name',$req['id']);
				if($institutionId===true)
					$institutionId=$this->chkInstitutionUsingTags(trim(strtolower($institution_city)),'city',$req['id']);
				if($institutionId===true)
					$institutionId=$this->chkInstitutionUsingTags(trim(strtolower($institution_state)),'state',$req['id']);	
				
				if($institutionId===true)
				{
					$name_tag_array=explode('|',$institution_name_tag);
					$city_tag_array=explode('|',$institution_city_tag);
					$state_tag_array=explode('|',$institution_state_tag);
					$array=$this->getArrData();
					unset($array['institution_name_tag']);
					unset($array['institution_city_tag']);
					unset($array['institution_state_tag']);
					if($id) {
						
						$this->db->update("institution", $array, "id='$id'");
						$this->db->query("DELETE FROM institution_tags WHERE institution_id='$id'");
					} else {
						$this->db->insert("institution", $array);
						$id = $this->db->insert_id;
					}
					for($i=0;$i< count($name_tag_array);$i++)
						{
							if($name_tag_array[$i])
							{
								$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($name_tag_array[$i])),'type'=>'name');
								$this->db->insert("institution_tags", $tag);
							}	
						}
					
					for($i=0;$i< count($city_tag_array);$i++)
					{
						if($institution_city_tag[$i])
						{
							$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($city_tag_array[$i])),'type'=>'city');
							$this->db->insert("institution_tags", $tag);
						}	
					}
					for($i=0;$i< count($state_tag_array);$i++)
					{
						if($state_tag_array[$i])
						{
							$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($state_tag_array[$i])),'type'=>'state');
							$this->db->insert("institution_tags", $tag);
						}	
					}
					return true;
				}
				else
				return $institutionId;
			}
			else
			return $institutionArr;
				
        }
        return $message;
    }
	/**
   * This function is used to get instution type  article.
   * Author   : Adarsh
   * Created  : 16/Nov/2007
   * Modified : 
   */			
	function getInstitutionList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT a.*,b.country_name FROM institution a LEFT JOIN country_master b  ON a.institution_country = b.country_id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}	
	/**
   * This function is used to get the get details of institution article.
   * Author   : Adarsh
   * Created  : 16/Nov/2007
   * Modified : 
   */
	function getInstitutionDetails($id,$flag=true) {
        $sql = "SELECT * FROM institution WHERE id ='$id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);
		if($flag===true)
		{
			$sql = "SELECT tag FROM institution_tags WHERE institution_id='$id' AND type='name' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['institution_name_tag'] .= $pipe.$rs1[$i]->tag;
			}
			$sql = "SELECT tag FROM institution_tags WHERE institution_id='$id' AND type='city' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['institution_city_tag'] .= $pipe.$rs1[$i]->tag;
			}
			$sql = "SELECT tag FROM institution_tags WHERE institution_id='$id' AND type='state' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['institution_state_tag'] .= $pipe.$rs1[$i]->tag;
			}
		}	
        return $rs;
    }	
	/**
   * This function is used to delete the institution type article.
   * Author   : Adarsh
   * Created  : 14/Nov/2007
   * Modified : 
   */			
	function institutionDelete($id) {
        $this->db->query("DELETE FROM institution WHERE id='$id'");
    }
	/**
   * This function is used to add author.
   * Author   : Adarsh
   * Created  : 16/Nov/2007
   * Modified : 
   */			
	function addEditAuthor(&$req) {
		
        extract($req);
        if(!trim($author)) {
            $message = "Author name is required";
        } elseif (!trim($institution)) {
            $message = "Institution is required";
	    }  elseif (!trim($country)) {
            $message = "Country is required";
        }  else {
			$arrVal=array("author"=>$author,"institution"=>$institution,"country"=>$country);
			$authorId=$this->chkDuplication($arrVal,'author_details',$req['id']);
			if($authorId===true)				 
			{
				$authorId=$this->chkAuthorUsingTags(trim(strtolower($author)),'name',$req['id']);
				if($authorId===true)
					$authorId=$this->chkAuthorUsingTags(trim(strtolower($institution)),'institution',$req['id']);
					
				if($authorId===true)
				{
					$name_tag_array=explode('|',$author_name_tag);
					$institution_tag_array=explode('|',$author_institution_tag);
					$array=$this->getArrData();
					unset($array['author_name_tag']);
					unset($array['author_institution_tag']);
					if($id) {
						$this->db->update("author_details", $array, "id='$id'");
						$this->db->query("DELETE FROM author_tags WHERE author_id='$id'");
					} else {
						$this->db->insert("author_details", $array);
						$id = $this->db->insert_id;
					}
					for($i=0;$i< count($name_tag_array);$i++)
					{
						if($name_tag_array[$i])
						{
							$tag =array('author_id'=>$id,'tag'=>trim(strtolower($name_tag_array[$i])),'type'=>'name');
							$this->db->insert("author_tags", $tag);
						}	
					}
						
					for($i=0;$i< count($institution_tag_array);$i++)
					{
						if($institution_tag_array[$i])
						{
							$tag =array('author_id'=>$id,'tag'=>trim(strtolower($institution_tag_array[$i])),'type'=>'institution');
							$this->db->insert("author_tags", $tag);
						}	
					}
					return true;
				}
				else
					return $authorId;
				
			}
			else
			 return $authorId;
				
        }
        return $message;
    }
	/**
   * This function is used to get list of authors.
   * Author   : Adarsh
   * Created  : 16/Nov/2007
   * Modified : 
   */			
	function getAuthorList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT a.*,b.country_name FROM author_details a LEFT JOIN country_master b  ON a.country = b.country_id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}	
	/**
   * This function is used to get the get details of author by ID.
   * Author   : Adarsh
   * Created  : 16/Nov/2007
   * Modified : 
   */
	function getAuthorDetails($id,$flag=true) {
        $sql = "SELECT a.*,b.country_name FROM author_details a LEFT JOIN country_master b  ON a.country = b.country_id WHERE a.id ='$id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);
		if($flag===true)
		{
			$sql = "SELECT tag FROM author_tags WHERE author_id='$id' AND type='name' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else
				$pipe="|";
				$rs['author_name_tag'] .= $pipe.$rs1[$i]->tag;
			}
			$sql = "SELECT tag FROM author_tags WHERE author_id='$id' AND type='institution' ORDER BY id";
			$rs1 = $this->db->get_results($sql);
			for($i=0;$i< count($rs1);$i++)
			{
				if($i==0)
				$pipe="";
				else 
				$pipe="|";
				$rs['author_institution_tag'] .= $pipe.$rs1[$i]->tag;
			}
		}	
        return $rs;
    }
	/**
   * This function is used to delete the author.
   * Author   : Adarsh
   * Created  :16/Nov/2007
   * Modified : 
   */			
	function authorDelete($id) {
        $this->db->query("DELETE FROM author_details WHERE id='$id'");
    }
	/**
   * This function is check the book details using tag fileds .
   * Author   : Adarsh
   * Created  :27/Nov/2007
   * Modified : 
   */			
	function chkBookUsingTags($val,$type,$id='') {
        $sql="SELECT book_id FROM book_tags WHERE tag='$val' AND type='$type'";
		if($id){
			$sql .= " AND book_id !='$id'";
		}
		$sql .=" GROUP BY book_id";
		$rs = $this->db->get_results($sql);
		if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->book_id;
			}
			return $array;
		}	
    }
	/**
   * This function is check the institution article details using tag fileds .
   * Author   : Adarsh
   * Created  :27/Nov/2007
   * Modified : 
   */			
	function chkInstitutionUsingTags($val,$type,$id='') {
        $sql="SELECT institution_id FROM institution_tags WHERE tag='$val' AND type='$type'";
		if($id){
			$sql .= " AND institution_id !='$id'";
		}
		$sql .=" GROUP BY institution_id";
		$rs = $this->db->get_results($sql);
		
		if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->institution_id;
			}
			return $array;
		}	
    }
	/**
   * This function is check the authors article details using tag fileds .
   * Author   : Adarsh
   * Created  :28/Nov/2007
   * Modified : 
   */			
	function chkAuthorUsingTags($val,$type,$id='') {
        $sql="SELECT author_id FROM author_tags WHERE tag='$val' AND type='$type'";
		if($id){
			$sql .= " AND author_id !='$id'";
		}
		$sql .=" GROUP BY author_id";
		$rs = $this->db->get_results($sql);
		
		if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->author_id;
			}
			return $array;
		}	
    }
	/**
   * This function is check the conference article details using tag fileds .
   * Author   : Adarsh
   * Created  :28/Nov/2007
   * Modified : 
   */			
	function chkConferenceUsingTags($val,$type,$id='') {
	 	extract($val);
        $sql="SELECT b.conid FROM conference a INNER JOIN conference_tags b WHERE b.tag='$conference_name' AND b.type='$type' AND conference_country='$conference_country' AND conference_year='$conference_year'";
		if($id){
			$sql .= " AND b.conid !='$id'";
		}
		$sql .=" GROUP BY b.conid";
		$rs = $this->db->get_results($sql);
		if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->conid;
			}
			return $array;
		}	
    }
	/**
   * This function is check the journal article details using tag fileds .
   * Author   : Adarsh
   * Created  :28/Nov/2007
   * Modified : 
   */			
	function chkJournalUsingTags($val,$type,$id='') {
		extract($val);
        $sql="SELECT a.id FROM journals a INNER JOIN  journal_tags b ON b.journal_id=a.id  WHERE b.tag='$journal_name' AND  b.type='$type' AND a.journal_year='$journal_year' AND a.journal_volume='$journal_volume' AND a.journal_number='$journal_number'";
		if($id){
			$sql .= " AND b.journal_id !='$id'";
		}
		$sql .=" GROUP BY b.journal_id";
		$rs = $this->db->get_results($sql);
				if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->id;
			}
			return $array;
		}	
    }
	/**
   * This function is used to get the get details of author by name.
   * Author   : Adarsh
   * Created  : 04/Des/2007
   * Modified : 
   */
	function getAuthorDetailsByName($name) {
        $sql = "SELECT a.*,b.country_name FROM author_details a LEFT JOIN country_master b  ON a.country = b.country_id WHERE a.author ='$name'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }
	/**
   * This function is used to get the get details of conference article by conference name.
   * Author   : Adarsh
   * Created  : 06/Des/2007
   * Modified : 
   */
	function getConferenceDetailsByName($name) {
        $sql = "SELECT a.*,b.country_name FROM conference a LEFT JOIN country_master b  ON a.conference_country = b.country_id WHERE a.conference_name ='$name'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }
	/**
   * This function is used to get the get details of journal article by journal name.
   * Author   : Adarsh
   * Created  : 07/Des/2007
   * Modified : 
   */
	function getJournalDetai1sByName($name) {
        $sql = "SELECT a.* FROM journals a  WHERE a.journal_name ='$name'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }	
	/**
   * This function is used to get the get details of book article by book name.
   * Author   : Adarsh
   * Created  : 07/Des/2007
   * Modified : 
   */
	function getBookDetai1sByName($name) {
        $sql = "SELECT a.* FROM book a  WHERE a.book_title ='$name'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }
	/**
   * This function is used to get the get details of report article by book name.
   * Author   : Adarsh
   * Created  : 18/Des/2007
   * Modified : 
   */
	function getReportDetai1sByName($name) {
        $sql = "SELECT a.* FROM institution a  WHERE a.institution_name ='$name'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }
	function insetArticle($array,$objCategory)
	{
		/**
		   * This function is used to insert article into database.
		   * Author   : Adarsh
		   * Created  : 07/Des/2007
		   * Modified : 
	  	   */
		$articleId=$array['articleid'];
		if($articleId) 
		{
		 	$articleDet=$this->getAlbumDetails($articleId); 
		} 
		if($array['published']=='conference')
		{
			
			if($array['conference_exist'])
			{
				$rs=$this->getConferenceDetailsByName(trim($array['conference_exist']));
				$aid= $rs['id'];
 			}
			else
			{
				$country=$this->getCountryIdByname($array['conference_country']);
				$article=array("conference_name"=>$array['conference_name'],"conference_acronym"=>$array['conference_acronym'],"conference_town"=>$array['conference_town'],"conference_state"=>$array['conference_state'],
				"conference_country"=>$country['country_id'],"conference_day"=>$array['conference_day'],"conference_month"=>$array['conference_month'],"conference_year"=>$array['conference_year'],"conference_sponsors"=>$array['conference_sponsors'],
				"conference_publisher"=>$array['conference_publisher'],"conference_url"=>$array['conference_url'],"conference_isbn"=>$array['conference_isbn']);
				if($articleId)
				{
					$conference_id=$articleDet['conference_id']; 
					$this->db->update("conference", $article,"id='$conference_id'");
					$article_array['conference_id'] = $conference_id;
				}	
				else{
					$aid=$this->db->insert("conference", $article);
					$article_array['conference_id'] = $aid;
				}
			}
			
	    }
		if($array['published']=='journal')
		{
			if($array['conference_exist'])
			{
				$rs=$this->getJournalDetailsByName(trim($array['journal_exist']));
				$aid= $rs['id'];
 			}
			else
			{
				$article=array("journal_name"=>$array['journal_name'],"journal_acronym"=>$array['journal_acronym'],"journal_month"=>$array['journal_month'],"journal_year"=>$array['journal_year'],"journal_volume"=>$array['journal_volume'],"journal_number"=>$array['journal_number'],
				"journal_publisher"=>$array['journal_publisher'],"journal_url"=>$array['journal_url'],"journal_isbn"=>$array['journal_isbn']);
				if($articleId){
					$journal_id=$articleDet['journal_id']; 
					$this->db->update("journals", $article,"id='$journal_id'");
					$article_array['journal_id'] = $journal_id;
				}
				else{
					$aid=$this->db->insert("journals", $article);
					$article_array['journal_id'] = $aid;
				}
			}
		}
		if($array['published']=='book')
		{
			if($array['conference_exist'])
			{
				$rs=$this->getBookDetailsByName($array['book_exist']);
				$aid= $rs['id'];
			}
			else
			{
					$article=array("book_title"=>$array['book_title'],"book_author"=>$array['book_author'],"book_month"=>$array['book_month'],"book_year"=>$array['book_year'],
					"book_publisher"=>$array['book_publisher'],"book_url"=>$array['book_url'],"book_isbn"=>$array['book_isbn']);
					if($articleId){
						$book_id=$articleDet['book_id']; 
						$this->db->update("book", $article,"id='$book_id'");
						$article_array['book_id'] = $book_id;
					}
					else{
						$aid=$this->db->insert("book", $article);
						$article_array['book_id'] = $aid;
					}
			}
		}
		if($array['published']=='report')
	    {
			if($array['report_exist'])
			{
				$rs=$this->getReportDetai1sByName($array['report_exist']);
				$aid= $rs['id'];
 			}
			else
			{
				 $article=array("institution_name"=>$array['report_name']);
				 if($articleId){
					 $aid=$articleDet['report_id']; 
					$this->db->update("institution", $article,"id='$report_id'");
					}
				else{
						$aid=$this->db->insert("institution", $article);
						$article_array['report_id'] = $aid;
					}
			}
			 $article_array['report_id'] = $aid;		
			 $article_array['report_identifier'] = $array['report_identifier'];
			 $article_array['report_month'] =$array['report_month'];		
			 $article_array['report_year'] = $array['report_year'];
		}
		
		for($i=1;$i <= $array['no_of_author'];$i++)
		{
			if($array['author_name_exist'][$i])
			{
				$rs=$this->getAuthorDetailsByName(trim($array['author_name_exist'][$i]));
				$author_ids[]= $rs['id'];
 			}
			else
			{
				$author=array();
				$author["author"]        = $array["author"][$i];
				$author["affiliation"]   = $array["affiliation"][$i];
				$author["department"]    = $array["department"][$i];
				$author["institution"]   = $array["institution"][$i];
				$author["city"]          = $array["city"][$i];
				$author["state"]  		 = $array["state"][$i];
				$author["country"]  	 = $array["country"][$i];
				$author["email"]  		 = $array["email"][$i];
				$author["author_type"]   = $array["author_type"][$i];
				if(!$articleId){
					//print_r($author);
					$author_ids[]=$this->db->insert("author_details",$author);}
				else{
					$authorId=$articleDet['author_id']; 
					$author_ids[]=$this->db->update("author_details",$arr,"id='$authorId'");
					$author_ids[]=$authorId;
				}
			}	
		}
		//print_r($author_ids);
		//die();
		$author_id=implode(',',$author_ids);
		$article_array['album_name']		=	$array['paper_title'];
		$article_array['abstract']			=	$array['abstract'];
		$article_array['link_to_paper']		=	$array['link_to_paper'];
		$article_array['link_pointing_to_paper']	=	$array['link_pointing_to_paper'];
		$article_array['isbn']		=	$array['isbn'];
		$article_array['doi']		=	$array['doi'];
		$category= $objCategory->getCategoryId($array['cat_id']);
		$article_array['cat_id']	=	$category[0]->category_id;;
		$article_array['author_email']	=	$array['author_email'];
		$article_array['published']	=	$array['published'];
		
		if($array['paper_file'])
		{
			$fileext2=$this->file_extension($array['paper_file']);
			$article_array['file_type'] 	=	$fileext2;
		}
		
		if(!$articleId){
			$article_array['user_id']		  =	 $array['user_id'];
			$article_array['post_date']  	  =	 date("Y-m-d G:i:s");;
			$article_array['active']		  =	 'y';
			$article_array['author_id']	      =	 $author_id;
			$id=$this->createAlbum($article_array);
		}
		else{
			$this->updateAlbum($article_array,$id,$memberID);
		}
		if($array['paper_file'])
		{
			
			$dir3=SITE_PATH."/modules/album/temp/";
			$tempdir=$dir3.date("Ymd:His").".".$fileext2;
			$dir2=SITE_PATH."/modules/album/article/";
			copy($array['paper_file'],$dir2.$id.".".strtolower($this->file_extension($array['paper_file'])));
			chmod($dir2.$id.".".strtolower($this->file_extension($array['paper_file'])), 0777);
			unlink($tempdir);
		}
		return $id;
	}
	/**
    * Fetching Country ID from Country name
  	* Author   : adarsh
  	* Created  : 11/Desc/2007	
  	* Modified : 
  	*/
	function getCountryIdByname($name)
	{
		$sql = "Select country_id from country_master where country_name='$name'";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs[0];
	}
	/**
    * get the details of a article
  	* Author   : adarsh
  	* Created  : 17/Desc/2007	
  	* Modified : 
  	*/
	function getArticleDetails($id)
	{
		if($id>0)
		{
			list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
			$sql = "SELECT a.*,$qry FROM album a $join_qry WHERE a.id='$id'";
			$rs = $this->db->get_row($sql, ARRAY_A);
			if($rs['conference_id']){
				$rs2=$this->getConferenceDetails($rs['conference_id'],false);
				$rs['conference_id']=$rs2;
			}
			else if($rs['journal_id'])
			{
				$rs2=$this->getJournalDetails1($rs['journal_id'],false);
				$rs['journal_id']=$rs2;
			}
			else if($rs['book_id'])
			{
				$rs2=$this->getBookDetails($rs['book_id'],false);
				$rs['book_id']=$rs2;
			}
			else if($rs['report_id'])
			{
				$rs2=$this->getInstitutionDetails($rs['report_id'],false);
				$rs['report_id']=$rs2;
			}
			if($rs['author_id'])
			{
				$authorId=explode(',',$rs['author_id']);
				for($i=0;$i< count($authorId);$i++)
				{
					$rs3=$this->getAuthorDetails($authorId[$i],false);
					$author[$i]=$rs3;
				}
				$rs['author_id']=$author;
			}
				
		return $rs;
		
		}
	}
	/**
   * This function is check the conference article details using tag fileds .
   * Author   : Adarsh
   * Created  :28/Nov/2007
   * Modified : 
   */			
	function getReportList($val) {
	    $name=trim($val);
        $sql="SELECT id FROM institution where institution_name='$name'";
		$sql .=" GROUP BY id";
		$rs = $this->db->get_results($sql);
		if(count($rs)==0){
			return true;
		}
		else
		{
			$array=array();
			for($i=0;$i< count($rs);$i++)
			{
				 $array[]=$rs[$i]->id;
			}
			return $array;
		}	
    }
	/**
   * This function is used to calculate the review of an article .
   * Author   : Adarsh
   * Created  :28/Des/2007
   * Modified : 
   */			
	
	function Fx($x,$kx)
	{
		$rs=$x*$kx;
		return $rs;
	}
	/**
   * This function is used to calculate the review of an article posted by all users .
   * Author   : Adarsh
   * Created  :28/Des/2007
   * Modified : 
   */			
	function getArticleReview($id)
	{
		$sql="SELECT id FROM article_review  WHERE article_id='$id' GROUP BY id";
		$rs = $this->db->get_results($sql, ARRAY_A);
		$numReview= count($rs);
		if(count($rs))
		{
			$article=$this->getArticleDetails($id);
			if($article['conference_id']['conference_quality_rating'])
			{
				$KQR=$article['conference_id']['conference_quality_rating'];
			}
			else if($article['journal_id']['journal_quality_rating'])
			{
				$KQR=$article['journal_id']['journal_quality_rating'];
			}
			else
			$KQR=1;
			
			$constant=$this->getVQR();
			if($article['conference_id'])
			{
				$VQR=$constant['conference_vqr'];
			}
			else if($article['journal_id'])
			{
				$VQR=$constant['journal_vqr'];
			}
			else if($article['book_id'])
			{
				$VQR=$constant['book_vqr'];
			}
			else if($article['report_id'])
			{
				$VQR=$constant['report_vqr'];
			}
			
			for($j=0;$j< count($rs);$j++)
			{
				$review_id=$rs[$j]['id'];
				$sql="SELECT b.value,b.qid,c.value AS qvalue,c.topic_id,a.pos  FROM  article_review_options a INNER JOIN review_option b ON  a.option_id=b.id INNER JOIN review_questions c ON  c.id=b.qid  WHERE a.review_id='$review_id' ";
				$rs1 = $this->db->get_results($sql,ARRAY_A);
				
				$curtotal="";
				$total="";
				$curSum=0;
				$var2="";
				for($i=0;$i< count($rs1);$i++)
				{
					$currentID=$rs1[$i]['topic_id'];
					if($rs1[$i]['pos']==0){
						//$sum1=$rs1[$i]['value']*$rs1[$i]['qvalue'];
						$sum1=$this->Fx($rs1[$i]['value'],$rs1[$i]['qvalue']);
						$curSum= $curSum+$sum1;
					}
					else{
						$currentID=$rs1[$i]['topic_id'];
						$sum= $rs1[$i]['value']*$rs1[$i]['qvalue'];
						$curtotal= $curtotal+$sum;
					 }
				}
			$var+= bcmul($curSum,$curtotal,10);
			}	
			$SI=($var*(1/$numReview))*($KQR*$VQR);
			$var2=$SI/100*20;
			return round($var2);
		}
		else
		return 0;	
	}
	/**
   * This function is used to get the value of constant vqr in article review calculation .
   * Author   : Adarsh
   * Created  :02/Jan/2007
   * Modified : 
   */			
	function getVQR() {
        $sql = "SELECT * FROM vqr";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
    }		
	/**
   * This function find the overal rateing number of an article .
   * Author   : Adarsh
   * Created  :07/Jan/2008
   * Modified : 
   */			
	function getRateingNumber($id)
	{
		$sql="SELECT  a.value, COUNT(a.value)FROM  review_option a left join  article_review_options b on a.id=b.option_id GROUP BY b.option_id HAVING COUNT( * ) >=ALL (SELECT COUNT( * ) FROM article_review_options c left join article_review d on d.id=c.review_id where d.article_id='$id' GROUP BY c.option_id )";
		$rs  = $this->db->get_row($sql, ARRAY_A);
		//print_r($rs);
		echo $rs[0];
	}	
	/**
   * This function add/edit the   conjunctions .
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */				
	function addEditConjunctions(&$req) {
        extract($req);
        if(!trim($conjunction)) {
            $message = "conjunction is required";
        } 
		 else {
		 	$rs=$this->getConjunctionByName(trim($conjunction),$req['id']);
			if(count($rs)==0)
			{
				if($id) {
					$array = array("conjunction"=>trim($conjunction));
					$this->db->update("conjunctions", $array, "id='$id'");
				} else {
					$array = array("conjunction"=>trim($conjunction));
					$this->db->insert("conjunctions", $array);
					$id = $this->db->insert_id;
				}
				return true;
			}
			else{
				 	$message = "conjunction already exits!";
					return $message;
				}
        }
        return $message;
    }	
	
  /**
   * This function find a conjunction .
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */					
	function getConjunctionByName($val,$id='') {
        $sql = "SELECT * FROM conjunctions WHERE conjunction='$val'";
		if($id){
			$sql.=" and id !='$id' ";
		}
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
	/**
   * This function used to get all the conjunctions.
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */			
	function getConjunctionList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
			$sql		= "SELECT * FROM conjunctions";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	/**
   * This function get the conjunction details  by id.
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */					
	function getConjunctionById($id) {
        $sql = "SELECT * FROM conjunctions WHERE id='$id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
  /**
   * This function find a conjunction .
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */	
	function conjunctionDelete($id) {
        $this->db->query("DELETE FROM conjunctions WHERE id='$id'");
    }
	/**
   * This function is used to get the conjunctions.
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */	
	function getConjunctions() {
        $sql = "SELECT * FROM conjunctions";
     	$rs = $this->db->get_results($sql, ARRAY_A);	
        return $rs;
    }
	/**
   * This function is used to remove the conjunction from a string value.
   * Author   : Adarsh
   * Created  :15/Jan/2008
   * Modified : 
   */	
	function removeConjunction($str)
	{
		$str_array=explode(" ",$str);
		$rs=$this->getConjunctions();
		for($i=0;$i< count($str_array);$i++)
		{
			for($j=0;$j< count($rs);$j++)
			{
				if (in_array(trim($str_array[$i]), $rs[$j])) {
				unset($str_array[$i]);
				}
			}
		}
		$str=implode(" ",$str_array);
		return $str;
	}
	/**
   * This function is used search for both article and dicussion topics.
   * Author   : Adarsh
   * Created  :17/Jan/2008
   * Modified : 
   */	
	function get_site_searchlist(&$req,$start=0,$end=0)
	 {
        $stxt=$req['search_value'];
		
		$rs=array();
		$res=array();
		$array=array();
		$arr=array();
		unset($rs);
		$sql='';
	    list($qry,$table_id,$join_qry)=$this->generateQry('album','d','a');
		
		$sql= "SELECT a.*,$qry FROM album a $join_qry  ";		
		if ($stxt!='')
		{
			 $sql=$sql. " where (a.album_name like '%$stxt%' )";
		}
		$rs = $this->db->get_results($sql, ARRAY_A);
		
		$qry=  "SELECT a.*,c.category_name FROM forum_topic a INNER JOIN forum_category b on a.id=b.forum_topic_id INNER JOIN master_category c on  b.cat_id= c.category_id and  a.topic_name like '%$stxt%'";	
		
		$res = $this->db->get_results($qry, ARRAY_A);
		
		
		if(count($rs))
		{
			foreach($rs as $key=>$value)
			{
				////get type detals
				if($value['conference_id']){
					$rs2=$this->getConferenceDetails($value['conference_id'],false);
					$rs[$key]['conference_id']=$rs2;
				}
				else if($value['journal_id'])
				{
					$rs2=$this->getJournalDetails1($value['journal_id'],false);
					$rs[$key]['journal_id']=$rs2;
				}
				else if($value['book_id'])
				{
					$rs2=$this->getBookDetails($value['book_id'],false);
					$rs[$key]['book_id']=$rs2;
				}
				else if($value['report_id'])
				{
					$rs2=$this->getInstitutionDetails($value['report_id'],false);
					$rs[$key]['report_id']=$rs2;
				}
				if($value['author_id'])
				{
					$authorId=explode(',',$value['author_id']);
					for($i=0;$i< count($authorId);$i++)
					{
						$rs3=$this->getAuthorDetails($authorId[$i],false);
						$author[$i]=$rs3;
					}
					$rs[$key]['author_id']=$author;
				}
			}
			////get type detals end .
		}
		////get add topic details start
		$next= count($rs);
		for($j=0;$j< count($res);$j++)
		{
			$rs[$next]=$res[$j];
			$next++;
		}		
		for($i=$start;$i<$end;$i++)
		{
			if($rs[$i])
			{
				$array[$i]=$rs[$i];
			}
		}
		$count=count($array);
		$arr=0;
		$arr=array($count,$array);
        return $arr;
    }
    
    /* 
    
    *Created:Afsal
    *Date:Mar-24-2008
    *Reset Default image
    
    */
    function resetDefaultThumb($album_id,$default_id,$type){
    	
    	if($album_id > 0 && $default_id > 0){
    	
    		$rs = $this->getAlbumDet($album_id);

    		
    		if($type == "video"){
    			$media_id = $rs["default_vdo"];
    		}
    		elseif($type == "photo"){
    			$media_id = $rs["default_img"];
    		}
    		
    		if($type == "video"){
    			
    			$SQL = "SELECT file_id FROM album_video  JOIN album_files ON album_video.id = album_files.file_id 
	    				AND album_files.album_id = $album_id AND type='$type' AND album_video.id <> $default_id";
    			
    		}elseif($type == "photo"){
    			
    			$SQL = "SELECT file_id FROM album_photos  JOIN album_files ON album_photos.id = album_files.file_id 
	    				AND album_files.album_id = $album_id AND type='$type' AND album_photos.id <> $default_id";
    			
    		}

	    		if($media_id == $default_id){

	    			$rs = $this->db->get_row($SQL,ARRAY_A);
	    			
	    			if(count($rs) >0 && $rs["file_id"] >0){
	    				
	    				return $rs["file_id"];

	    				
	    			}else{
	    				
	    				return 0;
	    			}
	    			
	    		}

    		
    	}else{
    		return 0;
    	}
    	
    }
    /*
    Created:Afsal
    Date:26-Mar-2008
    Check the photos and videos avialabe under this album
    */
    function unsetDefaultThumb($type,$album_id){
    	
    	if($type !="" && $album_id >0){
    		
    		if($type == "video"){
    			
    		
	    	$SQL = "SELECT file_id FROM album_video  JOIN album_files ON album_video.id = album_files.file_id 
		    		AND album_files.album_id = $album_id AND type='$type'";
	    	
    		}elseif($type == "photo"){
    			
    		$SQL = "SELECT file_id FROM album_photos  JOIN album_files ON album_photos.id = album_files.file_id 
	    			AND album_files.album_id = $album_id AND type='$type'";	
    		
    		}
    		
    		$rs  = $this->db->get_results($SQL, ARRAY_A);
    		
    		if(count($rs) > 0){
    			return "1";
    		}else{
    			return "0";
    		}
    	}
    }
	function noLocationInfo($flyer_id,$prop_id)
	{
			if($flyerInfo['location_city']=="" || $flyerInfo['location_state']=="" || $flyerInfo['location_country']=="" || $flyerInfo['location_zip']=="" )
			{
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id&red=1"));
			}
	}
	 /*
    Created:Vinoy
    Date:31-Mar-2008
    get  the id and details if the user who are booking the property
	  */
	  function getBookingUserDetails($pageNo, $limit = 10, $params='',$output=OBJECT,$orderBy,$owner_id)
	  { 
		// $SQL = "SELECT * FROM member_master  JOIN album_booking ON member_master.id = album_booking .user_id AND album_booking.owner_id =$owner_id";	
		   $SQL =	"SELECT t1.first_name as name,
		            		t2.id as bookid,t2.album_id as albumid,t2.date_booked as bookdate,t2.user_id as buyer_id,t2.start_date as startdate,t2.end_date as enddate,t2.totalamount as tot_amt,t2.amountpaid as bookamt,
							t3.flyer_name as albumname,t3.flyer_id as flyer_id
							FROM  member_master AS t1
							LEFT JOIN album_booking AS t2 ON t1.id=t2.user_id 
							LEFT JOIN flyer_data_basic AS t3 ON t2.album_id=t3.album_id
							 WHERE (t2.owner_id=$owner_id AND t2.album_id=t3.album_id)
							";
		// $rs  = $this->db->get_results($SQL, ARRAY_A);
		  $rs = $this->db->get_results_pagewise($SQL, $pageNo, $limit, $params, $output,$orderBy);
		  return $rs;
	  }
	   /*
    Created:Vinoy
    Date:01-April-2008
    get  the id and details of the user and price details for bidding  properties
	  */
	  
	  function getAlbumBidDttails($uid)
	  {
	   $currdate=date("Y-m-d");
	   $SQL="SELECT t1.album_id as albumid,t1.flyer_name as album_name,
	  				t2.start_bid as sbid,t2.auction_ends as expbiddate,
					t3.user_id as buid,t3.bid_amount as bamt,
					t4.first_name as name,t4.mem_type as mtype
					FROM flyer_data_basic AS t1
        	   LEFT JOIN property_pricing AS t2 ON t1.album_id = t2.album_id 
        	   LEFT JOIN property_bid AS t3 ON t2.id = t3.pricing_id 
			   LEFT JOIN member_master AS t4 ON t3.user_id = t4.id 
  			   WHERE (t1.user_id = '$uid' AND t2.id=t3.pricing_id AND  t2.auction_ends >= $currdate )";
		   
	     
			   $rs  = $this->db->get_results($SQL, ARRAY_A);
		 
		  return $rs;
	  
	  }
	   /*
    Created:Vinoy
    Date:01-April-2008
    get  the id and details of the user price details who are bidding the property
	  */
	  function bidHighestAmt($uid)
	  {
	  	 $SQL="SELECT t1.pricing_id as priceid,t1.bid_amount as bidamt,
	  		 t2.album_id AS albumid,
			 t3.flyer_name as albumname
	  		 FROM property_bid AS t1
	 		 LEFT JOIN property_pricing AS t2 ON t1.pricing_id = t2.id 
			 LEFT JOIN flyer_data_basic AS t3 ON t2.album_id=t3.album_id
	   	  	 WHERE (t1.user_id = '$uid')";
	
			   $rs  = $this->db->get_results($SQL, ARRAY_A);
				 $i=0;
			  foreach ($rs as $res){
				 $priceid= $res["priceid"];
				 $SQL1="SELECT MAX(bid_amount)as maxbid from property_bid where pricing_id='$priceid'";
				 $rs1  = $this->db->get_row($SQL1, ARRAY_A);
				
				 $rs[$i][maxbidprice]=$rs1["maxbid"];
				 $i++;
			  }
		 
		  return $rs;
	  
	  }
	  
	  function getEmailid($propid)
	  {
	   $SQL="SELECT t1.assigned_role as role,
	   t2.email
	   FROM propertyassign_relation AS t1
	   LEFT JOIN member_master AS t2 ON t1.assigned_user_id=t2.id
	   WHERE (t1.property_id=$propid)";
	  $rs  = $this->db->get_results($SQL, ARRAY_A);
	  return $rs;
	  
	  }
	  
	  /*
    Created:Vinoy
    Date:01-April-2008
    get  the id and details of the user price details who are booking the property
	  */
	  //($pageNo, $limit = 10, $params='', $output=OBJECT,$uid)
	  function myBookingProperties($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$uid)
	  {
	   $SQL =	"SELECT
		            		t2.album_id as albumid,t2.date_booked as bookdate,t2.amountpaid as bookamt,t2.totalamount as totalamt,t2.id as bookingid,t2.owner_id as sellerid,t2.start_date as sdate,t2.end_date as edate,
							t3.flyer_name as albumname,t3.flyer_id as flyerid
							FROM album_booking AS t2
							LEFT JOIN flyer_data_basic AS t3 ON t2.album_id=t3.album_id
							 WHERE (t2.user_id=$uid AND t2.album_id=t3.album_id)";
	 
		// $rs  = $this->db->get_results_pagewise($SQL, ARRAY_A);
		 $rs = $this->db->get_results_pagewise($SQL, $pageNo, $limit, $params, $output,$orderBy);
		  return $rs;
	  
	  }
	    /*
    Created:Vinoy
    Date:01-April-2008
    get the feedback of sellers
	  */
	
	  
	function getSellerCommentDetails($sellerid)
	{
			 $SQL =	"SELECT t1.type as type,t1.comment as comment,t1.postdate as postded_date,
		            		t2.first_name as fname,t2.last_name as lname
							FROM  media_comments AS t1
							LEFT JOIN member_master AS t2 ON t1.user_id=t2.id
							WHERE (t1.file_id='$sellerid' AND t1.type='seller')";
		 $rs  = $this->db->get_results($SQL, ARRAY_A);
		 
		  return $rs;
	
	
	}
	 /*
    Created:Vinoy
    Date:10-April-2008
    get the feedback details of  of Property
	  */
	
	function getPropertyCommentDetails($bookid,$pageNo=0, $limit = 10, $params='',$output=ARRAY_A,$orderBy)
	{
	
		     $i=0;
			 foreach($bookid as $bkid)
			 {
				 $bkarray[$i]=$bkid[id];
				 $i++;
			 }
		 $barray =implode(",",$bkarray);
		 $SQL    =	"SELECT t1.type as type,t1.comment as comment,t1.postdate as postded_date,
					 t2.first_name as fname,t2.last_name as lname
					 FROM  media_comments AS t1
					 LEFT JOIN member_master AS t2 ON t1.user_id=t2.id
				 	 WHERE (t1.diff_userid in($barray) AND t1.type='property')";
								
		  $rs 	 = 	$this->db->get_results_pagewise_ajax($SQL, $pageNo, $limit, $params, $output,$orderBy);
		  return $rs;
	}
	
	function getCommentDetails($bmid,$type,$pageNo=0, $limit = 10, $params='',$output=ARRAY_A,$orderBy)
	{
	 $SQL    =	"SELECT t1.type as type,t1.comment as comment,t1.postdate as postded_date,
					 t2.first_name as fname,t2.last_name as lname
					 FROM  media_comments AS t1
					 LEFT JOIN member_master AS t2 ON t1.user_id=t2.id
				 	 WHERE (t1.file_id ='$bmid' AND t1.type='$type')";
								
		  $rs 	 = 	$this->db->get_results_pagewise($SQL, $pageNo, $limit, $params, $output,$orderBy);
		  return $rs;
	}
	
	 /*
    Created:Vinoy
    Date:15-April-2008
    get the rating of a Broker
	  */
	function getBrokerRate($bid,$type)
	{
	   $SQL =	"select sum(mark)/count(mark) as rate, count(mark) as cnt
	   from  media_rating where type ='$type'  AND file_id='$bid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	
	}
	
	
	 /*
    Created:Vinoy
    Date:15-April-2008
    get the rating of a Property
	  */
	function getPropertyRate($propid)
	{
	 //  $SQL ="select sum(m.mark)/count( m.mark ) as rate, count( m.mark ) as cnt
	//   from album_booking as b INNER JOIN media_rating as m ON b.id = m.diff_userid where m.type = 'property' AND b.album_id='$propid'";
	  $SQL =	"select sum(mark)/count(mark) as rate, count(mark) as cnt
	   from  media_rating where type ='property'  AND file_id='$propid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	
	}
	 /*
    Created:Vinoy
    Date:15-April-2008
    get the total earnings of a Property
	  */
	function getPropertyEarnings($propid)
	{
	  //echo $SQL="select sum(amountpaid) from album_booking where start_date (CURDATE(),INTERVAL 360 DAY) AND album_id='$propid'";
	   $SQL="select sum(amountpaid) as totamt from album_booking where album_id='$propid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	 /*
    Created:Vinoy
    Date:15-April-2008
    get the Yearly earnings of a Property
	  */
	function getPropertyYearlyEarnings($propid)
	{
	   $SQL="select sum(amountpaid) as yearamt from album_booking where  DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= date_booked AND album_id='$propid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	  
	 /*
   	 	Created:Vipin
    	Date:15-April-2008
   		get  the property details of a property
	  */
	  function getBookingPropertyDetails($pageNo, $limit = 10, $params='',$output=OBJECT,$orderBy,$album_id)
	  { 
				  $SQL =	"SELECT t1.first_name as name,
		            		t2.album_id as albumid,t2.date_booked as bookdate,t2.amountpaid as bookamt,t2.totalamount as totalamt,t2.id as bookingid,t2.owner_id as sellerid,t2.start_date as sdate,t2.end_date as edate,
							t3.flyer_name as albumname
							FROM  member_master AS t1
							LEFT JOIN album_booking AS t2 ON t1.id=t2.user_id 
							LEFT JOIN flyer_data_basic AS t3 ON t2.album_id=t3.album_id
							WHERE (t2.album_id=$album_id )
							";
							
		// $rs  = $this->db->get_results($SQL, ARRAY_A);
		  $rs = $this->db->get_results_pagewise($SQL, $pageNo, $limit, $params, $output,$orderBy);
		  return $rs;
	  }
	   /*
   	 	Created:vinoy
    	Date:25-April-2008
   		get  all ids  of a property
	  */
	  function getalbumid()
	  {
	      $SQL =	"SELECT album_id from flyer_data_basic";
		  $rs  = $this->db->get_results($SQL, ARRAY_A);
		  return $rs;
	  }
	  function getRankofproperty($propid)
	  {
	     $SQL =	"SELECT score,rank from flyer_data_basic where album_id='$propid'";
		  $rs  = $this->db->get_row($SQL, ARRAY_A);
		  return $rs;
	  
	  }
	  
	/**
	 * This function generates a XML configuration for Flash Uploader
	 * Author   : Retheesh
	 * Created  : 13/May/2008
	 * Modified : 13/May/2008 By Retheesh
	 */
	  function getUploadConfXML($upd_url,$red_url,$redirect=1,$multiple=1)
	  {
	  	ob_start();
	  	
		header("Content-Type:text/xml");
		$_xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .= "<uploadconf updurl='{$upd_url}' redurl='{$red_url}'
		 		dored='$redirect' domulti='$multiple' msg=''>";
		$rs = $this->getUploadTypes();
		$type_len = sizeof($rs);
		for ($i=0;$i<$type_len;$i++)
		{
			$_xml .= "<filetypes desc='{$rs[$i]->desc}' extn='{$rs[$i]->extn}' id='{$rs[$i]->id}'/>";
		}
		$_xml .= "</uploadconf>";
		ob_get_clean();
		return $_xml;
	  }
	  
	/**
	 * This function retrieves upload types for Flash Uploader
	 * Author   : Retheesh
	 * Created  : 13/May/2008
	 * Modified : 13/May/2008 By Retheesh
	 */
	function getUploadTypes()
	{
		$sql = "select * from album_upload_types";
		$rs  = $this->db->get_results($sql);
		return $rs;
	}
	
	/**
	 * This function inserts records to a temporary Upload Table
	 * Author   : Retheesh
	 * Created  : 14/May/2008
	 * Modified : 14/May/2008 By Retheesh
	 */
	function insertTempUpload()
	{
		$arr = $this->getArrData();
		$this->db->insert("album_temp_upload",$arr);
		return $this->db->insert_id;
	}
	
	/**
	 * This function retrieves a list of files after multiple upload
	 * Author   : Retheesh
	 * Created  : 14/May/2008
	 * Modified : 14/May/2008 By Retheesh
	 */
	function getAfterUploadList ($user_id=0,$sess_id=0,$type='IMAGE')
	{
		$sql = "select * from album_temp_upload where user_id=$user_id and type='$type'";
		if ($sess_id>0)
		{
			$sql .= " and sess_id='$sess_id'";
		}
		
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	/**
	 * This function insert a record in Photos Table
	 * Author   : Retheesh
	 * Created  : 15/May/2008
	 * Modified : 15/May/2008 By Retheesh
	 */
	function insertPhotoDetails()
	{
		$arr = $this->getArrData();
		$arr=$this->splitFields($arr,'album_photos');
		$this->db->insert("album_photos",$arr[0]);
		$id = $this->db->insert_id;
		$arr[1]["table_key"]=$id;
		$arr[2]["user_id"]=$id;
		if ($arr[1]["table_id"]>0)
		{
			$this->db->insert("custom_fields_list", $arr[1]);
		}
		return $id;
	}
	
	/**
	 * This function removes a record from Temporary Upload Table
	 * Author   : Retheesh
	 * Created  : 15/May/2008
	 * Modified : 15/May/2008 By Retheesh
	 */
	function removeTemp($id)
	{
		$sql = "delete from album_temp_upload where id='$id'";
		$this->db->query($sql);
		return true;		
	}
	/**
	 * This function insert a record in Videos Table
	 * Author   : Retheesh
	 * Created  : 15/May/2008
	 * Modified : 15/May/2008 By Retheesh
	 */
	function insertVideo()
	{
		$arr = $this->getArrData();
		$arr=$this->splitFields($arr,'album_video');
		$this->db->insert("album_video",$arr[0]);
		$id = $this->db->insert_id;
		$arr[1]["table_key"]=$id;
		$arr[2]["user_id"]=$id;
		if ($arr[1]["table_id"]>0)
		{
			$this->db->insert("custom_fields_list", $arr[1]);
		}
		return $id;
	}
	
	function getAlbumCount($id)
	{
		$sql="SELECT COUNT(album_id) AS cnt1 FROM album_files where album_id=".$id;
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs[0]['cnt1'];
	}
	
	function getPhotoAlbums ($uid,$pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$typ='') 
	{
	
		
		$sql="SELECT  DATE_FORMAT(a.post_date,GET_FORMAT(DATE,'USA')) as date,a.id,a.album_name,a.user_id,a.is_locked,a.img_extension,count(b.id) as cnt,a.albumcover_id FROM `album` a left join";
		$sql=$sql." `album_files` b on (b.album_id=a.id and b.type='$type') where a.user_id!='' ";
		
		if ($stxt!='')
		{
			$sql=$sql. " and (a.album_name like '%$stxt%' or a.album_description like '%$stxt%')";
		}
		
		
		$sql=$sql. " group by a.id ";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	/**
	 * This function generates an xml for Video grid player
	 * Author   : Retheesh
	 * Created  : 18/Jun/2008
	 * Modified : 18/Jun/2008 By Retheesh
	 */
	function generateGridXML($cat_id='',$state='')
	{
		$states=$this->GetAllStates('840');
		
		if($state){
			$str_link='subLink="no"';
			$toolTip2='';
		}
		else{
			$str_link='';
			$toolTip2="Click here to see the videos under this state";
		}
		
		ob_start();
		header("Content-Type:text/xml");
		$_xml .="<xml>\r\n";
		$_xml .= '<thumb label="" cmssrc="advertise_here.php"	 rows="5" cols="10" '.$str_link.' toolTip1="Click here to watch this Video" toolTip2="'.$toolTip2.'" toolTip3="advertise your business with us">';
			if($cat_id)
			{
				for($i=0; $i < count($states); $i++)
				{	
					$value=$this->getVideosList($cat_id,$states[$i]['id']);
					if(count($value)){
						$url=makeLink(array("mod"=>"album", "pg"=>"video","url_encode"=>1 ),"act=details_prf&video_id=".$value[0]['id']."");
						$rurl=makeLink(array("mod"=>"album", "pg"=>"video","url_encode"=>1 ),"act=video_grid_xml&state=".$value[0]['state']."");
						$_xml .= '<thumb label="'.$states[$i]['name'].'" thumbsrc="'.SITE_URL .'/modules/album/video/'.$value[0]['id'].'_small.flv" vdosrc="'.htmlentities($url).'" id="'.$value[0]['id'].'" rurl="'.htmlentities($rurl).'"  />';
					}
					else{
						$_xml .= '<thumb label="'.$states[$i]['name'].'" thumbsrc="" vdosrc="" id="" rurl=""  />';
					}
				}	
			}	
			else
			{
				$video_list=$this->getVideosList($cat_id,$state);
				foreach($video_list as $key => $value){
					$url=makeLink(array("mod"=>"album", "pg"=>"video","url_encode"=>1 ),"act=details_prf&video_id=".$value['id']."");
					$rurl=makeLink(array("mod"=>"album", "pg"=>"video","url_encode"=>1 ),"act=video_grid_xml&state=".$value['state']."");
					$_xml .= '<thumb label="'.$states[$i]['name'].'" thumbsrc="'.SITE_URL .'/modules/album/video/'.$value['id'].'_small.flv" vdosrc="'.htmlentities($url).'" id="'.$value['id'].'" rurl="'.htmlentities($rurl).'" />';
				}
			}	
		$_xml .= '</thumb>';
		$_xml .= '</xml>';
		ob_end_clean();
		return $_xml;
	}
	
	/**
	 * This function is used to get the video list in particular category
	 * Author   : Adrash
	 * Created  : 19/Jun/2008
	 * 
	 */
	 
	function getVideosList($cat_id='',$state='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album_video','cs','av');
		
		if($state)
		{
			list($qry_cs)=$this->getCustomQry('album_video','state',$state,$criteria,'av','cs');	
		}
		$sql="select av.id, $qry from album_video av  $join_qry  left join member_master b on av.user_id=b.id ";
		
		if($cat_id && $state ){
		
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
			$sql.=" and  av.cat_id='$cat_id'";
		}
		else if($qry_cs){
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}	
		
		if($cat_id){
			$sql.="and av.home_appearance='Y' ";
		}
		
		$sql.=" and b.mem_type=0 and av.active='Y'   ";
		
		if($cat_id='' && $state=='' ){
			$sql.=' limit 50 ';
		}
		
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	function getSateName($id)
	{
		$sql="SELECT name FROM state_code where id='$id'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs['name'];
	}
	function GetAllStates($country_id)
	{
		$qry		=	"SELECT * FROM state_code WHERE country_id='$country_id' ORDER BY `name` ASC";
		$rs 		= 	$this->db->get_results($qry,ARRAY_A);
		return $rs;
	}
	
	function updateMediaDetByState($state_id)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album_video','cs','av');
		
		list($qry_cs)=$this->getCustomQry('album_video','state',$state_id,'','av','cs');	
		
		$sql="select av.id  from album_video av  $join_qry ";
		
		if($qry_cs){
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}	
		$sql.=" and av.home_appearance='Y'";
		$rs  = 	$this->db->get_results($sql,ARRAY_A);
		$array=array("home_appearance"=>'N');
		
		foreach($rs as $key=>$val){
			$id=$val['id'];
			$this->db->update("album_video", $array, "id='$id'");
		}
		return true;		
		
	}
	
}	
?>
