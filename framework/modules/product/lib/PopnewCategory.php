<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
 session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");

$objAccessory	=	new Accessory();
$objCategory	=	new Category();
$objProduct		=	new Product();
 
switch($_REQUEST['act']) {
    case "category";
	$grp_id=$_REQUEST['grp_id'];
		$prd_id=$_REQUEST['prd_id'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['image_extension']['name']);
			$ftype			=	$_FILES['image_extension']['type'];
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			if( ($category_id 	= 	$objCategory->modifycategory($req,$grp_id))>0 ) {
				$action = $req['category_id'] ? "Updated" : "Added";
            	setMessage("Category $action Successfully", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"product", "pg"=>"PopnewCategory"),"act=accessory&cat_id=".$category_id."&&grp_id=".$grp_id."&prd_id=".$prd_id));
			break;
			}
			else
			{
			setMessage("Category updation failed");
			}
		}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/popCategory.tpl");
		break;
		case "accessory":
		$grp_id=$_REQUEST['grp_id'];
		$prd_id=$_REQUEST['prd_id'];
		$cat_id=$_REQUEST['cat_id'];
		if(empty($grp_id) || $grp_id==0)
			$grp_id		=	$objCategory->GetCategoryInGroupID($cat_id);
		//echo "cat_id: $cat_id<br>";
		//echo "grp_id: $grp_id<br>";
		//echo "prd_id: $prd_id<br>";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		if($grp_id>0)
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			if( ($message 	= 	$objAccessory->AddnewAccessory($req,$grp_id,$cat_id))>0 ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Accessory $action Successfully", MSG_SUCCESS);
			}
			else
			{
			setMessage("Accessory updation failed");
			}
		}
		else
			{
			setMessage("Accessory updation failed");
			}
		}
		//echo "$cat_id<br>";
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/popNewAccessory.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>