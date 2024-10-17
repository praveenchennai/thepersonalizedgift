	<?php
		ob_start();
		include_once("config.php");
		include_once(FRAMEWORK_PATH."/includes/functions.php");
		include_once(FRAMEWORK_PATH."/includes/class.framework.php");
		include_once(SITE_PATH."/includes/xmlConfig.php");
		include_once(FRAMEWORK_PATH."/modules/editor/lib/class.editor.php");
		$framework 	= 	new FrameWork();
		$act		=	$_REQUEST['act'];
				
				$imageFileName	=	"16.jpg";
				$imageLocation	=	SITE_URL."/modules/product/images/16.jpg";
				$maxWidth 		= 	640;
				$maxHeight 		= 	480;
				$cmd 			= 	"identify '" . $imageLocation . "' 2>/dev/null";
				$results 		= 	exec($cmd);
				$results 		=   trim($results);
				$results 		=   explode(" ", $results);
				foreach ($results as $i=> $result) {
				  if (preg_match("/^[0-9]*x[0-9]*$/", $result)) {
					$results = explode("x", $result);
					break;
				  }
				}
				$dimensions['height'] = $results[1];
				$dimensions['width']  = $results[0];					
				// RESIZE IF UPLOAD IS TOO BIG
				if(($dimensions['width']>$maxWidth) || ($dimensions['width']>$maxWidth)){
					  $cmd = "convert " . $imageLocation . " -resize 640x480 " . $imageLocation;
					  $results = exec($cmd); 
					 
					  $cmd = "identify '" . $imageLocation . "' 2>/dev/null";
					  $results = exec($cmd);
					  $results = trim($results);
					  $results = explode(" ", $results);
					  foreach ($results as $i=> $result) {
						if (preg_match("/^[0-9]*x[0-9]*$/", $result)) {
						  $results = explode("x", $result);
						  break;
						}
					  }
					$dimensions['height'] = $results[1];
					$dimensions['width'] = $results[0];
			 }	
				$framework->tpl->assign("IMAGE_LOCATION", $imageLocation);
				$framework->tpl->assign("IMAGE_WIDTH"	,$dimensions['width']);
				$framework->tpl->assign("IMAGE_HEIGHT" ,$dimensions['height']);
				$framework->tpl->assign("IMAGE_NAME", $imageFileName);
				
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/crop_popup.tpl");
			
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>		  