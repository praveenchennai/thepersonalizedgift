<?php 
/**
 * Calulate the total amount using ajaxt techonology in product details page
 *
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");

$objProduct	=	new Product();
$objAccessory	=	new Accessory();
$objCombination	=	new Combination();

//print_r($_REQUEST);
//exit;

$content_size = $objCombination->GetAccessoryLists($product_id,$store_name);




$allCategory	=	array();
$allAccesory	=	array();
$allAccesoryId	=	array();
$allAccesoryExcId	=	array();
$exclGroup	=	array();

	foreach ( $content_size as $key=>$value) {
	
	
		if ( $value['id'] == $_REQUEST['group_id'] ) {
			$currArray	= $value;
			$currCatId  =  $currArray['categories'][0]['category_id'];
			$isMonogram	=  $currArray['categories'][0]['is_monogram'];	
			$currCatName	=  $currArray['categories'][0]['category_name'];
			$currGroupName	=  $currArray['group_name'];
			
		}
		
		foreach ( $value as $key1=>$value1 )	{
			if ( is_array ($value1) )	{
				foreach ( $value1 as $key2=>$value2 )	{
					$allCategory[] = $value2['category_id'];
				}
			}
		}
		
	}

	//print_r ($allCategory);
   
	//print_r ( $_REQUEST['access'][$currCatId] );    # SELECTED VALUE

	
	
	
	
	
	
	$allAccesory	=	$currArray['categories'][0]['accessory'];
	
	
	
	
	$controlType	=	'';
	foreach ( $allAccesory as $key=>$accessoryIds )	{
		$allAccesoryId[$key]=	$accessoryIds['id'];
		if ( trim ($controlType) == '' )	{
			if ( $accessoryIds['type'] )
			$controlType	=	$accessoryIds['type'];
			else
			$controlType	=	'Dropdown';
		}
	}
	


	$array	= $_REQUEST;
	$product_id	=	$array['product_id'];
	$arr	=	$objAccessory->GetAllAccessorySettingsList($product_id);
	//$arr=$objCombination->GetAccessoryLists($product_id,$storename);
	
	
	

	
	foreach ( $arr as $combination )	{
		foreach ( $combination['accessory'] as $combvalues )	{
			$exclGroup[] = implode (",", $combvalues );
		}
	} 	 




	$val11	=	array();
  	foreach ($array['access'] as $key=>$value)	{
		if ( $key != $currCatId )
		$val11[]	=	$value[0];
	}



/*
print "<pre>";
print_r ( $exclGroup);			# Exclude Combination   Array ( [0] => 222259,222306 [1] => 222265,222366 )
print "</pre>";





print "<pre>";
print_r ( $allAccesoryId);			# All Accessories of Current Group
print "</pre>";
*/

	$valueTest	= array();
	foreach ( $val11 as $key1=>$value111 ) {
		if (  $value111>0 ) {
			$valueTest[$key1] = $value111;
		}
	}

	$val11	=	$valueTest;


/*
print "<pre>";
print "Val111"; print_r ( $val11 );				# Selected Accessories  222259,222265
print "</pre>";
*/

	$filterAccId = array();
	foreach ( $allAccesoryId as $key=>$value )	{	# ALL ACCESSORY VALUE                  $filterAccId
		foreach ( $val11 as $key1=>$value1 )	{ # SELECTED ACCESSORY VALUES
			foreach ( $exclGroup as $key2=>$value2 )	{
				$AllCombi	=    array();
				$AllCombi[]	=	 $value;
				$AllCombi[]	=	 $value1;
				sort( $AllCombi );
				$ExcCombi	=	explode ( "," , $value2 );
				sort( $ExcCombi );
				
				
				
				if ( $AllCombi == $ExcCombi )	{
					$filterAccId[]	= $value;
				} else	{
					$result	=	array_intersect ( $ExcCombi,$val11 );
					/*
					print "<pre>";
						print "1111111111111" ; print_r ( $ExcCombi);			
					print "</pre>";
					print "<pre>";
						print "222222222222" ; print_r ( $AllCombi);			
					print "</pre>";
					print "<pre>";
						print "Ressssssssss" ; print_r ( $result);			
					print "</pre>";
					*/
					
					if ( count( $result ) )	{
						foreach ( $ExcCombi as $ExcValues)	{
							$filterAccId[]	= $ExcValues;
						}			
					}		
				}
				
				
				
			}
		}
	}
	
	
	$filterAccId	=	array_unique ($filterAccId);

/*
print "<pre>";
print_r ( $filterAccId);			# All Accessories of Current Group
print "</pre>";
*/
	
	$InsertValue	=	array ();
	foreach ( $allAccesoryId as $key=>$value )	{
		if ( !in_array ( $value , $filterAccId ) ) {
		$InsertValue [$key] = $value;
		}
	}







/*
print "<pre>";
print_r ( $filterAccId);			# All Accessories of Current Group
print "</pre>";

print "<pre>";
print_r ( $InsertValue);			# All Accessories of Current Group  
print "</pre>";

print "<pre>";
print_r ( $allAccesory);			# All Accessories of Current Group
print "</pre>";

*/

	if ( $isMonogram != "Y" )
	$currCatName	=	$currGroupName;


    

	 if ( $controlType == 'Dropdown' )	{
	
		$str	=	'<select id="access_'.$currCatId.'" name="access['.$currCatId.']" onChange="JavaScript:serverCall('.$product_id.','."'Dropdown'".',this);">';
		$str	.=	'<option value="0">--- '. $currCatName .'---</option>';

		if ( count ($allAccesory) > 0 )	{
			foreach ($allAccesory as $row)	{
				if ( !in_array ( $row['id'] , $filterAccId) )	{
					$str	.=	'<option value="'.$row['id'].'" ';
					if( in_array ( $row['id'] , $_REQUEST['access'][$currCatId] ) )
						$str.=' SELECTED';
					$str	.=	'>'.$row['comboname'].'</option>';
				}	
			}
		 }
		 $str	.=	'</select>';	
		 $str	.= 	'|'.$_REQUEST['dyn_divid'];
		 
	} elseif ( $controlType == 'Color' ) {
		
		
		$str	=	'<table  height="50" width="34%"  border="0" align="left" cellpadding="0" cellspacing="0">';
		// ********************  Finding Color ********************* //
		$col	=	1;
		foreach ($allAccesory as $aces)	{
			if ( !in_array ( $aces['id'] , $filterAccId) )	{
				if ( $aces['color1'] == "" && $aces['color2'] == "" && $aces['color3'] == "" )	{
					$tdcolor1	=	"FFFFFF";
					$tdcolor2	=	"FFFFFF";
					$tdcolor3	=	"FFFFFF";
				} elseif ( $aces['color1'] != "" && $aces['color2'] != "" && $aces['color3'] != "" ) {
					$tdcolor1	=	$aces['color1'];
					$tdcolor2	=	$aces['color2'];
					$tdcolor3	=	$aces['color3'];
				} elseif ( $aces['color1'] == "" && $aces['color2'] == "" ) {
					$tdcolor1	=	$aces['color3'];
					$tdcolor2	=	$aces['color3'];
					$tdcolor3	=	$aces['color3'];
				} elseif ( $aces['color2'] == "" && $aces['color3'] == "" ) {
					$tdcolor1	=	$aces['color1'];
					$tdcolor2	=	$aces['color1'];
					$tdcolor3	=	$aces['color1'];
				} elseif ( $aces['color1'] == "" && $aces['color3'] == "" ) {
					$tdcolor1	=	$aces['color2'];
					$tdcolor2	=	$aces['color2'];
					$tdcolor3	=	$aces['color2'];
				} elseif ( $aces['color1'] == "" ) {
					$tdcolor1	=	$aces['color2'];
					$tdcolor2	=	$aces['color2'];
					$tdcolor3	=	$aces['color3'];
				} elseif ( $aces['color2'] == "" ) {
					$tdcolor1	=	$aces['color1'];
					$tdcolor2	=	$aces['color1'];
					$tdcolor3	=	$aces['color3'];
				} elseif ( $aces['color3'] == "" ) {
					$tdcolor1	=	$aces['color1'];
					$tdcolor2	=	$aces['color1'];
					$tdcolor3	=	$aces['color2'];
				}
				
				
				if ( $aces['comboname'] )	{
					$comboName	=	substr($aces['comboname'] , 0 ,15 ) . '...';
					$comboName	=   wordwrap( $comboName , 10 ,"<br> \n");
				} else {
					$comboName	=	substr($aces['name'] , 0 ,15 ). '...';
					$comboName	=   wordwrap( $comboName , 10 ,"<br> \n");
				}
				
				$accesCat	=	"'".'access_'.$currCatId. "'";
				
				if( count ( $_REQUEST['access'][$currCatId] ) > 0 )	{
				$str	.=  '<input type="hidden" name="access['.$currCatId.']" id="access_'.$currCatId.'" value="'.$_REQUEST['access'][$currCatId][0].'">';
				}else{
				$str	.=  '<input type="hidden" name="access['.$currCatId.']" id="access_'.$currCatId.'" value="">';
				}
				
				$str	.=  '<td width="166" align="left" valign="top">';
					$str	.=  '<table width="70" height="50" border="0" align="left" cellpadding="0" cellspacing="0" onClick="javascript: setTableColor('.$accesCat.','.$product_id.','.$aces["id"].')" id="Table_01" style="cursor:pointer">';
						$str	.=  '<tr valign="bottom">';
						$str	.=  '<td height="30"><div align="center"><font size="-4">' .$comboName. '</font></div></td>';
						$str	.=  '</tr>';
						$str	.=  '<tr  valign="bottom">';
						$str	.=  '<td height="3"></td>';
						$str	.=  '</tr>';
						$str	.=  '<tr>';
							$str	.=  '<td>';
								$str	.=  '<table width="50" height="25"  border="0" align="center" cellpadding="0" cellspacing="0">';
									$str	.=  '<tr>';
									$str	.=  '<td height="8" bgcolor="#'.$tdcolor1.'"><img src="'.$global["tpl_url"].'/images/dot.gif" width="8" height="8" alt=""></td>';
									$str	.=  '</tr>';
									$str	.=  '<tr>';
									$str	.=  '<td height="8" bgcolor="#'.$tdcolor2.'"><img src="'.$global["tpl_url"].'/images/dot.gif" width="8" height="8" alt=""></td>';
									$str	.=  '</tr>';
									$str	.=  '<tr>';
									$str	.=  '<td height="8" bgcolor="#'.$tdcolor3.'"><img src="'.$global["tpl_url"].'/images/dot.gif" width="8" height="8" alt=""></td>';
									$str	.=  '</tr>';	
								$str	.=  '</table>';	
							$str	.=  '</td>';
						$str	.=  '</tr>';	
							
						$str	.=  '<tr valign="top">';
						$str	.=  '<td height="3"></td>';	
						$str	.=  '</tr>';
						
						$str	.=  '<tr valign="top">';
						$str	.=  '<td><div align="center">Price:'.$aces["adjust_price"].'</div></td>';
						$str	.=  '</tr>';
						
					$str	.=	'</table>';
				$str	.=	'</td>';
				
				$str	.=	'<td width="19" valign="top">&nbsp;</td>';
				
				if ( $col%6 == 0 )	{
				$str	.=	'</tr><tr>';
				}
	
			$col++;
		   }	
		}	

		$str	.=  '</tr>';
		$str	.=  '</table>';
		$str	.= 	'|'.$_REQUEST['dyn_divid'];
		
		
	}  elseif ( $controlType == 'Image' ) {
	
		$accesCat	=	"'".'access_'.$currCatId. "'";
	

		if( count ( $_REQUEST['access'][$currCatId] ) > 0 )	{
		$str	=  '<input type="hidden" name="access['.$currCatId.']" id="access_'.$currCatId.'" value="'.$_REQUEST['access'][$currCatId][0].'">';
		}else{
		$str	=  '<input type="hidden" name="access['.$currCatId.']" id="access_'.$currCatId.'" value="">';
		}
		
		$str	.=	'<table width="20%"  border="0" cellspacing="0" cellpadding="0">';
		$str	.=	'<tr>';
		$col	=	1;

		foreach ($allAccesory as $aces)	{
			if ( !in_array ( $aces['id'] , $filterAccId) )	{
				if ( $aces['image_extension'] != '' ) {
					$imgPath	= $global["modbase_url"] . "/product/images/accessory/thumb/" . $aces['id']. '.' .$aces['image_extension'];
				} else {
					$imgPath	= $global["tpl_url"] . "/images/noimage.gif";
				}
				
				
				$str	.=	'<td>';
					$str	.=	'<table style="cursor:pointer" width="75" height="20"  border="0" cellpadding="0" cellspacing="0" onClick="javascript: setImageId('.$accesCat.','.$product_id.','.$aces["id"].')">';
					$str	.=	'<tr>';
					$str	.=	'<td align="center"><div align="center" class="bodytext">';
						$str	.=	'<table width="50"  border="0" cellspacing="0" cellpadding="0">';
						$str	.=	'<tr>';
						$str	.=	'<td align="center" bgcolor="#FFFFFF" width="50"><div align="center" class="bodytext"><img  src="'. $imgPath .'"  width="50" height="25" border="0" ></div></td>';
						$str	.=	'</tr>';
					$str	.=	'</table></div></td>';
					$str	.=	'</tr>';
					$str	.=	'</table>';
				$str	.=	'</td>';
				
				$str	.=	'<td width="3%">&nbsp;</td>';
				
				if ($col%7 == 0)	{
				$str	.=	'</tr><tr><td>&nbsp;</td></tr><tr>';
				}
				$col++;	
			}
		}

		$str	.=	'</tr>';
		$str	.=	'</table>';
		$str	.= 	'|'.$_REQUEST['dyn_divid'];
		
		
	}  elseif ( $controlType == 'ListNo' ) {

		$str	=	'<select id="access_'.$currCatId.'" name="access['.$currCatId.']" multiple size="5" onChange="JavaScript:serverCall('.$product_id.','."'select-multiple'".',this);">';
		$str	.=	'<option value="0">--- '. $currCatName .'---</option>';

		if ( count ($allAccesory) > 0 )	{
			foreach ($allAccesory as $row)	{
				if ( !in_array ( $row['id'] , $filterAccId) )	{
					$str	.=	'<option value="'.$row['id'].'" ';
					
					if ( isset ($_REQUEST['access'][$currCatId]) ) {
					if( in_array ( $row['id'] , $_REQUEST['access'][$currCatId] ) )
						$str.=' SELECTED';
					}	
						
					$str	.=	'>'.$row['comboname'].'</option>';
				}	
			}
		 }
		 $str	.=	'</select>';	
		 $str	.= 	'|'.$_REQUEST['dyn_divid'];
		 
	}	elseif ( $controlType == 'Radio' ) {
		
		if ( count ($allAccesory) > 0 )	{
		    $str	= '';
			foreach ($allAccesory as $row)	{
				if ( !in_array ( $row['id'] , $filterAccId) )	{
				
					if( (isset ($_REQUEST['access'][$currCatId])) && (in_array ( $row['id'] , $_REQUEST['access'][$currCatId] )) ) {
						$str	.=	'<span><input align="middle" type="radio" id="access_'.$currCatId.'" name="access['.$currCatId.']" value="'.$row['id'].'" checked onClick="JavaScript:serverCall('.$product_id.','."''".',this);"></span>&nbsp;&nbsp;<span style="width:200px;">'.$row['comboname'].'</span><br>';
					}else {
						$str	.=	'<span><input align="middle" type="radio" id="access_'.$currCatId.'" name="access['.$currCatId.']" value="'.$row['id'].'" onClick="JavaScript:serverCall('.$product_id.','."''".',this);"></span>&nbsp;&nbsp;<span style="width:200px;">'.$row['comboname'].'</span><br>';
					}
				}	
			}
		}	
		$str	.= 	'|'.$_REQUEST['dyn_divid'];
	}
	
	
	
	echo $str;
	exit;
?>