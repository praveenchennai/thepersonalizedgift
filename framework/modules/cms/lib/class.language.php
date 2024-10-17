<?php

/**
* CMS Module Language
*
* @author aneesh
* @package defaultPackage
*/

	
	
	class Language extends FrameWork {
		
		var $db  = null;
		 	
		 function Language($db='') {
		 	if ($db=='')
		 	{
        		$this->FrameWork();
		 	}
		 	else 
		 	{	
        		$this->db = $db;
		 	}	
   		 }
   		 
   		 
   		 # RETURN ALL ACTIVE MODULE ORDER BY POSITION
   		 function sectionGetModules () {
			$sql	=	"SELECT id,name FROM module WHERE show_admin_menu='Y' ORDER BY position";		
       	 	$rs['value'] = $this->db->get_col($sql, 0);
       		$rs['display'] = $this->db->get_col($sql, 1);
       		return $rs;
   		 }
   		 
   		 # RETURN ALL ACTIVE LANGUAGE CONTENT PART 
   		 function sectionGetLangContent ( $lang_id=1 ) {
			$sql	=	"SELECT id,name FROM language_content WHERE active='Y' AND lang_id=$lang_id  ORDER BY position";		
       	 	$rs['value'] = $this->db->get_col($sql, 0);
       		$rs['display'] = $this->db->get_col($sql, 1);
       		return $rs;
   		 }
   		 
   		 # RETURN MODULES INFORMATION BY ITS ID
   		 function getModuleById( $mod )
		 {
			if ( trim($mod) ) {
				$sql ="SELECT * FROM module WHERE id=$mod";
				$rs = $this->db->get_row($sql,ARRAY_A);
				return $rs;
			}
		 }
   		 
		 # RETURN LANGUAGE INFO BY ID
		 function getLanguage ( $lang_id=1 ) {
	 		$sql ="SELECT * FROM language WHERE id=$lang_id";
			$rs = $this->db->get_row($sql,ARRAY_A);
			return $rs;
		 }
		 
		 # RETURN LANGUAGE CONTENT INFO BY ID
		 function getLanguageContents ( $contentID,$lang_id=1 ) {
		 	if ( trim($contentID) ) {
		 		$sql ="SELECT * FROM language_content WHERE id=$contentID AND lang_id=$lang_id ORDER BY position";
				$rs = $this->db->get_row($sql,ARRAY_A);
				return $rs;
		 	}
		 }
		 
		 
		  # RETURN ALL VARIABLE PREFIX AND ARRAY NAME 
		  function getLanguageContentsAll ( $lang_id=1 ) {
		 		$sql ="SELECT * FROM language_content WHERE lang_id=$lang_id ORDER BY position";
				$rs = $this->db->get_results($sql,ARRAY_A);
				return $rs;
		 }
		 
   		 
   		 function getLangContVariable ( $req )	{
   		 	
   		 	$mod		=	$req['module_id'];
   		 	$content	=	$req['content_id'];
   		 	$page		=	$req['page'];
   		 	$perage		=	$req['perpage'];
   		 	
   		 	
   		 	if( $mod < 1 )
   		 	return;
   		 	
   		 	   		 	
   		 	
   		 	# GET MODULE's FOLDER
   		 	$MOD_ARR	=	$this->getModuleById($mod);			# folder path Info
   		 	
   		 	# GET LANGUAGE PREFIX
   		 	$LANG_ARR	=   $this->getLanguage();
   		 	
			# GET CONTENTS ARRAY NAME
			$LANG_CONTENT_ARR	=   $this->getLanguageContents($content);
			
			# GET LAn VAR DETAILS
   		 	$LAN_VAR_ARRY  = $this->getLanguageContentsAll();
			
   		 	   		 				   		 	
   		 	# VARIABLE PATH
   		 	$VARIABLE_FILE = SITE_PATH . "/modules/" . $MOD_ARR['folder'] . "/lib/language/". $LANG_ARR['prefix'] . ".lang.php";
   		 	
   		 	# CUSTOM VARIABLE PATH
   		 	$CUST_VARIABLE_FILE = SITE_PATH . "/language/" . $MOD_ARR['folder'] . "_". $LANG_ARR['prefix'] . ".lang.php";
   		 	
   		 	
   		 	$MOD_VARIABLES1 = array();
   		 	$MOD_VARIABLES2 = array();
   		 	

   		 	if ( file_exists($VARIABLE_FILE) ) {
   		 		
   		 		include($VARIABLE_FILE);  
   		 		$MOD_VARIABLES1 = $MOD_VARIABLES;
  		 		
   		 	  		
   		 		if ( file_exists($CUST_VARIABLE_FILE) ) {
   		 			include($CUST_VARIABLE_FILE); 
   		 			$MOD_VARIABLES2 = $MOD_VARIABLES; 
   		 		}
   		 		
   		 	  		 		
   		 		if ( count($MOD_VARIABLES2) > 0  && is_array($MOD_VARIABLES2) ) {
   		 	   		 	
   		 			foreach($LAN_VAR_ARRY as $MOD_ARRAY_ALL){
   		 				if( array_key_exists($MOD_ARRAY_ALL['array_name'], $MOD_VARIABLES1) && array_key_exists($MOD_ARRAY_ALL['array_name'], $MOD_VARIABLES2) ){
   		 					
   		 					$MOD_VARIABLES[ $MOD_ARRAY_ALL['array_name'] ] = array_merge($MOD_VARIABLES1[$MOD_ARRAY_ALL['array_name']],$MOD_VARIABLES2[$MOD_ARRAY_ALL['array_name']]);
   		 				}else{
   		 					$MOD_VARIABLES[$MOD_ARRAY_ALL['array_name']] = $MOD_VARIABLES1[$MOD_ARRAY_ALL['array_name']];
   		 				}
   		 			}
  		 		}		 		
   		 		
   		 		
   		 		 		 		
   		 		
   		 		$MOD_STRINGS = array();
   		 		$i           = 0;
   		 		
   		 		if ( !trim($LANG_CONTENT_ARR['array_name']) ){
	   		 		# FOR ALL ARRAYS
	   		 		if ( count($MOD_VARIABLES) ) {
		   		 		foreach ($MOD_VARIABLES as $key1=>$MOD_VARIAB) {
		   		 			if ( count($MOD_VARIAB) ) {
			   		 			foreach ($MOD_VARIAB as $key=>$MOD_VARIAB){
			   		 				$MOD_STRINGS[$i]['arrayname']	= $key1;
				   		 			$MOD_STRINGS[$i]['const'] 	= $key;
				   		 			$MOD_STRINGS[$i]['variab']	= $MOD_VARIAB;
				   		 			$i++;
			   		 			}
		   		 			}
		   		 		}
	   		 		}
   		 		}else{
   		 			# FOR ARRAYS BY IDs
   		 			if ( count($MOD_VARIABLES[$LANG_CONTENT_ARR['array_name']]) ) {
	   		 			foreach ($MOD_VARIABLES[$LANG_CONTENT_ARR['array_name']] as $key=>$MOD_VARIAB){
	   		 				$MOD_STRINGS[$i]['arrayname']	= $LANG_CONTENT_ARR['array_name'];
		   		 			$MOD_STRINGS[$i]['const'] 	= $key;
		   		 			$MOD_STRINGS[$i]['variab']	= $MOD_VARIAB;
		   		 			$i++;
	   		 			}
		   		 	}
   		 		}
   		 		
   		 		
   		 		# INCLUDE PAGINATION
   		 		//include_once(SITE_PATH."/modules/cms/lib/class.arraypagination.php");
   		 		//$pagination  = new ArrayPagination;
   		 		//$MOD_STRINGS = $pagination->generate($MOD_STRINGS, 2,$page);
   		 		//$NUM_PADS    = $pagination->links();
   		 		
   		 		# ******************#
   		 		
   		 		return array($MOD_STRINGS);
   		 	} 	
   		 	
   		 }
   		 
   		 
   		 
   		 
   		 function getAjaxResults($MOD_ARRAY,$NUM_PADS)	{
 
   		 	$RES = "";
   		 	
   		 	
	 		$RES ="<table width=\"100%\" border=\"0\">";
				  
			  
			  $RES .="<tr height=\"20\">";
			  
			    $RES .="<td colspan=\"2\" width=\"100%\">";
			    
			    $i = 0;
			    foreach ($MOD_ARRAY as $key=>$variab_item)
			    {
			    if ($i % 2 == 0)
				$RES .="<table width=\"100%\" border=\"0\" height=\"15\" class=\"naGrid1\">";
				else 
				$RES .="<table width=\"100%\" border=\"0\" height=\"15\" class=\"naGrid2\">";
				
				  $RES .="<tr>";
					$RES .="<td align=\"left\" width=\"50%\">{$variab_item['const']}</td>";
					$RES .="<td align=\"left\" width=\"50%\"><input style=\"width:250px\" name=\"{$variab_item['const']}\" type=\"text\" class=\"input\" value=\"{$variab_item['variab']}\" /></td>";
				  $RES .="</tr>";
				$RES .="</table>";
				$i++;
			    }
				
				$RES .="</td>";
				
		      $RES .="</tr>";
		     
		       
		     //$RES .="<tr height=\"20\">";
					   //$RES . "<td colspan=\"2\" align=\"center\">{$NUM_PADS}</td>";
		     //$RES .=" </tr>";	      
		      
			$RES .="</table>";
	 		
	 		return $RES;
   		 }
   		 
   		 
   		 
   		 
   		 function createArrayFile ( $req ) {
   		 	
     		 	
   		 	extract($req);

   		 	if ($module_id > 0)
   		 	$moduleid = $module_id;
   		 	
   		 	
   		 	if( $moduleid < 1 )
   		 	return;
   		 	
   		 	   		 	
   		 	# GET MODULE's FOLDER
   		 	$MOD_ARR	=	$this->getModuleById($moduleid);			# folder path Info
   		 	
   		 	# GET LANGUAGE PREFIX
   		 	$LANG_ARR	=   $this->getLanguage();
   		 	
			# GET CONTENTS ARRAY NAME
			$LANG_CONTENT_ARR	=   $this->getLanguageContents($content);
			
			   		 	
   		 	# VARIABLE PATH
   		 	$VARIABLE_FILE = SITE_PATH . "/language/" . $MOD_ARR['folder'] . "_". $LANG_ARR['prefix'] . ".lang.php";
   		 	
   		 	
   		 	# CUSTOM VARIABLE PATH
   		 	$CUST_VARIABLE_FILE = SITE_PATH . "/language/" . $MOD_ARR['folder'] . "_". $LANG_ARR['prefix'] . ".lang.php";
   		 	if ( file_exists($CUST_VARIABLE_FILE) ) {
   		 		include($CUST_VARIABLE_FILE); 
   		 		
   		 		$MOD_VARIABLES1 = $MOD_VARIABLES;
   		 		
   		 		foreach($MOD_VARIABLES1 as $MOD_LABLES) {
   		 			$req = array_merge($MOD_LABLES,$req);
   		 		}
   		 		
   		 	}
   		 	
   		 	
   		 	# GET LAn VAR DETAILS
   		 	$LAN_VAR_ARRY  = $this->getLanguageContentsAll();
   		 	
   		 	$fp = fopen( $VARIABLE_FILE, "w+"); 
   		 	
   		 	if (!$fp) {
   		 		return false;
   		 	}
   		 	
   		 	chmod($VARIABLE_FILE, 0777);
   		 	
   		 	$FILE_CONTENTS = "";
   		 	   		 	
   		 	
   		 	if ( file_exists($VARIABLE_FILE) ) {
   		 		
				$FILE_CONTENTS  = "<?php\n";
				$FILE_CONTENTS .=  "\n";
				
				# Comment Block
				$FILE_CONTENTS .=  "\t# **************************************** #\n";
				$FILE_CONTENTS .=  "\t\t# Modified : " . date("F j, Y, g:i a") . " #\n";
				$FILE_CONTENTS .=  "\t# **************************************** #";
				$FILE_CONTENTS .=  "\n\n";
				
				
				$COUNT_EXIST    = 0;
				$FILE_LABELS    = "";
				$FILE_ARRAY_STR = "\t\t";
				foreach ($LAN_VAR_ARRY as $LAN_VAR) {
					
						foreach( $req as $KEY=>$PVAL ) {
							$prefixstr = explode("_",$KEY);
							if ( trim($prefixstr[0]) == $LAN_VAR["variable_prefix"] ) {
								if ( $COUNT_EXIST == 0 ) {
									$FILE_ARRAY_STR .= '"' . $LAN_VAR["array_name"] . '"' . ' => ' . '$' . $LAN_VAR["array_name"] . ' ,';
									$FILE_LABELS .=   "\t## " . strtoupper($LAN_VAR["name"]) . " ##\n\n";
									$FILE_LABELS .=   "\t$" . $LAN_VAR["array_name"] . "= array (";
									$FILE_LABELS .=   "\n";
									$COUNT_EXIST  = 1;
								}
								$FILE_LABELS .= "\t\t" . "'" . $KEY. "'" . " => ". "'" . $PVAL. "',"."\n";
							}
						}
						
						if ( $COUNT_EXIST == 1 ) {
							$FILE_LABELS  = substr($FILE_LABELS,0,-2);
							$FILE_LABELS .= "\n";
							$FILE_LABELS .= "\t);\n\n";
						}
						$COUNT_EXIST    = 0;
				}
				
				

				$FILE_CONTENTS .= $FILE_LABELS . "\n";
				
				$FILE_CONTENTS .=   "\t$" . "MOD_VARIABLES = array (";
				$FILE_CONTENTS .=   "\n";
				$FILE_ARRAY_STR    = substr($FILE_ARRAY_STR,0,-2);
				$FILE_CONTENTS .=    $FILE_ARRAY_STR . "\n" ;
				$FILE_CONTENTS .= "\t);\n\n";
				
				$FILE_CONTENTS .= "?>\n";
				
				
				fwrite($fp, $FILE_CONTENTS);
				fclose($fp);
				
				return true;
   		 	} 
			
   		 }
   		 
   		 
   		    		 
   		 function getLanguageVariable ($mod) {
   		 	
   		 	# GET LANGUAGE PREFIX
   		   		 	
   		 	$LANG_ARR	=   $this->getLanguage();
   		 	
   		 	
   		 	# VARIABLE PATH
   		 	$VARIABLE_FILE = SITE_PATH . "/modules/" . $mod . "/lib/language/". $LANG_ARR['prefix'] . ".lang.php";
   		
   		 	# CUSTOM VARIABLE PATH
   		 	$CUST_VARIABLE_FILE = SITE_PATH . "/language/" . $mod . "_". $LANG_ARR['prefix'] . ".lang.php";
   		 	
   		 	
			# GET LAn VAR DETAILS
   		 	$LAN_VAR_ARRY  = $this->getLanguageContentsAll();
   		 	   		 	

   		 	
   		 	if ( file_exists($VARIABLE_FILE) ) {
   		 		include($VARIABLE_FILE); 
   		 		$MOD_VARIABLES1 = $MOD_VARIABLES;
   		 	}
   		 	
   		 		
   		 	
   		 	if ( file_exists($CUST_VARIABLE_FILE) ) {
   		 		include($CUST_VARIABLE_FILE); 
   		 		$MOD_VARIABLES2 = $MOD_VARIABLES;
   		 	}
   		 	
   		 	
   		 
   		 	
   		 	/*if ( count($MOD_VARIABLES2) > 0  && is_array($MOD_VARIABLES2) )
   		 	$MOD_VARIABLES = array_merge($MOD_VARIABLES1, $MOD_VARIABLES2);*/
   		 	
   		 	
   		 	
   		 	if ( count($MOD_VARIABLES2) > 0  && is_array($MOD_VARIABLES2) ) {
   		 	   		 	
   		 			foreach($LAN_VAR_ARRY as $MOD_ARRAY_ALL){
   		 				if( array_key_exists($MOD_ARRAY_ALL['array_name'], $MOD_VARIABLES1) && array_key_exists($MOD_ARRAY_ALL['array_name'], $MOD_VARIABLES2) ){
   		 					
   		 					$MOD_VARIABLES[ $MOD_ARRAY_ALL['array_name'] ] = array_merge($MOD_VARIABLES1[$MOD_ARRAY_ALL['array_name']],$MOD_VARIABLES2[$MOD_ARRAY_ALL['array_name']]);
   		 				}else{
   		 					$MOD_VARIABLES[$MOD_ARRAY_ALL['array_name']] = $MOD_VARIABLES1[$MOD_ARRAY_ALL['array_name']];
   		 				}
   		 			}
  		 		}		
   		 	
   		 	//print_r($MOD_VARIABLES);
   		 	
   		   //	print_r($MOD_VARIABLES);
   		 	return $MOD_VARIABLES;
   		 }
   		 
   		 
   		
   		 
   		
	}
?>