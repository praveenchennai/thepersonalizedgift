<?php
class Music extends FrameWork
{
    /*
    constructor
    */
    function Music()
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

    function uploadPhoto()
    {
        $arrData = $this->getArrData();
        $photos=array();
        $arr = array();
        if ($_FILES["photo1"]["name"])
        {

            if (!pictureFormat($_FILES["photo1"]["type"]))
            {
                $this->setErr("Unknown Picture Format!!");
                return false;

            }
            else
            {
                $photos[]="photo1";
            }

        }

        if ($_FILES["photo2"]["name"])
        {
            if (!pictureFormat($_FILES["photo2"]["type"]))
            {
                $this->setErr("Unknown Picture Format!!");
                return false;
            }
            else
            {
                $photos[]="photo2";
            }
        }

        if ($_FILES["photo3"]["name"])
        {
            if (!pictureFormat($_FILES["photo3"]["type"]))
            {
                $this->setErr("Unknown Picture Format!!");
                return false;
            }
            else
            {
                $photos[]="photo3";
            }
        }

        if (sizeof($photos)==0)
        {
            $this->setErr("Please select atleast one picture to Upload");
            return false;
        }

        for ($i=0;$i<sizeof($photos);$i++)
        {
            $id=$this->db->insert("album_photos",$arrData);
            $dir=SITE_PATH."/modules/album/photos/";
            $thumbdir=$dir."thumb/";
            $redir=$dir."resized/";

            uploadImage($_FILES[$photos[$i]],$dir,"$id.jpg");
            chmod($dir."$id.jpg",0777);

            thumbnail($dir,$thumbdir,"$id.jpg",100);
            chmod($thumbdir."$id.jpg",0777);

            thumbnail($dir,$redir,"$id.jpg",500,500);
            chmod($redir."$id.jpg",0777);
        }

        return true;

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
				//print_r($sql);exit;
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
		
		if ($_REQUEST['sounds_like'])
		{
			if ($search_field!='')
			{
				$search_field.= ",sounds_like";
				$search_value.= ",".$_REQUEST['sounds_like'];
			}
			else 
			{
				$search_field = "sounds_like";
				$search_value = $_REQUEST['sounds_like'];
			}
		}
		
		if($search_field!='')
		{
			list($qry_cs)=$this->getCustomQry('album_music',$search_field,$search_value,$crt,'a','cs');	
			$sql = $sql." AND $qry_cs";
		}
		
		
        $sql=$sql." group by a.id";
        //print $sql;
		//exit;
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
                    
					$udet= $this->getUserdetails($value[$i]->user_id);
					$rs[0][$i]->user_image=$udet['image'];
                }
            }
        }
       		//print_r($rs);
		//	exit;
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
    
    function getMusicDetails($vid,$obj='')
    {
        list($qry,$table_id)=$this->generateQry('album_music','d');
        $sql="SELECT a.album_id,av.*, mm.username,mm.image,mm.id as user_id,$qry 
		FROM (`album_music` av left join `album_files` a on av.id = a.file_id
		AND a.type='music')inner join `member_master` mm on av.user_id = mm.id
		left join `custom_fields_list` d on av.id=d.table_key and d.table_id=$table_id where av.`id` = '$vid'";
        if ($obj!="")
        {
        	$RS = $this->db->get_row($sql, OBJECT);
        }
        else 
        {
        	$RS = $this->db->get_row($sql, ARRAY_A);
        }
		//print_r($sql);exit;
        return $RS;
    }

    function commentList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$vid)
    {
        $sql="    SELECT `media_comments`.*,`member_master`.image ,`member_master`.username 
                    FROM `media_comments`
              INNER JOIN `member_master` ON `media_comments`.user_id=`member_master`.id 
                   WHERE file_id='$vid' 
                     AND `media_comments`.type = 'music'
                ORDER BY postdate DESC";

        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

        return $rs;
    }

    function getCommentCount($vid)
    {
        $sql="SELECT count(id) as cnt from  `media_comments` where `file_id`='$vid' AND type='music'";
        $RS = $this->db->get_row($sql,ARRAY_A);
        return $RS;
    }

    function postComment()
    {
        $arrData=$this->getArrData();
        $this->db->insert("media_comments",$arrData);
        return true;
    }

    function rateMusic()
    {
        $arrData=$this->getArrData();
		$arrData["postdate"]=date("Y-m-d H:i:s");
        $sql="Select id from media_rating where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='music'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
            $this->db->insert("media_rating",$arrData);
            return true;
        }
        else
        {
            $this->setErr("You have already rated this Music!!!");
            return false;
        }
    }

    function incrementView($vid)
    {
        $this->db->query("Update `album_music` set views=views+1 where id='$vid'");
        return true;
    }

    function incrementDownload($vid)
    {
        $this->db->query("Update `album_music` set downloads=downloads+1 where id='$vid'");
        return true;
    }

    function addFavorite()
    {
        $arrData=$this->getArrData();
        $sql="Select id from media_favorites where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='music'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
            $this->db->insert("media_favorites",$arrData);
            return true;
        }
        else
        {
            $this->setErr("This music already exists in your favorites list!!!");
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
	##### Function to get email ids of subscribers........................
	#####  Author Jipson Thomas.............................
	#####  Dated  03/09/2007..
	function getSubscriberEmails($uid,$type)
	{
	
	$sql="select ms.`subscribed_id` as id,mm.email FROM my_subscriptions ms inner join member_master mm on ms.subscribed_id=mm.id WHERE 	ms.`member_id` =$uid AND ms.`type` = '$type'";
	$rs=$this->db->get_results($sql,ARRAY_A);
	return $rs;
	}
	
	####  End Function........................................
	
	##### Function to get Album Music Files........................
	#####  Author Jipson Thomas.............................
	#####  Dated  18/09/2007..
	function getMyTrack($pageNo, $limit, $params='',$uid, $output=OBJECT, $orderBy='')
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('album_music','cs','a');
		$sql="select a.*,$qry FROM album_music a $join_qry where  a.user_id='$uid'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
	
		return $rs;
	}
	
	####  End Function........................................

}//End Class
?>