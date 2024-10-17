<?php
class Cmp extends FrameWork
{
    /*
    constructor
    */
	
    function Cmp()
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
	
	
	
	function insertCmp()
	{
		$arrData = $this->getArrData();
		$sql="SELECT * FROM `competition_head` where `name`='".$arrData['name']."'";
		
        $count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->db->insert("competition_head",$arrData);
			$id = $this->db->insert_id;
        	return $id;
		}
		else
		{
			$this->setErr("Competition name already Exists");
			return false;
		}	
	}
	
	function updateCmp($id)
	{
		$arrData=$this->getArrData();
		$this->db->update("competition_head",$arrData,"id=$id");
	}
	
	function cmpWinnerList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0)
	{
		if($catid>0)
		{
			$sql="SELECT a.cat_id,a.name,b.*,c.username from (`competition_head`  a inner join 
			 `competition_winners` b on a.id=b.cmp_id) inner join `member_master` c on
			  b.user_id=c.id where a.cat_id=$catid order by mark desc,name";			 
		}
		else
		{
			 $sql="SELECT a.cat_id,a.name,b.*,c.username from (`competition_head`  a inner join 
			 `competition_winners` b on a.id=b.cmp_id) inner join `member_master` c on
			  b.user_id=c.id order by mark desc,name";
		}	 
				 
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
		
	}
	
	function cmpList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$stxt=0,$my=0,$uid=0,$filter=0,$admin=0,$type='') 
	{
		$sql="SELECT a.*,count(b.id)as active_users FROM `competition_head` a left join ";
		$sql=$sql." `competition_member` b on a.id=b.comp_id";
		if($catid>0)
		{
			
			$sql=$sql." where (a.cat_id=$catid)";
			if ($stxt!='')
			{
				$sql=$sql. " and (a.name like '%$stxt%') ";
			}
			if($my>0)
			{
				$sql=$sql." and (b.user_id=$uid)";
			}
			if ($filter==1)
			{
				$sql=$sql. " and (a.startdate<='".date("Y-m-d H:i:s")."' and a.enddate>='".date("Y-m-d H:i:s")."')";
			}
			elseif($filter==2)
			{
				$sql=$sql. " and (a.enddate<'".date("Y-m-d H:i:s")."')";
			}
			elseif($filter==3)
			{
				$sql=$sql. " and (a.startdate>'".date("Y-m-d H:i:s")."')";
			}
			elseif($filter==5)
			{
				$sql=$sql. " and a.user_id=$uid";
			}
			if($type!='')
			{
				$sql=$sql. " and a.cmp_type='$type'";
			}
			
		}
		else
		{
			if ($stxt!='')
			{
				$sql=$sql. " where (a.name like '%$stxt%')";
				if($my>0)
				{
					if($filter!=5)
					{	
						$sql=$sql." and (b.user_id=$uid)";
					}
					
				}
				if ($filter==1)
				{
					$sql=$sql. " and (a.startdate<='".date("Y-m-d H:i:s")."' and a.enddate>='".date("Y-m-d H:i:s")."')";
				}
				elseif($filter==2)
				{
					$sql=$sql. " and (a.enddate<'".date("Y-m-d H:i:s")."')";
				}
				elseif($filter==3)
				{
					$sql=$sql. " and (a.startdate>'".date("Y-m-d H:i:s")."')";
				}
				elseif($filter==4)
				{
					$sql=$sql. " where (a.enddate<'".date("Y-m-d H:i:s")."') and archive=1";
				}
				
				elseif($filter==5)
				{
					$sql=$sql. " and a.user_id=$uid";
				}
				if($type!='')
				{
					$sql=$sql. " and a.cmp_type='$type'";
				}

				
			}
			else
			{
				if($my>0)
				{
					
					if($filter!=5)
					{	
						$sql=$sql." where (b.user_id=$uid)";
						$pre="and";
					}
					else
					{
						$pre="where";
					}
					
					if ($filter==1)
					{
						$sql=$sql. " $pre (a.startdate<='".date("Y-m-d H:i:s")."' and a.enddate>='".date("Y-m-d H:i:s")."')";
					}
					elseif($filter==2)
					{
						$sql=$sql. " $pre (a.enddate<'".date("Y-m-d H:i:s")."') ";
					}
					elseif($filter==3)
					{
						$sql=$sql. " $pre (a.startdate>'".date("Y-m-d H:i:s")."')";
					}
					elseif($filter==4)
					{
						$sql=$sql. " $pre (a.enddate<'".date("Y-m-d H:i:s")."') and archive=1";
					}

					elseif($filter==5)
					{
						$sql=$sql. " $pre a.user_id=$uid";
					}
					if($type!='')
					{
						$sql=$sql. " and a.cmp_type='$type'";
					}

				}
				else
				{
						if ($filter==1)
						{
							$sql=$sql. " where  (a.startdate<='".date("Y-m-d H:i:s")."' and a.enddate>='".date("Y-m-d H:i:s")."')";
						}
						elseif($filter==2)
						{
							$sql=$sql. " where (a.enddate<'".date("Y-m-d H:i:s")."')";
							if($admin==1)
							{
								$sql=$sql. " and archive=0";
							}
						}
						elseif($filter==3)
						{
							$sql=$sql. " where (a.startdate>'".date("Y-m-d H:i:s")."')";
						}
						elseif($filter==4)
						{
							$sql=$sql. " where (a.enddate<'".date("Y-m-d H:i:s")."') and archive=1";
						}
						elseif($filter==5)
						{
							$sql=$sql. " and a.user_id=$uid";
						}
						
						if($type!='')
						{
							$sql=$sql. "and a.cmp_type='$type'";
						}

				}
			}
		}
		$sql=$sql." group by a.id ";
		
		//print $sql;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$member=$this->getUsername($rs[0][$i]->user_id,$rs[0][$i]->owner_type);
					$rs[0][$i]->username=$member["username"];
					$winner=$this->getWinner($value[$i]->id);
					$pre='';
					$winUser='';
					for($k=0;$k<sizeof($winner);$k++)
					{
						if($k>0)
						{
							$pre=", ";
						}
						$user=$this->getUsername($winner[$k]["winner"]);
						$winUser=$winUser.$pre.$user["username"];
					}
					if($winUser!='')
					{
						$rs[0][$i]->winner=$winUser;
					}	
					
					if($filter==1)
					{
						$diff=$this->get_time_difference(date("Y-m-d H:i:s"), $rs[0][$i]->enddate) ;
						$counter='';
						if ($diff["days"]>0)
						{
							$counter=$diff["days"]." Days, ";
						}
						if (($diff["hours"]>0) || ($diff["days"]>0))
						{
							$counter=$counter.$diff["hours"]." hours, ";
						}
						if (($diff["minutes"]>0)||($diff["hours"]>0) || ($diff["days"]>0)) 
						{
							$counter=$counter.$diff["minutes"]." minutes and ";
						}
						if (($diff["seconds"]>0)||($diff["minutes"]>0)||($diff["hours"]>0) || ($diff["days"]>0))
						{
							$counter=$counter.$diff["seconds"]." seconds.";
						}

						$rs[0][$i]->counter=$counter;
					}
				}	
			}
		}
		return $rs;
	}
	function getCmpDetails($cmpid)
	{
		$sql="SELECT a.*,count(b.id)as active_users FROM `competition_head` a left join ";
		$sql=$sql." `competition_member` b on a.id=b.comp_id where a.id=$cmpid group by a.id";
        $RS = $this->db->get_results($sql,ARRAY_A);
		$member=$this->getUsername($RS[0]["user_id"],$RS[0]["owner_type"]);
		$RS[0]["username"]=$member["username"];		
        return $RS[0];
	}
	
	function getUsername($uid,$type='')
    {
		if($type=="admin")
		{
			$tbl="admin";
		}	
		else
		{
			$tbl="member_master";
		}
		$sql="select * from $tbl where id=$uid";
		$RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
    }
	function checkCmpMember($cmpid,$uid)
	{
		$sql="Select id from `competition_member` where comp_id=$cmpid and user_id=$uid";
		$count=count($this->db->get_results($sql));
		if ($count==0)
		{
			return false;
		}
		else
		{
			return true;
		}		
	}
	
	function joinCmp($cmpid,$uid)
	{
		if ($this->checkCmpMember($cmpid,$uid))
		{
			$this->setErr("You are already a member of this Competition");
			return false;
		}
		else
		{
			$arrData=array();
			$arrData["comp_id"]=$cmpid;
			$arrData["user_id"]=$uid;
			$arrData["joindate"]=date("Y-m-d H:i:s");
			$this->db->insert("competition_member",$arrData);
			return true;
		}
	}
	
	function cmpMemList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$cmpid) 
	{
		$sql="select x.*,y.username,y.image from `competition_member` x inner join `member_master` y";
		$sql=$sql." on x.user_id=y.id where x.comp_id=$cmpid order by x.joindate desc";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
	}
	function cmpUpload()
	{
		$arrData=$this->getArrData();
		$sql="Select id from `competition_member_files` where cmp_mem_id=".$arrData["cmp_mem_id"]. " and type='".$arrData["type"]."' and file_id=".$arrData["file_id"];
		$count=count($this->db->get_results($sql));
		if ($count==0)
		{
	
			$this->db->insert("competition_member_files",$arrData);
			return true;
		}
		else
		{
			$this->setErr("This file already exists in this competition");
			return false;
		}	
	}
	
	function getFileCnt($memid)
	{
		$sql="Select id from `competition_member_files` where cmp_mem_id=$memid";
		$count=count($this->db->get_results($sql));
		return $count;
	}
	
	function getCmpMem($cmpid,$uid)
	{
		$sql="Select * from `competition_member` where comp_id=$cmpid and user_id=$uid";
		$rs=$this->db->get_results($sql);
		if (count($rs)==0)
		{
			$this->setErr("You are not a member of this competition");
			return false;
		}
		else
		{
			return $rs;
		}		
	}
	function getMediaDetails($tbl,$id)
	{
		$sql="Select * from $tbl where id=$id";
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS[0];
	}
	function cmpMediaList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$cmp_id,$type) 
	{
		if ($type=="video")
		{
			$tbl="album_video";
		}
		elseif($type=="photo")
		{
			$tbl="album_photos";
		}
		else		
		{
			$tbl="album_music";
		}
		
		$sql="SELECT c.user_id,d.*,IFNULL(sum(e.mark),0)+IFNULL(z.mark,0) as mark,a.id as cmp_file_id FROM (((`competition_member_files` a ";
		$sql=$sql."inner join `competition_member` b on (a.cmp_mem_id=b.id)) inner join ";
		$sql=$sql."`competition_head` c on b.comp_id=c.id ) inner join `$tbl` d on ";
		$sql=$sql."a.file_id=d.id) left join `media_rating` e on (e.type='$type' and ";
		$sql=$sql."e.file_id=a.file_id and (e.postdate>=a.postdate and e.postdate<=c.enddate)) ";
		$sql=$sql."left join `competition_points` z on (a.id=z.cmp_file_id  and (z.postdate>=a.postdate and z.postdate<=c.enddate))";
		$sql=$sql."where c.id=$cmp_id ";	
		
		//print $sql;
		
		$sql=$sql."group by a.id order by mark desc";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		$flg=0;
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$member=$this->getUsername($rs[0][$i]->user_id,$rs[0][$i]->owner_type);
					$rs[0][$i]->username=$member["username"];
					$rs[0][$i]->image=$member["image"];
					if(($pageNo=='') || ($pageNo==1))
					{
						if($rs[0][$i]->id>0)
						{

							if($flg==0)
							{
								if($rs[0][$i]->mark>0)
								{
									if($i>0)
									{
										if($rs[0][$i]->mark==$rs[0][$i-1]->mark)
										{
											$rs[0][$i]->win=1;
										}	
										else
										{
											$flg=1;
										}
									}
									else
									{
										$rs[0][$i]->win=1;
									}	
								}	
							}	
						}	
					}
				}	
			}
		}

		return $rs;
	}
	
	function getMediaCnt($cmp_id)
	{
		$sql="SELECT b.id FROM `competition_member` a inner join ";
		$sql=$sql."`competition_member_files` b on a.id=b.cmp_mem_id ";
		$sql=$sql." where a.comp_id=$cmp_id";
		
		$rs=$this->db->get_results($sql);

		return count($rs);
	}	
	function checkMemLimit($cmp_id)
	{
		$sql="SELECT b.id FROM `competition_member` a inner join ";
		$sql=$sql."`competition_member_files` b on a.id=b.cmp_mem_id ";
		$sql=$sql." where a.comp_id=$cmp_id";
	
	}
	function addPoints($file_id,$mark)
	{
		$arrData=array();
		$sql = "select * from competition_points where `cmp_file_id`=$file_id";
		$rs=$this->db->get_results($sql);
		if (count($rs)>0)
		{	
			$preMark = $rs[0]->mark;
			$id      = $rs[0]->id;
			$arrData["cmp_file_id"] = $file_id;
			$arrData["mark"]	    = $preMark+$mark;
			$arrData["postdate"]    = date("Y-m-d H:i:s");
			$this->db->update("competition_points",$arrData,"id=$id");
			return true;
		}
		else
		{
			$arrData["cmp_file_id"] = $file_id;
			$arrData["mark"]	    = $mark;
			$arrData["postdate"]    = date("Y-m-d H:i:s");
			$this->db->insert("competition_points",$arrData);
			return true;
		}	
	}
	function getCmpFinished()
	{
	}
	/*function getCmpWinner()
	{
		$sql="SELECT a.* as cnt from `competition_head` a  where (a.enddate<'".date("Y-m-d H:i:s")."') order by a.id desc" ;
		$rs = $this->db->get_results($sql);
		$arr=array();
			$cnt=0;
			for($i=0;$i<sizeof($rs);$i++)
			{
					
					$cmpid=$rs[$i]->id;
					$type=$rs[$i]->media;
					if ($rs[$i]->media=="video")
					{
						$tbl="album_video";
					}
					else
					{
						$tbl="album_music";
					}
					
					$sql="SELECT c.name,c.id as comp_id,c.user_id as owner,d.*,IFNULL(sum(e.mark),0)+IFNULL(z.mark,0) as mark,a.id as cmp_file_id FROM (((`competition_member_files` a ";
					$sql=$sql."inner join `competition_member` b on (a.cmp_mem_id=b.id)) inner join ";
					$sql=$sql."`competition_head` c on b.comp_id=c.id ) inner join `$tbl` d on ";
					$sql=$sql."a.file_id=d.id) left join `media_rating` e on (e.type='$type' and ";
					$sql=$sql."e.file_id=a.file_id and (e.postdate>=a.postdate and e.postdate<=c.enddate)) ";
					$sql=$sql."left join `competition_points` z on (a.id=z.cmp_file_id  and (z.postdate>=a.postdate and z.postdate<=c.enddate))";
					$sql=$sql."where c.id=$cmpid ";	
				
					$sql=$sql."group by a.id order by mark desc limit 1";
					$rs1 = $this->db->get_results($sql);
					if($rs1[0]->user_id>0)
					{
						$member=$this->getUsername($rs1[0]->user_id,'');
						$rs1[0]->username=$member["username"];
						$rs1[0]->image=$member["image"];
					}	

					if(($rs1[0]->user_id>0))
					{
						$arr[$cnt]=$rs1[0];
						$cnt+=1;
					}		
			}
			return $arr;		
	}*/
	
	function moveArchive($cmp_id)
	{
		$arrData=array();
		$arrData["archive"]=1;
		$this->db->update("competition_head",$arrData,"id=$cmp_id");
	}
	
	function recrList()
	{
		$sql="SELECT * from competition_head where (enddate<'".date("Y-m-d H:i:s")."' and recurring='Y' and rec_upd=0)";
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS;
	}
	function cmpFinishList()
	{
		$sql="SELECT * from competition_head where (enddate<'".date("Y-m-d H:i:s")."' and owner_type='user' and msg_upd=0)";
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS;
	}
	
	function sendFinishMsg($cmp_name,$uname,$cmp_id)
	{
		$arr = array();
		$arr["from"]="admin";
		$arr["to"]=$uname;
		$arr["subject"]="Competition($cmp_name) finish alert";
		$comment="This is to let you know that the competition ($cmp_name) has ended. <br><br>";
		$comment="Please click the following link to view details <br><br>";
		$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"competition", "pg"=>"cmp"), "act=media&cmp_id=$cmp_id&filter=complete")."\">View Details</a>";
		$arr["comments"]=$comment;

		$arr["datetime"]=date("Y-m-d H:i:s");
		$arr["status"]="U";
		$this->db->insert("message",$arr);
		$arr1=array();
		$arr1["msg_upd"]=1;
		$this->db->update("competition_head",$arr1,"id=$cmp_id");
	}

	function autoCmp($arrData)
	{
		$endDate  = $arrData['enddate']; 
		$interval = $arrData['days_interval'];
		 
		$sql = "select date_add('$endDate',INTERVAL $interval DAY) as nextDt";
		$rs=$this->db->get_row($sql);
		$startDate=substr($rs->nextDt,0,10)." 00:00:00";
		$sql = "select date_add('$startDate',INTERVAL $interval DAY) as endDt";
		$rs=$this->db->get_row($sql);
		$endDt=substr($rs->endDt,0,10)." 23:59:59";
		$arrData["startdate"]=$startDate;
		$arrData["enddate"]=$endDt;
		$preId = $arrData["id"];
		unset($arrData["id"]);
		$this->db->insert("competition_head",$arrData);
		$arr=array();
		$arr["rec_upd"]=1;
		$this->db->update("competition_head",$arr,"id=$preId");
	}
	
	function cmpDelete($cmp_id)
	{
		$sql = "select * from competition_member where `comp_id`=$cmp_id";
		$rs=$this->db->get_results($sql);
		if(count($rs)>0)
		{
			$this->setErr("This competition have members, hence you are not allowed to delete");
			return false;
		}
		else
		{
			$this->db->query("DELETE from competition_head where id=$cmp_id");
			return true;
		}
	}
	
	
	function get_time_difference($start, $end)
	{
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return(array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				$this->setErr("Ending date/time is earlier than the start date/time");
			}
		}
		else
		{
			$this->setErr("Invalid date/time data detected");
		}
		return( false );
	}

	function getCmpWinner($cmp_id,$type) 
	{
		if ($type=="video")
		{
			$tbl="album_video";
		}
		elseif ($type=="music")
		{
			$tbl="album_music";
		}
		else
		{
			$tbl="album_photos";
		}
		$sql="SELECT c.name,c.id as cmp_id,c.media,d.user_id,d.id as file_id,IFNULL(sum(e.mark),0)+IFNULL(z.mark,0) as mark,a.id as cmp_file_id FROM (((`competition_member_files` a ";
		$sql=$sql."inner join `competition_member` b on (a.cmp_mem_id=b.id)) inner join ";
		$sql=$sql."`competition_head` c on b.comp_id=c.id ) inner join `$tbl` d on ";
		$sql=$sql."a.file_id=d.id) left join `media_rating` e on (e.type='$type' and ";
		$sql=$sql."e.file_id=a.file_id and (e.postdate>=a.postdate and e.postdate<=c.enddate)) ";
		$sql=$sql."left join `competition_points` z on (a.id=z.cmp_file_id  and (z.postdate>=a.postdate and z.postdate<=c.enddate))";
		$sql=$sql."where c.id=$cmp_id ";	
		
		//print $sql;
		
		$sql=$sql."group by a.id order by mark desc";

        $rs = $this->db->get_results($sql,ARRAY_A);
			for($i=0;$i<sizeof($rs);$i++)
			{
				if($rs[$i]["cmp_id"]>0)
				{
					$member=$this->getUsername($rs[$i]["user_id"],$rs[$i]["owner_type"]);
					$rs[$i]["username"]=$member["username"];
					$rs[$i]["image"]=$member["image"];
				}	
			}
		
		return $rs;
	}
	
	function insertWinner($arr,$cmp_name,$uname)
	{
		$sql = "select * from competition_winners where `cmp_id`=".$arr["cmp_id"]." 
		and user_id=".$arr["user_id"]." and file_id=".$arr["file_id"];
		$rs=$this->db->get_results($sql);
		if(count($rs)==0)
		{
			$this->db->insert("competition_winners",$arr);
			$cmp_id=$arr["cmp_id"];
			$arr1 = array();
			$arr1["from"]="admin";
			$arr1["to"]=$uname["username"];
			$arr1["subject"]="You have won Competition($cmp_name)";
			$comment="This is to let you know that you have won Industrypage competition ($cmp_name) <br><br>";
			$comment="Please click the following link to view details <br><br>";
			$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"competition", "pg"=>"cmp"), "act=media&cmp_id=$cmp_id&filter=complete")."\">View Details</a>";
			$arr1["comments"]=$comment;
			$arr1["datetime"]=date("Y-m-d H:i:s");
			$arr1["status"]="U";
			$this->db->insert("message",$arr1);
			return true;
		}	
	}
	
	function getWinner($cmp_id)
	{
		$sql="SELECT user_id as winner from competition_winners where cmp_id=$cmp_id";
        $RS = $this->db->get_results($sql,ARRAY_A);
        return $RS;
	}
	
	function passValidate($cmp_id,$uid,$pass)
	{
		$sql = "select * from `competition_invites` where cmp_id=$cmp_id and to_user=$uid";
		$rs=$this->db->get_results($sql);
		if (count($rs)==0)
		{
			$this->setErr("You have not received an invitation to join this competition");
		}
		else
		{
			$sql = "select cmp_pass from `competition_head` where id=$cmp_id";
			$rs=$this->db->get_col($sql,0);
			if ($rs[0]==$pass)
			{
				return true;
			}
			else
			{
				$this->setErr("Invalid password");
			}
		}

	}
	
	function insertInvite()
	{
		$arrData=$this->getArrData();
		$sql = "select * from `competition_invites` where cmp_id=". $arrData["cmp_id"] ." and to_user=".  $arrData["to_user"] ;
		$rs=$this->db->get_results($sql);
		if (count($rs)==0)
		{
			$this->db->insert("competition_invites",$arrData);
			return true;
		}
	}
	
	function insertFee()
	{
		$arrData=$this->getArrData();
		$sql="select * from competition_fee";
		$rs=$this->db->get_col($sql,0);
		if (count($rs)==0)
		{
			$this->db->insert("competition_fee",$arrData);		
		}
		else
		{
			$id=$rs[0];
			$this->db->update("competition_fee",$arrData,"id=$id");
			
		}
	}
	
	function getLastPrize($prize)
	{
		$sql="select * from competition_fee";
		$type=$this->db->get_col($sql,1);
		$value=$this->db->get_col($sql,2);
				
		if(count($type)>0)
		{	
			if($type[0]==0)
			{
				$fee=$prize * ($value[0]/100);
				$cmn= $value[0] . "% of Prize Money = $fee USD";
				$lastprize=$prize+$fee;
			}
			else
			{
				$fee= $value[0];
				$cmn= $value[0] . " USD";
				$lastprize=$prize+$fee;
			}	
		}
		else
		{
			$cmn="FREE";
			$lastprize=$prize;
		}
		
		$arr=array();
		$arr[0]=$cmn;
		$arr[1]=$lastprize;	
		
		return $arr;

	}
	function insertPayTrack()
	{
		$arr=$this->getArrData();
		$this->db->insert("competition_pay_track",$arr);
		return true;
	}
	
	function cmpPayList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy) 
	{
		$sql= "Select a.*,b.name,b.price,c.username from `competition_pay_track` a left join `competition_head` b
			  on a.cmp_id=b.id left join `member_master` c on b.user_id=c.id order by a.id desc";	

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function cmpPayDel($id)
	{
			$this->db->query("DELETE from `competition_pay_track` where id=$id");
			return true;
	}

	
}// End Class	
?>	