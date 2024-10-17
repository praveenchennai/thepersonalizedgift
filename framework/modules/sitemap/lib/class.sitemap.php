<?php
/**
 *  SiteMap
 *
 * @author Ajith
 * @package defaultPackage
 */
class Sitemap extends FrameWork {
  var $category_name;
  var $parent_id;
  var $active;  
  var $img_url;
  var $img_path;
  function Sitemap($category_name="", $parent_id="",$active="") {
	$this->category_name 	= 	$category_name;
	$this->parent_id 		= 	$parent_id;
	$this->active			=	$active;	
	$this->img_path			=	SITE_URL."/templates/default/images/red-dot.jpg";
	$this->FrameWork();
  }
	/**
	 * Category List
	 *
	 * @param <Page Number> $pageNo
	 */
	function getCategoryTree(&$arr, $parent_id = 0, $level = 0) {
		global	$store_id;		
		$img_url		=	'<img src="'.$this->img_path.'" border=0>';
		if($store_id){
			$query		=	"SELECT a.* 
								FROM
								 	master_category a,store_category b 
								WHERE a.category_id=b.category_id  
								 AND a.parent_id = '$parent_id' 
								 AND a.is_in_ui<>'Y' 
								 AND a.active='y' 
								 AND  b.store_id=".$store_id;
		}else{
			$query		=	"SELECT *
								 FROM master_category 
							 WHERE parent_id = '$parent_id'
							 	 AND is_in_ui<>'Y' AND active='y'";			
		}
		//echo $query;	
			//$rs = $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '$parent_id' and is_in_ui<>'Y' and active='y'");
			$rs = $this->db->get_results($query);
			if($rs) {
				foreach ($rs as $row) {
					$arr[]=array("category_id"=>$row->category_id,"category_name"=>str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level).$img_url."&nbsp;"."<u>".ucwords($row->category_name)."</u>");
					$this->getCategoryTree($arr, $row->category_id, $level+1);
				}
			} else {
				return ;
			}	
				 
	}
	/**
	 *Listing staticpagelink in sitemap pages
	 *
	 * return array
	 */
	function linkSelectionList(&$linkarr) {
		global $store_id;
		$img_url	=	'<img src="'.$this->img_path.'" border=0>';
		if($store_id){				
			$sql	= 	"SELECT DISTINCT(a.title),a.url 
							FROM cms_link a,store_cms b
						 WHERE a.id=b.cms_id AND b.cms_type='L'
							 AND b.store_id='$store_id'
						 ORDER BY a.position ";
		}else{
			$sql	= 	"SELECT DISTINCT(a.title),a.url
						 	FROM cms_link a LEFT JOIN store_cms b ON(a.id=b.cms_id)  
						 WHERE b.cms_id is NULL 
						 ORDER BY a.position";
		}		
		$rs 		= 	$this->db->get_results($sql);
		if($rs) {
			foreach ($rs as $row) {
				$linkarr[]=array("title"=>$img_url."&nbsp;"."<u>".$row->title."</u>","url"=>$row->url);
			}
		} else {
			return ;
		}		
  }
  /**
	 *Listing CMS pages and its submenu
	 *
	 * return array
	 */
  function sectionMenuCombo () {
  		global $store_id;
  		$img_url	=	'<img src="'.$this->img_path.'" border=0>'; 		
       	$sql		= 	"SELECT id, name FROM cms_section WHERE show_menu = 'Y' AND active = 'Y'";		
        $rs			= 	$this->db->get_results($sql);
        $arr 		= 	array();
		$cnt		=	0;
        if ($rs) {
        	foreach ($rs as $row) {
			//$url=makeLink(array("mod"=>"cms", "pg"=>"display"), "section={$row->id}");
			$url="";
			$arr[$cnt++] 	=	array("name"=>$img_url."&nbsp;"."<u>".$row->name."</u>","id"=>$url);
        		if($store_id){
					$menuquery		= 	"SELECT a.id, a.name, a.seo_url 
											FROM cms_menu a, store_cms b 
										WHERE a.id=b.cms_id 
											AND b.cms_type='M' 
											AND a.section_id ='$row->id'
											AND b.store_id='$store_id' 
										ORDER BY a.position";
        		}else{
					$menuquery		= 	"SELECT a.id, a.name, a.seo_url
											FROM cms_menu a LEFT JOIN store_cms b ON(a.id=b.cms_id)  
										WHERE b.cms_id is NULL 
											AND a.section_id = '$row->id' 
										ORDER BY a.position ";
				}
				$menurs				= 	$this->db->get_results($menuquery);
				//$menurs			= 	$this->db->get_results("SELECT id, name FROM cms_menu WHERE section_id='$row->id'");
				if ($menurs) {
        			foreach ($menurs as $menurow) {
						//$url=makeLink(array("mod"=>"cms", "pg"=>"display"), "menu={$menurow->id}");
						$url = $menurow->seo_url.".php";
						$arr[$cnt++] 	=	array("name"=>str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", 1).$img_url."&nbsp;"."<u>".$menurow->name."</u>","id"=>$url); 						
						if($store_id){
							$pageQuery	=	"SELECT a.id,a.title 
											 	FROM cms_page a,store_cms b
											 WHERE a.id=b.cms_id 
										 		AND b.cms_type='P' 
												AND a.menu_id = '$menurow->id' 
												AND b.store_id='$store_id' 
											ORDER BY a.position ASC, a.post_date DESC";
						}else{
							$pageQuery	= 	"SELECT a.id,a.title
												FROM cms_page a LEFT JOIN store_cms b ON(a.id=b.cms_id)
										  	WHERE b.cms_id is NULL
						 						AND menu_id = '$menurow->id' 
											ORDER BY a.position ASC, a.post_date DESC ";						}
							$pages			= 	$this->db->get_results($pageQuery);
						//$pages			= 	$this->db->get_results("SELECT id, title FROM cms_page WHERE menu_id ='$menurow->id'");
//						if($pages){
//							foreach ($pages as $pagerow) {	
//								$url=makeLink(array("mod"=>"cms", "pg"=>"display"), "page={$pagerow->id}");
//								$arr[$cnt++] =	array("name"=>str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", 2).$img_url."&nbsp;"."<u>".$pagerow->title."</u>","id"=>$url); 								
//							}
//						}
					}
        		}
			}
		 }	
		return $arr;
	}
	 /**
	 *Listing all store links in sitemap section
	 *
	 * return array
	 */
	 function listStore(&$linkarr) {
	 	global	$store_id;
	 	$img_url='<img src="'.$this->img_path.'" border=0>';		
		$sql	= 	"SELECT * 
						FROM  store 
					WHERE active='Y'";		
		$rs 	= 	$this->db->get_results($sql);
		if($rs) {
			foreach ($rs as $row) {
				$linkarr[]=array("name"=>$row->name ,"heading"=>$img_url."&nbsp;"."<u>".$row->heading."</u>" );
			}
		} else {
			return ;
		}		
  }
}
?>