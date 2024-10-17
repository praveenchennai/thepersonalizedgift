<?php
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");



$product 		=	new Product();
$objAccessory	=	new Accessory();
$objCategory	=	new Category();
$objPrice		=	new Price();
$ajax_editor 	= 	new Ajax_Editor();
$objCart		=	new Cart();



////for meta tags dispaly for gifts by adarsh
$product_det	=	$product->ProductGet($_REQUEST['product_id']);	

$page_title			=	$product_det['page_title'];
$meta_keywords		=	$product_det['meta_keywords'];
$meta_description	=	$product_det['meta_description'];


if($store_id){
	$productdet	=	$product->ProductGet($product_det['parent_id']);	 
	 
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

switch($_REQUEST['act']) {
	case "list_accessories":

		$base_price = $objPrice->GetPriceOfProduct($_REQUEST["product_id"]);
		
		
		$framework->tpl->assign("BASE_PRICE",$base_price);

		$prd_det = $product->ProductGet($_REQUEST["product_id"]);

		


		
		$prd_det['price'] = $objPrice->GetPriceOfProduct($prd_det['id']);
		$names = $prd_det["x_co"];
		$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");

		$image_name = md5($image_name);
		$framework->tpl->assign("IMG_NAME",$image_name);
		$framework->tpl->assign("PRD_DET",$prd_det);
		$framework->tpl->assign("NAMES",$names);

		$catacc = 	203;
		$subacc	=	$objAccessory->GetSubCatOfAcc($catacc);
		$string = $catacc;
		for($i=0;$i<count($subacc);$i++)
		{
			$string	=	$string.",".$subacc[$i][category_id];
			// $string contains all the subcategory id of art backgounds.
			//art backgound id is 203 which is given above.
		}


		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "25";
		//For fixing the bug in case the cat_id is null in the url the default value is 236 for showing the artbackgerounds.
		// If the category is null it will show all the accessories.
		// If the category_id is 236 it will show the artbackgrounds only.
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "236";

		//$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
		$_SESSION['$category_id'] = $category_id;
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " a.cart_name ";
		$product_id			=	$_REQUEST["product_id"];
		//$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
		$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";

		$param				=	"mod=ajax_editor&pg=editor&act=$act&product_id=$product_id&cat_id=$category_id&u_id=$user_id&disorder=3";
		
	
		//$category_id	=	204;
		//This is done to list all the subcategoris of the Art background when art background is listed.
		list($res, $numpad1, $cnt1, $limitList)	=	$objAccessory->accessoryLists($category_id, $store_id, $pageNo, $show, $param, OBJECT, $orderBy);

		//exit;
		$startpoint = $pageNo+1;
		$endpoint = $pageNo+$show;
		if ($cnt1 < $endpoint)$endpoint=$cnt1; 
		

		//For listing the poems
		list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,$limit,$param,OBJECT, '',174,$store_id);
		$framework->tpl->assign("ACCESSORY1",$rs);

		$childcategories = $objCategory->getChildCategoriesListById(203);
		//$prd_det = $product->ProductGet($_REQUEST["product_id"]);
		//$framework->tpl->assign("PRD_DET",$prd_det);

		$framework->tpl->assign("CATEGORY",$childcategories);
		
		$framework->tpl->assign("CATID",$_SESSION['$category_id']);
		$framework->tpl->assign("PGNO",$pageNo);
		
		$catArr1 = $objCategory->getChildCategoriesListById(174);
		$framework->tpl->assign("CAT", $catArr1);
		$framework->tpl->assign("STARTPOINT",$startpoint);
		$framework->tpl->assign("ENDPOINT",$endpoint);
		$framework->tpl->assign("TOTAL",$cnt1);
		$framework->tpl->assign("ACCESSORY",$res);
		$framework->tpl->assign("ACCESSORYDISP",$res[0]);
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getArtCategoryPath($category_id,0));
		$framework->tpl->assign("ACCESSORY_NUMPAD",$numpad1);
		/*
			@ For editing the gift
			@ 30/apr/2008
		*/
		if($_REQUEST['flag'])
		{
			$framework->tpl->assign("EDITFLAG",true);
			
		}
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
							
							if ($accArray->accessory_name=='Mat Frame' || $accArray->type=='frame')
								$framework->tpl->assign("MAT_NAME_DISP",$accArray->aname);
								
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
							$framework->tpl->assign("ACC_WOOD_TYPE",$accArray->type);
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
		///////////editing ends
		//$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Search","JavaScript:void(0);","loadPic(); "));
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Search","JavaScript:void(0);","return chkPoerty(); "));
		$framework->tpl->assign("SUBMITBUTTON2", createImagebutton_Div("Add to Cart","JavaScript:void(0);","addtocart($product_id);"));
		
		# -------------- custom code for Personalizedgift update cart button done by Jeffy on 08th May 2008------------------		
		if($framework->config['single_prod'] == 1){
			if($_REQUEST['edit'] == 1){
				$framework->tpl->assign("BTN_UPDATE_CART", createImagebutton_Div("Update Cart","JavaScript:void(0);","return updateCart($product_id); "));
			}
			unset($_REQUEST['edit']);
		}
		# --------------------------------------
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/user_accessoy_listing.tpl");
		$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
		break;

	case "list_accessories_ajax":
		
		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "25";
		
		// modified to search atrs without category
		 $category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: $_SESSION['$category_id'];
		$_SESSION['$category_id'] = $category_id; 
		
		
		
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "a.cart_name ";
		$parent_cat_id		=	$_REQUEST["parent_cat_id"];
		$product_id			=	$_REQUEST["product_id"];
		//$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
		$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
		
		$artname				=	$_REQUEST["art_name"] 	? $_REQUEST["art_name"] 		: "";
		$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
		
		$_SESSION['$pageNo']= $page;
		
		//Added by Retheesh for removing poems from Art Searching 05/Feb/09
		
		$acs_type = $_REQUEST['acs_type']; 
		if ($acs_type)	
		{
			$fields = "type";
			$field_value = $acs_type;
		}
		//End
		$str='';	
		$param				=	"mod=ajax_editor&pg=editor&act=$act&product_id=$product_id&cat_id=$category_id&u_id=$user_id";
		list($res, $numpad1, $cnt, $limitList)	=	$objAccessory->accessoryLists($category_id,$store_id,$pageNo,$show,$param,OBJECT, $orderBy,$fields,$field_value,'',$artname);
		
		$startpoint = $pageNo+1;
		$endpoint = $pageNo+$show;
		if ($cnt < $endpoint)$endpoint=$cnt; 
				
		if(count($res)>0)
		{
		
			  if(count($res)>0)
			{$strnumpad='<div style="padding-top:2px; text-align:left; padding-left:5px; float:left ">
		<span class="toplinkpagi">Viewing Art Backgrounds '.$startpoint.' - '.$endpoint.' of '.$cnt.'</span>
		
		</div>';
				$strnumpad=$strnumpad.'<div style="padding-top:2px; text-align:right; padding-right:5px; float:right ">
		<span class="toplinkpagi">'.$numpad1.'</span>
		
		</div>';
			}  

			$str=$strnumpad.'<div class="rows">'; 
			
			for($i=0;$i<count($res);$i++)
			{
				$test="'".$res[$i]->image_extension."'";
				$test1="'".addslashes($res[$i]->name)."'";
				$test2="'".$res[$i]->adjust_price."'";
				$str.='<span class="product"><div ></div>
		
							<div align="center">
								<div>
									<div style="margin-top:4px">
									<a href="JavaScript:displayBigImg('.$res[$i]->id.','. $test.','.$test1.','.$test2.');"><img src="'.SITE_URL.'/modules/product/images/accessory/thumb/'.$res[$i]->id.'.'.$res[$i]->image_extension.'" border=0 title="Select an Art Background" height="89"></a><br>';

				$str.=$res[$i]->name;
				$str.='
									</div>
								</div>
							</div>
							
							</span>';
			}
			$str.='</div></div>';
			$str.='<div style="padding-top:5px; clear: both; text-align:right; padding-right:5px; ">
		<span >'.$numpad1.'</span>
		
		</div><br>';
			
		}
		else
		{
			$str.='<br><br><span class="label" style="text-align:center;padding-left:50px;"> Sorry, Item not found...</span>';
		}
		
		$str.='<input type="hidden" name="catid" id=catid value='.$category_id.'>';
		$str.='<input type="hidden" name="pgNo" id=pgNo value='.$_SESSION['$pageNo'].'>';
		echo $str;
		exit;
		break;
		
	case "list_poem":
			
		$catacc=174;
		$limit = 1;
		$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "1";
		$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: $_SESSION['$category_id1'];
		if(!$category_id)
		$category_id=240;
		
		$_SESSION['$category_id1'] = $category_id;
		
		$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "list_poem";
		$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
		
		$storename			=	$_REQUEST["storename"] 	? $_REQUEST["storename"] 	: "";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "name";
		$flag				=	$_REQUEST["flag"] 		? $_REQUEST["flag"] 	: "acc";
		$parent_cat_id		=	$_REQUEST["parent_cat_id"];
		$product_id			=	$_REQUEST["product_id"];
		
		$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
		$page 				= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: $pageNo;
		$_SESSION['$pageNo']= $page;
		//$imgpath   		= SITE_URL."/img/backimg.png";
		
		$str='';
		$framework->tpl->assign("CATID",$_SESSION['$category_id']);
		$param			=	"mod=ajax_editor&pg=editor&act=$act&product_id=$product_id&cat_id=$category_id&u_id=$user_id&storename=$storename&parent_cat_id=$parent_cat_id&Falg=acc";
		$subacc	=	$objAccessory->GetSubCatOfAcc($catacc);
		//$string = $catacc;
		for($i=0;$i<count($subacc);$i++)
		{
			$string	=	$string.",".$subacc[$i][category_id];
		}	

		
	
		list($res, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesOfCatagory($pageNo,$limit,$param,OBJECT, $orderBy,$category_id);
		
		$numpad	=	str_replace("Previous","Previous Poem",$numpad);
		$numpad	=	str_replace("Next","Next Poem",$numpad);
		$startpoint = $pageNo+1;
		$endpoint = $pageNo+$show;
		if ($cnt1 < $endpoint)$endpoint=$cnt1; 

		
		if(count($res)>0){
			  if(count($res)>0){
			$strnumpad='<div style="padding-top:2px; clear: both; text-align:left; padding-right:5px; width:30%; float:left; ">
				
		<span class="toplinkpagi">&nbsp;Viewing Poems '.$startpoint.' - '.$endpoint.' of '.$cnt.'</span>
		
		</div>';
			
				$strnumpad=$strnumpad.'<div style="padding-top:2px; text-align:right; padding-right:5px; float:right; ">
				
		<span>'.$numpad.'</span>
		
		</div>';
			}  

			$str=$strnumpad.'<div style="width:700px; height:100px; position:absolute; margin-top:100px; visibility: visible;"></div><div class="">'; 
			
			
			for($i=0;$i<count($res);$i++){

				if(trim($res[$i]->poem)){

					$test="'".$res[$i]->image_extension."'";
					$test1="'".$res[$i]->name."'";
					$opt_count= substr_count($res[$i]->poem, '<Opening'); 
					$cl_count= substr_count($res[$i]->poem, '<Closing');
					$arr=explode('\n',$res[$i]->poem);

					// echo '<pre> ------------res start-------------- ';
					// print_r($res[$i]);
					// echo ' --------------res end------------ </pre>';
				
				///print nl2br($res[0]->poem);
				for($j=0;$j< count($arr);$j++){
					if(stristr($arr[j],'<Opening'))
					{
						unset($arr[j]);
					}
					if(stristr($arr[j],'<Closing'))
					{
						unset($arr[j]);
					}
				}
				$res[$i]->sss=implode('',$arr);
				$str.='';
				$imgpath1   		= SITE_URL."/modules/product/images/accessory/{$res[$i]->id}.jpg";	
				
				$str.='<div style="clear:both"></div><div style="height:20px;text-align:center;"><a  href="javascript:poem_id('.$res[$i]->id.','."'".$res[$i]->name."'".','.$opt_count.','.$cl_count.');"><img src="'.$global['tpl_url'].'/images/select.jpg" border=0></a></div>';
				$str.='<div style=width:670px; height:100px"></div><div  style="margin-left:8%;">
						<div style="width:600px;float:left;padding:5px;text-align:center;overflow:hidden;">
						<div class="blacktext_fordefault" ><img src="'.$imgpath1.'" border=0>';
				$str.='<div style="clear:both"></div><div style="height:20px">&nbsp;</div>';
						$str.='
						</pre></div><div style="clear:both"></div><div style="height:10px; width:600px;" align="center"> </div></div>
						</div>';
				$str.='<input type="hidden" name="PoemName" id="PoemName" value='.$res[$i]->name.'>'; 
			if($i>1 && ($i+1)%2==0)
			{

				$str.='<div style="clear:both"></div><div style="height:10px">&nbsp;</div>';
			}	
		
			}
			//$str.='<div style="clear:both"></div><div style="height:20px"><a  href="javascript:poem_id('.$res[$i]->id.','."'".$res[$i]->name."'".','.$opt_count.','.$cl_count.');"><img src="'.$global['tpl_url'].'/images/select.jpg" border=0></a></div>';
			}
			$str.='</div>';
			
			$str.='<div style="padding-top:5px; clear: both; text-align:right; padding-right:5px;">
		<span >'.$numpad.'</span>
		
		</div><br>';
			
		}
		else
		{
			$str.='<br><br><span class="label" style="text-align:center;padding-left:50px;"> Sorry, Item not found...</span>';
		}
		
		$str.='<input type="hidden" name="catid" id=catid value='.$_SESSION['$category_id'].'>';
		$str.='<input type="hidden" name="catid" id=catid value='.$_SESSION['$category_id'].'>';
		$str.='<input type="hidden" name="pgNo" id=pgNo value='.$_SESSION['$pageNo'].'>';
		
		echo $str;
		exit;
		break;
	case "load_txt":
		$req=&$_REQUEST;
		$ac_value = $objAccessory->GetAccessory($_REQUEST['po_id']);

		$text_op = $ajax_editor->GetTextBoxValuesO($ac_value['poem'],0);
		$newvar_op	=	explode(">",$text_op);
		
		$text_op = $ajax_editor->GetTextBoxValuesC($ac_value['poem'],0);
		$newvar_cl	=	explode(">",$text_op);
		
		/*//code modified by robin
		//31-03-2008
		
		$p_txt = explode("\n",$rs[0]->poem);
		
		

				if(strstr(strtolower($p_txt),'<opening'))
				{
					$op_cnt++;
					
						$p_txt = strstr($p_txt,":");
						$p_txt = str_replace(":","",$p_txt);
						$p_txt = str_replace(">","",$p_txt);
					
					//list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label,$y,$xval);
					//list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,$font_label_text,$y,$xval,$font_type);
					
	
				}
				elseif ((strstr(strtolower($p_txt),'<closing')))
				{
					
						$p_txt = strstr($p_txt,":");
						$p_txt = str_replace(":","",$p_txt);
						$p_txt = str_replace(">","",$p_txt);
					
					
				}
				
				
			*/
				
		$str='<div class="headingbox">&nbsp;&nbsp;Personalize Your Poem</div>';
		if($req['opt'] || $req['cl'])
		{
			$str.='<div>&nbsp;</div><div style=" padding-left:10px">';
			
			for($i=0;$i< $req['opt'];$i++)
			{
				$k=$i+1;
				$str.='<div class="innertextname1" ><b>Opening Line&nbsp;'.$k.'</b></div>';
				$str.='<div><input type="text" id="opt'.$i.'" name="opt" size="23" class="graytext_fordefault" value="'.rtrim($newvar_op[$i],",").'" onfocus="javascript : doClear(this)" onBlur="javascript : doReload(this);"></div>';
			}
			$str.='<input type="hidden" name="openingcount" value="'.$i.'">';
			for($j=0;$j< $req['cl'];$j++)
			{
				$q=$j+1;
				$str.='<div class="innertextname1"><b>Closing Line&nbsp;'.$q	.'</b></div>';
				$str.='<div><input type="text" name="col" id="col'.$j.'" size="23" class="graytext_fordefault" value="'.rtrim($newvar_cl[$j],",").'" onfocus="javascript : doClear(this)" onBlur="javascript : doReload(this);"></div>';
			}
			$str.='<input type="hidden" name="closingcount" value="'.$j.'">';
			$str.='<div>&nbsp;</div><div style=" padding-left:55px">'.createImagebutton_Div("Apply","javascript:void(0);","loadPicFrmPoem();").'</div>';
			$str.='</div>';
			}
		echo $str;
		break;	
	case "poemdet":
		$poem_id  = $_REQUEST["poem_id"];
		list($qry1,$table_id,$join_qry)=$objAccessory->generateQry('product_accessories','d','a');
		if($poem_id)
		{
			 $qry	=	"SELECT a.*,$qry1 FROM product_accessories a $join_qry WHERE a.id=".$poem_id;
			 $rs 	= 	$objAccessory->db->get_row($qry, ARRAY_A);
		}
		
		echo $rs['poem'];
		break;
	 

	 case "load_src":
	 
	 echo $_SESSION['pathim'].",".$_SESSION['port'];
	 
	 break;
	 
	 case "load_surname":
	 	 /**
           * This is used for getting surname text.
           * Author   : Salim
           * Created  : 20/May/2008
           */
	 	
	 		$surname		=	&$_REQUEST;
	 		$surnamearry	=	$ajax_editor->getSurName(strtolower($surname['surname']));
			$imgpath   		= SITE_URL."/img/backimg.png";	
			
	 		if(count($surnamearry) == ''){
	 			$strtoret	=	'nomatch';
	 		}
	 		else{
	 		$strtoret	=	'<div id="surdiv" style="text-align:center; margin-top:-420px;"><div style="font-family:Georgia; font-size:9px; width:350px; text-align:justify;margin:0 auto;color:#000000;" >
			'.substr($surnamearry->Text,0,1000).' ...<i>continued</i></div>';
	 		$strtoret.='<div style="font-family:Georgia; font-size:7px; width:250px; text-align:center;margin:0 auto; " >&nbsp;</div>';
				if(strlen($surnamearry->Arms)>110)
				{
				$strtoret.='<div style="font-family:Georgia; font-size:9px; width:320px; text-align:center;margin:0 auto;color:#000000" >'.substr($surnamearry->Arms,0,110).' ...</div>';
				}else{	 		
				$strtoret.='<div style="font-family:Georgia; font-size:9px; width:280px; text-align:center;margin:0 auto;color:#000000" >'.$surnamearry->Arms.'</div>';
	
				}
				if(strlen($surnamearry->Crest)>110)
				{
				$strtoret.='<div style="font-family:Georgia; font-size:9px; width:320px; text-align:center;margin:0 auto;color:#000000" >'.substr($surnamearry->Crest,0,110).' ...</div>';
				}else{	 		
				$strtoret.='<div style="font-family:Georgia; font-size:9px; width:280px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Crest.'</div>';
	
				}
	 		
	 		$strtoret.='<div style="font-family:Georgia; font-size:9px; width:280px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Origin.'</div></div>';
	 		}
	 		echo $strtoret;
	 		exit;

	 	break;
		
	default:
		
		//$base_price = $product->getProductPrice($_REQUEST["product_id"]);
		//$framework->tpl->assign("BASE_PRICE",$base_price);
		$prd_det = $product->ProductGet($_REQUEST["product_id"]);
		$prd_det['price'] = $objPrice->GetPriceOfProduct($prd_det['id']);
		$framework->tpl->assign("BASE_PRICE",$prd_det['price']);
		$names = $prd_det["x_co"];
		$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");
		
		$childcategories = $objCategory->getChildCategoriesListById(203);
		$framework->tpl->assign("CATEGORY",$childcategories);
		/* For getting parent id when parent_cat_id is NULL in URL	*/
		$parent_cat_id	=	$objCategory->getCategoryByProduct($_REQUEST["product_id"]);
		$framework->tpl->assign("PARENT_CAT_ID_D",$parent_cat_id);
		
		$image_name = md5($image_name);
		$framework->tpl->assign("IMG_NAME",$image_name);
	
	

		if($_REQUEST['flag'])
		{
			$framework->tpl->assign("EDITFLAG",true);
			
		}
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
			
		}	
		
		$framework->tpl->assign("PRD_DET",$prd_det);
		
		
		
		$framework->tpl->assign("NAMES",$names);
		$CID=$_REQUEST['parent_cat_id'];
		$framework->tpl->assign("CID",$CID);
	
		$storename			=	$_REQUEST["storename"] 	? $_REQUEST["storename"] 	: "";
		$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$flag				=	$_REQUEST["flag"] 		? $_REQUEST["flag"] 	: "acc";
		$parent_cat_id		=	$_REQUEST["parent_cat_id"];
		$product_id			=	$_REQUEST["product_id"];
		
		if($_REQUEST['flag'])
		{
			$framework->tpl->assign("EDITFLAG",true);
			
		}
		
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
			foreach($CARTARRAY['records'] as $key=>$val)
			{
				if($val->id==$cart_id)
				{
					$artArray=$val;
			
					$framework->tpl->assign("TOTAL_PRICE",$artArray->total_price);
					for($i=0;$i< count($artArray->accessory);$i++)
					{
						$accArray=$artArray->accessory[$i];
						if($accArray->type=='art')
						{
							$framework->tpl->assign("ART_ANAME",$accArray->aname);
							$framework->tpl->assign("ART_IMG",$accArray->accessory_id);
							$framework->tpl->assign("ART_EXT",$accArray->image_extension);
							$image_extension=$accArray->image_extension;
							
							
							$source_path=SITE_PATH."/modules/cart/images/".$cart_id.".".$image_extension;
							$dest_path   = SITE_URL."/modules/ajax_editor/images/";	
							$save_filename=$image_name;
							copy($source_path,$dest_path.$save_filename);
							$_SESSION['pathim']=SITE_URL."/modules/ajax_editor/images/$image_name.jpg";
							
						}
						if($accArray->type=='mat')
						{
							$framework->tpl->assign("MAT_ID",$accArray->accessory_id.".".$accArray->image_extension);
							$framework->tpl->assign("ACC_ID",$accArray->accessory_id);
							$framework->tpl->assign("MAT_NAME",$accArray->aname);
							$framework->tpl->assign("MAT_PRICE",$accArray->price);
						}
						
					}
					
					if($artArray->name=="Grandma's Garden Gift")///for Grandmas Garden Gift
					{
						    $name_counter=0;
							$gender_counter=0;
							$tree_name=array();
							$tree_gender=array();
							$option = explode("|",$artArray->notes); 
							
							for ($k=0;$k<sizeof($option);$k++)
							{
								$arr1 = explode("~",$option[$k]);
								if ($arr1[0] && $arr1[1])
								{
									$options[$k]["label"] = $arr1[0];
									$options[$k]["value"] = $arr1[1];
								}	
								if($k==0)
								{
									$framework->tpl->assign("FAMILY_NAME",$options[$k]["value"]);
								}
								else{
									
									if(strstr($arr1[0],"Name"))
									{
										$tree_name[$name_counter]=$arr1[1];
										$name_counter++;
									}
									else if(strstr($arr1[0],"Gender"))
									{
										$tree_gender[$gender_counter]=$arr1[1];
										$gender_counter++;
									}
								}
							}
							foreach($options as $key=>$value)
							{
								if(strstr($value['label'],"Family Name"))
								{
									$str_tree.='<div style=""><span style="font-size:12px;FONT-WEIGHT:bold; ">'.$value['label'].': </span><span style="font-size:12px;"><span id="salim">'.ucfirst($value['value']).'</span></span></div><br>';
									
									$str_tree.='<div style=""><span style="font-size:12px;FONT-WEIGHT:bold; ">Names:</span><span style="font-size:12px;">&nbsp;</span></div><br>';
								}
								else
								{
								if(strstr($value['label'],"Gender"))
								$str_tree.='<div style=""><span style="font-size:12px;FONT-WEIGHT:bold; ">&nbsp;(</span><span style="font-size:12px;"><span id="salim">'.ucfirst($value['value']).'</span></span><span style="font-size:12px;FONT-WEIGHT:bold; ">)</span></div><br>';
								else
								$str_tree.='<div style="float:left"><span style="font-size:12px;FONT-WEIGHT:bold; "></span><span style="font-size:12px;"><span id="salim">'.ucfirst($value['value']).'</span></span></div>';
								}
							}
							$framework->tpl->assign("TREE_NAME",$tree_name);
							$framework->tpl->assign("TREE_GENDER",$tree_gender);
							$framework->tpl->assign("STR_TREE",$str_tree);
					}//Family Tree Gift 2 ends
					
					
					if($artArray->name=="Family Tree Gift") //for Family Tree Gift
					{
						    $name_counter=0;
							$dob_counter=0;
							$tree_name=array();
							$tree_dob=array();
							$option = explode("|",$artArray->notes); 
							
							for ($k=0;$k<sizeof($option);$k++)
							{
								$arr1 = explode("~",$option[$k]);
								
								if ($arr1[0] && $arr1[1])
								{
									$options[$k]["label"] = $arr1[0];
									$options[$k]["value"] = $arr1[1];
								}	
								if($k==0)
								{
									$framework->tpl->assign("FAMILY_NAME",$options[$k]["value"]);
								}
								else{
									
									if(strstr($arr1[0],"Name"))
									{
										$tree_name[$name_counter]=$arr1[1];
										$name_counter++;
									}
									else if(strstr($arr1[0],"DOB"))
									{
										$tree_dob[$dob_counter]=$arr1[1];
										$dob_counter++;
									}
								}
							}
							foreach($options as $key=>$value)
							{
							
							   if(strstr($value['label'],"Family Name"))
								{
									$str_tree.='<div style=""><span style="font-size:12px;FONT-WEIGHT:bold; ">'.$value['label'].': </span><span style="font-size:12px;"><span id="salim">'.ucfirst($value['value']).'</span></span></div><br>';
									
									$str_tree.='<div style=""><span style="font-size:12px;FONT-WEIGHT:bold; ">Names:</span><span style="font-size:12px;">&nbsp;</span></div><br>';
								}
								else
								{
									if(strstr($value['label'],"DOB"))
									{
										if($value['value']!='' && $value['value']!='mm/dd/yyyy')
										{
											$dateArr=explode("/",$value['value']);
											$timestamp = mktime( 0, 0, 0, $dateArr[1], $dateArr[0], $dateArr[2] ); 
											//$value['value']=date("F d,Y",$timestamp);
											$str_tree.='<div style="" ><span style="font-size:12px;"><span id="salim">('.$value['value'].')</span></span></div><br>';
										}	
									}
									else
										$str_tree.='<div style="float:left"><span style="font-size:12px;FONT-WEIGHT:bold; "> </span><span style="font-size:12px;"><span id="salim">'.ucfirst($value['value']).'</span></span></div>';
								}	
							}
							$framework->tpl->assign("TREE_NAME",$tree_name);
							$framework->tpl->assign("TREE_DOB",$tree_dob);
							$framework->tpl->assign("STR_TREE",$str_tree);
							
					}//Family Tree G
					
					if($artArray->name == "Surname Gift"){//Surname History Gift
						$CID=233;
						$framework->tpl->assign("CID",$CID);
						$option = explode("~",$artArray->notes);
						$surnamearry	=	$ajax_editor->getSurName($option[1]);
						
							if(empty($artArray->accessory[0]->aname)){
							 $strtoret ='<div id="surdiv" style="text-align:center; margin-top:-420px;"><div style="font-family:Georgia; font-size:9px; width:350px; text-align:justify;margin:0 auto;color:#000000" >'.substr($surnamearry->Text,0,1000).' ...</div>';
	
						 	}	
						 	else{
							 	$strtoret ='<div id="surdiv" style="text-align:center; margin-top:-360px;"><div style="font-family:Georgia; font-size:9px; width:350px; text-align:justify;margin:0 auto;color:#000000" >'.substr($surnamearry->Text,0,1000).' ...</div>';
						 	}
							 $strtoret.='<div style="font-family:Tahoma; font-size:7px; width:250px; text-align:center;margin:0 auto; " >&nbsp;</div>';
							 $strtoret.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto;color:#000000" >'.$surnamearry->Arms.'</div>';
							 $strtoret.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Crest.'</div>';
							 $strtoret.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Origin.'</div></div>';
		 		
						 	$framework->tpl->assign("SURNAME_TEXT",$strtoret);
						 	$framework->tpl->assign("SURNAME_TEXT_BOX",$option[1]);
							
					 	//echo $strtoret;
						
						

					}
				}
			}
		}
		///////////editing ends	
		
		$framework->tpl->assign("SUBMITBUTTON3", createImagebutton_Div("Submit","javascript:void(0);","return checktree(); "));
		if($_REQUEST['cart_id'])
		$framework->tpl->assign("SUBMITBUTTON2", createImagebutton_Div("Update Cart","javascript:void(0);","updateCart($product_id); return false "));
		else
		$framework->tpl->assign("SUBMITBUTTON2", createImagebutton_Div("Add to Cart","javascript:void(0);","addtocart($product_id);return false;"));
		
		if($names>3 && $CID!=233)
		{
		
			//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor.tpl");
					//$framework->tpl->display($global['curr_tpl']."/inner.tpl");

			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/user_accessoy_listing.tpl");
			$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
		}
		else if($CID==233)
		{
		
		$framework->tpl->assign("SUBMITBUTTON3", createImagebutton_Div("Search","JavaScript:void(0);","return checkSur(); "));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/user_accessoy_listing.tpl");
					$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
					/*$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor.tpl");
					$framework->tpl->display($global['curr_tpl']."/inner.tpl");*/

		}else
			{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor_per.tpl");
			$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
		}

}
?>