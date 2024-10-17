<?php
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");

$objCms = new Cms();
$album= new Album();
$objPhoto=new Photo();
$objUser=new User();
$music=new Music();
$email	 = new Email();
$flyer = new Flyer();
$objSearch	    =   new Search();
if($global["sort_by_category_name"]=="1")
	$catarr=$objUser->getCategoryArr($_REQUEST["mod"],1);
else
	$catarr=$objUser->getCategoryArr($_REQUEST["mod"]);
$memberID = $_SESSION['memberid'];
$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));
$framework->tpl->assign("CAT_ARR",$catarr);
$framework->tpl->assign("LEFTBOTTOM","photo" );
if(isset($_SESSION['chps1']))
	{
	 $framework->tpl->assign("chps1",$_SESSION['chps1']);
	}
	if($_SESSION['chps1']==1){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("social_community_right");
	}elseif($_SESSION['chps1']==2){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}elseif($_SESSION['chps1']==3){
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
			$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==5){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}else{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	
	
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}

$framework->tpl->assign("advertisement",1);
$framework->tpl->assign("inner_content",1);
switch($_REQUEST['act'])
{
	case "edit":
		/*
		* Created:Jipson Thomas
		* Proj:link54
		  Created :28-12-2007 
		 
		*/
		
		checkLogin();
		if($_REQUEST['photo_id'])
		{
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"]);
			//print_r($phdet);exit;
			$framework->tpl->assign("PHOTODETAILS",$phdet);
			$framework->tpl->assign("EDITFLG",1);
			$_REQUEST=$_REQUEST+$phdet;
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$objPhoto->setArrData($_POST);
			$objPhoto->editPhoto("album_photos","photo");
			//print_r($_FILES["photoImg"]["size"]);exit;
			if($_FILES["photoImg"]["size"]!=0){
				$extension = strstr($_FILES["photoImg"]["name"],".");
				$dir=SITE_PATH."/modules/album/photos/";
				$imgArray = array("size" => $_FILES["photoImg"]["size"],"type" => $_FILES["photoImg"]["type"], "tmp_name" => $_FILES["photoImg"]["tmp_name"]);
				$id=$_POST['id'];
				$photo_det = $album->mediaDetailsGet($_POST['id'],"photos");
				($photo_det['img_extension']!="") ? $img_ext = $photo_det['img_extension'] : $img_ext =".jpg";	
				@unlink(SITE_PATH."/modules/album/photos/".$id.$img_ext);
				@unlink(SITE_PATH."/modules/album/photos/thumb/".$id.$img_ext);
				@unlink(SITE_PATH."/modules/album/photos/resized/".$id.$img_ext);
				@chmod($dir."$id".$extension,0777);
				$thumbdir=$dir."thumb/";
				$redir=$dir."resized/";
				uploadImage($imgArray,$dir,"$id".$extension,1);
				chmod($dir."$id".$extension,0777);
				if ($global["photo_thumb1"])
				{
					$thumb_size  = explode(",",$global["photo_thumb1"]);
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
				if ($global["photo_resize"])
				{
					$thumb_size  = explode(",",$global["photo_resize"]);
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
				if ($global["photo_thumb2"])
				{
					$thumb_size  = explode(",",$global["photo_thumb2"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				
					thumbnail($dir,$thumbdir,"$id".$extension,$thumb_width,$thum_height,"","$id"."_thumb2".$extension,0);
					chmod($thumbdir."$id".$extension,0777);
				}
			}
			redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum&crt=M2"));
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/edit_photos.tpl");
		break;
	case "upload":
	
		checkLogin();
		//echo $_REQUEST['flyer_id'];
		
		/*
		* Created:Afsal
		* Proj:Bayard
		  Created :28-11-2007 
		*/
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		
		
		if($framework->config["tab_show"] == "1")
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(5, $_REQUEST['flyer_id'],$_REQUEST['propid']);
		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);
		if($global['Location_price_info']=='N'){
			if($flyerInfo['location_city']=="" || $flyerInfo['location_state']=="" || $flyerInfo['location_country']=="" || $flyerInfo['location_zip']=="" )
			{
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id&red=1"));
			}
		}
		
		$FloatingPrice = $flyer->getFlatingPriceResults($prop_id);
		if($global['Location_price_info']=='N'){
			if($FloatingPrice['price']<1 || $FloatingPrice['duration']<1 || $FloatingPrice['unit']=="")
			{
			  redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_quantity&flyer_id=$flyer_id&propid=$prop_id&red=2"));
			}
		}
		$framework->tpl->assign("LEFTBOTTOM","upload_photo" );
		if($global["show_property"] == 1){//realestate tube.....
			$framework->tpl->assign("TOP_MENU_SUB",SITE_PATH."/modules/album/tpl/top_menu.tpl");
		}

		$url=$_SERVER['QUERY_STRING'];
		$url=urlencode($url);
		$framework->tpl->assign("URLALB",$url);

		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"],$_POST["Submit"]);
			$_POST["postdate"]=date("Y-m-d G:i:s");
			$_POST["user_id"]=$_SESSION["memberid"];
			/*
			check the image format
			*/
			// If proect=Jewish chekUploadImageValidation1  else chekUploadImageValidation
			$photoArr = $objPhoto->chekUploadImageValidation();
			
			
			if ($photoArr != "error")
			{
				/**/
				$title   = $_POST["title"];
				$photoid = $_POST['photoid'];
				
				/*Created :Afsal
				/*Upload photo and page redirecting to next page
				/*Date:17-12-2007
				*/
				if($framework->config["redirect"] == 1){
		
					$photcount = $_REQUEST['photocnt'];
					unset($_POST['photocnt']);				
				}
				

				$default_img = isset($_POST['default_img'])? $_POST['default_img'] : "" ;
				unset($_POST["title"],$_POST['submit'],$_POST['photoid'],$_POST['default_img']);
				
				

				for($i=0;$i<count($photoArr);$i++)
				{
					
					if(count($title) == 1)
					$_POST["title"] =  $title[0];
					else
					$_POST["title"] =  $title[$photoArr[$i]];
					if(isset($photoid[$photoArr[$i]]))
					$updateid = $photoid[$i];
					else
					$updateid = 0;
					
					$objPhoto->setArrData($_POST);
					$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],$photoArr[$i],$updateid,"",'',$album);
					
				}

				if(isset($photoid))
				{
					for($i=0;$i<count($photoid);$i++)
					{
						$_POST["title"] = $title[$i];
						//print_r($_POST["title"]);exit;
						$objPhoto->setArrData($_POST);
						$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],'',$photoid[$i]);

					}

				}

				
				if($global["show_property"] == 1) //realestate tube
				{
					if($default_img =='')
					{
						$rs = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_SESSION["memberid"]);
						$default_img = $rs[0]["id"];
					}
					else
					{
						
						$default_img = $default_img;
					}
					$album->updateAlbum(array("default_img" => $default_img),$_REQUEST['propid']);
				}
				###  Portion to send mail to subscribers................................................
				$mail_header = array();
				$mail_header['from'] 	    = 	$framework->config['admin_email'];
				$toemails					=	$music->getSubscriberEmails($_SESSION["memberid"],"music");

				for($i=0;$i<sizeof($toemails);$i++){
					$mail						=	$toemails[$i]["email"];
					$udet						=	$objUser->getUserdetails($_SESSION["memberid"]);
					$mail_header["to"]          =	$mail;
					$dynamic_vars               =	array();
					$dynamic_vars["USERNAME"]  	=	$udet["username"];
					$dynamic_vars["SITE"]  		=	$framework->config['site_name'];
					$dynamic_vars["LINK"]       =	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=list&crt=M1")."\">Get Your new Photo</a>";
					$email->send("send_to_subscribers",$mail_header,$dynamic_vars);

				}


				### End Portion to send mail to subscribers..............................................
				setMessage("Photos have been updated successfully",MSG_SUCCESS);

				if($global["show_property"] == 1){
					
					
					/*
					* Created:Afsal
					* Proj:Bayard
					  Created :29-11-2007 
					*/
					if($framework->config["redirect"] == 1){/* For bayard project*/
						//if($photcount > 0)
						//redirect(makeLink(array("mod"=>"album", "pg"=>"video"),"act=upload&&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}&crt=M2"));
						//else
						redirect(makeLink(array("mod"=>"album", "pg"=>"photo"),"act=upload&&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}&crt=M2"));
					}else{
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&&propid={$_REQUEST['propid']}&view=edit"));	
					}
				}
				else
				{
				
				
				//act=myalbum&crt=M2&tpm=5
				
				if($global["inner_change_reg"]=="yes")
							{
				
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum&crt=M2&tpm=5&leftid={$_REQUEST['leftid']}&pid={$_REQUEST['pid']}"));
					}
					else if($global["mymedia_redirect"]==1)
					{
						redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum&crt=M2"));
					}
					else
					{
					redirect(makeLink(array("mod"=>"album", "pg"=>"photo"),"act=list&crt=M1"));
					}
					
				}
			}
		}
		if($framework->config["show_property"] == "1"){
			$rs = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_SESSION["memberid"]);
			$framework->tpl->assign("PROP_DETAILS",$album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]));
		}
		$publish=$objSearch->basicAlbumInfo($_REQUEST["propid"]);
		//print_r($publish);
		$framework->tpl->assign("PUBLISH",$publish);
		$framework->tpl->assign("PHOTO_LIST",$rs);
		
		
		$framework->tpl->assign("PHOTO_CNT",count($rs));
		
		/*
		* Created:Afsal
		* Proj:Bayard
		* Created :04-12-2007 
		*/
		if($framework->config["show_property"] == "1"){
			if(count($rs) > 3)
			$framework->tpl->assign("CNT",count($rs));
			else 
			$framework->tpl->assign("CNT",count($rs));
			
		}
		
		
		
		/*
		* Created:Afsal
		* Proj:Bayard
		* Created :28-11-2007 
		*/
		if($framework->config["tab_show"] == "1")
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		if($global['profile_inner']=='Y')
		 {
		 	 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			 $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/album/tpl/upload_photos.tpl");
			
		 }
		 else
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_photos.tpl");
		break;
	case "delete":
		checkLogin();
		$msg = $album->mediaDelete($_REQUEST['photoid'], $_REQUEST['crt']);
		
		if($_REQUEST['propid'] > 0 && $_REQUEST["photoid"] >0){
				$pid = $album->resetDefaultThumb($_REQUEST['propid'],$_REQUEST["photoid"],"photo");
				
				if($pid > 0){
					
					if($pid && $_REQUEST['propid'])
					$album->updateAlbum(array("default_img" => $pid),$_REQUEST['propid']);
				}
				
				
				/*if no record in album_photos set default_mg in custom table set as null */
				$unset_photo = $album->unsetDefaultThumb("photo",$_REQUEST['propid']);
				if($unset_photo == "0"){
					$album->updateAlbum(array("default_img" => ""),$_REQUEST['propid']);
				}
		}
		
		setMessage("Photo deleted Successfully",MSG_SUCCESS);
		/*
		* Created:Afsal
		* Proj:Bayard
		  Created :29-11-2007 
		*/
		if($framework->config["redirect"] == 1)/* For bayard project*/
		redirect(makeLink(array("mod"=>"album", "pg"=>"photo"),"act=upload&&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}&crt=M2"));
		else
		redirect(makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&propid=".$_REQUEST["propid"])."&crt=M2&suc=del");
		
		break;
	case "list":
	if($global["inner_change_reg"]=="yes")
							{
	     checkLogin();
		       }
		$framework->tpl->assign("TITLE_HEAD", "Photo List");
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}
		if ($_REQUEST["cat_id"])
		{
			$par = $par."&cat_id=".$_REQUEST['cat_id'];
		}

		if($_REQUEST["txtSearch"])
		{
			$stxt=$_REQUEST["txtSearch"];
			$framework->tpl->assign("STXT",$stxt);

		}
		else
		{	if(!$_REQUEST["stxt"])
		{
			$stxt=0;
		}
		else
		{
			$stxt=$_REQUEST["stxt"];
			$framework->tpl->assign("STXT",$stxt);
		}
		}
		$par = $par."&stxt=".$stxt;
		if ($_REQUEST["cat_id"])
		{


			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
			list($rs, $numpad,$count) = $objPhoto->photoList($_REQUEST['pageNo'], 5, $par, OBJECT, "id desc",$_REQUEST["cat_id"],$stxt);
		}
		elseif ($_REQUEST["crt"])
		{
			if ($_REQUEST["crt"]=="M1")
			{
				$pheader="Most Recent";
				$field="postdate desc";
			}
			elseif ($_REQUEST["crt"]=="M2")
			{
				$pheader="Most Viewed";
				$field="views desc";
			}
			elseif ($_REQUEST["crt"]=="M3")
			{
				$pheader="Most Discussed";
				$field="cmcnt desc";
			}
			elseif ($_REQUEST["crt"]=="M4")
			{
				$pheader="Top Rated";
				$field="rating desc";
			}
			elseif ($_REQUEST["crt"]=="M5")
			{
				$pheader="Top Favorites";
				$field="favcnt desc";
			}

			$framework->tpl->assign("CRT",$_REQUEST["crt"]);
			$framework->tpl->assign("PH_HEADER", $pheader);
			list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, $field,0,$stxt);
		}
		else
		{
			//print_r($par);exit;
			$framework->tpl->assign("PH_HEADER", "All Photos");
			if($_REQUEST["user_id"]){
				//checkLogin();
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt);
				
			}else{
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt);
			}
		}
		//$pht=0;
		for ($i=0;$i<sizeof($rs);$i++)
		{
			$medet=$objUser->getUsernameDetails($rs[$i]->username);
			if($medet["user_id"]==$_SESSION["memberid"]){
				$rs[$i]->owner="Y";
			//	$pht++;
			}
			$rs[$i]->nick_name=$medet["nick_name"];
			$rs[$i]->mem_type=$medet["mem_type"];
			if($global['show_private']=='Y'){
				if($rs[$i]->privacy=='private'){
					if($rs[$i]->friends_can_see=='Y'){
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true){
							$rs[$i]->show_photo="Y";
							//$pht++;
						}else{
							$rs[$i]->show_photo="N";
							//$count--;
						}
					}else{
						$rs[$i]->show_photo="N";
						//$count--;
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$rs[$i]->show_photo="Y";
						//$pht++;
					}
				}else{
					$rs[$i]->show_photo="Y";
					//$pht++;
				}
				if($medet["mem_type"]==3){
					$medet1=$objUser->getUserdetails($medet["user_id"]);
					if($medet1["friends_can_see"]=='Y'){
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true){
							$rs[$i]->show_profile="Y";
						}else{
							$rs[$i]->show_profile="N";
						}
					}else{
						$rs[$i]->show_profile="N";
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$rs[$i]->show_profile="Y";
					}
				}else{
					$rs[$i]->show_profile="Y";
				}
			}
		}
		
		### code using to show the pagination .....
		### Author : Jipson Thomas.................
		### Date   :08/01/2008.....................
			if($global["show_private"]=="Y"){
				if($_REQUEST["cat_id"]){
				 	$ctid=$_REQUEST["cat_id"];
				 }else{
				 	$ctid=0;
				}
				if(!$field)
					$field="id desc";
				list($res, $numpades) = $objPhoto->photoList('','500000', $par, OBJECT,$field,$ctid,$stxt);
				
				$cnt=0;
				for ($i=0;$i<sizeof($res);$i++)
					{
						if($res[$i]->privacy=='private'){
							if($res[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($res[$i]->user_id,$_SESSION["memberid"])==true){
									$cnt++;
								}
							}
							if($res[$i]->user_id==$_SESSION["memberid"]){
								$cnt++;
							}
						}else{
							$cnt++;
						}
					}
				if(count($res)>0){
				
					$pos=strpos($numpad,"|");
					$stf=substr($numpad,0,$pos);
					$stl=stristr($numpad,":");
					$stl1=stristr($stl,"&nbsp; &nbsp;<font color=black>|</font>&nbsp; &nbsp;");
					$npd=$stf."<font color=black>|</font>&nbsp; &nbsp;Matching Records : $cnt".$stl1;
					$framework->tpl->assign("NUMPAD", $npd);
					
				}
			}
			
		###  End....................................
		
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/photo_list.tpl");
		$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/photo_list_left.tpl");
		break;
	case "details":
		$framework->tpl->assign("TITLE_HEAD", "Photo Details");
		if($_REQUEST["fn"]!="share")
		{
			$objPhoto->incrementView($_REQUEST["photo_id"], "album_photos");
		}
		else
		{
			checkLogin();
			$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
			if($global["new_album_functions"]==1){
				$rs = $objUser->ViewFriends($_SESSION['memberid']);
				$framework->tpl->assign("CONTACT",$rs);
				//print_r($rs);exit;
			}else{
				$contact  = $objUser->listContacts($userinfo["username"]);
				$framework->tpl->assign("CONTACT",$contact);
			}
			$framework->tpl->assign("USERDET",$userinfo);
		}
		if($_REQUEST["fn"]=="subscribe")
		{
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"]);
			$medet=$objUser->getUsernameDetails($phdet["username"]);
			
			////////////////to check to display the profile/////////////////////////////
		                ###to list private users Profile on the page
						### Modified on & Jan 2008.
		                ### Modified By Jinson.
		if($global['show_private']=='Y')
						{
					     
							        
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$phdet["view_profile"]="Y";}
															 else{$phdet["view_profile"]="N";}
										                         }
																			else{
															$phdet["view_profile"]="N";
																				}
																				
																				
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $phdet["view_profile"]="Y";
					                                                             }
																				
																 }
																  else{$rs[$i]->show_profile="Y";}
																	
						
						}
			    //////////////////////////////////////////		
			
					
			//print_r();exit;
			checkLogin();
			$res=$album->Subscribe($_SESSION["memberid"],$phdet["user_id"],"photo");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}elseif($_REQUEST["fn"]=="unsubscribe")
		{
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"]);
			checkLogin();
			$res=$album->Unsubscribe($_SESSION["memberid"],$phdet["user_id"],"photo");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
		checkLogin();
			if ($_REQUEST["fn"]=="share")
			{
				$arr=explode(",",$_POST["friends"]);
				for($i=0;$i<sizeof($arr);$i++){
					if($global["show_screen_name_only"]=="1"){
						$fdet=$objUser->getUnamebyscreenname($arr[$i]);
						$arruname[$i]=$fdet["username"];
						
					}
				}
				if($global["show_screen_name_only"]=="1"){
					$arr=$arruname;
				}
				for($i=0;$i<sizeof($arr);$i++)
				{	if($arr[$i])
				{
					if(!$objUser->validUsername($arr[$i]))
					{
						if($invalid!='')
						{
							$invalid=$invalid."<br>".$arr[$i]." (Unknown user)";
						}
						else
						{
							$invalid=$arr[$i]." (Unknown user)";
						}
					}
				}
				}
				$arr1=explode(",",$_POST["email"]);
				for($i=0;$i<sizeof($arr1);$i++)
				{	if($arr1[$i])
				{

					if(!checkEmail($arr1[$i]))
					{
						if($invalid!='')
						{
							$invalid=$invalid."<br>".$arr1[$i]." (Invalid Email Id)";
						}
						else
						{
							$invalid=$arr1[$i]." (Invalid Email Id)";
						}
					}
				}
				}

				if($invalid=='')
				{
					for($i=0;$i<sizeof($arr);$i++)
					{	if($arr[$i])
					{
						if($objUser->validUsername($arr[$i]))
						{

							$arrData  = array();

							$arrData["from"]     = $userinfo["username"];
							$arrData["to"]       = $arr[$i];
							$arrData["subject"]  = $_REQUEST["subject"];
							$arrData["datetime"] = date("Y-m-d G:i:s");
							$arrData["status"]   = "U";
							$touser   = $objUser->getUsernameDetails($arr[$i]);
							$touserid = $touser["id"];
							$phid    = $_REQUEST["photo_id"];
							$from     = $userinfo["username"];
							$comment = $_REQUEST["comments"]."<br><br>";
							$comment = $comment."Click on the link below to view the photo<br>";
							$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=details&photo_id=$phid")."\">View Photo</a>";


							$arrData["comments"] = $comment;
							$objUser->setArrData($arrData);
							$objUser->sendMessage(1);

						}

					}
					}

					for($i=0;$i<sizeof($arr1);$i++)
					{	if($arr1[$i])
					{
						$arrData  = array();

						$arrData["from"]     = $userinfo["username"];
						$arrData["to"]       = $arr1[$i];
						$arrData["subject"]  = $_REQUEST["subject"];

						$touser   = $objUser->getUsernameDetails($arr1[$i]);
						$touserid = $touser["id"];
						$phid    = $_REQUEST["photo_id"];
						$from     = $userinfo["username"];

						//$comment = $_REQUEST["comments"]."<br><br>";

						$message="<div style='padding-left: 25px; padding-right: 25px;'>";
						$message=$message."<h2>I want to share the following Photo with you</h2>";
						$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=details&photo_id=$phid")."\"><img src='".SITE_URL ."/modules/album/photos/thumb/$phid.jpg' border='0'></a>";
						$message=$message."<h4>Personal Message</h4>";
						$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
						$message=$message."<p>Thanks,<br>";
						$message=$message. $userinfo["first_name"]. " " . $userinfo["last_name"] . "</p>";
						$message=$message."</div>";
				if($global["inner_change_reg"]=="yes")
							{
							mimeMail($arrData["to"],$arrData["subject"],$message,'','','<Link54.com'.$framework->config['admin_email'].'>');
							}else
							{		
						
				mimeMail($arrData["to"],$arrData["subject"],$message,'','','<Industrypage.com'.$framework->config['admin_email'].'>');
						//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
					}}
					}

					redirect(makeLink(array("mod"=>"album", "pg"=>"photo"), "act=details&photo_id=".$_REQUEST["photo_id"]));
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$invalid);
				}
			}
			else
			{
				$_POST["type"]     = "photo";
				$_POST["user_id"]  = $_SESSION["memberid"];
				$_POST["file_id"] = $_REQUEST["photo_id"];
				$_POST["postdate"] = date("Y-m-d G:i:s");
				unset($_POST["x"],$_POST["y"]);
				$objPhoto->setArrData($_POST);
				$objPhoto->postComment();
			}
		}
		if($_REQUEST["rate"])
		{
			checkLogin();
			if($global["new_album_functions"]==1){
				$msg=$album->AddRating('album_photos',$_REQUEST["photo_id"],'photo',$_REQUEST["rate"],$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}else{
				$array=array();
				$array["type"]    = "photo";
				$array["file_id"] = $_REQUEST["photo_id"];
				$array["userid"]  = $_SESSION["memberid"];
				$array["mark"]    = $_REQUEST["rate"];
				$objPhoto->setArrData($array);
				if(!$objPhoto->ratePhoto())
				{
					$framework->tpl->assign("MESSAGE",$objPhoto->getErr());
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"photo"),"act=list"));
				}
			}
		}
		if($_REQUEST["fn"]=="add")
		{
			checkLogin();
			if($global["new_album_functions"]==1){
				$msg=$album->AddFavourites('album_photos',$_REQUEST["photo_id"],'photo',$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}else{
				$array=array();
				$array["type"]    = "photo";
				$array["file_id"] = $_REQUEST["photo_id"];
				$array["userid"]  = $_SESSION["memberid"];
				$objPhoto->setArrData($array);
				if(!$objPhoto->addFavorite())
				{
					$framework->tpl->assign("MESSAGE",$objPhoto->getErr());
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=favr&crt=M2&tpm=5"));
				}
			}
		}
		if($global["new_album_functions"]==1){
			$rate=$album->GetRatingNew('album_photos',$_REQUEST["photo_id"],'photo');
			$framework->tpl->assign("RATE",$rate);
		}else{
			if($rate=$objPhoto->getRating('photo',$_REQUEST["photo_id"]))
			{
				$framework->tpl->assign("RATE",$rate);
			}
		}

		$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"]);

		list($width, $height, $type, $attr) = getimagesize(SITE_PATH."/modules/album/photos/resized/$phdet[id]$phdet[img_extension]");
		if($height>450){
			$phdet["imgheight"]=450;
		}else{
			$phdet["imgheight"]=$height;
		}
		if($width>450){
			$phdet["imgwidth"]=450;
		}else{
			$phdet["imgwidth"]=$width;
		}
		$medet=$objUser->getUsernameDetails($phdet["username"]);
		$phdet["nick_name"]=$medet["nick_name"];
		$phdet["mem_type"]=$medet["mem_type"];
		if($phdet["user_id"]==$_SESSION["memberid"]){
				$framework->tpl->assign("OWNER","YES");
		}
		$framework->tpl->assign("PHDET",$phdet);
		if($memberID){
			$sub=$album->chkSubscribe($memberID,$phdet["user_id"],"photo");
			$framework->tpl->assign("SUBS",$sub);
		}
		$cmCount=$objPhoto->getCommentCount($_REQUEST["photo_id"]);
		$framework->tpl->assign("COMMENT_COUNT",$cmCount["cnt"]);

		list($rs, $numpad) = $objPhoto->commentList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&photo_id=".$_REQUEST["photo_id"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["photo_id"]);
//print_r($rs);exit;
 ###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($rs);$i++)
										{
										$medet=$objUser->getUsernameDetails($rs[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$rs[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$rs[$i]->show_profile="Y";}
															 else{$rs[$i]->show_profile="N";}
										                         }
																			else{
															$rs[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $rs[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$rs[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////




	
		$framework->tpl->assign("COMMENT_LIST",$rs);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
		if($_REQUEST["fn"]=="share")
		{
			$framework->tpl->assign("MEDIA","Photo");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/share_media.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/share_media_left.tpl");
		}
		else if($global['profile_inner']=='Y')
		 {
			 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/photo_details.tpl");
			
		 }
		else
		{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/photo_details.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/photo_details_left.tpl");
		}
		break;

	case "photolist":

		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],15,$par,ARRAY_A,'','default_img','0',$_REQUEST['view'],'>');
		$framework->tpl->assign("VIDEO_LIST",$rs);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_list.tpl");

		break;
	case "setDisp":
		checkLogin();

		$id = $_REQUEST['id'];
		$pdet = $objPhoto->getPhotoDetails($id);
		$img_ext = $pdet['img_extension'];
		$mem_id = $_SESSION['memberid'];
		$source = SITE_PATH."/modules/album/photos/";
		$dest   = SITE_PATH."/modules/member/images/userpics/";
		$arr = array();
		$arr['image'] = "Y";
		$arr['id']    = $mem_id;
		$objUser->setArrData($arr);
		$objUser->update();
		copy($source.$id.$img_ext,$dest.$mem_id.".jpg");
		copy($source."/thumb/".$id.$img_ext,$dest."thumb/".$mem_id.".jpg");
		copy($source."/thumb/".$id."_thumb2".$img_ext,$dest."thumb/".$mem_id."_thumb2.jpg");
		setMessage("Your display image has been changed",MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum&crt=M2"));
		break;
	case "update_default":
		$flyer_id	=	$_REQUEST['flyer_id'];
		$propid		=	$_REQUEST['propid'];
		$default_img = isset($_REQUEST['default_img'])? $_REQUEST['default_img'] : "" ;
		$album->updateAlbum(array("default_img" => $default_img),$_REQUEST['propid']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"video"),"act=upload&flyer_id=$flyer_id&propid=$propid"));
		break;	
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>

