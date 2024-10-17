<?
function GetAccessoryType()
{
	global $framework;
	if($framework->config['dynamic_accessory_type']=='Y')
		$array = array("List","Turn Around","Clipart","Checkbox","Dropdown");
	else
		$array = array("List","Radio","Image","Checkbox","Dropdown","Color");
	#$array = array("Radio","Image","Dropdown","Color");
	return $array;
}

?>