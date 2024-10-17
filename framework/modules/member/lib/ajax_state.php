<?php 
/**
 * Calulate the total amount using ajaxt techonology in product details page
 *
 * @author Nirmal
 * @package defaultPackage
 * 	* Modified : 11/Apr/2008	 By Vipin
  	* listing state using country name 
 */
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objUser 	= 	new User();
$states 	= 	new States();
$country_id	=	$_REQUEST['country_id'];
$name		=	$_REQUEST['state_name'];
$opt_name	=	$_REQUEST['opt_name'];
//$classname	=	"input";
if($_REQUEST["classname"]){
	$classname	=	$_REQUEST["classname"];
}
if($_REQUEST["classname1"]){
	$classname1	=	$_REQUEST["classname1"];
}
if($_REQUEST["classname5"]){
	$classname5=	$_REQUEST["classname5"];
}

if ($_REQUEST['width'])
{
	$style_val = "style='width:".$_REQUEST['width']."px'" ;
}
/*echo "country_id: $country_id<br>";
echo "name: $name<br>";
echo "opt_name: $opt_name<br>";*/// style="width:135px"
if (is_numeric ($country_id)){
$rs_country			=	$objUser->getCountryName($country_id);
}else{ 
$country_id_arr			=	$objUser->getCountryId($country_id);
$country_id			= $country_id_arr['country_id'];
$rs_country			=	$objUser->getCountryName($country_id);
}
$rs			=	$states->GetAllStates($country_id);
if(count($rs)>0)
	{
	##### this condition is added by Jipson Thomas on 10 April 2008 to apply a class to the state.
		if($classname1){
			$str=' <select id="'.$opt_name.'" name="'.$opt_name.'" class="'.$classname1.'" ' . $style_val .'>
		<option value="">--Please Select--</option>';
		}elseif($classname){
			$str=' <select id="'.$opt_name.'" name="'.$opt_name.'" class="'.$classname.'" ' . $style_val .'>
		<option value="">--Please Select--</option>';
		}
		elseif($classname5){
			$str=' <select id="'.$opt_name.'" name="'.$opt_name.'" class="'.$classname5.'" ' . $style_val .' onfocus="javascript:setDropdownDefault(\'state_left\',\'state_error\',\'form_wrap\');">
		<option value="">--Please Select--</option>';
		}
		
		else{
			$str=' <select id="'.$opt_name.'" name="'.$opt_name.'" class="'.$classname.'"  style="width:127px">
		<option value="">--Please Select--</option>';
		}
		
	
	foreach ($rs as $row)
		{
		$str	.=	'<option value="'.$row['name'].'" ';
		if($row['name']==$name)
			$str.=' SELECTED';
		$str	.=	'>'.$row['name'].'</option>';
		}
	$str	.=	'</select>';
	
	}
	
else
	{
		##### this condition is added by Jipson Thomas on 10 April 2008 to apply a class to the state.
		if($classname){
			$str	= 	'<input name="'.$opt_name.'" type="text" class="'.$classname.'" id="'.$opt_name.'"'.$style_val;
	
			if($name)
				$str	.=	' value="'.$name.'"'; 
			$str	.="/>";
		}
		else if($classname5){
			$str	= 	'<input name="'.$opt_name.'" type="text" class="'.$classname5.'" id="'.$opt_name.'"'.$style_val;
	
			if($name)
				$str	.=	' value="'.$name.'"  '; 
			$str	.='  onfocus="javascript:setDropdownDefault(\'state_left\',\'state_error\',\'form_wrap\'); showTips(\'state_holder\',\'state_hint\',\'state_error\',\'state_left\');" onBlur="javascript:hideTips(\'state_holder\'); checkDefault(this.value,\'state\',\'\',\'form_field_wrap_input\',\'form_field_wrap_input\');" ';
				
			$str	.="/>";
		}
		
		else{
			$str	= 	'<input name="'.$opt_name.'" type="text" class="'.$classname.'" id="'.$opt_name.'"'.$style_val;
	
			if($name)
				$str	.=	' value="'.$name.'"'; 
			$str	.=	'style="width:127px" />';
		}
	
	}
echo $str;
//echo "document.getElementById('total_price').innerHTML = 'Total : <strong>$".number_format($objProduct->calulate_price($_REQUEST)*$_REQUEST['qty'], 2)."</strong>';";
exit;
?>