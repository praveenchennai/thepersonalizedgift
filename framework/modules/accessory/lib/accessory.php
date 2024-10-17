<?
header("Pragma: no-cache");
header("Cache: no-cahce");
/*
Owner: Newagesmeb.com

Created By: Nirmal. K. R
Date: 11-4-2007
*/

session_start();

if ($_REQUEST['storename']) {
	$storeDetails			=	$objStore->storeGetByName($_REQUEST['storename']);	
	$store_id				=	$storeDetails['id'];
}

if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","accessory_accessory") ;
//    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
    $permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","accessory") ;
	$framework->tpl->assign("PG","accessory") ;
	$STORE = array('hide'=>'N','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

$framework->tpl->assign("CURR_MOD","store") ;

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$cat_id			=	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
$accessory_search	= 	$_REQUEST["accessory_search"] ? $_REQUEST["accessory_search"] 	: "";
$search_status	= 	$_REQUEST["search_status"] 	? $_REQUEST["search_status"] 	: "0";//search_status
//$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder&cat_id=$cat_id&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"];
$catacc			=	$_REQUEST['aid'] ? $_REQUEST['aid'] : "";
$param			=	"mod=$mod&pg=$pg&act=$act&aid=$catacc&orderBy=$orderBy&sortOrder=$sortOrder&cat_id=$cat_id&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"];
$objAccessory	=	new Accessory();

$objCategory	=	new Category();
$objProduct		=	new Product();
switch($_REQUEST['act']) {
	case "list":
	
	//echo " enter ".$_REQUEST['hid_cat'];
		include_once(SITE_PATH."/includes/areaedit/include.php");		
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		
		//code by robin
		if($accessory_search)
		{
			$search_status="1";
		}
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		$req 			=	&$_POST;
		
		 $message =	$objAccessory->AccessorymassUpdate($req);
		
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			
			if($_REQUEST['accessory_search'])
			{
				$search_status="1";//search_status   1->true; 0->false
				$accessory_search=$_POST['accessory_search'];
				
				if($_POST['submit']=="Submit"){
				
				
				
				 $message =	$objAccessory->AccessorymassUpdate($req);
				 }
				
			}
			else
			{
					unset($_POST["select_all"]);
					$message	=	$objAccessory->AccessorymassUpdate($req);
					//===============================
							 $sid   = $_REQUEST['hf2'];
							 $strid = explode(",", $sid);
							 $aid   = $_POST['del_id'];
						
						if(count($strid) >0){
							for($i=0;$i<count($aid);$i++){
							for($j=0;$j<count($strid);$j++){
							  $storeMessage = $objAccessory->storeAccDetails($strid[$j],$aid[$i]);
							}
						}}
					//===============================
					//redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&cat_id={$_REQUEST['cat_id']}&limit=".$_REQUEST["limit"]."&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));

					
					redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=list&cat_id={$_REQUEST['hid_cat']}&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&orderBy=".$orderBy));
			}
		}
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		
		 editorInit('html_desc');
		/*else
		{
		 editorInit('html_desc');
		 }*/
		
		if($search_status=="1")//search_status   1->true; 0->false
		{
		$param			.=	"&search_status=1";//search_status   1->true; 0->false
		$param			.=	"&accessory_search=$accessory_search";
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAccessoriesbySearch($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$accessory_search);
		}
		else
		{
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$store_id);
		}
		$parent_id		=	isset($_REQUEST["cat_id"])?trim($_REQUEST["cat_id"]):"0";
		//echo '<pre>';		print_r($rs);exit;
		//exit;
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign("ACCESSORY_LIST", $rs);
		$framework->tpl->assign("ACCESSORY_NUMPAD", $numpad);
		$framework->tpl->assign("ACCESSORY_LIMIT", $limitList);
		$framework->tpl->assign("ACCESSORY_SEARCH_TAG", $accessory_search);
		#--
		$framework->tpl->assign("CATEGORY_MASS", $objCategory->getAllCategory_is_in_ui());
		//$testArr=$objCategory->getAllaccessoryCategory($catArr1,0,0,28,$store_id);
		//$framework->tpl->assign("ACCESSORY_CATEGORY", $catArr1);
		$framework->tpl->assign("STORE_LIST", $objAccessory->storeGetDetails());
		//$framework->tpl->assign("STORE_LIST", $objAccessory->storeGetDetails());
		#--
		//$objCategory->getAllaccessoryCategoryParentFirst($catArr,$parent_id,0,28);
		//$framework->tpl->assign("CATEGORY", $catArr);
		$framework->tpl->assign("CATEGORY",$objCategory->getAccessoryCategoryTreeParentLevel($parent_id,28,$store_id));
		//$framework->tpl->assign("CATEGORY", $objCategory->getAllCategory_is_in_ui());
		$framework->tpl->assign("TYPE", GetAccessoryType());		
		####################################################################################
		
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
		###################################################################################
		$framework->tpl->assign("ALL_STORE_LIST", $objAccessory->GetAllStoresforAccessory());
		
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAccessory($parent_id,$limit,"&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		if ($framework->config["accessory_custom"]==1)
		{
			$fields_cs = $objAccessory->fetchFields('list_order');
			
			for ($i=0;$i<sizeof($rs);$i++)
			{
				for ($j=0;$j<sizeof($fields_cs);$j++)
				{
					$val = $fields_cs[$j]->field_name;
					$rs1[$i][$j] =  $rs[$i]->{$val};
				}
			}
			
			$framework->tpl->assign("FIELDS_VALS",$rs1);
			//$framework->tpl->assign("FIELDS_CS",$fields_cs);
			
			$fields_cs = $objAccessory->fetchFields('display_order');
			
			for($i=0;$i<sizeof($fields_cs);$i++)
			{
				$fl_type = $fields_cs[$i]->ctrl_type;
				
				
				if ($fields_cs[$i]->ctrl_type!="file")
				{
					$new_fields[$i] = $fields_cs[$i];
				}
				if ($fields_cs[$i]->field_name=="active")
				{
					$new_fields[$i]->db_val = "Y";
				}	
		
				if ($fl_type=="combo")	
				{
					if ($fields_cs[$i]->field_name=="accessory_category[]")
					{
						if(count($catArr)>0){
							$new_fields[$i]->options = array_values($catArr1);
						}
						if ($selected_ids)
						{
							$fields_cs[$i]->selected = array_values($selected_ids);
						}	
						
					}
					else 
					{
						$option = explode("|",$fields_cs[$i]->combo_default);
						for ($k=0;$k<sizeof($option);$k++)
						{
							$arr1 = explode("~",$option[$k]);
							$options[0][$k] = $arr1[0];
							$options[1][$k] = $arr1[1];
						}
						$new_fields[$i]->options = $options;
					}
				}
				
								
			}	
			if (count($new_fields)%2>0)
			{
				$framework->tpl->assign("ADD_ROW",1);
			}
			
			$framework->tpl->assign("FIELDS_CS1",$new_fields);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/accessory_list.tpl");
		}
		else 
		{
		
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_list.tpl");
		}	
		break;
	
		
	case "form":

		$acce_id=0; 
		$flag			=	$_REQUEST["flag"] ? $_REQUEST["flag"] : "0";
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		
		if(count($del_id)>0)
		{ 	foreach ($del_id as $accessory_id)
			{  	$acce_id=$accessory_id; }
			
		}
		if($flag==1)
		{ 	
			include_once(SITE_PATH."/includes/areaedit/include.php");
			editorInit('html_desc');
		 }
		if($acce_id!=0)
		{
		
				$ac_value = $objAccessory->GetAccessory($acce_id);
				$ac_value['image_extension']="";
				unset($ac_value['id']); //print_r($ac_value);
		}
		else
		{
			$ac_value = $objAccessory->GetAccessory($_REQUEST['id']);
		}
		
		
		//$ac_value = $objAccessory->GetAccessory($_REQUEST['id']);
		#-------------------------------------
			
			
		//if($flag!=1){
		if($_SERVER['REQUEST_METHOD'] == "POST") {		
			if($store_id){
				 $_POST['hf2'] = $store_id;
			
				if(!isset($_POST['name'])){
					//This fetches the Name of the accessory if the POST of nameis NULL(Disabled field in store/manage).
					$_POST['name']	=	$objAccessory->getnameifpostnull($_POST['id']);
				}
			}
			
		
	
		//echo "<pre>";
		//print_r($_POST);
		
		//exit;
			//header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			//header('Cache-Control: Private');
			
			$req 			=	&$_POST; 
			$fname			=	basename($_FILES['image_extension']['name']);
			$ftype			=	$_FILES['image_extension']['type'];
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			
			if($_REQUEST['manage']=="manage"){
				
				if(!$req['accessory_category']){
					$selected_ids = $objAccessory->GetAllAccessoryCategoty($_REQUEST['id']);
					$req['accessory_category']	=	$selected_ids['category_id'];	
				}
			}
			
		
				$message 		= 	$objAccessory->accessoryAddEdit($req,$fname,$tmpname);
			
			
			if( ($message['status']) == true ) {
			
			
			
			//=========================================
				$sid   = $_REQUEST['hf2'];
				 $strid = explode(",", $sid);
				 $aid   = $message['id'];
				 
				 for($i=0;$i<count($strid);$i++){
				  $storeMessage = $objAccessory->storeAccDetails($strid[$i],$aid);
				 }
	       //==========================================
			
			 # done by adarsh 30 oct 2009 for showing accessory in stores when a new accessory is inserted in root store
				//if( ($message['status']) == true & $_REQUEST['manage']=='' && $_REQUEST['active']=='Y')
				//print_r($_REQUEST);exit;
				if( ($message['status']) == true & $_REQUEST['manage']=='')
				{
					if($_REQUEST['active']=='Y')
						$actve = 'Y';
					else 
						$actve = 'N';
						
					if(!($_REQUEST['id']))
					{
					 	$accessoryid   = $message['id'];
						$store_det	   = $objAccessory->getStoreList();
					
						foreach($store_det as $key=>$storedetails)
						{
							$store_acc_array =	array("accessory_id"=>$accessoryid,"store_id"=>$storedetails['id'],'active'=>$actve);
							$objAccessory->db->insert("store_accessory", $store_acc_array);
						}
					}
					/*else
					{
					
					 	$accessoryid   = $message['id'];
						$store_det	   = $objAccessory->getStoreList();
					
						foreach($store_det as $key=>$storedetails)
						{
							$store_acc_array =	array("accessory_id"=>$accessoryid,"store_id"=>$storedetails['id'],'active'=>$actve);
							$objAccessory->db->update("store_accessory", $store_acc_array,"accessory_id=$accessoryid");
						}
					
					
					}*/
				}
			  # done by adarsh 30 oct 2009 ends
			
			
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
#for redirecting to sub accessory listings after a add/edit any kind of sub accsssories	::salim			
				if($req['sId'] != 'Accessory') {
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=cat_acc&aid={$_REQUEST['aid']}&accessory_search=".$_REQUEST["accessory_search"]."&limit=".$_REQUEST["limit"]."&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&orderBy=".$orderBy."&pageNo=".$_REQUEST["pageNo"]."&action=".$action));
				}
				else{
				#Original redirect
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&cat_id={$_REQUEST['cat_id']}&accessory_search=".$_REQUEST["accessory_search"]."&limit=".$_REQUEST["limit"]."&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&orderBy=".$orderBy."&action=".$action));
				}
			}
			setMessage($message['message']);
			################## 
			//To show the valus on submission false.
			
		
			
			$fetch_val = $ac_value;
			$fields_cs = $objAccessory->fetchFields('display_order');
			for($i=0;$i<sizeof($fields_cs);$i++)
			{
				$fl_type = $fields_cs[$i]->ctrl_type;
				$fields_cs[$i]->db_val = $fetch_val[$fields_cs[$i]->field_name];
		
				if ($fl_type=="combo")	
				{
					if ($fields_cs[$i]->field_name=="accessory_category[]")
					{
						if (!empty($catArr1)){
						$fields_cs[$i]->options = array_values($catArr1);
						}
						if ($selected_ids)
						{
							$fields_cs[$i]->selected = array_values($selected_ids);
						}	
						//print_r(array_values($catArr));
					}
					else 
					{
						$option = explode("|",$fields_cs[$i]->combo_default);
						for ($k=0;$k<sizeof($option);$k++)
						{
							$arr1 = explode("~",$option[$k]);
							$options[0][$k] = $arr1[0];
							$options[1][$k] = $arr1[1];
						}
						$fields_cs[$i]->options = $options;
						$fields_cs[$i]->selected[0] = $fields_cs[$i]->db_val;
					}
				}
								
			}
			//print_r($fields_cs);
			$framework->tpl->assign("FIELDS_CS",$fields_cs);
			##################End of POST
		}
		else
		{
			include_once(SITE_PATH."/includes/areaedit/include.php");
			$fetch_val = $ac_value;
		 	editorInit('html_desc');
		 	
		 }
	
	 //}else
	//  { editorInit('html_desc'); }	 
		
		 
		 
		//$framework->tpl->assign("Category_accessory", $objAccessory->GetConformationRequest($_REQUEST['id']));
		$framework->tpl->assign("CATEGORY", $objCategory->getAllCategory_is_in_ui());
		if ($global['single_prod'] == 1)//This is for personalizedgift
			{
				$objCategory->getAllaccessoryCategory($catArr1,$catacc,0,28,$store_id);
			}
		else 
			{
				$objCategory->getAllaccessoryCategory($catArr1,0,0,28,$store_id);
		
			}
		$framework->tpl->assign("ACCESSORY_CATEGORY", $catArr1);
		
		//print_r($catArr1);
		if($acce_id!=0)
		{
				$selected_ids = $objAccessory->GetAllAccessoryCategoty($acce_id);
				$framework->tpl->assign("RELSTORE", $objAccessory->getRelatedStore($acce_id));
				$selected_stores	=	$objAccessory->GetselectedStoresAccessory($acce_id);
		}
		else
		{
			$selected_ids = $objAccessory->GetAllAccessoryCategoty($_REQUEST['id']);
			$framework->tpl->assign("RELSTORE", $objAccessory->getRelatedStore($_REQUEST['id']));
			$selected_stores	=	$objAccessory->GetselectedStoresAccessory($_REQUEST['id']);
		}
		#-------------------------- 18-07-07 --------------------
		if($acce_id==0 && $_REQUEST['id']=="")
		{
			$framework->tpl->assign("RELSTORE", $objAccessory->storeGetDetails());
		}
		$framework->tpl->assign("ALL_STORE_LIST", $objAccessory->GetAllStoresforAccessory());
		$framework->tpl->assign("SELECTED_STORES", $selected_stores);
		
		#-----------------------------------------------
		
		$framework->tpl->assign('ACCESSORY_CATEGORY_SELECTEDIDS', $selected_ids);
		$framework->tpl->assign("TYPE", GetAccessoryType());
		$framework->tpl->assign("STORE_LIST", $objAccessory->storeGetDetails());
		
		$framework->tpl->assign("ACCESSORY",$ac_value);
		$framework->tpl->assign("CATEGORY_ID", $_REQUEST['cat_id']);
		$framework->tpl->assign("DATE", date("H:i:s"));
		
		
		
		if ($framework->config["accessory_custom"]==1)
		{
			
			$fields_cs = $objAccessory->fetchFields('display_order');
			
	
			/*To make the values to Y for main store.////////////////////////////////////////
			*/
			if(!$_REQUEST['manage']=="manage") {
				for($i=0;$i<count($fields_cs);$i++)
					{
						$fields_cs[$i]->art_store = 'Y';
						$fields_cs[$i]->mat_store = 'Y';
						$fields_cs[$i]->poem_store = 'Y';
					}
			}
			
			//print_r($fields_cs);exit;
			//$i =0 ;
			for($i=0;$i<sizeof($fields_cs);$i++)
			{
				$fl_type = $fields_cs[$i]->ctrl_type;
				$fields_cs[$i]->db_val = $fetch_val[$fields_cs[$i]->field_name];
		
				if ($fl_type=="combo")	
				{
					
					if ($fields_cs[$i]->field_name=="accessory_category[]")
					{
						if (!empty($catArr1)){
						$fields_cs[$i]->options = array_values($catArr1);
						}
						if ($selected_ids)
						{
							$fields_cs[$i]->selected = array_values($selected_ids);
						}
						else
						{
							$array=array("category_id]"=>array(0=>236));
							$fields_cs[$i]->selected = array_values($array);
						}	
						//print_r(array_values($catArr));
					}
					else 
					{
						$option = explode("|",$fields_cs[$i]->combo_default);
						for ($k=0;$k<sizeof($option);$k++)
						{
							$arr1 = explode("~",$option[$k]);
							$options[0][$k] = $arr1[0];
							$options[1][$k] = $arr1[1];
						}
						$fields_cs[$i]->options = $options;
						$fields_cs[$i]->selected[0] = $fields_cs[$i]->db_val;
					}
				}
								
			}
			
			//print_r($fields_cs);
			
			
			$framework->tpl->assign('STOREMANAGE', $store_id);
			
			$framework->tpl->assign("CAT_ACC_ID",$_REQUEST['aid']);
			$framework->tpl->assign("FIELDS_CS",$fields_cs);
			//print_r($fields_cs);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/accessory_form.tpl");
		
			

		}
		else 
		{
		
		    
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_form.tpl");
			
			}
		
		break;
	case "delete":
	extract($_POST);
  
  	if(count($id)>0)
			{
			$message=true;
			foreach ($id as $accessory_id)
				{  
				
				if($objAccessory->accessoryDelete($accessory_id)==false)
					$message=false;
				}
			}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) deleted successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) cannot be deleted!");
			
			if($req['sId'] != 'Accessory') {
				redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=cat_acc&aid={$_REQUEST['aid']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&accessory_search=".$_REQUEST["accessory_search"]));
			}
			else 
			{
				redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&accessory_search=".$_REQUEST["accessory_search"]));
			}
		break;
	case "grouplist":
	$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_order";
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoryGroup($pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("GROUP_LIST", $rs);
		$framework->tpl->assign("GROUP_LIMIT", $limitList);
		$framework->tpl->assign("GROUP_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_group_list.tpl");
		break;
	case "groupform":
	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objAccessory->AddEditaccessoryGroup($req)) === true ) {
				$action = $req['group_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=grouplist&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
				}
			setMessage($message);
			}
			$framework->tpl->assign("STORE_LIST", $objAccessory->storeGetDetails());
			$framework->tpl->assign("RELSTORE", $objAccessory->getRelatedGroupsStore($_REQUEST['group_id']));
		$framework->tpl->assign('ACCESSORY_NAME', $objAccessory->GetAccessoryGroupName($_REQUEST['group_id']));
		$framework->tpl->assign("CATEGORY_GROUP", $objAccessory->GetCategoryGroups($_REQUEST['group_id']));
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_group_form.tpl");
		break;
	case "groupDelete":
	if(count($id)>0)
			{
			$message=true;
			foreach ($id as $grp_id)
				{
				if($objAccessory->accessoryGroupDelete($grp_id)==false)
					$message=false;
				}
			}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=grouplist&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
	break;
	case "settingsList":
		
		unset ( $_SESSION['newItems'] );
	
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->GetAccessorySettingsList($pageNo,$limit,$param,OBJECT, $orderBy);
		
		$framework->tpl->assign("GROUP_LIST", $rs);
		$framework->tpl->assign("GROUP_LIMIT", $limitList);
		$framework->tpl->assign("GROUP_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_settings_list.tpl");
	break;
	case "settingsAdd":
		$group_id		=	$_REQUEST["group_id"] ? $_REQUEST["group_id"] : "";
		$cat_id			=	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "15";
		
		if($_SERVER['REQUEST_METHOD'] == "POST" ) {
				
				if( isset($_POST['btn_search']) && trim($_POST['accessory_search']) )
				{
					$search_status="1";//search_status   1->true; 0->false
					$accessory_search=$_POST['accessory_search'];
					
				}elseif ( isset($_POST['btnSubmit']) ) {
					header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
					header('Cache-Control: Private');
					
					$_REQUEST['accessory']	=	$_REQUEST['accessorynew'];
					$req 			=	&$_REQUEST;
					$message 	= 	$objAccessory->AddAccessorySettings($req);
					if($message['status']==true) {
						$action = $req['id'] ? "Updated" : "Added";
						setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
						redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=settingsList&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
						}
					else
						setMessage($message['message']);
				} else {
					   if ( !isset( $_SESSION['newItems'] )  &&  !empty ( $_REQUEST['accessory'] ) ) {
							 $_SESSION['newItems'] = array();
							 $_SESSION['newItems'] = $_REQUEST['accessory'];
					   } else if ( isset( $_SESSION['newItems'] )  && !empty ( $_REQUEST['accessory'] ) )  { 
							 $_SESSION['newItems']	=	array_merge ( $_SESSION['newItems'] , $_REQUEST['accessory'] );
							 $_SESSION['newItems']  =   array_unique( $_SESSION['newItems'] );
					   }
				}
		}
			
			
		if ( !isset( $_SESSION['newItems'] ) )	{	
			$old_Item	=	$objAccessory->GetAccessorySettingsByGroup($group_id);
			$arrOld_val	=	array();
			if ( count($old_Item) ) {
				foreach ( $old_Item as $rowval)	{
					$arrOld_val[]	=	$rowval['id'];
				}
				$_SESSION['newItems']  =  $arrOld_val;
			}
		}
		
		
		
	    if($search_status=="1")//search_status   1->true; 0->false
		{
		$param			.=	"&search_status=1";//search_status   1->true; 0->false
		$param			.=	"&accessory_search=$accessory_search";
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAccessoriesbySearch($pageNo,$limit,$param,ARRAY_A, $orderBy,$cat_id,$accessory_search);
		}
		else
		{
			if ( $cat_id > 0 )
			list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesByCategory($pageNo,15,$param,ARRAY_A, $orderBy,$store_id,$cat_id );
			else
			list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,15,$param,ARRAY_A, $orderBy,$store_id,$cat_id );
		}
		$parent_id		=	isset($_REQUEST["cat_id"])?trim($_REQUEST["cat_id"]):"0";		
		
		
				
		$framework->tpl->assign('CATEGORY_ACCESSORY', $rs );  
		$framework->tpl->assign('CATEGORY_ACCESSORY_NUMPAD', $numpad ); 
		$framework->tpl->assign("CATEGORY_ACCESSORY_LIMIT", $limitList);
		$framework->tpl->assign("CATEGORY_ACCESSORY_SEARCH_TAG", $accessory_search);


		$framework->tpl->assign("CATEGORY_ACCESSORY_PATH",$objCategory->getCategoryPathAccessoryExclude($parent_id,$limit,"&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&group_id=".$_REQUEST["group_id"]."&mId=".$_REQUEST["mId"]));


		
			
		$framework->tpl->assign('SELECTED_ACCESSORY_IDS', $objAccessory->GetAccessorySettings($group_id));
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		
	    $framework->tpl->assign('CATEGORY_NEW_ALL', $objAccessory->GetAccessorySettingsByRequest($_SESSION['newItems']));
		$framework->tpl->assign('CATEGORY_EDIT_ALL', $objAccessory->GetAccessorySettingsByGroup($group_id));
		#$framework->tpl->assign('CATEGORY_ONLY_ALL', $objAccessory->getAccessories_of_category_combo());
		$framework->tpl->assign("CATEGORY",$objCategory->getAccessoryCategoryTreeParentLevel($parent_id,28,$store_id));
		
		
		
		if($parent_id>0)
		{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
		}
		else
		{
			$framework->tpl->assign('SELECT_DEFAULT', "");
		}				
		 
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_settings_form.tpl");
	break;
	case "settingsAddPop":
		$group_id		=	$_REQUEST["group_id"] ? $_REQUEST["group_id"] : "";
		$cat_id			=	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "10";
		
		if($_SERVER['REQUEST_METHOD'] == "POST" ) {
				
				if( isset($_POST['btn_search']) && trim($_POST['accessory_search']) )
				{
					$search_status="1";//search_status   1->true; 0->false
					$accessory_search=$_POST['accessory_search'];
					
				}elseif ( isset($_POST['btnSubmit']) ) {
					header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
					header('Cache-Control: Private');
					
					$_REQUEST['accessory']	=	$_REQUEST['accessorynew'];
					$req 			=	&$_REQUEST;
					$message 	= 	$objAccessory->AddAccessorySettings($req);
					if($message['status']==true) {
						$action = $req['id'] ? "Updated" : "Added";
						setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
						unset ( $_SESSION['newItems'] );
						echo "<script language=\"javascript\">opener.window.location.href=opener.window.location.href;window.close();</script>";
						#redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=settingsList&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
						}
					else
						setMessage($message['message']);
				} else {
					   if ( !isset( $_SESSION['newItems'] )  &&  !empty ( $_REQUEST['accessory'] ) ) {
							 $_SESSION['newItems'] = array();
							 $_SESSION['newItems'] = $_REQUEST['accessory'];
					   } else if ( isset( $_SESSION['newItems'] )  && !empty ( $_REQUEST['accessory'] ) )  { 
							 $_SESSION['newItems']	=	array_merge ( $_SESSION['newItems'] , $_REQUEST['accessory'] );
							 $_SESSION['newItems']  =   array_unique( $_SESSION['newItems'] );
					   }
				}
		}
			
			
		if ( !isset( $_SESSION['newItems'] ) )	{	
			$old_Item	=	$objAccessory->GetAccessorySettingsByGroup($group_id);
			$arrOld_val	=	array();
			if ( count($old_Item) ) {
				foreach ( $old_Item as $rowval)	{
					$arrOld_val[]	=	$rowval['id'];
				}
				$_SESSION['newItems']  =  $arrOld_val;
			}
		}
		
		
		
	    if($search_status=="1")//search_status   1->true; 0->false
		{
		$param			.=	"&search_status=1";//search_status   1->true; 0->false
		$param			.=	"&accessory_search=$accessory_search";
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAccessoriesbySearch($pageNo,$limit,$param,ARRAY_A, $orderBy,$cat_id,$accessory_search);
		}
		else
		{
			if ( $cat_id > 0 )
			list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesByCategory($pageNo,10,$param,ARRAY_A, $orderBy,$store_id,$cat_id );
			else
			list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,10,$param,ARRAY_A, $orderBy,$store_id,$cat_id );
		}
		$parent_id		=	isset($_REQUEST["cat_id"])?trim($_REQUEST["cat_id"]):"0";		
		
		
				
		$framework->tpl->assign('CATEGORY_ACCESSORY', $rs );  
		$framework->tpl->assign('CATEGORY_ACCESSORY_NUMPAD', $numpad ); 
		$framework->tpl->assign("CATEGORY_ACCESSORY_LIMIT", $limitList);
		$framework->tpl->assign("CATEGORY_ACCESSORY_SEARCH_TAG", $accessory_search);


		$framework->tpl->assign("CATEGORY_ACCESSORY_PATH",$objCategory->getCategoryPathAccessoryExclude($parent_id,$limit,"&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&group_id=".$_REQUEST["group_id"]."&mId=".$_REQUEST["mId"]));


		
			
		$framework->tpl->assign('SELECTED_ACCESSORY_IDS', $objAccessory->GetAccessorySettings($group_id));
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		
	    $framework->tpl->assign('CATEGORY_NEW_ALL', $objAccessory->GetAccessorySettingsByRequest($_SESSION['newItems']));
		$framework->tpl->assign('CATEGORY_EDIT_ALL', $objAccessory->GetAccessorySettingsByGroup($group_id));
		#$framework->tpl->assign('CATEGORY_ONLY_ALL', $objAccessory->getAccessories_of_category_combo());
		$framework->tpl->assign("CATEGORY",$objCategory->getAccessoryCategoryTreeParentLevel($parent_id,28,$store_id));
		
		
		
		if($parent_id>0)
		{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
		}
		else
		{
			$framework->tpl->assign('SELECT_DEFAULT', "");
		}				
		 
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_settings_form_popup.tpl");
		$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		
		exit;
	break;

	case "settingsDelete":
			extract($_REQUEST);
			if(count($group_id)>0)
			{
			$message=true;
			foreach ($group_id as $grp_id)
				{
				if($objAccessory->accessorySettingsDelete($grp_id)==false)
					$message=false;
				}
			}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"), "act=settingsList&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
	break;
	// ************* for taking art accessory ******************
	case "accessory_group_list":
	$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllSettings($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_group_settings_list.tpl");
	
	break;
	
	case "accessorysettingsForm":
		$id 			= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req 		= 	&$_REQUEST;
			if( ($message = $objAccessory->addEditSettings($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=accessory_group_list&limit=$limit&fId=$fId&sId=$sId"));
			}
		setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("PRD_SETTINGS", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("PRD_SETTINGS", $objProduct->SettingsGet($id));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/accessory_group_settings_form.tpl");
	
	
	break;
	
	
	// **************** for taking art accessory end ****************************
	/**
           * This is used for listing accessories catogarised 
           * Author   : Salim
           * Created  : 04/Dec/2007
           * Modified :
		   * Desc	  :	  
           */

	case "cat_acc";
	
	if($_REQUEST['action'] != '')
	setMessage($_REQUEST['sId']." ".$_REQUEST['action']." Successfully" ,MSG_SUCCESS);
	
	if ($_SERVER['REQUEST_METHOD']=='POST')
	 $_REQUEST["accessory_search"]	= 	$_POST["accessory_search"];
	
	$accessory_search=$_REQUEST['accessory_search'];
	extract($_REQUEST);
	$subacc	=	$objAccessory->GetSubCatOfAcc($catacc);
	$string = $catacc;
	for($i=0;$i<count($subacc);$i++)
	{
		$string	=	$string.",".$subacc[$i][category_id];
	}
	if($accessory_search)
		{
			$search_status="1";
		}
		
		if($_REQUEST['sId']=="Art Backgrounds")
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		else
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_order";
	
		if($search_status=="1")//search_status   1->true; 0->false
		{
		$param			.=	"&search_status=1";//search_status   1->true; 0->false
		$param			.=	"&accessory_search=$accessory_search";
		
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesOfCatagorybySearch2($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$accessory_search,$string,$store_id);
		
		
		}
		else
		{
	
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesOfCatagory($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$store_id,$string,1);
		}
		
	//	print_r($rs);
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign("ACCESSORY_LIST", $rs);//print_r($rs);
		$framework->tpl->assign("ACCESSORY_NUMPAD", $numpad);
		$framework->tpl->assign("ACCESSORY_LIMIT", $limitList);
		$framework->tpl->assign("ACCESSORY_SEARCH_TAG", $accessory_search);
		$framework->tpl->assign("CAT_ACC", 'Y');
		//if ( $global['payment_tpl'] == 'popup' ){$show=1;}else{$show=0;}
		$fields_cs = $objAccessory->fetchFields('list_order');
			
			for ($i=0;$i<sizeof($rs);$i++)
			{
				for ($j=0;$j<sizeof($fields_cs);$j++)
				{
					$val = $fields_cs[$j]->field_name;
					$rs1[$i][$j] =  $rs[$i]->{$val};
				}
			}
//	print_r($fields_cs);
			$framework->tpl->assign("FIELDS_VALS",$rs1);
			
			
			
			$framework->tpl->assign("FIELDS_CS",$fields_cs);
			
			$fields_cs = $objAccessory->fetchFields('display_order');
			
			for($i=0;$i<sizeof($fields_cs);$i++)
			{
				$fl_type = $fields_cs[$i]->ctrl_type;
				
				
				if ($fields_cs[$i]->ctrl_type!="file")
				{
					$new_fields[$i] = $fields_cs[$i];
				}
				if ($fields_cs[$i]->field_name=="active")
				{
					$new_fields[$i]->db_val = "Y";
				}	
		
				if ($fl_type=="combo")	
				{
					if ($fields_cs[$i]->field_name=="accessory_category[]")
					{
						if(count($catArr)>0){
							$new_fields[$i]->options = array_values($catArr1);
						}
						if ($selected_ids)
						{
							$fields_cs[$i]->selected = array_values($selected_ids);
						}	
						
					}
					else 
					{
						$option = explode("|",$fields_cs[$i]->combo_default);
						for ($k=0;$k<sizeof($option);$k++)
						{
							$arr1 = explode("~",$option[$k]);
							$options[0][$k] = $arr1[0];
							$options[1][$k] = $arr1[1];
						}
						$new_fields[$i]->options = $options;
					}
				}
				
								
			}	
			if (count($new_fields)%2>0)
			{
				$framework->tpl->assign("ADD_ROW",1);
			}
			//($new_fields);
			$framework->tpl->assign("FIELDS_CS1",$new_fields);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/accessory_list.tpl");
		
	break;
	
	case "active":
	if($_REQUEST['manage']=="manage")
	$objAccessory->changeStoreActive($_REQUEST["stat"],$_REQUEST['id'],$store_id);
	else
	{
		$objAccessory->changeActive($_REQUEST["stat"],$_REQUEST['id']);
		if($_REQUEST["stat"]=='N')
		{
			$acc_store_count=$objAccessory->accessoryExistsinStore($_REQUEST['id']);
			if($acc_store_count ==0){
			
				$objAccessory->insertAccessoryRelation($_REQUEST['id']);
			}
		}
		
	}	
	
	redirect(makeLink(array("mod"=>$_REQUEST["mod"], "pg"=>$_REQUEST["pg"]), "act=cat_acc&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&accessory_search={$_REQUEST['accessory_search']}&accessory_search={$_REQUEST['accessory_search']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}"));
	
	break;
	/**
    * Set the Fields Premissions for store manage For Arts,Mats and Poems 
  	* Author   : Salim
  	* Created  : 10-Apr-2008
  	* Modified : 
  	*/
	case "field_permission":
		$did	=	$_REQUEST['did'] ? $_REQUEST['did'] :'art_store';//Drop down value.

		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$arr_disable	=	array();
			$arr_enable		=	array();
			$arr_hide		=	array();
			$arr_disable[$did]	=	'N';
			$arr_enable[$did]	=	'Y';
			$arr_hide[$did]		=	'H';
			
			for ($i=0; $i<$_REQUEST['count']; $i++) {
				
						if ($_REQUEST['hide'.$i] == 0){
							$id = $_REQUEST['id'.$i];
							$table = $_REQUEST['tablename'.$i];
							$message = $objAccessory->updateStoreEditable($arr_disable,$id,$table);
						}
						
						if ($_REQUEST['hide'.$i] == 1){
							$id = $_REQUEST['id'.$i];
							$table = $_REQUEST['tablename'.$i];
							$message = $objAccessory->updateStoreEditable($arr_enable,$id,$table);
						}
						
						if ($_REQUEST['hide'.$i] == 2){
							$id = $_REQUEST['id'.$i];
							$table = $_REQUEST['tablename'.$i];
							$message = $objAccessory->updateStoreEditable($arr_hide,$id,$table);
						}
							
							if($message == true){
								setMessage("Field Permissions Updated Successfully!",MSG_SUCCESS);
							}
			} 
		}
		//Making an array for the dropdown
		$dropdown	=	array();
		$dropdown[0]['value'] = art_store;
		$dropdown[0]['disp'] = ARTS;
		$dropdown[1]['value'] = mat_store;
		$dropdown[1]['disp'] = MATS;
		$dropdown[2]['value'] = poem_store;
		$dropdown[2]['disp'] = POEMS;
		
		$fields_manage_store	=	$objAccessory->getStoreEditable(3,$did);
		
		$framework->tpl->assign("DROPDOWN",$dropdown);
		$framework->tpl->assign("ACCNAME",$did);
		$framework->tpl->assign("FIELD_PERMISSIONS",'accessories');
		$framework->tpl->assign("FIELD_MANAGE_STORE",$fields_manage_store);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/store_field_permission.tpl");
		break;
		
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>