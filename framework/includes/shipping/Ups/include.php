<?php

include_once(FRAMEWORK_PATH.'/includes/shipping/Ups/ups.php');

function getQuote($param) {

	$ups = new ups; 
	$ups->set_src_zip($param['src_zip']); 
	$ups->set_dst_zip($param['dst_zip']); 
	if(!$param['weight'])$param['weight'] = 0.001;
	$ups->set_weight ($param['weight']); 
	$ups->set_src_country($param['src_country']); 
	$ups->set_dst_country($param['dst_country']);
	$ups_cost = $ups->get_cost(); 
	
	
	if (!$ups_cost) return array('error'=>$ups->_errors); 
	else return array('cost' => $ups_cost); 

}

?>