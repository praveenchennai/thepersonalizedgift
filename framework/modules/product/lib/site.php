<?
session_start();
include_once("config.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");

$objProduct		=	new Product();
$objUser		=	new User();
$objExtras		=	new Extras();
$objCms 		= 	new Cms();
$objStore 		= 	new Store();
$objAccessory	=	new Accessory();

//checkLogin();

switch($_REQUEST['act']) {
	case "create_web":
	$id = $_REQUEST['id'];
	if($_SERVER['REQUEST_METHOD'] == "POST")
		{	
			//strtolower($sitename = $_REQUEST['sitename'] ? $_REQUEST['sitename'] : 'samplewebsite');
			//strtolower($dbname = $_REQUEST['dbname'] ? $_REQUEST['dbname'] : 'sampledb');
			strtolower($sourcedb = $_REQUEST['sourcedb']);
			$serial = $objCms->maxvalue(website_created,id);//getting the maximum value of id from the table
			$sr_no = ++$serial->maxval;
			
			$sitename = 'demosite'.$sr_no;//setting the sitename
			$dbname   = 'dbdemosite'.$sr_no;//setting the data base name
			$arr = array(sitename=>$sitename,dbname=>$dbname);
		
			//$objCms	->sitedata($arr);//insert the details to database
				
			//creating directories
			$path = $_SERVER['DOCUMENT_ROOT'];
			mkdir($path."/".$sitename, 0777);
			chmod($path."/".$sitename, 0777);
  //start copying files			
  // loc1 is the path on the server to the base directory that may be moved
  // copy a directory and all subdirectories and files (recursive)
  // void dircpy( str 'source directory', str 'destination directory' [, bool 'overwrite existing files'] )
 define('loc1', $path, true);
  
			function dircpy($source, $dest, $overwrite = false){

			  if($handle = opendir(loc1 . $source)){         // if the folder exploration is sucsessful, continue
				while(false !== ($file = readdir($handle))){ // as long as storing the next file to $file is successful, continue
				  if($file != '.' && $file != '..'){
					$path = $source . '/' . $file;
					if(is_file(loc1 . $path)){
					  if(!is_file(loc1 . $dest . '/' . $file) || $overwrite)
						if(!@copy(loc1 . $path, loc1 . $dest . '/' . $file)){
						  echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>';
						}
						chmod (loc1 . $dest . '/' . $file,0777);
					} elseif(is_dir(loc1 . $path)){
					  if(!is_dir(loc1 . $dest . '/' . $file))
						mkdir(loc1 . $dest . '/' . $file); // make subdirectory before subdirectory is copied
						chmod(loc1 . $dest . '/' . $file,0777);
					  dircpy($path, $dest . '/' . $file, $overwrite); //recurse!
					}
				  }
				}
				closedir($handle);
			  }
			} // end of dircpy()
			
			
dircpy('/myspace','/'.$sitename);
				
				//modify the config file
				chmod($path."/".$sitename."/config.php",0777);

				 $file_contents = file_get_contents($path."/".$sitename."/config.php");
				 $string = array("myspace","databasename");
				 $replace_string = array($sitename,$dbname);
 				 $file_replaced = str_replace($string,$replace_string,$file_contents);
				 //file_put_contents($path."/".$sitename."/config.php",$file_replaced);
				 
				  $fp	=	fopen($path."/".$sitename."/config.php" , "w");
				 			fwrite($fp , $file_replaced);
							fclose($fp);
				 

			//end copying files		
			
	//creating database
	$localhost	=   DB_HOST;
	$username	=	DB_USER;
	$password	=	DB_PASSWORD;

	$backupRt = $path."/salim/";
	$backupFile = $backupRt . $sourcedb . date("Y-m-d-H-i-s") . '.sql';
	$command = "mysqldump --opt -h $localhost -u $username  $sourcedb  > $backupFile";
	system($command); 
	chmod($backupFile,0777);

	//creating new db
	$sql = 'CREATE DATABASE '. $dbname;
	if (mysql_query($sql)) {
		echo "Database ".$dbname." created successfully\n";
	} else {
		echo 'Error creating database: ' . mysql_error() . "\n";
	}

	$command2 = "mysql -h $localhost -u $username $dbname < $backupFile";
	system($command2);
	
	//end creating database
	
	$db_selected = mysql_select_db($dbname);//select the newly created database
	if (!$db_selected) {
    	die ('Can\'t use '.$dbname.' : ' . mysql_error());
		}

	$arr = array();
	$arr['active']=Y;

	for ($i=0; $i<$_REQUEST['count']; $i++) {

		if ($_REQUEST['check'.$i] == 1){
			$val = $_REQUEST['hid'.$i];
			$arr['name'] = $_REQUEST['text'.$i]; 
			$objCms->UpdateModule($arr,$val);
		}
	}
	
	
			mysql_select_db(DB_NAME);
			//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=$id"));
			//redirect(makeLink(array("mod"=>"product", "pg"=>"site"), "act=succe&sitename=$sitename"));*/
			redirect(makeLink(array("mod"=>"product", "pg"=>"site"), "act=web_menu&pid=$id&newsite=$sitename&newdb=$dbname"));
		}
		
		else
			 {
				$param = "show_admin_menu";	
			 	//$rs = $objCms->ModuleGet($param);
				//print_r($rs);
			 	$srcdb = $objCms->DBGet($id);//print_r($srcdb);echo $rs['name'];
				$db_selected = mysql_select_db($srcdb['name']);	
				
				$rsmodule = $objCms->ModuleGet($param);				

				
				/*foreach ($rsmodule as $a1){
					foreach ($rs as $b1){
					if ($a1[id]===$b1[id]){
					echo $a1[content] = $b1[content];
					$framework->tpl->assign("MENU_HEADS1",$a1[content]);
					}
				}
			 }*/
			 
				$framework->tpl->assign("DB_NAME", $srcdb);
				$framework->tpl->assign("MENU_HEADS",$rsmodule);
		
				$db_selected = mysql_select_db(DB_NAME);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/web_create.tpl");
		}
		break;
		
		case "web_menu":
		
		$pid = $_REQUEST['pid'];
		$dbname = $_REQUEST['newdb'];
		$sitename = $_REQUEST['newsite'];
		mysql_select_db($dbname);
		if($_SERVER['REQUEST_METHOD'] == "POST")
			{	
						$arr = array();
						$arr['active']=Y;
				
					 for ($i=0; $i<$_REQUEST['count']; $i++) {
				
						if ($_REQUEST['check'.$i] == 1){
							$val = $_REQUEST['hid'.$i];
							$arr['menu'] = $_REQUEST['text'.$i]; 
							$objCms->UpdateModuleMenu($arr,$val);
						}
					} 
					//redirect(makeLink(array("mod"=>"product", "pg"=>"site"), "act=web_menu&id=$id&newdb=$dbname"));*/
					echo $sal = $_REQUEST['finish'];echo $sal1 = $_REQUEST['submit'];
					if ($_REQUEST['finish']){
					
					redirect(makeLink(array("mod"=>"product", "pg"=>"site"), "act=succe&sitename=$sitename"));
					}
					else{
					
					}
	
			}
			$param = "active";	
			$rsmodules = $objCms->ModuleGet($param);	//print_r($rsmodules);
			$rsmenus = $objCms->GetMenusByModule();//print_r($thanks);
			$framework->tpl->assign("PRODUCT",$pid);
			$framework->tpl->assign("SITENAME",$sitename);
			$framework->tpl->assign("DBNAME",$dbname);
			$framework->tpl->assign("MENU_HEADS",$rsmodules);
			$framework->tpl->assign("MENU",$rsmenus);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/web_create_menu.tpl");
		break;
		
		case "web_fields":
			
			$pid = $_REQUEST['pid'];
			$dbname = $_REQUEST['newdb'];
			$sitename = $_REQUEST['newsite'];
			$menu_id = $_REQUEST['menu_id'];
			mysql_select_db($dbname);
			if ($_SERVER['REQUEST_METHOD'] == "POST")
				{		
						$arr = array();
						$arr['active']=Y;
				
						for ($i=0; $i<$_REQUEST['count']; $i++) {
					
							if ($_REQUEST['check'.$i] == 1){
								$val = $_REQUEST['hid'.$i];
								$arr['name'] = $_REQUEST['text'.$i]; 
								print_r($arr);
								$objCms->UpdateModuleMenuFields($arr,$val);
							}
						}
					redirect(makeLink(array("mod"=>"product", "pg"=>"site"), "act=web_menu&pid=$pid&newdb=$dbname&newsite=$sitename"));
				}

			$rsfields = $objCms->GetFieldsByMenu($menu_id);//print_r($rsfields);
			$framework->tpl->assign("PRODUCT",$pid);
			$framework->tpl->assign("DBNAME",$dbname);
			$framework->tpl->assign("SITENAME",$sitename);
			$framework->tpl->assign("FIELDS",$rsfields);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/web_create_fields.tpl");
		break;	 

		 case "succe":
			$arr_succ = array($_REQUEST['sitename'],$_SERVER['SERVER_NAME']);
			$thanks = $objCms->GetCMSpageById(54);
	
			$framework->tpl->assign("ARR_SUCC",$arr_succ);
			$framework->tpl->assign("THNX",$thanks);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/thnx.tpl");
		break;
		case "welcome_store":
		$storename = $_REQUEST['sid'];
		$storeDetails = $objStore->storeGetByName($storename);
		$framework->tpl->assign("THNX",$storeDetails);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/welcome_store.tpl");
		break;
		case "cms_desc":
				$id = $_REQUEST['id'];
				$table = $_REQUEST['tbl'];
				$url = $_REQUEST['url'];
				if($table === 'cms_link'){
					if($_REQUEST['storename']){
						redirect(SITE_URL.'/'.$_REQUEST['storename'].'/'.$url);
					}
					else{
						redirect(SITE_URL.'/'.$url);
					}
				}
				else{
					$rs = $objProduct->getCmsSearchDetails($id);
					if ($_REQUEST['storename']){
					redirect(SITE_URL.'/'.$_REQUEST['storename'].'/'.$rs.'.'.php);//Redirecting to the request URL in STORES.
					}
					else{
					redirect(SITE_URL.'/'.$rs.'.'.php);//Redirecting to the request URL.
					}
				}
				
				//print_r($rs);exit;
				
		break;	 
		case "art_desc":
				$id = $_REQUEST['id'];
				$table = $_REQUEST['tbl'];
				
				$rs = $objAccessory->GetAccessory($id);
				
				//print_r($rs);exit;

				$framework->tpl->assign("PATH",$table);
				$framework->tpl->assign("DETAILS",$rs);
				$framework->tpl->assign("SUBMITBUTTON", createImagebutton_Div("Go back","#","Goback();return false; "));
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/user_art_discription.tpl");
		break;	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>