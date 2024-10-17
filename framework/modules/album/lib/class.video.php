<?php
class Video extends FrameWork
{
    /*
    constructor
    */
    function Video()
    {
        $this->FrameWork();
    }

    function setArrData($szArrData)
    {
        $this->arrData = $szArrData;
    }

    function getArrData()
    {
        return $this->arrData;
    }

    function setErr($szError)
    {
        $this->err .= "$szError";
    }


    function getErr()
    {
        return $this->err;
    }
    function getUserdetails($id)
    {
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','m');
		$sql="SELECT m.*,c.country_name,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on 
		      m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) $join_qry WHERE m.id='$id'";
		$RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
    }
    function videoList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$shop=0,$state='',$state_search='')
    {
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
        
      	list($qry,$table_id,$join_qry)=$this->generateQry('album_video','cs','a');
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "d.username,d.screen_name";
		}
		else 
		{
			$member_search_fields = "d.username";
		}	
		
		
		
		$sql="SELECT a.*,$fld as $fldnm,$member_search_fields,$qry FROM
		((`album_video` a left join `album_files` b on (a.id=b.file_id and b.type='video'))
		 inner join `member_master` d on a.user_id=d.id) left join
		`$tbl` e on (a.id=e.file_id and e.type='video') $join_qry"; 
		
		if($catid>0)
		{
		### this part is added for showing private videos to friends under conditions.
		### Modified on 4 Jan 2008.
		### Modified By Jipson Thomas.
			if($this->config['show_private']!='Y'){
				$sql=$sql." where (a.cat_id=$catid) and (a.privacy='public')";
			}else{
				$sql=$sql." where (a.cat_id=$catid)";
			}
		#####End############	
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
				### this part is added for showing private videos to friends under conditions.
				### Modified on 4 Jan 2008.
				### Modified By Jipson Thomas.
					if($this->config['show_private']!='Y'){
						$sql=$sql." and (a.privacy='public')";
					}
				#####End############
					
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
				### this part is added for showing private videos to friends under conditions.
				### Modified on 4 Jan 2008.
				### Modified By Jipson Thomas.
					if($this->config['show_private']!='Y'){
						$sql=$sql." where (a.privacy='public')";
					}
				#####End############
					
				}
			}
			
		}
		
		if ($shop == 1) {
			$sql=$sql." and a.price > 0 ";
		}
		if($state!=''){
			list($qry_cs)=$this->getCustomQry('album_video','state',$state,$criteria,'a','cs');
			$sql.=" and $qry_cs ";
		}
		if($state_search !=''){
			$sql.=" and (a.title like '%$state_search%' or  a.description like '%$state_search%') ";
		}
        $sql=$sql." group by a.id";
		
        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
//print_r($rs);
        foreach ($rs as $value)
        {
            for($i=0;$i<sizeof($value);$i++)
            {
                if($value[$i]->id>0)
                {
                    if($this->config["new_album_functions"]==1){
						$rating=$album->GetRatingNew('album_video',$value[$i]->id,'video');
						$favcnt =$album->GetFavCntNew('album_video',$value[$i]->id,'video');
					}else{
						$rating=$this->getRating('video',$value[$i]->id);
						$favcnt =$this->getFavCnt('video',$value[$i]->id);
					}
                    $rs[0][$i]->rate=$rating["rate"];
                    $rs[0][$i]->cnt=$rating["cnt"];
                    $cmcnt =$this->getCommentsCnt('video',$value[$i]->id);
                    $rs[0][$i]->cmtcnt=$cmcnt;
                    $rs[0][$i]->favrcnt=$favcnt;
                    $udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
                }
            }
        }
        return $rs;
    }
    function getVideoDetails($vid)
    {
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "mm.screen_name,mm.username,mm.image,mm.id as user_id";
		}
		else 
		{
			$member_search_fields = "mm.username,mm.image,mm.id as user_id";
		}	
	
		list($qry,$table_id,$join_qry)=$this->generateQry('album_video','cs','av');
		
       $sql="SELECT a.album_id,av.*, $member_search_fields ,$qry ,date_format(av.postdate, '%W %M %d %Y' )as pdate
			 FROM (`album_video` av left join `album_files` a on av.id = a.file_id 
			 AND a.type='video') $join_qry inner join `member_master` mm on av.user_id = mm.id  
			  where av.`id` = '$vid'";
		
        $RS = $this->db->get_row($sql, ARRAY_A);
        return $RS;
    }
    
   
    function commentList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$vid)
    {
	
	if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "`member_master`.screen_name, `member_master`.image ,`member_master`.username,`member_master`.mem_type";
		}
		else 
		{
			$member_search_fields = "`member_master`.image ,`member_master`.username";
		}	
	 
	
	
	
        $sql="    SELECT `media_comments`.*,$member_search_fields 
                    FROM `media_comments`
              INNER JOIN `member_master` ON `media_comments`.user_id=`member_master`.id 
                   WHERE file_id='$vid' 
                     AND `media_comments`.type = 'video'
                ORDER BY postdate DESC";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

        return $rs;
    }

    function getCommentCount($vid)
    {
        $sql="SELECT count(id) as cnt from  `media_comments` where `file_id`='$vid' AND type='video'";
        $RS = $this->db->get_row($sql,ARRAY_A);
        return $RS;
    }

    function postComment()
    {
        $arrData=$this->getArrData();
        $this->db->insert("media_comments",$arrData);
        return true;
    }

    function rateVideo()
    {
        $arrData=$this->getArrData();
		$arrData["postdate"]=date("Y-m-d H:i:s");
        $sql="Select id from media_rating where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='video'";
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
	
	function rateProperty()
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


    function incrementView($vid)
    {
        $this->db->query("Update `album_video` set views=views+1 where id='$vid'");
        return true;
    }

    function incrementDownload($vid)
    {
        $this->db->query("Update `album_video` set downloads=downloads+1 where id='$vid'");
        return true;
    }

    function addFavorite()
    {
        $arrData=$this->getArrData();
        $sql="Select id from media_favorites where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='video'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
            $this->db->insert("media_favorites",$arrData);
            return true;
        }
        else
        {
            $this->setErr("This video already exists in your favorites list!!!");
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
	function updateVideoInfo($postArray)
	{
		$array = array("title" => $postArray["title"],"description" =>$postArray["description"]);
		$this->db->update("album_video",$array,"id={$postArray['vid']}");
	}
	function updateAdminVideoInfo($time,$field)
	{
	  
		$this->db->query("Update `config` set value='$time' where field='$field'");
        return true;
	}
	function getSubscriptionDetails($pageNo, $limit, $params, $uid, $tbl_name, $output=OBJECT)
	{
			list($qry,$table_id,$join_qry)=$this->generateQry($tbl_name,'cs','av');
			if($this->config["show_private"]=="Y"){
				$sql = "SELECT av.* ,mm.username,mm.screen_name,$qry FROM $tbl_name av INNER JOIN member_master mm on av.user_id=mm.id $join_qry WHERE av.user_id=$uid";
			}else{
				$sql = "SELECT av.* ,mm.username,mm.screen_name,$qry FROM $tbl_name av INNER JOIN member_master mm on av.user_id=mm.id $join_qry WHERE av.user_id=$uid and av.privacy='public'";
			}
			$rs =$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function getSubscriberDetails($uid,$type)
	{
		$sql="SELECT ms.`subscribed_id` as id,mm.username,mm.screen_name FROM my_subscriptions ms 
		      INNER JOIN member_master mm on ms.subscribed_id=mm.id WHERE ms.`member_id` =$uid AND ms.`type` = '$type'";
		$rs=$this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	function propertyList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$shop=0,$default_vd_php=0,$memberid=0,$search_fields='',$search_values='',$criteria='=')
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
        
      	list($qry,$table_id)=$this->generateQry('album','cs');
      	
      	if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('album',$search_fields,$search_values,$criteria,'m','cs');
		}
		
		$sql = "SELECT a.*,$fld as $fldnm,d.username,$qry FROM
		       ((`album` a left join `album_files` b on (a.id=b.file_id and b.type='video'))
		       inner join `member_master` d on a.user_id=d.id) left join
		      `$tbl` e on (a.id=e.file_id and e.type='album') left join `custom_fields_list` cs 
		       on a.id=cs.table_key and cs.table_id=$table_id"; 
		
		if($catid>0)
		{
			$sql=$sql." where (a.cat_id=$catid) and (a.privacy='public')";
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
					$sql=$sql." and (a.privacy='public')";
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
					//if($default_vd_php == 1)
					//$qry1 = " and cs.field_22 >0"; //cs.field_22 = default_vdo
					
					$sql=$sql." where 1 $qry1";
					
					if($memberid >0)
					$qry2 = " and a.user_id=$memberid";
				}
			}
			
		}
		
		if ($shop == 1) {
			$sql=$sql." and a.price > 0 ";
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
				
                    $rating=$this->getRating('album',$value[$i]->id);
                    $rs[0][$i]->rate=$rating["rate"];
                    $rs[0][$i]->cnt=$rating["cnt"];

                    $cmcnt =$this->getCommentsCnt('album',$value[$i]->id);
                    $rs[0][$i]->cmtcnt=$cmcnt;

                    $favcnt =$this->getFavCnt('album',$value[$i]->id);
                    $rs[0][$i]->favrcnt=$favcnt;

                }
            }
        }
        return $rs;
    }

	/**/
function musicListSubscribe ($pageNo, $limit, $params='',$uid, $output=OBJECT, $orderBy='',$catid=0,$stxt=0,$tp='',$shop=0)
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
		$sql=$sql." and d.id=$uid";
        $sql=$sql." group by a.id";
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
       // print_r($rs);exit;
       return $rs;
    }
    function editVideo($id)
    {
    	$arr = $this->getArrData();
    	$this->db->update("album_video",$arr,"id=$id");
    	return true;
    }
	
	/**
	 * This function is used to get the list of video 
	 * Author   : Adarsh
	 * Created  : 26/jun/2008
	 * Modified : 
	 */
	 
    function getVideosListRandom()
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album_video','cs','av');
		
		$sql="select av.id, $qry from album_video av  $join_qry  left join member_master b on av.user_id=b.id ";
		
		$limit = $this->config["featured_mem_cnt"];
		if ($limit==""){
			$limit=9;
		}
		$sql .= " where av.active='Y' and b.mem_type !=0 order by av.postdate DESC  limit $limit";
		$rs = $this->db->get_results($sql,ARRAY_A);
		
		return $rs;
	}

}//End Class
?>