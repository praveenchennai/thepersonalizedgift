<?php
/**
 * 
 *
 * @author Adarsh v s
 * @package defaultPackage
 */
 
 include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
 include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
 include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
 include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
 include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
	
 $ShippingObj	=	new Shipping();
 $objUser 	= 	new User();
 $email	 		= new Email();
 $cart 			= 	new Cart();
	
 switch ($_REQUEST["act"])
 {
 	case "shipping_method":
		
		$str='';
		$country_id=$_REQUEST['country_id'];
		if($store_id){
		$storeDet=$objUser->getStoreDetById($store_id);
		$userDet=$objUser->getUserdetails($storeDet['user_id']);	
		$shippingMethods= $ShippingObj->getShippingMethodsAll($storeDet['name']);	
		}
		else{
			$userDet=$objUser->getUserDetByType(0);
			$userDet['country']=840;
			$shippingMethods= $ShippingObj->getShippingMethodsAll(0);
		}
		
		$IntrnlDetails=$ShippingObj->getInternationalMessageDetails();
		$flatRateShippingDet=$ShippingObj->getFlatRateShipping($store_id);
		
					
		if($country_id==$userDet['country']){
			$ShppingType='domestic';
			if($flatRateShippingDet['shipping_status']=='Y'){
				$type='flat_rate';
			}
			else if(count($shippingMethods)){ 
				$type='ship_method';
			}
			else{
				$type='not_set';
			}
		}
		else{
			$ShppingType='international';
			
			/*if($flatRateShippingDet['intr_frs_status']=='N'){
				$type='flat_rate';
			}*/
			if($flatRateShippingDet['intr_frs_status']=='3'){
				$type='flat_rate';
			}else if($flatRateShippingDet['intr_frs_status']=='1'){
				$type='inter_not';
			}
			else if ($flatRateShippingDet['intr_frs_status']=='2'){
			    $type='inter';
			}
			else if(count($shippingMethods)){ 
				$type='ship_method';
			}
			else{
				$type='not_set';
			}
		}
		
		
		///added by adarsh for flat rate
		$matCount=0;
		
		$avilable_table	=$framework->config['avilable_access'];
		if($avilable_table=='N'){
			$cartArray	=	$cart->getCart('N');
			$CARTARRAY	= $cart->getCart('N');
		//Purpose: To get the parent_cat_id for Tree and Surname Gifts When going back to editor from cart view.
		// Project Personalized Gift

				foreach ($CARTARRAY['records'] as $cartArray_new){
					if(empty($cartArray_new->accessory)){
						$cartArray_new->accessory[0]->category_id=$objCategory->getCategoryByProduct($cartArray_new->product_id);
					}
				}
		}else{
			$CARTARRAY	= $cart->getCart();
			$cartArray	=	$cart->getCart();
		}
	//	print_r($CARTARRAY);
		
		///added by adarsh for flat rate ends
		
		
		switch($type){
		
			case "flat_rate":
			
				$giftShipppingPrice=0;
				$matShipppingPrice=0;
				$custom_sprice_total=0;
				
				if($ShppingType=='domestic'){
					$giftShippping='sp_firstgift';
					$additionalGiftShippping='sp_additionalgift';
					$matShippping='sp_firstmat';
					$additionalMatShippping='sp_additionalmat';
					
					$woodFrameShipping				=	'sp_firstwood';
					$additionalwoodFrameShipping	=	'sp_additionalwood';
					$plaqueFrameShipping			=	'sp_firstplaque';
					$additionalplaqueFrameShipping	=	'sp_additionalplaque';
					$glassFrameShipping				=	'sp_firstglass';
					$additionalglassFrameShipping	=	'sp_additionalglass';
					
					$lagrgeWoodFrameShipping			=	'sp_firstlargewoodframe';
					$additionallagrgeWoodFrameShipping	=	'sp_additionallargewoodframe';
					
				}	
				else{
					$giftShippping='intr_sp_firstgift';
					$additionalGiftShippping='intr_sp_additionalgift';
					$matShippping='intr_sp_firstmat';
					$additionalMatShippping='intr_sp_additionalmat';
					
					$woodFrameShipping				=	'intr_sp_firstwood';
					$additionalwoodFrameShipping	=	'intr_sp_additionalwood';
					$plaqueFrameShipping			=	'intr_sp_firstplaque';
					$additionalplaqueFrameShipping	=	'intr_sp_additionalplaque';
					$glassFrameShipping				=	'intr_sp_firstglass';
					$additionalglassFrameShipping	=	'intr_sp_additionalglass';
					
					$lagrgeWoodFrameShipping			=	'intr_sp_firstlargewoodframe';
					$additionallagrgeWoodFrameShipping	=	'intr_sp_additionallargewoodframe';
					
				}
				
				
				
				
				$matcount=0;
				$$tortalPrice=0;
				
				$giftCount = 0;
				$customCount=0;
				$acc_total=0;
				
				 $matShipppingPrice=0;
					 $giftShipppingPrice = 0;
					 $acc_total=0;	
				
				$c = 0;	$g=0; $d=0; $e=0; $f = 0; 
				for($i=0;$i< count($CARTARRAY['records']);$i++){
				
				$m = 0; 
				if($CARTARRAY['records'][$i]->custom_product=='N')
					{

					 $quantity=$CARTARRAY['records'][$i]->quantity;
					 
						
				
					foreach($CARTARRAY['records'][$i]->accessory as $key=>$val)
					{
				
						if($val->type=='mat')
						{   $c++;
							for($j=0;$j<$quantity;$j++)
							{
							if($c==1 && $j==0){
								$matShipppingPrice+=$flatRateShippingDet[$matShippping];
							}
							else
							$matShipppingPrice+=$flatRateShippingDet[$additionalMatShippping];
							//$matcount++;
							
							}	
								
						}

					
						
						//adding the frame price added on 16 nov 09
						$myFrameName		=	$val->aname;
						$frameType			=	"";
						$frameCats			=	$cart->getAccessoryCategoryByName($myFrameName); 
						$cat_glassframeid	=	$framework->config['config_cat_glassframeid'];
						$cat_woodframeid	=	$framework->config['config_cat_woodframeid'];
						$cat_plaqueframeid	=	$framework->config['config_cat_plaqueframeid'];
						
						for($x=0;$x<count($frameCats);$x++)
						{
							if($frameCats[$x]["category_id"]==$cat_glassframeid)
							{
								$frameType	=	"glassFrame";
							}
							elseif($frameCats[$x]["category_id"]==$cat_woodframeid)
							{
								$frameType	=	"woodFrame";
							}
							elseif($frameCats[$x]["category_id"]==$cat_plaqueframeid)
							{
								$frameType	=	"plaqueFrame";
							}
							
							elseif($frameCats[$x]["category_id"]==281)
							{
								$frameType	=	"lagrgeWoodFrame";
							}
							
						}
						// checking the frame type
												
						if($val->type=='frame' && $frameType ==	"glassFrame")
						{    $d++;
							for($j=0;$j<$quantity;$j++)
							{
								if($d==1 && $j==0){
									$matShipppingPrice+=$flatRateShippingDet[$glassFrameShipping];
								}
								else
								$matShipppingPrice+=$flatRateShippingDet[$additionalglassFrameShipping];
							}	
						}
						if($val->type=='frame' && $frameType ==	"plaqueFrame")
						{    $e++;
							for($j=0;$j<$quantity;$j++)
							{
								if($e==1 && $j==0){
									$matShipppingPrice+=$flatRateShippingDet[$plaqueFrameShipping];
								}
								else
								$matShipppingPrice+=$flatRateShippingDet[$additionalplaqueFrameShipping];
							}	
						}
						if($val->type=='frame' && $frameType ==	"woodFrame")
						{    $f++;																					
							for($j=0;$j<$quantity;$j++)
							{
								if($f==1 && $j==0){
									$matShipppingPrice+=$flatRateShippingDet[$woodFrameShipping];
								}
								else
								$matShipppingPrice+=$flatRateShippingDet[$additionalwoodFrameShipping];
							}															
						}

					
					if($val->type=='frame' && $frameType ==	"lagrgeWoodFrame")
						{   $g++;										
							for($j=0;$j<$quantity;$j++)
							{							
								if($g==1 && $j==0){
									$matShipppingPrice+=$flatRateShippingDet[$lagrgeWoodFrameShipping];								
								}
								else
								{
									$matShipppingPrice+=$flatRateShippingDet[$additionallagrgeWoodFrameShipping];
								}
							}							
						
					}
					
					//end adding the frame price added on 16 nov 09
				}
						for($j=0;$j<$quantity;$j++)
						{
							if($giftCount==0 ){
							$giftShipppingPrice+=$flatRateShippingDet[$giftShippping];
							 }
							else{
							$giftShipppingPrice+=$flatRateShippingDet[$additionalGiftShippping];
							
							}
							$giftCount++;
						}	
						
					
						
			
				}
				else
				{
					if($ShppingType=='domestic'){
					
					$custom_sprice			=	$CARTARRAY['records'][$i]->domestic_sprice;
					$custom_sprice_addtl	=	$CARTARRAY['records'][$i]->domestic_sprice_addtl;	
					
					}
					else
					{
						$custom_sprice			=	$CARTARRAY['records'][$i]->inter_sprice;
						$custom_sprice_addtl	=	$CARTARRAY['records'][$i]->inter_sprice_addtl;	
					}
					
				
					
					$quantity=$CARTARRAY['records'][$i]->quantity;
					 
					for($m=0;$m<$quantity;$m++)
						{
							if($customCount==0 ){
							$custom_sprice_total+=$custom_sprice;
							 }
							else{
							$custom_sprice_total+=$custom_sprice_addtl;
							
							}
							$customCount++;
						}	
					
				}		
					
				}/// cart array

				$tortalPrice+=$giftShipppingPrice+$matShipppingPrice + $custom_sprice_total ;					
				$str='<div ><b>Flat Rate Shipping : &nbsp;&nbsp;</b>'.$currencydet["symbol"].number_format($tortalPrice,2).'<input type="hidden" name="flat_rate" value="'.$tortalPrice.'" /> </div>';	
				if ($ShppingType!='domestic')
				$str.='<table align="center" width="600px" border="0" class="borderBlue"><tr><td style="text-align:justify">'.$flatRateShippingDet['intr_message_two'].'</td></tr></table>';	
			break;
			case "ship_method":
				$str='<div align="center">
			         <span  align="center" style="width:80%;float:left "> Shipping Method: *
					  <select name="shipping_method" id="shipping_method" onchange="return loadShippingServices(this.value);" 		                       style="width:250px; ">
                     <option value="">-- Select Shipping Method --</option>';
					 foreach ($shippingMethods as $key=>$val)
					 {
					 	$str.=' <option value="'.$val['dbvalue'].'">'.$val['label'].'</option>';
					 }
			    $str.='</select></span><span id="loadingservice" style="display:none;float:left">
					<img  src="'.$global["tpl_url"].'/images/ajax-loader.gif" border="0"></span></div>';	
			break;
			case "not_set":
				$str='<span style="color:#FF0000" id="shipping_div_error"><b>Shipping information not present.</b></span>';
				$mail_header = array();
				$mail_header['from'] 	= 	$framework->config['admin_email'];
				$mail_header["to"]   	= $userDet["email"];
				$dynamic_vars = array();
				$dynamic_vars["FIRST_NAME"] = $userDet["first_name"];
				$dynamic_vars["LAST_NAME"]  = $userDet["last_name"];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				$dynamic_vars["STORE_NAME"]  = $storeDet["name"];
				$email->send("shipping_information",$mail_header,$dynamic_vars);	
			break;
			case "inter":
				$str='<table align="center" width="600px" border="0" class="borderBlue"><tr><td style="text-align:justify">'.$flatRateShippingDet['intr_message_one'].'</td></tr></table>';	
			break;
			case "inter_not":
				$str='<table align="center" width="600px" border="0" class="borderBlue"><tr><td style="text-align:justify">'.$flatRateShippingDet['intr_message_one'].'</td></tr></table>'."|1";	
			break;
		}
		echo $str;
		exit;
		break;
		
 	
 }


?>