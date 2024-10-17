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
	
	var		$FlyerObj;	
	var		$UserObj;
	
	
	/**
	 * @description Constructor	
	 *
	 */
	function RSS($FlyerObj, $UserObj)
	{
		$this->FlyerObj	= $FlyerObj;
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
	 function generateRSSFeed($member_id)
	 {
	 	$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
		$global["modbase_url"] 	= SITE_URL."/modules/";
		$global["site_url"] 	= SITE_URL;
		
		require_once SITE_PATH.'/includes/Rss/feedcreator.class.php';
		$rss = new UniversalFeedCreator();
		
		$RssFlyerDetails	=	$this->FlyerObj->GetFormRssFields($member_id);
		$UserDetails		=	$this->UserObj->getUserdetails($member_id);
		
		$rss->title 		= 	"{$UserDetails['username']}'s Listings"; 
		$rss->description 	= 	"Listing consists of {$UserDetails['username']}"; 
		$rss->xslStyleSheet = 	SITE_URL."/includes/Rss/rssstyles.xsl";
		$rss->link 			= 	SITE_URL;
		
		
		foreach($RssFlyerDetails as $Row) {
			$PreviewFile		=	$Row[0].'.html';
			$item 				=	new FeedItem();
			$item->title 		=	$Row[2];
			$item->link 		=	SITE_URL.'/htmlflyers/'.$PreviewFile;
			$item->description	=	$this->getFeedItemDescription($Row);
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
	function getFeedItemDescription($ItemData)
	{
		
		$AttributesHTML		=	'';
		
		print_r($ItemData[4]);
		
		if(is_array($ItemData[4])) {
			foreach($ItemData[4] as $Key => $Value)
				$AttributesHTML	.=	'<div style="font-family:Arial, Helvetica, sans-serif;"><strong>'.$Key.':&nbsp;</strong>'.$Value.'</div>';
		}
		
		
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