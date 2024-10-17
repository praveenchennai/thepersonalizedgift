<?php
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
$product = new Product();
$base_price = $product->getProductPrice($_REQUEST["product_id"]);
$framework->tpl->assign("BASE_PRICE",$base_price);
$prd_det = $product->ProductGet($_REQUEST["product_id"]);
$names = $prd_det["x_co"];
$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");

$image_name = md5($image_name);
$framework->tpl->assign("IMG_NAME",$image_name);

$framework->tpl->assign("PRD_DET",$prd_det);

$framework->tpl->assign("NAMES",$names);
$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor.tpl");
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>