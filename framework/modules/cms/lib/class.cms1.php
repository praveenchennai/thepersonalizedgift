<?php
class Cms extends FrameWork {
    
	var 	$errorMessage;
	var		$LeftMenuText;
	var		$CategorySelectionJs;
		
    function Cms() {
        $this->FrameWork();
    }
    /**
	 * Menu List
	 *
	 */
    function menuList ($sectionId) {
	
		global $store_id;		 			
		if($store_id){		
		
		/*Afsal - 09.21.2007 - Bug on Store query */
		 $sql	=	"SELECT a.* FROM cms_menu a,store_cms b WHERE a.id=b.cms_id AND b.cms_type='M' AND a.section_id = '$sectionId' AND b.store_id='$store_id' AND b.store_id!='0' ORDER BY a.position ";          	 //   echo $sql	= 	"SELECT a.* FROM cms_menu a LEFT JOIN store_cms b ON a.id=b.cms_id  AND b.store_id='0' WHERE a.section_id = '$sectionId'ORDER BY a.position ";
 
		// $sql	=	"SELECT a.* FROM cms_menu a,store_cms b WHERE a.id=b.cms_id AND b.cms_type='M' AND a.section_id = '$sectionId' AND b.store_id='$store_id' AND b.store_id!='0' ORDER BY a.position ";          
		// echo $sql;
			
		}else{	
		
		
			 $sql	= 	"SELECT distinct a.* FROM cms_menu a LEFT JOIN store_cms b ON a.id=b.cms_id  AND b.store_id='0' WHERE a.section_id = '$sectionId'ORDER BY a.position ";
          // conmmeded this code for getting cms menulinks
		
			// $sql	= 	"SELECT a.* FROM cms_menu a LEFT JOIN store_cms b ON(a.id=b.cms_id)  WHERE b.cms_id is NULL AND a.section_id = '$sectionId' ORDER BY a.position ";
			
		} 
		//echo $sql;
		
		$rs = $this->db->get_results($sql);
		
	//print_r($rs);
	
		 $rs['section_id'];
		
		//echo $rs['cms_type'];
        return $rs;
    }
    /**
	 * Menu Combo
	 *
	 */
    function menuCombo ($sectionId) {
		global $store_id;
		if($store_id){
			 $sql	=	"SELECT a.id,a.name FROM cms_menu a,store_cms b WHERE a.id=b.cms_id AND b.cms_type='M' AND a.section_id = '$sectionId' AND b.store_id='$store_id' ORDER BY a.name";
			
			
		}else{
			$sql	= 	"SELECT a.id,a.name FROM cms_menu a LEFT JOIN store_cms b ON(a.id=b.cms_id)  WHERE b.cms_id is NULL AND a.section_id = '$sectionId' ORDER BY a.name ";
		}
			//$sql	=	"SELECT id,name FROM cms_menu  WHERE section_id = '$sectionId' ORDER BY name";
			$rs['id'] = $this->db->get_col($sql, 0);
			$rs['name'] = $this->db->get_col("", 1);
			return $rs;
    }
    /**
	 * Get Menu for ID
	 *
	 */
    function menuGet ($id) {
        $sql		= "SELECT * FROM cms_menu WHERE id = '$id'";
        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
    /**
	 * Section List for Combo Box in menu page
	 *
	 */
    function sectionCombo () {
	global $store_id;
	if($store_id){
		$sql	=	"SELECT id,name FROM cms_section WHERE active='Y' and show_sub_store='Y'";	
	}else{
	$sql	=	"SELECT id,name FROM cms_section WHERE 1";		
	}
		
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['name'] = $this->db->get_col("", 1);
        return $rs;
    }
	
	   /**
	 * Section List for link area section
	 *
	 */
    function sectionLinkArea () {
		$sql	=	"SELECT value,display FROM cms_link_area WHERE active='Y'";		
        $rs['value'] = $this->db->get_col($sql, 0);
        $rs['display'] = $this->db->get_col($sql, 1);
        return $rs;
    }

    
    
    /* 
    	Get Limit of Link area 
    	11-Feb-2008
		Aneesh Aravindan
    */
    function getLimitLinkArea($area="")	{
       	if(trim($area)){
    		 $SQL = "SELECT limit_display FROM cms_link_area WHERE value = '{$area}'";
    		 $rs = $this->db->get_row($SQL, ARRAY_A);
       		 return $rs;
    	}
    }
    
    
    /**
	 * Add Edit CMS Menu
	 *
	 * @param <POST/GET Array> $req
	 * @return Error Message if Any
	 */
    function menuAddEdit (&$req) {		
    	
        extract($req);
		
		global $store_id;				
        if(!trim($section_id)) {
            $message = "Section is required";
        } elseif (!trim($name)) {
            $message = "Name is required";
        } elseif (!trim($seo_url)) {
            $message = "SEO URL is required";
        } elseif (!trim($position)) {
            $message = "Position is required";
        } else {
            $array = array("section_id"=>$section_id, "name"=>$name, "seo_url"=>$seo_url, "position"=>$position, "active"=>$active, "type_link"=>$type_tip,"storeowner_edit_pages"=>$storeowner_edit_pages);//changed "type_tip"=>$type_tip 0n 27th sept 

          
            if($id) {
                $array['id'] = $id;
                $this->db->update("cms_menu", $array, "id='$id'");
            } else {
				$this->db->insert("cms_menu", $array);
                $id = $this->db->insert_id;					
				//print_r($id);exit;
				//if(trim($store_id)){					
					$arr_cmsstore	=	array("store_id"=>$store_id,"cms_id"=>$this->db->insert_id, "cms_type"=>'M');
					$this->db->insert("store_cms", $arr_cmsstore);
				//}				
            }
            return true;
        }
        return $message;
    }
	
	//function to get value for action from table order_status
	function orderStatus() {
		$sql = "SELECT * FROM order_status WHERE 1";
		$rs['id'] = $this->db->get_col($sql, 0);
        $rs['name'] = $this->db->get_col("", 1);
        return $rs;
	}
	
	/**
	 * Delete Menu
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */
    function menuDelete ($id) {
        $this->db->query("DELETE FROM cms_page WHERE menu_id='$id'");
        $this->db->query("DELETE FROM cms_menu WHERE id='$id'");
    }
	
	/**
	 * List Modules
	 *
	 * @param <POST/GET Array> $req
	 */
    function pageList ($menu_id, $pageNo, $limit = 10, $params='', $output=OBJECT) {	
		global $store_id;
		if($store_id){
			//$sql	=	"SELECT a.*,DATE_FORMAT(a.post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page a,
						//store_cms b WHERE a.id=b.cms_id AND b.cms_type='P' AND a.menu_id = '$menu_id' AND b.store_id='$store_id' ORDER BY a.position ASC, a.post_date DESC";	
						$sql	= 	"SELECT a.*,DATE_FORMAT(a.post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page a LEFT JOIN store_cms b ON (a.id=b.cms_id)  WHERE 
						b.cms_id is NOT NULL AND a.post_admin='' AND b.store_id='$store_id' AND menu_id = '$menu_id' ORDER BY a.position ASC, a.post_date DESC ";

		}else{
			$sql	= 	"SELECT distinct a.*,DATE_FORMAT(a.post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page a LEFT JOIN store_cms b ON(a.id=b.cms_id)  WHERE 
						a.post_admin='admin' AND menu_id = '$menu_id' ORDER BY a.position ASC, a.post_date DESC ";
		}
		//echo $sql;
		//die($sql."HHH");
		
		//$sql	= 	"SELECT *, DATE_FORMAT(post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page WHERE menu_id = '$menu_id' ORDER BY position ASC, post_date DESC";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output);
		
        return $rs;
    }

    /**
	 * Get Menu for ID
	 *
	 */
    function pageGet ($id, $output=ARRAY_A) {
	
        $sql		= "SELECT *, DATE_FORMAT(post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page WHERE id = '$id'";
        $rs = $this->db->get_row($sql, $output);
        return $rs;
    }
	
	
	//functions created by jeffy for the page dropdown in module cms on 30th August 2007
	//function to get the values from dropdown table by value as fetch id
	function dropdownGet ($dropdown_id, $pageNo, $limit = 10, $params='', $output=OBJECT) {	
		global $store_id;
		//$sql	= 	"SELECT * from drop_down where drop_down_id=$dropdown_id";
		$sql	= 	"SELECT a.*,b.subject,b.body from drop_down a LEFT JOIN email_config b 
					 ON a.drop_down_id=b.drop_down_id where a.drop_down_id=$dropdown_id";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output);
		//print_r($sql);exit;
		return $rs;
    }
	
	//function to add/edit group values to table drop down
	function groupvalueAddEdit (&$req) {				
        extract($req);
		global $store_id;
        if(!trim($name)) {
            $message = "Value is required";
        } else {
            $array = array("group_id"=>$id, "value"=>$name);
            if($drop_down_id) {
                $this->db->update("drop_down", $array, "drop_down_id='$drop_down_id'");
				$id=$drop_down_id;

            } else {
				$this->db->insert("drop_down", $array);
                $id = $this->db->insert_id;					
				//print_r($id);exit;
				if(trim($store_id)){					
					$arr_cmsstore	=	array("store_id"=>$groupstore_id,"cms_id"=>$this->db->insert_id, "cms_type"=>'M');
					$this->db->insert("store_cms", $arr_cmsstore);
				}				
            }
            return $id;
        }
        return $message;
    }
	
	//function to get the dropdown values of a group.
	function groupvalueGet ($group_id, $pageNo, $limit = 10, $params='', $output=OBJECT) {	
		global $store_id;
		$sql	= 	"SELECT * from drop_down where group_id=$group_id order by position";
		//$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output);
		  $rs = $this->db->get_results($sql);
		//print_r($rs);
		return $rs;
    }
	
	//function to delete group values from table drop_down
	function groupvalueDelete ($id) {
        $this->db->query("DELETE FROM drop_down WHERE drop_down_id='$id'");
    }
	// --- ends here ---

    
	
    /**
	 * Module Add Edit
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
    function pageAddEdit (&$req) {
        extract($req);
		global $store_id;		
        if(!trim($menu_id)) {
            $message = "Menu Name is required";
        } elseif (!trim($title)) {
            $message = "Title is required";
        } elseif (!trim($position)) {
            $message = "Position is required";
        } elseif (!trim($content)) {
            $message = "Content is required";
        } else {
            $array = array("menu_id"=>$menu_id,"title"=>$title, "short_content"=>$short_content, "content"=>mysql_real_escape_string($content),"post_date"=>date("Y-m-d H:i:s"), "position"=>$position, "active"=>$active);
			
			 if (trim($store_id)){
			 $array['post_admin']='';
			 }
			 else
			 $array['post_admin']=$_SESSION['adminSess']->username;
			 
             if($meta_description)
			 {
			  $array['meta_description'] = $meta_description;
			 }
			 if($meta_keywords)
			 {
			 $array['meta_keywords'] = $meta_keywords;
			 }
			 if($page_name)
			 {
			 $array['page_name'] = $page_name;
			 }	
			
             if($id) {
                //$array['id'] = $id;
                $this->db->update("cms_page", $array, "id='$id'");				
            } else {
				
                $this->db->insert("cms_page", $array);
                $id = $this->db->insert_id;
				
				if(trim($store_id)){
				
					$arr_cmsstore	=	array("store_id"=>$store_id,"cms_id"=>$this->db->insert_id, "cms_type"=>'P');
					$this->db->insert("store_cms", $arr_cmsstore);
					//exit;
				}
            }
            return true;
        }
        return $message;
    }	
    /**0
	 * Delete Page
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */
    function pageDelete ($id) {
        $this->db->query("DELETE FROM cms_page WHERE id='$id'");
    }

    /**
	 * List Modules
	 *
	 * @param <POST/GET Array> $req
	 */
    function sectionList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	=	"SELECT * FROM cms_section WHERE 1";		 
        $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
        return $rs;
    }

    /**
	 * Get section for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
    function sectionGet ($id) {
        $rs = $this->db->get_row("SELECT * FROM cms_section WHERE id='{$id}'", ARRAY_A);
		
        return $rs;
    }

    /**
	 * section Add Edit
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
    function sectionAddEdit (&$req) {
        extract($req);		
        if(!trim($name)) {
            $message = "Section Name is required";
        } else {
            $array = array("name"=>$name, "show_menu"=>$show_menu,"show_sub_store"=>$show_sub_store, "active"=>$active);
            if($id) {
                $array['id'] = $id;
                $this->db->update("cms_section", $array, "id='$id'");
            } else {
                $this->db->insert("cms_section", $array);				
                $id = $this->db->insert_id;
				if(trim($store_id)){
					$arr_cmsstore	=	array("store_id"=>$store_id,"cms_id"=>$this->db->insert_id, "cms_type"=>'S');
					$this->db->insert("store_cms", $arr_cmsstore);
				}
            }
            return true;
        }
        return $message;
    }

    /**
	 * Delete section
	 *
	 * @param [ID] $id
	 */
    function sectionDelete ($id) {
        $this->db->query("DELETE FROM cms_section WHERE id='$id'");
    }

    /**
     * Adding Link
     *
     * @param Array $req
	 * Modified : 13/Nov/2007 By Salim.
     * Description: The return value is changed to the id of the newly created cms link.
	 */
    function linkAddEdit (&$req) {

    	extract($req);		
		
    	
		
		if ($fname){
			$dir			=	SITE_PATH."/modules/cms/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$fname;
			$path_parts 	= 	pathinfo($fname);
			$file_type=$path_parts['extension'];
			$array['link_image'] = $file_type ;
		}
		$file1_type="";
		if ($mfname){
		
			$dir1			=	SITE_PATH."/modules/cms/images/";
			$file2			=			$dir1."thumb/";
			$resource_file1	=	$dir1.$mfname;
			$path_parts1 	= 	pathinfo($mfname);
			
			$file1_type=$path_parts1['extension'];
			$array['link_oimage'] = $file1_type;
		}
		
		
		global $store_id;
    	if(!trim($title)) {
            $message = "Title is required";
    	} elseif(!trim($url)) {
            $message = "URL is required";
        } else {
		
		$title=htmlspecialchars($title,ENT_QUOTES);
			if ( trim ($file_type) ) {
            $array = array("menu_id"=>$menu_id,"title"=>$title, "url"=>$url, "area"=>$area,"window"=>$window,"type_link"=>$type_link, "link_image"=>$file_type, "link_oimage"=>$file1_type, "description"=>$description);
				
			} else {
			$array = array("menu_id"=>$menu_id,"title"=>$title, "url"=>$url, "area"=>$area,"window"=>$window,"type_link"=>$type_link, "description"=>$description);
				
			}
			
			
			$confeatured=$this->getFeatured();
			if($confeatured)
			{
				if($featured)
				{
				 $array["featured"] = $featured;
				}else{
				$featured=0;
				$array["featured"] = $featured;
				//$array = array("featured"=>$featured);
				}
				
			}	
			
			if($id) {
			//print_r($array);exit;
                $array['id'] = $id;
                $this->db->update("cms_link", $array, "id='$id'");
				//print("select * from cms_link where id='$id'");exit;
            } else {
			
                $this->db->insert("cms_link", $array);
                $id = $this->db->insert_id;
				if(trim($cat_id))
				{
				$cms_type = 'C';
				}
				else
				{
				$cms_type = 'L';
				}					

				if(trim($store_id))
				{
					$arr_cmsstore	=	array("store_id"=>$store_id,"cms_id"=>$this->db->insert_id, "cms_type"=>$cms_type);
					$this->db->insert("store_cms", $arr_cmsstore);
				}
				else
				{
					$arr_cmsstore	=	array("store_id"=>'0',"cms_id"=>$this->db->insert_id, "cms_type"=>$cms_type);
					$this->db->insert("store_cms", $arr_cmsstore);
				
				}
            }
			
			
			if ($fname){
				$save_filename	=	$req['template'].$id.".".$path_parts['extension'];
				$save_filename_small	=	$req['template'].$id. "_small" . ".".$path_parts['extension'];
				
				if ( $cms_thumb_height1>0 && $cms_thumb_width1>0 ) {
				_upload($dir,$save_filename,$tmpname,1,$cms_thumb_width1,$cms_thumb_height1);
					if ( $cms_thumb_height2>0 && $cms_thumb_width2>0 )
					_upload($dir,$save_filename_small,$tmpname,1,$cms_thumb_width2,$cms_thumb_height2);
				}else{
				_upload($dir,$save_filename,$tmpname,1);
				}
				//$array["link_image"]= $save_filename;
                //$this->db->update("cms_link", $array, "id='$id'");

			}
			if ($mfname){
				$save_filename1	=	$req['template']."m_".$id.".".$path_parts1['extension'];
				$save_filename1_small	=	$req['template']."m_".$id. "_small" .".".$path_parts1['extension'];
				
				if ( $cms_thumb_height1>0 && $cms_thumb_width1>0 ) {
				_upload($dir1,$save_filename1,$mtmpname,1,$cms_thumb_width1,$cms_thumb_height1);
					if ( $cms_thumb_height2>0 && $cms_thumb_width2>0 )
					_upload($dir,$save_filename1_small,$mtmpname,1,$cms_thumb_width2,$cms_thumb_height2);
				}else {
				_upload($dir1,$save_filename1,$mtmpname,1);
				}
				//$array["link_oimage"]= $save_filename1;
				//$this->db->update("cms_link", $array, "id='$id'");

			}
           
			
            return $id;
        }
        #return $message;
    }
	 
#############################################################
/**
      * To upload image links for multiple templates
      * Author   : Salim
      * Created  : 13/Nov/2007
      * Modified : NA
      */
	 
	 function linkAddEditMultiTemp (&$req,$id) {
    	extract($req);		
		
		
		if ($fname){
			$dir			=	SITE_PATH."/modules/cms/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$fname;
			$path_parts 	= 	pathinfo($fname);
			$file_type=$path_parts['extension'];
			$array['link_image'] = $file_type ;
		}
		$file1_type="";
		if ($mfname){
		
			$dir1			=	SITE_PATH."/modules/cms/images/";
			$file2			=			$dir1."thumb/";
			$resource_file1	=	$dir1.$mfname;
			$path_parts1 	= 	pathinfo($mfname);
			
			$file1_type=$path_parts1['extension'];
			$array['link_oimage'] = $file1_type;
		}
		
		
			if ($fname){
				$save_filename	=	$req['template'].$id.".".$path_parts['extension'];
				$save_filename_small	=	$req['template'].$id. "_small" . ".".$path_parts['extension'];
				
				if ( $cms_thumb_height1>0 && $cms_thumb_width1>0 ) {
				_upload($dir,$save_filename,$tmpname,1,$cms_thumb_width1,$cms_thumb_height1);
					if ( $cms_thumb_height2>0 && $cms_thumb_width2>0 )
					_upload($dir,$save_filename,$tmpname,1,$cms_thumb_width2,$cms_thumb_height2);
				}else{
				_upload($dir,$save_filename,$tmpname,1);
				}

			}
			if ($mfname){
				$save_filename1	=	$req['template']."m_".$id.".".$path_parts1['extension'];
				$save_filename1_small	=	$req['template']."m_".$id. "_small" .".".$path_parts1['extension'];
				
				
				if ( $cms_thumb_height1>0 && $cms_thumb_width1>0 ) {
				_upload($dir1,$save_filename1,$mtmpname,1,$cms_thumb_width1,$cms_thumb_height1);
					if ( $cms_thumb_height2>0 && $cms_thumb_width2>0 )
					_upload($dir1,$save_filename1_small,$mtmpname,1,$cms_thumb_width2,$cms_thumb_height2);
				}else{
				_upload($dir1,$save_filename1,$mtmpname,1);
				}

			}

            return true;

        return $message;
    }
#######################################################
    /**
	* List Links
	*
	* @param <POST/GET Array> $req
	*/
	  function linkList2($area) {
	  /*	 $sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY a.position";
         $rs = $this->db->get_results($sql,ARRAY_A);
	 
		 for($i=0;$i<count($rs);$i++)
		 {
		       $url=$rs[$i]['url'];
			  
		       $pos=strpos($url,'cat_id');
			   $catid=substr($url,$pos+7);
			   
				 $qry="select * from master_category where  category_id='$catid'";
		          $rs1 = $this->db->get_results($qry,ARRAY_A);
				  for($j=0;$j<count($rs1);$j++)
		          {
				      $catid=$rs1[$j]['category_id'];
					 $maincat=$rs1[$j]['category_name'];
					  $maincat="<ul class='leftNavExpandableMenu' id='leftNavMenu1'>$maincat";
					  
					  
					   $qry2="select * from master_category where parent_id='$catid'";
		                $rs2 = $this->db->get_results($qry2,ARRAY_A);
							for($k=0;$k<count($rs2);$k++)
							{
								 $subcatid=$rs2[$k]['category_id'];
								 $subcat=$rs2[$k]['category_name'];
								  $cat=$maincat."<li><a href='{smarty.const.SITE_URL}/{SNAME}/index.php?mod=product&pg=list&act=list&base_cat=343&cat_id=250'>$subcat</a></li></ul>";
							}
				  }
		  }*/
		  
		  global $store_id;
		
		//exit;
		if($store_id){
		
			
			 $sql	=	"SELECT T1.*, T3.cms_type AS cms_type,T2.content as content FROM cms_link AS T1 LEFT JOIN cms_page AS T2 ON T2.menu_id = T1.menu_id LEFT JOIN store_cms AS T3 ON T3.cms_id = T1.id WHERE T1.area = '$area' and T3.store_id = '$store_id' order by T1.position ";
			
		} else {
		
		 	$sql	=	"SELECT T1.*, T3.cms_type AS cms_type,T2.content as content FROM cms_link T1 LEFT JOIN cms_page T2 ON T2.menu_id = T1.menu_id LEFT JOIN store_cms T3 ON T3.cms_id = T1.id WHERE T1.area = '$area' and T3.store_id = '0' order by T1.position ";
		}
		//$sql		= "SELECT * FROM cms_link WHERE area='$area' ORDER BY position";
		//echo $sql;		
	//	exit;
		
        $rs = $this->db->get_results($sql);
		//echo $rs[0]->id;
		
		//echo $rs['cms_type'];
		
		
		/* 
			####  _%rediectUrl%_ Method
		*/
		$this->callrediectUrl ( $rs );
		/* 
			####  _%rediectUrl%_ Method
		*/


        return $rs;
		  
		  
  }
	
    function linkList($area, $store="",$fullpath="") {
		global $store_id;
		global $global;
		
		
		/* Getting Limit of Area */
		if(trim($area) &&  $_SESSION['CURR_PANEL'] == 'client') {
			$LIMIT_ARRAY = $this->getLimitLinkArea($area);
			$limit_display = $LIMIT_ARRAY['limit_display'];
		}
		
		
		
		if($store_id){
		
			//  $sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='$store_id' ORDER BY a.position";
			//$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY a.position";
			 if ($limit_display>0)
			 $sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY RAND() limit $limit_display";
			 else
			 $sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY a.position";
			/*$sql="Select cms_link.id,cms_link.menu_id,cms_link.title,cms_link.link_image,cms_link.link_oimage,cms_link.url,cms_link.`position`,cms_link.area,cms_link.window,cms_link.active,
				cms_link.type_link,cms_link.description From store_cms Inner Join cms_link ON cms_link.menu_id = store_cms.cms_id
				Where
				cms_link.area = '$area' AND
				store_cms.store_id = '$store_id'";*/

		
		} else {
			if ($limit_display>0)
			 $sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0'  ORDER BY RAND() limit $limit_display";
			else
		 		$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0'  ORDER BY a.position";
		}
		//$sql		= "SELECT * FROM cms_link WHERE area='$area' ORDER BY position";
		//echo $sql;		
		//exit;
	//exit;
	
        $rs = $this->db->get_results($sql);
		//print_r($rs);
		//echo $rs[0]->id;
		
		//echo $rs['cms_type'];
		
		
		/* 
			####  _%rediectUrl%_ Method
		*/
		$this->callrediectUrl ( $rs );
		/* 
			####  _%rediectUrl%_ Method
		*/
		
		// this section added by robin 17-1-2008
		// This is for getting store links at checkout time
		if ($store)
		{
			for($i=0;$i<count($rs);$i++)
			{
			if(stristr($_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF'], 'thepersonalizedgift'))
				$rs[$i]->url=SITE_URL . '/' .$store."/".$rs[$i]->url;
			else	
				$rs[$i]->url=SITE_URL . '/' .$rs[$i]->url;
			
			}
		}
		
		if($fullpath==1)
		{
			for($i=0;$i<count($rs);$i++)
			{
			$rs[$i]->url=SITE_URL . '/' .$rs[$i]->url;
				
			}
		}


        return $rs;
    }
	
	//function to identify link is content or menu link
	//Ratheesh  13-Nov-2007
	  function linkListmenu($area) {
			$sql	=	"SELECT T1.*, T3.type_link AS cms_type_link,T2.content as content FROM cms_link T1 LEFT JOIN cms_page T2 ON T2.menu_id = T1.menu_id LEFT JOIN cms_menu T3 ON T3.id = T1.menu_id WHERE T1.area = '$area' order by T1.position ";
        	$rs = $this->db->get_results($sql);
			$this->callrediectUrl ( $rs );
		/* 
			####  _%rediectUrl%_ Method
		*/

	   return $rs;
   }
	
	// updating all the stores with default values 20-07-07
	function updateStore($area)
	{
		$area2=str_replace("store_","",$area);
		// deleting already existing menus for the store
		$delres		=	mysql_query("select * from cms_link where area='$area2'");
		while($delrow=mysql_fetch_array($delres))
		{
			$store_cms_id	=	$delrow["id"];
			$this->db->query("DELETE FROM store_cms WHERE store_id !='0'  and cms_id='$store_cms_id'");
		}
			
		$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY a.position";
		
		$res	=	mysql_query($sql);
		while($row=mysql_fetch_array($res))
		{
			$type		=	$row['cms_type'];//print($type);exit;
			//$cms_id		=	$row['id']; 
			$title		=	$row['title']; 
			$url		=	$row['url'];
			$position	=	$row['position'];
			$window		=	$row['window'];
			$active		=	"Y";
			$newarea	=	str_replace("store_","",$area);
			$cmsarray 			= 	array("title"=>$title,
			"url"=>$url,
			"position"=>$position,
			"area"=>$newarea,
			"window"=>$window,
			"active"=>$active);
			$this->db->insert("cms_link", $cmsarray);					
			$cmsId			= 	$this->db->insert_id;//print($cmsId);exit;
			
			$res2	=	mysql_query("select  id from store where active='Y'");
			while($row2=mysql_fetch_array($res2))
			{
					$store_id	=	$row2['id'];
					$storearray 			= 	array("store_id"=>$store_id,
					"cms_id"=>$cmsId,
					"cms_type"=>$type);
					$this->db->insert("store_cms", $storearray);					
			}
		}
	}
	
	 function homeLink($area) {
	 
	// $sql= "select * from cms_page where id='44'";
	 /*   $sql= "select * from cms_page where menu_id='78'";
	  $rs = $this->db->get_row($sql,ARRAY_A);
	  return $rs;*/
	  global $store_id;
		
		if($store_id){
		
		 $sql	=	"SELECT * from store where  id='$store_id'";
			 $rs = $this->db->get_row($sql, ARRAY_A);
		}else{
		
		
		//$sql="SELECT a.*,b.area from cms_page a,cms_link b where b.area='home' and a.title=b.title and b.position='0'";
		//$sql="SELECT a.*,b.area,c.id from cms_page a,cms_link b,cms_menu c where b.area='home' and a.menu_id =c.id and b.position='0'";
       //  $sql="SELECT a.* ,b.* from cms_page a,cms_link b  where b.area='home' and a.menu_id=b.menu_id and b.position='0'";
		 $sql="SELECT a.* ,b.menu_id,b.area,b.position from cms_page a,cms_link b  where b.area='home' and a.menu_id=b.menu_id and b.position='0'";

		
		
		
       // $rs = $this->db->get_results($sql);
	   	$rs = $this->db->get_row($sql, ARRAY_A);
		$pos= $rs['position'];
		if($pos==""){
		
		 $sql="SELECT a.* ,b.menu_id,b.area,b.position from cms_page a,cms_link b  where b.area='home' and a.menu_id=b.menu_id ORDER BY b.id  LIMIT 0,1";
		
         //$sql="SELECT a.*,b.area from cms_page a,cms_link b where b.area='home' and a.title=b.title";
	      $rs = $this->db->get_row($sql, ARRAY_A);
		  }
		  }
		//exit;
		//print_r($rs);
		
		
		//$this->callrediectUrl ( $rs );
		
        return $rs;
	 }
	

    function linkGet ($id) {
    	$rs = $this->db->get_row("SELECT * FROM cms_link WHERE id='{$id}'", ARRAY_A);
    	return $rs;   
    }
	 function linkIdGetByUrl ($id,$area) {
	 	$sql="SELECT id FROM cms_link WHERE url='{$id}' and area='{$area}'";
    	$rs = $this->db->get_row($sql, ARRAY_A);
		//print_r($rs);exit;
    	return $rs;   
    }
 function checkCms($id) {
	 	$sql="SELECT * FROM cms_menu WHERE seo_url='{$id}' ";
    	$rs = $this->db->get_row($sql, ARRAY_A);
		if($rs){
			return 1;  
		}else{
			return 0;  
		}
    	 
    }
    function linkDelete ($id) {
        $this->db->query("DELETE FROM cms_link WHERE id='$id'");
		$this->db->query("DELETE FROM store_cms WHERE cms_id='$id'");
    }

    function sortLinks($linkOrder) {
    	if($linkOrder) {
    		foreach ($linkOrder as $pos=>$link) {
    			$this->db->update("cms_link", array("position"=>$pos), "id=$link");
    		}
    	}
    }

    function sectionMenuCombo () {
		global $store_id;
        $sql		= "SELECT id, name FROM cms_section WHERE 1";
        $rs			= $this->db->get_results($sql);
        $arr = array();
        if ($rs) {
        	foreach ($rs as $row) {			
			if($store_id){
				$query	=	"SELECT a.id,a.name FROM cms_menu a,store_cms b WHERE a.id=b.cms_id AND b.cms_type='M' AND b.store_id='$store_id' AND a.section_id ='$row->id' ";
			}else{				
				$query	= 	"SELECT a.id,a.name FROM cms_menu a LEFT JOIN store_cms b ON(a.id=b.cms_id)  WHERE b.cms_id is NULL AND a.section_id ='$row->id'";
			}	
			//$menurs			= 	$this->db->get_results("SELECT id, name FROM cms_menu WHERE section_id='$row->id'");
        		$menurs			= 	$this->db->get_results($query);
				if ($menurs) {
        			foreach ($menurs as $menurow) {
        				$arr[$row->name][$menurow->id] = $menurow->name;
        			}
        		}
        	}
        }
        return $arr;
    }

    function menuGetByURL ($url) {
	
	global $store_id;
		if($store_id){
			$sql = "Select
					c.id,
					c.section_id,
					c.menu_id,
					c.name,
					c.seo_url,
					c.`position`,
					c.active,
					c.type_link,
					c.type_tip
					From
					cms_menu AS c
					Inner Join cms_page AS p ON c.id = p.menu_id AND p.post_admin = '' AND c.seo_url = '$url'
					Inner Join store_cms s ON p.id = s.cms_id AND s.store_id ='$store_id'
					";
		}else
		{
    	$sql ="SELECT c.* FROM cms_menu c inner join cms_page p on c.id=p.menu_id AND p.post_admin='admin' AND seo_url = '$url'";;
		}
		//echo $sql;
		        $rs = $this->db->get_row($sql, ARRAY_A);
        return $rs;
    }
	//to get cms pages when menu id is given
	function GetCMSpage($menu_id)
	{
	$sql= "SELECT * FROM cms_page WHERE menu_id = '$menu_id' ORDER BY post_date DESC";
	$rs = $this->db->get_results($sql,OBJECT);
	return $rs;
	}
	// Get Page Title
	function pageTitle($title1,$title2)
	{
		if($title1)
		{
			
			$sql = "SELECT title FROM page_title WHERE title_key ='$title1' AND act = '$title2'";
			//print $sql;
			$rs=$this->db->get_row($sql,ARRAY_A);

			if(count($rs) > 0)
			{
				return $rs['title'];
			}
			else
			{
				$sql = "SELECT title FROM page_title WHERE title_key ='$title1'";
				$rs=$this->db->get_row($sql,ARRAY_A);
				return $rs['title'];

			}
		}
	}



	function GetCMSTIPpage()
	{
	#$sql= "SELECT * FROM cms_page ORDER BY post_date DESC";
	$sql= "SELECT * FROM cms_page,cms_menu WHERE cms_menu.type_tip = 'Y' AND cms_page.menu_id = cms_menu.id ORDER BY RAND() LIMIT 1";
	$rs = $this->db->get_row($sql,ARRAY_A);
	return $rs;
	}

	function GetCMSTIPpage1()
	{
		global $store_id;
		if($store_id){
			$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='$store_id' ORDER BY RAND() LIMIT 1";
			//$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY a.position";
		}else{
			$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='$area' AND b.store_id='0' ORDER BY RAND() LIMIT 1";
		}
		
		//$sql		= "SELECT * FROM cms_link WHERE area='$area' ORDER BY position";
		//echo $sql;		
        $rs = $this->db->get_row($sql,ARRAY_A);
        return $rs;
  }
  # Function to get Latest 5 news and events section 
  # Author Jipson.
  # Dated on : 30/07/07...
  function GetNews($sce_id){
  $sql="SELECT m.id AS 'menuid', m.seo_url AS 'url', p.title AS 'title', p.content AS 'content', p.id AS 'id' FROM `cms_menu` m, `cms_page` p WHERE m.id = p.menu_id AND m.section_id ='$sce_id' ORDER BY p.id DESC LIMIT 0 , 5";
 // echo $sql;
 // exit;
	$rs = $this->db->get_results($sql,OBJECT);
	return $rs;
  }


	function storeGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	
	
	function callrediectUrl ( $TLinks )	{
		global $store_id;
		
		if ( $TLinks ){
			foreach($TLinks as $keys=>$vals)
			{
			
			
					if ( $vals->url== '_%redirectURL%_' )     
					{  
						if ($store_id) {
							$STORE_DETAILS		=	$this->storeGet ($store_id);
							if ( trim( $STORE_DETAILS['redirect_url'] ) )
							$TLinks[$keys]->url = $STORE_DETAILS['redirect_url'];
							else
							$TLinks[$keys]->url = SITE_URL . '/' .  $STORE_DETAILS['name'] . '/';
						} else {
							$TLinks[$keys]->url = SITE_URL . '/';
						}
					}	
			}
		}			
		
	
	}
	
	
	
	
	/**
	 * @description		The following method returns the left menu for the store
	 *
	 *
	 * @author 	vimson@newagesmb.com
	 * Modified by Retheesh for integrating SEO URL
	 */
	function getLeftMenu($MenuPosition, $StoreName, $SelBaseCatId)
	{
		$this->LeftMenuText		=	'';

		$Qry1		=	"SELECT 
							T1.*,
							T2.cms_type
						FROM cms_link AS T1, store_cms AS T2 
						WHERE T1.id = T2.cms_id 
						AND (T2.cms_type='L' OR T2.cms_type = 'C')
						AND T1.area='$MenuPosition' AND T2.store_id='0' ORDER BY T1.title";
		$Rows1		=	$this->db->get_results($Qry1,ARRAY_A);

		$this->LeftMenuText		.=	'<div id="leftNavContainer" class="leftNavContainer" style="width:px;" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">';
		
		$Index	=	1;
		foreach($Rows1 as $Row1) {
			$LinkUrl	=	$Row1['url'];
			
			if(strpos($LinkUrl, 'sess=') === false) {
				$CatidPos		=	strpos($LinkUrl, 'cat_id');
				$CatId			=	(int)substr($LinkUrl, $CatidPos + 7);
			} else {
				$SessPos		=	strpos($LinkUrl, 'sess=');
				$Base64EncdStr	=	substr($LinkUrl, $SessPos + 5);
				$Base64DecdStr	=	base64_decode($Base64EncdStr);
				$IndexStr		=	substr($LinkUrl, 0, $SessPos);
				$LinkUrl		=	$IndexStr.$Base64DecdStr;
				$CatidPos		=	strpos($LinkUrl, 'cat_id');
				$CatId			=	(int)substr($LinkUrl, $CatidPos + 7);
			}
			$BaseCatId	=	$CatId;
				
			
			if($SelBaseCatId == $CatId)
				$this->CategorySelectionJs	=	"leftNavExpand(document.getElementById('leftNavMenu$Index'));";
			
			if($CatId == 0)
				continue;
			
			$Qry2				=	"SELECT category_name FROM master_category WHERE category_id = '$CatId' ";
			$Row2				=	$this->db->get_row($Qry2, ARRAY_A);
			$CategoryName		=	$Row2['category_name'];
			
			
			//$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenu" id="leftNavMenu'.$Index.'">'.$CategoryName;
			
			$ChildNodeExists	=	$this->whetherChildCategoryExists($CatId);
			//=======================
			if($ChildNodeExists === TRUE) {
				$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenu" id="leftNavMenu'.$Index.'">'.$CategoryName;
			} else {
			//$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenuNCat" id="leftNavMenu'.$Index.'"><li><a href="'.SITE_URL.'/'.$StoreName.'/index.php?mod=product&pg=list&act=list&base_cat='.$BaseCatId.'&cat_id='.$InnrCategoryId.'" target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'. '[-]&nbsp;' .$CategoryName. '</a></li></ul>';
				if (SEO_URL==1)
				{
					$link_url = makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&cat_id=$BaseCatId");
					$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenuNCat" id="leftNavMenu'.$Index.'"><li><a href='.$link_url.' target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'. '[-]&nbsp;' .$CategoryName. '</a></li></ul>';
				}
				else 
				{
					$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenuNCat" id="leftNavMenu'.$Index.'"><li><a href="'.SITE_URL.'/'.$StoreName.'/index.php?mod=product&pg=list&act=list&cat_id='.$BaseCatId.'" target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'. '[-]&nbsp;' .$CategoryName. '</a></li></ul>';
				}	
          
			}
			//========================
			
			
			if($ChildNodeExists === TRUE) {
				$Qry3		=	"SELECT category_id, category_name,parent_id FROM master_category WHERE parent_id = '$CatId' order by category_name";
				$Rows3		=	$this->db->get_results($Qry3,ARRAY_A);
				
				if(is_array($Rows3)) {
					foreach($Rows3 as $Row3) {
						$InnrCategoryId		=	$Row3['category_id'];
						$InnrCategoryName	=	$Row3['category_name'];
						
						if (SEO_URL==1)
						{
							$link_url = makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&cat_id=$BaseCatId&cat_id=".$InnrCategoryId);
						}
						else 
						{
							$link_url = SITE_URL.'/'.$StoreName.'/index.php?mod=product&pg=list&act=list&base_cat='.$BaseCatId.'&cat_id='.$InnrCategoryId;
						}
							$this->LeftMenuText		.=	'<li><a href="'.$link_url.'" target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'.$InnrCategoryName.'</a>';
							$this->setChildCategories($InnrCategoryId, $BaseCatId, $StoreName);
							$this->LeftMenuText		.=	'</li>';
							
					}
				}
			} else if($ChildNodeExists === FALSE) {
			$InnrCategoryName="";
			//$this->LeftMenuText	.=	'<ul class="leftNavExpandableMenu" id="leftNavMenu'.$Index.'">'.$CategoryName;

				//$this->LeftMenuText		.=	'<li><a href="'.SITE_URL.'/'.$StoreName.'/index.php?mod=product&pg=list&act=list&base_cat='.$BaseCatId.'&cat_id='.$InnrCategoryId.'" target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'.$InnrCategoryName.'</a></li>';
				//$this->LeftMenuText		.=	'<li style="background-color:#2e384f ">No Item Found </li>';
			}	
			if($ChildNodeExists === TRUE) {
			$this->LeftMenuText	.=	'</ul>';
			}
			$Index++;
			
		}
	
		$this->LeftMenuText		.=	'</div>';

		return $this->LeftMenuText;
		
	}
	
	
	/**
	 * @description	The following method returns the code for javascript category selection in case a particular category is selected
	 *
	 *
	 *
	 * @author vimson@newagesmb.com
	 */
	function getCategoryselectionJS()
	{
		return $this->CategorySelectionJs;
	}
	
	
	/**
	 * @description	The following method is used for setting the child categories related with a category for left menu 
	 *
	 *
	 *
	 * @author vimson@newagesmb.com
	 */
	function setChildCategories($CatId, $BaseCatId, $StoreName)
	{
		$Qry1		=	"SELECT category_id, category_name,parent_id FROM master_category WHERE parent_id = '$CatId' order by category_name ";
		$Rows1		=	$this->db->get_results($Qry1,ARRAY_A);
		
		if(count($Rows1) == 0)
			return;
		else
			$this->LeftMenuText	.=	'<ul>';
		
		foreach($Rows1 as $Row1) {
			$CategoryId		=	$Row1['category_id'];
			$CategoryName	=	$Row1['category_name'];
			
			if (SEO_URL==1)
			{
				$link_url = makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&cat_id=$BaseCatId&cat_id=".$CategoryId);
			}
			else 
			{
				$link_url = SITE_URL.'/'.$StoreName.'/index.php?mod=product&pg=list&act=list&base_cat='.$BaseCatId.'&cat_id='.$CategoryId;
			}

			$this->LeftMenuText		.=	'<li><a href="'.$link_url.'" target="_top" onMouseOver="expandFrame();" onMouseOut="reduceFrame();">'.$CategoryName.'</a>';
			
			if($this->whetherChildCategoryExists($CategoryId) === TRUE)
				$this->setChildCategories($CategoryId, $BaseCatId, $StoreName);

			$this->LeftMenuText		.=	'</li>';
		}
		
		$this->LeftMenuText	.=	'</ul>';
	}
	
	
	
	/**
	 * @description	The following method checks whether the child category exists for a particular category
	 *
	 *
	 * @author	vimson@newagesmb.com
	 */
	function whetherChildCategoryExists($CategoryId)
	{
	
	
		$Qry		=	"SELECT COUNT(*) AS TotCount FROM master_category WHERE parent_id = '$CategoryId'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$TotCount	=	$Row['TotCount'];
  
		if($TotCount > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	
	
	/**
	 * @description  The following method used for finding the level of category
	 *
	 *
	 * @author	vimson@newagesmb.com
	 */
	function getCategoryLevel($CatId)
	{
		$Level	=	0;
		
		$flag	=	0;
		
		do {
			$Qry			=	"SELECT parent_id FROM master_category WHERE category_id = '$CatId'";
			$Row			=	$this->db->get_row($Qry, ARRAY_A);
			$ParentCatId	=	$Row['parent_id'];
			$CatId			=	$ParentCatId;
			$Level++;
		} while($ParentCatId != 0);
		
		
		return $Level;
		
	}
//added by salim. start	
function GetCMSpageById($menu_id)
	{
		
		$sql= "SELECT * FROM cms_page WHERE id = '$menu_id' ORDER BY post_date DESC";
		$rs = $this->db->get_results($sql,OBJECT);
		return $rs;
	
	}

function ModuleGet($param)
	{
			$sql	=	"SELECT m.id,name,m.active,c.content FROM module m 
						LEFT JOIN cms_page c ON m.description = c.id 
						WHERE m.".$param." = 'Y'
						ORDER BY active DESC";
			$rs 	=	$this->db->get_results($sql, ARRAY_A);
			return $rs;
					
	}
function DBGet($id)
	{
				
			$rs 	=	$this->db->get_row("SELECT id,name,description 
											FROM web_db 
											WHERE product_id ='$id'", ARRAY_A);			
			return $rs;
		
	}
function UpdateModule($field_arr,$id)	
	{
		
		$this->db->update("module", $field_arr, "id='$id'");
	
	}
	
function sitedata($field_arr)	
	{
		
		$this->db->insert("website_created", $field_arr);
	
	}
function maxvalue($table,$field)
	{

		$rs	= 	$this->db->get_row("SELECT MAX($field) as maxval FROM $table");
		return $rs;

	}
function GetMenusByModule()
	{

		$rs	=	$this->db->get_results("SELECT  m.name, mm.id, mm.menu, mm.active
										FROM module m
										INNER JOIN module_menu mm ON m.id = mm.module_id
										WHERE m.active = 'Y'
										ORDER BY m.id ASC ", ARRAY_A);
		return $rs;
		 
	}	
function UpdateModuleMenu($field_arr,$id)	
	{
		
		$this->db->update("module_menu", $field_arr, "id='$id'");
	
	}
function GetFieldsByMenu($menu_id)
	{
		$rs = 	$this->db->get_results("SELECT id,name,active FROM module_fields 
										WHERE menu_id ='$menu_id'", ARRAY_A);
		return $rs;
	}
function UpdateModuleMenuFields($field_arr, $id)
	{
	
		$this->db->update("module_fields", $field_arr , "id='$id'");
	
	}
//end
	/**
      * Author   : Vinoy
      * Created  : 
      * Modified : 19/May/2008 By Salim
      * Modification : Added two filters to the query.And different queries for store and main store.
      */
	function getcmslinktitle($data){
		global $store_id;		 			
		if($store_id){	
			$sql="SELECT * FROM `cms_link` WHERE url = '$data.php' AND area LIKE '%store%' AND type_link IS NOT NULL";
		}
		else{
			 $sql="SELECT * FROM `cms_link` WHERE url = '$data.php' AND area NOT LIKE '%store%' AND type_link IS NOT NULL";
		}
	 
	$rs = $this->db->get_results($sql, ARRAY_A);
	 //print_r($rs);exit;
	return $rs;
	}
	
	
	// list the section ehere images to be displayed
	 function imagesectionCombo () {
		$sql	=	"SELECT * FROM cms_image_area WHERE active='Y'";		
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['display'] = $this->db->get_col("", 2);
        return $rs;
    }
	 function imageList($section_id) {
		$sql	=	"SELECT * FROM cms_image WHERE image_area_id =$section_id";		
		 $rs = $this->db->get_results($sql);
		 $rs['section_id'];
        return $rs;
    }
	 function imageListALL() {
		$sql	=	"SELECT * FROM cms_image";		
		 $rs = $this->db->get_results($sql);
		 $rs['section_id'];
        return $rs;
    }
	function imageDetail($id) {
		$sql	=	"SELECT * FROM cms_image WHERE id =$id";		
		 $rs = $this->db->get_results($sql);
		 $rs['section_id'];
        return $rs;
    }
	function imageAddEdit (&$req) {
	    extract($req);
		
		if($section_id==0){
			$message = "Please select where the image to be implemented";
		}elseif($fname=="") {
            $message = "Image is required";
        } else {
				$path_parts 	= 	pathinfo($fname);
				$file_type=$path_parts['extension'];
				
				
            $array = array("image_area_id"=>$section_id ,"title"=>$title, "image"=>$file_type, "active"=>$active);
            if($id) {
                $array['id'] = $id;
                $this->db->update("cms_image", $array, "id='$id'");
            } else {
			      $this->db->insert("cms_image", $array);				
                $id = $this->db->insert_id;
			}
			if ($fname){
				$dir			=	SITE_PATH."/modules/cms/images/dynamic/";
				$thumbdir=$dir."thumb/";
				
					$new_img= $id.".".$file_type;
					uploadImage($_FILES['cms_image'],$dir,$new_img,1);
					chmod($dir.$new_img,0777);
					thumbnail($dir,$thumbdir,$new_img,100,100,"",$new_img);
					chmod($thumbdir.$new_img,0777);
					
					if($req['photo1']!=""){
						$thumbdir			=	SITE_PATH."/modules/cms/images/dynamic/photo1/";
						list($height,$width) = explode(",",$req['photo1']);
						if($height>0 AND $width>0){
							$new_img= $id.".".$file_type;
							thumbnail($dir,$thumbdir,$new_img,$height,$width,"",$new_img);
							chmod($thumbdir.$new_img,0777);
						}
					}
					if($req['photo2']!=""){
						$thumbdir			=	SITE_PATH."/modules/cms/images/dynamic/photo2/";
						list($height,$width) = explode(",",$req['photo2']);
						if($height>0 AND $width>0){
							$new_img= $id.".".$file_type;
							thumbnail($dir,$thumbdir,$new_img,$height,$width,"",$new_img);
							chmod($thumbdir.$new_img,0777);
						}
					}
					if($req['photo3']!=""){
						$thumbdir			=	SITE_PATH."/modules/cms/images/dynamic/photo3/";
						list($height,$width) = explode(",",$req['photo3']);
						if($height>0 AND $width>0){
							$new_img= $id.".".$file_type;
							thumbnail($dir,$thumbdir,$new_img,$height,$width,"",$new_img);
							chmod($thumbdir.$new_img,0777);
						}
					}
					if($req['photo4']!=""){
						$thumbdir			=	SITE_PATH."/modules/cms/images/dynamic/photo4/";
						list($height,$width) = explode(",",$req['photo4']);
						if($height>0 AND $width>0){
							$new_img= $id.".".$file_type;
							thumbnail($dir,$thumbdir,$new_img,$height,$width,"",$new_img);
							chmod($thumbdir.$new_img,0777);
						}
					}
					if($req['photo5']!=""){
						$thumbdir			=	SITE_PATH."/modules/cms/images/dynamic/photo5/";
						list($height,$width) = explode(",",$req['photo5']);
						if($height>0 AND $width>0){
							$new_img= $id.".".$file_type;
							thumbnail($dir,$thumbdir,$new_img,$height,$width,"",$new_img);
							chmod($thumbdir.$new_img,0777);
						}
					}
					
				}
			
			return true;
        }
        return $message;
    }
	
	function imageDelete ($id) {
		$rs =$this->imageDetail($id);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/thumb/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/photo1/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/photo2/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/photo3/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/photo4/".$id.".".$rs[0]->image);
		@unlink(SITE_PATH."/modules/cms/images/dynamic/photo5/".$id.".".$rs[0]->image);
        $this->db->query("DELETE FROM cms_image WHERE id='$id'");
   }
   function homeimage($area) {
		if($area!=""){
			
			$sql	=	"SELECT a.* from cms_image a,cms_image_area b where b.value='$area' and a.image_area_id=b.id";
			 $rs = $this->db->get_row($sql);
			
		} 
    return $rs;
   }
   
   


	/**
	* The following code written for getting category id 
	* @author vipin@newagesmb.com
	*/
	function getLinksAndCatIds($CenterLinks)
	{
		$LinksArray		=	array();
		
		if(count($CenterLinks) == 0)
			return $LinksArray;
		
		$ArrIndx	=	0;
		foreach($CenterLinks as $CenterLink) {
			$LinksArray[$ArrIndx]	=	$CenterLink;
			$url					=	$CenterLink->url;
			$pos	=	strpos($url,'cat_id');
			
			if($pos != FALSE)
				$catid	=	substr($url,$pos+7);
			else
				$catid	=	'';
				
			$LinksArray[$ArrIndx]->cat_id	=	$catid;
			$ArrIndx++;
		}
		return $LinksArray;
	}
	/**
	* The following code written for getting ......
	*content of a cms page using seourl............ 
	* @author Jipson Thomas........................
	*/
   function getContent($url){
		$sql="select cp.content from cms_page cp inner join cms_menu cm on cp.menu_id=cm.id where cm.seo_url='$url'";
		$rs = $this->db->get_row($sql,ARRAY_A);
	//print_r($rs);exit;
		return $rs;
   }
   
   function template()
   {
	 $sql	=	"SELECT  possible_values FROM config WHERE field='curr_tpl'";
	 $rs = $this->db->get_row($sql,ARRAY_A);
	 $values = $rs['possible_values'];
	 return $values;
		   
   }
function GetCMSpageByTitle($title)
	{
		
		$sql= "SELECT * FROM cms_page WHERE title = '$title'";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	
	}
	/**
	 * @inserting the order status mail details to the customer
	 *
	 *
	 * @author	shinu@newagesmb.com
	 */
	function emailDetailsAddEdit(&$req,$drop_downId)
	{
		extract($req);
		if($drop_downId != 0 )
		{
			$this->db->query("DELETE FROM email_config WHERE drop_down_id='$drop_downId'");
			$temp_name	=	"order_status_mail_".$drop_downId;
			$array = array("subject"=>$subject, "body"=>$body,"bit_type"=>"1",
							"name"=>$temp_name,"use_template"=>"Y","drop_down_id"=>$drop_downId);
			$this->db->insert("email_config", $array);
		}
		return true;
	}
	function sortDropdownLinks($linkOrder) {
    	if($linkOrder) {
    		foreach ($linkOrder as $pos=>$link) {
    			$this->db->update("drop_down", array("position"=>$pos), "drop_down_id=$link");
    		}
    	}
    }
	function getFeatured()
	{
	 $sql= "SELECT * FROM config WHERE field ='featured' and value='Y'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	
	}
  function pageGetByName ($name, $output=ARRAY_A)
   {
	
        $sql		= "SELECT *, DATE_FORMAT(post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page WHERE title = '$name'";
        $rs = $this->db->get_row($sql, $output);
        return $rs;
    }
	
	function getCmsMenuPermisssion($store_id,$menu_id)
	{
		 $sql="SELECT name,storeowner_edit_pages FROM cms_menu a LEFT JOIN store_cms b ON a.id = b.cms_id WHERE b.store_id = '$store_id' AND b.cms_id ='$menu_id' AND b.cms_type = 'M'";
		 $rs = $this->db->get_row($sql,ARRAY_A);
       	 return $rs;	  
	}
	
	/**
  	 * This function used to update all the store pages with super admin cms pages
  	 * Author   : Adasrh	
  	 * Created  : 7/Aug/2008
  	 * 
  	 */
	function updateAllStore($menu_id)
	{
		$this->db->query("DELETE FROM store_cms WHERE store_id !='0'  and cms_id='$menu_id' and cms_type='M'");
		$res2	=	$this->db->get_results("select  id from store ",ARRAY_A);
		foreach($res2 as $k=>$value)
		{
			$store_id	=	$value['id'];
			$storearray 			= 	array("store_id"=>$store_id,"cms_id"=>$menu_id,"cms_type"=>'M');
			$this->db->insert("store_cms", $storearray);					
		}
		
		$resPages=	$this->db->get_results("SELECT id FROM cms_page WHERE post_admin!='admin' and menu_id='$menu_id'",ARRAY_A);
		foreach($resPages as $pagekey=>$pageval)
		{
			$id=$pageval['id'];
			$this->db->query("DELETE FROM cms_page WHERE id='$id'");
			$this->db->query("DELETE FROM store_cms WHERE store_id !='0'  and cms_id='$id' and cms_type='P'");
		}
		foreach($res2 as $skey=>$sval)
		{
			$this->insertDefaltPages($menu_id,$sval['id']);
		}
		return true;
	}
	
	function insertDefaltPages($menuid, $store_id)
	{
				$sql_page= "select * from cms_page where active='Y' and post_admin='admin' and menu_id=$menuid";
				$rs = $this->db->get_results($sql_page);
				
				if($rs)
				{
					foreach ($rs as $results)
					{
						$insert_array=array();
	
						$insert_array=array("menu_id"=>$results->menu_id,"title"=>$results->title,"short_content"=>mysql_real_escape_string($results->short_content),"content"=>mysql_real_escape_string($results->content),"post_admin"=>'',"post_date"=>$results->post_date,"position"=>$results->position,"active"=>$results->active,"meta_description"=>$results->meta_description,"meta_keywords"=>$results->meta_keywords,"page_name"=>$results->page_name);
						
						$this->db->insert("cms_page",$insert_array);
						$cms_page_id = $this->db->insert_id;
						$cms_type	=	"P";
						$this->db->insert("store_cms", array("store_id"=>$store_id, "cms_id"=>$cms_page_id, "cms_type"=>$cms_type));
					}
				}
				
		
	}
	function getPageDet($pagename)
	{
		  $sql		= "SELECT *, DATE_FORMAT(post_date, '".$this->config['date_format']." ".$this->config['time_format']."') AS post_date_f FROM cms_page WHERE page_name = '$pagename'";
      $rs = $this->db->get_results($sql,OBJECT);
        return $rs;
	}
	
	
}

?>