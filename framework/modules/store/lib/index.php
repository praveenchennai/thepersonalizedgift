<?php 
/**
 * Admin Store
 *
 * @author sajith
 * @package defaultPackage
 */

//authorize();

 if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","store_index") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","index") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.template.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

$template = new Template();
$email			= 	new Email();
$store 			= 	new Store();
$objProduct		=	new Product();
$objUser		=	new User();
$objCategory	=	new Category();






switch($_REQUEST['act']) {
    case "list":
	
    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
		if ($storename){
        list($rs, $numpad, $cnt, $limitList) = $store->storeList_User($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", $storename, OBJECT, $_REQUEST['orderBy']);
		}else{
		list($rs, $numpad, $cnt, $limitList) = $store->storeList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", OBJECT, $_REQUEST['orderBy']);
		}		
        $framework->tpl->assign("STORE_LIST", $rs);
        $framework->tpl->assign("STORE_NUMPAD", $numpad);
        $framework->tpl->assign("STORE_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_list.tpl");
        break;
    case "mstore": 
	
        if($_SERVER['REQUEST_METHOD'] == "POST") {
		$id=0;
		$store->logoupload($id);
		$arr = array("page_title" => $_REQUEST['page_title'], "meta_description" => $_REQUEST['meta_description'], "meta_keywords" => $_REQUEST['meta_keywords']);
		$store->SetMainstore($arr);
		//logoupload
		
		//setMessage("Logo uploaded Successfully", MSG_SUCCESS);
		setMessage("Main Store Details updated Successfully", MSG_SUCCESS);

		}
		$mstore = $store->GetMainstore();
		$framework->tpl->assign("MSTORE", $mstore);
	
	  $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/main_store.tpl");
	break;
    case "form":
	
	 include_once(SITE_PATH."/includes/areaedit/include.php");

			
			editorInit('content');
			
        if($_SERVER['REQUEST_METHOD'] == "POST") {
		// include_once(SITE_PATH."/includes/areaedit/include.php");
		  //editorInit('content');
		
            $req = &$_REQUEST;
									
									
									
			if($req['member_id'])
			 		{												
					
				if( ($message = $store->storeAddEdit2($req, $sId)) > 0 ) {
				
						$action = $req['id'] ? "Updated" : "Added";
						setMessage("Store information $action Successfully. Select a template for the store to Proceed", MSG_SUCCESS);
				   //redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list&sId=$sId&fId=$fId"));
				   //for sending emails
						  if($req['id'])
						  {				  
								$flag=1;
						}else
						{
						$flag=0;
						}
							$userArray=$objUser->getUserdetails($req['member_id']);
							$storeArray=$store->storeGet($message);
							 if($flag==0)
							{
							$mailFrom= $store->getAdminEmail();
							$mail_header	=	array(	"from"	=>	 $mailFrom,
														"to"	=>	$userArray['email']);
														
							$dynamic_vars 	=	array(	"USER_NAME"		=>	$userArray['username'],	
															"PASSWORD"	=>	$userArray['password'],
															"STORE_LINK"			=>	"<a href='".SITE_URL.'/'.$storeArray['name']."'/> Click here to view your Store </a>",
															"STORE_LINK_MANAGE"		=>	"<a href='".SITE_URL.'/'.$storeArray['name']."/manage"."'/>Click here to view your Store Admin </a>"
														);																																												
							$email->send('store_creation_confirmation', $mail_header, $dynamic_vars);
								
							}
							//end of code for sending emails
					   if($_REQUEST['manage']=='manage')
									redirect(makeLink(array("mod"=>"store", "pg"=>"store_index"), "act=template_list&sId=$sId&fId=$fId&store_id=".$message));
								else
								
									redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=template_list&sId=$sId&fId=$fId&store_id=".$message));
							
					  // redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=template_list&sId=$sId&fId=$fId&store_id=".$message));
				}
				 setMessage($message);
			}
			
          
        }
		
		
		$objCategory->getCategoryTree1($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		$framework->tpl->assign('CATEGORY', $catArr1);
		$framework->tpl->assign('SELECTEDIDS', $store->GetAllStoreCategoty($_REQUEST['id']));
		
        include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
        $user = new User();

        if($message) {
            $framework->tpl->assign("STORE", $_POST);

            if($_POST['member_id']) {
            	$details = $user->getUserdetails($_POST['member_id']);
            	$framework->tpl->assign("OWNER_NAME", $details['first_name']." ".$details['last_name']." (".$details['username'].")");
            }
        } elseif($_REQUEST['id']) {
        	$storeDetails = $store->storeGet($_REQUEST['id']);
			 $framework->tpl->assign("STORE", $storeDetails);

            $cat = $store->storeCategoriesGet($_REQUEST['id']);
            $framework->tpl->assign("ALL_CATEGORIES", $cat['all']);			
            $framework->tpl->assign("STORE_CATEGORIES", $cat['store']);
			
          
            if ($storeDetails['user_id']) {
            	$details = $user->getUserdetails($storeDetails['user_id']);
            	$framework->tpl->assign("OWNER_NAME", $details['first_name']." ".$details['last_name']." (".$details['username'].")");
            }
        }
        if($global['artist_selection']=='Yes'){
        	$framework->tpl->assign("ARTIST_SELECTION", 'Yes');
        }
		$framework->tpl->assign("REG_PACK", $objUser->loadRegPack());
        $cat = $store->storeCategoriesGet($_REQUEST['id']);
        $framework->tpl->assign("ALL_CATEGORIES", $cat['all']);
        $framework->tpl->assign("STORE_CATEGORIES", $cat['store']);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_form.tpl");
        break;
	case "template_list":			
	
		$framework->tpl->assign("STORE_DETAILS", $template->storeGet($_REQUEST['store_id']));	
		$_REQUEST['limit'] 	= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $template->cssList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("CSS_LIST", $rs);
		$framework->tpl->assign("DEFAULT", $rs);
		$framework->tpl->assign("STORE_ID", $_REQUEST['store_id']);
		//temperory code  by robin
		/*if(SITE_URL=='http://192.168.1.254/thepersonalizedgift')
			$showImage=1;
		else
			$showImage=0;
		$framework->tpl->assign("SHOWIMGE", $showImage);*/
		//end
		
		
		$framework->tpl->assign("SID", $_REQUEST['sId']);
		$framework->tpl->assign("SID", $_REQUEST['sId']);
		$framework->tpl->assign("FID", $_REQUEST['fId']);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/css_List1.tpl");
        break; 
	case "showimage":
		$temp_id	=	$_REQUEST['css_id'];	
		
		$framework->tpl->assign("CSS_DETAILS", $template->getCss($temp_id));		
		$framework->tpl->display( FRAMEWORK_PATH."/modules/store/tpl/show_css.tpl");
		exit;
		//$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		
		break;	
	case "assign_css":		
	
		$store_id	=	$_REQUEST['store_id'];
		$temp_id	=	$_REQUEST['css_id'];		
		$avtar=$_REQUEST['avtar'];
		
	  if( ($message =	$template->assignCss($store_id,$temp_id,$avtar[0])) === true ) {		  
			setMessage("CSS Assigned Successfully", MSG_SUCCESS);					
		}	
		 if($_REQUEST['manage']=='manage')
									redirect(makeLink(array("mod"=>"store", "pg"=>"store_index"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&store_id=".$store_id));
								else
				 redirect(makeLink(array("mod"=>$_REQUEST['mod'],"pg"=>"index"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&store_id=".$store_id));
		 setMessage($message);
	break;  
    case "delete":
        $store->storeDelete($_REQUEST['id']);
        setMessage("$sId Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list&sId=$sId&fId=$fId&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}&store_pageNo={$_REQUEST['store_pageNo']}"));
        break;
	case  "store_del":
		$store_ids=$_POST['store_ids'];
		if(count($store_ids)>0){
			foreach($store_ids as $key=>$val){
				// $store->storeDelete($val);
				 $store->disableStore($val,'Y');
			}
			
		setMessage("$sId(s) Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list&sId=$sId&fId=$fId&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}&store_pageNo={$_REQUEST['store_pageNo']}"));
		}
		break;	
		
	 case "store_accessory":
	// print($_REQUEST['store']);
	    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $store->storeList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", OBJECT, $_REQUEST['orderBy']);
		
		
			if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$status	=	$objProduct->defaultProductAccessoryAddEdit($req);
			/*if($status['status']==true)
			{
				$product_id=$status['id'];
				if( ($status['status'] 	= 	$objProduct->insertRelated($req,$product_id)) == true ) {
					$action = $req['id'] ? "Updated" : "Added";
					setMessage("$sId $action Successfully", MSG_SUCCESS); 
					redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&category_id={$_REQUEST['category_id']}&limit=$limit&fId=$fId&sId=$sId"));
				}*/
		}
		
		
		
        $framework->tpl->assign("STORE_LIST", $rs);
        $framework->tpl->assign("STORE_NUMPAD", $numpad);
        $framework->tpl->assign("STORE_LIMIT", $limitList);
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1'));
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_accessory.tpl");
        break;
		
		case "admin_form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $admin->adminAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Admin User $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"admin", "pg"=>"admin"), "act=list"));
            }
            setMessage($message);
        }
        if($message) {
            $framework->tpl->assign("ADMIN", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("ADMIN", $admin->adminGet($_REQUEST['id']));
        }
        //$framework->tpl->assign("MODULE_LIST", $admin->adminModuleList($_REQUEST['id']));
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/admin_form.tpl");
        break;
        
        case "showavatar":
		$avatar_id	=	$_REQUEST['avatar_id'];
		
		$framework->tpl->assign("CSS_DETAILS", $avatar_id);		
		$framework->tpl->display( FRAMEWORK_PATH."/modules/store/tpl/show_avatar.tpl");
		exit;
		
		
		case "assign_css_ptp":		
	
		$store_id	=	$_REQUEST['store_id'];
		$temp_id	=	$_REQUEST['css'];		
		$avtar_id	=	$_REQUEST['avtar'];		

		if( ($message =	$template->assignCss($store_id,$temp_id,$avtar_id)) === true ) {		  
			setMessage("CSS Assigned Successfully", MSG_SUCCESS);					
		}	
		 if($_REQUEST['manage']=='manage')
									redirect(makeLink(array("mod"=>"store", "pg"=>"store_index"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&store_id=".$store_id));
								else
				 redirect(makeLink(array("mod"=>$_REQUEST['mod'],"pg"=>"index"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&store_id=".$store_id));
		 setMessage($message);
	break; 
	
	case "active":
	
		$store->changeActive($_REQUEST["stat"],$_REQUEST['id']);
		redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}&store_pageNo={$_REQUEST['store_pageNo']}"));
		break;	
		
	case "inactive_store_list":
	
		$store_status=$_REQUEST['status'];
		if($_REQUEST['id'])
		{
			$store->disableStore($_REQUEST['id'],$store_status);
		}
		
		 $_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
		 
		list($rs, $numpad, $cnt, $limitList) = $store->storeDisabledList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", OBJECT, $_REQUEST['orderBy']);
		
        $framework->tpl->assign("STORE_LIST", $rs);
        $framework->tpl->assign("STORE_NUMPAD", $numpad);
        $framework->tpl->assign("STORE_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/inactive_store_list.tpl");
		
		break;	
		
		case  "store_delete":
		$store_ids=$_POST['store_ids'];
		if(count($store_ids)>0){
			foreach($store_ids as $key=>$val){
				 $store->storeDelete2($val);
			}
		setMessage("$sId(s) Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=inactive_store_list&sId=$sId&fId=$fId&limit={$_REQUEST['limit']}&store_limit={$_REQUEST['store_limit']}&pageNo={$_REQUEST['pageNo']}&store_pageNo={$_REQUEST['store_pageNo']}"));
		}
		break;	
		
		
}


//
//$framework->tpl->display($global['curr_tpl']."/admin.tpl");

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>

