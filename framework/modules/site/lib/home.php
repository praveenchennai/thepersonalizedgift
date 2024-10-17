<?php 
	/**
	 * siteIndexpage
	 *
	 * @author ajith
	 * @package defaultPackage
	 */
	authorize_site();
	include_once(FRAMEWORK_PATH."/modules/site/lib/class.site.php");
	$site					=	new Site();
	$siteDetails			=	$site->getSiteByName($_REQUEST['sitename']);		
	$site_id				=	$siteDetails['id'];	
	$modules				=	$site->allModules();
	$framework->tpl->assign("MODULE_LIST", $modules);	
	if($siteDetails['modules']){		
		$modulesval			=	explode(',',$siteDetails['modules']);			
		 for($i=0;$i<count($modules);$i++){		 	
		 	for($j=0;$j<count($modulesval);$j++){			
				if($modules[$i]->id == $modulesval[$j]){				
					$modules[$i]->check="T";
				}	
			}
				
		}	
		$framework->tpl->assign("MODULES", $modules);
	}	
	 if($_SERVER['REQUEST_METHOD'] == "POST") {
		$req = &$_REQUEST;		
		if( ($message = $site->updateModules($req)) === true ) {
			$user_name	=	$siteSess[0]->username;
			$password	=	$siteSess[0]->password;
			$login 		= 	new Site($user_name,$password);
			$action 	= 	$siteDetails['modules'] ? "Updated" : "Added";
			$login->authenticate();			
			setMessage("Modules $action Successfully", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"site", "pg"=>"home")));
		}
		setMessage($message);	
	}			
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/site/tpl/module_list.tpl");
	if($siteDetails['modules']){
		$framework->tpl->display($global['curr_tpl']."/site.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/home.tpl");
	}
?>
