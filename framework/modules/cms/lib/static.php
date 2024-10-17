<?php
    
	$file = SITE_PATH."/modules/cms/tpl/".$_REQUEST['name'].".tpl";

    if(file_exists($file)) {
	  $framework->tpl->display($file);
    } else {
	  $framework->tpl->display($global['curr_tpl']."/index.tpl");
	}
?> 