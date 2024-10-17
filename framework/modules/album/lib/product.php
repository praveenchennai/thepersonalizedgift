<?php
    session_start();
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
	include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
	include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
	include_once(FRAMEWORK_PATH."/modules/chat/lib/src/phpfreechat.class.php");
	include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

	
	$objCms = new Cms();
	$album= new Album();
	$objPhoto=new Photo();
	$objUser=new User();
	$music=new Music();
	$email	 = new Email();
	$categ = new Category();
if(isset($_SESSION['chps1']))
	{
	 $framework->tpl->assign("chps1",$_SESSION['chps1']);
	}
	
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}


$memberID = $_SESSION['memberid'];
$usr=$objUser->getUserDetails($_SESSION["memberid"]);
$_REQUEST +=$usr;
//	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
//if($global["sort_by_category_name"]=="1")
//	$catarr=$objUser->getCategoryArr($_REQUEST["mod"],1);
//else
//	$catarr=$objUser->getCategoryArr($_REQUEST["mod"]);	
//	$framework->tpl->assign("CAT_ARR", $catarr);

	//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	//list($catlist,$numcat)=$categ->getSubcategories('46','','50');
	// print_r($catlist);exit;
	list($catlist,$numcat)=$categ->getSubcategories('44','','45');
	$framework->tpl->assign("CAT_LIST", $catlist);	
	$framework->tpl->assign("CAT_ARR", $catlist);
	$framework->tpl->assign("advertisement",1);
	$framework->tpl->assign("inner_content",1);
	
	
	switch($_REQUEST['act']) 
	{
		case "upload":
		
			checkLogin();
			
			$framework->tpl->assign("LEFTBOTTOM","upload_product" );
			if($_REQUEST["product_id"]){
				$phdet=$objPhoto->getPhotoDetails($_REQUEST["product_id"],"album_products","product");
				$_REQUEST +=$phdet;
								//print_r($_REQUEST);exit;

			}

			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["image_x"],$_POST["image_y"],$_POST["Submit"]);
				$_POST["postdate"]=date("Y-m-d G:i:s");
				$_POST["user_id"]=$_SESSION["memberid"];

				/*
				check the image format
				*/
				$photoArr = $objPhoto->chekUploadImageValidation();
				if ($photoArr != "error")
				{
				/**/
					$title   = $_POST["title"];
					$photoid = $_POST['product_id'];
					
					$default_img = isset($_POST['default_img'])? $_POST['default_img'] : "" ;
					unset($_POST["title"],$_POST['submit'],$_POST['product_id'],$_POST['default_img']);

					
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
						
						$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],$photoArr[$i],$updateid,"album_products",'product');
						
					}
					
					if(isset($photoid))
					{
						for($i=0;$i<count($photoid);$i++)
						{
							$_POST["title"] = $title[$i];
							$objPhoto->setArrData($_POST);
							$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],'',$photoid[$i],'',"album_products",'product');

						}
						
					}
					
					### End Portion to send mail to subscribers..............................................
					setMessage("Product have been updated successfully",MSG_SUCCESS);
					
					if($global["show_property"] == 1)
						redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&&propid={$_REQUEST['propid']}&view=edit"));
					else
						redirect(makeLink(array("mod"=>"album", "pg"=>"product"),"act=list&crt=M1"));					
				}
			}
			
			$rs = $album->propertyList("album_products","product",0,$_REQUEST["propid"],$_SESSION["memberid"]);
			$framework->tpl->assign("PROP_DETAILS",$album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]));
			$framework->tpl->assign("PHOTO_LIST",$rs);
			$framework->tpl->assign("SECTION_LIST", $album->albumSectionList()); 
						
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_products.tpl");
			break;
		case "delete":
			$msg = $album->mediaDelete($_REQUEST['photoid'], $_REQUEST['crt']);
			setMessage("Product deleted Successfully",MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"album", "pg"=>"product"), "act=upload&propid=".$_REQUEST["propid"])."&crt=M2&suc=del");
			break;
		case "edit":
			checkLogin();
			$framework->tpl->assign("LEFTBOTTOM","upload_product" );
			if($_REQUEST["product_id"]){
				$phdet=$objPhoto->getPhotoDetails($_REQUEST["product_id"],"album_products","product");
				$_REQUEST +=$phdet;
			}
			//print_r($_REQUEST);exit;
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
				if($_FILES['photoImg']['size'][0] > 0)
					$photoArr = $objPhoto->chekUploadImageValidation();
				if ($photoArr != "error")
				{
					$title   = $_POST["title"];
					$photoid = $_POST['product_id'];
					$default_img = isset($_POST['default_img'])? $_POST['default_img'] : "" ;
					unset($_POST["title"],$_POST['submit'],$_POST['product_id'],$_POST['default_img']);
					if(count($title) == 1)
						$_POST["title"] =  $title[0];
					else
						$_POST["title"] =  $title[$photoArr[0]];
					$updateid = $photoid;
					$objPhoto->setArrData($_POST);
					$objPhoto->uploadPhoto('',$_REQUEST['crt'],$photoArr[0],$updateid,"album_products",'product',$album);
				}		
			redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum&crt=M5"));					
			
			}
			$rs = $album->propertyList("album_products","product",0,$_REQUEST["propid"],$_SESSION["memberid"]);
			$framework->tpl->assign("PROP_DETAILS",$album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]));
			$framework->tpl->assign("PHOTO_LIST",$rs);
			$framework->tpl->assign("SECTION_LIST", $album->albumSectionList()); 
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/edit_products.tpl");
			break;
			
		case "list":
		if (!$_REQUEST["mem_type"])
		{
              
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
		else
		{
		
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
        $framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->assign("chatsection",1);
		$framework->tpl->assign("SESS_NUMPAD",$numpad);
		$framework->tpl->assign("SESS_LIMIT",$sess_limit);
		$framework->tpl->assign("LEFTBOTTOM",'swapshop');
		$framework->tpl->assign("LIMIT_LIST",$sess_limit);
		//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/myhome.tpl");
		/*////////////////// social community chat users
		  if($global["inner_change_reg"]=="yes")
							{
							$framework->tpl->assign("CHAT",$chat->printChat());
		                    $framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");
							
							}*/
 
			$framework->tpl->assign("TITLE_HEAD", "Product List");
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
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5, $par, OBJECT, "id desc",$_REQUEST["cat_id"],$stxt,'','','','album_products','product');				
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
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, $field,0,$stxt,'','','','album_products','product');
			}	
			else
			{
				//print_r($par);exit;
				$framework->tpl->assign("PH_HEADER", "All Products");
				if($_REQUEST["user_id"]){
					//checkLogin();
					list($rs, $numpad) =$objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt,'','','','album_products','product');
				}else{
					list($rs, $numpad) =$objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt,'','','','album_products','product');
				}
			}	
			for ($i=0;$i<sizeof($rs);$i++)	
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->nick_name=$medet["nick_name"];
				$rs[$i]->mem_type=$medet["mem_type"];
				if($medet["user_id"]==$_SESSION["memberid"]){
					$rs[$i]->owner="Y";
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

				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/product_list.tpl");
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/product_list_left.tpl");		
				break;
		case "details":
			$framework->tpl->assign("TITLE_HEAD", "Product Details");
			if($_REQUEST["fn"]!="share")
			{
				$objPhoto->incrementView($_REQUEST["photo_id"], "album_products","product");
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
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"], "album_products","product");
				checkLogin();
				$res=$album->Subscribe($_SESSION["memberid"],$phdet["user_id"],"product");
				//print_r($res);exit;
				if($res!="true"){
					setMessage($album->getErr());	
				}
			}elseif($_REQUEST["fn"]=="unsubscribe")
			{
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"], "album_products","product");
				checkLogin();
				$res=$album->Unsubscribe($_SESSION["memberid"],$phdet["user_id"],"product");
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
									$comment = $comment."Click on the link below to view the product<br>";
									$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=details&photo_id=$phid")."\">View Product</a>";
									

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
									$message=$message."<h2>I want to share the following Product with you</h2>";
									$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"product"), "act=details&photo_id=$phid")."\"><img src='".SITE_URL ."/modules/album/productss/thumb/$phid.jpg' border='0'></a>";
									$message=$message."<h4>Personal Message</h4>";
									$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
									$message=$message."<p>Thanks,<br>";
									$message=$message. $userinfo["first_name"]. " " . $userinfo["last_name"] . "</p>";
									$message=$message."</div>";
									
									
									
									mimeMail($arrData["to"],$arrData["subject"],$message,'','','link54.com <'.$framework->config['admin_email'].'>');
									//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
							}	
						}
						
						redirect(makeLink(array("mod"=>"album", "pg"=>"product"), "act=details&photo_id=".$_REQUEST["photo_id"]));
					}	
					else
					{
						$framework->tpl->assign("MESSAGE",$invalid);
					}
				}elseif($_REQUEST["fn"]=="sendmail"){
					$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"], "album_products","product");
					checkLogin();
					$arrData  = array();
					$un=$objUser->getUserdetails($phdet["user_id"]);
					//$_REQUEST["uname"]=$un["username"];
					$from  = $objUser->getUserdetails($_SESSION["memberid"]);
					$arrData["from"]=$from["username"];
					$arrData["datetime"]=date("Y-m-d G:i:s");
					$arrData["status"]="U";
					$arrData["to"]=$un["username"];
					$arrData["subject"]="Mail Regarding your Product ".$phdet["title"];
					$arrData["comments"]=$_POST["body"];
					//unset($_POST["x"],$_POST["y"]);
					
					//print_r($arrData);exit;
					$objUser->setArrData($arrData);
					$msid=$objUser->sendMessage();
					if($msid){
						$album->AddMessageFile("album_products",$_REQUEST["photo_id"],"product",$msid);
						setMessage("Your mail has been sent");
					}
					//$res=$album->Unsubscribe($_SESSION["memberid"],$phdet["user_id"],"product");
					//print_r($res);exit;
					//if($res!="true"){
					//	setMessage($album->getErr());	
					//}
				}
				else
				{
					$_POST["type"]     = "product";
					$_POST["user_id"]  = $_SESSION["memberid"];
					$_POST["file_id"] = $_REQUEST["photo_id"];
					$_POST["postdate"] = date("Y-m-d G:i:s");
					unset($_POST["x"],$_POST["y"],$_POST["body"],$_POST["fn"]);
					$objPhoto->setArrData($_POST);
					$objPhoto->postComment();
				}	
			}
			if($_REQUEST["rate"])
			{
				checkLogin();
				$msg=$album->AddRating('album_products',$_REQUEST["photo_id"],'product',$_REQUEST["rate"],$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}
			if($_REQUEST["fn"]=="add")
			{
				checkLogin();
				$msg=$album->AddFavourites('album_products',$_REQUEST["photo_id"],'product',$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}
			$rate=$album->GetRatingNew('album_products',$_REQUEST["photo_id"],'product');
			$framework->tpl->assign("RATE",$rate);
			
			$phdet=$objPhoto->getPhotoDetails($_REQUEST["photo_id"],"album_products","product");
			$medet=$objUser->getUsernameDetails($phdet["username"]);
			////////////////to check to display the profile/////////////////////////////
			###to list private users Profile on the page
			### Modified on & Jan 2008.
			### Modified By Jipson.
			if($global['show_private']=='Y')
			{
				if($medet["mem_type"]==3){
					if($medet["friends_can_see"]=='Y')
					{
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
							{
								$phdet["view_profile"]="Y";
							}
						else{
							$phdet["view_profile"]="N";
						}
					}
					else{
						$phdet["view_profile"]="N";
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$phdet["view_profile"]="Y";
					}
				}
				else{
					$rs[$i]->show_profile="Y";
				}
			}
		//////////////////////////////////////////	
			
			 list($width, $height, $type, $attr) = getimagesize(SITE_PATH."/modules/album/products/resized/$phdet[id]$phdet[img_extension]");
			
			if($height>300){
				$phdet["imgheight"]=300;
			}else{
				$phdet["imgheight"]=$height;
			}
			if($width>300){
				$phdet["imgwidth"]=300;
			}else{
				$phdet["imgwidth"]=$width;
			}
			$medet=$objUser->getUsernameDetails($phdet["username"]);
			$phdet["nick_name"]=$medet["nick_name"];
			$phdet["mem_type"]=$medet["mem_type"];
			$framework->tpl->assign("PHDET",$phdet);
			if($memberID){
				$sub=$album->chkSubscribe($memberID,$phdet["user_id"],"product");
				$framework->tpl->assign("SUBS",$sub);
			}
			$cmCount=$objPhoto->getCommentCount($_REQUEST["photo_id"],"album_products","product");
			$framework->tpl->assign("COMMENT_COUNT",$cmCount["cnt"]);
			if($_REQUEST['pageNo']){
					$pagno=$_REQUEST['pageNo'];
				}else{
					$pagno=1;
				}
			if($_REQUEST['next2'])
				$pagno =1;
			else
				$pagno = $_REQUEST['pageNo'];
			list($rs, $numpad) = $objPhoto->commentList($pagno, 5, "mod=$mod&pg=$pg&photo_id=".$_REQUEST["photo_id"]."&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["photo_id"],"album_products","product");
			if($phdet["user_id"]==$_SESSION["memberid"]){
				$framework->tpl->assign("OWNER","YES");
				$param="mod=$mod&pg=$pg&next2=msg&photo_id=".$_REQUEST["photo_id"]."&act=".$_REQUEST['act'];
				list($msg, $numpad1)=$album->getmessageOnFile($_REQUEST['pageNo'],5,$param,OBJECT, $_REQUEST['orderBy'],$_REQUEST["photo_id"],"album_products","product");
				$framework->tpl->assign("MSGS",$msg);
				$framework->tpl->assign("MSGS_NUMPAD", $numpad1);
			}
			
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
			//print_r($rs);exit;
			$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
			if($_REQUEST["fn"]=="share")
			{
				$framework->tpl->assign("MEDIA","Product");
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/share_media.tpl");	
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/share_media_left.tpl");
			}	
			else
			{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/product_details.tpl");	
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/product_details_left.tpl");		
			}
			break;
			
		case "productlist":
		
			$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
			list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],15,$par,ARRAY_A,'','default_img','0',$_REQUEST['view'],'>');
			$framework->tpl->assign("VIDEO_LIST",$rs);
			$framework->tpl->assign("NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_list.tpl");
			
			break;
	}		
	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>