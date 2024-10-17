<?php
	/**
 * This  is used to check author detalis entered by user  already exit.
 * Author   : Adarsh
 * Created  : 21/Nov/2007
 * Modified : 
 */			
   $framework->tpl->assign("VAL", $_REQUEST['val']);
   
   $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/author_list.tpl");
   $framework->tpl->display($global['curr_tpl']."/userPopup.tpl");		
?>