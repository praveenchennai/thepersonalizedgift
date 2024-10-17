<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
set_time_limit(0);
$objAccessory	=	new Accessory();
$objCategory	=	new Category();
if($_REQUEST['grpid'])
	$grpid=$_REQUEST['grpid'];
if($_REQUEST['from'])
	$from=$_REQUEST['from'];	
if($_REQUEST['product_id'])
	$product_id=$_REQUEST['product_id'];	
if($from=="accessory_group")	
	{
	displayAccoryGroup($grpid,$objCategory,$objAccessory);
	}
else if($from=="display_all")	
	{
	$rSet=$objCategory->getAllCategory_is_in_ui_normally();
	foreach ($rSet as $category)
		{
		if($objCategory->CheckCategoryInGroup($category['category_id'])==false)
			{
			echo <<<AAA
			document.getElementById("cat_{$category['category_id']}").style.display='inline';
AAA;
			}
		}
}//if($from=="display_all")	
else if($from=="Show_accessory")//
	{
	$rSet=$objCategory->getAllCategory_is_in_ui_normally();
	foreach ($rSet as $category)
		{
		echo <<<AAA
		document.getElementById("cat_{$category['category_id']}").style.display='none';
AAA;
		}
	$rSet=$objAccessory->getProductAccessoryCategory($product_id);
	if(count($rSet)>0)
		{
		foreach ($rSet as $category)
			{
			if($objCategory->CheckCategoryInGroup($category['category_id'])==false)
			{
			echo <<<AAA
			document.getElementById("cat_{$category['category_id']}").style.display='inline';
AAA;
			}
			}
		}
		$rSet=$objAccessory->getProductAccessoryIDCategory($product_id);
		if(count($rSet)>0)
		{
		foreach ($rSet as $accessory)
			{
			$accessory_id=$accessory['accessory_id'];
			echo <<<BBBB
				document.getElementById("accessory_{$accessory_id}").checked=true;
BBBB;
			}
		}
}//else if($from=="Show_accessory")
else if($from=="remove_all")	
	{
	$_SESSION['sess_grp_array']="";
	$rSet=$objCategory->getAllCategory_is_in_ui_normally();
	foreach ($rSet as $category)
		{
		echo <<<AAA
		document.getElementById("cat_{$category['category_id']}").style.display='none';
AAA;
		}
	echo 'document.getElementById("group_id").selectedIndex=0;';
	echo "document.getElementById('group_message').innerHTML = 'All Accessory Group Removed to the List';";
	echo "document.getElementById('group_message').style.display='inline';";
	}//if($from=="remove_all")	
else if($from=="Append")
	{
	//echo "append";
	//$_SESSION['sess_grp_array']='';
	//exit;
	$status=false;
	$i=0;
	//print_r($_SESSION['sess_grp_array']);
	
	if($_SESSION['sess_grp_array'])
		{
		$grp_array = $_SESSION['sess_grp_array'];
		while($grp_array[$i])
			{
			if($grp_array[$i]==$_REQUEST['grpid'])
				{
				$status=true;
				}
			$i++;
			}
		}
	if($status==false && $_REQUEST['grpid'])
		{
		$grp_array[$i]=$_REQUEST['grpid'];
		$_SESSION['sess_grp_array']=$grp_array;
		displayAccoryGroup($grpid,$objCategory,$objAccessory);
		
		echo 'document.getElementById("group_id").selectedIndex=0;';
		echo "document.getElementById('group_message').innerHTML = 'Accessory Group Appended to the List';";
		echo "document.getElementById('group_message').style.display='inline';";
		}
	}	//else if($from=="Append")
else if($from=="Remove")
	{
	$status=false;
	$i=0;
	if($_SESSION['sess_grp_array'])
		{
		$grp_array = $_SESSION['sess_grp_array'];
		while($grp_array[$i])
			{
			if($grp_array[$i]==$_REQUEST['grpid'])
				{
				$status=true;
				array_pop($grp_array);
				}
			$i++;
			}
		}
		$_SESSION['sess_grp_array']=$grp_array;
		displayAccoryGroup($grpid,$objCategory,$objAccessory);
		echo 'document.getElementById("group_id").selectedIndex=0;';
		echo "document.getElementById('group_message').innerHTML = 'Accessory Group Removed to the List';";
		echo "document.getElementById('group_message').style.display='inline';";
	}	//else if($from=="Remove")
function displayAccoryGroup($grpid,&$objCategory,&$objAccessory)
		{
		$rSet=$objCategory->getAllCategory_is_in_ui_normally();
		
		foreach ($rSet as $category)
		{
		echo <<<AAA
		document.getElementById("cat_{$category['category_id']}").style.display='none';
AAA;
		}
		$i=0;
		if($_SESSION['sess_grp_array'])
			{
			$grp_array = $_SESSION['sess_grp_array'];
			$i=0;
			
			while($grp_array[$i])
				{
				$rowsess=$objAccessory->GetAccessoryGroupItems($grp_array[$i]);
				//print_r($rowsess);
				//exit;
				if(count($rowsess)>0)
					{
					foreach ($rowsess as $ro)
						{
						if($objCategory->CheckCategoryInGroup($ro['category_id'])==false)
						{
						echo <<<BBB
								document.getElementById("cat_{$ro['category_id']}").style.display='inline';
BBB;
	
						}
						}
					}
				$i++;
				}
			}
	//displaying any category of the selected item.	
	$rSet=$objAccessory->GetAccessoryGroupItems($grpid);
	if(count($rSet)>0)
		{
		foreach ($rSet as $row)
			{
				if($objCategory->CheckCategoryInGroup($row['category_id'])==false)
				{
				echo <<<AAA
				document.getElementById("cat_{$row['category_id']}").style.display='inline';
AAA;
				}
			}
		}
		echo "document.getElementById('group_message').style.display='none';";
		}
function removeAllSelection()
	{
	echo <<<AAA
			for (var i=0;i<document.admFrm.elements.length;i++)
			{
			var e = document.admFrm.elements[i];
			e.checked=false;
			}
AAA;
		}
?>