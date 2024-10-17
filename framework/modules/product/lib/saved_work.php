<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
$email			= 	new Email();
$objCategory	=	new Category();
$objProduct		=	new Product();
$objPrice		=	new Price();
$objCombination	=	new Combination();
$objMade		=	new Made();
$objAccessory	=	new Accessory();
$cart 			= 	new Cart();
$user_id		=	$_SESSION['memberid'];
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "12";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
if(empty($limit))
	$_REQUEST["limit"]		=	"12";
switch($_REQUEST['act']) {
	case "saved_product":
		header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT'); header('Cache-Control: Private');
	  checkLogin();		
	list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listSaveProduct($pageNo,$limit,$param,OBJECT, $orderBy,$user_id);
		$framework->tpl->assign("PRODUCTS", $rs);		
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);			
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_saved_product.tpl");
	break;	
	
	case "download_files":
		$product_id		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($product_id)); 
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/download_files.tpl");
	break;
	case "save_pdf":
		$download_file		= 	$_REQUEST["file"] ? $_REQUEST["file"] : "";
		header("Cache-Control: public, must-revalidate");
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="pdfdocument.pdf"');
		@readfile($download_file); 
		exit;		
	break;
	case "save_psd":
		$download_file		= 	$_REQUEST["file"] ? $_REQUEST["file"] : ""; 
		header("Cache-Control: public, must-revalidate");
		header('Content-type: application/psd');
		header('Content-Disposition: attachment; filename="psdfile.psd"');
		@readfile($download_file); exit;
	break;
	case "save_ai":
		$download_file		= 	$_REQUEST["file"] ? $_REQUEST["file"] : "";
		header("Cache-Control: public, must-revalidate");
		header('Content-type: application/ai');
		header('Content-Disposition: attachment; filename="ai_image.ai"');
		@readfile($download_file); exit;
	break;	
	case "saved_art":
	  checkLogin(); 
		if($_REQUEST['delete']=="delete" && $_REQUEST['id']>0) {	
			$user_id = $_SESSION['memberid'];
			$id=$_REQUEST['id'];
			$del_status = $objProduct->delSavedWork($id,$user_id);
		}
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listSaveArt($pageNo,12,$param,OBJECT, $orderBy,$user_id);
		$framework->tpl->assign("PRODUCTS", $rs);	
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);
		#---------------------------------
		list($paddleRs, $paddleNumpad, $paddleCnt, $paddleLimitList)	= 	$objProduct->listSavedPaddles($pageNo,12,$param,OBJECT, "",$user_id);
		foreach($paddleRs as $key=>$val){
			$parent_id = $val[parent_id];
			if($parent_id>0){
				$rs_prod	=	$objProduct->ProductGet($parent_id);
				if($rs_prod[dual_side]!="Y")	$rs_prod[dual_side]="N";
				$paddleRs[$key][dual_side] = $rs_prod[dual_side];
			}

		}
		$framework->tpl->assign("PADDLE", $paddleRs);	
		$framework->tpl->assign("PADDLE_NUMPAD", $paddleNumpad);
		$framework->tpl->assign("PADDLE_LIMIT", $paddleLimitList);
		#---------------------------------
		//if(isset($_POST['buy_now'])) {	
		if(isset($_REQUEST['parent_id'])) {			
			$_REQUEST['user_id'] = $_SESSION['memberid'];
			$parent_id	=	$_REQUEST['parent_id']; 
			$rs	=	$objProduct->ProductGet($parent_id);
			$_REQUEST['product_id'] = $rs['id']; 
			$_REQUEST['product_price'] = $rs['price']; 
			$_REQUEST['total_price'] = $rs['price']; 
			$_REQUEST['store_id'] = ""; 
			$_REQUEST['notes'] = ""; 
			$_REQUEST['contact_me'] = ""; 
			$_REQUEST['qty'] = 1;
			if ($global["cart_restricted_amt"] == "Y"){
				$status = $cart->addToCart($_REQUEST);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view&status=$status"));	
			}else{
				$cart->addToCart($_REQUEST);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));	
			}
		}	
		$framework->tpl->assign("BUYBUTTON", createButton("BUY NOW","","check()"));
		$framework->tpl->assign("BUTTONURL", BUTTONURL);	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_saved_art.tpl");
	break;
	case "product_desc":
		header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT'); header('Cache-Control: Private');
		$pro_id	=	$_REQUEST['id'];
		$workArr	=	$objProduct->getSavedWork($_REQUEST['id']);
		$pro_type	=	$workArr['product_type'];		
		if($pro_type=='P'){
			$proSave		=	$objProduct->ProductGet($workArr['pro_save_id']);
			$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($workArr['parent_id']));
		}
		if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['btn_save'])||isset($_POST['btn_publish']))) {	
			$parent_id	  	=	 $_REQUEST['parent_id'];
			$parCat			=	$objProduct->getParentCat($parent_id);			
			for($i=0;$i<sizeof($parCat);$i++){			
				$catArray[$i]=$parCat[$i]['category_id'];
			}
			if($_REQUEST['btn_publish']!=''){
				$_REQUEST['active']	='Y';
			}else{
				$_REQUEST['active']	='N';
			}			
			$_REQUEST['category']	  =	 $catArray;
			$req 					  =	&$_REQUEST;	
			unset($req['id']);						
			$status	=	$objProduct->ProductAddEdit($req,'','');		
			if($status['status']==true){				
				copy(SITE_PATH."/modules/product/images/saved_work/".$pro_id.".jpg",SITE_PATH."/modules/product/images/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				//upload listing images
				$new_listname		=	$status['id']."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$new_listname);
				$new_name		=	$status['id']."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$product_id=$status['id'];
				//orginal thumb
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,66,58,$mode,$new_name);														
				$action = $req['id'] ? "Updated" : "Added";
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$status['id']));
			}
		}
		$framework->tpl->assign("PRO_WRK_ID",$pro_id);
		$framework->tpl->assign("WORK_DETAIL",$workArr);
		$framework->tpl->assign("SAVE_DETAIL",$proSave);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/saved_workdetails.tpl");
	break;
	 case "product_art":	
		$pro_id		=	$_REQUEST['id'];
		$workArr	=	$objProduct->getSavedWork($_REQUEST['id']);
		$pro_type	=	$workArr['product_type'];
		$acccat		=	$workArr['pro_art_category'];		
		if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_save'] || $_POST['btn_publish']) ) {	
			$parent_id	  	=	$_REQUEST['parent_id'];			
			$category		=	$_REQUEST['category'];	
			$catArr			=	explode('#',$category);
			$accessory_category	=	$catArr;
			if($_REQUEST['btn_publish']){
				$_REQUEST['active']			=  'Y';	
			}else{
				$_REQUEST['active']			=  'N';	
			}
			$_REQUEST['adjust_price']		=	$_REQUEST['price'];				 	
			$req 					  		=	&$_REQUEST;				
			$status		=	$objAccessory->accessoryAddEdit($req,'','');	
			if($status['status']==true){			
				copy(SITE_PATH."/modules/product/images/saved_work/".$pro_id.'.jpg',SITE_PATH."/modules/product/images/accessory/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/accessory/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				//upload listing images
				$new_listname		=	$status['id']."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);	
			}		
		}		
		$framework->tpl->assign("WORK_DETAIL",$workArr);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/saved_artwork.tpl");
	break;
	
	
	
	}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>