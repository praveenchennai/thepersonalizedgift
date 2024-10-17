<?
class Combination extends FrameWork
{

	function Combination()
	{
		$this->FrameWork();
	}
	function setArrData($szArrData)
	{
		$this->arrData 	= 	$szArrData;
	}
	function getArrData()
	{
		return $this->arrData;
	}
	function setErr($szError)
	{
		$this->err 		.= 	"$szError";
	}
	function getErr()
	{
		return $this->err;
	}
	
	function GetDetails($Combination_id=0)
		{
		$select					=	"select name,category_name from product_availabe_accessory as paa left join (product_accessories pa ) on (paa.accessory_id=pa.id) left join (master_category mc) on (mc.category_id=paa.category_id) where paa.id=".$combination_id."";
		$rs						=	 $this->db->get_row($select, ARRAY_A);
		return $rs['name']."[".$rs['category_name']."]";
		}
		
///user side function

function get_accessory_store($store_id)
{
	 $qry	=	"select * from product_accessory_store where store_id='".$store_id."'";
	
	$rs		=	 $this->db->get_row($qry, ARRAY_A);
	//$store_id	=	$rs['store_id'];
	$count	=	count($rs);
	return $count;
}
function get_storeid($storename)
{
	if($storename)	
		{
			$qry	=	"select s.id from store s where s.name='".$storename."'";
			$rs		=	 $this->db->get_row($qry, ARRAY_A);
			$store_id	=	$rs['id'];
		}
		else
		{	$store_id=0; 	}
		return $store_id;
}


function GetAccessoryListsByGroup($product_id,$group_id,$store_name='')
{
$arr	=	array();
$categories=array();
$arr=$groups	=	$this->getAccessoryGroups($product_id,$store_name);
for($i=0;$i<count($groups);$i++)
	{
	
	#------ for stores[display accsry if parameter ='Y' else check in the product_accessory_store] ------------
	$accessory_flag		=	"Y";
	$product_store_id	=	$this->get_storeid($store_name);
	$group_parameter	=	$groups[$i]['parameter']; 
	if($group_parameter!='Y')
	{
		if($product_store_id!='0')
		{
			
			$accessory_count	=	$this->get_accessory_store($product_store_id);
			if($accessory_count>0)
			{
				$accessory_flag	=	"Y";
			}
			else
			{
				$accessory_flag	=	"N";
				$groupname	=	$groups[$i]['group_name'];
				unset($arr[$i]);
				unset($groups[$i]);
			}
		}
	}
	#--- end for stores ---------------
	
	if($accessory_flag=="Y") {
	
	$arr[$i]['categories']=$categories[$i]		=	$this->getCategory($product_id,$groups[$i]['id'],$store_name);
	for($j=0;$j<count($categories[$i]);$j++)
		{
		//echo 'category_id '.$categories[$i][$j]['category_id'];
		$arr[$i]['categories'][$j]['accessory']=$accessories[$j]	=	$this->getAccessories($product_id,$categories[$i][$j]['category_id'],$store_name);
		
		if($accessories[$j])
					{
					$k=0;
					foreach ($accessories[$j] as $Loopaccessory)
						{
						$TmpAccessory			=	$Loopaccessory;
							$AccessId			=	$Loopaccessory['id'];
							$AdjustPrice		=	$this->getAccessoryAdjustPriceFromProductAvailableAccessory($product_id, $categories[$i][$j]['category_id'], $AccessId);
							
							if(trim($AdjustPrice))
								$TmpAccessory['adjust_price']	=	$AdjustPrice;
							
							$arr[$i]['categories'][$j]['accessory'][$k]	=	$TmpAccessory;
						//$item['N'][$i]['accessory'][$j]		=	$accessory;
						
						$k++;
						}//foreach ($accessories as $accessory)
					}

		}
	}
	}
//echo "<pre>";	
//print_r($arr);
return $arr;
//exit;
}





function GetAccessoryLists($product_id,$store_name='')
{
$arr	=	array();
$categories=array();
$arr=$groups	=	$this->getAccessoryGroups($product_id,$store_name);
for($i=0;$i<count($groups);$i++)
	{
	
	#------ for stores[display accsry if parameter ='Y' else check in the product_accessory_store] ------------
	$accessory_flag		=	"Y";
	$product_store_id	=	$this->get_storeid($store_name);
	$group_parameter	=	$groups[$i]['parameter']; 
	if($group_parameter!='Y')
	{
		if($product_store_id!='0')
		{
			
			$accessory_count	=	$this->get_accessory_store($product_store_id);
			if($accessory_count>0)
			{
				$accessory_flag	=	"Y";
			}
			else
			{
				$accessory_flag	=	"N";
				$groupname	=	$groups[$i]['group_name'];
				unset($arr[$i]);
				unset($groups[$i]);
			}
		}
	}
	#--- end for stores ---------------
	
	if($accessory_flag=="Y") {
	
	$arr[$i]['categories']=$categories[$i]		=	$this->getCategory($product_id,$groups[$i]['id'],$store_name);
	for($j=0;$j<count($categories[$i]);$j++)
		{
		//echo 'category_id '.$categories[$i][$j]['category_id'];
		$arr[$i]['categories'][$j]['accessory']=$accessories[$j]	=	$this->getAccessories($product_id,$categories[$i][$j]['category_id'],$store_name);
		
		if($accessories[$j])
					{
					$k=0;
					foreach ($accessories[$j] as $Loopaccessory)
						{
						$TmpAccessory			=	$Loopaccessory;
							$AccessId			=	$Loopaccessory['id'];
							$AdjustPrice		=	$this->getAccessoryAdjustPriceFromProductAvailableAccessory($product_id, $categories[$i][$j]['category_id'], $AccessId);
							
							if(trim($AdjustPrice))
								$TmpAccessory['adjust_price']	=	$AdjustPrice;
							
							$arr[$i]['categories'][$j]['accessory'][$k]	=	$TmpAccessory;
						//$item['N'][$i]['accessory'][$j]		=	$accessory;
						
						$k++;
						}//foreach ($accessories as $accessory)
					}

		}
	}
	}
//echo "<pre>";	
//print_r($arr);
return $arr;
//exit;
}
//function to display the accessory combinations
function GetAccessoryLists1($product_id,$store_name='')
	{
	$item	=	array();
	$i		=	0;
	//get categories of non monogram
	//echo $product_id;
	$categories['N']		=	$this->getCategoryListforAccessory($product_id,'N',$store_name);
	$categories['Y']		=	$this->getCategoryListforAccessory($product_id,'Y',$store_name);
	//print_r($categories);exit;
	if($categories['N']==false && $categories['Y']==false)
		{
		return false;
		}
	else
		{
		if(count($categories['N'])>0)
			{
			foreach ($categories['N'] as $category)
				{
				//print_r($category);
				//echo '<br>';
				$accessories=array();
				$item['N'][$i]['category']	=	$this->GetCategoryGroupName($category['category_id']);
				$item['N'][$i]['category_desc']	=	$this->GetcategoryDescription($category['category_id']);
				$status_customization_text_required=false;
				$accessories	=	$this->getAccessories($product_id,$category['category_id'],$store_name);
				
				$j		=	0;
				if($accessories)
					{
					foreach ($accessories as $accessory)
						{
						$TmpAccessory		=	$accessory;
							$AccessId			=	$accessory['id'];
							$AdjustPrice		=	$this->getAccessoryAdjustPriceFromProductAvailableAccessory($product_id, $category['category_id'], $AccessId);
							if(trim($AdjustPrice))
								$TmpAccessory['adjust_price']	=	$AdjustPrice;
							
							$item['N'][$i]['accessory'][$j]	=	$TmpAccessory;
						//$item['N'][$i]['accessory'][$j]		=	$accessory;
						$j++;
						}//foreach ($accessories as $accessory)
					$i++;
					}
				}//foreach ($categories['N'] as $category)
			}
		$i		=	0;
		if(count($categories['Y'])>0)
			{
				$objCategory	=	new Category();
				$ROW_monogramheading = $this->GetParentmonogramCategory($categories['Y'][0]['parent_id']);
				if(!$ROW_monogramheading)
					$ROW_monogramheading	=	"Monogram Customization";
				$item['monogram_heading']	= 	$ROW_monogramheading;	
				$i=0;
				
				foreach ($categories['Y'] as $category)
				{
				/***/
				
				/*if($category['parent_id']>0)
					$parent	=	$objCategory->CategoryGet($category['parent_id']);
				if($category['is_monogram']=='Y' && $parent['is_monogram']=='Y')
					$category_id=$category['parent_id'];
				//echo $category_id;
				if($objCategory->CheckCategoryInGroup($category_id))
					{
					
					$group_id=$objCategory->GetCategoryInGroupID($category_id);
					$arr[$i]=array('group_id'=>$group_id,"category_id"=>$category_id);
					$i++;
					}*/
					//exit;
				/***/
				$accessories=array();
				$item['Y'][$i]['category']	=	$this->GetCategoryGroupName($category['category_id']);
				$item['Y'][$i]['category_desc']	=	$this->GetcategoryDescription($category['category_id']);
				$status_customization_text_required=false;
				$accessories	=	$this->getAccessories($product_id,$category['category_id'],$store_name);
				$j		=	0;
				
				if($accessories)
					{
						foreach ($accessories as $accessory)
						{
							
							$TmpAccessory		=	$accessory;
							$AccessId			=	$accessory['id'];
							$AdjustPrice		=	$this->getAccessoryAdjustPriceFromProductAvailableAccessory($product_id, $category['category_id'], $AccessId);
							if(trim($AdjustPrice))
								$TmpAccessory['adjust_price']	=	$AdjustPrice;
							
							$item['Y'][$i]['accessory'][$j]	=	$TmpAccessory;
							
						$j++;
						}//foreach ($accessories as $accessory)
					}
				$i++;
				}//foreach ($categories as $category)
			}
			
			//}//if(count($categories)>0)
		}//else
		return $item;
	//echo "<pre>";
	//print_r($item);
	//exit;
	
	//exit;
	}//function GetAccessoryLists($product_id=0,$option=0)
function GetCategoryGroupName($category_id)
	{
	$res 	= $this->db->get_row("SELECT * FROM master_category WHERE category_id='{$category_id}'", ARRAY_A);

	$qry	=	"select pg.* from product_accessory_group as pg,product_accessory_group_categories as pc where pc.group_id=pg.id and pc.category_id=$category_id ORDER BY pg.display_order";
	$rs		=	 $this->db->get_row($qry, ARRAY_A);
	
	if(count($rs)>0)
		$res['category_name']=$rs['group_name'];
	return $res;
	}
function GetParentmonogramCategory($category_id)
	{
	$qry	=	"select * from master_category where category_id=".$category_id ." and is_monogram='Y'" ;
	$res		=	 $this->db->get_row($qry, ARRAY_A);
	$qry	=	"select pg.* from product_accessory_group as pg,product_accessory_group_categories as pc where pc.group_id=pg.id and pc.category_id=$category_id ORDER BY pg.display_order";
	$rs		=	 $this->db->get_row($qry, ARRAY_A);
	if(count($rs)>0)
		$res['category_name']=$rs['group_name'];
	
	return $res['category_name'];
	}
function getAccessoryGroups($product_id=0,$store_name='')
	{
	if($product_id>0)
			{
			if($store_name)	
				{
				$qry	=	"select s.id from store s where s.name='".$store_name."'";
				$rs		=	 $this->db->get_row($qry, ARRAY_A);
				$store_id	=	$rs['id'];
				}
				
				
		if(empty($store_id)|| $store_id=='')
			$store_id	=	'0';
		$qry1		=	" 	SELECT DISTINCT pag . *
							FROM product_availabe_accessory paa
							LEFT JOIN (
							product_accessory_store ps
							) ON ( paa.id = ps.available_id ) , product_accessory_group pag
							WHERE";
		 $qry1	.=		" 		(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND 
								paa.product_id=".$product_id." and
								paa.group_id=pag.id 
								ORDER BY pag.display_order ";//print($qry1);
								
								
		/*SELECT DISTINCT pag . *
FROM product_availabe_accessory paa
LEFT JOIN (
product_accessory_store ps
) ON ( paa.id = ps.available_id ) , product_accessory_group pag
WHERE (
ps.available_id IS NULL
OR ps.available_id = ''
OR ps.available_id =0
OR ps.store_id =0
)
AND paa.product_id =427
AND paa.group_id = pag.id
ORDER BY pag.display_order*/
		//echo $qry1;
		//exit;
		$rs1		=	$this->db->get_results($qry1, ARRAY_A); 
		if(count($rs1)>0)
				return $rs1;
		}
		else
		return false;
	}
	
function getMandatoryCategory($product_id,$group_id,$store_name='')
	{
	if($store_name)	
		{
		$qry	=	"select s.id from store s where s.name='".$store_name."'";
		$rs		=	 $this->db->get_row($qry, ARRAY_A);
		$store_id	=	$rs['id'];
		}
		if(empty($store_id)|| $store_id=='')
			$store_id	=	'0';
	$qry1		=	" SELECT DISTINCT mc.* FROM ";
	$qry1	.=		"		master_category mc,
							product_availabe_accessory paa left join (product_accessory_store ps) on (paa.id=ps.available_id)
							
					WHERE ";
	$qry1	.=		" 		(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND 
							mc.active='Y' and
							mc.is_in_ui='Y' and
							mc.mandatory='Y' and
							paa.product_id=".$product_id." and
							paa.category_id=mc.category_id and
							paa.group_id	=	".$group_id.
							" ORDER BY mc.display_order asc ";
	//echo $qry1."<br>";
	$rs1		=	$this->db->get_results($qry1, ARRAY_A); 
		if(count($rs1)>0)
				return $rs1;
	}
	
function getCategory($product_id,$group_id,$store_name='')
	{
	if($store_name)	
		{
		$qry	=	"select s.id from store s where s.name='".$store_name."'";
		$rs		=	 $this->db->get_row($qry, ARRAY_A);
		$store_id	=	$rs['id'];
		}
		if(empty($store_id)|| $store_id=='')
			$store_id	=	'0';
	$qry1		=	" SELECT DISTINCT mc.* FROM ";
	$qry1	.=		"		master_category mc,
							product_availabe_accessory paa left join (product_accessory_store ps) on (paa.id=ps.available_id)
							
					WHERE ";
	$qry1	.=		" 		(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND 
							mc.active='Y' and
							mc.is_in_ui='Y' and
							paa.product_id=".$product_id." and
							paa.category_id=mc.category_id and
							paa.group_id	=	".$group_id.
							" ORDER BY mc.display_order asc ";
	//echo $qry1."<br>";
	$rs1		=	$this->db->get_results($qry1, ARRAY_A); 
		if(count($rs1)>0)
				return $rs1;
	}
function getCategoryListforAccessory($product_id=0,$is_monogram='N',$store_name='')
	{
	if($product_id>0)
			{
			if($store_name)	
		{
		$qry	=	"select s.id from store s where s.name='".$store_name."'";
		$rs		=	 $this->db->get_row($qry, ARRAY_A);
		$store_id	=	$rs['id'];
		}
		if(empty($store_id)|| $store_id=='')
			$store_id	=	'0';
	#--- code till 23-05-07 --------------
	if($is_monogram=="Y")
	{
	$qry1		=	" SELECT DISTINCT mc.* FROM ";
	$qry1	.=		"		master_category mc,
							product_availabe_accessory paa left join (product_accessory_store ps) on (paa.id=ps.available_id)
							
					WHERE ";
	$qry1	.=		" 		(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND
							mc.active='Y' and
							mc.is_in_ui='Y' and
							mc.is_monogram='".$is_monogram."' and
							paa.product_id=".$product_id." and
							paa.category_id=mc.category_id and
							
					ORDER BY mc.display_order";
					}
				else
				{
	#--- code till 23-05-07 --------------
		
		$qry1		=	" SELECT DISTINCT mc.* FROM ";
	$qry1	.=		"		master_category mc,
							product_availabe_accessory paa left join (product_accessory_store ps) on (paa.id=ps.available_id),
							product_accessory_group_categories pac,
							product_accessory_group pag
							
					WHERE ";
	$qry1	.=		" 		(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND
							mc.active='Y' and
							mc.is_in_ui='Y' and
							mc.is_monogram='".$is_monogram."' and
							paa.product_id=".$product_id." and
							paa.category_id=mc.category_id and
							pac.category_id=mc.category_id and
							pac.group_id=pag.id and
							
					ORDER BY pag.display_order ";
				}
		//echo $qry1;
		$rs1		=	$this->db->get_results($qry1, ARRAY_A); 
		if(count($rs1)>0)
				return $rs1;
		}
		else
		return false;
	}		
function getAccessories($product_id=0,$category_id=0,$store_name='')
{

	if($store_name)	
		{
		$qry	=	"select s.id from store s where s.name='".$store_name."'";
		$rs		=	 $this->db->get_row($qry, ARRAY_A);
		 $store_id	=	$rs['id'];
		}
		
		#-------- 19-07-07 removing the unselected accessory form store ------
		
		
		
		$res	=	mysql_query("select * from accessory_notin_store where store_id='$store_id'");
		$exclude_accsry	=	"'0'";
		while($row=	mysql_fetch_array($res))
		{
			 $acc_id	=	$row["assessory_id"];
			
			
			$exclude_accsry=$exclude_accsry. " , '".$acc_id."'";
		}
		#-------- 19-07-07 removing the unselected accessory form store ------
		
		
		
	if(empty($store_id)|| $store_id=='')
			$store_id	=	'0';
	if($product_id>0)
			{
			$qry1	=	"SELECT CONCAT(pa.display_name,'&nbsp;&nbsp;&nbsp;ADD $',ifnull(pa.adjust_price,'0')) as comboname,pa.*,concat(pa.id,'_',".$category_id.") as new_item_id FROM ";
			$qry1	.=		"	product_availabe_accessory paa left join (product_accessory_store ps) on (paa.id=ps.available_id),
								product_accessories pa
						WHERE ";
			 $qry1	.=		" 	(
								ps.available_id IS NULL
								OR ps.available_id = ''
								OR ps.available_id =0
								OR ps.store_id =$store_id
								) 
								AND
								paa.product_id=".$product_id." and
								paa.category_id=".$category_id." and 
								paa.accessory_id=pa.id and
								paa.accessory_id NOT IN($exclude_accsry) and
								pa.active='Y'
						ORDER BY pa.display_order ASC";
						//echo $qry1;
						//exit;
			/*$qry1	=	"SELECT CONCAT(pa.name,':ADD $',ifnull(pa.adjust_price,'0')) as comboname,pa.* FROM ";
			
			if($store_name)	
			$qry1	.=		"	store_category sc,
								store s,";
								
			$qry1	.=		"	master_category mc,
								product_availabe_accessory paa,
								product_accessories pa
						WHERE ";
						
			if($store_name)				
			$qry1	.=		" 	s.name='".$store_name."' and
								sc.store_id=s.id and
								sc.category_id=mc.category_id and ";
								
			$qry1	.=		" 	mc.active='Y' and
								mc.is_in_ui='Y' and
								paa.product_id=".$product_id." and
								paa.category_id=mc.category_id and
								mc.category_id=".$category_id." and
								
								paa.accessory_id=pa.id and
								pa.active='Y'
						ORDER BY pa.name";*/
			/*$qry1	=	"SELECT CONCAT(pa.name,':ADD $',ifnull(pa.adjust_price,'0')) as comboname,pa.*,concat(pa.id,'_',".$category_id.") as new_item_id FROM";
			$qry1	.=		"	product_availabe_accessory paa,
								product_accessories pa
						WHERE ";
			$qry1	.=		" 	paa.product_id=".$product_id." and
								paa.accessory_id=pa.id and
								pa.active='Y'
						ORDER BY pa.name";*/
		$rs1		=	$this->db->get_results($qry1, ARRAY_A);
		
		$arr	=	array();
		for($i=0;$i<count($rs1);$i++)
			{
			$arr[$i]=$rs1[$i];
			if($rs1[$i]['adjust_price']==NULL || $rs1[$i]['adjust_price']==0)
				$arr[$i]['comboname'] =$rs1[$i]['display_name'] ? $rs1[$i]['display_name']: $rs1[$i]['name'];
			}
		if(count($arr)>0)
				return $arr;
		}
		return false;
	}
function GetCustomizationTextOptions()
	{
	$qry	=	"SELECT * FROM product_accessory_wrap_text";
	$rs		=	$this->db->get_results($qry, ARRAY_A);
	return $rs;
	}
function GetcategoryDescription($category_id)
	{
	$qry	=	"Select * from master_category where category_id=".$category_id;
	$rs		=	 $this->db->get_row($qry, ARRAY_A);
	$desc	= 	$rs['category_description'];
	if(empty($desc))
		{
		$qry	=	"Select pg.* from product_accessory_group_categories pc,product_accessory_group pg where pc.group_id=pg.id and pc.category_id=".$category_id;
		$row	=	 $this->db->get_row($qry, ARRAY_A);
		$desc	= 	$row['description'];
		}
	return $desc;
	}	
	

	# The following method returns the accessories adjust price from the product_availabe_accessory if available else return empty string
	# Added on 04-25-2007 
	function getAccessoryAdjustPriceFromProductAvailableAccessory($product_id, $category_id, $accessory_id)
	{
		 $Qry1			=	"SELECT adjust_price FROM product_availabe_accessory WHERE product_id = '$product_id' AND category_id = '$category_id' AND accessory_id = '$accessory_id'";
		 $Result			=	$this->db->get_row($Qry1, ARRAY_A);
		 $adjust_price	=	$Result['adjust_price'];
		
		if( $adjust_price==0.00){
		 $Qry1			=	"SELECT adjust_price FROM product_accessories WHERE  id ='$accessory_id'";
		 $Result		=	$this->db->get_row($Qry1, ARRAY_A);
		 $adjust_price	=	$Result['adjust_price'];
			
			}
		return $adjust_price; 	
	}

function ValidateMandatory($array,$store_name='')
	{
	$objCategory		=	new Category();
	extract($array);
	$selected_array	=	array();
	//get the selected categories
	if($array['access'])
		{
		foreach ($array['access'] as $key=>$value)
				{
				if (!in_array($key, $selected_array))
					{
					if($value>0)
					$selected_array[]=$key;
					}
				}
			}
	//print_r($selected_array);
	$arr			=	array();
	$categories		=	array();
	//get the available groups for the product
	$groups	=	$this->getAccessoryGroups($product_id,$store_name);
	for($i=0;$i<count($groups);$i++)
		{
		$message='';
		$selected_count=0;
		$unselected_count=0;
		//get the manadatory categories under the group for the product
		$categories[$i]		=	$this->getMandatoryCategory($product_id,$groups[$i]['id'],$store_name);
		for($j=0;$j<count($categories[$i]);$j++)
			{
			$arr[$i]['category_name']=$categories[$i][$j]['category_name'];
			if(in_array($categories[$i][$j]['category_id'],$selected_array))//check for mandatory category selected or not
					{
					//$status[$i][$j]['status']	=	'YES';
					$selected_count	=	$selected_count+1;
					}
				else
					{
					$unselected_count	=	$unselected_count+1;
					//$status[$i][$j]['status']	=	'NO';
					//if a mandatory category is not selected fetch the error message.
					$message	.= "Please Select the ".$objCategory->GetCategoryGroupName($categories[$i][$j]['category_id']).".<BR>";
					}
				//echo "\tStatus: ".$status[$i][$j];
				//echo "\n";
			}//for 2
		//if the group is having only one category, set the message that mandatory category is selected or not
		if(count($categories[$i])==1)
			{
			if($selected_count==1)
				$arr[$i]['status']	=	'YES';
			else
				{
				$arr[$i]['status']	=	'NO';
				$arr[$i]['message']	=	$message;
				}
			}//count==1
			//if the group is having more than one category like monogram, 
			//
		if(count($categories[$i])>1)
			{
			//if we have selected atleast one category and not all mandatory categories are selected. then status will be no
			//else status will be yes
			if($selected_count>0 && $selected_count!=count($categories[$i]))
				{
				$arr[$i]['status']	=	'NO';
				$arr[$i]['message']	=	$message;
				}
			else
				{
				$arr[$i]['status']	=	'YES';
				
				}
			}//if count>1
		}//for 1
		$main_status=true;
		if($arr)
			{
			
			$main_message='';
			foreach ($arr as $row)
				{
				if($row['status']=='NO')
					{
					$main_status=false;
					$main_message.=$row['message'];
					}
				}
			}
		$status['status']=$main_status;
		if($main_status==false)
			$status['message']=$main_message;
		
	return $status;
	//print_r($arr);
	//exit;
	}
}

?>