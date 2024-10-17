<?php

/**
 * @description	The following class is used for RSS file creation and manipulation
 * 
 * @author 	v@newagesmb.com
 *
 *
 *
 *
 *
 */

class RSS extends FrameWork
{
	
	var		$BlogObj;	
	var		$UserObj;
	
	/**
	 * @description Constructor	
	 *
	 */
	function RSS($BlogObj,$UserObj)
	{
		$this->BlogObj	= $BlogObj;
		$this->UserObj	= $UserObj;
		$this->FrameWork();
	}
	/**
	 *
	 * @description	The following method generates an RSS feed 
	 *
	 *
	 *
	 * @param $RssData	data passed for RSS creation
	 */
	 function generateRSSFeed($member_id,$blog_id)
	 {
	 
	 	$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
		$global["modbase_url"] 	= SITE_URL."/modules/";
		$global["site_url"] 	= SITE_URL;
		
		require_once SITE_PATH.'/includes/Rss/feedcreator.class.php';
		$rss = new UniversalFeedCreator();
		
		$RssBlogDetails		=	$this->BlogObj->blogentryList2($blog_id);
		$UserDetails		=	$this->UserObj->getUserdetails($member_id);
		
		$rss->title 		= 	"{$UserDetails['username']}'s Listings"; 
		$rss->description 	= 	"Listing consists of {$UserDetails['username']}"; 
		$rss->xslStyleSheet = 	SITE_URL."/includes/Rss/rssstyles.xsl";
		$rss->link 			= 	SITE_URL;
		
		$blogDetails = $this->BlogObj->getBlog($member_id);
		
		$_REQUEST['subcat_id'] = $blogDetails['subcat_id'];
		$_REQUEST['parent_id'] = $blogDetails['cat_id'];
		$url	=	"&subcat_id=".$_REQUEST['subcat_id'] ."&parent_id=".$_REQUEST['parent_id'];
		
		
		foreach($RssBlogDetails as $row){
			
			$PreviewFile		=	"index.php?mod=blog&pg=blog_usercomments&act=list&id=".$row->id."&user_id=".$member_id.$url;
			$item 				=	new FeedItem();
			$item->title 		=	$row->post_title;
			$item->link 		=	SITE_URL.'/'.$PreviewFile;
			$item->description	=	$row->post_description;
			$rss->addItem($item);		
			
		}
		$FeedHTML	=	 $rss->createFeed("RSS2.0"); 
	 	return $FeedHTML;

	 } 


	/**
	 *	@description	The following method generates the feed item description for the RSS Feed
	 *
	 *
	 *
	 */		
	function getFeedItemDescription($ItemData, $FlyerId = '')
	{
		$Qry	=	"SELECT description FROM flyer_data_basic WHERE flyer_id = '$FlyerId'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		$description	=	substr(strip_tags($Row['description']),0,255).'...';
		$description	=	htmlspecialchars(utf8_encode($description), ENT_QUOTES);
		
		$AttributesHTML		=	'';
		if(is_array($ItemData[4])) {
			foreach($ItemData[4] as $Key => $Value)
				$AttributesHTML	.=	'<div style="font-family:Arial, Helvetica, sans-serif;"><strong>'.$Key.':&nbsp;</strong>'.$Value.'</div>';
		}
		
		$AttributesHTML		.=	'<div style="font-family:Arial, Helvetica, sans-serif;"><strong>Description:&nbsp;</strong>'.$description.'</div>';
		
		
		$ImageHTML	=	'';	
		if($ItemData[1] != '')
			$ImageHTML	=	'<td width="13%" valign="top"><img src="'.SITE_URL.'/modules/flyer/images/thumb/'.$ItemData[1].'" width="75" height="75" /></td>';
		
		$ItemDescription	=	'<table width="80%" border="0" cellpadding="0" cellspacing="0">
								  <tr>'.
									$ImageHTML
									.'<td width="87%" valign="top">'.
										$AttributesHTML
									.'</td>
								  </tr>
								</table>';
		
		return $ItemDescription;
	
	}
	
	
}


?>