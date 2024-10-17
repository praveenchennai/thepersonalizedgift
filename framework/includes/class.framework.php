<?php
include_once(FRAMEWORK_PATH."/includes/db/class.mysql.php");
include_once(FRAMEWORK_PATH."/includes/smarty/Smarty.class.php");
include_once(FRAMEWORK_PATH."/includes/class.message.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.language.php");
include_once(FRAMEWORK_PATH."/includes/JSON.php");

global 	$DbObj;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class SingletonDatabase Extends FrameWork {
	function &getInstance() {
		if (!isset($GLOBALS['DbObj'])) {
			//$GLOBALS['DbObj']		=	& new DB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			$GLOBALS['DbObj']		=	new DB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			
		}
		return $GLOBALS['DbObj'];
	}

	function &getLangInstance($mod,$db) {
		if (!isset($GLOBALS["langObj{$mod}"])) {
			//$GLOBALS["langObj{$mod}"]		=	& new Language($db);
			$GLOBALS["langObj{$mod}"]		=	new Language($db);
		}
		return $GLOBALS["langObj{$mod}"];
	}


	function &getSmartyInstance()
	{
		if (!isset($GLOBALS['smarty_config'])) {
			//$this->tpl 		=&	new Smarty;
			$this->tpl 		= new Smarty;
	
			$this->tpl->template_dir 	= 	SITE_PATH . "/templates";
			$this->tpl->compile_dir 	= 	SITE_PATH . "/tmp/templates_c";
			$this->tpl->config_dir 		= 	SITE_PATH . "/includes/smarty/configs";
			$this->tpl->cache_dir 		= 	SITE_PATH . "/tmp/cache";
	
			$this->tpl->register_block('makeLink', 'makeLink');
			$this->tpl->register_block('colorPicker', 'colorPicker');
			$this->tpl->register_block('flashPlayer', 'flashPlayer');
			$this->tpl->register_function('messageBox', 'messageBox');
			$this->tpl->register_function('messageBox1', 'messageBox1');
			$this->tpl->register_function('messageBox_top', 'messageBox_top');
			$this->tpl->register_block('DateFormat','DateFormat');
			$this->tpl->register_block('makeLink2', 'makeLink2');
			$this->tpl->register_block('loadEditor','loadEditor');
			
			$this->getConfig();
			if ($this->config['lang_enabled']=='Y')
			{
				$this->lang = & SingletonDatabase::getLangInstance($mod,$this->db);
				$lang_var =  $this->getLangVariable();
			}
			$GLOBALS['smarty_config']['tpl']    = $this->tpl;
			$GLOBALS['smarty_config']['lang']    = $this->lang;
			$GLOBALS['smarty_config']['config'] = $this->config;
			$GLOBALS['smarty_config']['mod_var'] = $lang_var['MOD_VARIABLES'];	
			$GLOBALS['smarty_config']['com_var'] = $lang_var['COM_VARIABLES'];			
		}	
		return $GLOBALS['smarty_config'];
	}
}


class FrameWork {

	var $db     	= 	null;
	var $tpl    	= 	null;
	var $message    = 	null;
	var $lang       =   null;
	var $config 	= 	null;
	
	var $MOD_VARIABLES = null;
	var $COM_VARIABLES = null;

	public function FrameWork() {
		$this->db =	$this->getInstance();
		//$this->db
		$smarty = $this->getSmartyInstance();
		//$smarty->
		$this->config = $smarty['config'];
		$this->tpl = $smarty['tpl'];
		$this->lang = $smarty['lang'];
		$this->MOD_VARIABLES = $smarty['mod_var'];
		$this->COM_VARIABLES = $smarty['com_var'];
	}

	function &getInstance() {
		if (!isset($GLOBALS['DbObj'])) {
			//$GLOBALS['DbObj']		=	& new DB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			$GLOBALS['DbObj']		=	new DB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			
		}
		return $GLOBALS['DbObj'];
	}

	function &getLangInstance($mod,$db) {
		if (!isset($GLOBALS["langObj{$mod}"])) {
			//$GLOBALS["langObj{$mod}"]		=	& new Language($db);
			$GLOBALS["langObj{$mod}"]		=	new Language($db);
		}
		return $GLOBALS["langObj{$mod}"];
	}

	function &getSmartyInstance()
	{
		if (!isset($GLOBALS['smarty_config'])) {
			//$this->tpl 		=&	new Smarty;
			$this->tpl 		= new Smarty;
	
			$this->tpl->template_dir 	= 	SITE_PATH . "/templates";
			$this->tpl->compile_dir 	= 	SITE_PATH . "/tmp/templates_c";
			$this->tpl->config_dir 		= 	SITE_PATH . "/includes/smarty/configs";
			$this->tpl->cache_dir 		= 	SITE_PATH . "/tmp/cache";
	
			$this->tpl->register_block('makeLink', 'makeLink');
			$this->tpl->register_block('colorPicker', 'colorPicker');
			$this->tpl->register_block('flashPlayer', 'flashPlayer');
			$this->tpl->register_function('messageBox', 'messageBox');
			$this->tpl->register_function('messageBox1', 'messageBox1');
			$this->tpl->register_function('messageBox_top', 'messageBox_top');
			$this->tpl->register_block('DateFormat','DateFormat');
			$this->tpl->register_block('makeLink2', 'makeLink2');
			$this->tpl->register_block('loadEditor','loadEditor');
			
			$this->getConfig();
			if ($this->config['lang_enabled']=='Y')
			{
				$this->lang = $this->getLangInstance($mod,$this->db);
				$lang_var =  $this->getLangVariable();
			}
			$GLOBALS['smarty_config']['tpl']    = $this->tpl;
			$GLOBALS['smarty_config']['lang']    = $this->lang;
			$GLOBALS['smarty_config']['config'] = $this->config;
			$GLOBALS['smarty_config']['mod_var'] = $lang_var['MOD_VARIABLES'];	
			$GLOBALS['smarty_config']['com_var'] = $lang_var['COM_VARIABLES'];			
		}	
		return $GLOBALS['smarty_config'];
	}


	/**
  	 * This function fetches Module based variable from Label Management
  	 * Author   : Retheesh
  	 * Created  : 01/Apr/2008
  	 * Modified : 01/Apr/2008 By Retheesh
  	 * Modified : 08/May/2009 By Retheesh
  	 * Label management error inside the class files fixed
  	 */
	function getLangVariable()
	{
		if ($_REQUEST['sess'])
		{
			parse_str(base64_decode($_REQUEST['sess']), $req);
			$_REQUEST = array_merge($_REQUEST, $req);
		}

		$MOD_VARIABLES = $this->lang->getLanguageVariable("member");
		$COM_VARIABLES = $MOD_VARIABLES;
		
		$mod = $_REQUEST['mod'] ? $_REQUEST['mod'] : "member";
		
		if ($mod!="member")
		{
			$this->MOD_VARIABLES = $this->lang->getLanguageVariable($mod);
		}
		else 
		{
			$this->MOD_VARIABLES = $MOD_VARIABLES ;
		}

		$this->MOD_VARIABLES['MOD_COMM'] = $MOD_VARIABLES['MOD_COMM'];
		$this->COM_VARIABLES = $COM_VARIABLES;
		
		$lang_arr['MOD_VARIABLES'] = $this->MOD_VARIABLES;
		$lang_arr['COM_VARIABLES'] = $this->COM_VARIABLES;
		
		return $lang_arr;
	}



	function getConfig() {
		$rs = $this->db->get_results("SELECT field, value FROM config");
		if($rs) {
			foreach ($rs as $row) {
				$this->config[$row->field] = $row->value;
			}
		}
		

	}
	//This function returns an array which contains the respective field numbers of custom fileds

	function getCustomFields($table_name,$fields='')
	{
		$sql = "select `table_id` from `custom_fields_table` where `table_name`='$table_name'";
		$rs  = $this->db->get_col($sql,0);
		$table_id=$rs[0];
		$arr    = array();

		if($table_id!='')
		{
			$sql="select field_no,field_name from `custom_fields_title` where table_id=$table_id";
			$rs = $this->db->get_results($sql);
		}
		if($fields=='')
		{
			$arr[0] = $rs;
			$arr[1] = $table_id;
		}
		else
		{
			$arr1 = array();
			$arr2 = $fields;
			$cnt=0;

			for($i=0;$i<sizeof($fields);$i++)
			{
				for($j=0;$j<sizeof($rs);$j++)
				{
					if($fields[$i]==$rs[$j]->field_name)
					{
						$arr1[$i] = "field_".$rs[$j]->field_no;
						break;
					}
				}

			}
			$arr[0] = $fields;
			$arr[1] = $arr1;
			$arr[2] = $table_id;
		}
		return $arr;
	}

	//This function returns a two dimensional array.The index '0' contains an array of master fields
	// and index '1' contains an array of custom fields

	function splitFields($arr_master,$table_name)
	{
		$arr=$this->getCustomFields($table_name);
		$arr_custom=array();
		for($i=0;$i<sizeof($arr[0]);$i++)
		{
			$key="field_".$arr[0][$i]->field_no;
			$value=$arr[0][$i]->field_name;
			foreach($arr_master as $key_name=>$val)
			{
				if($key_name==$arr[0][$i]->field_name)
				{
					$arr_custom[$key]=$arr_master["$value"];
				}
			}
			unset($arr_master[$value]);
		}
		if($arr[1])
		{
			$arr_custom["table_id"] = $arr[1];
		}
		$arr_new    = array();
		$arr_new[0] = $arr_master;
		$arr_new[1] = $arr_custom;
		return $arr_new;
	}

	//This function will genreate custom fields for a query
	function generateQry($table_name,$table_alias='tb',$main_table='m')
	{
		$arr=$this->getCustomFields($table_name);
		for($i=0;$i<sizeof($arr[0]);$i++){
			if($i>0){
				$qry=$qry.",";
			}
			$qry=$qry."$table_alias.field_".$arr[0][$i]->field_no." as ".$arr[0][$i]->field_name;
		}
		if($qry=='')
		{
			$qry="1";
			$join_qry = '';
		}
		else
		{
			$tb_id =  $arr[1];
			$join_qry = "left join `custom_fields_list` $table_alias on $main_table.id=$table_alias.table_key and $table_alias.table_id=$tb_id";
		}
		$ar=array();
		$ar[0] = $qry;
		$ar[1] = $arr[1];
		$ar[2] = $join_qry;
		return $ar;
	}

	function generateQryAggr($table_name,$aggr_functions,$aggr_fields,$table_alias='tb',$main_table='m')
	{
		$arr=$this->getCustomFields($table_name);
		$agg_fields = explode(",",$aggr_fields);
		$agg_fns    = explode(",",$aggr_functions);
		$groupby_fields = array();

		for($i=0;$i<sizeof($arr[0]);$i++)
		{
			if($i>0)
			{
				$qry=$qry.",";
			}
			$match =0;
			for ($j=0;$j<sizeof($agg_fields);$j++)
			{
				if ($arr[0][$i]->field_name==$agg_fields[$j])
				{
					$match = 1;
					$match_index = $j;
					break;
				}
				else
				{
					$match = 0;
				}
			}
			if ($match==1)
			{
				$match_fn = $agg_fns[$match_index];
				$qry.="$match_fn($table_alias.field_".$arr[0][$i]->field_no.") as ".$arr[0][$i]->field_name."_$match_fn";
				$qry.=",$table_alias.field_".$arr[0][$i]->field_no." as ".$arr[0][$i]->field_name;
			}
			else
			{
				$groupby_fields[] = $arr[0][$i]->field_no;
				$qry=$qry."$table_alias.field_".$arr[0][$i]->field_no." as ".$arr[0][$i]->field_name;
			}
		}
		if($qry=='')
		{
			$qry="1";
			$join_qry = '';
		}
		else
		{
			$tb_id =  $arr[1];
			$join_qry = "left join `custom_fields_list` $table_alias on $main_table.id=$table_alias.table_key and $table_alias.table_id=$tb_id";
		}
		$group_qry="";
		for ($k=0;$k<sizeof($groupby_fields);$k++)
		{
			if($k>0)
			{
				$group_qry.=",";
				$pre = "";
			}
			else
			{
				$pre = " GROUP BY ";
			}
			$group_qry .= $pre."field_".$groupby_fields[$k] ;
		}
		$ar=array();
		$ar[0] = $qry;
		$ar[1] = $arr[1];
		$ar[2] = $join_qry;
		$ar[3] = $group_qry;
		return $ar;
	}

	function getCustomQry($table_name,$search_fields,$search_values,$criteria,$master_prefix='m',$custom_prefix='cs')
	{
		$search_fields = explode(",",$search_fields);
		$search_values = explode(",",$search_values);
		$criteria      = explode(",",$criteria);
		$arr = $this->getCustomFields($table_name,$search_fields);
		if($arr[2]=='')
		{
			$arr[2] = $custom_prefix.".table_id";
		}
		$prefix_arr = array();
		foreach($arr[1] as $key=>$value)
		{
			$search_fields[$key] = $value;
			$prefix_arr[$key] = $custom_prefix;
		}

		for($i=0;$i<sizeof($search_fields);$i++)
		{
		($i>0) ? $cm=" AND " : $cm="";
		(isset($prefix_arr[$i])=='') ? $pre=$master_prefix : $pre=$prefix_arr[$i];
		($criteria[$i]=='') ? $crt="=" : $crt=$criteria[$i];
		
		$qry=isset($qry);

		if (strtolower($crt)=="like"){

			$qry = $qry.$cm.$pre.".".$search_fields[$i]." $crt '%".$search_values[$i]."%'";
		
		} elseif(($crt==">") || ($crt=="<") || ($crt==">=") || ($crt=="<=")){
			
			$qry = $qry.$cm.$pre.".".$search_fields[$i]."$crt".$search_values[$i]."";
		} else{
			$qry = $qry.$cm.$pre.".".$search_fields[$i]."$crt'".$search_values[$i]."'";
		}

		}
		$ar = array();
		$ar[0] = $qry;
		$ar[1] = $arr[2];
		return $ar;
	}

	function getCustomId($table_name)
	{
		$sql = "select `table_id` from `custom_fields_table` where `table_name`='$table_name'";
		$rs  = $this->db->get_col($sql,0);
		$table_id=$rs[0];
		return $table_id;
	}
	/**
	 * This function checks whether a particular user has reached his/her maximum record limit
	 * Author   : Retheesh
	 * Created  : 14/Sep/2007
	 * Modified : 14/Sep/2007 By Retheesh
	 */
	function maxRecordLimit($user_id,$table_name,$user_id_field="user_id",$condition='',$check_id='')
	{

		$link_field = $this->config["restriction_field"];
		if ($link_field=="") return false;
		$sql = "select $link_field from member_master where id='$user_id'";
		$rs  = $this->db->get_row($sql,ARRAY_A);

		$link_value = $rs["$link_field"];
		if ($link_value)
		{
			$sql = "select * from custom_fields_table where table_name='$table_name'";
			$rs  = $this->db->get_row($sql,ARRAY_A);
			if (count($rs)>0)
			{
				$table_id = $rs["table_id"];

				$sql = "select * from member_restrictions where table_id=$table_id and link_id=$link_value";
				$rs  = $this->db->get_row($sql,ARRAY_A);

				$max_records = $rs["max_records_user"];
				if ($max_records>0)
				{
					if($check_id=="") {
						$sql = "select * from $table_name where $user_id_field=$user_id";
					} else {
						$sql = "select * from $table_name where $user_id_field=$check_id";
					}

					if($condition != "")
					$sql .= " AND ".$condition;
					$rs  = $this->db->get_results($sql);
					if (count($rs)>=$max_records)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	/*
	function maxRecordLimit($user_id,$table_name,$user_id_field="user_id")
	{

	$link_field = $this->config["restriction_field"];
	if ($link_field=="") return false;
	$sql = "select $link_field from member_master where id='$user_id'";
	$rs  = $this->db->get_row($sql,ARRAY_A);

	$link_value = $rs["$link_field"];

	if ($link_value)
	{
	$sql = "select * from custom_fields_table where table_name='$table_name'";
	$rs  = $this->db->get_row($sql,ARRAY_A);
	if (count($rs)>0)
	{
	$table_id = $rs["table_id"];

	$sql = "select * from member_restrictions where table_id=$table_id and link_id=$link_value";
	$rs  = $this->db->get_row($sql,ARRAY_A);

	$max_records = $rs["max_records_user"];
	if ($max_records>0)
	{
	$sql = "select * from $table_name where $user_id_field=$user_id";
	$rs  = $this->db->get_results($sql);
	if (count($rs)>$max_records)
	{
	return true;
	}
	else
	{
	return false;
	}
	}
	else
	{
	return false;
	}
	}
	else
	{
	return false;
	}
	}
	else
	{
	return false;
	}
	}*/



	/**
	 * This function updates and retrieves page hits
	 * Author    : Retheesh
	 * Created   : 04/Oct/2007
	 * Modified  : 04/Oct/2007 By Retheesh
	 * DB Tables : page_hit
	 *
	 */
	function pageHit($pageName,$pg_user_id,$increment=1)
	{
		$sql = "select id,page_views from page_hit where page_name='$pageName'
		and page_user='$pg_user_id'";
		$rs  = $this->db->get_row($sql);
		$sess_id  = session_id();
		$hit_date = date("Y-m-d");
		$unique_field = md5($unique_field);
		if (count($rs)>0)
		{

			if ($increment==1)
			{
				$sql ="select * from page_hit_log where sess_id='$sess_id' and hit_date='$hit_date'
				 and page_user=$pg_user_id and page_name='$pageName'";
				$log_rs = $this->db->get_row($sql);
				$incr = 0;
				if (count($log_rs)==0)
				{
					$incr=1;
					$sql = "update page_hit set page_views=page_views+1 where page_name='$pageName'
					and page_user='$pg_user_id'";
					$this->db->query($sql);
					$arr = array();
					$arr["page_name"]    = $pageName;
					$arr["page_user"]    = $pg_user_id;
					$arr['hit_date']     = $hit_date;
					$arr['sess_id']      = $sess_id;
					$this->db->insert("page_hit_log",$arr);
				}

			}
			if ($incr==0)
			{
				return $rs->page_views;
			}
			else
			{
				return $rs->page_views+1;
			}
		}
		else
		{
			if ($increment==1)
			{

				$arr = array();
				$arr["page_name"]    = $pageName;
				$arr["page_user"]    = $pg_user_id;
				$arr["page_views"]   = 1;
				$this->db->insert("page_hit",$arr);

				$arr1 = array();
				$arr1["page_name"]    = $pageName;
				$arr1["page_user"]    = $pg_user_id;
				$arr1['hit_date']     = $hit_date;
				$arr1['sess_id']      = $sess_id;
				$this->db->insert("page_hit_log",$arr1);
				return 1;
			}
			else
			{
				return 0;
			}
		}
	}
}

?>