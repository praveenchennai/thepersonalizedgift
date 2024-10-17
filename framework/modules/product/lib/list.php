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
include_once(FRAMEWORK_PATH."/modules/editor/lib/class.editor.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");



$cms 			= 	new Cms();
$PaymentObj		=	new Payment();
$ShippingObj	=	new Shipping();
$typeObj 		= 	new paymentType();
$oder			=	new Order();
$user           =   new User();
$editorObj      =   new editor();
$email			= 	new Email();
$objCategory	=	new Category();
$objProduct		=	new Product();
$objPrice		=	new Price();
$objCombination	=	new Combination();
$objMade		=	new Made();
$objAccessory	=	new Accessory();
$cart 			= 	new Cart();
$store			=	new	Store();
$ajax_editor 	= 	new Ajax_Editor();

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";

////for meta tags dispaly for gifts by adarsh
$product_det	=	$objProduct->ProductGet($_REQUEST['id']);	

$page_title			=	$product_det['page_title'];
$meta_keywords		=	$product_det['meta_keywords'];
$meta_description	=	$product_det['meta_description'];


if($store_id){
	/*$productdet	=	$objProduct->ProductGet($product_det['parent_id']);	 */
	 
	if($page_title==''){
		 if($storeDetails['meta_keywords'] !=''){
			$page_title     	= $storeDetails['page_title'];			
		}
		else
			$page_title     	= $global['page_title'];	
	}
	if($meta_keywords ==''){ 
		   if($storeDetails['meta_keywords'] !=''){
				 $meta_keywords     	= $storeDetails['meta_keywords'];			
			}
			else
				 $meta_keywords     	= $global['meta_keywords'];			
		}
		if($meta_description ==''){ 
			 if($storeDetails['meta_description'] !=''){
				 $meta_description     	= $storeDetails['meta_description'];			
			}
			else
				 $meta_description     	= $global['meta_description'];			
		}		
}
else{

		if($page_title ==''){ 
			$page_title    = $global['page_title'];	
		}
		if($meta_keywords ==''){ 
			 $meta_keywords    = $global['meta_keywords'];	
		}
		if($meta_description ==''){ 
			 $meta_description  = $global['meta_description'];		
		}

}

	$framework->tpl->assign("PAGE_TITLE", $page_title);
	$framework->tpl->assign("META_KEYWORD", $meta_keywords);
	$framework->tpl->assign("META_DESCRIPTION", $meta_description);
	
////for meta tags dispaly for gifts by adarsh ends	
	

/* Set Number of Listing Per page :: Added By Aneesh Aravindan*/
if( !$global['product_list_num'] )
$global['product_list_num'] = 9;

switch($_REQUEST['act']) {
	case "desc":	
	    setMessage("");
	
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$productDetails		=	$objProduct->ProductGet($_REQUEST['id']);		
		$discount_price 	= 	 $objPrice->GetPriceOfProduct($product_id );
		$pro_price			=	$discount_price;
		$pro_baseprice		=	$pro_price;


		$bprice				=	$productDetails['price'];
		if ( $bprice>0 && $bprice!=$pro_price)
			$PRODUCT_BASIC_PRICE	=	$bprice;





		$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));

		
		$REL_PROD_ARRAY	=	$objProduct->RelatedProductGet($_REQUEST['id']);
		foreach ($REL_PROD_ARRAY as $RELPROD)	{
				$dis_Price			 =	$objPrice->GetPriceOfProduct($RELPROD->id);
			
				if ( $RELPROD->price>0 && $RELPROD->price!=$dis_Price)
				$RELPROD->base_price = $RELPROD->price;

				$RELPROD->price		 = $dis_Price;
		}


		$framework->tpl->assign("RELATED_PRODUCT",$REL_PROD_ARRAY);

		
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";



		if($product_id>0) {
			$framework->tpl->assign("CURR_CAT_NAME",$objCategory->CategoryGetName ( $objCategory->getCategoryByProduct($product_id) ) );
		}

		//For Getting the accessory
		if($_REQUEST['shop']=='Y'){
			$accessDetails	=	$objAccessory->getAccessDetails($_REQUEST['save_id']);
		}else{
			$accessDetails	=	$objAccessory->getAccessDetails($product_id);
		}		
		//print_r($accessDetails);		
		if($_REQUEST['art_id']!='' || $_REQUEST['shop']=='Y'){		
			
			$save_id		=	$_REQUEST['id'];	
			$saved_artid		=	$_REQUEST['save_id'];
			$art_id			=	$_REQUEST['art_id'];
			$art_arr		=	explode(',',$art_id);	
			$accPrice		=	0;						
			for($i=0;$i<sizeof($art_arr);$i++){
				$getAccessory	    =	$objAccessory->GetAccessory($art_arr[$i]);					
					$accPrice		=	$accPrice+$getAccessory['adjust_price'];		
					$pro_price		=	$pro_price+$getAccessory['adjust_price'];
							
			}			
			
			$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
			$framework->tpl->assign("PRODUCT_PRICE", $pro_price);
			$framework->tpl->assign("PRODUCT_BASEPRICE", $pro_baseprice);
			$framework->tpl->assign("ACC_PRICE", $accPrice);
			$framework->tpl->assign("ACC_CAT", $accessDetails);
			$framework->tpl->assign("SHOP",'Y');
			$framework->tpl->assign("SAVE_ID", $saved_artid);			
			$saved_Art_Details = $objProduct->getSavedWork($saved_artid);
			if($saved_Art_Details[rear_image_extension]!="") $saved_Art_Details[product_price]=$discount_price;
			else $saved_Art_Details[product_price]=$bprice;
			$framework->tpl->assign("SAVED_ART_DETAILS",$saved_Art_Details);
			$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
			$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
			$framework->tpl->assign("STORE_NAMES", $storename);
			$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
			$framework->tpl->assign("content_size",$content_size);
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
			$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
			
	}else{
		$product_Arr	=	$objProduct->ProductGet($_REQUEST['id']);
		
		$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
		$parentProArr	=	$objProduct->ProductGet($product_Arr['parent_id']);
		$base_price		=	$objPrice->GetPriceOfProduct($product_Arr['parent_id']);				
		//For getting build in accessory of Product		
		$build_acc		=	$objAccessory->takeBuildAccessory($_REQUEST['id']);		
		
		$countBuild		=	count($build_acc);
		if($countBuild){
			for($i=0;$i<$countBuild;$i++){
				$getAccessory	=	$objAccessory->GetAccessory($build_acc[$i]->accessory_id);
				$accPrice		=	$accPrice+$getAccessory['adjust_price'];
			}
		}
		$id=$_REQUEST['id'];
		$save_id		=	$_REQUEST['id'];	
		$framework->tpl->assign("PRODUCT_BASEPRICE", $base_price);
		$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
		$framework->tpl->assign("PRODUCT_BASIC_PRICE", $PRODUCT_BASIC_PRICE);
		$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
		$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
		$framework->tpl->assign("STORE_NAMES", $storename);
		$framework->tpl->assign("ACC_PRICE", $accPrice);
		$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
				
		$framework->tpl->assign("content_size",$content_size);
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
		$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
		$framework->tpl->register_object('ProductObj',$objProduct);
		$framework->tpl->assign("COLUMN_RELATED",$objProduct->GetRelatedProduct($product_id,$storename));
		$framework->tpl->assign("DISCRIPTION",1);
		$framework->tpl->assign("ADDTOCART", createButton("ADD TO CART","#","javascript:addtocart($id);"));
		$saved_Art_Details = $objProduct->getSavedWork($save_id);
		$framework->tpl->assign("SAVED_ART_DETAILS",$saved_Art_Details);
		$framework->tpl->assign("RECOMENED", createButton("RECOMMEND TO A FRIEND","#","recommend();"));
		}
      	$acessory_data=$objAccessory->GetProductsALLcatAccessory($_REQUEST['id']);
		$framework->tpl->assign("ACCESSORY_DATA", $acessory_data);
		$saved_Art_price=$saved_Art_Details['product_price']+($saved_Art_Details['product_price']*$acessory_data[0]['adjust_price'])/100;
		$saved_Art_price=number_format($saved_Art_price,2);
		$framework->tpl->assign("SAVED_ART_PRICE",$saved_Art_price);
		$framework->tpl->assign("main_tpl", SITE_PATH."/templates/blue/inner.tpl");
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_description.tpl");
		
	break;
	case "desc_paddle":
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$art_id				=   $_REQUEST["art_id"] ? $_REQUEST["art_id"] : "";
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
		$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
		$framework->tpl->assign("ARTID", $art_id);
		$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
		$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
		$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
		$framework->tpl->assign("STORE_NAMES", $storename);
		$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
		$framework->tpl->assign("content_size",$content_size);
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
		$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
		$framework->tpl->register_object('ProductObj',$objProduct);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_description.tpl");
	break;
	case "pro_desc":		
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
		$rs_productDetails  = $objProduct->ProductGet($_REQUEST['id']);
		if($rs_productDetails['dual_side']!="Y") $rs_productDetails[dual_side]="N";
		
		$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
		$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
		$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
		$framework->tpl->assign("STORE_NAMES", $storename);
		$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
		$framework->tpl->assign("content_size",$content_size);
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
		$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
		$framework->tpl->register_object('ProductObj',$objProduct);
		$framework->tpl->assign("BUTTONURL", BUTTONURL);
		$framework->tpl->assign("PRODUCT_DETAILS",$rs_productDetails);
		$framework->tpl->assign("CUSTOMIZEITBUTTON", createButton("Customize It","#","customizeit()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_details.tpl");
	break;
	case "editor":
		include_once(SITE_PATH."/includes/xmlConfig.php");		
		 if($global['artist_selection']=='Yes')
			{
			echo trim($editorObj->getClipartcat());
			
			}else{
		 	echo trim($editorObj->getClipart());
		 	
			}
	break;	
	case "prod":
	include_once(SITE_PATH."/includes/xmlConfig.php");
			$catId=$_REQUEST["cid"];
			$uId	=	$_REQUEST['uId'];	
			if($catId>0)		
				echo $editorObj->accessory($catId,$uId);
			elseif($uId>0)
				echo $editorObj->myFolderArt($uId);
			exit;
	break;
	case "getquote_list":
		$totalPrice	=	$objPrice->GetPriceOfProduct($_REQUEST['id']);
		if($totalPrice=="Please select a product")
		{ $totalPrice="$0.00";}
		$framework->tpl->assign("PADDLES", $objProduct->getProductAll());
		$framework->tpl->assign("PADDLE_PRICE", $totalPrice);
		$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/getquote_list.tpl");
		break;
	
	case "save_item":
		include_once(SITE_PATH."/includes/editor/BitmapExporter.php");
		exit;
	break;
	case "save_newproduct":
		checkLogin();
		$parent_id		=	$_REQUEST['parent_Id'];
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];		
		$imgName		=	$_REQUEST['savedURL'];		
		$image_type		=	$_REQUEST['image_type'];
		$artcat_id		=	$_REQUEST['artcat_Ids'];
		$rearimgName	=	$_REQUEST['rearImgURL'];
		
		$status['status']==true;
		$catArray=array();
			$parCat	=	$objProduct->getParentCat($parent_id);			
			for($i=0;$i<sizeof($parCat);$i++){			
				$catArray[$i]=$parCat[$i]['category_id'];
			}			
			$proArr			=	$objProduct->ProductGet($parent_id);	
			$pro_price		=	$proArr['price'];					
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_save'])) {		
			$_REQUEST['parent_id']	  =	 $_REQUEST['parent_Id'];
			$proArr			=	$objProduct->ProductGet($_REQUEST['parent_id']);	
			$pro_price		=	$proArr['price'];	
			//$pro_price		  	=	$_REQUEST['price'];	
			$pro_baseprice	=	$proArr['price'];		
			$art_ids		=	$_REQUEST['art_Ids'];
			$pro_weight		=	$proArr['weight'];
			$art_arr		=	explode(',',$art_ids);			
			for($i=0;$i<sizeof($art_arr);$i++){
			$getAccessory	=	$objAccessory->GetAccessory($art_arr[$i]);			
			$pro_price		=	$pro_price+$getAccessory['adjust_price'];					
			}	
			
			$_REQUEST['price']		  	=	$pro_baseprice;	
			//$_REQUEST['price']		  	=	$_REQUEST['price'];				
			//$catArr						=   $objCategory->getCategoryId('My Folder');					
			//$_REQUEST['category']	  		=	array($catArr[0]->category_id);	
			$_REQUEST['category']	  	=	$catArray;
			
				
			//$objCategory->getCategoryId('My Folder')
			//$_REQUEST['prices']			=	$pro_price;
			//$_REQUEST['prices']			=	$_REQUEST['price'];
			$_REQUEST['prices']			=	$pro_price;
			$_REQUEST['price_type']		=	$_REQUEST['price_type'];
			$save_type					=	$_REQUEST['save_type'];	
			$_REQUEST['weight']			=	$pro_weight;		
			if($save_type=='p'){
				$_REQUEST['active']		  =  'Y';	
			}else{
				$_REQUEST['active']		  =  'N';	
			}	
			if($_REQUEST['main_store']){				
				$_REQUEST['hide_in_mainstore']='N';
			}else{
				$_REQUEST['hide_in_mainstore']='Y';
			}
			if($_REQUEST['own_store']){			
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['stores_id']	  =	 $store_id[0];		
				
			}			
			$_REQUEST['product_type'] =	 $_REQUEST['image_type'];
			$image_name				  =  $_REQUEST['image_name'];	
			$image_name_rear		  =  $_REQUEST['image_name_rear'];	
			$_REQUEST['display_name'] = $_REQUEST['name'];
			$_REQUEST['product_price']	=	$pro_price;
			$_REQUEST['sale_price']		=	$pro_price;
			$req 					  =	 &$_REQUEST;
			if($save_type=='p')	{			
				$status		=	$objProduct->ProductAddEdit($req,'','');
				if(count($art_arr)>0){
					$artStatus	=	$objAccessory->saveArtDetails($artcat_id,$status['id'],'N');
				}			
				$artstat		=	$objProduct->savedWork($req,$status['id']);
			}else if($save_type=='b'){
				$artstat	=	$objProduct->savedWork($req,'');					
				$artStatus	=	$objAccessory->saveArtDetails($artcat_id,$artstat,'Y');

				
			}else{
			$artstat		=	$objProduct->savedWork($req,'');
			}			
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$artstat.".jpg";				
				$new_listname		=	$artstat.".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_listname);
				chmod($thumb."$new_listname",0777);
				$save_desc_name	=	$artstat."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
				chmod($thumb."$save_desc_name",0777);
				$save_list_name	=	$artstat."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$save_list_name);
				chmod($thumb."$save_list_name",0777);
				//For dual side Image
				if($image_name_rear!=""){
					copy(SITE_PATH."/modules/product/images/art_image/".$image_name_rear,SITE_PATH."/modules/product/images/saved_work/".$artstat."_rear.jpg");	
					$path=SITE_PATH."/modules/product/images/saved_work/";
					$thumb=$path."thumb/";						
					$save_filename	=	$artstat."_rear.jpg";				
					$new_listname		=	$artstat."_rear.jpg";
					list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
					thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_listname);
					$save_desc_name	=	$artstat."_rear_des.jpg";
					list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
					thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
					$save_list_name	=	$artstat."_rear_List.jpg";
					list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
					thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$save_list_name);
				}		
			}		
			if($status['status']==true){			
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				$new_listname		=	$status['id']."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_listname);
				chmod($thumb."$new_listname",0777);
				$new_name		=	$status['id'].".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_name);
				chmod($thumb."$new_name",0777);
				$desc_file_name	=	$status['id']."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$desc_file_name);
				chmod($thumb."$desc_file_name",0777);
				$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);	
			}
			if($save_type=='p')	{
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=product_list&id=".$status['id']));	
			}else if($save_type=='b'){			
			
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&shop=Y&image_name=".$image_name."&id=".$parent_id."&art_id=".$art_ids."&save_id=".$artstat));
			
			}else{
				if($global['no_productlist']=='Y'){
					redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));	
				}else{
					redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_product&id=".$artstat['id']));	
				}
			}	
		}	
		$objCategory->getCategoryTree($catArr);
		$framework->tpl->assign('CATEGORY', $catArr);
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());	
		$framework->tpl->assign('IMAGE_NAME', $imgName);	
		$framework->tpl->assign('IMAGE_NAME_REAR', $rearimgName);	
		$framework->tpl->assign('XMLVAL', $xml);	
		$framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($_REQUEST['id']));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/save_userproduct.tpl");
	break;
	case "save_paddle":	  
		$parent_id		=	$_REQUEST['parent_Id'];
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];	
		$imgName		=	$_REQUEST['savedURL'];
		$image_type		=	$_REQUEST['image_type'];
		$status['status']==true;
		$savedId			=	$_REQUEST['savedId'];
		$catArray=array();
			$parCat	=	$objProduct->getParentCat($parent_id);			
			for($i=0;$i<sizeof($parCat);$i++){			
				$catArray[$i]=$parCat[$i]['category_id'];
			}			
			$proArr			=	$objProduct->ProductGet($parent_id);	
			$pro_price		=	$proArr['price'];					
		if(isset($_REQUEST['btn_save']) && $_REQUEST['btn_save']!="") {	
			checkLogin();
		 	$_REQUEST['parent_id']	  =	 $_REQUEST['parent_Id'];
			$base_pro_id=$_REQUEST['parent_Id'];
			$proArr			=	$objProduct->ProductGet($_REQUEST['parent_id']);	
			$pro_price		=	$proArr['price'];	
			$pro_baseprice	=	$proArr['price'];		
			$art_ids		=	$_REQUEST['art_Ids'];
			$art_arr		=	explode(',',$art_ids);			
			$_REQUEST['price']		  	=	$pro_baseprice;		
			$_REQUEST['category']	  	=	$catArray;	

			$_REQUEST['prices']			=	$pro_price;
			$_REQUEST['price_type']		=	$_REQUEST['price_type'];
			$save_type					=	$_REQUEST['save_type'];			
			if($save_type=='p'){
				$_REQUEST['active']		  =  'Y';	
			}else{
				$_REQUEST['active']		  =  'N';	
			}	
			if($_REQUEST['main_store']){				
				$_REQUEST['hide_in_mainstore']='N';
			}else{
				$_REQUEST['hide_in_mainstore']='Y';
			}
			if($_REQUEST['own_store']){			
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['stores_id']	  =	 $store_id[0];		
			}			
			$_REQUEST['product_type'] =	 $_REQUEST['image_type'];
			$image_name				  =  $_REQUEST['image_name'];	
			 $_REQUEST['display_name']= $_REQUEST['name'];
			 	unset($_REQUEST['id']);
				$_REQUEST['id']		=	$savedId;
			$req 					  =	 &$_REQUEST;	
			if($save_type=='p' || $save_type=='b')	{			
			//$status	=	$objProduct->ProductAddEdit($req,'','');						
			$artstat	=	$objProduct->savedWork($req);
			}else{
			$artstat	=	$objProduct->savedWork($req,'');
			}	
			//print_r($artstat);
			//exit;
			
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$artstat.".jpg";				
				$new_listname		=	$artstat.".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_listname);
				$save_desc_name	=	$artstat."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
			}		
			//if($status['status']==true){			
				/*copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				$new_listname		=	$status['id']."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$desc_file_name	=	$status['id']."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$desc_file_name);*/
				//$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				//setMessage("$sId $action Successfully", MSG_SUCCESS);	
			//}
			//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc_paddle&id=".$base_pro_id."&art_id=".$artstat));	
			redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));	
		}	
		if(isset($_REQUEST['btn_buy']) && $_REQUEST['btn_buy']!="") {	
			//checkLogin();
		 	$_REQUEST['parent_id']	  =	 $_REQUEST['parent_Id'];
			$_REQUEST['product_id']	  =	 $_REQUEST['parent_Id'];
			$base_pro_id=$_REQUEST['parent_Id'];
			$proArr			=	$objProduct->ProductGet($_REQUEST['parent_id']);	
			$pro_price		=	$proArr['price'];	
			$pro_baseprice	=	$proArr['price'];		
			$art_ids		=	$_REQUEST['art_Ids'];
			$art_arr		=	explode(',',$art_ids);
			/*
			for($i=0;$i<sizeof($art_arr);$i++){
			$getAccessory	=	$objAccessory->GetAccessory($art_arr[$i]);			
			$pro_price		=	$pro_price+$getAccessory['adjust_price'];					
			}	*/
			$_REQUEST['price']		  	=	$pro_baseprice;		
			$_REQUEST['category']	  	=	$catArray;	
			$_REQUEST['prices']			=	$pro_price;
			$_REQUEST['price_type']		=	$_REQUEST['price_type'];
			$save_type					=	$_REQUEST['save_type'];
			
			if($save_type=='p'){
				$_REQUEST['active']		  =  'Y';	
			}else{
				$_REQUEST['active']		  =  'N';	
			}	
			if($_REQUEST['main_store']){				
				$_REQUEST['hide_in_mainstore']='N';
			}else{
				$_REQUEST['hide_in_mainstore']='Y';
			}
			if($_REQUEST['own_store']){			
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['stores_id']	  =	 $store_id[0];		
			}			
			$_REQUEST['product_type'] =	 $_REQUEST['image_type'];
			$image_name				  =  $_REQUEST['image_name'];	
			 $_REQUEST['display_name']= $_REQUEST['name'];
			 	unset($_REQUEST['id']);
				$_REQUEST['id']		=	$savedId;
			$req 					  =	 &$_REQUEST;	
			if($save_type=='p' || $save_type=='b')	{			
			//$status	=	$objProduct->ProductAddEdit($req,'','');						
			$artstat	=	$objProduct->savedWork($req);
			}else{
			$artstat	=	$objProduct->savedWork($req,'');
			}	
			//print_r($artstat);
			//exit;
			
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$artstat.".jpg";				
				$new_listname		=	$artstat.".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_listname);
				$save_desc_name	=	$artstat."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
			}		
			//if($status['status']==true){			
				/*copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				$new_listname		=	$status['id']."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$desc_file_name	=	$status['id']."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$desc_file_name);*/
				//$product_id=$status['id'];				
				//$action = $req['id'] ? "Updated" : "Added";
				//setMessage("$sId $action Successfully", MSG_SUCCESS);	
			//}
			//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc_paddle&id=".$base_pro_id."&art_id=".$artstat));	
								$_REQUEST['user_id'] = $_SESSION['memberid'];
								$_REQUEST['qty'] = 1;
								$cart->addToCart($_REQUEST);
								//redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
								//echo "location.href='".makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view")."';";
		 	redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));	
		}	
		$objCategory->getCategoryTree($catArr);
		
		$framework->tpl->assign("SAVEBUTTON", createButton("SAVE & BUY LATER","#","submitsave()"));
		$framework->tpl->assign("BUYBUTTON", createButton("BUY","#","submitbuy()"));
		$framework->tpl->assign('CATEGORY', $catArr);
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());	
		$framework->tpl->assign('IMAGE_NAME', $imgName);	
		$framework->tpl->assign('SELECTEDIDS', $objProduct->GetAllProductCategoty($_REQUEST['id']));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/save_userproduct.tpl");
	break;

	case "save_newart":	
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];	
		$imgName		=	$_REQUEST['savedURL'];
		$image_type		=	$_REQUEST['image_type'];		
		
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_save'])) {
			$art_ids		=	$_REQUEST['art_Ids'];
			$pro_price		=	$_REQUEST['adjust_price'];
			$image_name		=  $_REQUEST['image_name'];
			$art_arr		=	explode(',',$art_ids);
			for($i=0;$i<sizeof($art_arr);$i++){
				$getAccessory	=	$objAccessory->GetAccessory($art_arr[$i]);			
				$pro_price		=	$pro_price+$getAccessory['adjust_price'];					
			}
			$save_type				  =	 $_REQUEST['save_type'];
			if($_REQUEST['main_store']){				
				$_REQUEST['hide_in_mainstore']='N';
			}else{
				$_REQUEST['hide_in_mainstore']='Y';
			}
			if($_REQUEST['own_store']){						
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['hf2']	  =	 $store_id[0];		
			}	
			
			if($save_type=='p'){
				$_REQUEST['active']			=  'Y';	
			}else{
				$_REQUEST['active']			=  'N';	
			}			
			$_REQUEST['price']				=	$_REQUEST['adjust_price'];
			$_REQUEST['adjust_price']		=	$pro_price;
			$_REQUEST['sale_price']			=	$pro_price;
			$_REQUEST['product_type']		=	$_REQUEST['image_type'] ; 
			$cat							=	$_REQUEST['accessory_category'] ; 
			$_REQUEST['pro_art_category']	=	'';
			if(count($cat>0)){
				for($i=0;$i<count($cat);$i++){
					if($_REQUEST['pro_art_category']==''){
						$_REQUEST['pro_art_category']	=	$cat[$i];	
					}else{
						$_REQUEST['pro_art_category']=$_REQUEST['pro_art_category'].'#'.$cat[$i];
					}
				}
			}		
			$_REQUEST["user_id"] = $_SESSION['memberid'];		
			$req 					  =	&$_REQUEST;
			if($save_type=='p')	{			
			$status		=	$objAccessory->accessoryAddEdit($req,'','');						
			$artstat	=	$objProduct->savedWork($req,$status['id']);
			}else{
			$artstat	=	$objProduct->savedWork($req,'');
			}						
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$artstat.".jpg";				
				$new_listname	=	$artstat.".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_listname);
				$save_desc_name	=	$artstat."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
				$save_list_name	=	$artstat."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$save_list_name);
			}	
			if($status['status']==true){			
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/accessory/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/accessory/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				//upload listing images
				$new_listname		=	$status['id']."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$save_desc_name	=	$status['id']."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
				$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));	
			}	
					
		}		
		$framework->tpl->assign("CATEGORY", $objCategory->getAllCategory_is_in_ui());
		$objCategory->getAllaccessoryCategory($catArr,0,0,28);	
		$framework->tpl->assign("ACCESSORY_CATEGORY", $catArr);
		$framework->tpl->assign('IMAGE_NAME', $imgName);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/save_userart.tpl");
	break;	
	case "edit_product":
		$product_saved_work_id = $_REQUEST['product_saved_work_id'];		
		$parent_id		=	$_REQUEST['parent_Id'];					
		$pro_saveid		=	$_REQUEST['pro_saveid'];		
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];	
		$imgName		=	$_REQUEST['savedURL'];
		$image_type		=	$_REQUEST['image_type'];
		$base_id		=	$_REQUEST['base_id'];
		$artcat_id		=	$_REQUEST['artcat_Ids'];		
		$status['status']==true;
		$catArray=array();		
		$saveWork		=	$objProduct->getSavedWork($product_saved_work_id);	
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_save'])) {
			$_REQUEST['parent_id']	  =	 $_REQUEST['base_id'];
			if($pro_saveid){
				$proArr		=	$objProduct->ProductGet($pro_saveid);
				if(count($proArr)>0){
					$parCat	=	$objProduct->getParentCat($pro_saveid);			
						for($i=0;$i<sizeof($parCat);$i++){			
							$catArray[$i]=$parCat[$i]['category_id'];
						}					
						$pro_price		=	$proArr['price'];
						$_REQUEST['id'] =	$pro_saveid;
				}
			}else{
				$proArr			=	$objProduct->ProductGet($base_id);
				$pro_price		=	$proArr['price'];
				$pro_weight		=	$proArr['weight'];
				$parCat	=	$objProduct->getParentCat($base_id);			
						for($i=0;$i<sizeof($parCat);$i++){			
							$catArray[$i]=$parCat[$i]['category_id'];
						}	
			}						
			$art_ids		=	$_REQUEST['art_Ids'];
			$art_arr		=	explode(',',$art_ids);
			for($i=0;$i<sizeof($art_arr);$i++){
				$getAccessory	=	$objAccessory->GetAccessory($art_arr[$i]);			
				$pro_saveprice	=	$pro_price+$getAccessory['adjust_price'];					
			}	
			$_REQUEST['price']		  =  $pro_price;
			$_REQUEST['prices']		  =  $pro_saveprice;
			$_REQUEST['weight']		  =  $pro_weight;
			$_REQUEST['category']	  =	 $catArray;	
			$save_type				  =	 $_REQUEST['save_type'];
			if($save_type=='p'){
				$_REQUEST['active']		  =  'Y';	
			}else{
				$_REQUEST['active']		  =  'N';	
			}	
			$_REQUEST['product_type'] =	 $_REQUEST['image_type'];
			$image_name				  =  $_REQUEST['image_name'];	
			 $_REQUEST['display_name']= $_REQUEST['name'];							
			$req 					  =	&$_REQUEST;	
			if($save_type=='p')	{			
				$status	=	$objProduct->ProductAddEdit($req,'','');
				unset($_REQUEST['id']);
				$_REQUEST['id']		=	$_REQUEST['parent_Id'];						
				$artstat	=	$objProduct->savedWork($req,$status['id']);
			}else if($save_type=='b'){
				$artstat	=	$objProduct->savedWork($req,'');
			}else{
				unset($_REQUEST['id']);
				$_REQUEST['id']		=	$_REQUEST['parent_Id'];	
				$artstat	=	$objProduct->savedWork($req,'');
			}	
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename		=	$artstat.".jpg";				
				$new_listname		=	$artstat.".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_listname);
				$save_desc_name	=	$artstat."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
				$save_list_name	=	$artstat."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$save_list_name);
				
			}		
			if($status['status']==true){			
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				//upload listing images
				$new_listname		=	$status['id']."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_name);
				$save_desc_name	=	$status['id']."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
				$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);
				if($save_type=='p' || $save_type=='b')	{
					if($global['artist_selection']=='Yes') redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_product&id=".$status['id']));	
					else redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$status['id']));	
				}else{
					redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_product&id=".$status['id']));	
				}	
			}	
		}	
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());	
		$framework->tpl->assign('IMAGE_NAME', $imgName);
		$framework->tpl->assign('SAVE_WORK', $saveWork);				
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/edit_userproduct.tpl");
	break;
	case "edit_art":		
		$parent_id		=	$_REQUEST['parent_Id'];					
		$pro_saveid		=	$_REQUEST['pro_saveid'];		
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];	
		$imgName		=	$_REQUEST['savedURL'];
		$image_type		=	$_REQUEST['image_type'];			
		$rearimgName	=	$_REQUEST['rearImgURL'];
		$status['status']=true;		
		$saveWork			=	$objProduct->getSavedWork($parent_id);	
		$accessoryCat=array();		
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_save'])) {
		$_REQUEST['accessory_category']			=	explode('#',$_REQUEST['pro_art_category']);						
			if($pro_saveid){
				$proArr		=	$objAccessory->GetAccessory($pro_saveid);
				if(count($proArr)>0){
					$_REQUEST['id']	=	$pro_saveid;
				}				
			}
			$art_ids		=	$_REQUEST['art_Ids'];
			$pro_price		=	$_REQUEST['adjust_price'];
			$image_name		=  $_REQUEST['image_name'];
			$image_name_rear=  $_REQUEST['image_name_rear'];	
			$art_arr		=	explode(',',$art_ids);
			for($i=0;$i<sizeof($art_arr);$i++){
				$getAccessory	=	$objAccessory->GetAccessory($art_arr[$i]);			
				$pro_price		=	$pro_price+$getAccessory['adjust_price'];					
			}
			$save_type			=	 $_REQUEST['save_type'];
			if($_REQUEST['main_store']){				
				$_REQUEST['hide_in_mainstore']='N';
			}else{
				$_REQUEST['hide_in_mainstore']='Y';
			}
			if($_REQUEST['own_store']){			
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['hft']	  =	 $store_id[0];		
			}	
			if($save_type=='p'){
				$_REQUEST['active']			=  'Y';	
			}else{
				$_REQUEST['active']			=  'N';	
			}			
			$_REQUEST['price']				=	$_REQUEST['adjust_price'];
			$_REQUEST['adjust_price']		=	$pro_price;
			$_REQUEST['sale_price']			=	$pro_price;
			$_REQUEST['product_type']		=	$_REQUEST['image_type'] ; 	
			$req 					  		=	&$_REQUEST;
			if($save_type=='p')	{			
				$status		=	$objAccessory->accessoryAddEdit($req,'','');
				unset($_REQUEST['id']);
				$_REQUEST['id']					=	$_REQUEST['parent_Id'];						
				$artstat	=	$objProduct->savedWork($req,$status['id']);
			}else if($save_type=='b'){
				$parent_id		=	$_REQUEST['parent_Id'];		
				$req 					  		=	&$_REQUEST;
				$artstat	=	$objProduct->savedWork($req,'');
			}else if($save_type=='a'){
				$parent_id		=	$_REQUEST['parent_Id'];		
				$req 					  		=	&$_REQUEST;
				$artstat	=	$objProduct->savedWork($req,'');
			}else{
				unset($_REQUEST['id']);
				$_REQUEST['id']					=	$_REQUEST['parent_Id'];
				$artstat	=	$objProduct->savedWork($req,'');
			}						
			if($artstat){
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$artstat.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$artstat.".jpg";				
				$new_listname	=	$artstat.".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_listname);
				$save_desc_name	=	$artstat."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
				$save_list_name	=	$artstat."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$save_list_name);
				//For dual side Image
				if($image_name_rear!=""){
					copy(SITE_PATH."/modules/product/images/art_image/".$image_name_rear,SITE_PATH."/modules/product/images/saved_work/".$artstat."_rear.jpg");	
					$path=SITE_PATH."/modules/product/images/saved_work/";
					$thumb=$path."thumb/";						
					$save_filename	=	$artstat."_rear.jpg";				
					$new_listname		=	$artstat."_rear.jpg";
					list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
					thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_listname);
					$save_desc_name	=	$artstat."_rear_des.jpg";
					list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
					thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
					$save_list_name	=	$artstat."_rear_List.jpg";
					list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
					thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$save_list_name);
				}		
			}	
			if($save_type=='b'){			
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&shop=Y&image_name=".$image_name."&id=".$parent_id."&art_id=".$art_ids."&save_id=".$artstat));
			}elseif($save_type=='a'){			
				redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));
			}
			if($status['status']==true){			
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/accessory/".$status['id'].".jpg");	
				$path=SITE_PATH."/modules/product/images/accessory/";
				$thumb=$path."thumb/";						
				$save_filename	=	$status['id'].".jpg";				
				//upload listing images
				$new_listname		=	$status['id']."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$new_listname);
				$new_name		=	$status['id'].".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_name);
				$save_desc_name	=	$status['id']."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
				$product_id=$status['id'];				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));		
			}	
					
		}		
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());	
		$framework->tpl->assign('IMAGE_NAME', $imgName);
		$framework->tpl->assign('SAVE_WORK', $saveWork);		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/edit_userart.tpl");
	
	break;	
	// The following case is only use for taking_art project so do not edit it..........
	case "edit_product_art":
		$product_saved_work_id = $_REQUEST['product_saved_work_id'];		
		$parent_id		=	$_REQUEST['parent_Id'];					
		$pro_saveid		=	$_REQUEST['pro_saveid'];		
		$sell_option	=	$_REQUEST['sell_option'];
		$xml			=	$_REQUEST['saveXML'];	
		$imgName		=	$_REQUEST['savedURL'];
		$image_type		=	$_REQUEST['image_type'];
		$base_id		=	$_REQUEST['base_id'];
		$artcat_id		=	$_REQUEST['artcat_Ids'];		
		$status['status']==true;
		$catArray=array();		
		$saveWork		=	$objProduct->getSavedWork($product_saved_work_id);	
		$accessoryCat=array();		
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_save'])) {
			$art_ids		=	$_REQUEST['art_Ids'];
			$image_name		=  $_REQUEST['image_name'];
			$art_arr		=	explode(',',$art_ids);
			
			$save_type			=	 $_REQUEST['save_type'];
			if($_REQUEST['own_store']){			
				$store_id		=	array();
				$storeDetails	=	$store->getStoreByUser($_SESSION['memberid']);
				$store_id[0]	=	$storeDetails['id'];	
				$_REQUEST['hft']	  =	 $store_id[0];		
			}	
			if($save_type=='p'){
				$_REQUEST['active']			=  'Y';	
			}else{
				$_REQUEST['active']			=  'N';	
			}			
			$_REQUEST['product_type']		=	$_REQUEST['image_type'] ; 	
			$req 					  		=	&$_REQUEST;
			if($pro_saveid){
				if($main_store!=""){
			
				$edit_prod=$objProduct->updateProd($req);	
				$image_name=$_REQUEST[image_name];
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/".$pro_saveid.".jpg");	
				$path=SITE_PATH."/modules/product/images/";
				$thumb=$path."thumb/";						
				$save_filename	=	$pro_saveid.".jpg";				
				//upload listing images
				$new_listname		=	$pro_saveid."_List_.jpg";
				list($list_width,$list_height,)	=	split(',',$framework->config['product_list_image']);
				thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_listname);
				chmod($thumb."$new_listname",0777);
				$new_name		=	$pro_saveid.".jpg";
				list($thumb_width,$thumb_height,)	=	split(',',$framework->config['product_thumb_image']);
				thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$new_name);
				chmod($thumb."$new_name",0777);
				$save_desc_name	=	$pro_saveid."_des_.jpg";
				list($desc_width,$desc_height,)	=	split(',',$framework->config['product_desc_image']);
				thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$save_desc_name);
				chmod($thumb."$save_desc_name",0777);
				$product_id=$pro_saveid;				
				$action = $req['id'] ? "Updated" : "Added";
				setMessage("$sId $action Successfully", MSG_SUCCESS);
				}
			}	
			if($product_saved_work_id){
				$edit_prodart=$objProduct->updateArtProd($req);	
				copy(SITE_PATH."/modules/product/images/art_image/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$product_saved_work_id.".jpg");	
				$path=SITE_PATH."/modules/product/images/saved_work/";
				$thumb=$path."thumb/";						
				$save_filename	=	$product_saved_work_id.".jpg";				
				$new_listname	=	$product_saved_work_id.".jpg";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$new_listname);
				chmod($thumb."$new_listname",0777);
				$save_desc_name	=	$product_saved_work_id."_des_.jpg";
				thumbnail($path,$thumb,$save_filename,144,180,$mode,$save_desc_name);
				chmod($thumb."$save_desc_name",0777);
				$save_list_name	=	$product_saved_work_id."_List_.jpg";
				thumbnail($path,$thumb,$save_filename,202,180,$mode,$save_list_name);
				chmod($thumb."$save_list_name",0777);
			}
			if($save_type=='p' || $save_type=='b')	{
					redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_product&id=".$status['id']));	
			}elseif($save_type=='a'){			
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&shop=Y&image_name=".$image_name."&id=".$parent_id."&art_id=".$art_ids."&save_id=".$artstat));
			}else{			
				redirect(makeLink(array("mod"=>"product", "pg"=>"saved_work"), "act=saved_art"));
			}
			
		}		
		$framework->tpl->assign('IMAGE_NAME', $imgName);
		$framework->tpl->assign('SAVE_WORK', $saveWork);				
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/edit_userproduct.tpl");
	break;
	case "editor_tpl":		
		checkLogin();		
		include_once(SITE_PATH."/includes/xmlConfig.php");
		$product_saved_work_id			=	$_REQUEST['product_saved_work_id'];
		$sellprod			=	$_REQUEST['sellprod'];
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";		
		$parent_id			=	$_REQUEST['parent_id'];
		$img_type			=	$_REQUEST["img_type"];		
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";	
		$pro_save_id 		= 	$_REQUEST["pro_save_id"] ? $_REQUEST["pro_save_id"] : "";	
		$parent_prodID		=	$_REQUEST['parent_id'];
		if($_REQUEST['edit']=='edit'){			
			$edit			=	$_REQUEST['edit'];			
			
		}else if($_REQUEST['edit']=='cust'){			
			$edit				=	"cust";
		}else{
			$edit				=	"new";
		}
		if($edit=='edit'){
			$parentArr			=	$objProduct->ProductGet($parent_id);
			if($global['artist_selection']=='Yes') {
					$proArr['imagePath'] ="images/"."2D_".$parentArr['id'].".".$parentArr['image_extension'];
					if($pro_save_id==0) $proArr['imagePath'] ="images/".$parentArr['id'].".".$parentArr['image_extension'];
			}else{
					$proArr['imagePath'] ="images/".$parentArr['id'].".".$parentArr['image_extension'];
			}
								
			if($_REQUEST['oveImage']){
				$proArr['ovePath'] ="images/".$_REQUEST['oveImage'];
			}else{
				$proArr['ovePath'] ="images/"."OV_".$parentArr['id'].".".$parentArr['overlay'];
			}
			$proArr['id']		=	$parent_id;
			if($img_type=='P'){
				$destPath		=	"edit_product";
				if($global['artist_selection']=='Yes') {
					$destPath		=	"edit_product_art";
				}
			}else{
				$destPath		=	"edit_art";	
			}				
		}else{		
			if($img_type=='P'){
				$proArr			=	$objProduct->ProductGet($_REQUEST['id']);
				if($global['artist_selection']=='Yes') {
					if($proArr['two_d_image']=="")	$image_ext = $proArr['image_extension'];
					else $image_ext = $proArr['two_d_image'];
						//$proArr['imagePath'] ="images/"."2D_".$proArr['id'].".".$image_ext;
					$proArr['imagePath'] ="images/".$proArr['id'].".".$image_ext;
				}else{
					$proArr['imagePath'] ="images/".$proArr['id'].".".$proArr['image_extension'];
				}
				if($_REQUEST['oveImage']){
					$proArr['ovePath'] ="images/".$_REQUEST['oveImage'];
				}else{
					$proArr['ovePath'] ="images/"."OV_".$proArr['id'].".".$proArr['overlay'];
					if($parent_id>0){
						if($global['artist_selection']=='Yes') {
							if($_REQUEST['oveImage']){
								$proArr['ovePath'] ="images/".$_REQUEST['oveImage'];
							}else{
								$proArr['ovePath'] ="images/"."OV_".$parentArr['id'].".".$parentArr['overlay'];
							}
						}
					}
				}	
					$destPath		=	"save_newproduct";
			}else{
				$proArr			=	$objAccessory->GetAccessory($_REQUEST["id"]);
				
				$destPath		=	"save_newart";
				if($proArr['image_extension']){
					$proArr['imagePath'] ="images/accessory/".$proArr['id'].".".$proArr['image_extension'];	
				}else{
					$proArr['imagePath'] ="images/accessory/".$proArr['id'].".jpg";	
				}	
				
			}
		}
					
		$framework->tpl->assign("PRO_WRK_ID",$product_saved_work_id);
		$framework->tpl->assign("PRODUCT", $proArr);
		$framework->tpl->assign("DESTPATH", $destPath);
		$framework->tpl->assign("IMAGE_TYPE", $img_type);
		$framework->tpl->assign("PRO_SAVE_ID", $pro_save_id);
		$framework->tpl->assign("EDIT", $edit);
		$framework->tpl->assign("BASE_PRODUCT",$parent_id);
		$framework->tpl->assign("BASE_PARENT_PRODUCT",$parent_prodID);
		$framework->tpl->assign("SELL_OPTION", $sellprod);
		$framework->tpl->assign("USERID", $_SESSION['memberid']);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/editor.tpl");
	break;
	case "list":
	 	$listall			=	$_REQUEST["show_All_page"] ? $_REQUEST["show_All_page"] : "N";
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "0";
		$base_cat           =   $_REQUEST["base_cat"] ? $_REQUEST["base_cat"] 	: "0";  
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$limit 				= 	$_REQUEST["limit"] ? $_REQUEST["limit"] 	: "6";
		
		
		if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"])
		{	
			$pageNo=$_REQUEST["pageNo1"];
		}		
		$pageNoC 			= 	$_REQUEST["PageNoC"] ? $_REQUEST["PageNoC"] 	: "0";
				
		if($_REQUEST["pageNo2"] && !$_REQUEST["PageNoC"])
		{	
			$pageNoC=$_REQUEST["pageNo2"];
		}
		
		$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order ";
		$orderByC			=	$_REQUEST["orderByC"] 	? $_REQUEST["orderByC"] 	: " display_order ";
		$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
		$param				=	"mod=product&pg=list&act=list&pageNo1=$pageNo&pageNo2=$pageNoC&cat_id=$category_id&show_All=$show_All&orderBy=$orderBy&orderByC=$orderByC&parent_id=$parent_id";
		$is_product			= 	true;
		#-------------------------------------------
		
		#-----------  For html description of categories(02-07-07) --------------
		if($category_id>0)
			{
				if($objCategory->checkForChildcategories($category_id))
				{
					$html_description=$objCategory->getCategoryhtmlDescription($category_id);
					$framework->tpl->assign("HTML_DISCRIPTION",$html_description);
					$framework->tpl->assign("CATEGORY_DETAILS",$objCategory->getCategoryDetails($category_id) );

				}
		
			}
			
			
			
		#-----------  End For html description of categories(02-07-07) --------------
		
		//$catArr1	=	new array();
		//$objCategory->getCategoryTree($catArr1);
		//$framework->tpl->assign('CATEGORYDROPDOWN', $catArr1);
		
		
		if($category_id>0) {
			$framework->tpl->assign("CURR_CAT_NAME",$objCategory->CategoryGetName ( $category_id ) );
		}
		
		
		if($category_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($category_id).' ---');
			}
		else
			{
			//$framework->tpl->assign('SELECT_DEFAULT', "-- Select a Category --");
			$framework->tpl->assign('SELECT_DEFAULT', "");
			}
		$framework->tpl->assign('CATEGORYDROPDOWN', $objCategory->getCategoryTreeParentLevel($category_id));
		#-------------------------------------------
		
		
		if($category_id>0)
			{
			if($objCategory->IscategoryInAccessory($category_id))
				$is_product	= 	false;
			}
		//if($is_product==true)
			//{
			//display product of the selected category and the store.
			if($objProduct->CheckTheAvailablityOfProduct($category_id,$storename)==false || $show_All=='Y')
				{
				if ($listall == "Y"){
				$res	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory_1($category_id,$parent_id,$show_All,$storename,$listall);
				}
				else{
				// commented on 10 dec 09 for the product listing page
				//list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory($category_id,$parent_id,$show_All,$storename,$pageNo,$global['product_list_num'],$param,OBJECT);
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategoryForListing($category_id,$parent_id,$show_All,$storename,$pageNo,6,$param,OBJECT);

				
				}
				if($objCategory->checkForChildcategories($category_id)==true)
				{
					$framework->tpl->assign("ALLCATEGORY"," &raquo; All");
				}
				else
				{
					$framework->tpl->assign("ALLCATEGORY","");
				}
				}
			else 
				{
					if ($listall == "Y"){
					$res	=	$objProduct->GetProductsOfTheStoreAndcategory_1($category_id,$parent_id,$storename,$listall);
					$framework->tpl->assign("ALLCATEGORY","");
					}
				else{	
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndcategory($category_id,$parent_id,$storename,$pageNo,$global['product_list_num'],$param,OBJECT);
				$framework->tpl->assign("ALLCATEGORY","");
				}
				}
				if ($global['single_prod'] == 1){
				if (count($res)==1)
				redirect(makeLink(array("mod"=>"product","pg"=>"list"),"act=listproduct&id=".$res[0]['id']."&parent_id=".$res[0]['id']."&cat_id=".$_REQUEST['cat_id']));
				}
				
				//	$percent = $objProduct->getMemberPercent();
					

			//$objProduct->GetProductsAccessories($category_id,$parent_id,$storename,$pageNo,8,$param,OBJECT, $orderBy);
			/*list($newres, $newnumpad, $newcnt, $newlimit)=$objProduct->GetProductsAccessories($category_id,$parent_id,$storename,$pageNo,8,$param,OBJECT, $orderBy);
			//echo "Product";
			//print_r($res);	
			$arr=array();
			for($i=0;$i<count($newres);$i++)
				{
				
				$arr[$i]['product']	=	$newres[$i]['product'];
				if($newres[$i]['product']=='Y')
				{
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				//$arr[$i]['brand_id']	=	;
				$arr[$i]['price']	=	"$".$objPrice->GetPriceOfProduct($newres[$i]['id']);
				}
				else
				{
				$arr[$i]['ImagePath']	=	"accessory/";
				$arr[$i]['ACT']	=	"access_desc";
				if($newres[$i]['price']>0)
					{
					$arr[$i]['price']	=	$newres[$i]['price']."$";
					}
				else
					{
					$arr[$i]['price']	=	"";
					}
				}
				$arr[$i]['id']	=	$newres[$i]['id'];
				$arr[$i]['name']	=	$newres[$i]['display_name'];
				$arr[$i]['weight']	=	$newres[$i]['weight'];
				$arr[$i]['description']	=	$newres[$i]['description'];
				$arr[$i]['image_extension']	=	$newres[$i]['image_extension'];
				$arr[$i]['active']	=	$newres[$i]['active'];
				}*/
			
				$arr=array();
			for($i=0;$i<count($res);$i++)
				{
				
				$arr[$i]['product']	=	"Y";
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['brand_id']	=	$res[$i]['brand_id'];
				$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				//$discount_price = $objProduct->getMemberPercent($objPrice->GetPriceOfProduct($res[$i]['id']));
				//$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				$arr[$i]['price']	=	number_format($res[$i]['price'], 2, '.', '');
				$arr[$i]['discount_price']	=	number_format($discount_price, 2, '.', '');
				$arr[$i]['weight']	=	$res[$i]['weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['date_created']	=	$res[$i]['date_created'];
				$arr[$i]['group_id']	=	$res[$i]['group_id'];
				$arr[$i]['personalise_with_monogram']	=	$res[$i]['personalise_with_monogram'];
				
				$arr[$i]['is_giftcertificate']	=	$res[$i]['is_giftcertificate'];
				$arr[$i]['active']	=	$res[$i]['active'];

				$bprice	=	"$".number_format($res[$i]['price'], 2, '.', '');
				if ($res[$i]['price']>0 && $bprice!=$arr[$i]['price'])
					$arr[$i]['base_price']	=	$bprice;
				}
				

			//}
		//else//list accessories
			//{
			if($i>0){
				if($listall == "Y"){
				$res	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,6,$param,OBJECT, $orderBy,$listall);
				}
				else{
				list($res, $numpad1, $cnt1, $limitList1)	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,6,$param,OBJECT, $orderBy,$listall);
				}
			}
			else{
				if($listall == "Y"){
				$res	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,6,$param,OBJECT, $orderBy,$listall);
				}
				else{
				list($res, $numpad, $cnt, $limitList)	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,6,$param,OBJECT, $orderBy,$listall);
				}
			}
			//echo "<br>Accessory";
			
			
			//$arr=array();
			if ($_REQUEST["add_accessory"]!="N")	
			{
				for($k=0,$j=$i+1;$k<count($res);$j++,$k++)
					{
					$arr[$j]['product']	=	"N";
					$arr[$j]['ImagePath']	=	"accessory/";
					$arr[$j]['ACT']	=	"access_desc";
					$arr[$j]['id']	=	$res[$k]['id'];
					$arr[$j]['name']	=	$res[$k]['display_name'];
					$arr[$j]['category_id']	=	$res[$k]['category_id'];
					if($res[$k]['adjust_price']>0)
					{
						if($res[$k]['is_price_percentage']=="Y")
						
							$arr[$j]['price']	=	$res[$k]['adjust_price']."%";
						else
						
							$arr[$j]['price']	=	"$".$res[$k]['adjust_price'];
					}
					else
					{
						$arr[$j]['price']	=	"";
						}
					$arr[$j]['weight']	=	$res[$k]['adjust_weight'];
					$arr[$j]['description']	=	$res[$k]['description'];
					$arr[$j]['image_extension']	=	$res[$k]['image_extension'];
					$arr[$j]['is_price_percentage']	=	$res[$k]['is_price_percentage'];
					$arr[$j]['is_weight_percentage']	=	$res[$k]['is_weight_percentage'];
					$arr[$j]['type']	=	$res[$k]['type'];
					$arr[$j]['active']	=	$res[$k]['active'];
					}
			}		
			//}
		//else//list accessories
			//{
			
			//print_r($res);
			
			//exit;
			//}
			list($rsC, $numpadC)=$objCategory->getSubcategories($category_id,$pageNoC,6,$param,OBJECT, $orderByC,'PageNoC');

			$framework->tpl->assign("PRODUCTS",$arr);
			
			$framework->tpl->assign("HeadingImage","products-heading.jpg");			
			$val		= str_replace('&raquo;Categories','',$objCategory->getCategoryPath($category_id,0));
			//$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getPath($category_id,0,$base_cat));
			$framework->tpl->assign("MAIN_PATH",$val);
			$framework->tpl->assign("CATA_ID",$category_id);$framework->tpl->assign("BASE_CAT",$base_cat);
			$framework->tpl->assign("SUB_CATEGORY",$rsC);//print_r($rsC);
			$framework->tpl->assign("DISCRIPTION",$objCategory->getDiscription($category_id));
			$framework->tpl->assign("CAT_ID",$objCategory->getCatId($category_id));
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("CATEGORY_NUMPAD",$numpadC);
			
			
			
			// modified on 02-07-07 for category html description page
			if($html_description !="")
			{
				$category_description_flag	=	1;
				$framework->tpl->assign("CATEGORY_DESCRIPTION_FLAG",$category_description_flag);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/category_html_description.tpl");
			}
			else
			{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			}
			// modified on 02-07-07 for category html description page
             
			/* comment by robin 09-05-2007
			
			$framework->tpl->assign("PRODUCTS",$arr);
			$framework->tpl->assign("HeadingImage","products-heading.jpg");
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			$framework->tpl->assign("SUB_CATEGORY",$objCategory->getSubcategories($category_id));
			$framework->tpl->assign("DISCRIPTION",$objCategory->getDiscription($category_id));
			$framework->tpl->assign("CAT_ID",$objCategory->getCatId($category_id));
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");*/
			//print($numpad);
		break;
	case "list_order":
	 	$listall			=	$_REQUEST["show_All_page"] ? $_REQUEST["show_All_page"] : "N";
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "0";
		$base_cat           =   $_REQUEST["base_cat"] ? $_REQUEST["base_cat"] 	: "0";  
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"])
		{	
			$pageNo=$_REQUEST["pageNo1"];
		}		
		$pageNoC 			= 	$_REQUEST["PageNoC"] ? $_REQUEST["PageNoC"] 	: "0";
				
		if($_REQUEST["pageNo2"] && !$_REQUEST["PageNoC"])
		{	
			$pageNoC=$_REQUEST["pageNo2"];
		}
		
		$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order ";
		$orderByC			=	$_REQUEST["orderByC"] 	? $_REQUEST["orderByC"] 	: " display_order ";
		$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
		$param				=	"mod=product&pg=list&act=list_order&pageNo1=$pageNo&pageNo2=$pageNoC&cat_id=$category_id&show_All=$show_All&orderBy=$orderBy&orderByC=$orderByC&parent_id=$parent_id";
		$is_product			= 	true;
		#-------------------------------------------
		
		#-----------  For html description of categories(02-07-07) --------------
		if($category_id>0)
			{
				if($objCategory->checkForChildcategories($category_id))
				{
					$html_description=$objCategory->getCategoryhtmlDescription($category_id);
					$framework->tpl->assign("HTML_DISCRIPTION",$html_description);
					$framework->tpl->assign("CATEGORY_DETAILS",$objCategory->getCategoryDetails($category_id) );

				}
		
			}
			
			
			
		#-----------  End For html description of categories(02-07-07) --------------
		
		//$catArr1	=	new array();
		//$objCategory->getCategoryTree($catArr1);
		//$framework->tpl->assign('CATEGORYDROPDOWN', $catArr1);
		
		
		if($category_id>0) {
			$framework->tpl->assign("CURR_CAT_NAME",$objCategory->CategoryGetName ( $category_id ) );
		}
		
		
		if($category_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($category_id).' ---');
			}
		else
			{
			//$framework->tpl->assign('SELECT_DEFAULT', "-- Select a Category --");
			$framework->tpl->assign('SELECT_DEFAULT', "");
			}
		$framework->tpl->assign('CATEGORYDROPDOWN', $objCategory->getCategoryTreeParentLevel($category_id));
		#-------------------------------------------
		
		
		if($category_id>0)
			{
			if($objCategory->IscategoryInAccessory($category_id))
				$is_product	= 	false;
			}
		//if($is_product==true)
			//{
			//display product of the selected category and the store.
			if($objProduct->CheckTheAvailablityOfProduct($category_id,$storename)==false || $show_All=='Y')
				{
				if ($listall == "Y"){
				$res	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory_1($category_id,$parent_id,$show_All,$storename,$listall);
				}
				else{
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory($category_id,$parent_id,$show_All,$storename,$pageNo,$global['product_list_num'],$param,OBJECT);
				}
				if($objCategory->checkForChildcategories($category_id)==true)
				{
					$framework->tpl->assign("ALLCATEGORY"," &raquo; All");
				}
				else
				{
					$framework->tpl->assign("ALLCATEGORY","");
				}
				}
			else 
				{
					if ($listall == "Y"){
					$res	=	$objProduct->GetProductsOfTheStoreAndcategory_1($category_id,$parent_id,$storename,$listall);
					$framework->tpl->assign("ALLCATEGORY","");
					}
				else{	
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndcategoryOrder($category_id,$parent_id,$storename,$pageNo,$global['product_list_num'],$param,OBJECT);
				$framework->tpl->assign("ALLCATEGORY","");
				}
				}
				
				$arr=array();
			for($i=0;$i<count($res);$i++)
				{
				
				$arr[$i]['product']	=	"Y";
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['brand_id']	=	$res[$i]['brand_id'];
				$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				//$discount_price = $objProduct->getMemberPercent($objPrice->GetPriceOfProduct($res[$i]['id']));
				//$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				$arr[$i]['price']	=	"$".number_format($discount_price, 2, '.', '');
				$arr[$i]['weight']	=	$res[$i]['weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['date_created']	=	$res[$i]['date_created'];
				$arr[$i]['group_id']	=	$res[$i]['group_id'];
				$arr[$i]['personalise_with_monogram']	=	$res[$i]['personalise_with_monogram'];
				$arr[$i]['is_giftcertificate']	=	$res[$i]['is_giftcertificate'];
				$arr[$i]['active']	=	$res[$i]['active'];

				$bprice	=	"$".number_format($res[$i]['price'], 2, '.', '');
				if ($res[$i]['price']>0 && $bprice!=$arr[$i]['price'])
					$arr[$i]['base_price']	=	$bprice;

				}
			//}
		//else//list accessories
			//{
			if($i>0){
				if($listall == "Y"){
				$res	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,8,$param,OBJECT, $orderBy,$listall);
				}
				else{
				list($res, $numpad1, $cnt1, $limitList1)	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,8,$param,OBJECT, $orderBy,$listall);
				}
			}
			else{
				if($listall == "Y"){
				$res	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,8,$param,OBJECT, $orderBy,$listall);
				}
				else{
				list($res, $numpad, $cnt, $limitList)	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,8,$param,OBJECT, $orderBy,$listall);
				}
			}
			//echo "<br>Accessory";
			//print_r($res);	
			
			//$arr=array();
			if ($_REQUEST["add_accessory"]!="N")	
			{
				for($k=0,$j=$i+1;$k<count($res);$j++,$k++)
					{
					$arr[$j]['product']	=	"N";
					$arr[$j]['ImagePath']	=	"accessory/";
					$arr[$j]['ACT']	=	"access_desc";
					$arr[$j]['id']	=	$res[$k]['id'];
					$arr[$j]['name']	=	$res[$k]['display_name'];
					$arr[$j]['category_id']	=	$res[$k]['category_id'];
					if($res[$k]['adjust_price']>0)
					{
						if($res[$k]['is_price_percentage']=="Y")
						
							$arr[$j]['price']	=	$res[$k]['adjust_price']."%";
						else
						
							$arr[$j]['price']	=	"$".$res[$k]['adjust_price'];
					}
					else
					{
						$arr[$j]['price']	=	"";
						}
					$arr[$j]['weight']	=	$res[$k]['adjust_weight'];
					$arr[$j]['description']	=	$res[$k]['description'];
					$arr[$j]['image_extension']	=	$res[$k]['image_extension'];
					$arr[$j]['is_price_percentage']	=	$res[$k]['is_price_percentage'];
					$arr[$j]['is_weight_percentage']	=	$res[$k]['is_weight_percentage'];
					$arr[$j]['type']	=	$res[$k]['type'];
					$arr[$j]['active']	=	$res[$k]['active'];
					}
			}		
			//}
		//else//list accessories
			//{
			
			//print_r($res);
			
			//exit;
			//}
			list($rsC, $numpadC)=$objCategory->getSubcategories($category_id,$pageNoC,8,$param,OBJECT, $orderByC,'PageNoC');
			$framework->tpl->assign("PRODUCTS",$arr);
			
			$framework->tpl->assign("HeadingImage","products-heading.jpg");			
			$val		= str_replace('&raquo;Categories','',$objCategory->getCategoryPath($category_id,0));
			//$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getPath($category_id,0,$base_cat));
			$framework->tpl->assign("MAIN_PATH",$val);
			$framework->tpl->assign("CATA_ID",$category_id);$framework->tpl->assign("BASE_CAT",$base_cat);
			$framework->tpl->assign("SUB_CATEGORY",$rsC);//print_r($rsC);
			$framework->tpl->assign("DISCRIPTION",$objCategory->getDiscription($category_id));
			$framework->tpl->assign("CAT_ID",$objCategory->getCatId($category_id));
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("CATEGORY_NUMPAD",$numpadC);
			
			
			
			// modified on 02-07-07 for category html description page
			if($html_description !="")
			{
				$category_description_flag	=	1;
				$framework->tpl->assign("CATEGORY_DESCRIPTION_FLAG",$category_description_flag);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/category_html_description.tpl");
			}
			else
			{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			}
			// modified on 02-07-07 for category html description page
             
			/* comment by robin 09-05-2007
			
			$framework->tpl->assign("PRODUCTS",$arr);
			$framework->tpl->assign("HeadingImage","products-heading.jpg");
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			$framework->tpl->assign("SUB_CATEGORY",$objCategory->getSubcategories($category_id));
			$framework->tpl->assign("DISCRIPTION",$objCategory->getDiscription($category_id));
			$framework->tpl->assign("CAT_ID",$objCategory->getCatId($category_id));
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");*/
			//print($numpad);
		break;
		case "list_accessories":	
			echo 'reached list.php';
			if($global['artist_selection']=='Yes')
			  $show = 4;
			else
			  $show = 8;	
			$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " a.id ";
			//$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
			$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
			
		 	$param				=	"mod=product&pg=list&act=$act&cat_id=$category_id&u_id=$user_id&disorder=3";
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryList($category_id,$store_id,$pageNo,$show,$param,OBJECT, $orderBy);
	
			$framework->tpl->assign("ACCESSORY",$res);
			$framework->tpl->assign("ACCESSORYDISP",$res[0]);
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getArtCategoryPath($category_id,0));
			$framework->tpl->assign("ACCESSORY_NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_accessoy_listing.tpl");
		break;

	case "list_accessories_frame":	// 	For Displaying Mat Frames in an IFRAME - Retheesh	

			$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " a.id ";
			//$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
			$search_fields = 'type';
			$search_values = 'mat';
			$search_crt    = $_REQUEST["serach_crt"]; 
			$category_id="";
			$param				=	"mod=product&pg=list&act=$act&cat_id=$category_id&art=".$_REQUEST["art"]."&search_fields=$search_fields&search_values=$search_values&search_crt=$search_crt&poem=".$_REQUEST["poem"];			
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryLists($category_id,$store_id,$pageNo,50,$param,OBJECT, $orderBy,$search_fields,$search_values,$search_crt);
			
			$framework->tpl->assign("ACCESSORY",$res);
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			$framework->tpl->assign("ACCESSORY_NUMPAD",$numpad);
			
			$category_id="245,246,248,247";
			$search_fields = 'type';
			$search_values = 'frame';
			
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryLists($category_id,$store_id,$pageNo,50,$param,OBJECT, $orderBy,$search_fields,$search_values,$search_crt);
			$framework->tpl->assign("ACCESSORY_FRAME",$res);
			$framework->tpl->assign("ACCESSORY_FRAME_NUMPAD",$numpad);
			
			$childcategories = $objCategory->getChildCategoriesListById3(245,$store_id);
			$framework->tpl->assign("CATEGORY1",$childcategories);
			
			$childcategories = $objCategory->getChildCategoriesListById3(252,$store_id);
			$framework->tpl->assign("CATEGORY2",$childcategories);
			
			
		
			if ($_REQUEST["poem"]=="true")
			{
				
				//$framework->tpl->display(SITE_PATH."/modules/product/tpl/user_accessory_frame_poem.tpl");
				$framework->tpl->display(SITE_PATH."/modules/product/tpl/user_accessory_frame_art.tpl");
			}
			elseif ($_REQUEST["art"]=="true")
			{
				
				$framework->tpl->display(SITE_PATH."/modules/product/tpl/user_accessory_frame_art.tpl");
			}
			else 
			{
				$framework->tpl->display(SITE_PATH."/modules/product/tpl/user_accessory_frame_list.tpl");
			}	
			exit;
		break;	
	case "listproduct":	
	
	        $listall			=	$_REQUEST["show_All_page"] ? $_REQUEST["show_All_page"] : "N";
			$parent_id 		    = 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "";	
			$product_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "";
			$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
			$product_details = $objProduct->ProductGet($_REQUEST['id']);			
			$discount_price 	= 	 $objPrice->GetPriceOfProduct($_REQUEST['id']);
			$product_details['base_price'] = $product_details['price'];//for personalizedgift:salim
			$product_details['price'] = $discount_price;
			$product_details['discount_price'] = $discount_price;
			$framework->tpl->assign("CID",$category_id);
			$framework->tpl->assign("PRODUCT_DETAILS",$product_details);
			$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
			$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
			$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
			$framework->tpl->assign("STORE_NAMES", $storename);
			$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
			$framework->tpl->assign("content_size",$content_size);
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
			 
			$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
			$framework->tpl->register_object('ProductObj',$objProduct);			
			
			
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order ";
			$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
			$param				=	"mod=product&pg=list&act=listproduct&cat_id=$category_id&show_All=$show_All&orderBy=$orderBy&parent_id=$parent_id&id=$parent_id";
			$is_product			= 	true;
		if($category_id>0)
			{
			$arr			=	$objCategory->CategoryGet($category_id);
			if($arr['accessory_category']=="Y")
				$is_product	= 	false;
			}
		if($is_product==true)
			{
			
			//display product of the selected category and the store.
			if($objProduct->CheckTheAvailablityOfProduct($category_id,$storename)==false || $show_All=='Y')
				{
			
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory($category_id,$parent_id,$show_All,$storename,$pageNo,$global['product_list_num'],$param,OBJECT, $orderBy);
				
				$framework->tpl->assign("ALLCATEGORY"," &raquo; All");
				}
			else 
				{
				
				list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndcategory($category_id,$parent_id,$storename,$pageNo,$global['product_list_num'],$param,OBJECT, $orderBy);
				$framework->tpl->assign("ALLCATEGORY","");
				}
				$arr=array();
			for($i=0;$i<count($res);$i++)
				{
				$arr[$i]['product']	=	"Y";
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['brand_id']	=	$res[$i]['brand_id'];
				$arr[$i]['price']	=	"$".$objPrice->GetPriceOfProduct($res[$i]['id']);
				$arr[$i]['weight']	=	$res[$i]['weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['date_created']	=	$res[$i]['date_created'];
				$arr[$i]['group_id']	=	$res[$i]['group_id'];
				$arr[$i]['personalise_with_monogram']	=	$res[$i]['personalise_with_monogram'];
				$arr[$i]['is_giftcertificate']	=	$res[$i]['is_giftcertificate'];
				$arr[$i]['active']	=	$res[$i]['active'];
				}
			}
		else//list accessories
			{
			if($listall == "Y"){
				$res	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,8,$param,OBJECT, $orderBy,$listall);
				}else{
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->GetAccessoriesOfTheStoreAndcategory($category_id,$storename,$pageNo,8,$param,OBJECT, $orderBy, $listall);
			}
			$arr=array();
			for($i=0;$i<count($res);$i++)
				{
				$arr[$i]['product']	=	"N";
				$arr[$i]['ImagePath']	=	"accessory/";
				$arr[$i]['ACT']	=	"access_desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['category_id']	=	$res[$i]['category_id'];
				if($res[$i]['adjust_price']>0)
				{
					if($res[$i]['is_price_percentage']=="Y")
						$arr[$i]['price']	=	$res[$i]['adjust_price']."%";
					else
						$arr[$i]['price']	=	$res[$i]['adjust_price']."$";
				}
				else
				{
					$arr[$i]['price']	=	"";
					}
				$arr[$i]['weight']	=	$res[$i]['adjust_weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['is_price_percentage']	=	$res[$i]['is_price_percentage'];
				$arr[$i]['is_weight_percentage']	=	$res[$i]['is_weight_percentage'];
				$arr[$i]['type']	=	$res[$i]['type'];
				$arr[$i]['active']	=	$res[$i]['active'];
				}
			//print_r($res);
			
			//exit;
			}
#for 3 image buttons			
	$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Continue","javascript:void(0);" ,"submit_form();return false;"));
	$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
#END
			$framework->tpl->assign("PRODUCTS",$arr);
			//print_r($arr);exit;
			$framework->tpl->assign("HeadingImage","products-heading.jpg");
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,0));
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_compleatedproduct.tpl");
			//print($numpad);
			
			
	break;	
	case "recommand":
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		//echo $product_id;
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['your_name']))
			{
				$error	=	"Enter Your Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['your_email']))
			{
				$error	=	"Enter Your Email";
				$flag	=	1;
			}
			else if(empty($_REQUEST['friend_name']))
			{
				$error	=	"Enter Friend's Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['friend_email']))
			{
				$error	=	"Enter Friend's Email";
				$flag	=	1;
			}
			else if(empty($_REQUEST['message']))
			{
				$error	=	"Enter Your Message";
				$flag	=	1;
			}
			
			//$req 			=	&$_REQUEST;
			//echo "Manage";
			if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['your_email'],
											"to"	=>	$_REQUEST['friend_email']);
				$dynamic_vars 	=	array(	"YOUR_NAME"		=>	$_REQUEST['your_name'],
											"FRIEND_NAME"	=>	$_REQUEST['friend_name'],
											"LINK"			=>	SITE_URL.'/'.makeLink(array("mod"=>"product", "pg"=>"list"),"act=desc&id=$product_id"),
											"CONTENT"		=>	trim(stripslashes(nl2br($_REQUEST['message']))));
											
				if($email->send('sent_to_friend', $mail_header, $dynamic_vars))
					redirect(makeLink(array("mod"=>"product", "pg"=>"list"),"act=recommand_success&id=$product_id"));
				}
			else
				{
				setMessage($error, MSG_SUCCESS);
				}
			}//if($_SERVER['REQUEST_METHOD'] == "POST") {
			$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
			$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/recommand.tpl");
		break;
	case "recommand_success":
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		setMessage("An e-mail sent to your friend Successfully", MSG_SUCCESS);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/recommand_success.tpl");
		break;
	case "access_desc":
		$accessory_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";		
		$framework->tpl->assign("ACCESSORY_DETAILS", $objAccessory->GetAccessory($_REQUEST['id']));
		$framework->tpl->assign("STORE_NAMES", $storename);
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.id ";			
		$param				=	"mod=product&pg=list&act=$act";		
		if($global['artist_selection']=='Yes') {
			$framework->tpl->assign("USER_DETAIL",$objProduct->GetArtUserName($_REQUEST['id']));	
			//$objProduct->GetArtUserName($_REQUEST['id']);
		}	
		list($res, $numpad, $cnt, $limitList)	=	$objAccessory->getProductsByAccessory($accessory_id,$storename,$pageNo,8,$param,OBJECT, $orderBy);
		$framework->tpl->assign("PRODUCTS",$res);
		$framework->tpl->assign("HeadingImage","products-heading.jpg");
		$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
		#print_r($objCategory->getCategoryPathAccessory($category_id,$accessory_id));
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getArtCategoryPath($category_id,$accessory_id));
		$framework->tpl->assign("PREVIOUS_NEXT",$objAccessory->GetNextPreviousAccessory($accessory_id,$category_id,$storename));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_accessory_description.tpl");
		break;
	case "groups":
	//echo "groups";
		$heading			=	$_REQUEST["head"] 	? $_REQUEST["head"] 		: "";
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"])
		{	
			$pageNo=$_REQUEST["pageNo1"];
		}		
		$group_id 			= 	$_REQUEST["group_id"] ? $_REQUEST["group_id"] 	: "";
		//echo "$group_id";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order ";
		$param="mod=product&pg=list&act=groups&group_id=$group_id&orderBy=$orderBy&head=$head";
	list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductGroups($group_id,$storename,$pageNo,8,$param,OBJECT, $orderBy);
			if($heading)
			$framework->tpl->assign("CATEGORY_PATH",$heading);
			else	
			$framework->tpl->assign("CATEGORY_PATH",$res[0]['groupname']);
			//echo "Product";
			//print_r($res);	
			$arr=array();
			for($i=0;$i<count($res);$i++)
				{
				
				$arr[$i]['product']	=	"Y";
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['brand_id']	=	$res[$i]['brand_id'];
				$arr[$i]['price']	=	"$".$objPrice->GetPriceOfProduct($res[$i]['id']);
				$arr[$i]['weight']	=	$res[$i]['weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['date_created']	=	$res[$i]['date_created'];
				$arr[$i]['group_id']	=	$res[$i]['group_id'];
				$arr[$i]['personalise_with_monogram']	=	$res[$i]['personalise_with_monogram'];
				$arr[$i]['is_giftcertificate']	=	$res[$i]['is_giftcertificate'];
				$arr[$i]['active']	=	$res[$i]['active'];
				
				}
			$framework->tpl->assign("PRODUCTS",$arr);
			$framework->tpl->assign("HeadingImage","products-heading.jpg");	
			$framework->tpl->assign("MAIN_PATH","");
			$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			break;
			case "cat_list_user":
				list($rs, $numpad)=$objCategory->getSubcategories($category_id,$pageNoC,8,$param,OBJECT, $orderByC,'PageNoC',$store_id);				
				$framework->tpl->assign("PRODUCTS",$rs);
				$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_cat_listing.tpl");
				break;		
			case "product_list":			
					$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "0";
					$base_cat           =   $_REQUEST["base_cat"] ? $_REQUEST["base_cat"] 	: "0";  
					$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
					$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
					if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"]){			
						$pageNo=$_REQUEST["pageNo1"];
					}		
					$pageNoC 			= 	$_REQUEST["PageNoC"] ? $_REQUEST["PageNoC"] 	: "0";
					if($_REQUEST["pageNo2"] && !$_REQUEST["PageNoC"]){			
						$pageNoC=$_REQUEST["pageNo2"];
					}	
					$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
					$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order ";
					$orderByC			=	$_REQUEST["orderByC"] 	? $_REQUEST["orderByC"] 	: " display_order ";
					$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
					$param				=	"mod=product&pg=list&act=product_list&pageNo1=$pageNo&pageNo2=$pageNoC&cat_id=$category_id&show_All=$show_All&orderBy=$orderBy&orderByC=$orderByC&parent_id=$parent_id";
					$is_product			= 	true;
					if($global['artist_selection']=='Yes'){
			  			$view_all 			=   $_REQUEST["view_all"] ? $_REQUEST["view_all"] 	: "0";
					$param1 ="index.php?mod=product&pg=list&act=product_list&cat_id=$category_id&show_All=$show_All&view_all=1";
						if ($view_all>0)
						$show = "All";
						else
						$show = 6;
					}else{
			  			$show = 8;
					}	
					
					list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory($category_id,$parent_id,$show_All,$storename,$pageNo,$show,$param,OBJECT, $orderBy);	
					//list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryList($category_id,$storename,$pageNo,6,$param,OBJECT, $orderBy);
					$arr=array();
			        for($i=0;$i<count($res);$i++)
				    {
				
				          $res[$i]['ACT']	=	"desc";
						  $discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
						 // $discount_price =  $objProduct->getMemberPercent($res[$i]['price']);
						  $res[$i]['price'] = "$".$discount_price;
				    }		
							
					$framework->tpl->assign("PRODUCTS",$res);					
					
					$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);		
					$framework->tpl->assign("PRODUCT_LIMITLIST",$limitList);	
					$framework->tpl->assign("PARAM",$param1);				
					list($rsC, $numpadC)=$objCategory->getSubcategories($category_id,$pageNoC,8,$param,OBJECT, $orderByC,'PageNoC',$store_id);				
					if($category_id>0)
						{
							$framework->tpl->assign('SELECT_DEFAULT', $objCategory->getCategoryname($category_id));
						}
					//$framework->tpl->assign("CATEGORY_PATH",$objCategory->getPath($category_id,0,$base_cat));
					$framework->tpl->assign("MAIN_PATH",$val);
					$framework->tpl->assign("SUB_CATEGORY",$rsC);					
					$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathTaking($category_id,0));
					if($objCategory->checkForChildcategories($category_id)==true){				
						$framework->tpl->assign("ALLCATEGORY"," &raquo; All");
					}
					else{				
						$framework->tpl->assign("ALLCATEGORY","");
					}					
					$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			break;
			case "new_product_list":			
					$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "0";
					$base_cat           =   $_REQUEST["base_cat"] ? $_REQUEST["base_cat"] 	: "0";  
					$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
					$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
					if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"]){			
						$pageNo=$_REQUEST["pageNo1"];
					}		
					$pageNoC 			= 	$_REQUEST["PageNoC"] ? $_REQUEST["PageNoC"] 	: "0";
					if($_REQUEST["pageNo2"] && !$_REQUEST["PageNoC"]){			
						$pageNoC=$_REQUEST["pageNo2"];
					}	
					$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
					$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.date_created DESC ";
					$orderByC			=	$_REQUEST["orderByC"] 	? $_REQUEST["orderByC"] 	: " display_order ";
					$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
					$param				=	"mod=product&pg=list&act=new_product_list&pageNo1=$pageNo&pageNo2=$pageNoC&cat_id=$category_id&show_All=$show_All&orderBy=$orderBy&orderByC=$orderByC&parent_id=$parent_id";
					$is_product			= 	true;
					
					list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsOfTheStoreAndAllSubcategory($category_id,$parent_id,$show_All,$storename,$pageNo,10,$param,OBJECT, $orderBy);	
					
					
					$arr=array();
			        for($i=0;$i<count($res);$i++)
				    {
				
				          $res[$i]['ACT']	=	"desc";
						  $discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
						  $res[$i]['price'] = "$".$discount_price;
				    }		
								
					$framework->tpl->assign("PRODUCTS",$res);					
					
					#$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);						
					$framework->tpl->assign("ALLCATEGORY","&raquo; New Arrivals");
							
					$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			break;
			case "group_prod_list":			
					$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] 	: "0";
					$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
					$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
					if($_REQUEST["pageNo1"] && !$_REQUEST["pageNo"]){			
						$pageNo=$_REQUEST["pageNo1"];
					}		
					$pageNoC 			= 	$_REQUEST["PageNoC"] ? $_REQUEST["PageNoC"] 	: "0";
					if($_REQUEST["pageNo2"] && !$_REQUEST["PageNoC"]){			
						$pageNoC=$_REQUEST["pageNo2"];
					}	
					$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
					$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " p.display_order";
					$orderByC			=	$_REQUEST["orderByC"] 	? $_REQUEST["orderByC"] 	: " display_order ";
					$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] 	: "0";
					$param				=	"mod=product&pg=list&act=group_prod_list&pageNo1=$pageNo&pageNo2=$pageNoC&id=$id&show_All=$show_All&orderBy=$orderBy&orderByC=$orderByC&parent_id=$parent_id";
					$is_product			= 	true;
					
					
					list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetProductSettingItemPagewise($id,$parent_id,$show_All,$storename,$pageNo,$global['product_list_num'],$param,OBJECT, $orderBy);	
					
					
					
					$arr=array();
			        for($i=0;$i<count($res);$i++)
				    {
				
						 $bprice	= number_format($res[$i]['price'], 2, '.', '');  //"$".
 

				          $res[$i]['ACT']	=	"desc";
						  $discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
						  $res[$i]['price'] = "$".$discount_price;

							 if ($bprice>0 && $bprice!=$discount_price)
							 $res[$i]['base_price']	=	"$".$bprice;


							

				    }		
								
					$framework->tpl->assign("PRODUCTS",$res);					
					
					$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);						
					
					
					$framework->tpl->assign("CURR_CAT_NAME",$objProduct->GetProductsGroupsName ( $id ) );
					
					
					$framework->tpl->assign("ALLCATEGORY","&raquo;&nbsp;".$objProduct->GetProductsGroupsName ( $id ) );
							
					$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
			break;
			case "upload":
			
				include_once(SITE_PATH."/includes/areaedit/include.php");
				if($_REQUEST['id']){
					$workArr	=	$objProduct->getSavedWork($_REQUEST['id']);
				}				
				if($_SERVER['REQUEST_METHOD'] == "POST") {										
					$fname				=	basename($_FILES['image_extension']['name']);
					$ftype				=	$_FILES['image_extension']['type'];
					$tmpname			=	$_FILES['image_extension']['tmp_name'];
					$path_parts 		= 	pathinfo($fname);
					$extension			=	$path_parts['extension'];					
					$_REQUEST['active'] =	'Y';					
						if($_REQUEST['main_store']){				
							$_REQUEST['hide_in_mainstore']='N';
						}else{
							$_REQUEST['hide_in_mainstore']='Y';
						}
						if($_REQUEST['own_store']){						
							$store_id				=	array();
							$storeDetails			=	$store->getStoreByUser($_SESSION['memberid']);
							$store_id[0]			=	$storeDetails['id'];	
							$_REQUEST['hf2']	  	=	$store_id[0];		
						}	
						$catArr						=  $objCategory->getCategoryId('My Folder');					
						//$_REQUEST['accessory_category']	  	=	array($catArr[0]->category_id);	
						$cat							=	array($catArr[0]->category_id) ;
						$_REQUEST['pro_art_category']	=	'';
						if(count($cat>0)){
							for($i=0;$i<count($cat);$i++){
								if($_REQUEST['pro_art_category']==''){
									$_REQUEST['pro_art_category']	=	$cat[$i];	
								}else{
									$_REQUEST['pro_art_category']=$_REQUEST['pro_art_category'].'#'.$cat[$i];
								}
							}
						}		
						$_REQUEST['price']			=	$_REQUEST['adjust_price'];
						$_REQUEST['adjust_price']	=	$pro_price;
						$_REQUEST['sale_price']		=	$pro_price;
						$_REQUEST['product_type']	=	'A';
						$_REQUEST['price_type']		=	 0;
						$_REQUEST['user_id']		=	$_SESSION['memberid'];
						$field_arr = $_POST;
						$field_arr['hf2']			=    $_REQUEST['hf2'];
						$field_arr['user_id']		=   $_SESSION['memberid'];
						$field_arr['accessory_category']=	$cat;
						if($_REQUEST['pro_save_id']!=''){
							$field_arr['id']		=	$_REQUEST['pro_save_id'];
						}
						unset($field_arr["save_type"],$field_arr["own_store"],$field_arr["main_store"]);
						$field_arr['active']			=	'Y';
						$acc_arr						=	&$field_arr;
						$req							=	&$_REQUEST;								
					if($_REQUEST['save_type']=='p'){	
									
							$message 		= 	$objAccessory->accessoryAddEdit($acc_arr,$fname,$tmpname);														
							if( ($message['status']) == true ) {								
								$image_name=$message[id].".".$extension;
								$myfolder 		= 	$objAccessory->uploadMyArtwork($req,'','',$message['id']);
								if($fname){
									copy(SITE_PATH."/modules/product/images/accessory/".$image_name,SITE_PATH."/modules/product/images/saved_work/".$myfolder.".".$extension);	
									$path=SITE_PATH."/modules/product/images/saved_work/";
									$thumb=$path."thumb/";						
									$save_filename	=	$myfolder.".".$extension;				
									$new_listname	=	$myfolder.".".$extension;
									$new_descname	=	$myfolder."_des_.".$extension;
									list($acc_descwidth,$acc_descheight,)	=	split(',',$framework->config['accessory_thumb2_image']);
									list($acc_listwidth,$acc_listheight,)	=	split(',',$framework->config['accessory_thumb3_image']);
									thumbnail($path,$thumb,$save_filename,$acc_listwidth,$acc_listheight,$mode,$new_listname);
									thumbnail($path,$thumb,$save_filename,$acc_descwidth,$acc_descheight,$mode,$new_descname);				
								}
								$action = $req['id'] ? "Updated" : "Added";
								setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
								redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=list_accessories"));
							}
							setMessage($message['message']);						
					}else if($_REQUEST['save_type']=='a'){
					
						$myfolder 		= 	$objAccessory->uploadMyArtwork($req,$fname,$tmpname);
						if( ($myfolder['status']) == true ) {
							$action = $req['id'] ? "Updated" : "Added";
							setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
							redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=list_accessories"));
						}
						redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=list_accessories"));//added by jeffy on 30th Jan 08
					}	
				  }else{			
					editorInit('html_desc');
				}
				//$framework->tpl->assign("Category_accessory", $objAccessory->GetConformationRequest($_REQUEST['id']));
				$framework->tpl->assign("CATEGORY", $objCategory->getAllCategory_is_in_ui());
				$objCategory->getAllaccessoryCategory($catArr,0,0,28);				
				$framework->tpl->assign("ACCESSORY_CATEGORY", $catArr);
				$framework->tpl->assign("WORK_DETAIL",$workArr);
				$framework->tpl->assign('ACCESSORY_CATEGORY_SELECTEDIDS', $objAccessory->GetAllAccessoryCategoty($_REQUEST['id']));
				$framework->tpl->assign("TYPE", GetAccessoryType());
				$framework->tpl->assign("STORE_LIST", $objAccessory->storeGetDetails());
				$framework->tpl->assign("RELSTORE", $objAccessory->getRelatedStore($_REQUEST['id']));
				$framework->tpl->assign("ACCESSORY", $objAccessory->GetAccessory($_REQUEST['id']));
				$framework->tpl->assign("CATEGORY_ID", $_REQUEST['cat_id']);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/upload_accessory.tpl");
			break;
			 case "quote":       
			 	///////////Done on 27/07/07//////////////////////////////////////////////////
					$dt=$cms->getContent("instant_quote_content");
					$framework->tpl->assign("CONTENT_PAGE",$dt["content"]);
			 		$_REQUEST['id']		=	561;
					$product_id 		= 	561;
					$productDetails		=	$objProduct->ProductGet($_REQUEST['id']);		
					$discount_price 	= 	$objPrice->GetPriceOfProduct($product_id );
					$pro_price			=	$discount_price;
					$pro_baseprice		=	$pro_price;
					$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
					$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
					//For Getting the accessory
					if($_REQUEST['shop']=='Y'){
						$accessDetails	=	$objAccessory->getAccessDetails($_REQUEST['save_id']);
					}else{
						$accessDetails	=	$objAccessory->getAccessDetails($product_id);
					}		
					//print_r($accessUser);		
					if($_REQUEST['art_id']!='' || $_REQUEST['shop']=='Y'){			
						$save_id		=	$_REQUEST['save_id'];	
						$art_id			=	$_REQUEST['art_id'];
						$art_arr		=	explode(',',$art_id);	
						$accPrice		=	0;						
						for($i=0;$i<sizeof($art_arr);$i++){
							$getAccessory	    =	$objAccessory->GetAccessory($art_arr[$i]);					
								$accPrice		=	$accPrice+$getAccessory['adjust_price'];		
								$pro_price		=	$pro_price+$getAccessory['adjust_price'];
										
						}			
						$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
						$framework->tpl->assign("PRODUCT_PRICE", $pro_price);
						$framework->tpl->assign("PRODUCT_BASEPRICE", $pro_baseprice);
						$framework->tpl->assign("ACC_PRICE", $accPrice);
						$framework->tpl->assign("ACC_CAT", $accessDetails);
						$framework->tpl->assign("SHOP",'Y');
						$framework->tpl->assign("SAVE_ID", $save_id);			
						$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
						$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
						$framework->tpl->assign("STORE_NAMES", $storename);
						$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
						$framework->tpl->assign("content_size",$content_size);
						$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
						$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
				}else{		
					$product_Arr	=	$objProduct->ProductGet($_REQUEST['id']);
					$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
					$parentProArr	=	$objProduct->ProductGet($product_Arr['parent_id']);
					$base_price		=	$objPrice->GetPriceOfProduct($product_Arr['parent_id']);				
					//For getting build in accessory of Product		
					$build_acc		=	$objAccessory->takeBuildAccessory($_REQUEST['id']);		
					$countBuild		=	count($build_acc);
					if($countBuild){
						for($i=0;$i<$countBuild;$i++){
							$getAccessory	=	$objAccessory->GetAccessory($build_acc[$i]->accessory_id);
							$accPrice		=	$accPrice+$getAccessory['adjust_price'];
						}
					}
					$framework->tpl->assign("PRODUCT_BASEPRICE", $base_price);
					$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
					$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
					$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
					$framework->tpl->assign("STORE_NAMES", $storename);
					$framework->tpl->assign("ACC_PRICE", $accPrice);
					$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
					//print_r($content_size[0]["categories"][0]["accessory"][0]['comboname']);
					$i=0;
					foreach($content_size as $group)
					{
						//print_r($group["categories"]["accessory"]);
						$j=0;
						foreach($group["categories"] as $category)
						{
							$k=0;
							foreach($category["accessory"] as $acc){
							 			//print($acc['comboname']);
										//print("<br>");
										
										$str=$content_size[$i]["categories"][$j]["accessory"][$k]['comboname'];
										$content_size[$i]["categories"][$j]["accessory"][$k]['combomix']=$str;
										$str1=explode("ADD $",$str);
										$content_size[$i]["categories"][$j]["accessory"][$k]['comboname']=trim($str1[0]);
								if($str1[1]!=""){
									$content_size[$i]["categories"][$j]["accessory"][$k]['combomsg'] ="$".$str1[1]."&nbsp;Charge";
									}
										//print_r($str1[1]);
										//print_r("<br>");
										
									//$content_size[$i]["categories"][$j]["accessory"][$k]['comboname']='1111';
									$k++;
							}
							
							$j++;
						}
						
						//	foreach($category["accessory"] as $acc){
						//		print_r($acc["comboname"]);
						//	}
						//}
					//print_r($ct)."=========";
					
					//echo $group["category"]["accessory"]["comboname"];
					$i++;
					}
					
					//print_r($content_size);
//exit;
					$framework->tpl->assign("content_size",$content_size);
					$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
					$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
					$framework->tpl->register_object('ProductObj',$objProduct);
					$framework->tpl->assign("COLUMN_RELATED",$objProduct->GetRelatedProduct($product_id,$storename));
					$framework->tpl->assign("DISCRIPTION",1);
					}
					$framework->tpl->assign("main_tpl", SITE_PATH."/templates/blue/inner.tpl");
					
		///////////Done on 27/07/07/////////////////////////////////////////////////////////////////////
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/quote.tpl");
				
				break;
			case "oderform": 
			########### This Case is written by Jipson for the california silkscreen Projects Place order Pages..........
				if($_SESSION["memberid"]){
					$udet = $user->getUserDetails($_SESSION["memberid"]);
				}
				$dt=$cms->getContent("orderform_intro");
				$pmtpage=$cms->getContent("payment_page_content");
				$framework->tpl->assign("INTRO_PARA",$dt["content"]);
				$discpage=$cms->getContent("orderform_disclaimer_content");
				$framework->tpl->assign("DISCL_PARA",$discpage["content"]);
				$framework->tpl->assign("PAYMENT_PAGE_CONTENT",$pmtpage["content"]);
				$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());
				//print_r($udet);
				if ($udet)
				{
					$_REQUEST +=$udet;
					//$telephone=$_REQUEST["telephone"];
					//$ph1=substr($ph,0,3);
					//$ph2=substr($ph,3,3);
					//$ph3=substr($ph,6);
					$_REQUEST["ph1"]=$ph1;
					$_REQUEST["ph2"]=$ph2;
					$_REQUEST["ph3"]=$ph3;
					//$fax=$_REQUEST["fax"];
					//$fax1=substr($fax,0,3);
					//$fax2=substr($fax,3,3);
					//$fax3=substr($fax,6);
					//$_REQUEST["fax1"]=$fax1;
					//$_REQUEST["fax2"]=$fax2;
					//$_REQUEST["fax3"]=$fax3;
				} 
				if($_REQUEST['act1']==1){
					checkLogin();  
					if($_SERVER['REQUEST_METHOD']=="POST")
					{	
						//$_POST["telephone"]=$_POST["ph1"].$_POST["ph2"].$_POST["ph3"];
						//$_POST["fax"]=$_POST["fax1"].$_POST["fax2"].$_POST["fax3"];
						$_POST["id"]=$_SESSION["memberid"];
						$_POST["addr_type"] = "master";
						unset($_POST["x"],$_POST["y"],$_POST["confirm_email"]);
						$user->setArrData($_POST);
						$upId=$user->update();
						redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3")); 
					} 
					$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/oderform1.tpl");
				}elseif($_REQUEST['act1']==3){
				checkLogin(); 
				if($global['show_recent_order']=='1'){
					$resu=$cart->getCart();
					//print_r($resu);exit;
					$framework->tpl->assign("RECENT",$resu);
				}
				//code for shipping added on 24/07/07
				if($_SERVER['REQUEST_METHOD']=="POST") {
					$contact['fname']	 	  =	  $_POST['first_name'];
					$contact["lname"]    	  =   $_POST['last_name'];
					$contact["resale_number"] =   $_POST['resale_number'];
					$contact["company_name"]  =   $_POST['company_name'];
					$contact["email"]    	  =   $_POST['email'];
					$contact["fax"]     	  =   $_POST['fax'];
					$contact["address1"]      =   $_POST['address1']; 
					$contact["address2"]      =   $_POST['address2']; 
					$contact["city"]          =   $_POST['city']; 
					$contact["country"]       =   $_POST['country']; 
					$contact["state"]         =   $_POST['bill_state']; 
					$contact["postalcode"]    =   $_POST['bill_postalcode']; 
					$contact["telephone"]     =   $_POST["telephone"];
					$contact["mobile"]        =   $_POST["mobile"];
					$contact["user_id"]       =   $_SESSION["memberid"];
					$contact["addr_type"] 	  =   "billing";
					$user->setArrData($contact);
					$bId=$user->insertAddress();
					$contact["addr_type"] 	  =   "master";
					$contact["fax"]           =   $_POST["fax"];
					$contact["company_name"]  =   $_POST["company_name"];
					$contact["email"]    	  =   $_POST['email']; 
					$contact["id"]   		  =   $_SESSION["memberid"];
					$contact['first_name']	  =	  $_POST['first_name'];
					$contact["last_name"]     =   $_POST['last_name'];
					unset($contact["user_id"],$contact['fname'],$contact['lname']);
					$user->setArrData($contact);
					$cpId=$user->update();
					$shipping_service	=	$_REQUEST['shipping_service'];
					$ShipSrvcArr		=	explode('*^*',$shipping_service);
					$_SESSION['shipping_price']		    =	 $ShipSrvcArr[2];
					$_SESSION['shipping_service'] 		=	 $ShipSrvcArr[0];
					$_SESSION['SHIPPING_ADDRESS'] = array('fname'		=> 	$_POST['billing_first_name'],
							'lname' 		=> 	$_POST['billing_last_name'],
							'address1' 		=> 	$_POST['billing_address1'],
							'address2' 		=> 	$_POST['billing_address2'],
							'city' 			=> 	$_POST['billing_city'],
							'state' 		=> 	$_POST['state'],
							'postalcode' 	=> 	$_POST['postalcode'],
							'country' 		=> 	$_POST['billing_country'],
							'telephone' 	=> 	$_POST['billing_telephone'],
							'method' 		=> 	$ShipSrvcArr[1]);
							
					$rs = $cart->getCartBox();
					$rt=$cart->getCart();
					$tot=$rt["productTotal"];
					$atot=$rt["AccessoryTotal"];
					//print_r($rt);exit;
					$tax	=	$cart->CalculateTax($_POST['billing_country'],$_POST['state'],false,$storename);
					if(empty($tax))
					$tax=0;
					if($_POST["tax_exemption"]=="Y"){
						$tax=0;
					}
					//$cart_price 		= 	$rs->total_price;
					//	print_r($cart_price);echo"<br>";
					$udet = $user->getUserDetails($_SESSION["memberid"]);
					if($udet["sp_discount"]){
						$disc=$udet["sp_discount"];
					}else{
						$disc=1;
					}
					$cart_price =($tot * $disc)+$atot;
					$shipping_price 	= 	($_SESSION['coupon']['coupon_type']=='F') ? 0 : $_SESSION['shipping_price'];
					
					
					$total_price		= 	round(($cart_price+$shipping_price) * (100 + $tax)/100, 2);
									//	print_r($total_price);exit;

					if($_SESSION['coupon']['coupon_type']=='F') {
						$coupon_amount = "-";
					} elseif ($_SESSION['coupon']['coupon_type']=='A') {
						$coupon_amount = - $_SESSION['coupon']['amount'];
					} elseif ($_SESSION['coupon']['coupon_type']=='P') {
						$coupon_amount = - $total_price * $_SESSION['coupon']['amount'] / 100;
					}
					$udet				 =		  $user->getUserdetails($_SESSION['memberid']);
					$certificate_amount = - $_SESSION['gift_certificate']['amount'];
					$bill 	= 	$objUser->getAddresses($_SESSION['memberid'], "billing");
					$paid_price			=	$total_price + $coupon_amount + $certificate_amount;
					$paid_price			=	$paid_price < 0 ? 0 : $paid_price;
					$odarray["cart_price"]            =   $cart_price;
					$odarray["shipping_price"]        =   $shipping_price;
					$odarray["tax"]                   =   $tax;
					$odarray["total_price"]           =   $total_price;
					$odarray["paid_price"]            =   round($total_price);
					$odarray["billing_first_name"]    =   $_POST['first_name'];
					$odarray["billing_last_name"]     =   $_POST['last_name'];
					$odarray["billing_address1"]      =   $_POST['address1']; 
					$odarray["billing_address2"]      =   $_POST['address2']; 
					$odarray["billing_city"]          =   $_POST['city']; 
					$odarray["billing_country"]       =   $_POST['country']; 
					$odarray["billing_state"]         =   $_POST['bill_state']; 
					$odarray["billing_postalcode"]    =   $_POST['bill_postalcode']; 
					$odarray["billing_email"]    	  =   $_POST['email']; 
					$odarray["billing_telephone"]     =   $_POST['telephone']; 
					$odarray["billing_mobile"]    	  =   $_POST['mobile']; 
					$odarray["shipping_first_name"]   =   $_POST["billing_first_name"];
					$odarray["shipping_last_name"]    =   $_POST["billing_last_name"];
					$odarray["shipping_address1"]     =   $_POST["billing_address1"];
					$odarray["shipping_address2"]     =   $_POST["billing_address2"];
					$odarray["shipping_city"]         =   $_POST["billing_city"];
					$odarray["shipping_country"]      =   $_POST["billing_country"];
					$odarray["shipping_state"]        =   $_POST["state"];
					$odarray["shipping_postalcode"]   =   $_POST["postalcode"];
					$odarray["client_ip"]             =   $_SERVER['REMOTE_ADDR'];
					$odarray["shipping_method"]		  =	  $_SESSION['SHIPPING_ADDRESS']['method'];
					$odarray["notes"]			 	  =   $_SESSION['order_notes'];
					$odarray["shipping_service"]	  =   $_SESSION['shipping_service'];
					$odarray["date_ordered"]          =   date("Y-m-d H:i:s");
					$odarray["user_id"]               =   $_SESSION['memberid'];
					$_SESSION["odarray"]=$odarray;
					if ($_SESSION['memberid']) {
					// update shipping address
						$array = $_SESSION['SHIPPING_ADDRESS'];
						array_pop($array);
						$array['user_id'] = $_SESSION['memberid'];
						$array['addr_type'] = "shipping";
						$objUser->setArrData($array);
						$objUser->insertAddress();
					}
				/* 
				////payment//////////////////////////////
					$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
					$storename		=	$_REQUEST["storename"] ? $_REQUEST["storename"] : "0";
					$PaymentMethod	=	$PaymentObj->getActivePaymentGateway($store_name);  #Paypal Pro, Authorize.Net, LinkPoint Central	0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)
					if($PaymentMethod === 'Paypal Pro') {
						$UserDetails				 =		  $user->getUserdetails($_SESSION['memberid']);
						$Params						 =		  array(); 
						$Params['firstName']         =        $UserDetails['first_name'];
						$Params['lastName']          =        $UserDetails['last_name'];; 
						$Params['creditCardType']    =        $_REQUEST['card_type'];  
						$Params['creditCard']        =        $_REQUEST['creditCard'];
						$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
						$Params['Expiry_Year']       =        $_REQUEST['Expiry_Year']; 
						$Params['cvc']               =        $_REQUEST['cvc'];
						$Params['address1']          =        $UserDetails['address1']; 
						$Params['address2']          =        $UserDetails['address2']; 
						$Params['city']              =        $UserDetails['city'];
						$Params['state']             =        $UserDetails['state']; 
						$Params['zip']               =        $UserDetails['postalcode']; 
						$Params['country']           =        $user->getCountry2LetterCode($UserDetails['country']);
						$Params['paid_price']        =        $total_price;
					} else if($PaymentMethod === 'Authorize.Net' || $PaymentMethod === 'LinkPoint Central' ) {
						$Params['paid_price']        =        $total_price;
						$Params['creditCard']        =        $_REQUEST['creditCard'];
						$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
						$Params['Expiry_Year']       =        substr($_REQUEST['Expiry_Year'], -2); 
						$Params['cvc']               =        $_REQUEST['cvc'];
					}
					$Result			=	$PaymentObj->processPaymentRequest($store_name,$Params);
					if($Result['Approved'] == 'No') {
						setMessage($Result['Message']);
						redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=oderform&act1=3"));
					} else {
						$TransactionId		=	$Result['TransactionId']; 
						if($TransactionId != "") {
							$newcheckout	=	array("payment_transactionid"	=>	$TransactionId);
							$odarray		=	array_merge($odarray,$newcheckout);
						}
						$cart->placeOrder($odarray);
						redirect(makeLink(array("mod"=>"cart","pg"=>"default"), "act=thanks"));	
					}
				# Close if Submit
				////payment////////////////////////////// 
				 */
					//redirect(makeLink(array("mod"=>"cart","pg"=>"default"), "act=thanks"));
					redirect(makeLink(array("mod"=>"product","pg"=>"list", "sslval"=>"true"), "act=oderform&act1=4"));
				}
				if ($_SESSION['SHIPPING_ADDRESS'] == "") {
					if ($_SESSION['memberid']) {
						$userDetails = $objUser->getUserdetails($_SESSION['memberid']);
						$mail = $userDetails['email'];
						$ship 	= 	$objUser->getAddresses($_SESSION['memberid'], "shipping");
						$master	= 	$objUser->getAddresses($_SESSION['memberid'], "master");				
						$bill	=	get_object_vars($bill[0]);
						$ship	=	get_object_vars($ship[0]);
						$master	=	get_object_vars($master[0]);
						$master['fname'] =$userDetails['first_name'];
						$master['lname'] =$userDetails['last_name'];
						if($bill){
							$bill['email'] = $master['email'] = $mail;
						}
						$master['email'] = $mail;
						$framework->tpl->assign("SHIPPING_ADDRESS", $ship ? $ship : $master);
					}
				}else{
					$framework->tpl->assign("SHIPPING_ADDRESS", $_SESSION['SHIPPING_ADDRESS']);
				}
				$master1= 	$objUser->getAddresses($_SESSION['memberid'], "master");				
				$bill 	= 	$objUser->getAddresses($_SESSION['memberid'], "billing");
				//$bill	=	get_object_vars($bill[0]);
				//$master1=	get_object_vars($master1[0]);
				$billcountry=$objUser->getCountryName($bill["country"]);
				$bill[0]["country"]=$billcountry["country_name"];
				if($bill){
					$billadd=$bill[0];
				}else{
					$billadd=$master1[0];
				}
				if(!$_REQUEST["country"]){
					$_REQUEST["country"]=840;
				}
				//print_r($billadd);exit;
				$framework->tpl->assign("BILLING_ADDRESS", $billadd);
				$framework->tpl->assign("SHIPPING_METHODS", $ShippingObj->getShippingMethodsForComboFilling($storename));
				////code for shipping added on 24/07/07-------------end
				$framework->tpl->assign("CREDITCARD", $typeObj->GetAllCreditCards($storename));
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/oderform3.tpl");
			}elseif($_REQUEST['act1']==4){
				checkLogin(); 
				$odarray=$_SESSION["odarray"];
				$paidprice=$odarray["paid_price"];
				if($_SERVER['REQUEST_METHOD']=="POST") {
				$odarray=$_SESSION["odarray"];
				$paydet=$typeObj->GetPaymentType($_REQUEST['card_type']);
				$odarray["paytype"]=$paydet["name"];
				//print_r($paydet);exit;
					
					////payment//////////////////////////////
					$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
					$storename		=	$_REQUEST["storename"] ? $_REQUEST["storename"] : "0";
					$PaymentMethod	=	$PaymentObj->getActivePaymentGateway($store_name);  #Paypal Pro, Authorize.Net, LinkPoint Central	0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)
					if($PaymentMethod === 'Paypal Pro') {
						$UserDetails				 =		  $user->getUserdetails($_SESSION['memberid']);
						$Params						 =		  array(); 
						$Params['firstName']         =        $UserDetails['first_name'];
						$Params['lastName']          =        $UserDetails['last_name'];; 
						$Params['creditCardType']    =        $_REQUEST['card_type'];  
						$Params['creditCard']        =        $_REQUEST['creditCard'];
						$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
						$Params['Expiry_Year']       =        $_REQUEST['Expiry_Year']; 
						$Params['cvc']               =        $_REQUEST['cvc'];
						$Params['address1']          =        $UserDetails['address1']; 
						$Params['address2']          =        $UserDetails['address2']; 
						$Params['city']              =        $UserDetails['city'];
						$Params['state']             =        $UserDetails['state']; 
						$Params['zip']               =        $UserDetails['postalcode']; 
						$Params['country']           =        $user->getCountry2LetterCode($UserDetails['country']);
						$Params['paid_price']        =        $total_price;
					} else if($PaymentMethod === 'Authorize.Net' || $PaymentMethod === 'LinkPoint Central' ) {
						$Params['paid_price']        =        $odarray["total_price"];
						$Params['creditCard']        =        $_REQUEST['creditCard'];
						$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
						$Params['Expiry_Year']       =        substr($_REQUEST['Expiry_Year'], -2); 
						$Params['cvc']               =        $_REQUEST['cvc'];
					}
					
					$Result			=	$PaymentObj->processPaymentRequest($store_name,$Params);
					//print_r($Result);exit;
					if($Result['Approved'] == 'No') {
						//print_r($Result['Message']);exit;
						setMessage($Result['Message']);
						//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=oderform&act1=4"));
					} else {
						unset($_SESSION["odarray"]);
						$TransactionId		=	$Result['TransactionId']; 
						if($TransactionId != "") {
							$newcheckout	=	array("payment_transactionid"	=>	$TransactionId);
							$odarray		=	array_merge($odarray,$newcheckout);
						}
						
						$cart->placeOrder($odarray);
						
						redirect(makeLink(array("mod"=>"cart","pg"=>"default"), "act=thanks"));	
					}
				# Close if Submit
				////payment//////////////////////////////

					//redirect(makeLink(array("mod"=>"cart","pg"=>"default"), "act=thanks"));
				}
				$framework->tpl->assign("PAIDAMT", $paidprice);
				$framework->tpl->assign("CREDITCARD", $typeObj->GetAllCreditCards($storename));
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/oderform31.tpl");
			}else{
				///////////Done on 26/07/07//////////////////////////////////////////////////
				if($global['show_recent_order']=='1'){
					$resu=$cart->getCart();
					$framework->tpl->assign("RECENT",$resu);
				}
				if($_REQUEST["jobid"]){
					$ordArray = $oder->orderProducts($_REQUEST["order_id"]);
					foreach($ordArray as $order){
						foreach($order as $job){
							if($job->id==$_REQUEST["jobid"]){
								$work=$job;
							}
						}
					}
					$framework->tpl->assign("WORKS", $work);
					if($global['separate_shipadd_job']=='1'){
						$meadd=$objUser->getProductShipMap($_REQUEST['jobid']);
						if($meadd){
							$shipadd=$objUser->getAddress($meadd);
							//$_REQUEST +=$shipadd;
							//print_r($_REQUEST);exit;
							$framework->tpl->assign("SHIPADD",$shipadd);
							//print_r($shipadd);exit;
						}
					}
				}elseif($_REQUEST["cart_id"]){
					//$ordArray = $cart->getCart($_REQUEST["cart_id"]);
				//	print_r($ordArray);exit;
					foreach($resu as $order){
						foreach($order as $job){
							if($job->id==$_REQUEST["cart_id"]){
								$work=$job;
							}
						}
					}
					//print_r($work);exit;
					$framework->tpl->assign("WORKS", $work);
					if($global['separate_shipadd_job']=='1'){
						$meadd=$objUser->getProductShipMap($_REQUEST['cart_id']);
						if($meadd){
							$shipadd=$objUser->getAddress($meadd);
							//$_REQUEST +=$shipadd;
							//print_r($_REQUEST);exit;
							$framework->tpl->assign("SHIPADD",$shipadd);
							//print_r($shipadd);exit;
						}
					}
				}
				//print_r($work);exit;
				if($global['separate_shipadd_job']=='1'){
						$framework->tpl->assign("SHOW_SHIPADD", "YES");
				}
				for($i=1000;$i<=$global['quantity'];$i=$i+1000){
					$qnty[]=$i;
				}
				
				$framework->tpl->assign("QNTY", $qnty);
				$_REQUEST['id']		=	561;
				$product_id 		= 	561;
				$productDetails		=	$objProduct->ProductGet($_REQUEST['id']);		
				$discount_price 	= 	 $objPrice->GetPriceOfProduct($product_id );
				$pro_price			=	$discount_price;
				$pro_baseprice		=	$pro_price;
				$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
				$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
				//For Getting the accessory
				if($_REQUEST['shop']=='Y'){
					$accessDetails	=	$objAccessory->getAccessDetails($_REQUEST['save_id']);
				}else{
					$accessDetails	=	$objAccessory->getAccessDetails($product_id);
				}		
				//print_r($accessUser);		
				if($_REQUEST['art_id']!='' || $_REQUEST['shop']=='Y'){			
					$save_id		=	$_REQUEST['save_id'];	
					$art_id			=	$_REQUEST['art_id'];
					$art_arr		=	explode(',',$art_id);	
					$accPrice		=	0;						
					for($i=0;$i<sizeof($art_arr);$i++){
						$getAccessory	    =	$objAccessory->GetAccessory($art_arr[$i]);					
						$accPrice		=	$accPrice+$getAccessory['adjust_price'];		
						$pro_price		=	$pro_price+$getAccessory['adjust_price'];
				
					}			
					$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
					$framework->tpl->assign("PRODUCT_PRICE", $pro_price);
					$framework->tpl->assign("PRODUCT_BASEPRICE", $pro_baseprice);
					$framework->tpl->assign("ACC_PRICE", $accPrice);
					$framework->tpl->assign("ACC_CAT", $accessDetails);
					$framework->tpl->assign("SHOP",'Y');
					$framework->tpl->assign("SAVE_ID", $save_id);			
					$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
					$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
					$framework->tpl->assign("STORE_NAMES", $storename);
					$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
					$framework->tpl->assign("content_size",$content_size);
					$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
					$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
				}else{		
					$product_Arr	=	$objProduct->ProductGet($_REQUEST['id']);
					$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
					$parentProArr	=	$objProduct->ProductGet($product_Arr['parent_id']);
					$base_price		=	$objPrice->GetPriceOfProduct($product_Arr['parent_id']);				
					//For getting build in accessory of Product		
					$build_acc		=	$objAccessory->takeBuildAccessory($_REQUEST['id']);		
					$countBuild		=	count($build_acc);
					if($countBuild){
						for($i=0;$i<$countBuild;$i++){
							$getAccessory	=	$objAccessory->GetAccessory($build_acc[$i]->accessory_id);
							$accPrice		=	$accPrice+$getAccessory['adjust_price'];
						}
					}
					$framework->tpl->assign("PRODUCT_BASEPRICE", $base_price);
					$framework->tpl->assign("PRODUCT_PRICE", $objPrice->GetPriceOfProduct($_REQUEST['id']));
					$framework->tpl->assign("PRODUCT_MADE_IN", $objMade->GetMadeInForProduct($_REQUEST['id']));
					$framework->tpl->assign("CUSTOMIZE_TEXT", $objCombination->GetCustomizationTextOptions());
					$framework->tpl->assign("STORE_NAMES", $storename);
					$framework->tpl->assign("ACC_PRICE", $accPrice);
					$content_size=$objCombination->GetAccessoryLists($product_id,$storename);
					$i=0;
					foreach($content_size as $group)
					{
						$j=0;
						if(strlen($group["group_name"])>20){
							$content_size[$i]["lengthy_name"]=$group["group_name"];
						}
						foreach($group["categories"] as $category)
						{
							$k=0;
							foreach($category["accessory"] as $acc){
								$str=$content_size[$i]["categories"][$j]["accessory"][$k]['comboname'];
								$content_size[$i]["categories"][$j]["accessory"][$k]['combomix']=$str;
								$str1=explode("ADD $",$str);
								$content_size[$i]["categories"][$j]["accessory"][$k]['comboname']=trim($str1[0]);
								if($str1[1]!=""){
									$content_size[$i]["categories"][$j]["accessory"][$k]['combomsg'] ="$".$str1[1]."&nbsp;Charge";
								}
								if($work){
									foreach($work->accessory as $acarray){
										if($acarray->accessory_id==$content_size[$i]["categories"][$j]["accessory"][$k]['id']){
											$content_size[$i]["categories"][$j]["accessory"][$k]['select']="Selected";
							
										}
									}
								}
								$k++;
							}
						
						$j++;
						}
						$i++;
					}
					
					//
					//print_r($content_size);exit;
					$framework->tpl->assign("content_size",$content_size);
					$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
					$framework->tpl->assign("PREVIOUS_NEXT",$objProduct->GetNextPreviousProducts($product_id,$category_id,$storename));
					$framework->tpl->register_object('ProductObj',$objProduct);
					$framework->tpl->assign("COLUMN_RELATED",$objProduct->GetRelatedProduct($product_id,$storename));
					$framework->tpl->assign("DISCRIPTION",1);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/templates/blue/inner.tpl");
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/oderform2.tpl");
				///////////Done on 02/08/07/////////////////////////////////////////////////////////////////////
				if($_SERVER['REQUEST_METHOD']=="POST")
				{	
				//print_r($_FILES);exit;
				//print_r($_REQUEST);exit;
					$storeArr = $store->storeGetByName($_REQUEST['storename']);
					$_REQUEST['store_id'] = $storeArr['id'];
					$status	=	$objCombination->ValidateMandatory($_REQUEST);
					if($status['status']==true) {
						if($objProduct->ValidateGiftCertificate($_REQUEST['product_id'],$_REQUEST['price']))
						{
							$accessory_status	=	$objAccessory->Validate_Accessory_Excluded($_REQUEST);
							if($accessory_status['status']==true)
							{
								$_REQUEST['user_id'] = $_SESSION['memberid'];
								if($_REQUEST["cart_id"]){
									$cart_id=$cart->editFromCart($_REQUEST,$_REQUEST["cart_id"]);
								}else{
							//	print("Ethiiiiii");exit;
									$cart_id=$cart->addToCart($_REQUEST);
								}
								
								if($global['separate_shipadd_job']=='1'){
									if($_REQUEST["c1"]=="yes"){
										$ship_product = array('fname'		=> 	$_POST['billing_first_name'],
															'lname' 		=> 	$_POST['billing_last_name'],
															'address1' 		=> 	$_POST['billing_address1'],
															'address2' 		=> 	$_POST['billing_address2'],
															'city' 			=> 	$_POST['billing_city'],
															'state' 		=> 	$_POST['state'],
															'postalcode' 	=> 	$_POST['postalcode'],
															'country' 		=> 	$_POST['billing_country'],
															'user_id'		=>	$_SESSION['memberid'],
															'addr_type' 	=> 	$cart_id."Product_Shipping");
									unset($_POST['billing_first_name'],$_POST['billing_last_name'],$_POST['billing_address1'],$_POST['billing_address2'],$_POST['billing_city'],$_POST['state'],$_POST['postalcode'],	$_POST['billing_country']);
										$objUser->setArrData($ship_product);
										$memaddid=$objUser->insertAddress();
										$objUser->insertProductShipMap($cart_id,$memaddid);
										
									}
									unset($_POST["c1"]);
								}
								$nomatch=0;
								if($_FILES){
									$fname1			=	basename($_FILES['file1']['name']);
									$ftype1		=	$_FILES['file1']['type'];
									$tmpname1		=	$_FILES['file1']['tmp_name'];
									$fname2			=	basename($_FILES['file2']['name']);
									$ftype2		=	$_FILES['file2']['type'];
									$tmpname2		=	$_FILES['file2']['tmp_name'];
									$fname3			=	basename($_FILES['file3']['name']);
									$ftype3		=	$_FILES['file3']['type'];
									$tmpname3		=	$_FILES['file3']['tmp_name'];
									//echo"File Format<br>";
									/*$fformat=$objProduct->GetAccessoryName($_POST["access"]["7"]);
									$checkformat=$fformat["name"][0];
									if($checkformat=="JPEG" || $checkformat=="JPG"){
										$checkformat="image/pjpeg";
										$checkformat1="image/jpeg";
										$checkformat2="image/jpg";
									}
									if($checkformat=="psd"){
										$checkformat="application/octetstream";
									}
									if($checkformat=="png"){
										$checkformat="image/png";
									}
									if($checkformat=="gif" || $checkformat=="GIF"){
										$checkformat="image/gif";
										$checkformat1="image/GIF";
									}
									if($ftype1!=$checkformat && $ftype1!=$checkformat1 && $ftype1!=$checkformat2 ){
										setMessage("File format is not supporting");
										$nomatch=1;
									}else if($ftype2!=$checkformat && $ftype2!=$checkformat1 && $ftype2!=$checkformat2 ){
										setMessage("File format is not supporting");
										$nomatch=1; 
									}else if($ftype3!=$checkformat && $ftype3!=$checkformat1 && $ftype3!=$checkformat2 ){
										setMessage("File format is not supporting");
										$nomatch=1;
									}else{*/
										if ($fname1){
											$dir			=	SITE_PATH."/modules/order/images/userorders/";
											$resource_file	=	$dir.$fname1;
											$path_parts 	= 	pathinfo($fname1);
											$save_filename	=	$cart_id."file1".".".$path_parts['extension'];
											$file1type		=	$path_parts['extension'];
											//_upload($dir,$save_filename,$tmpname1,1);
											uploadFile($_FILES['file1'], $dir, $save_filename);
										}
										if ($fname2){
											$i++;
											$dir			=	SITE_PATH."/modules/order/images/userorders/";
											$resource_file	=	$dir.$fname2;
											$path_parts 	= 	pathinfo($fname2);
											$save_filename	=	$cart_id."file2".".".$path_parts['extension'];
											$file2type		=	$path_parts['extension'];
											uploadFile($_FILES['file2'], $dir, $save_filename);
										}
										if ($fname3){
											$i++;
											$dir			=	SITE_PATH."/modules/order/images/userorders/";
											$resource_file	=	$dir.$fname3;
											$path_parts 	= 	pathinfo($fname3);
											$save_filename	=	$cart_id."file3".".".$path_parts['extension'];
											uploadFile($_FILES['file3'], $dir, $save_filename);
											$file3type		=	$path_parts['extension'];
										}
										$cart->addfileextToCartJob($cart_id,$file1type,$file2type);
										$_SESSION['order_notes']		=	$_REQUEST['notes'];
										if($_POST["continuty"]==1){
											redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=2"));
										}else{
											redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3"));
										}
				
									#}
									}else{
										$_SESSION['order_notes']		=	$_REQUEST['notes'];
										if($_POST["continuty"]==1){
											redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=2"));
										}else{
											redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3"));
										}
									}
				
								}
				
							}
							else
							{
								echo "document.getElementById('message').innerHTML = 'Please Specify The Amoount for ".$objProduct->GetProductName($_REQUEST['product_id'])."';";
								echo "height = document.getElementById('message').clientHeight;";
								echo "height = height+10;";
								echo "document.getElementById('show_message').style.height = height+'px';";
								echo "document.getElementById('show_message').style.overflow ='visible';";
								echo "document.getElementById('add_to_cart').style.display='inline';";
								echo "document.getElementById('addtocart').style.display='none';";
								echo "window.location='#error_message';";
							}
						} else {
						echo "document.getElementById('message').innerHTML = '".$status['message']."';";
						echo "height = document.getElementById('message').clientHeight;";
						echo "height = height+10;";
						echo "document.getElementById('show_message').style.height = height+'px';";
						echo "document.getElementById('show_message').style.overflow ='visible';";
						echo "document.getElementById('add_to_cart').style.display='inline';";
						echo "document.getElementById('addtocart').style.display='none';";
						echo "window.location='#error_message';";
					}
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/oderform2.tpl");
				}
				$framework->tpl->display($global['curr_tpl']."/inner_od.tpl");
				exit;
				break; 

				########### ***************************************************************************....................
			case "ove_template":			
				 $count	=0;
				 $sellProd	=	$_REQUEST['sellprod'];
				 $proArray	=	$objProduct->ProductGet($_REQUEST['id']);
				 $array		=array();
					 if($proArray['overlay']!=""){
						$array[]	=	"OV_".$_REQUEST['id'].".".$proArray['overlay'];
					 }
					if($proArray['overlay2']!=""){
						$array[]	=	"OV2_".$_REQUEST['id'].".".$proArray['overlay2'];
					}					
					if($proArray['overlay3']!=""){
						$array[]	=	"OV3_".$_REQUEST['id'].".".$proArray['overlay3'];
					}
					if($proArray['overlay4']!=""){
						$array[]	=	"OV4_".$_REQUEST['id'].".".$proArray['overlay4'];
					 }
					if($proArray['overlay5']!=""){
						$array[]	=	"OV5_".$_REQUEST['id'].".".$proArray['overlay5'];
					}
					$framework->tpl->assign("OVERLAY", $array);
					$framework->tpl->assign("SELL_OPTION", $sellProd);
				 	$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
				 	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/listing_template.tpl");
			break;
			case "search_desc":
				$id=$_REQUEST['id'];
				$framework->tpl->assign("OVERLAY", $id);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_search_description.tpl");
			break;
			
			case "acceassory_show":
			     $acc_id  = $_REQUEST['accid'];
				 
				$ac_value = $objAccessory->GetAccessory($acc_id);
				//print_r($ac_value);
				
				$strs .= '<table >';
				
				$strs .= '<tr>';
				$strs .= '<td style="color:#000000"><b> Mat:&nbsp;&nbsp;'.$ac_value["cart_name"].'';
				$strs .= '</b></td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td >';
				$strs .= '</td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td style="color:#000000"><b> Description:&nbsp;&nbsp;'.$ac_value["description"].'';
				$strs .= '</b></td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td >';
				$strs .= '</td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td style="color:#000000"><b> Comments:&nbsp;&nbsp;'.$ac_value["comments"].'';
				$strs .= '</b></td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td >';
				$strs .= '</td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td style="color:#000000"><b>Size:&nbsp;&nbsp; '.$ac_value["size"].'';
				$strs .= '</b></td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td >';
				$strs .= '</td>';
				$strs .= '</tr>';
				$strs .= '<tr>';
				$strs .= '<td style="color:#000000"><b>Color:&nbsp;&nbsp; '.$ac_value["color"].'';
				$strs .= '</b></td>';
				$strs .= '</tr>';
				
				$strs .= '</table>';
				
				//$framework->tpl->assign("ACCESSORY",$ac_value);
				 echo $strs;
				//$framework->tpl->display(SITE_PATH."/modules/product/tpl/accessory_Popup.tpl");
				exit;
			break;
			
			/**
			* This will send a mail to the admin with details entered.
			* Author   : Salim
			* Created  : 08/May/2008
			*/
			case "req_a_quote":
				checkLogin();
				
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					$myId	=	$_SESSION['memberid'];
					$usdeta	=	$user->getUserdetails($myId);
					if(($_REQUEST['partno'] == '')&&($_REQUEST['modelno'] == '')&&($_REQUEST['craftype'] == '')&&($_REQUEST['quantity'] == '')){
						setMessage("You cannot leave all the fields empty.Please enter atleast one field ");
					}
					else {
								$mail_header = array();
								$mail_header["from"]   	= 	$usdeta['email'];
								$mail_header['to'] 		= 	$framework->config['admin_email'];

								$dynamic_vars	=	array();
								$dynamic_vars["PART_NUMBER"]  	= $_POST["partno"];
								$dynamic_vars["MODEL_NUMBER"]  	= $_POST["modelno"];
								$dynamic_vars["AIRCRAFT_TYPE"]  = $_POST["craftype"];
								$dynamic_vars["QUANTITY"]  		= $_POST["quantity"];
								//$dynamic_vars["EMAIL"]  		= $_POST["email"];
								$dynamic_vars["EMAIL"]  		= $usdeta['email'];
								
						if($email->send('quote_requested', $mail_header, $dynamic_vars)){
							setMessage("Quote Sent Successfully",MSG_SUCCESS);
						}
					}
						
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/requestaquate.tpl");
				break;
				
				
		case "list_accessories_frame_ajax":	
		
		
			$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " a.id ";

			$search_fields = 'type';
			$search_values = $_REQUEST['type'];
			$search_crt    = $_REQUEST["serach_crt"]; 
			//$category_id="";
			$param				=	"mod=product&pg=list&act=$act&cat_id=$category_id&art=".$_REQUEST["art"]."&search_fields=$search_fields&search_values=$search_values&search_crt=$search_crt&poem=".$_REQUEST["poem"];
						
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryLists($category_id,$store_id,$pageNo,50,$param,OBJECT, $orderBy,$search_fields,$search_values,$search_crt);
			
			$framework->tpl->assign("ACCESSORY",$res);
			
		
			$framework->tpl->display(SITE_PATH."/modules/product/tpl/user_accessory_frame_list_ajax.tpl");
			exit;
		break;	
		
		case "acceassory_det":
				$acc_id  = $_REQUEST['accid'];
				$ac_value = $objAccessory->GetAccessory($acc_id);
				$accessory_det	=	array();
				$accessory_det['display_name']	=	$ac_value['cart_name'];
				$accessory_det['size']			=	$ac_value['size'];
				$accessory_det['color']			=	$ac_value['color'];
				$accessory_det['description']	=	$ac_value['description'];
				$accessory_det['comments']		=	$ac_value['comments'];
				$accessory_det['type']			=	$ac_value['type'];
				$str = implode("|", $accessory_det);
				echo  $str ;
				exit;
			break;
			
		case "pg_list":
		
			
			
			//ADDED FOR SEO URL
			$data 					=	$_REQUEST['data'];
			if($data){
				$_REQUEST['cat_id']	=	$objCategory->CategoryGetBySeoUrl($data);
			}
			
			
			$category_det	=	$objCategory->CategoryGet($_REQUEST['cat_id']);
			$framework->tpl->assign("CATEGORY_DET", $category_det);
			
			$rs = $FeaturedProducts->GetFeaturedItems2($_REQUEST['storename']); 
			$framework->tpl->assign("FEATURED_ITEMS_LIST",$rs);
			//print_r($category_det);
			
			list($rs, $numpad, $cnt, $limitList) = 	$objProduct->getPredefinedGiftList_new($_REQUEST['pageNo'],$_REQUEST['limit'],$param,ARRAY_A, $_REQUEST['orderBy'],$store_id,$_REQUEST['cat_id'],'',1);
			//print_r($rs);
			
				// ---- new code added eldho on may 09-16 --- //
				for($i=0;$i<count($rs);$i++)
				{
					if($rs[$i]['other_gift'] == "Y"){
						
						$discount_price = $objPrice->GetPriceOfProduct($rs[$i]['id']);
						
						if($discount_price != $rs[$i]['product_basic_price']){
							$rs[$i]['product_sale_price']	=	number_format($discount_price, 2, '.', '');
						}
						
								
					}
				}
				// ---- new code End eldho on may 09-16 --- //
				
				
				// META DESCRIPTION & META KEYWORDS & PAGE TITLE
				
				$store_name	  =	"The Personalized Gift";
				if($store_id !=0)
					$store_name =   $objProduct->getStoreNameByid($store_id);
				
				$page_title			=	$store_name." - ".$category_det['category_name'];
				$meta_description	=   $page_title;
				$meta_keywords	    =   $store_name.",".$category_det['category_name'];

				if($rs[0]['category_name']){
					$page_title			=	$store_name." - ".$rs[0]['category_name'];
					$meta_description	=   $page_title." - ";
					$meta_keywords	    =   $store_name.",".$rs[0]['category_name'];
					
					foreach($rs as $item){	
						$meta_description	.=	$item['product_title'].",";
						$meta_keywords		.=  ",".$item['product_title'];
				    }
					$meta_description	 = substr($meta_description,0,-1);
				}
				$framework->tpl->assign("PAGE_TITLE",$page_title);
				$framework->tpl->assign("META_KEYWORD",$meta_keywords);
				$framework->tpl->assign("META_DESCRIPTION",$meta_description);
			
			
			
			$framework->tpl->assign("PREDFD_GIFT_LIST",$rs);
			$framework->tpl->assign("PREDFD_GIFT_NUMPAD", $numpad);
			$framework->tpl->assign("PREDFD_GIFT_LIMIT", $limitList);
		
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/pg_list.tpl");
			
			break;	
		case "pg_details":
		
		
		
		if($_REQUEST['cart_id'])
		{
			$cart_id=$_REQUEST['cart_id'];
			$framework->tpl->assign("CART_ID",$cart_id);
			
			$avilable_table	=$framework->config['avilable_access'];
			if($avilable_table=='N'){
				$cartArray	=	$objCart->getCart('N');
				$CARTARRAY	= $objCart->getCart('N');
			}else{
				$CARTARRAY	= $objCart->getCart();
				$cartArray	=	$objCart->getCart();
			}
			
			$prd_det = $objProduct->ProductGet($_REQUEST["product_id"]);
			
			$prd_det['price'] = $objPrice->GetPriceOfProduct($prd_det['id']);
			$names = $prd_det["x_co"];
			
			foreach($CARTARRAY['records'] as $key=>$val)
			{
			
				if($val->id==$cart_id)
				{
					$artArray=$val;
					
					
					
					if($artArray->pgift_id)
					{
						$rs = $product->getPredefinedGiftDetails($artArray->pgift_id);
						
						if($rs['product_sale_price'] !='')
						$pg_price = $rs['product_sale_price'];
						else
						$pg_price = $rs['product_basic_price'];
						
						$framework->tpl->assign("PG_ID",$artArray->pgift_id);
						$framework->tpl->assign("PG_PRICE",$pg_price);
						$framework->tpl->assign("BASE_PRICE",$pg_price);
						
					}
					
					if($artArray->pgift_id!=''){
						$tot_price=$artArray->total_price+$artArray->accessory_price;
						$framework->tpl->assign("TOTAL_PRICE",$tot_price);
						
						$framework->tpl->assign("BASE_PRICE_GIFT",$artArray->total_price);
					}
					else
					$framework->tpl->assign("TOTAL_PRICE",$artArray->total_price);
					
					for($i=0;$i< count($artArray->accessory);$i++)
					{
						$accArray=$artArray->accessory[$i];
						
						
						
						if($accArray->accessory_name=='Art Background')
						{
							$framework->tpl->assign("ART_ANAME",$accArray->aname);
							$framework->tpl->assign("ART_IMG",$accArray->accessory_id);
							$framework->tpl->assign("ART_EXT",$accArray->image_extension);
							$image_extension=$accArray->image_extension;
							
							$im_path=SITE_PATH."/modules/product/images/accessory/".$accArray->accessory_id.".".$accArray->image_extension;
							$src_size = getimagesize($im_path);
							if(($src_size[0]==503 || $src_size[0]>503))
								{
								}else{
									$framework->tpl->assign("HEIGHTPLUS",'YES');
								}
							$option = explode("|",$artArray->notes); 
							
							for ($k=0;$k<sizeof($option);$k++)
							{
								$arr1 = explode("~",$option[$k]);
								if ($arr1[0])
								{
									$options[$k]["label"] = $arr1[0];
									$options[$k]["value"] = $arr1[1];
								}	
							}
							if($names==1)
							{
								$framework->tpl->assign("NAME",$options[0]['value']);
								$framework->tpl->assign("GENDER",$options[1]['value']);
								$framework->tpl->assign("SENT1",$options[2]['value']);
								$framework->tpl->assign("SENT2",$options[3]['value']);
								$framework->tpl->assign("LANGUAGE",$options[4]['value']);
							}
							else
							{
								$framework->tpl->assign("NAME",$options[0]['value']);
								$framework->tpl->assign("GENDER",$options[1]['value']);
								$framework->tpl->assign("NAME1",$options[2]['value']);
								$framework->tpl->assign("GENDER1",$options[3]['value']);
								$framework->tpl->assign("SENT1",$options[4]['value']);
								$framework->tpl->assign("SENT2",$options[5]['value']);
								$framework->tpl->assign("LANGUAGE",$options[6]['value']);
							}
							$source_path=SITE_PATH."/modules/cart/images/".$cart_id.".".$image_extension;
							$dest_path   = SITE_URL."/modules/ajax_editor/images/";	
							$save_filename=$image_name;
							copy($source_path,$dest_path.$save_filename);
							$_SESSION['pathim']=SITE_URL."/modules/ajax_editor/images/$image_name.jpg";
							
						}
				
						if($accArray->accessory_name=='Mat Frame' || ($accArray->type=='frame' && $accArray->accessory_name!='Wood Frame'))
						{
							$framework->tpl->assign("MAT_ID",$accArray->accessory_id.".".$accArray->image_extension);
							$framework->tpl->assign("ACC_ID",$accArray->accessory_id);
							$framework->tpl->assign("MAT_NAME",$accArray->aname);
							$framework->tpl->assign("MAT_PRICE",$accArray->price);
							$framework->tpl->assign("ACC_NAME_",$accArray->accessory_name);
							$framework->tpl->assign("ACC_TYPE",$accArray->type);
						}
						
						if($accArray->accessory_name=='Wood Frame')
						{
							$framework->tpl->assign("WOOD_FRAME_ID",$accArray->accessory_id.".".$accArray->image_extension);
							$framework->tpl->assign("WF_ACC_ID",$accArray->accessory_id);
							$framework->tpl->assign("WOOD_FRAME_NAME",$accArray->aname);
							$framework->tpl->assign("WOOD_FRAME_PRICE",$accArray->price);
						}
						
						if($accArray->accessory_name=='Personalized Poetry Gift')
						{
							$framework->tpl->assign("POEM_NAME",$accArray->aname);
							$framework->tpl->assign("POEM_ID",$accArray->accessory_id);
							
							$ac_value = $objAccessory->GetAccessory($accArray->accessory_id);
							$opt_count= substr_count($ac_value['poem'], '<Opening'); 
							$cl_count= substr_count($ac_value['poem'], '<Closing');
							
							$linearray=explode('|',$artArray->notes);
							
							for($l=0;$l<=count($linearray);$l++)
							{
								if(strstr($linearray[$l], 'opening')){
									$oparray[]=str_replace('~','',strstr($linearray[$l], '~'));
								}
								if(strstr($linearray[$l], 'closing')){
									$clarray[]=str_replace('~','',strstr($linearray[$l], '~'));
								}
							}
														
							for($m=0;$m< $opt_count;$m++)
							{
								
								$opStr.='<div style="height:40px;"><span style="font-size:12px;FONT-WEIGHT:bold; ">Opening Line '.($m+1).'</span><br><span style="font-size:12px;"><span id="salim">'.$oparray[$m].'</span></span></div>';
							}
							for($j=0;$j< $cl_count;$j++)
							{
								$clStr.='<div style="height:40px;"><span style="font-size:12px;FONT-WEIGHT:bold; ">Closing Line '.($j+1).'</span><br><span style="font-size:12px;"><span id="salim">'.$clarray[$j].'</span></span></div>';
							}
							$framework->tpl->assign("OPT_COUNT",$opt_count);
							$framework->tpl->assign("CL_COUNT",$cl_count);
							
							
							$framework->tpl->assign("OPSTR",$opStr);
							$framework->tpl->assign("CLSTR",$clStr);
							$framework->tpl->assign("POEM_ID",$accArray->accessory_id);
						}
					}
					
				}
			}
		}
		
			
		
			$pg_det = $product->getPredefinedGiftDetails($_REQUEST["id"]);
			
		
			$prd_det = $product->ProductGet($pg_det["product_id"]);
			$names = $prd_det["x_co"];
			
			$framework->tpl->assign("PRD_DET",$prd_det);
			$framework->tpl->assign("NAMES",$names);
			
			
			
			$framework->tpl->assign("GIFT_DET",$pg_det);
			
			
			
			
			if($names==3)
			{
				
				$op_text = $pg_det['op_text'];
				$cl_text = $pg_det['cl_text'];
				
				$op_text_arr = explode('|',$op_text);
				$cl_text_arr = explode('|',$cl_text);
			
				$poem_details = $objAccessory->GetAccessory($pg_det['poem_id']);
				
			
				$opt_count= substr_count($poem_details['poem'], '<Opening'); 
				$cl_count= substr_count($poem_details['poem'], '<Closing');
				
				$text_op = $ajax_editor->GetTextBoxValuesO($poem_details['poem'],0);
				$newvar_op	=	explode(">",$text_op);
		
				$text_op = $ajax_editor->GetTextBoxValuesC($poem_details['poem'],0);
				$newvar_cl	=	explode(">",$text_op);
								
				$framework->tpl->assign("OP_COUNT", $opt_count);
				$framework->tpl->assign("CL_COUNT", $cl_count);
				
				$num=0;
				$cl_num=0;
				$ol_str = '';
				$cl_str = '';
					
				for($i=0;$i<$opt_count;$i++)
				{
				$num++;
				$ol_str .=  '<div class="innertextname1"><span class="innertextname1"><b>Opening Line '.$num.'  </span></div>';
				$ol_str .=  '<div><input type="text"  id="opt'.$i.'" name="opt" value="'.trim($op_text_arr[$i]).'"  class="graytext_fordefault" size="25"  onfocus="javascript : doClear(this)" onBlur="javascript : doReload(this);" ></div>';
				}
				
				for($i=0; $i< $cl_count;$i++)
				{
				$cl_num++;
				$cl_str .=  '<div class="innertextname1"><span class="innertextname1"><b>Closing Line '.$cl_num.'  </span></div>';
				$cl_str .=  '<div><input type="text"  name="col" id="col'.$i.'" value="'.trim($cl_text_arr[$i]).'"  class="graytext_fordefault" size="25"  onfocus="javascript : doClear(this)" onBlur="javascript : doReload(this);" ></div>';
				
				}
				
				
				$framework->tpl->assign("OP_VAR", $ol_str);
				$framework->tpl->assign("CL_VAR", $cl_str);
			}	
			
			
			$framework->tpl->assign("PAGE_TITLE",$pg_det['meta_title']); 
			$framework->tpl->assign("META_KEYWORD",$pg_det['meta_keyword']); 
			$framework->tpl->assign("META_DESCRIPTION",$pg_det['meta_description']); 
				
			$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");

			$image_name = md5($image_name);
			$framework->tpl->assign("IMG_NAME",$image_name);
			if($_REQUEST['cart_id'])
			$framework->tpl->assign("SUBMITBUTTON2", createImagebutton_Div("Update Cart","javascript:void(0);","updateCart(".$pg_det['product_id'].");"));
			else
			$framework->tpl->assign("SUBMITBUTTON2", createImagebutton_Div("Add to Cart","JavaScript:void(0);","addtocart(".$pg_det['product_id'].");"));
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/pg_details.tpl");
			$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
			exit;
			
			break;	
			 
			case "pg_details_product":
			
			
				// FOR SEO URLS
				$data1		=	$_REQUEST['data1'];
				$data2 		=	$_REQUEST['data1'];
				$data3		=	$_REQUEST['data3'];
				if($data3){
					$data_det					=	$objCategory->getProductPredefined($data3);
					//$_REQUEST['cat_id']			=	$data_det['category_id'];
					$_REQUEST['id']				=	$data_det['id'];
					$_REQUEST['product_id']		=	$data_det['product_id'];
					$_REQUEST['parent_cat_id']	=	$data_det['category'];
				}
				// END
				
				$product_id = $_REQUEST['id'];
				$product_details = 	$objProduct->getPredefinedGift_detail($store_id,$_REQUEST['cat_id'],$product_id);
				
				//PrintArray($product_details);exit;
				
				if($store_id !=0){
					$store_name =   $objProduct->getStoreNameByid($store_id);
				}else{
					$store_name	=	"The Personalized Gift";
				}
				// META DESCRIPTION & META KEYWORDS
				if($product_details[0]['product_sale_price'] > 0){
				$meta_description = $product_details[0]['category_name']." - ".$product_details[0]['product_title']." on ".$store_name." @ Base Prize:$".$product_details[0]['product_basic_price']." - Sale Price:$".$product_details[0]['product_sale_price'];
				$meta_keywords	=   $store_name.",".$product_details[0]['product_title'].",".$product_details[0]['category_name'].",Base Price:$".$product_details[0]['product_basic_price'].",Sale Price:$".$product_details[0]['product_sale_price'];
				
				}else{
				
				$meta_description = $product_details[0]['category_name']." - ".$product_details[0]['product_title']." on ".$store_name." @ Base Prize:$".$product_details[0]['product_basic_price'];
				
				$meta_keywords	=   $store_name.",".$product_details[0]['product_title'].",".$product_details[0]['category_name'].",Base Price:$".$product_details[0]['product_basic_price'];
				}
				//PAGE TITLE
				$page_title		=	$store_name." - ".$product_details[0]['product_title'];
			
				$framework->tpl->assign("PAGE_TITLE",$page_title);
				$framework->tpl->assign("META_KEYWORD",$meta_keywords);
				$framework->tpl->assign("META_DESCRIPTION",$meta_description);
				
				$framework->tpl->assign("PREDFD_GIFT_LIST",$product_details[0]);
				
				$framework->tpl->assign("PAGE_TITLE",$product_details[0]['meta_title']); 
				$framework->tpl->assign("META_KEYWORD",$product_details[0]['meta_keyword']); 
				$framework->tpl->assign("META_DESCRIPTION",$product_details[0]['meta_description']); 
								
				//$framework->tpl->assign("PREDFD_GIFT_LIMIT", $limitList);
			
				
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/pg_list_details.tpl");
			break;


			case "other_gifts":
				
				$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
				
				$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathOther($category_id,$product_id));
				
				//echo $category_id,$parent_id,$storename;
				$res				=	$objProduct->GetCustomProductsOfTheStoreAndcategory($category_id,$parent_id,$storename);
				
				$arr=array();
				for($i=0;$i<count($res);$i++)
				{
				
				$arr[$i]['product']	=	"Y";
				$arr[$i]['ImagePath']	=	"";
				$arr[$i]['ACT']	=	"desc";
				$arr[$i]['id']	=	$res[$i]['id'];
				$arr[$i]['name']	=	$res[$i]['display_name'];
				$arr[$i]['brand_id']	=	$res[$i]['brand_id'];
				$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				//$discount_price = $objProduct->getMemberPercent($objPrice->GetPriceOfProduct($res[$i]['id']));
				//$discount_price = $objPrice->GetPriceOfProduct($res[$i]['id']);
				$arr[$i]['price']	=	number_format($res[$i]['price'], 2, '.', '');
				$arr[$i]['discount_price']	=	number_format($discount_price, 2, '.', '');
				$arr[$i]['weight']	=	$res[$i]['weight'];
				$arr[$i]['description']	=	$res[$i]['description'];
				$arr[$i]['image_extension']	=	$res[$i]['image_extension'];
				$arr[$i]['date_created']	=	$res[$i]['date_created'];
				$arr[$i]['group_id']	=	$res[$i]['group_id'];
				$arr[$i]['personalise_with_monogram']	=	$res[$i]['personalise_with_monogram'];
				
				$arr[$i]['is_giftcertificate']	=	$res[$i]['is_giftcertificate'];
				$arr[$i]['active']	=	$res[$i]['active'];

				$bprice	=	"$".number_format($res[$i]['price'], 2, '.', '');
				if ($res[$i]['price']>0 && $bprice!=$arr[$i]['price'])
					$arr[$i]['base_price']	=	$res[$i]['price'];
					
					
				$arr[$i]['CUSTOM_FIELDS']	=	$objProduct->getProductCustomFields($res[$i]['id'],$_REQUEST['cart_id']);
					//	print_r($arr[$i]['CUSTOM_FIELDS']);
				
				}
				
				if($_REQUEST['cart_id'] > 0){
					$cart_item		=	$cart->getCartByCartID($_REQUEST['cart_id']);
					
				}
				$framework->tpl->assign("CART_ITEM",$cart_item);
				//print_r($arr);exit;
				$framework->tpl->assign("PRODUCTS",$arr);
				$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Add to Cart","javascript:void(0);" ,"submit_form();return false;"));
				$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));				
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/other_gifts.tpl");
				break;
	
			
		
				
			
}
if($_REQUEST['act']=='editor_tpl'){
	if($global['editor_display']=='notinner'){
		$framework->tpl->display(SITE_PATH."/modules/product/tpl/editor.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/editor_load.tpl");
	}
}else{
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
?>