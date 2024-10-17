<?php
	session_start();
	checkLogin();		
	include_once(FRAMEWORK_PATH."/modules/editor/lib/class.editor.php");
	
	
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");




$user           =   new User();
$editorObj      =   new editor();
$objCategory	=	new Category();
$objProduct		=	new Product();
$objCombination	=	new Combination();
$objMade		=	new Made();
$objAccessory	=	new Accessory();
$store			=	new	Store();

	if($_REQUEST['sProId']) 
	{
		$framework->tpl->assign("ACTIVEMODE", 'edit');
	}else
	{
		$framework->tpl->assign("ACTIVEMODE", 'new');
	}
	
				 
				 	
					
		include_once(SITE_PATH."/includes/xmlConfig.php");
		$sellprod			=	$_REQUEST['sellprod'];
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";		
		$parent_id			=	$_REQUEST['parent_id'];
		$img_type			=	$_REQUEST["img_type"];		
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";	
		$pro_save_id 		= 	$_REQUEST["pro_save_id"] ? $_REQUEST["pro_save_id"] : "";	
		if($_REQUEST['edit']=='edit'){			
			$edit			=	$_REQUEST['edit'];			
		}else if($_REQUEST['edit']=='cust'){			
			$edit				=	"cust";
		}else{
			$edit				=	"new";
		}
		if($edit=='edit'){
			$parentArr			=	$objProduct->ProductGet($parent_id);
			if($global['artist_selection']=='Yes') 
					$proArr['imagePath'] ="images/"."2D_".$parentArr['id'].".".$parentArr['image_extension'];
			else
					$proArr['imagePath'] ="images/".$parentArr['id'].".".$parentArr['image_extension'];
								
			if($_REQUEST['oveImage']){
				$proArr['ovePath'] ="images/".$_REQUEST['oveImage'];
			}else{
				$proArr['ovePath'] ="images/"."OV_".$parentArr['id'].".".$parentArr['overlay'];
			}
			$proArr['id']		=	$parent_id;
			if($img_type=='P'){
				$destPath		=	"edit_product";
			}else{
				$destPath		=	"edit_art";	
			}				
		}else{		
			if($img_type=='P'){
				$proArr			=	$objProduct->ProductGet($_REQUEST['id']);
				if($global['artist_selection']=='Yes') 
					$proArr['imagePath'] ="images/"."2D_".$proArr['id'].".".$proArr['image_extension'];
				else
					$proArr['imagePath'] ="images/".$proArr['id'].".".$proArr['image_extension'];
				if($_REQUEST['oveImage']){
					$proArr['ovePath'] ="images/".$_REQUEST['oveImage'];
				}else{
					$proArr['ovePath'] ="images/"."OV_".$proArr['id'].".".$proArr['overlay'];
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
		
			if($_REQUEST['notsell']!=1){
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
					$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($_REQUEST['id']));
				}
					
		$framework->tpl->assign("PRODUCT", $proArr);
		$framework->tpl->assign("DESTPATH", $destPath);
		$framework->tpl->assign("IMAGE_TYPE", $img_type);
		$framework->tpl->assign("PRO_SAVE_ID", $pro_save_id);
		$framework->tpl->assign("EDIT", $edit);
		$framework->tpl->assign("BASE_PRODUCT",$parent_id);
		$framework->tpl->assign("SELL_OPTION", $sellprod);
		$framework->tpl->assign("USERID", $_SESSION['memberid']);
		
	$framework->tpl->display(SITE_PATH."/modules/editor/tpl/buildapaddle.tpl");
	
   
?> 