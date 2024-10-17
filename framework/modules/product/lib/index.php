<?
/*print_r($_REQUEST);
echo '<br/>';
echo $store_id;
echo '<br/>';
*/
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","product_index") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$framework->tpl->assign("PG","index") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}

session_start();
$_SESSION['sess_grp_array']="";
/*
search_status   1->true; 0->false comment
1->true:	The search result will be displayed.
0->false:	The search result will be ignored and ordinary list will be displayed.

For Accessing the 2D image: {$GLOBAL.mod_url}/images/2D_{id}.{two_d_image}
For Accessing the Overlay image: {$GLOBAL.mod_url}/images/OV_{id}.{overlay}
*/
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");



$relate_id		=	$_REQUEST["relate_id"] 		? $_REQUEST["relate_id"] 	: "0";
$flg			=	$_REQUEST["flg"] 			? $_REQUEST["flg"] 	: "";
$act			=	$_REQUEST["act"] 			? $_REQUEST["act"] 				: "";
$limit			=	$_REQUEST["limit"]          ? $_REQUEST["limit"] : "40";
$pageNo 		= 	$_REQUEST["pageNo"] 		? $_REQUEST["pageNo"] 			: "1";
$product_search	= 	$_REQUEST["product_search"] ? $_REQUEST["product_search"] 	: "";
$search_status	= 	$_REQUEST["search_status"] 	? $_REQUEST["search_status"] 	: "0";//search_status   1->true; 0->false
$category_id	=	$_REQUEST['category_id'];
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder&category_id=$category_id&relate_id=$relate_id&flg=$flg&brandid=$brandid&zoneid=$zoneid&fId=$fId&sId=$sId";
$objProduct		=	new Product();
$objCategory	=	new Category();
$objAccessory	=	new Accessory();
$objCombination	=	new Combination();
$objPrice		=	new Price();
$objMade		=	new Made();
$objUser		=	new User();
//print_r($objProduct->ProductGet(245));
//exit;
$ajax_editor 	= 	new Ajax_Editor();



switch($_REQUEST['act']) {
	case "list":	
			
			if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if($_POST['btn_search'])  # product_search
			{
			$_REQUEST['product_search'] = $_POST['product_search'];
			$search_status="1";//search_status   1->true; 0->false
			$product_search=$_POST['product_search'];
			}
			else
			{
			//$_REQUEST['product_search'] = $_REQUEST['product_search'];
			$search_status="0";//search_status   1->true; 0->false
			$product_search="";
			$objProduct->massUpdate($req,$store_id);
			if($_REQUEST['manage']=='manage')
				redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id."&brandid=".$brandid));
			else
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&limit=$limit&fId=$fId&sId=$sId&zoneid=".$zoneid."&brandid=".$brandid."&category_id=".$category_id));
			}
			
		}
		if($_REQUEST['action'] != '')
		{
		setMessage($_REQUEST['sId']." ".$_REQUEST['action']." Successfully", MSG_SUCCESS); 
		}
		//print_r($_REQUEST);
		
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_order";
			 //print $orderBy;

		if($search_status=="1" || $_REQUEST['product_search'])//search_status   1->true; 0->false
			{
			
			$param			.=	"&search_status=1";//search_status   1->true; 0->false
			$param			.=	"&product_search=$product_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,'',$_REQUEST['brandid'],$_REQUEST['zoneid']);
			
			}
			
		else//search_status   1->true; 0->false
			{
			$param			.=	"&product_search=$product_search";
			$param			.=	"&search_status=0";//search_status   1->true; 0->false
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,'',$_REQUEST['category_id'],$_REQUEST['brandid'],$_REQUEST['zoneid']);
			}
			//print_r($rs);
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup());
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);
		$framework->tpl->assign("PRODUCT_LIST", $rs);
		$framework->tpl->assign("MEMBER_TYPE", $member_type);
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
	
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("MADE", $objMade->GetAllMades());
		
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		$framework->tpl->assign('CATEGORY', $catArr1);
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign('I', 1);
		$framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		$framework->tpl->assign("PRODUCTCODE",$global['product_code'] );
		$framework->tpl->assign('EXCLUDEDIDS', '');
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/product_list.tpl");
		
		break;
	case "form":
	
		
		
	 $flag=0;
	  include_once(SITE_PATH."/includes/areaedit/include.php");
	 
	 //========================
	
	$framework->tpl->assign('STOREMANAGE', $store_id);
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));

	 
	 
	 //=============================
	 
		//echo "1<pre>";
		//print_r($_SESSION['StoreAccessories']);
		//exit;
			//$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		
		//echo "store_id: ".$store_id;
		$status['status']==true;
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		//==============
		
		/////////////
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		
		
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
		//$_REQUEST=stripslashes_deep($_REQUEST);
		 $_REQUEST['description'] = stripslashes($_REQUEST['description']);
	
		  if($_POST['seo_name'] !='' && $_REQUEST['manage']!="manage"){
		   $chkStatus=$objProduct->chkSeoNameExists($_POST['seo_name'],$product_id);
		     }
		   
		   if($chkStatus=='true'){
		  	 setMessage("SEO Name already exists", MSG_ERROR); 
			 $framework->tpl->assign("PRODUCT",$_POST);
			 }
			 else{
		
			 
		  $pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		
		 	foreach($pro_id as $pid)
		    {
			 $proid=$pid;
			}
			 $flag=0;
			 if($proid!=0){
				 $flag=1;
			   
			   $framework->tpl->assign("PRODUCT", $objProduct->ProductGet($proid));
			   $framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($proid));
		     }
		
		   
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			
			
			$req['store_id']=	$store_id;
			$fname			=	basename($_FILES['image_extension']['name']);
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			//For swf image
			$swfname   = basename($_FILES['image_swf']['name']);
			$tmp_swf        =   $_FILES['image_swf']['tmp_name'];
			//For 2D image
			$two_d			=	basename($_FILES['two_d_image']['name']);
			$tmp_two_d		=	$_FILES['two_d_image']['tmp_name'];
			//For overlay image
			$overl			=	basename($_FILES['overlay']['name']);
			$tmp_over		=	$_FILES['overlay']['tmp_name'];
			
			//For pdf file
			$pdf_file		=	basename($_FILES['pdf_file']['name']);
			$tmp_pdf_file	=	$_FILES['pdf_file']['tmp_name'];
			//For psd file
			$psd_file		=	basename($_FILES['psd_file']['name']);
			$tmp_psd_file	=	$_FILES['psd_file']['tmp_name'];
			//For ai image
			$ai_image		=	basename($_FILES['ai_image']['name']);
			$tmp_ai_image	=	$_FILES['ai_image']['tmp_name'];
			//for overlay images
			$over2			=	basename($_FILES['overlay2']['name']);
			$tmp_over2		=	$_FILES['overlay2']['tmp_name'];
			$over3			=	basename($_FILES['overlay3']['name']);
			$tmp_over3		=	$_FILES['overlay3']['tmp_name'];
			$over4			=	basename($_FILES['overlay4']['name']);
			$tmp_over4		=	$_FILES['overlay4']['tmp_name'];
			$over5			=	basename($_FILES['overlay5']['name']);
			$tmp_over5		=	$_FILES['overlay5']['tmp_name'];
			//print("enter"); break;
			//echo $_REQUEST['store_id'];
			
			if($_REQUEST['manage']=="manage"){
				if(!$_POST['name']){
					 $req['name']	=	$objProduct->getvalueifpostnull($req['id'],'name');
				}
				
				if(!$_POST['price']){
					 $req['price']	=	$objProduct->getvalueifpostnull($req['id'],'price');
				}
				if(!$_POST['display_name']){
					 $req['display_name']	=	$objProduct->getvalueifpostnull($req['id'],'display_name');
				}
				
				if(!$_POST['cart_name']){
					 $req['cart_name']	=	$objProduct->getvalueifpostnull($req['id'],'cart_name');
				}
				
				if(!$_POST['description']){
					 $req['description']	=	$objProduct->getvalueifpostnull($req['id'],'description');
				}
				
				if(!$_POST['brand_id']){
					 $req['brand_id']	=	$objProduct->getvalueifpostnull($req['id'],'brand_id');
				}
				if(!$_POST['out_message']){
					 $req['out_message']	=	$objProduct->getvalueifpostnull($req['id'],'out_message');
				}
				if(!$_POST['date_created']){
					 $req['date_created']	=	$objProduct->getvalueifpostnull($req['id'],'date_created');
				}
				if(!$_POST['personalise_with_monogram']){
					 $req['personalise_with_monogram']	=	$objProduct->getvalueifpostnull($req['id'],'personalise_with_monogram');
				}
				if(!$_POST['weight']){
					 $req['weight']	=	$objProduct->getvalueifpostnull($req['id'],'weight');
				}
				if(!$_POST['active']){
					 $req['active']	=	$objProduct->getvalueifpostnull($req['id'],'active');
				}
				
				if(!$_POST['category']){
					$catArr_frmdb = $objProduct->GetAllProductCategoty($_REQUEST['id']);
					$req['category']	=	$catArr_frmdb['category_id'];
			
				}
				$res_val=$objProduct->ProductGet($req['id']);
				
				if($res_val['parent_id']==0){
					$req['parent_id']=$req['id'];
				}
				else{
					$req['parent_id']=$res_val['parent_id'];
				}
				
			}
			
			$req['page_title']=$_POST['page_title'];
			$req['meta_description']=$_POST['meta_description'];
			$req['meta_keywords']=$_POST['meta_keywords'];
			
			
			if(isset($_POST['pro_submit'])){
		
		 	$status	=	$objProduct->ProductAddEdit($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);
		 	
			}
			if($_POST['remove']=='yes')
			{
			$status	=	$objProduct->ProductAddEdit($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);

			}

			
			
			
			
			
					if($status['status']==true)
					{
						$product_id=$status['id'];
					
						   
							if( ($status['status'] 	= 	$objProduct->insertRelated($req,$product_id)) == true )
							{
																
								$action = $req['id'] ? "Updated" : "Added";
								
								if($_REQUEST['manage']=='manage')
								{
								
								
									redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy&action=$action&category_id=".$_REQUEST['category_id']."&product_search=".$_REQUEST['product_search']));
									}
								else
								{
								
								
									redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&product_search=".$product_search."&zoneid=".$zoneid."&brandid=".$brandid."&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy&action=$action&category_id=".$_REQUEST['category_id']."&product_search=".$_REQUEST['product_search']));
									}
							}
						if(isset($_SESSION['StoreAccessories']))
						unset($_SESSION['StoreAccessories']);
					}
					else
						setMessage($status['message']);
					if($status['status']==false && $flag==0)
						 {
							$_POST['name'] = stripslashes($_POST['name']);
							$framework->tpl->assign("PRODUCT", $_POST);
						 } elseif($_REQUEST['id'] && $flag==0 ) 
					 {
					 		//print_r($objProduct->ProductGet($_REQUEST['id']));
							$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
					 }
					 
				}	 
		}
		
		else
		{
		
		//editorInit('description'); Commented for new editor
		
		//$pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		    
		if($_REQUEST['id'] && $flag==0) {
		 /* For new editor */
	 	    $prodRS=$objProduct->ProductGet($_REQUEST['id']);
		   if($prodRS['description'])$row['description'] = $prodRS['description'];
		   /*New reditor ends*/
			//print_r($objProduct->ProductGet($_REQUEST['id']));
				$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
			}
			
				
		}
		
		
		if($_REQUEST['id'])
			{
			$grp_arr=$objCategory->GetProductCategory($_REQUEST['id']);
			if(count($grp_arr)>0)
				{
				$framework->tpl->assign("GROUP_ACC", $grp_arr);
				}
			}
		$select_store=array();
		if($_REQUEST['id'])
			{
			$selected_store[0]['store_id']	=	0;
			$selected_store[0]['other']	=	$objProduct->GetSelectedStore(0,$_REQUEST['id']);
			$stores				=	$objProduct->getRelatedStore($_REQUEST['id']);
			//print_r($stores['id']);
			/*if (count($stores['id'])>0)
				{
				$i=1;
				foreach ($stores['id'] as $store_id)
					{
						$selected_store[$i]['store_id']		=	$store_id;
						$selected_store[$i]['other']		=	$objProduct->GetSelectedStore($store_id,$_REQUEST['id']);
					$i++;
					}
				}*/
			}
			
		//echo "<pre>";
		//print_r($selected_store);
		//exit;
		/*
		** This Creates a dummy array with 100 elements as value Y.
		** tpl contains variable $STOREFIELD to enable/disable the form fields for store/manage.
		** Admin can edit which field is to be enabled/disabled.
		*/
		if(!$_REQUEST['manage']=="manage"){
			$enable_pro_fom_fields	=	array();
				for($i=0;$i<100;$i++){
					$enable_pro_fom_fields[$i] = 'Y';
				}
			$framework->tpl->assign("STOREFIELD",$enable_pro_fom_fields);
		}
	 
		
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_PRICE", $objPrice->priceTypeofProduct($_REQUEST['id']));
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign("MADE_IN", $objMade->GetZoneIdOfProduct($_REQUEST['id']));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		//$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup($_REQUEST['id']));
		
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		
//print_r ( $objProduct->GetAllProductCategoty($_REQUEST['id']) ); exit;
		$framework->tpl->assign('CATEGORY', $catArr1);
		if($flag==0){
		$framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($_REQUEST['id']));
		}
		$framework->tpl->assign('AVAILABLE_ACCESSORIES', $objProduct->GetAllSelectedAccessory($_REQUEST['id']));
		//$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("RELPRODUCT", $objProduct->getRelatedProducts($_REQUEST['id']));
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("MAINSTORE", $objProduct->CheckTheAvailablityStoreAccessory($_REQUEST['id'],'0'));
		$framework->tpl->assign("STORE_LIST", $objProduct->storeGetDetails($_REQUEST['id'],''));
		$framework->tpl->assign("RELSTORE",$stores );
		//print_r($objProduct->getRelatedStore($_REQUEST['id']));
		//exit;
		$framework->tpl->assign("PRODUCT_LIST", $objProduct->productsList($store_id,$_REQUEST['id'],$_REQUEST['category_id']));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
		$framework->tpl->assign("DATE", date("H:i:s"));
		
        $framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		$framework->tpl->assign("EXCLUDED_ACCESSORIES", $objAccessory->getExcludedAccessoryList1() );  /* Aug08 */
		

		$framework->tpl->assign('EXCLUDEDIDS', $objProduct->GetAccessoryExclude($_REQUEST['id']));
		$framework->tpl->assign('EXCLUDEDIDS1', $objProduct->GetAccessoryExclude1($_REQUEST['id']));
		$framework->tpl->assign("ARTIST_SELECTION", $global['artist_selection'] );
		
		$framework->tpl->assign("PRODUCTCODE",$global['product_code'] );
				
		#$framework->tpl->assign("EXCLUDED_ACCESSORY", $objProduct->excludeAccessoryList(0,0));  /* Aug08 */
		//$framework->tpl->assign('SELECTEDSTORE', $selected_store);
		//print_r($objProduct->GetFromProductAccessoryStore($_REQUEST['id']));
		if($_REQUEST['id']>0)
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromProductAccessoryStore($_REQUEST['id']));
		else
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromDefaultAccessoryStore());
		
		//print_r($objProduct->GetFromDefaultAccessoryStore());
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/product_form.tpl");
		
		break;
	case "delete":	
//echo "Delete from table";
		//exit;
		$category_id	=	$_REQUEST['category_id'];
		$product_id     =   $_REQUEST['product_id'];
		$ids = $_REQUEST[id];
		
		$message=false;
		if(count($product_id)>0)
					{
			$message=true;
			foreach ($product_id as $id)
				{
				if($objProduct->deleteProduct($id,$store_id)===false)
					$message=false;
				
					
				}
				
		
			if($message==true)
			setMessage("Product(s) Deleted Successfully!");
			}
	//to delete supplier product		
		if(count($ids)>0)
					{
			$message=true;
			foreach ($ids as $id)
				{
				if($objProduct->deleteProduct($id,$store_id)===false)
					$message=false;
									
				}	
				if($message==true)
			setMessage("Product(s) Deleted Successfully!");
			}	
		//			
		if($message==false)
			setMessage("Product(s) Can not Deleted!");
		if(count($ids)>0)
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=supply&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId&product_search={$_REQUEST['product_search']}"));
		else	
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId&product_search={$_REQUEST['product_search']}"));
		break;
	case "settings_Delete":
		if(count($id)>0)
			{
			$message=true;
			foreach ($id as $grp_id)
				{
				if($stat=($objProduct->deleteSettings($grp_id))==false)
					$message=false;
				}
			}
		if($message==true)
			setMessage("Product Group(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Product Group(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"),"act=settings&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId"));
	break;
	
	/*case "removeimage":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		$fld 		= 	$_REQUEST["fld"] ? $_REQUEST["fld"] : "";
		$module 	= 	$_REQUEST["module"] ? $_REQUEST["module"] : "";
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/settings_list.tpl");
		break;*/
	
	case "settings":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllSettings($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/settings_list.tpl");
		break;
	case "settingsForm":
	
		$id 			    = 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$stores				=	$objProduct->getRelatedStore1($_REQUEST['id']);
		$mainstore				=	$objProduct->getMainStore($_REQUEST['id']);
		$framework->tpl->assign("STORE1", $objProduct->storeGetDetails1($id,'1',$store_id));
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			$req 		= 	&$_REQUEST;
			
			
			if( ($message = $objProduct->addEditSettings($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"),"act=settings&limit=$limit&fId=$fId&sId=$sId"));
			}
		setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("PRD_SETTINGS", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("PRD_SETTINGS", $objProduct->SettingsGet($id));
			//===
			//$framework->tpl->assign("STORE1", $objProduct->storeGetDetails1($id,'1',$store_id));
			$framework->tpl->assign("RELSTORE",$stores);
			$framework->tpl->assign("MAINSTORE",$mainstore);
			//===
		}
		if ( $global['payment_tpl'] == 'popup' ) 
		{
		$show=1;
		 }else{
		 $show=0;
		 }
		 $framework->tpl->assign("SHOW",$show);
		 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/settings_form.tpl");
		break;
case "settings_Form":
		$id 			= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		if($id){
			$framework->tpl->assign("PRD_SETTINGS", $objProduct->SettingsGet($id));
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req 		= 	&$_REQUEST;
			//print_r($req );
			if( ($message = $objProduct->insertSettingsProduct($req,$id)) === true ) {
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"),"act=settings_Form&id=$id&limit=$limit&fId=$fId&sId=$sId"));
			}

		}
		# config variable for Personalized Gift added by Jeffy on 13th may 2008
		if ($global['single_prod'] == 1){
			$framework->tpl->assign("PERSONALIZED", 1);
		}
		# ----------------------------
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_LIST", $objProduct->getProductAll());
		$framework->tpl->assign("PRODUCT_LIST_SETTINGS", $objProduct->getSettingsProduct($id));
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/productSettings.tpl");
		
		break;
case "supply":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			print_r($message);
			if( ($message 	= 	$objProduct->suppluUpdate($req)) === true ) {
			
					setMessage("$sId Updated for the selected Product(s) Successfully", MSG_SUCCESS);
				}
			else//echo "Message:  ".$message."<br>";
			setMessage($message);
		}
		$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder&fId=$fId&sId=$sId";
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProductSupply($pageNo,$_REQUEST['limit'],$param,OBJECT,$orderBy,$store_id);
		//print_r($rs);		
		$framework->tpl->assign("BULK_LIST", $rs);
		$framework->tpl->assign("BULK_NUMPAD", $numpad);
		$framework->tpl->assign("BULK_LIMIT", $limitList);
		$member_type=3;
		$framework->tpl->assign("SUPPLIERS", $objUser->allUsers('mem_type,active',"$member_type,Y"));
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/supply_product_list.tpl");
		break;
		
		
		# modified on 26 Dec 2007 for related products by shinu
		case "list_related":
		
		if(isset($_SESSION['xxrelate_id']))
			$relate_id	=	$_SESSION['xxrelate_id'];
		else
			$relate_id 			= 	$_REQUEST["relate_id"] ? $_REQUEST["relate_id"] : "0";
		
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";

			if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if($_POST['btn_search'])  # product_search
			{
			$search_status="1";//search_status   1->true; 0->false
			$product_search=$_POST['product_search'];
			}
			else
			{
			$search_status="0";//search_status   1->true; 0->false
			$product_search="";
			$objProduct->massUpdate($req,$store_id);
			if($_REQUEST['manage']=='manage')
				redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id));
			else
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id));
			}
		}
		
			if($search_status=="1" || $_REQUEST['product_search'])//search_status   1->true; 0->false
			{
			$param			.=	"&search_status=1";//search_status   1->true; 0->false
			$param			.=	"&product_search=$product_search";
			if($_REQUEST['flg']=='R')
				list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllRelateGroupItems($relate_id,$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,$_REQUEST['category_id'],$_REQUEST['brandid'],$_REQUEST['zoneid']);
			else
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,'',$_REQUEST['brandid'],$_REQUEST['zoneid']);
			}
			
		else//search_status   1->true; 0->false
			{
			$param			.=	"&product_search=$product_search";
			$param			.=	"&search_status=0";//search_status   1->true; 0->false
			if($_REQUEST['flg']=='R') {
				list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllRelateGroupItems($relate_id,$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,$_REQUEST['category_id'],$_REQUEST['brandid'],$_REQUEST['zoneid']);
				}
			else {
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,'',$_REQUEST['category_id'],$_REQUEST['brandid'],$_REQUEST['zoneid']);
			}
			}
		
		$framework->tpl->assign("R_FLAG", $_REQUEST['flg']);
		$framework->tpl->assign("RELATE_ID", $relate_id);
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup());
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);
		$framework->tpl->assign("PRODUCT_LIST", $rs);
		$framework->tpl->assign("MEMBER_TYPE", $member_type);
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
		
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT CATEGORY --");
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");

			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("MADE", $objMade->GetAllMades());
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		$framework->tpl->assign('CATEGORY', $catArr1);
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign('I', 1);
		$framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		
		$framework->tpl->assign('EXCLUDEDIDS', '');
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/related_product_list.tpl");
		
		break;
		# modified on 26 Dec 2007 for related products by shinu
		
		/* case "list_related":
			if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if($_POST['btn_search'])  # product_search
			{
			$search_status="1";//search_status   1->true; 0->false
			$product_search=$_POST['product_search'];
			}
			else
			{
			$search_status="0";//search_status   1->true; 0->false
			$product_search="";
			$objProduct->massUpdate($req,$store_id);
			if($_REQUEST['manage']=='manage')
				redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id));
			else
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id));
			}
		}
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		if($search_status=="1" || $_REQUEST['product_search'])//search_status   1->true; 0->false
			{
			$param			.=	"&search_status=1";//search_status   1->true; 0->false
			$param			.=	"&product_search=$product_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search);
			}
			
		else//search_status   1->true; 0->false
			{
			$param			.=	"&product_search=$product_search";
			$param			.=	"&search_status=0";//search_status   1->true; 0->false
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,'',$_REQUEST['category_id']);
			}
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup());
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);
		$framework->tpl->assign("PRODUCT_LIST", $rs);
		$framework->tpl->assign("MEMBER_TYPE", $member_type);
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
		
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		$framework->tpl->assign('CATEGORY', $catArr1);
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign('I', 1);
		$framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  
		
		$framework->tpl->assign('EXCLUDEDIDS', '');
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/related_product_list.tpl");
		
		break;*/
		
		case "related_product_delete":	
		$category_id	=	$_REQUEST['category_id'];
		$message=false;
		if(count($product_id)>0)
					{
			$message=true;
			foreach ($product_id as $id)
				{
				if($objProduct->deleteProduct($id,$store_id)===false)
					$message=false;
					
				}
			if($message==true)
			setMessage("Product(s) Deleted Successfully!");
			}
		if($message==false)
			setMessage("Product(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list_related&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId&product_search={$_REQUEST['product_search']}"));
		break;
		
		case "related_product_form":
	 	$flag=0;
		
	  	include_once(SITE_PATH."/includes/areaedit/include.php");
	 
	 //========================
	 
		$parent_id = isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));

	 
	 
	 //=============================
	 
		//echo "1<pre>";
		//print_r($_SESSION['StoreAccessories']);
		//exit;
			//$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		
		//echo "store_id: ".$store_id;
		$status['status']==true;
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		//==============
		
		/////////////
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
		  $pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		 	foreach($pro_id as $pid)
		    {
			 $proid=$pid;
			}
			 $flag=0;
			 if($proid!=0){
				 $flag=1;
			   
			   $framework->tpl->assign("PRODUCT", $objProduct->ProductGet($proid));
			   $framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($proid));
		     }
		
		   
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$req['store_id']=	$store_id;
			$fname			=	basename($_FILES['image_extension']['name']);
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			//For swf image
			$swfname   = basename($_FILES['image_swf']['name']);
			$tmp_swf        =   $_FILES['image_swf']['tmp_name'];
			//For 2D image
			$two_d			=	basename($_FILES['two_d_image']['name']);
			$tmp_two_d		=	$_FILES['two_d_image']['tmp_name'];
			//For overlay image
			$overl			=	basename($_FILES['overlay']['name']);
			$tmp_over		=	$_FILES['overlay']['tmp_name'];
			
			//For pdf file
			$pdf_file		=	basename($_FILES['pdf_file']['name']);
			$tmp_pdf_file	=	$_FILES['pdf_file']['tmp_name'];
			//For psd file
			$psd_file		=	basename($_FILES['psd_file']['name']);
			$tmp_psd_file	=	$_FILES['psd_file']['tmp_name'];
			//For ai image
			$ai_image		=	basename($_FILES['ai_image']['name']);
			$tmp_ai_image	=	$_FILES['ai_image']['tmp_name'];
			//for overlay images
			$over2			=	basename($_FILES['overlay2']['name']);
			$tmp_over2		=	$_FILES['overlay2']['tmp_name'];
			$over3			=	basename($_FILES['overlay3']['name']);
			$tmp_over3		=	$_FILES['overlay3']['tmp_name'];
			$over4			=	basename($_FILES['overlay4']['name']);
			$tmp_over4		=	$_FILES['overlay4']['tmp_name'];
			$over5			=	basename($_FILES['overlay5']['name']);
			$tmp_over5		=	$_FILES['overlay5']['tmp_name'];
			//print("enter"); break;
			//echo $_REQUEST['store_id'];
			if(isset($_POST['pro_submit'])){
				$status	=	$objProduct->relatedProductAddEdit($req);
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list_related&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy"));
			}
			
			
			
					if($status['status']==true)
					{
						$product_id=$status['id'];
							if( ($status['status'] 	= 	$objProduct->insertRelated($req,$product_id)) == true )
							{
								$action = $req['id'] ? "Updated" : "Added";
								setMessage("$sId $action Successfully", MSG_SUCCESS); 
								if($_REQUEST['manage']=='manage')
									redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy"));
								else
								
									redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy"));
							}
						if(isset($_SESSION['StoreAccessories']))
						unset($_SESSION['StoreAccessories']);
					}
					else
						setMessage($status['message']);
					if($status['status']==false && $flag==0)
						 {
						
							$_POST['name'] = stripslashes($_POST['name']);
							$framework->tpl->assign("PRODUCT", $_POST);
						 } elseif($_REQUEST['id'] && $flag==0 ) 
					 {
					 
					 
						//print_r($objProduct->ProductGet($_REQUEST['id']));
							$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
					 }
		}
		
		else
		{
		//echo $pro_id;
		//exit;
		
		editorInit('description');
		
		//$pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		    
		if($_REQUEST['id'] && $flag==0) {
		   
			//print_r($objProduct->ProductGet($_REQUEST['id']));
				$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
			}
			
				
		}
		
		
		if($_REQUEST['id'])
			{
			$grp_arr=$objCategory->GetProductCategory($_REQUEST['id']);
			if(count($grp_arr)>0)
				{
				$framework->tpl->assign("GROUP_ACC", $grp_arr);
				}
			}
		$select_store=array();
		if($_REQUEST['id'])
			{
			$selected_store[0]['store_id']	=	0;
			$selected_store[0]['other']	=	$objProduct->GetSelectedStore(0,$_REQUEST['id']);
			$stores				=	$objProduct->getRelatedStore($_REQUEST['id']);
			//print_r($stores['id']);
			/*if (count($stores['id'])>0)
				{
				$i=1;
				foreach ($stores['id'] as $store_id)
					{
						$selected_store[$i]['store_id']		=	$store_id;
						$selected_store[$i]['other']		=	$objProduct->GetSelectedStore($store_id,$_REQUEST['id']);
					$i++;
					}
				}*/
			}
			
		//echo "<pre>";
		//print_r($selected_store);
		//exit;
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_PRICE", $objPrice->priceTypeofProduct($_REQUEST['id']));
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign("MADE_IN", $objMade->GetZoneIdOfProduct($_REQUEST['id']));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		//$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup($_REQUEST['id']));
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
//print_r ( $objProduct->GetAllProductCategoty($_REQUEST['id']) ); exit;
		$framework->tpl->assign('CATEGORY', $catArr1);
		if($flag==0){
		$framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($_REQUEST['id'],$_REQUEST['category_id']));
		}
		$framework->tpl->assign('AVAILABLE_ACCESSORIES', $objProduct->GetAllSelectedAccessory($_REQUEST['id']));
		//$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("RELPRODUCT", $objProduct->getRelatedProducts($_REQUEST['id']));
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("MAINSTORE", $objProduct->CheckTheAvailablityStoreAccessory($_REQUEST['id'],'0'));
		$framework->tpl->assign("STORE_LIST", $objProduct->storeGetDetails($_REQUEST['id'],''));
		$framework->tpl->assign("RELSTORE",$stores );
		//print_r($objProduct->getRelatedStore($_REQUEST['id']));
		//exit;
		$framework->tpl->assign("PRODUCT_LIST", $objProduct->productsList1($store_id,$_REQUEST['id'],$_REQUEST['category_id']));
		//print_r($objProduct->productsList1($store_id,$_REQUEST['id'],$_REQUEST['category_id']));
		//exit;
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
		$framework->tpl->assign("DATE", date("H:i:s"));
		
        $framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		$framework->tpl->assign("EXCLUDED_ACCESSORIES", $objAccessory->getExcludedAccessoryList1() );  /* Aug08 */
		

		$framework->tpl->assign('EXCLUDEDIDS', $objProduct->GetAccessoryExclude($_REQUEST['id']));
		$framework->tpl->assign('EXCLUDEDIDS1', $objProduct->GetAccessoryExclude1($_REQUEST['id']));
		
				
		#$framework->tpl->assign("EXCLUDED_ACCESSORY", $objProduct->excludeAccessoryList(0,0));  /* Aug08 */
		//$framework->tpl->assign('SELECTEDSTORE', $selected_store);
		//print_r($objProduct->GetFromProductAccessoryStore($_REQUEST['id']));
		if($_REQUEST['id']>0)
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromProductAccessoryStore($_REQUEST['id']));
		else
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromDefaultAccessoryStore());
		
		//print_r($objProduct->GetFromDefaultAccessoryStore());
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/related_product_form.tpl");
		break;
		
		
		### For new feature in  relative product 18 Dec 2007
		# old in cms module act=list_related
		case "relative_product":
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllRelateGroups($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("RELATE_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("RELATE_NUMPAD", $numpad);
		$framework->tpl->assign("RELATE_LIMIT", $limitList);
		$framework->tpl->assign("RELATE_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/relate_list.tpl");
		break;
		
		case "relative_product_items":
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllRelateGroupItems($_REQUEST['relate_id'],$keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("RELATE_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("RELATE_NUMPAD", $numpad);
		$framework->tpl->assign("RELATE_LIMIT", $limitList);
		$framework->tpl->assign("RELATE_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/relate_list.tpl");

		
		break;
		
		case "relative_product_delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $relate_id)
			{  
			if($objProduct->RelateNameDelete($relate_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=relative_product&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		
		break;
		
		case "relative_productItem_delete":
		extract($_POST);
		if(count($product_id)>0)
		{
		$message=true;
		foreach ($product_id as $prd_id)
			{  
			if($objProduct->RelateProductDelete($_REQUEST['relate_id'],$prd_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=relative_product&flg=R&relate_id=".$_REQUEST["relate_id"]."&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		
		break;
		
		case "related_product_add":
		if(count($product_id)>0)
		{
			$message=true;
			foreach ($product_id as $prd_id)
				{  
				if($objProduct->RelateProductAdd($relate_id,$prd_id)==false)
					$message=false;
				}
		}
		unset($_SESSION['xxrelate_id']);
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=relative_product&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		
		break;
		
		case "addRelateProductName":
		if($_POST['relate_name'] != '')
		{
			$new_relate_id	=	$objProduct->RelateNameAdd($_REQUEST['relate_name']);
			if(count($product_id)>0)
			{
				$message=true;
				foreach ($product_id as $prd_id)
					{  
					if($objProduct->RelateProductAdd($new_relate_id,$prd_id)==false)
						$message=false;
					}
			}
		
		}
		redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=relative_product&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));

		//print_r($_POST);exit;
		
		
		break;
		
		
		### End For new feature in  relative product 18 Dec 2007
		
		case "view_report":
	     
	    $orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listProductViewItems($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		//echo $limitList;
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/productviewreport.tpl");
		break;
	#### End...................................................................................................
	break;
	  
	case "shopreport":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listCartItems($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/shop_list.tpl");
		break;
	case "reportdetail":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_name";
		$user_id=$_REQUEST['uid'];
		if($user_id)
		{
		$userdetails=$objUser->getUserdetails($user_id);
		//print_r($userdetails);
		$framework->tpl->assign("USER_LIST", $userdetails);
		$framework->tpl->assign("USER_ID", $user_id);
		}
		if($pid)
		{
		$Productresult=$objProduct->ProductGet($pid);
		$framework->tpl->assign("PRODUCT_LIST",$Productresult);
		
		//print_r($Productresult);
		}
		
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listUserCartItems($user_id,$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SHOP_LIST",$rs);
		//print_r($rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/shop_detail.tpl");
		break;
		
	case "cartdetail":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_name";
		$user_id=$_REQUEST['uid'];
		$pid=$_REQUEST['pid'];
		$cartid=$_REQUEST['cartid'];
		if($user_id)
		{
		$userdetails=$objUser->getUserdetails($user_id);
		//print_r($userdetails);
		$framework->tpl->assign("USER_LIST", $userdetails);
		$framework->tpl->assign("USER_ID", $user_id);
		}
		if($pid)
		{
		$Productresult=$objProduct->ProductGet($pid);
		$framework->tpl->assign("PRODUCT_LIST",$Productresult);
		
		//print_r($Productresult);
		}
		
		list($rs, $numpad, $cnt, $limitList)	= $objProduct->ShopProductGet($cartid,$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SHOP_LIST",$rs);
		//print_r($rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/shop_accessarydetail.tpl");
		break;
	/**
    * Set the Fields Premissions for store manage For products.
  	* Author   : Salim
  	* Created  : 9-Apr-2008
  	* Modified : 
  	*/
	case "field_permission":
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//$array = $_POST;
			//print_r($array);exit;
			$arr_disable	=	array();
			$arr_enable		=	array();
			$arr_hide		=	array();
			$arr_disable['manage_store']=	'N';
			$arr_enable['manage_store']	=	'Y';
			$arr_hide['manage_store']	=	'H';
			
			for ($i=0; $i<$_REQUEST['count']; $i++) {
				
						if ($_REQUEST['hide'.$i] == 0){
							$id = $_REQUEST['id'.$i];
							$mesage = $objProduct->updateStoreEditable($arr_disable,$id);
						}
						
						if ($_REQUEST['hide'.$i] == 1){
							$id = $_REQUEST['id'.$i];
							$mesage = $objProduct->updateStoreEditable($arr_enable,$id);
						}
						
						if ($_REQUEST['hide'.$i] == 2){
							$id = $_REQUEST['id'.$i];
							$mesage = $objProduct->updateStoreEditable($arr_hide,$id);

						}
							
							if($mesage == true){
								setMessage("Field Permissions Updated Successfully!",MSG_SUCCESS);
							}
			}
		}
		
		$fields_manage_store	=	$objProduct->getStoreEditable(20);
		
		$framework->tpl->assign("FIELD_PERMISSIONS",'products');
		$framework->tpl->assign("FIELD_MANAGE_STORE",$fields_manage_store);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/store_field_permission.tpl");
		break;
		
	case "active":
		
		if($_REQUEST['manage']=="manage"){
			
					$req 			=	&$_REQUEST;
					$req['store_id']=	$store_id;
			
			
					if(!$_POST['name']){
						 $req['name']	=	$objProduct->getvalueifpostnull($req['id'],'name');
					}
					
					if(!$_POST['price']){
						 $req['price']	=	$objProduct->getvalueifpostnull($req['id'],'price');
					}
					if(!$_POST['display_name']){
						 $req['display_name']	=	$objProduct->getvalueifpostnull($req['id'],'display_name');
					}
					
					if(!$_POST['cart_name']){
						 $req['cart_name']	=	$objProduct->getvalueifpostnull($req['id'],'cart_name');
					}
					
					if(!$_POST['description']){
						 $req['description']	=	$objProduct->getvalueifpostnull($req['id'],'description');
					}
					
					if(!$_POST['brand_id']){
						 $req['brand_id']	=	$objProduct->getvalueifpostnull($req['id'],'brand_id');
					}
					if(!$_POST['out_message']){
						 $req['out_message']	=	$objProduct->getvalueifpostnull($req['id'],'out_message');
					}
					if(!$_POST['date_created']){
						 $req['date_created']	=	$objProduct->getvalueifpostnull($req['id'],'date_created');
					}
					if(!$_POST['personalise_with_monogram']){
						 $req['personalise_with_monogram']	=	$objProduct->getvalueifpostnull($req['id'],'personalise_with_monogram');
					}
					if(!$_POST['weight']){
						 $req['weight']	=	$objProduct->getvalueifpostnull($req['id'],'weight');
					}
					if(!$_POST['active']){
						 $req['active']	=	$objProduct->getvalueifpostnull($req['id'],'active');
					}
					
					if(!$_POST['category']){
						$catArr_frmdb = $objProduct->GetAllProductCategoty($_REQUEST['id']);
						$req['category']	=	$catArr_frmdb['category_id'];
				
					}
					if(!$_POST['seo_name']){
						 $req['seo_name']	=	$objProduct->getvalueifpostnull($req['id'],'seo_name');
					}
					
					if(!$_POST['x_co']){
						 $req['x_co']	=	$objProduct->getvalueifpostnull($req['id'],'x_co');
					}
					
					$res_val=$objProduct->ProductGet($req['id']);
					$req['display_order']=$res_val['display_order'];
					
					if($res_val['parent_id']==0){
						$req['parent_id']=$req['id'];
					}
					else{
						$req['parent_id']=$res_val['parent_id'];
						$status['id']=$req['id'];
					}
					
					if($res_val['parent_id']==0){
					$status	=	$objProduct->ProductAddEdit($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);
					}
					$objProduct->changeActive($_REQUEST["stat"],$status['id']);
				
				
			}
			else
			$objProduct->changeActive($_REQUEST["stat"],$_REQUEST['id']);
			
			
		
		
		
		
		redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&category_id={$_REQUEST['category_id']}&product_search={$_REQUEST['product_search']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}"));
		//redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
		break;	
		
	case "predef_gift":
		
		//echo $_SERVER['REQUEST_METHOD'].'<br/>';
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		
			$category 				= $_POST['category'];
			$product_id 			= $_POST['product_id'];
			$product_title 			= trim($_POST['product_title']);
			$product_description 	= trim($_POST['product_description']);
			$product_basic_price 	= trim($_POST['product_basic_price']);
			$product_sale_price 	= trim($_POST['product_sale_price']);
			$art_id 		    	=  $_POST['art_id'];
			
		
			
			if(empty($category))
			{
				setMessage("Select the Gift Category"); 
			}
			else if(empty($product_id))
			{
				setMessage("Select the Gift Type"); 
			}
			else if(($product_id) && $product_id==496 && $_POST['poem_id']=='' )
			{
				setMessage("Select the poem"); 
			}
			else if(empty($art_id))
			{
				setMessage("Select a Art Background"); 
			}
			else if(empty($product_title))
			{
				setMessage("Enter the product title"); 
			}
			else if(empty($product_description))
			{
				setMessage("Enter the product description"); 
			}
			else if(!is_numeric($product_basic_price))
			{
				setMessage("Enter a valid basic price"); 
			}
			else
			{
			
				$proceed=1;
				
				if($_POST['product_id'])
				{
				
					$prd_det = $objProduct->ProductGet($_POST['product_id']);
					
					if($prd_det['name']== 'Single Name Gift'){
					
						if(!$_POST['gender']){
							setMessage("Select a Gender"); 
							$proceed=0;
						}
						
						if(!$_POST['first_name']){
							setMessage("Please enter the name"); 
							$proceed=0;
						}
					}
					
					
					if($prd_det['name']== 'Double Name Gift'){
					
						if(!$_POST['gender2']){
							setMessage("Select a Gender2"); 
							$proceed=0;
						}
						
						if(!$_POST['first_name2']){
							setMessage("Please specify name2"); 
							$proceed=0;
						}
						if(!$_POST['gender']){
							setMessage("Select a Gender1"); 
							$proceed=0;
						}
						
						if(!$_POST['first_name']){
							setMessage("Please specify name1"); 
							$proceed=0;
						}
					}
					
				
				}
			
				 if($_POST['product_id'])
				 {
					$p_det = $objProduct->ProductGet($_POST['product_id']);
					if($p_det['name']== 'Poetry Gift'){
					
						if(!$_POST['poem_id']){
							setMessage("Select the Poem"); 
							$proceed=0;
						}
					}
				}
			
				if($proceed==1)
				{
			
					$array = array();
					$array['category'] 			  =  $category;
					$array['art_id'] 		      =  $_POST['art_id'];
					$array['mat_id'] 		      =  $_POST['mat_id'];
					$array['frame_id'] 		      =  $_POST['frame_id'];
					$array['poem_id'] 		      =  $_POST['poem_id'];
					$array['product_id'] 		  =  $product_id;
					$array['product_title']		  =  addslashes(stripslashes($product_title));
					$array['product_description'] =  addslashes(stripslashes($product_description));
					$array['product_basic_price'] =  $product_basic_price;
					$array['product_sale_price']  =  $product_sale_price;
					$array['product_active']   	  =  $_POST['product_active'];
					$array['store_id'] 			  =	 $store_id;
					$array['frame_type'] 		  =  $_POST['frame_type'];
					$array['meta_title'] 		  =  addslashes(stripslashes($_POST['meta_title']));
					$array['meta_keyword'] 		  =  addslashes(stripslashes($_POST['meta_keyword']));
					$array['meta_description'] 	  =  addslashes(stripslashes($_POST['meta_description']));
								
				
						$product_det = $objProduct->ProductGet($_POST['product_id']);
						
					
						if($product_det['name'] == 'Single Name Gift')
						{
							$array['first_name'] 			  =	 $_POST['first_name'];
							$array['gender'] 				  =	 $_POST['gender'];
							$array['lang'] 					  =	 $_POST['lang'];
							$array['sentiment1'] 			  =	 $_POST['sentiment1'];
							$array['sentiment2'] 			  =	 $_POST['sentiment2'];
						
						}
						elseif($product_det['name'] == 'Double Name Gift')
						{
							$array['first_name'] 			  =	  $_POST['first_name'];
							$array['first_name2'] 			  =	  $_POST['first_name2'];
							$array['gender'] 				  =	  $_POST['gender'];
							$array['gender2'] 				  =	  $_POST['gender2'];
							$array['lang'] 					  =	  $_POST['lang'];
							$array['sentiment1'] 			  =	  $_POST['sentiment1'];
							$array['sentiment2'] 			  =	  $_POST['sentiment2'];
						}
						elseif($product_det['name'] == 'Poetry Gift')
						{
						
							 $_REQUEST['gift_type'] = 'poem';
							 
							 $array['sentiment1'] 			  =	  '';
							 $array['sentiment2'] 			  =	  '';
						
							 $op_count = count($_POST['opt']);
							 $cl_count = count($_POST['col']);
							 
							 $opt_array = $_POST['opt'];
							 $col_array = $_POST['col'];
							 
							 $notes_val = '';
							 
							 $j=0;
							 $m=0;
							 
							 $op_txt= '';
							 $cl_txt= '';
							 
							 for($i =0; $i <$op_count; $i++ )
							 {
							 	$j=$i+1;
							 	$_POST['op'.$j] = $opt_array[$i];
								
								if(($i+1)==$op_count)
								$str='';
								else
								$str='|';
								
								 $op_txt.=$opt_array[$i].$str;
								 
								 $_REQUEST['op'.$j]  =$opt_array[$i];
							 }
							 
							 for($k =0; $k <$cl_count; $k++ )
							 {
							 	$m=$k+1;
							 	$_POST['cl'.$m] = $col_array[$k];
								
								if(($k+1)==$cl_count)
								$str='';
								else
								$str='|';
								
								$cl_txt.=$col_array[$k].$str;
								
							    $_REQUEST['cl'.$m]  =$col_array[$k];
								
							 }
							 
							 $array['op_text'] 			  =	  $op_txt;
							 $array['cl_text'] 			  =	  $cl_txt;
							 
							
						}
				
				if($_REQUEST['id'])
				{
					$id = $_REQUEST['id'];
					//print_r($array);

					if($_REQUEST['manage']=='manage' && $id > 0){

						//echo "<br/>inside manage";
					
						$pdet = $objProduct->getPredefinedGiftByStore($store_id,$id);
						
						if($pdet){
						
								$objProduct->updatePredefinedProduct($array,$_REQUEST['id']);
						}
						else{
							
							$old_id = $_REQUEST['id'];
							unset($_REQUEST['id']);
							$_REQUEST['id']='';
							
							$objProduct->db->query("DELETE FROM product_predefined_store WHERE store_id ='$store_id' and  product_id='$id'");
							$id = $objProduct->insertPredefinedProduct($array);
							$ins	=	array("category_id"=>$category,"product_id"=>$id);
							$objProduct->db->insert("category_predefined_product", $ins);
						}
					
					}
					else{
						//echo "inside else <br>";
						$objProduct->updatePredefinedProduct($array,$_REQUEST['id']);
					}	
				}
				else{
				
					$id = $objProduct->insertPredefinedProduct($array);
					$ins	=	array("category_id"=>$category,"product_id"=>$id);
					$objProduct->db->insert("category_predefined_product", $ins);
				}	
				
					
				if($_REQUEST['manage']=='manage' && $_REQUEST['id']=='')
				{
					$s_array=array();
					$s_array['product_id'] = $id;
					$s_array['store_id']   =	 $store_id;
					
					$objProduct->db->insert("product_predefined_store", $s_array);
				}
				
		/*		if(!$_REQUEST['manage']){
				
					if($_REQUEST['id']==''){
							$sql_pr = "select * from store ";
							$store_list = $objProduct->db->get_results($sql_pr);
							
							for ($i=0;$i<sizeof($store_list);$i++)
							{
								if($store_list[$i]->name=='newhome'|| $store_list[$i]->name=='personaltouch')
								{
									$arr_pr = array();
									$arr_pr["store_id"]		= $store_list[$i]->id;
									$arr_pr["product_id"]   = $id;
									$arr_pr["active"]   	= $_POST['product_active'];
									$objProduct->db->insert("product_predefined_store",$arr_pr);
								}	
							}
					}
				}	
				*/
				
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
				
				
					$art_det = $objAccessory->GetAccessory($_REQUEST['art_id']);
					
					$mat_det = $objAccessory->GetAccessory($_REQUEST['mat_id']);
					
					$frame_det = $objAccessory->GetAccessory($_REQUEST['frame_id']);
					
				
					
					
					
					
					$_REQUEST['art_image'] 		      =  $_POST['art_id'];
					$_REQUEST['extension'] 		      =  $art_det['image_extension'];
					$_REQUEST['image_name'] 		  =  $id;
					if($mat_det){
						$_REQUEST['mat_id'] 		    =  $_POST['mat_id'].'.'.$mat_det['image_extension'];
					}
					else
					$_REQUEST['mat_id'] ='';
					
					if($frame_det){
						$_REQUEST['frame_id'] 		      =  $_POST['frame_id'].'.'.$frame_det['image_extension'];
					}
					else
					$_REQUEST['frame_id'] ='';
					
					$_REQUEST['poem_id'] 		      =  $_POST['poem_id'];
					
			
					
					include(FRAMEWORK_PATH."/modules/ajax_editor/lib/output2.php");
				}
				
				redirect(makeLink(array("mod"=>"$mod", "pg"=>"$pg"), "act=predef_gift_list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
			}
			}
		}
		
		
		if($_REQUEST['id'])
		{
			if(!$_POST)
			{
				$rs = $objProduct->getPredefinedGiftDetails($_REQUEST['id']);
				
			
				
				$_REQUEST += $rs;
			}
			
		}
		
		
		
		if($_REQUEST['art_id'])
		{
			$art = $objAccessory->GetAccessory($_REQUEST['art_id']);
			$framework->tpl->assign("ART_DET",$art);
		}
		if($_REQUEST['mat_id'])
		{
			$art = $objAccessory->GetAccessory($_REQUEST['mat_id']);
			$framework->tpl->assign("MAT_DET",$art);
		}
		if($_REQUEST['frame_id'])
		{
			if($_REQUEST['frame_type'] =='woodframe')
			{
				$art = $objAccessory->GetAccessory($_REQUEST['frame_id']);
				$framework->tpl->assign("WOODFRAME_DET",$art);
			}	
			else
			{
				$art = $objAccessory->GetAccessory($_REQUEST['frame_id']);
				$framework->tpl->assign("FRAME_DET",$art);
			}
				
		}
		if($_REQUEST['poem_id'])
		{
			$acc_det = $objAccessory->GetAccessory($_REQUEST['poem_id']);
			$framework->tpl->assign("POEM_DET",$acc_det);
			
			
		
			
			$opt_count= substr_count($acc_det['poem'], '<Opening'); 
			$cl_count= substr_count($acc_det['poem'], '<Closing');
			
			$framework->tpl->assign("OP_COUNT", $opt_count);
			$framework->tpl->assign("CL_COUNT", $cl_count);
			
			if($_REQUEST['id'] && $rs['op_text']){
			
				$optxt = $rs['op_text'];
				$cltxt = $rs['cl_text'];
				$op_array= explode("|", $optxt);
				$cl_array= explode("|", $cltxt);
				
				$framework->tpl->assign("OP_ARRAY", $op_array);
				$framework->tpl->assign("CL_ARRAY", $cl_array);
			
				
			}
			else if($_POST['opt']){	
			
				$framework->tpl->assign("OP_ARRAY", $_REQUEST['opt']);
				$framework->tpl->assign("CL_ARRAY", $_REQUEST['col']);
			}	
			
			else
			{
				$text_op = $ajax_editor->GetTextBoxValuesO($acc_det['poem'],0);
				$newvar_op	=	explode(">",$text_op);
				
				$text_op = $ajax_editor->GetTextBoxValuesC($acc_det['poem'],0);
				$newvar_cl	=	explode(">",$text_op);
				
				$framework->tpl->assign("OP_ARRAY", $newvar_op);
				$framework->tpl->assign("CL_ARRAY", $newvar_cl);
				
			}
			
		
			
		}
		
		
		
		$product_det = $objProduct->ProductGet($_REQUEST['product_id']);
		$framework->tpl->assign("PRODUCT_NAME",$product_det['name']);
		
		
		
		$framework->tpl->assign("MATS",$objAccessory->listAllAccessoriesOfCatagoryAll($cat_id,$store_id,234,$aid));
		$subacc	=	$objAccessory->GetSubCatOfAcc(245);
		$string = 245;
		for($i=0;$i<count($subacc);$i++)
		{
			$string	=	$string.",".$subacc[$i][category_id];
		}
		$framework->tpl->assign("FRAMES",$objAccessory->listAllAccessoriesOfCatagoryAll($cat_id,$store_id,$string,$aid));
		
		$framework->tpl->assign("POEMS",$objAccessory->listAllAccessoriesOfCatagoryAll($cat_id,$store_id,174,$aid));
		
		$childcategories = $objCategory->getChildCategoriesListById(282);
		
		$framework->tpl->assign("CATEGORY",$childcategories);
		
		$framework->tpl->assign("PRODUCTS",$objProduct->getAllProductsByStoreId());	
		
		
		if($_REQUEST['manage'])
		{	
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/store_predef_gift.tpl");
		}
		else
		{
			//$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/predef_gift.tpl");
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/store_predef_gift.tpl");
		}	
		
		break;	
	case 'predef_gift_list':
	
	
		/*$dir			=	SITE_PATH."/modules/ajax_editor/images/";
		$thumbdir		=	$dir."thumb/";
		$image_name		=	"1550.jpg";
		echo $dir.$image_name;
		
		echo "<br/>";
		thumbnail($dir,$thumbdir,$image_name.'.jpg',202,155,"",$image_name.'.jpg',0);
		echo $thumbdir.$image_name;
		exit;	*/
		
	
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";
		//$_REQUEST["product_search"]	= 	$_POST["product_search"] ? $_POST["product_search"]: $_REQUEST["product_search"];
		if ($_SERVER['REQUEST_METHOD']=='POST')
	 		$_REQUEST["product_search"]	= 	$_POST["product_search"];
		
		
		
		$product_search	= 	$_REQUEST["product_search"] ? $_REQUEST["product_search"] 	: "";
		$category_id = $_REQUEST['category_id'];
		$param			.=	"&product_search=$product_search&category_id=$category_id";
		list($rs, $numpad, $cnt, $limitList) = 	$objProduct->getPredefinedGiftList($_REQUEST['pageNo'],$_REQUEST['limit'],$param,ARRAY_A, $_REQUEST['orderBy'],$store_id,$_REQUEST['category_id'],$_REQUEST['product_search'],0);
		
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);
		
		
		
		$framework->tpl->assign("PREDFD_GIFT_LIST",$rs);
		$framework->tpl->assign("PREDFD_GIFT_NUMPAD", $numpad);
		$framework->tpl->assign("PREDFD_GIFT_LIMIT", $limitList);
		
		$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");


		
		
		$childcategories = $objCategory->getChildCategoriesList('Predefined Gift Categories');
		$framework->tpl->assign("CATEGORY_PARENT",$childcategories);
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/predef_gift_list.tpl");
		break;	
		
	case 'predef_gift_delete':
		
		$id = $_POST['id'];
		if(count($id)>0)
		{
			$message=true;
			foreach ($id as $key=>$val)
			{
				if($store_id)
					$objProduct->PredefinedGiftDeleteStore($val,$store_id);
				else
					$objProduct->PredefinedGiftDelete($val);
			}
			setMessage("Product(s) Deleted Successfully!",MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"$mod", "pg"=>"$pg"), "act=predef_gift_list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		}	
		
		break;	
		
	case 'predef_status':
	
			if($_REQUEST['manage'])
			{
				$objProduct->changePredefinedGiftStatusStore($_REQUEST['current_act'],$_REQUEST['id'],$store_id);
			}
			else
			{
				$objProduct->changePredefinedGiftStatus($_REQUEST['current_act'],$_REQUEST['id']);
			}	
			
			redirect(makeLink(array("mod"=>"$mod", "pg"=>"$pg"), "act=predef_gift_list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]."&product_search=".$_REQUEST["product_search"]."&category_id=".$_REQUEST["category_id"]));
		break;	
		
	case 'predef_inc_fields':
	
		if($_REQUEST['product_id'] == 494)
		echo $framework->tpl->display(FRAMEWORK_PATH."/modules/product/tpl/inc_singlenmae_fields.tpl");
		elseif($_REQUEST['product_id'] == 495)
		echo $framework->tpl->display(FRAMEWORK_PATH."/modules/product/tpl/inc_doublenmae_fields.tpl");
		elseif($_REQUEST['acc_id'])
		{
		
			$acc_det = $objAccessory->GetAccessory($_REQUEST['acc_id']);
			
			 $opt_count= substr_count($acc_det['poem'], '<Opening'); 
			 $cl_count= substr_count($acc_det['poem'], '<Closing');
			
			$framework->tpl->assign("OP_COUNT", $opt_count);
			$framework->tpl->assign("CL_COUNT", $cl_count);
			
			
			$str.='<table cellpadding="0" cellspacing="0" border="0">';
			
			for($i=0;$i< $opt_count; $i++)
			{
			
				$loop= $i+1;
				
				$str.='<tr valign="middle" class=naGrid2>
				  <td width="300" height="25"  align="right"><span class="fieldname">Opening Line '.$loop.' :</span></td>
				  <td width="15" height="25">&nbsp;</td>
				  <td width="442" height="25" align="left"><span class="formfield">
				 <input  type="text" name="opt" id="opt'.$i.'"" value="" size="40" maxlength="255"  />
				  </span></td>
				</tr>';
			}
			
			
			for($i=0;$i< $cl_count; $i++)
			{
			
				$loop= $i+1;
				$str.='<tr valign="middle" class=naGrid2>
				  <td width="300" height="25"  align="right"><span class="fieldname">Opening Line '.$loop.' :</span></td>
				  <td width="15" height="25">&nbsp;</td>
				  <td width="442" height="25" align="left"><span class="formfield">
				 <input  type="text" name="col'.$i.'" id="col'.$i.'" value="" size="40" maxlength="255"  />
				  </span></td>
				</tr>';
			}
			
			
			echo $str;
		
		}
		
		
		exit;		
		break;	
	case "featured_listing":
	
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
				parse_str($_REQUEST['sortOrder'], $sortOrder);
				$linkOrder = $sortOrder['linkOrder'];
				$objProduct->sortPredefinedGifts($linkOrder,$store_id,$_POST['p_type'],$_POST['display_status']);
				setMessage("Gifts Ordered Successfully", MSG_SUCCESS);
		}
	
		$rs = 	$objProduct->getPredefinedGiftList_1($store_id);//print_r($rs);
		
		$i=1;
		foreach($rs as $key=>$val)
		{
			if($val['position']!='')
			$rs[$key]['display_order']=$i++;
		}

		
		
		$framework->tpl->assign("PREDFD_GIFT_LIST",$rs);
		//$framework->tpl->assign("PREDFD_GIFT_NUMPAD", $numpad);
		//$framework->tpl->assign("PREDFD_GIFT_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/featured_listing.tpl");
	
		break;	
		
		case "change_status":
		$stat			=	$_REQUEST['stat'];
		$product_id		=	$_REQUEST['product_id'];
		$p_type			=	$_REQUEST['p_type'];
		$store_id		=	$_REQUEST['store_id'];
		
		$flag 			=	$objProduct->changeDispalyStatus($stat,$product_id,$p_type,$store_id);
		redirect(makeLink(array("mod"=>"$mod", "pg"=>"$pg"), "act=featured_listing&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]));
		
		break;

		case "add_custom_product":
			
			
		
	 $flag=0;
	  include_once(SITE_PATH."/includes/areaedit/include.php");
	 
	 //========================
	
	$framework->tpl->assign('STOREMANAGE', $store_id);
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));
		
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));

	 
	 
	 //=============================
	 
		//echo "1<pre>";
		//print_r($_SESSION['StoreAccessories']);
		//exit;
			//$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		
		//echo "store_id: ".$store_id;
		$status['status']==true;
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		//==============
		
		/////////////
		
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		if($product_id >0 ){
			$ProductCustomFields	=	$objProduct->getProductCustomFields($product_id);
		}
		//print_r($ProductCustomFields);
		$framework->tpl->assign("CUSTOM_FIELDS", $ProductCustomFields);
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			 
			
		
		//$_REQUEST=stripslashes_deep($_REQUEST);
		 $_REQUEST['description'] = stripslashes($_REQUEST['description']);
	
		  if($_POST['seo_name'] !='' && $_REQUEST['manage']!="manage"){
		   $chkStatus=$objProduct->chkSeoNameExists($_POST['seo_name'],$product_id);
		     }
		   
		   if($chkStatus=='true'){
		  	 setMessage("SEO Name already exists", MSG_ERROR); 
			 $framework->tpl->assign("PRODUCT",$_POST);
			 }
			 else{
		
			 
		  $pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		
		 	foreach($pro_id as $pid)
		    {
			 $proid=$pid;
			}
			 $flag=0;
			 if($proid!=0){
				 $flag=1;
			   
			   $framework->tpl->assign("PRODUCT", $objProduct->ProductGet($proid));
			   $framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($proid));
		     }
		
		   
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			
			
			$req['store_id']=	$store_id;
			$fname			=	basename($_FILES['image_extension']['name']);
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			//For swf image
			$swfname   = basename($_FILES['image_swf']['name']);
			$tmp_swf        =   $_FILES['image_swf']['tmp_name'];
			//For 2D image
			$two_d			=	basename($_FILES['two_d_image']['name']);
			$tmp_two_d		=	$_FILES['two_d_image']['tmp_name'];
			//For overlay image
			$overl			=	basename($_FILES['overlay']['name']);
			$tmp_over		=	$_FILES['overlay']['tmp_name'];
			
			//For pdf file
			$pdf_file		=	basename($_FILES['pdf_file']['name']);
			$tmp_pdf_file	=	$_FILES['pdf_file']['tmp_name'];
			//For psd file
			$psd_file		=	basename($_FILES['psd_file']['name']);
			$tmp_psd_file	=	$_FILES['psd_file']['tmp_name'];
			//For ai image
			$ai_image		=	basename($_FILES['ai_image']['name']);
			$tmp_ai_image	=	$_FILES['ai_image']['tmp_name'];
			//for overlay images
			$over2			=	basename($_FILES['overlay2']['name']);
			$tmp_over2		=	$_FILES['overlay2']['tmp_name'];
			$over3			=	basename($_FILES['overlay3']['name']);
			$tmp_over3		=	$_FILES['overlay3']['tmp_name'];
			$over4			=	basename($_FILES['overlay4']['name']);
			$tmp_over4		=	$_FILES['overlay4']['tmp_name'];
			$over5			=	basename($_FILES['overlay5']['name']);
			$tmp_over5		=	$_FILES['overlay5']['tmp_name'];
			//print("enter"); break;
			//echo $_REQUEST['store_id'];
			
			if($_REQUEST['manage']=="manage"){
				if(!$_POST['name']){
					 $req['name']	=	$objProduct->getvalueifpostnull($req['id'],'name');
				}
				
				if(!$_POST['price']){
					 $req['price']	=	$objProduct->getvalueifpostnull($req['id'],'price');
				}
				if(!$_POST['display_name']){
					 $req['display_name']	=	$objProduct->getvalueifpostnull($req['id'],'display_name');
				}
				
				if(!$_POST['cart_name']){
					 $req['cart_name']	=	$objProduct->getvalueifpostnull($req['id'],'cart_name');
				}
				
				if(!$_POST['description']){
					 $req['description']	=	$objProduct->getvalueifpostnull($req['id'],'description');
				}
				
				if(!$_POST['brand_id']){
					 $req['brand_id']	=	$objProduct->getvalueifpostnull($req['id'],'brand_id');
				}
				if(!$_POST['out_message']){
					 $req['out_message']	=	$objProduct->getvalueifpostnull($req['id'],'out_message');
				}
				if(!$_POST['date_created']){
					 $req['date_created']	=	$objProduct->getvalueifpostnull($req['id'],'date_created');
				}
				if(!$_POST['personalise_with_monogram']){
					 $req['personalise_with_monogram']	=	$objProduct->getvalueifpostnull($req['id'],'personalise_with_monogram');
				}
				if(!$_POST['weight']){
					 $req['weight']	=	$objProduct->getvalueifpostnull($req['id'],'weight');
				}
				if(!$_POST['active']){
					 $req['active']	=	$objProduct->getvalueifpostnull($req['id'],'active');
				}
				
				if(!$_POST['category']){
					$catArr_frmdb = $objProduct->GetAllProductCategoty($_REQUEST['id']);
					$req['category']	=	$catArr_frmdb['category_id'];
			
				}
				$res_val=$objProduct->ProductGet($req['id']);
				
 /* 
				if($res_val['parent_id']==0){
					$req['parent_id']=$req['id'];
				}
				else{
					$req['parent_id']=$res_val['parent_id'];
				}
				
*/
			}
			
			$req['page_title']=$_POST['page_title'];
			$req['meta_description']=$_POST['meta_description'];
			$req['meta_keywords']=$_POST['meta_keywords'];
			
			
			
			if(isset($_POST['pro_submit'])){
		
		 	$status	=	$objProduct->ProductAddEdit2($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);
		 	
			}
			if($_POST['remove']=='yes')
			{
			$status	=	$objProduct->ProductAddEdit2($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);

			}

			
			
			
			
			
					if($status['status']==true)
					{
						$product_id=$status['id'];
					
						//print_r($_POST);exit;
						// --------------- Custom Fields ----------------//
						
									 $text_titles			=	$_POST['text_titles'];
									 $text_area_titles		=	$_POST['text_area_titles'];
									 $select_titles			=	$_POST['select_titles'];
									 $radio_titles			=	$_POST['radio_titles'];
									 $select_options		=	$_POST['select_options'];
									 $radio_options			=	$_POST['radio_options'];
									
									 $text_required				=	$_POST['text_required'];
     								 $text_area_required		=	$_POST['text_area_required'];
									 $radio_required			=	$_POST['radio_required'];
									 $select_required			=	$_POST['select_required'];
									 
									 
									// print_r( $_POST);exit;
									 
									 $objProduct->deleteProductCustomFields($product_id);
									
									
									
									 $i	=	0;
									 foreach( $text_titles as  $title){
									 	
										$required			=	$text_required[$i];
									 	
										$custom_fields		=	array('product_id' => $product_id,
																	  'field_type' => 'text', 
																	  'field_name' => mysql_real_escape_string(trim($title)),
																	  'field_options' => '',
																	  'required'   => $required);
										$objProduct->db->insert("product_custom_fields", $custom_fields);
										$i++;	
										
									 }
									
									
									
									 $i	= 0;
									 foreach( $text_area_titles as  $title){
									 	$required			=	$text_area_required[$i];
										$custom_fields		=	array('product_id' => $product_id, 
																	  'field_type' => 'text_area',
																	  'field_name' => mysql_real_escape_string(trim($title)),
																	  'field_options' => '',
																	  'required'   => $required);
										$objProduct->db->insert("product_custom_fields", $custom_fields);	
										//print_r($custom_fields);exit;
										$i++;	
									 }
									
									
									
									 $i	=	0;
									 for( $i = 0; $i < count($select_titles); $i++ ){
									 
									 	$required			=	$select_required[$i];
										$custom_fields		=	array('product_id' => $product_id, 
																	  'field_type' => 'select', 'field_name' => mysql_real_escape_string(trim($select_titles[$i])), 
																	  'field_options' => mysql_real_escape_string(trim($select_options[$i])),
																	  'required'   => $required);
										$objProduct->db->insert("product_custom_fields", $custom_fields);	
										$i++;	
									 }
									
									
									
									 $i	=	0;
									 for( $i = 0; $i< count($radio_titles); $i++ ){
									 
										$required			=	$radio_required[$i];
										$custom_fields		=	array('product_id' => $product_id, 'field_type' => 'radio',
																	  'field_name' => mysql_real_escape_string(trim($radio_titles[$i])), 
																	  'field_options' => mysql_real_escape_string(trim($radio_options[$i])),
																	  'required'   => $required);
										$objProduct->db->insert("product_custom_fields", $custom_fields);
										$i++;	
									 }
								 
						
									// ---------------*** Custom Fields ***--------------------------//					
					
					
					
					
						   
							if( ($status['status'] 	= 	$objProduct->insertRelated($req,$product_id)) == true )
							{
																
								$action = $req['id'] ? "Updated" : "Added";
								
								if($_REQUEST['manage']=='manage')
								{
								
								
									redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=custom_product_list&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy&action=$action&category_id=".$_REQUEST['category_id']."&product_search=".$_REQUEST['product_search']));
									}
								else
								{
								
								
									redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=custom_product_list&product_search=".$product_search."&zoneid=".$zoneid."&brandid=".$brandid."&category_id=".$category_id."&limit=$limit&fId=$fId&sId=$sId&orderBy=$orderBy&action=$action&category_id=".$_REQUEST['category_id']."&product_search=".$_REQUEST['product_search']));
									}
							}
						if(isset($_SESSION['StoreAccessories']))
						unset($_SESSION['StoreAccessories']);
					}
					else
						setMessage($status['message']);
					if($status['status']==false && $flag==0)
						 {
							$_POST['name'] = stripslashes($_POST['name']);
							$framework->tpl->assign("PRODUCT", $_POST);
						 } elseif($_REQUEST['id'] && $flag==0 ) 
					 {
					 		//print_r($objProduct->ProductGet($_REQUEST['id']));
							$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
					 }
					 
				}	 
		}
		
		else
		{
		
		//editorInit('description'); Commented for new editor
		
		//$pro_id 		= 	$_REQUEST["product_id"] ? $_REQUEST["product_id"] : "";
		    
		if($_REQUEST['id'] && $flag==0) {
		 /* For new editor */
	 	    $prodRS=$objProduct->ProductGet($_REQUEST['id']);
		   if($prodRS['description'])$row['description'] = $prodRS['description'];
		   /*New reditor ends*/
			//print_r($objProduct->ProductGet($_REQUEST['id']));
				$framework->tpl->assign("PRODUCT", $objProduct->ProductGet($_REQUEST['id']));
			}
			
				
		}
		
		
		if($_REQUEST['id'])
			{
			$grp_arr=$objCategory->GetProductCategory($_REQUEST['id']);
			if(count($grp_arr)>0)
				{
				$framework->tpl->assign("GROUP_ACC", $grp_arr);
				}
			}
		$select_store=array();
		if($_REQUEST['id'])
			{
			$selected_store[0]['store_id']	=	0;
			$selected_store[0]['other']	=	$objProduct->GetSelectedStore(0,$_REQUEST['id']);
			$stores				=	$objProduct->getRelatedStore($_REQUEST['id']);
			//print_r($stores['id']);
			/*if (count($stores['id'])>0)
				{
				$i=1;
				foreach ($stores['id'] as $store_id)
					{
						$selected_store[$i]['store_id']		=	$store_id;
						$selected_store[$i]['other']		=	$objProduct->GetSelectedStore($store_id,$_REQUEST['id']);
					$i++;
					}
				}*/
			}
			
		//echo "<pre>";
		//print_r($selected_store);
		//exit;
		/*
		** This Creates a dummy array with 100 elements as value Y.
		** tpl contains variable $STOREFIELD to enable/disable the form fields for store/manage.
		** Admin can edit which field is to be enabled/disabled.
		*/
		if(!$_REQUEST['manage']=="manage"){
			$enable_pro_fom_fields	=	array();
				for($i=0;$i<100;$i++){
					$enable_pro_fom_fields[$i] = 'Y';
				}
			$framework->tpl->assign("STOREFIELD",$enable_pro_fom_fields);
		}
	 
		
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		if($_REQUEST['id']>0)
		{
			$framework->tpl->assign("PRODUCT_PRICE", $objPrice->priceTypeofProduct($_REQUEST['id']));
		}	
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign("MADE_IN", $objMade->GetZoneIdOfProduct($_REQUEST['id']));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		//$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup($_REQUEST['id']));
		
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',0);
		
//print_r ( $objProduct->GetAllProductCategoty($_REQUEST['id']) ); exit;
		$framework->tpl->assign('CATEGORY', $catArr1);
		if($flag==0){
			
			if($_REQUEST['id'])
			{
				$framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($_REQUEST['id']));
			}	
			else
			{
				$arr =	array();
				$arr['category_id']	=	$_POST['category'];				
				$framework->tpl->assign('SELECTEDIDS',$arr);
			}	
		}
		$framework->tpl->assign('AVAILABLE_ACCESSORIES', $objProduct->GetAllSelectedAccessory($_REQUEST['id']));
		//$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("RELPRODUCT", $objProduct->getRelatedProducts($_REQUEST['id']));
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("MAINSTORE", $objProduct->CheckTheAvailablityStoreAccessory($_REQUEST['id'],'0'));
		$framework->tpl->assign("STORE_LIST", $objProduct->storeGetDetails($_REQUEST['id'],''));
		$framework->tpl->assign("RELSTORE",$stores );
		//print_r($objProduct->getRelatedStore($_REQUEST['id']));
		//exit;
		$framework->tpl->assign("PRODUCT_LIST", $objProduct->productsList($store_id,$_REQUEST['id'],$_REQUEST['category_id']));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
		$framework->tpl->assign("DATE", date("H:i:s"));
		
        $framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		$framework->tpl->assign("EXCLUDED_ACCESSORIES", $objAccessory->getExcludedAccessoryList1() );  /* Aug08 */
		

		$framework->tpl->assign('EXCLUDEDIDS', $objProduct->GetAccessoryExclude($_REQUEST['id']));
		$framework->tpl->assign('EXCLUDEDIDS1', $objProduct->GetAccessoryExclude1($_REQUEST['id']));
		$framework->tpl->assign("ARTIST_SELECTION", $global['artist_selection'] );
		
		$framework->tpl->assign("PRODUCTCODE",$global['product_code'] );
				
		#$framework->tpl->assign("EXCLUDED_ACCESSORY", $objProduct->excludeAccessoryList(0,0));  /* Aug08 */
		//$framework->tpl->assign('SELECTEDSTORE', $selected_store);
		//print_r($objProduct->GetFromProductAccessoryStore($_REQUEST['id']));

		$childcategories = $objCategory->getChildCategoriesListById(282);
		$framework->tpl->assign("CATEGORY",$childcategories);
	
		if($_REQUEST['id']>0)
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromProductAccessoryStore($_REQUEST['id']));
		else
			$framework->tpl->assign('SELECTEDSTORE', $objProduct->GetFromDefaultAccessoryStore());
		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/add_custom_product.tpl");
		break;	

		

	case "custom_product_list":
	
	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if($_POST['btn_search'])  # product_search
			{
			$_REQUEST['product_search'] = $_POST['product_search'];
			$search_status="1";//search_status   1->true; 0->false
			$product_search=$_POST['product_search'];
			}
			else
			{
			//$_REQUEST['product_search'] = $_REQUEST['product_search'];
			$search_status="0";//search_status   1->true; 0->false
			$product_search="";
			$objProduct->massUpdate($req,$store_id);
			if($_REQUEST['manage']=='manage')
				redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=list&limit=$limit&fId=$fId&sId=$sId&category_id=".$category_id."&brandid=".$brandid));
			else
				redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&limit=$limit&fId=$fId&sId=$sId&zoneid=".$zoneid."&brandid=".$brandid."&category_id=".$category_id));
			}
			
		}
		if($_REQUEST['action'] != '')
		{
		setMessage($_REQUEST['sId']." ".$_REQUEST['action']." Successfully", MSG_SUCCESS); 
		}
		//print_r($_REQUEST);
		
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_order";
			 //print $orderBy;

		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			$framework->tpl->assign('SELECT_DEFAULT1', "-- SELECT BRAND --");
			$framework->tpl->assign('SELECT_DEFAULT2', "-- SELECT MADE IN --");
			}
			$param1			=	"&fId=$fId&sId=$sId";
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("product","index",$param1),$parent_id,$limit));



		if($search_status=="1" || $_REQUEST['product_search'])//search_status   1->true; 0->false
			{
			
			$param			.=	"&search_status=1";//search_status   1->true; 0->false
			$param			.=	"&product_search=$product_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct2($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,$parent_id,$_REQUEST['brandid'],$_REQUEST['zoneid']);

			
			}
			
		else//search_status   1->true; 0->false
			{
			$param			.=	"&product_search=$product_search";
			$param			.=	"&search_status=0";//search_status   1->true; 0->false
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct2($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$store_id,$product_search,$parent_id,$_REQUEST['brandid'],$_REQUEST['zoneid']);

			}

			for($i=0;$i<count($rs);$i++)
				{
	
				$discount_price = $objPrice->GetPriceOfProduct($rs[$i]->id);
				//$discount_price = $objProduct->getMemberPercent($objPrice->GetPriceOfProduct($res[$i]['id']));
				//$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				$rs[$i]->price	=	number_format($rs[$i]->price, 2, '.', '');
				$rs[$i]->discount_price	=	number_format($discount_price, 2, '.', '');
				

				}

			//print_r($rs);
		$framework->tpl->assign("ORDERBY", $orderBy);
		$framework->tpl->assign('ACCESSORYMENU', $objAccessory->GetAllAccessoryGroup());
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);
		$framework->tpl->assign("PRODUCT_LIST", $rs);
		$framework->tpl->assign("MEMBER_TYPE", $member_type);
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);
		$framework->tpl->assign("FOLDER_NAME", $objProduct->GetMenuName('accessory'));
	
		
		$childcategories = $objCategory->getChildCategoriesList('Predefined Gift Categories');
		$framework->tpl->assign("CATEGORY_PARENT",$childcategories);
		
	//	$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,0));	
	//	$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id,$store_id));
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups());
		
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		$framework->tpl->assign("MADE", $objMade->GetAllMades());
		
		$objCategory->getCategoryTree($catArr1,'0','0','PRODUCT_SIDE',$store_id);
		$framework->tpl->assign('CATEGORY', $catArr1);
		$framework->tpl->assign("ZONE", $objMade->GetAllMades());
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category());
		$framework->tpl->assign("STORE", $objProduct->storeGetDetails($_REQUEST['id'],'1',$store_id));
		$framework->tpl->assign("PRODUCT_DISPLAY_NAME", $objProduct->getCMSProductDisplayName());
		$framework->tpl->assign("ACCESSORY_DISPLAY_NAME", $objProduct->getCMSAccessoryDisplayName());
		$framework->tpl->assign('I', 1);
		$framework->tpl->assign("EXCLUDED_ACCESSORY", $objAccessory->getExcludedAccessoryList() );  /* Aug08 */
		$framework->tpl->assign("PRODUCTCODE",$global['product_code'] );
		$framework->tpl->assign('EXCLUDEDIDS', '');
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/custom_product_list.tpl");
		break;		
		
		
		case "active_custom":
		
		if($_REQUEST['manage']=="manage"){
			
					$req 			=	&$_REQUEST;
					$req['store_id']=	$store_id;
			
			
					if(!$_POST['name']){
						 $req['name']	=	$objProduct->getvalueifpostnull($req['id'],'name');
					}
					
					if(!$_POST['price']){
						 $req['price']	=	$objProduct->getvalueifpostnull($req['id'],'price');
					}
					if(!$_POST['display_name']){
						 $req['display_name']	=	$objProduct->getvalueifpostnull($req['id'],'display_name');
					}
					
					if(!$_POST['cart_name']){
						 $req['cart_name']	=	$objProduct->getvalueifpostnull($req['id'],'cart_name');
					}
					
					if(!$_POST['description']){
						 $req['description']	=	$objProduct->getvalueifpostnull($req['id'],'description');
					}
					
					if(!$_POST['brand_id']){
						 $req['brand_id']	=	$objProduct->getvalueifpostnull($req['id'],'brand_id');
					}
					if(!$_POST['out_message']){
						 $req['out_message']	=	$objProduct->getvalueifpostnull($req['id'],'out_message');
					}
					if(!$_POST['date_created']){
						 $req['date_created']	=	$objProduct->getvalueifpostnull($req['id'],'date_created');
					}
					if(!$_POST['personalise_with_monogram']){
						 $req['personalise_with_monogram']	=	$objProduct->getvalueifpostnull($req['id'],'personalise_with_monogram');
					}
					if(!$_POST['weight']){
						 $req['weight']	=	$objProduct->getvalueifpostnull($req['id'],'weight');
					}
					if(!$_POST['active']){
						 $req['active']	=	$objProduct->getvalueifpostnull($req['id'],'active');
					}
					
					if(!$_POST['category']){
						$catArr_frmdb = $objProduct->GetAllProductCategoty($_REQUEST['id']);
						$req['category']	=	$catArr_frmdb['category_id'];
				
					}
					if(!$_POST['seo_name']){
						 $req['seo_name']	=	$objProduct->getvalueifpostnull($req['id'],'seo_name');
					}
					
					if(!$_POST['x_co']){
						 $req['x_co']	=	$objProduct->getvalueifpostnull($req['id'],'x_co');
					}

					if(!$_POST['page_title']){
						 $req['page_title']	=	$objProduct->getvalueifpostnull($req['id'],'page_title');
					}
					
					if(!$_POST['meta_description']){
						 $req['meta_description']	=	$objProduct->getvalueifpostnull($req['id'],'meta_description');
					}
					
					if(!$_POST['meta_keywords']){
						 $req['meta_keywords']	=	$objProduct->getvalueifpostnull($req['id'],'meta_keywords');
					}
					

					
					$res_val=$objProduct->ProductGet($req['id']);
					$req['display_order']=$res_val['display_order'];
					
					if($res_val['parent_id']==0){
						$req['parent_id']=$req['id'];
					}
					else{
						$req['parent_id']=$res_val['parent_id'];
						$status['id']=$req['id'];
					}
					
					if($res_val['parent_id']==0){
					$status	=	$objProduct->ProductAddEdit($req,$fname,$tmpname,$swfname,$tmp_swf,$two_d,$tmp_two_d,$overl,$tmp_over,$pdf_file,$tmp_pdf_file,$psd_file,$tmp_psd_file,$ai_image,$tmp_ai_image,$over2,$tmp_over2,$over3,$tmp_over3,$over4,$tmp_over4,$over5,$tmp_over5);
					}
					$objProduct->changeActive($_REQUEST["stat"],$status['id']);
				
				
			}
			else
			$objProduct->changeActive($_REQUEST["stat"],$_REQUEST['id']);
			
			
		
		
		
		
		redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=custom_product_list&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&category_id={$_REQUEST['category_id']}&product_search={$_REQUEST['product_search']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}"));
		//redirect(makeLink(array("mod"=>"product", "pg"=>"index"), "act=list&aid={$_REQUEST['aid']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
		break;	
		
		
		
	case "del_custom_product":	
//echo "Delete from table";
		//exit;
		$category_id	=	$_REQUEST['category_id'];
		$product_id     =   $_REQUEST['product_id'];
		$ids = $_REQUEST[id];
		
		$message=false;
		if(count($product_id)>0)
					{
			$message=true;
			foreach ($product_id as $id)
				{
				if($objProduct->deleteProduct($id,$store_id)===false)
					$message=false;
				
					
				}
				
		
			if($message==true)
			setMessage("Product(s) Deleted Successfully!");
			}
	//to delete supplier product		
		if(count($ids)>0)
					{
			$message=true;
			foreach ($ids as $id)
				{
				if($objProduct->deleteProduct($id,$store_id)===false)
					$message=false;
									
				}	
				if($message==true)
			setMessage("Product(s) Deleted Successfully!");
			}	
		//			
		if($message==false)
			setMessage("Product(s) Can not Deleted!");
		if(count($ids)>0)
		redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=custom_product_list&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId&product_search={$_REQUEST['product_search']}"));
		else	
		redirect(makeLink(array("mod"=>"store", "pg"=>"product_index"), "act=custom_product_list&pageNo=$pageNo&limit=$limit&category_id=$category_id&fId=$fId&sId=$sId&product_search={$_REQUEST['product_search']}"));
		break;

}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>