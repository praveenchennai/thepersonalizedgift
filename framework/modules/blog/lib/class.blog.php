<?php
/**
 *  Blog
 *
 * @author Ajith
 * @package defaultPackage
 */
class Blog extends FrameWork {
  var $width;
  var $page_type;
  var $page_align;
   
  function Blog($width="",$page_type="",$page_align="") {
	$this->width = $width;
	$this->page_type = $page_type;
	$this->page_align=$page_align;
    $this->FrameWork();
  }
/**
	 * Setting the look and feel of blog page
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
  function sttingBlogpage (&$req,$file,$tmpname) {  
    extract($req);	
	if($page_type=='P'){
		$page_width=$page_width."%";
	}	if ($file){							
		$dir=SITE_PATH."/modules/blog/images/template/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1);	
	}
	if($file)
	{
	$pic=$file;
	}else{$pic=$picture;}
	  if(!trim($blog_id)) {
	//code by robin
     			$user_id=$_SESSION['memberid'];
				if ($this->config["inner_change_reg"]=="yes")
						{
						$array = array("user_id"=>$user_id ,"active"=>'y',"create_date" => $create_date,"blog_name"=>$blog_name,"blog_description"=>$blog_description);
						}else
						{
					$array = array("user_id"=>$user_id ,"active"=>'y',"create_date" => $this->config['date_format']);	
						}
				
				$this->db->insert("blog_master", $array);
				$blog_id = $this->db->insert_id;
				
				
				$arrayPagewidth 	= 	array("blog_id"=>$blog_id,"page_width"=>$page_width,"page_type"=>$page_type,"page_align"=>$page_align);	
				$arrayHeadersetting =	array("blog_id"=>$blog_id,"header_type"=>$header_type,"site_txt"=>$site_txt,"stxt_clr"=>$stxt_clr,
										"stxt_font"=>$stxt_font,"stxt_fontsize"=>$stxt_fontsize,"border_clr"=>$border_clr,"inter_clr"=>$inter_clr,"tag_txt"=>$tag_txt,
										"tag_clr"=>$tag_clr,"tag_font"=>$tag_font,"tag_fontsize"=>$tag_fontsize,"own_header"=>$own_header);		
				$arrayPagebg 		= 	array("blog_id"=>$blog_id,"bgcolor"=>$bgcolor,"picture"=>$pic,"position"=>$position,"repeat"=>$repeat,"scroll"=>$scroll);
				$arrayTextlink 		= 	array("blog_id"=>$blog_id,"txtfont"=>$txtfont,"txtfontsize"=>$txtfontsize,"txtclr"=>$txtclr,"nrm_clr"=>$nrm_clr,
										"nrm_uline"=>$nrm_uline,"act_clr"=>$act_clr,"act_uline"=>$act_uline,"visit_clr"=>$visit_clr,"visit_uline"=>$visit_uline,"hover_clr"=>$hover_clr,"hover_uline"=>$hover_uline);
				$arrayleftModule 	= 	array("blog_id"=>$blog_id,"brd_clr"=>$brd_clr,"brd_style"=>$brd_style,"brd_pixel"=>$brd_pixel,"title_bgclr"=>$title_bgclr,
										"title_txt_clr"=>$title_txt_clr,"title_align"=>$title_align,"intr_bgclr"=>$intr_bgclr,"intr_txt_clr"=>$intr_txt_clr,"intr_align"=>$intr_align);
				$arrayMusic 		= 	array("blog_id"=>$blog_id,"filepath"=>$filepath,"loop_music"=>$loop_music);
				$arrayMainBody		= 	array("blog_id"=>$blog_id,"mbcolor"=>$mbcolor,"sbcolor"=>$sbcolor,"transperant"=>$transperant,"txtfont"=>$hfont,"txtfontsize"=>$bfontsize,"color"=>$headercolor);
				if($hidetrim_clr){				
					$arraySearch 	= 	array("blog_id"=>$blog_id,"view"=>$view,"brd_clr"=>$searchbrd_clr,"intr_bgclr"=>$searchintr_bgclr,"trim_clr"=>$hidetrim_clr);	
				}else{
					$arraySearch 	= 	array("blog_id"=>$blog_id,"view"=>$view,"brd_clr"=>$searchbrd_clr,"intr_bgclr"=>$searchintr_bgclr,"trim_clr"=>$trim_clr);	
				}			
				
				
				$arrayPagewidth['blog_id']=$blog_id;			
				$arrayHeadersetting['blog_id']=$blog_id;
				$arrayPagebg['blog_id']=$blog_id;
				$arrayBg['blog_id']=$blog_id;
				$arrayTextlink['blog_id']=$blog_id;	
				$arrayleftModule['blog_id']=$blog_id;	
				$arrayMusic['blog_id']=$blog_id;
				$arraySearch['blog_id']=$blog_id;	
				$arrayMainBody['blog_id']=$blog_id;				
						
				$this->db->insert("blog_style_pagewidth", $arrayPagewidth);
				$this->db->insert("blog_style_header", $arrayHeadersetting);
				$this->db->insert("blog_style_page_bg", $arrayPagebg);
				$this->db->insert("blog_style_txt_links", $arrayTextlink);
				$this->db->insert("blog_style_left_mod", $arrayleftModule);
				$this->db->insert("blog_style_music", $arrayMusic);
				$this->db->insert("blog_style_search", $arraySearch);
				$this->db->insert("blog_style_main_body", $arrayMainBody);
				
				return true;
	 
    } else {			
			$arrayPagewidth 	= 	array("blog_id"=>$blog_id,"page_width"=>$page_width,"page_type"=>$page_type,"page_align"=>$page_align);	
			$arrayHeadersetting =	array("blog_id"=>$blog_id,"header_type"=>$header_type,"site_txt"=>$site_txt,"stxt_clr"=>$stxt_clr,
									"stxt_font"=>$stxt_font,"stxt_fontsize"=>$stxt_fontsize,"border_clr"=>$border_clr,"inter_clr"=>$inter_clr,"tag_txt"=>$tag_txt,
									"tag_clr"=>$tag_clr,"tag_font"=>$tag_font,"tag_fontsize"=>$tag_fontsize,"own_header"=>$own_header);		
			$arrayPagebg 		= 	array("blog_id"=>$blog_id,"bgcolor"=>$bgcolor,"picture"=>$pic,"position"=>$position,"repeat"=>$repeat,"scroll"=>$scroll);
			$arrayTextlink 		= 	array("blog_id"=>$blog_id,"txtfont"=>$txtfont,"txtfontsize"=>$txtfontsize,"txtclr"=>$txtclr,"nrm_clr"=>$nrm_clr,
									"nrm_uline"=>$nrm_uline,"act_clr"=>$act_clr,"act_uline"=>$act_uline,"visit_clr"=>$visit_clr,"visit_uline"=>$visit_uline,"hover_clr"=>$hover_clr,"hover_uline"=>$hover_uline);
			$arrayleftModule 	= 	array("blog_id"=>$blog_id,"brd_clr"=>$brd_clr,"brd_style"=>$brd_style,"brd_pixel"=>$brd_pixel,"title_bgclr"=>$title_bgclr,
									"title_txt_clr"=>$title_txt_clr,"title_align"=>$title_align,"intr_bgclr"=>$intr_bgclr,"intr_txt_clr"=>$intr_txt_clr,"intr_align"=>$intr_align);
			$arrayMusic 		= 	array("blog_id"=>$blog_id,"filepath"=>$filepath,"loop_music"=>$loop_music);
			$arrayMainBody		= 	array("blog_id"=>$blog_id,"mbcolor"=>$mbcolor,"sbcolor"=>$sbcolor,"transperant"=>$transperant,"txtfont"=>$hfont,"txtfontsize"=>$bfontsize,"color"=>$headercolor);
			if($hidetrim_clr){				
				$arraySearch 	= 	array("blog_id"=>$blog_id,"view"=>$view,"brd_clr"=>$searchbrd_clr,"intr_bgclr"=>$searchintr_bgclr,"trim_clr"=>$hidetrim_clr);	
			}else{
				$arraySearch 	= 	array("blog_id"=>$blog_id,"view"=>$view,"brd_clr"=>$searchbrd_clr,"intr_bgclr"=>$searchintr_bgclr,"trim_clr"=>$trim_clr);	
			}			
			
			if($blog_id) {
				//$arrayPagewidth['id'] = $blog_id;
				$this->db->update("blog_style_pagewidth", $arrayPagewidth, "blog_id='$blog_id'");
				$this->db->update("blog_style_header", $arrayHeadersetting, "blog_id='$blog_id'");
				$this->db->update("blog_style_page_bg", $arrayPagebg, "blog_id='$blog_id'");
				$this->db->update("blog_style_txt_links", $arrayTextlink, "blog_id='$blog_id'");
				$this->db->update("blog_style_left_mod", $arrayleftModule, "blog_id='$blog_id'");
				$this->db->update("blog_style_music", $arrayMusic, "blog_id='$blog_id'");
				$this->db->update("blog_style_search", $arraySearch, "blog_id='$blog_id'");
				$this->db->update("blog_style_main_body", $arrayMainBody, "blog_id='$blog_id'");
		  } else {
				$this->db->insert("blog_style_pagewidth", $arrayPagewidth);
				$this->db->insert("blog_style_header", $arrayHeadersetting);
				$this->db->insert("blog_style_page_bg", $arrayPagebg);
				$this->db->insert("blog_style_txt_links", $arrayTextlink);
				$this->db->insert("blog_style_left_mod", $arrayleftModule);
				$this->db->insert("blog_style_music", $arrayMusic);
				$this->db->insert("blog_style_search", $arraySearch);
				$this->db->insert("blog_style_main_body", $arrayMainBody);
				$id = $this->db->insert_id;
		  } 
     	 return true;
    }
    return $message;
  }
  /**
	 *Listing Fonts in combo 
	 *
	 * @return Array
	 */
function menufontList () {
        $sql			= "SELECT id,font_name FROM font_list WHERE 1";		
        $rs['id']			 = $this->db->get_col($sql, 0);
        $rs['font_name']	 = $this->db->get_col("", 1);		
        return $rs;
	}
	/**
	 * Get Pagewidth details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getPagewidth ($blog_id) {
 	$rs = $this->db->get_row("SELECT * FROM blog_style_pagewidth  WHERE blog_id='{$blog_id}'", ARRAY_A);
	return $rs;
  }
  /**
	 * Get Header details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getHeader ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_header  WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Background details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getBackground ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_page_bg   WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
   /**
	 * Get MainBody details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getMainBody ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_main_body   WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Text&Link details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getText ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_txt_links  WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Left Module  details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getLeftmodule ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_left_mod   WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Left Music  details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getMusic ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_style_music  WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Left Search details  for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
  function getSearchbar ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM   blog_style_search   WHERE blog_id='{$blog_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Template List
	 *
	 * @param <Page Number> $pageNo
	 */
	function templateList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
    $sql		= "SELECT * FROM blog_template  WHERE 1";	
    $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
    return $rs;
  }
  /**
	 * Add Edit Blog
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
  function blogAddEdit (&$req) {  
  
	if(isset($_SESSION['memberid'])){
			$user_id=$_SESSION['memberid'];
		}
	
		if($temp_id){
			$tempDetails=$this->getTemplate($temp_id);
			$arrayPagewidth 	= 	 array("page_width"=>$tempDetails[temp_width],"page_type"=>$tempDetails[temp_type],"page_align"=>$tempDetails[temp_align]);
			$arrayHeader 		=	 array("header_type"=>$tempDetails[header_type],"site_txt"=>$tempDetails[site_text],"stxt_clr"=>$tempDetails[site_txtcolor],"stxt_font"=>$tempDetails[site_font],
									"stxt_fontsize"=>$tempDetails[site_fontsize],"border_clr"=>$tempDetails[header_bordercolor],"inter_clr"=>$tempDetails[header_interior],
									"tag_txt"=>$tempDetails[header_tagtext],"tag_clr"=>$tempDetails[header_tagcolor],"tag_font"=>$tempDetails[header_tagfont],"tag_fontsize"=>$tempDetails[header_tagfontsize]);
			$arrayBg			=	array("bgcolor"=>$tempDetails[bg_color],"picture"=>$tempDetails[temp_image],"position"=>$tempDetails[bg_position],"repeat"=>$tempDetails[bg_repeat],"scroll"=>$tempDetails[bg_scroll]);
			$arryText			=	array("txtfont"=>$tempDetails[txt_font],"txtfontsize"=>$tempDetails[txt_font_size],"txtclr"=>$tempDetails[txt_fontcolor],"nrm_clr"=>$tempDetails[txt_linknormal],
									"nrm_uline"=>$tempDetails[txt_normaluline],"act_clr"=>$tempDetails[txt_linkactive],"act_uline"=>$tempDetails[txt_activeuline],"visit_clr"=>$tempDetails[txt_linkvisit],
									"visit_uline"=>$tempDetails[txt_vistuline],"hover_clr"=>$tempDetails[txt_linkhover],"hover_uline"=>$tempDetails[txt_hoveruline]);
			$arrayLeft			= 	 array("brd_clr"=>$tempDetails[left_bordercolor],"brd_style"=>$tempDetails[left_brd_style],"brd_pixel"=>$tempDetails[left_brd_pixel],
									"title_bgclr"=>$tempDetails[left_titlebgcolor],"title_txt_clr"=>$tempDetails[left_titlecolor],"title_align"=>$tempDetails[left_title_align],"intr_bgclr"=>$tempDetails[left_interiorcolor],
									"intr_txt_clr"=>$tempDetails[left_interiorcolor],"intr_align"=>$tempDetails[left_align]);
			$arrayMusic			= 	 array("filepath"=>$tempDetails[music_path],"loop_music"=>$tempDetails[music_loop]);
			$arraySearchview	= 	 array("view"=>$tempDetails[search_view],"brd_clr"=>$tempDetails[search_bordercolor],"intr_bgclr"=>$tempDetails[search_interiorcolor],"trim_clr"=>$tempDetails[search_trimcolor]);
			
		}		
	if(!trim($user_id)) {
      $message = "user_id";
    } else {		
     		 $array = array("cat_id"=>$cat_id,"subcat_id"=>$subcat_id,"user_id"=>$user_id,"temp_id"=>$temp_id,"blog_name"=>$blog_name,"blog_description"=>$blog_description,"create_date"=>$create_date,"active"=>$active);
			 
		
		if($id ) {
		$this->db->update("blog_master", $array, "user_id='$user_id'");		
        $array['user_id'] = $user_id;
		$arrayPagewidth['blog_id']=$id;				
		$arrayHeader['blog_id']=$id;
		$arrayBg['blog_id']=$id;
		$arryText['blog_id']=$id;	
		$arrayLeft['blog_id']=$id;	
		$arrayMusic['blog_id']=$id;
		$arraySearchview['blog_id']=$id;
		$countPagewidth		=	$this->getCounter("blog_style_pagewidth",$id);
		$countStyleheader	=	$this->getCounter("blog_style_header",$id);		
		$countPagebg		=	$this->getCounter("blog_style_page_bg",$id);
		$countTextLink		=	$this->getCounter("blog_style_txt_links",$id);
		$countLeftmodule	=	$this->getCounter("blog_style_left_mod",$id);
		$countMusic			=	$this->getCounter("blog_style_music",$id);
		$countStylesearch	=	$this->getCounter("blog_style_search",$id);	
			if($countPagewidth !=0){				
				$this->db->update("blog_style_pagewidth", $arrayPagewidth, "blog_id='$id'");	
			}	
			if($countStyleheader !=0	){
				$this->db->update("blog_style_header", $arrayHeader, "blog_id='$id'");	
			}
			if($countPagebg !=0){
				$this->db->update("blog_style_page_bg", $arrayBg, "blog_id='$id'");	
			} 
			if($countTextLink !=0){
				$this->db->update("blog_style_txt_links", $arryText, "blog_id='$id'");	
			} 
			if($countLeftmodule !=0){
				$this->db->update("blog_style_left_mod", $arrayLeft, "blog_id='$id'");	
			}
			if($countMusic !=0){
				$this->db->update("blog_style_music", $arrayMusic, "blog_id='$id'");	
			}
			if($countStylesearch !=0){
				$this->db->update("blog_style_search", $arraySearchview, "blog_id='$id'");	
			}
      } else {	  
       $this->db->insert("blog_master", $array);
        $blog_id = $this->db->insert_id;
			if($temp_id){	
				$arrayPagewidth['blog_id']=$blog_id;			
				$arrayHeader['blog_id']=$blog_id;
				$arrayBg['blog_id']=$blog_id;
				$arryText['blog_id']=$blog_id;	
				$arrayLeft['blog_id']=$blog_id;	
				$arrayMusic['blog_id']=$blog_id;
				$arraySearchview['blog_id']=$blog_id;						
				$this->db->insert("blog_style_pagewidth", $arrayPagewidth);		
				$this->db->insert("blog_style_header", $arrayHeader);
				$this->db->insert("blog_style_page_bg", $arrayBg);
				$this->db->insert("blog_style_txt_links", $arryText);	
				$this->db->insert("blog_style_left_mod", $arrayLeft);
				$this->db->insert("blog_style_music", $arrayMusic);
				$this->db->insert("blog_style_search", $arraySearchview);
				
			}
      }     
      return true;
    }
    return $message;
  }
  
   function getAdvanceBlog ($user_id,$id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_master  WHERE user_id='{$user_id}' and cat_id='{$id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Blog details for given user_id
	 *
	 * @param <user_id> $user_id
	 * @return Array
	 */
  function getBlog ($user_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_master  WHERE user_id='{$user_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Blog details for given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @return Array
	 */
   function getBlogdetails ($blog_id) {
    $rs = $this->db->get_row("SELECT * FROM  blog_master  WHERE id='{$blog_id}'", ARRAY_A);
	return $rs;
  }
  /**
	 * Get template details for given temp_id
	 *
	 * @param <temp_id> $temp_id
	 * @return Array
	 */
  function getTemplate ($temp_id) {
    $rs 	= 	$this->db->get_row("SELECT * FROM blog_template  WHERE id='{$temp_id}'", ARRAY_A);
   	return $rs;
  }
  /**
	 * Get Count of pagewidth settings based on given blog_id
	 *
	 * @param <blog_id> $blog_id
	 * @param <table_name> $table
	 * @return Array
	 */
   function getCounter ($table,$blog_id){
    $rs 	= 	$this->db->get_row("SELECT * FROM  ".$table."  WHERE blog_id='{$blog_id}'");
   	$count=count($rs);
	return $count;
   }
  /**
	 *Listing Category in combo 
	 *
	 * @return  Array
	 */
	function menuSectionList () {
        $sql		= "SELECT category_id AS id, category_name AS cat_name FROM master_category WHERE parent_id=0";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['cat_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	/**
	 *Listing Subcategory in combo 
	 * @param <cat_id> $cat_id
	 * @return  Array
	 */
	function menuSubcatList ($cat_id) {
        $sql		= "SELECT category_id AS id, category_name AS cat_name FROM master_category WHERE parent_id={$cat_id}";
        $rs['id'] = $this->db->get_col($sql, 0);
        $rs['cat_name'] = $this->db->get_col("", 1);
        return $rs;
    }
	
	/**
	 * Add Edit Blog Entry
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function blogentryAddEdit (&$req) {
    extract($req);
	$message="";
	$dispFlag=0;
	$desc_len=strlen($post_description);		
    if(!trim($post_title)) {
		if(!($message)){
			$message = $message."Title";
		}else{
			$message = $message.","."Title";
		}
		$dispFlag=1;
	}	
	 if($dispFlag==0){
			 $array = array("blog_id"=>$blog_id,"post_title"=>$post_title,"post_description"=>$post_description,"create_date"=>$create_date);
			 if($id) {
					$array['id'] = $id;
					$this->db->update("blog_post", $array, "id='$id'");
				} else {
					$this->db->insert("blog_post", $array);
					$id = $this->db->insert_id;
				}     
			 return true;
   	}
	else{
		 $message=$message."  "."are required";
	}
    return $message;
}
	/**
	 * Get Blog Post Details
	 *
	 * @param <id> $blog_entry_id
	 * @return Array
	 */
  function getBlogentry ($blog_entry_id) {
    $rs = $this->db->get_row("SELECT a.*,b.user_id FROM  `blog_post` a inner join `blog_master` b on a.blog_id=b.id WHERE a.id='{$blog_entry_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Get Blog Post Details in date formated
	 *
	 * @param <id> $blog_entry_id
	 * @return Array
	 */
   function getBlogentrydetails ($blog_entry_id) {
   
    $query="SELECT b.user_id,
				   a.id,
				   a.blog_id,
				   a.post_title,
				   a.post_description,
				   a.blogs_comments_no,
				   DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as create_date,
				   DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogentrytime,
				   c.username as uname
				   FROM  blog_post a,blog_master b,member_master c 
				   WHERE a.id='{$blog_entry_id}' AND a.blog_id=b.id AND b.user_id=c.id";
				   
    //$rs = $this->db->get_row("SELECT b.user_id,a.id,a.blog_id,a.post_title,a.post_description,a.blogs_comments_no,DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as create_date,DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogentrytime  FROM  `blog_post` a inner join `blog_master` b on a.blog_id=b.id WHERE a.id='{$blog_entry_id}'", ARRAY_A);
    $rs = $this->db->get_row($query, ARRAY_A);
    return $rs;
   
   }
   /**
	 * Getting Blogentry List
	 * @param <user_id>$user_id
	 * @param <pageNo>$pageNo
	 */
  function blogentryList ($blog_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
   		$sql="SELECT  b.user_id,a.id,a.blog_id,a.post_title,a.post_description,a.blog_rating,DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as create_date,DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogentrytime,a.blogs_comments_no  FROM `blog_post` a inner join `blog_master` b on a.blog_id=b.id  WHERE blog_id='{$blog_id}'";  		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs; 
  }
 
  /**
	 * Blog categoryList
	 *
	 * @param <Page Number> $pageNo
	 */
	function blogcategoryList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	= "SELECT * FROM master_category  WHERE parent_id=0";	
		list($rs,$numpad) = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);	
		if($rs){
			foreach ($rs as $key=>$row){		
				$cat_id= $row->category_id;
				$countSubcat=count($this->subcatCount($cat_id));	
				$rs[$key]->subcat=$this->sucatList($cat_id);
				if($countSubcat>10){				
					$rs[$key]->more=true;
				}						
			}
		}
		return array($rs,$numpad);		
	}
	function getCountBlog($user_id){
		$rs = $this->db->get_results("SELECT * FROM  blog_master  WHERE user_id='{$user_id}'");
		$countBlog=count($rs);
		return $countBlog;
	}
	/**
	 * For getting subcategory for given cat_id
	 *
	 * @param <id> $cat_id
	 * @return Array
	 */
	 
	function BlogcategoryleftList () {
		$sql	= "SELECT * FROM master_category  WHERE parent_id=0 and active='y'";	
		$rs= $this->db->get_results($sql);		
		/*if($rs){
			foreach ($rs as $key=>$row){		
				$cat_id= $row->id;
				$countSubcat=count($this->subcatCount($cat_id));							
				$rs[$key]->subcat=$this->sucatList($cat_id);
								
			}
		}*/
		return $rs;		
	} 
	 
	 
	 
	 
	 
  function sucatList($cat_id) {
	$rs = $this->db->get_results("SELECT * FROM master_category  WHERE parent_id=$cat_id");	
	//print_r($rs);exit;  
	return $rs;
	}
	 /**
	 * For calculating count for given cat_id
	 *
	 * @param <id> $cat_id
	 * @return Array
	 */
  function subcatCount($cat_id) {
	$rs = $this->db->get_results("SELECT * FROM master_category WHERE parent_id='{$cat_id}'");	
	return $rs;
	}
	/**
	 * For listing Subcategory 
	 *
	 * @param <id> $id
	 * @param <pageNo> $id
	 * @return Array
	 */
	function blogsubcategoryList($cat_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {		
		$sql	= "SELECT * FROM master_category  WHERE parent_id='{$cat_id}'";	
		list($rs,$numpad) = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);		
		if($rs){
			foreach ($rs as $key=>$row){		
				$cat_id= $row->category_id;
				$rs[$key]->countBlog=count($this->CountBlog($cat_id));							
			}
		}
		return array($rs,$numpad);		
	}
	 /**
	 * Get Topics Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
  function CountBlog($cat_id) {
  	$sql="SELECT * FROM blog_master  WHERE subcat_id='{$cat_id}'";
	$rs = $this->db->get_results($sql);	
	return $rs;
	}
	/**
	 * Get Subcategory name
	 *
	 * @param <id> $cat_id
	 * @return Array
	 */
  function getCatname ($cat_id) {
    $rs = $this->db->get_row("SELECT * FROM  master_category WHERE category_id ='{$cat_id}'", ARRAY_A);
    return $rs;
  }
  /**
	 * Getting Blog List
	 * @param <subcat_id>$subcat_id
	 * @param <pageNo>$pageNo
	 */
  function blogList ($subcat_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {    	
		$sql		= "SELECT a.id as id,
		                      a.user_id,
		                      a.cat_id,
		                      a.subcat_id,
		                      a.blog_name,
		                      a.blog_description,
		                      DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as blogdate,
					          DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogtime, 
					          b.id,
					          b.username as uname,
					          CONCAT(b.first_name,' ',b.last_name) as username,
					          b.image 
					     FROM blog_master a,
					          member_master b 
					    WHERE a.subcat_id='{$subcat_id}' 
					      AND a.user_id=b.id 
					      AND a.active='Y'";
   		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
				
		return $rs;
  } 
  /**
	 * Getting Blogcomments List
	 * @param <blog_entry_id>$blog_entry_id
	 * @param <pageNo>$pageNo
	 */
  function blogCommentsList ($blog_entry_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
   		$sql="SELECT  a.id,
			a.blog_entry_id,
			a.user_id,
			a.body,
			DATE_FORMAT(a.post_date,'".$this->config['date_format']."') as create_date,
			DATE_FORMAT(a.post_date,'".$this->config['time_format']."') as blogentrytime,
			CONCAT(b.first_name,' ',b.last_name) as name,			
			b.image FROM blog_comments a,
			member_master b 
			WHERE a.blog_entry_id='{$blog_entry_id}' AND a.user_id=b.id";			
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs; 
  } 
  /**
	 * Add Blog Comments
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
  function blogcommentsAdd (&$req) {
    extract($req);
	$message="";
	$dispFlag=0;		
    if(!trim($body)) {
		if(!($message)){
			$message = $message."Comments";
		}else{
			$message = $message.","."Comments";
		}
		$dispFlag=1;
	}
	
	 if($dispFlag==0){
				$array = array("blog_entry_id"=>$blog_entry_id,"user_id"=>$user_id,"body"=>$body,"post_date"=>$post_date);
				$this->db->insert("blog_comments", $array);
				$id = $this->db->insert_id;
				$UpdateBlogentry="UPDATE blog_post SET blogs_comments_no=blogs_comments_no+1 WHERE id=$blog_entry_id";
				$this->db->query($UpdateBlogentry);
				return true;
   	}
	else{
		 $message=$message."  "." required";
	}
    return $message;
}
/**
	 * Delete blog comments
	 *
	 * @param <id> $id
	 * @return Boolean/Error Message	 
	 */
	function blogcommentsDelete($id){
		$rs = $this->db->get_row("select * from blog_comments WHERE id='$id'", ARRAY_A);
		$UpdateBlogentry="UPDATE blog_post SET blogs_comments_no=blogs_comments_no-1 WHERE id=$rs[blog_entry_id]";
		$this->db->query($UpdateBlogentry);
	   $this->db->query("DELETE FROM blog_comments WHERE id='$id'");
	}
	/**
	 * Getting Subscribed blogs List
	 * @param <user_id>$user_id
	 * @param <pageNo>$pageNo
	 */
  function blogsubscribeList ($user_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
  		$tt="SELECT ms. * , bm. * ,mm.username 
FROM `my_subscriptions` ms
INNER JOIN blog_master bm ON ms.`subscribed_id` = bm.user_id
INNER JOIN member_master mm ON ms.`subscribed_id` = mm.id
WHERE ms.type = 'blog'
AND ms.member_id =$user_id";
$rs = $this->db->get_results_pagewise($tt, $pageNo, $limit, $params, $output, $orderBy);
return $rs;
//$rs1 = $this->db->get_results($tt);
//print_r($rs);
   	/*	$sql="SELECT 
			b.blog_id,
			b.cat_id,
			b.subcat_id,
			b.user_id as subscribed_id,
			b.blog_name,
			b.blog_description,
			c.username as uname
			FROM 
			blog_master b,
			member_master c
			WHERE b.user_id IN(select subscribed_id from my_subscriptions where member_id=$user_id and type='blog')
		    AND b.user_id=c.id";  	
		
					print_r($sql);	exit;

		 */
  }
  /**
	 * For Subscribing blogs
	 * @param <user_id>$user_id
	 * @param <blog_d>$blog_id
	 */
  function blogSubscribe($user_id,$blog_id){
 	 $array = array("user_id"=>$user_id,"blog_id"=>$blog_id);
	 $this->db->insert("blog_subscribe", $array);
	 $id = $this->db->insert_id;
  }
  /**
	 * For getting subscribed  blogs under given user_id
	 * @param <user_id>$user_id
	 */
  function getSubscribedblog($blog_id){
	$rs 	= $this->db->get_row("SELECT * FROM  blog_subscribe  WHERE blog_id='{$blog_id}'");
	$count	= count($rs);
	return $count;
	}
	/**
	 * For Unsubscribing Blogs
	 * @param <POST/GET Array> $req
	 */
	function blogUnsubscribe($blog_id){
	 $this->db->query("DELETE FROM blog_subscribe WHERE blog_id='{$blog_id}'");
	}
 /**
	 * For searching blogs
	 * @param <POST/GET Array> $req
	 */
 function blogsearchList (&$req,$active,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
 
 
 	if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "d.screen_name as uname";
		}
		else 
		{				$member_search_fields = "d.username as uname ";
		
		}
		extract($req);			
		if($active=='Y'){
		$sql		= "SELECT DISTINCT (a.blog_name),
					  a.id as blog_id,
					  a.cat_id,a.subcat_id,
					  a.user_id,
					  a.blog_description,
					  DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as blogdate,
					  DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogtime,
					  CONCAT(d.first_name,' ',d.last_name) as name, 
					  d.image,
					  $member_search_fields 
					  FROM blog_master a,
					  blog_post b,
					  blog_comments c,
					  member_master d 
					  WHERE a.user_id=d.id AND a.active='Y'";
					 	
		}else{		
		$sql		= "SELECT DISTINCT (a.blog_name),
					   a.id as id,
					   a.cat_id,
					   a.subcat_id,
					   a.user_id,
					   a.blog_description,
					   a.active,
					   DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as blogdate,
					   DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogtime,
					   CONCAT(d.first_name,' ',d.last_name) as name, 
					   d.image, 
					   d.username as uname
					   FROM blog_master a,
					   blog_post b,
					   blog_comments c,
					   member_master d 
					   WHERE a.user_id=d.id";
		}			 
		if($cat_id){
		$sql		= $sql." AND a.cat_id=$cat_id";
		}
		if($subcat_id){
		$sql		= $sql." AND a.subcat_id=$subcat_id";
		}
		if ($this->config['member_screen_name']=='Y')
		{
			$sql =$sql. " AND d.mem_type!=3";
		}
				
		if($txtkeywords!="" && $chkExact==""){
			$txtKeyArray=explode(" ",trim($txtkeywords));
			$countArr=count($txtKeyArray);			
			$sql=$sql." AND (";			
			for($i=0;$i<$countArr;$i++){			
				if($txtKeyArray[$i]!=""){
					if($i!=$countArr-1){
						$sql=$sql."  a.blog_name Like '%$txtKeyArray[$i]%' OR a.blog_description Like '%$txtKeyArray[$i]%' OR 
									 b.post_title Like '%$txtKeyArray[$i]%' OR b.post_description Like '%$txtKeyArray[$i]%' OR
									 c.body Like '%$txtKeyArray[$i]%' OR";
					}else{					
						$sql=$sql."  a.blog_name Like '%$txtKeyArray[$i]%' OR a.blog_description Like '%$txtKeyArray[$i]%' OR 
									 b.post_title Like '%$txtKeyArray[$i]%' OR b.post_description Like '%$txtKeyArray[$i]%' OR
									 c.body Like '%$txtKeyArray[$i]%' ";
					}					
				}	
			}	
			$sql=$sql.")";		
		}else if(($txtkeywords!="" && $chkExact!="")){
						$sql=$sql." AND (a.blog_name Like '%$txtkeywords%' OR a.blog_description Like '%$txtkeywords%' OR 
									b.post_title Like '%$txtkeywords%' OR b.post_description Like '%$txtkeywords%' OR
									c.body Like '%$txtkeywords%')";
		}
	//echo $sql;
	
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy); 		
		return $rs;
	}
	/**
	 * For Template managment from admin side
	 * @param <page_no> $page_no
	 */
	   function adminBlogtempalteList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
   		$sql="SELECT  * FROM blog_template";  		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs; 
  }
  /**
	 * Add Edit Blog Template for admin
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
function blogtemplateaddedit (&$req,$file,$tmpname) {
    extract($req);
	if ($file){							
		$dir=SITE_PATH."/modules/blog/images/template/";			
		$file1=$dir."thumb/";
		$resource_file=$dir.$file;
		_upload($dir,$file,$tmpname,1);	
	}
	$message="";
	$dispFlag=0;	
	if(!trim($page_width)) {
		$message = $message."Page width";
		$dispFlag=1;
    }
    if(!trim($page_type)) {
		if(!($message)){
			$message = $message."Page Type";
		}else{
			$message = $message.","."Page Type";
		}
		$dispFlag=1;
	}else {
			if($file){			
			 $array = 	array("temp_image"=>$file,"temp_width"=>$page_width,"temp_type"=>$page_type,"temp_align"=>$page_align,
			 			"header_type"=>$header_type,"site_text"=>$site_txt,"site_txtcolor"=>$stxt_clr,"site_font"=>$stxt_font,
						"site_fontsize"=>$stxt_fontsize,"header_bordercolor"=>$border_clr,"header_interior"=>$inter_clr,
						"header_tagtext"=>$tag_txt,"header_tagcolor"=>$tag_clr,"header_tagfont"=>$tag_font,"header_tagfontsize"=>$tag_fontsize,
						"bg_color"=>$bgcolor,"bg_position"=>$position,"bg_repeat"=>$repeat,"bg_scroll"=>$scroll,"txt_font"=>$txtfont,
						"txt_font_size"=>$txtfontsize,"txt_fontcolor"=>$txtclr,"txt_linknormal"=>$nrm_clr,"txt_normaluline"=>$nrm_uline,
						"txt_linkactive"=>$act_clr,"txt_activeuline"=>$act_uline,"txt_linkvisit"=>$visit_clr,"txt_vistuline"=>$visit_uline,
						"txt_linkhover"=>$hover_clr,"txt_hoveruline"=>$hover_uline,"left_bordercolor"=>$brd_clr,"left_brd_style"=>$brd_style,
						"left_brd_pixel"=>$brd_pixel,"left_titlebgcolor"=>$title_bgclr,"left_titlecolor"=>$title_txt_clr,"left_title_align"=>$title_align,
						"left_interiorcolor"=>$intr_bgclr,"interior_txt_color"=>$intr_txt_clr,"left_align"=>$intr_align,"music_path"=>$filepath,
						"music_loop"=>$loop_music,"search_view"=>$view,"search_bordercolor"=>$searchbrd_clr,"search_interiorcolor"=>$searchintr_bgclr,"search_trimcolor"=>$trim_clr);
			}else{
			$array = 	array("temp_width"=>$page_width,"temp_type"=>$page_type,"temp_align"=>$page_align,
						"header_type"=>$header_type,"site_text"=>$site_txt,"site_txtcolor"=>$stxt_clr,"site_font"=>$stxt_font,
						"site_fontsize"=>$stxt_fontsize,"header_bordercolor"=>$border_clr,"header_interior"=>$inter_clr,
						"header_tagtext"=>$tag_txt,"header_tagcolor"=>$tag_clr,"header_tagfont"=>$tag_font,"header_tagfontsize"=>$tag_fontsize,
						"bg_color"=>$bgcolor,"bg_position"=>$position,"bg_repeat"=>$repeat,"bg_scroll"=>$scroll,"txt_font"=>$txtfont,
						"txt_font_size"=>$txtfontsize,"txt_fontcolor"=>$txtclr,"txt_linknormal"=>$nrm_clr,"txt_normaluline"=>$nrm_uline,
						"txt_linkactive"=>$act_clr,"txt_activeuline"=>$act_uline,"txt_linkvisit"=>$visit_clr,"txt_vistuline"=>$visit_uline,
						"txt_linkhover"=>$hover_clr,"txt_hoveruline"=>$hover_uline,"left_bordercolor"=>$brd_clr,"left_brd_style"=>$brd_style,
						"left_brd_pixel"=>$brd_pixel,"left_titlebgcolor"=>$title_bgclr,"left_titlecolor"=>$title_txt_clr,"left_title_align"=>$title_align,
						"left_interiorcolor"=>$intr_bgclr,"interior_txt_color"=>$intr_txt_clr,"left_align"=>$intr_align,"music_path"=>$filepath,
						"music_loop"=>$loop_music,"search_view"=>$view,"search_bordercolor"=>$searchbrd_clr,"search_interiorcolor"=>$searchintr_bgclr,"search_trimcolor"=>$trim_clr);
						
			}
			  if($id) {
				$array['id'] = $id;
				$this->db->update("blog_template", $array, "id='$id'");
			  } else {
				$this->db->insert("blog_template", $array);
				$id = $this->db->insert_id;
			}     
			 return true;
   		 }
		 if($dispFlag==1){
		 	$message=$message."  "."are required";
		 }
    return $message;
  }
  /**
	 * For Deleting template from admin side
	 * @param <id> $temp_id
	 */
	function blogtemplateDelete($temp_id){
	 $this->db->query("DELETE FROM blog_template WHERE id='{$temp_id}'");
	}
	/**
	 * Get Template for particular id
	 *
	 * @param <id> $temp_id
	 * @return Array
	 */
  function templateGet ($temp_id) {
	$rs = $this->db->get_row("SELECT * FROM  blog_template WHERE id='{$temp_id}'", ARRAY_A);
	return $rs;
  }
  /**
	 * For Getting all the blog details
	 * @param <page_no> $page_no
	 */
	   function adminBlogList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
   		$sql="SELECT  * FROM blog_master";  		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs; 
  }
   /**
	 * For Deleting template from admin side
	 * @param <id> $temp_id
	 */
	function adminblogDelete($id){
	 $this->db->query("DELETE FROM blog_master WHERE id='{$id}'");
	}
	/**
	 * For Suspenending Blog From Admin side
	 * @param <id> $id
	 */
	function adminblogSuspend($req){
		extract($req);
		$id=$req['id'];		
		$rs=$this->getBlog_User($req['id']);
		$subject="Your Blog is Temporarily Suspended";	
		$from='admin@industrypage.com';		
		$this->db->query("UPDATE  blog_master SET active='N' WHERE id='{$id}'");
		sendMail($rs['email'],$subject,$req['strMessage'],$from,$mailFormat='TEXT');	
	}
	/**
	 * For Unsususpenending Blog From Admin side
	 * @param <id> $id
	 */
	function adminblogUnsuspend($req){
		extract($req);	
		$rs=$this->getBlog_User($id);
		$subject="Your Blog is Activated";	
		$from='admin@industrypage.com';
		sendMail($rs['email'],$subject,$req['strMessage'],$from,$mailFormat='TEXT');
		$this->db->query("UPDATE  blog_master SET active='Y' WHERE id='{$id}'");
	}
	function getBlog_User($id){
		$rs = $this->db->get_row("SELECT a.*,b.*  FROM blog_master a,member_master b WHERE a.user_id=b.id AND a.id=$id" , ARRAY_A);
		return $rs;	
	}
	/**
	 * For Rating Blogs
	 * @param <id> $id
	 */
	function rateBlog(){	
		$arrData=$this->getArrData();
		$arrData["postdate"]=date("Y-m-d H:i:s");
		$sql="Select id from media_rating where file_id=".$arrData["file_id"] ." and userid=".$arrData['userid']." and type='photo'";
		$count=count($this->db->get_results($sql));
		if($count==0){		
			$this->db->insert("media_rating",$arrData);
			return true;
		}
		else{		
			$this->setErr("You have already rated this photo!!!");
			return false;
		}	
	}
	
	function settingsGet () {
	$rs = $this->db->get_row("SELECT * FROM  blog_settings ", ARRAY_A);
	return $rs['active'];
  }
  
  function settingsUpdate ($type) {
 	 	$array = array("active"=>$type);
		$this->db->update("blog_settings", $array);
	  }
	  
	 ### Function for Subscribe a user............
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 06 September 2007.............
	 
	 function Subscribe($uid,$subid,$type)
	 {
	 	if($uid==$subid){
			$this->setErr("You Can't Subscribe Yourself.");
			return false;
		}else{
			$sql = "select * from my_subscriptions  where member_id=$uid and subscribed_id=$subid and type='$type'";
			$rs  = $this->db->get_row($sql);
			if(count($rs)<1)
			{
				$arr=array();
				$arr["member_id"]		=	$uid;
				$arr["subscribed_id"]	=	$subid;
				$arr["date"]			=	date("Y-m-d h:i:s");
				$arr["type"]			=	$type;
				$this->db->insert("my_subscriptions", $arr);
				return true;
			}
			else
			{
				$this->setErr("You Are Already Subscribed This User.");
				return false;
			}
		}	
	 }
	   /* 
	   Setting the Error
    */
    function setErr($szError)
    {
        $this->err .= "$szError";
    }
    /*
    End function setErr 
	    Returns the error
    */
    function getErr()
    {
        return $this->err;
    }

    /*
    End function getErr
	*/
	
	 ### Function for Checking Subscription.......
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 10 September 2007.............
	 
	 function chkSubscribe($uid,$subid,$type)
	 {
	 	if($uid==$subid){
			$res="Y";
			return $res;
		}else{
			$sql = "select * from my_subscriptions  where member_id=$uid and subscribed_id=$subid and type='$type'";
			$rs  = $this->db->get_row($sql);
			if(count($rs)<1)
			{
				$res="N";
			return $res;
			}
			else
			{
				$res="Y";
			return $res;
			}
			
		}
	 		
	 }
	   ### Function for unsubscription.............
	 ###  Author  : Jipson Thmas..................
	 ###  Dated   : 10 September 2007.............
	 
	 function Unsubscribe($uid,$subid,$type)
	 {
	 	if($uid==$subid){
			$this->setErr("You Can't unsubscribe Yourself.");
			return false;
		}else{
			$sql = "delete from my_subscriptions  where member_id=$uid and subscribed_id=$subid and type='$type'";
			$this->db->query($sql);
			return true;
			
		}
	 		
	 }//End of class
	 
	 ##### Function get Details of Blog Members
	 #### Author :Afsal Isami........
	 ### Ddated:10 Oct 2007 ....
	 function getBlogMemberList($pageNo, $limit=9, $params, $output, $orderBy)
	 {
	 	$sql = "SELECT m.username,m.id,m.image,m.joindate FROM member_master AS m ORDER BY joindate";
		$rs  = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	 }
	 
	 #### Function get the blog details based on the user
	 ### Author : Afsal
	 ### Dated :01-11-2007
	 function blogentryList2 ($blog_id) {
	 
   		$sql="SELECT  b.user_id,a.id,a.blog_id,a.post_title,a.post_description,a.blog_rating,
		      DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as create_date,
			  DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogentrytime,
			  a.blogs_comments_no  FROM `blog_post` a inner join `blog_master` b on a.blog_id=b.id  
			  WHERE blog_id='{$blog_id}'";  		
			  
		$rs = $this->db->get_results($sql);
		return $rs; 
		
  	}
	/////////////////////////
	function  blogdisplayList($cat_id,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
	
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "b.screen_name as uname";
		}
		else 
		{				$member_search_fields = "b.username as uname ";
		
		}
	 $sql		= "SELECT a.id as id,
									  a.user_id,
									  a.cat_id,
									  a.subcat_id,
									  a.blog_name,
									  a.blog_description,a.*,
									  DATE_FORMAT(a.create_date,'".$this->config['date_format']."') as blogdate,
									  DATE_FORMAT(a.create_date,'".$this->config['time_format']."') as blogtime, 
									  b.id,
									  $member_search_fields,
									  CONCAT(b.first_name,' ',b.last_name) as username,
									  b.image 
								 FROM blog_master a,
									  member_master b 
								WHERE a.cat_id='{$cat_id}' 
								  AND a.user_id=b.id 
								  AND a.active='Y'
	";
	if ($this->config['member_screen_name']=='Y')
		{
			 $sql	=  $sql."AND b.mem_type!=3";
		}
		
		
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs; 
	
	
	}
	
	/**
    * removing blog picture
  	* Author   : Ratheesh
  	* Created  : 18/Dec/2007	
  	*/		
	function removePicture($blog_id)
	{
		$arrData=array();
		$arrData["picture"]="";
		$this->db->update("blog_style_page_bg",$arrData ,"blog_id=$blog_id");
		return true;
	}
	////////////////////////

}
?>
