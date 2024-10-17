<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$email			= 	new Email();
$objCategory	=	new Category();
$objProduct		=	new Product();
$objPrice		=	new Price();
$objCombination	=	new Combination();
$objMade		=	new Made();
$objAccessory	=	new Accessory();
$objUser		= 	new User();

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";

/* Set Number of Listing Per page :: Added By Aneesh Aravindan*/
if( !$global['product_list_num'] )
$global['product_list_num'] = 9;


switch($_REQUEST['act']) {
	case "search":
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());
		//$objAccessory->ValidateTheSelectionOfAccessory(array('product_id'=>'6'));
		//exit;
		//$framework->tpl->assign("MADE", $objMade->GetAllMade());
		
		$objCategory->GetAllCategoryoftheStore($catArr,$storename);
		
		//print_r($catArr);
		$framework->tpl->assign("CATEGORY",$catArr);
		$framework->tpl->assign("SEARCHBUTTON", createButton("SEARCH","#","check()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_search.tpl");
		break;
	case "result":
	
		$listall		=	$_REQUEST["show_All_page"] ? $_REQUEST["show_All_page"] : "N";
		$show_status 	= 	false;
		$orderBy		=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$pageNo			=	$_REQUEST["pageNo"] 	? $_REQUEST["pageNo"] 	: "1";
		$limit			=	$_REQUEST["limit"] 		? $_REQUEST["limit"] 	: $global['product_list_num'];
		$param			=	"mod=product&pg=search&act=result&type=".$_REQUEST['type']."&orderBy=$orderBy";
		$storename		=	$_REQUEST['storename']	? $_REQUEST['storename']:'';
		//echo $_REQUEST['type'];
		//$params='type='.$_REQUEST['type'];
		
		
		
		switch($_REQUEST['type']) {
		case	'normal':// normal search result.
		
				/*The keyword should search for the product name and product description*/
				
				//$param		.=	"&keyword=".$_REQUEST['keyword'];
				$param		.=	"&keyword=".$_REQUEST['keyword']."&price1=".$_REQUEST['price1']."&price2=".$_REQUEST['price2']."&category_id=".$_REQUEST['category_id'];
				if ($listall == "Y"){//if listing is all
					$rs	=	$objProduct->GetProductsearchResults_1($_REQUEST,'normal',$pageNo, $limit, $param, OBJECT, $orderBy,$storename,$listall);
					if(count($rs)>0)
						$show_status = true;
				}
				
				else{
					if ($global['single_prod'] == 1){//For personalized gift
						list($rs1, $numpad1, $cnt, $limitList)	=	$objProduct->GetAllsearchResults($_REQUEST,'normal',$pageNo, $limit, $param, OBJECT, $orderBy,$storename);
						if($cnt<=$limit){
							$framework->tpl->assign("NO_NAV","Y");
						}else{
							$framework->tpl->assign("NO_NAV","N");
						}				
						if(count($rs1)>0)
						$show_status = true;	
					}
						else{
							list($rs, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsearchResults($_REQUEST,'normal',$pageNo, $limit, $param, OBJECT, $orderBy,$storename);
							if($cnt<=$limit){
							$framework->tpl->assign("NO_NAV","Y");
							}else{
								$framework->tpl->assign("NO_NAV","N");
							}
							if(count($rs)>0)
							$show_status = true;
						}
				}
				
				$startpoint = ($limit*$pageNo)- $limit;
				$startpoint = $startpoint+1;
				$endpoint   = $startpoint+$limit;
				$endpoint   = $endpoint-1;
				if ($cnt < $endpoint)$endpoint=$cnt; 
				
				//print_r($rs1);
				break;
		case	'advanced':// Advanced search result.
				$param		.=	"&title=".$_REQUEST['title'];
				$param		.=	"&subject=".$_REQUEST['subject'];
				$param		.=	"&keyword=".$_REQUEST['keyword'];
				$param		.=	"&location=".$_REQUEST['location'];
				$param		.=	"&brand_id=".$_REQUEST['brand_id'];
				$param		.=	"&category_id=".$_REQUEST['category_id'];
				$param		.=	"&price1=".$_REQUEST['price1'];
				$param		.=	"&price2=".$_REQUEST['price2'];
				
				if ($listall == "Y"){//if listing is all
					$rs	=	$objProduct->GetProductsearchResults_1($_REQUEST,'advanced',$pageNo, $limit, $param, OBJECT, $orderBy,$storename,$listall);
					if(count($rs)>0)
						$show_status = true;
				}
				
				else{
					list($rs, $numpad, $cnt, $limitList)	=	$objProduct->GetProductsearchResults($_REQUEST,'advanced',$pageNo, $limit, $param, OBJECT, $orderBy,$storename);
					if(count($rs)>0)
						$show_status = true;
					if($cnt<=$limit){
						$framework->tpl->assign("NO_NAV","Y");
					}else{
						$framework->tpl->assign("NO_NAV","N");
					}
				}
				break;
		}

		
		if($show_status === true)//If results are available....
			{
				$arr1 =	array();
				
				for($i=0;$i<count($rs1);$i++)
				{
						$arr1[$i]['product']			=	"Y";
						$arr1[$i]['ImagePath']			=	"";
						$arr1[$i]['ACT']				=	"desc";
						$arr1[$i]['id']					=	$rs1[$i]['id'];
						$arr1[$i]['name']				=	$rs1[$i]['name'];
						$arr1[$i]['description']		=	strip_tags($rs1[$i]['description']);
						$arr1[$i]['image_extension']   	=	$rs1[$i]['image_extension'];
						$arr1[$i]['price']   	        =	$rs1[$i]['price'];
						$arr1[$i]['type']				=	$rs1[$i]['type'];
						$arr1[$i]['TableName']			=	$rs1[$i]['TableName'];
						$arr1[$i]['cat_id']				=	$objProduct->getCategoryIdOfSearchItem($rs1[$i]['id'],$rs1[$i]['TableName']);
						
						
				}
				
				
				
			
			$arr=array();
			for($i=0;$i<count($rs);$i++)
				{
							
					//print_r($rs[$i]);
						$arr[$i]['product']			=	"Y";
						$arr[$i]['ImagePath']		=	"";
						$arr[$i]['ACT']				=	"desc";
						$arr[$i]['id']				=	$rs[$i]['id'];
						$arr[$i]['name']			=	$rs[$i]['display_name'];
						$arr[$i]['brand_id']		=	$rs[$i]['brand_id'];
						$arr[$i]['price']			=	"$".$objPrice->GetPriceOfProduct($rs[$i]['id']);
						//$discount_price = $objProduct->getMemberPercent($objPrice->GetPriceOfProduct($rs[$i]['id']));
						//$arr[$i]['price']	        =   "$".$discount_price;
						$arr[$i]['weight']			=	$rs[$i]['weight'];
						$arr[$i]['description']		=	$rs[$i]['description'];
						$arr[$i]['image_extension']	=	$rs[$i]['image_extension'];
						$arr[$i]['date_created']	=	$rs[$i]['date_created'];
						$arr[$i]['group_id']		=	$rs[$i]['group_id'];
						$arr[$i]['personalise_with_monogram']	=	$rs[$i]['personalise_with_monogram'];
						$arr[$i]['active']			=	$rs[$i]['active'];
						//print_r($arr[$i]);
						//echo "<br><BR>";
				}
				
				$framework->tpl->assign("STARTPOINT",$startpoint);
				$framework->tpl->assign("ENDPOINT",$endpoint);
				$framework->tpl->assign("TOTAL",$cnt);
				
				$framework->tpl->assign("SEARCHRES",$arr1);
				$framework->tpl->assign("CURR_CAT_NAME","Search Results");
				$framework->tpl->assign("PARAM",$_REQUEST['keyword']);
				$framework->tpl->assign("TYPE",$_REQUEST['type']);
				$framework->tpl->assign("PRODUCTS",$arr);
				$framework->tpl->assign("HeadingImage","search-result-heading.jpg");
				$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPath($category_id,$product_id));
				$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
				$framework->tpl->assign("ACCESSORY_NUMPAD",$numpad1);
				$framework->tpl->assign("CMS_NUMPAD",$numpad2);
				
				if ($global['single_prod'] == 1){
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing_ptp.tpl");
				}
				else{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing.tpl");
				}
				break;
			
			}else {
				$objCategory->GetAllCategoryoftheStore($catArr,$storename);
		
				
				$framework->tpl->assign("PARAM",$_REQUEST['keyword']);
				$framework->tpl->assign("CATEGORY",$catArr);
				$framework->tpl->assign("SEARCHBUTTON", createButton("SEARCH","#","check()"));
				if ($global['single_prod'] == 1){
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_listing_ptp.tpl");
				}
				else{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_product_search.tpl");
				}
				break;
			}
			//exit;
			/**
			* Redirecting to the search page after login.Onsubit it searches for the inventry and will go the case "inve_res".
			* Author   : Salim
			* Created  : 06/May/2008
			*/
			case "search_inventry":
				checkLogin();
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/product/tpl/user_product_search.tpl");
			break;
			case "inve_res":
				checkLogin();
					$orderBy		=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
					$pageNo			=	$_REQUEST["pageNo"] 	? $_REQUEST["pageNo"] 	: "1";
					$limit			=	$_REQUEST["limit"] 		? $_REQUEST["limit"] 	: "5";
					$param			=	"mod=product&pg=search&act=inve_res&orderBy=$orderBy";
//					$param			.=	"&email=".$_REQUEST['email'];
					
					/*if($_REQUEST['email'] == ''){
						setMessage("Please Enter Email Id");
					}
					else {*/
						list($rs, $numpad, $cnt, $limitList)	=	$objProduct->searchInventry($_REQUEST,'',$pageNo, $limit, $param, ARRAY_A, $orderBy);
	
								$myId	=	$_SESSION['memberid'];
								$usdeta	=	$objUser->getUserdetails($myId);
								
								$mail_header = array();
								$mail_header["from"]   	= 	$usdeta['email'];
								$mail_header['to'] 		= 	$framework->config['admin_email'];
								
								$dynamic_vars	=	array();
								$dynamic_vars["PART_NUMBER"]  	= $_POST["partno"];
								$dynamic_vars["MODEL_NO"]  		= $_POST["modelno"];
								$dynamic_vars["AIRCRAFT_TYPE"]  = $_POST["craftype"];
								$dynamic_vars["EMAIL"]  		= $usdeta['email'];
								$dynamic_vars["NAME"]  			= $usdeta['first_name'];
								$dynamic_vars["PHONE"]  		= $usdeta['telephone'];
								
								$email->send("search_information",$mail_header,$dynamic_vars);
								$framework->tpl->assign("SHOW_SEARCH_FIELDS",'Y');
								$framework->tpl->assign("RESULTS",$rs);
								$framework->tpl->assign("NUMPAD",$numpad);
//					}
						
						
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/product/tpl/user_product_search.tpl");
				break;
				


}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>