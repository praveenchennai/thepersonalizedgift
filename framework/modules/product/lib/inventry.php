<?
session_start();

include_once("config.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");

$objProduct		=	new Product();
error_reporting(E_ALL);

switch($_REQUEST['act']) {
	case "list":
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$cntInsert	=	0;
			$cntUpdate	=	0;
			$cntSuccess =	0;
			$row		= 	1;
			
			$fileName		=	date("G:i:s").$_FILES['invnetry']['name'];
			$fileType 		= 	$_FILES['invnetry']['type'];
			$fileSize 		= 	$_FILES['invnetry']['size'];
			$tmp_csv_file	=	$_FILES['invnetry']['tmp_name'];
			//if($fileType  == 'application/vnd.ms-excel')
			if($fileType  == 'application/octet-stream'){
						$dir	=	SITE_PATH."/modules/product/images/";
						$sal	=	_upload($dir,$fileName,$tmp_csv_file,'','','','');
			
			$arraytocheck	=	$objProduct->checkUniqueKeyExists('part_num');
			
			$handle = fopen($dir.$fileName, "r");
		
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				  	$row++;
				  	
				    if($row == 2 || $data[0]=='')
				    continue;
				    
					$where	=	addslashes($data[0]);
					$cntSuccess++;
					
				    $array	=	array( 'part_num' 		=> addslashes($data[0]),
							            'model_num' 	=> addslashes($data[1]),
							            'description' 	=> addslashes($data[2]),
							            'condition'  	=> addslashes($data[3]),
							            'price'  		=> addslashes($data[4]),
							            'quoted_price'  => addslashes($data[5]),
							            'aircraft_type' => addslashes($data[6]),
							            'quantity'  	=> addslashes($data[7]),
							            'vendot/customer_num'  => addslashes($data[8]),
  									    'manufacturer'  => addslashes($data[9])
				            		);
					if(isset($arraytocheck)){
					 		
					     	if(!in_array($data[0],$arraytocheck)){
					     		$cntInsert++;
					     		$objProduct->addInventry($array);
					     	}
					     	else {
					     		$cntUpdate++;
					     		$objProduct->updateInventry($array,$where);
					     	}
						
					}    
					else{
						$cntInsert++;
						$objProduct->addInventry($array);
					}
				    
				}
				fclose($handle);
	
				$arr_inventrylog = array('name' 	=> $fileName, 
										'success' 	=> $cntSuccess, 
										'added' 	=> $cntInsert, 
										'updated' 	=> $cntUpdate
										);
				$objProduct->inventryLog($arr_inventrylog);
						
				setMessage("File Uploaded Successfully",MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"inventry"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']));
			}
			
			else{
				setMessage("Please Upload files in CSV format");
				redirect(makeLink(array("mod"=>"product", "pg"=>"inventry"), "act=list&sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']));
			}
		}
	
	$framework->tpl->assign("LOG", $objProduct->getInventryLog());
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/inventry_upload.tpl");
	break;
		
	case "view":
		
		$search			=	$_REQUEST["search"]		? $_REQUEST["search"]	: "0";
		$search_tag		=	$_REQUEST["inventry_search"]	? $_REQUEST["inventry_search"]	: "";
		$orderBy		=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
		$pageNo			=	$_REQUEST["pageNo"] 	? $_REQUEST["pageNo"] 	: "1";
		$limit			=	$_REQUEST["limit"] 		? $_REQUEST["limit"] 	: "20";
		$params			=	"mod=product&pg=inventry&act=view";
		if($search_tag != ''){
			$search="1";
		}
		if($search =='1'){
			list($rs, $numpad, $cnt, $limitList)	=	$objProduct->getInventryListBySearch($pageNo,$limit,$params,$orderBy,$search_tag);
		}
		else {
			list($rs, $numpad, $cnt, $limitList)	=	$objProduct->getInventryList($pageNo,$limit,$params,$orderBy);
		}
		//print_r($rs);
		
		$framework->tpl->assign("INVENTRY", $rs);
		$framework->tpl->assign("INVENTRY_LIMIT", $limitList);
		$framework->tpl->assign("NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/inventry_list.tpl");
		break;
		
	case "edit":
		
		if($_SERVER['REQUEST_METHOD'] =='POST'){
			$array	=	$_POST;
			$sId	=	$array['sId'];
			$fId	=	$array['fId'];
			unset($array['tmpcount'],$array['sId'],$array['fId'],$array['submit']);
			$where	=	$array['part_num'];
			$objProduct->updateInventry($array,$where);
			redirect(makeLink(array("mod"=>"product", "pg"=>"inventry"), "act=view&sId=".$sId."&fId=".$fId));
		}
		
		$part_num	=	$_REQUEST['part_num'];
		$inventry	=	$objProduct->getOneInventryByPartNumber($part_num);

		$framework->tpl->assign("INVENTRY", $inventry);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/inventry_form.tpl");
		break;
		
	case "delete":
		
		extract($_REQUEST);
		if(count($del_id)>0)
			{
			$message=true;
			foreach ($del_id as $inventry_id)
				{  
				
				if($objProduct->inventryDelete($inventry_id)==false)
					$message=false;
				}
			}
			if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
			
		redirect(makeLink(array("mod"=>"product", "pg"=>"inventry"), "act=view&sId=".$sId."&fId=".$fId));
		break;
		
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>