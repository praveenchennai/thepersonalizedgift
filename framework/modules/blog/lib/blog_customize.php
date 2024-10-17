	<?php	
		$blogDetails= $blog->getBlog($user_id);
		$blog_Id=$blogDetails['id'];
		$pageBackground=$blog->getBackground($blog_Id);	
		$pageMainBody=$blog->getMainBody($blog_Id);	//created by robin
		$pageHeader= $blog->getHeader($blog_Id);
		$pageLeft=$blog->getLeftmodule($blog_Id);
		$blogText=$blog->getText($blog_Id);	
		
	
		if($pageHeader['border_clr']){
			$headerBg="border:1px solid  ". $pageHeader['border_clr'].";";
		}
		if($pageHeader['inter_clr']){				
			$headerBg = $headerBg."  background-color:".$pageHeader['inter_clr'].";";
		}	
		if($pageHeader['stxt_clr']){				
			$headerHeading = $headerHeading."  COLOR:".$pageHeader['stxt_clr'].";";
		}
		if($pageHeader['stxt_font']){				
			$headerHeading = $headerHeading."  FONT-FAMILY:".$pageHeader['stxt_font'].";";
		}
		if($pageHeader['stxt_fontsize']){				
			$headerHeading = $headerHeading."  FONT-SIZE:".$pageHeader['stxt_fontsize']."pt;";
		}
		if($pageHeader['tag_clr']){				
			$headerTag = $headerTag."  COLOR:".$pageHeader['tag_clr'].";";
		}
		if($pageHeader['tag_font']){				
			$headerTag = $headerTag."  FONT-FAMILY:".$pageHeader['tag_font'].";";
		}
		if($pageHeader['tag_fontsize']){				
			$headerTag = $headerTag."  FONT-SIZE:".$pageHeader['tag_fontsize']."pt;";
		}
		
		if($blogText['txtclr']){				
			$bodyText = $bodyText."  COLOR:".$blogText['txtclr'].";";
		}
		
		if($blogText['txtfont']){				
			$bodyText    =  $bodyText."  FONT-FAMILY:".$blogText['txtfont'].";";
			$normalLink  =  $normalLink."  FONT-FAMILY:".$blogText['txtfont'].";";
			$visitedLink =  $visitedLink."  FONT-FAMILY:".$blogText['txtfont'].";";
			$activeLink	 =	$activeLink."  FONT-FAMILY:".$blogText['txtfont'].";";
			$hoverLink	 =	$hoverLink."  FONT-FAMILY:".$blogText['txtfont'].";";
		}
		if($blogText['txtfontsize']){				
			$bodyText 	 =  $bodyText."  FONT-SIZE:".$blogText['txtfontsize']."pt;";
			$normalLink  =  $normalLink."  FONT-SIZE:".$blogText['txtfontsize']."pt;";
			$visitedLink =  $visitedLink." FONT-SIZE:".$blogText['txtfontsize']."pt;";
			$activeLink	 =	$activeLink." FONT-SIZE:".$blogText['txtfontsize']."pt;";
			$hoverLink	 =	$hoverLink." FONT-SIZE:".$blogText['txtfontsize']."pt;";
		}
		
		if($blogText['nrm_clr']){				
			$normalLink = $normalLink."  COLOR:".$blogText['nrm_clr'].";";
		}
		if($blogText['nrm_uline']=='Y'){				
			$normalLink = $normalLink."  TEXT-DECORATION:underline;";
		}else{
			$normalLink = $normalLink."  TEXT-DECORATION:none;";
		}
		if($blogText['visit_clr']){				
			$visitedLink = $visitedLink."  COLOR:".$blogText['visit_clr'].";";
		}
		if($blogText['visit_uline']=='Y'){				
			$visitedLink = $visitedLink."  TEXT-DECORATION:underline;";
		}else{
			$visitedLink = $visitedLink."  TEXT-DECORATION:none;";
		}
		if($blogText['act_clr']){				
			$activeLink = $activeLink."  COLOR:".$blogText['act_clr'].";";
		}
		if($blogText['act_uline']=='Y'){				
			$activeLink = $activeLink."  TEXT-DECORATION:underline;";
		}else{
			$activeLink = $activeLink."  TEXT-DECORATION:none;";
		}
		if($blogText['hover_clr']){				
			$hoverLink = $hoverLink."  COLOR:".$blogText['hover_clr'].";";
		}
		if($blogText['hover_uline']=='Y'){				
			$hoverLink = $hoverLink."  TEXT-DECORATION:underline;";
		}else{
			$hoverLink = $hoverLink."  TEXT-DECORATION:none;";
		}
		if($pageBackground['bgcolor']){				
			$mainTablebg = "background-color:".$pageBackground['bgcolor'].";";
		}	
		if($pageBackground['picture']){
			$image_path	=	SITE_URL."/modules/blog/images/template/".$pageBackground['picture'];
			$PageBg		=	"background-image:url(".$image_path.");";
		}else{
			$PageBg		=	$PageBg." "."background-color:".$pageBackground['bgcolor'].";";
		}
		if($pageBackground['scroll']){
			$PageBg	=	$PageBg." "."background-attachment:".$pageBackground['scroll'].";";
		}
		if($pageBackground['repeat']){
			$PageBg	=	$PageBg." "."background-repeat:".$pageBackground['repeat'].";";
		}
		if($pageBackground['position']){
			$PageBg	=	$PageBg." "."background-position:".$pageBackground['position'].";";
		}
		
		if($pageLeft['title_txt_clr']){
			$leftBg	=	"COLOR:".$pageLeft['title_txt_clr'].";";
		}
		if($pageLeft['title_align']){
			$leftBg	=	$leftBg." "."text-align:".$pageLeft['title_align'].";";
		}
		if($pageLeft['title_bgclr']){
			$leftBg	=	$leftBg." "."background-color:".$pageLeft['title_bgclr'].";";
		}
		if($pageLeft['brd_pixel']){		
			$leftBorder	=	$leftBorder." "."border:".$pageLeft['brd_pixel']."px";
		}
		if($pageLeft['brd_style']){		
			$leftBorder	=	$leftBorder." ".$pageLeft['brd_style'];
		}
		if($pageLeft['brd_clr']){		
			$leftBorder	=	$leftBorder." ".$pageLeft['brd_clr'].";";
		}
		if($pageLeft['intr_txt_clr]']){
			$leftInterior	=	"COLOR:".$pageLeft['intr_txt_clr]'].";";
		}
		if($pageLeft['intr_align']){
			$leftInterior	=	$leftInterior." "."text-align:".$pageLeft['intr_align'].";";
		}
		if($pageLeft['intr_bgclr']){
			$leftInterior	=	$leftInterior." "."background-color:".$pageLeft['intr_bgclr'].";";
		}
		if($pageMainBody['mbcolor']){				
			$mainBodyBg = "background-color:".$pageMainBody['mbcolor'].";";
		}	
		if($pageMainBody['sbcolor']){
		
	
			if($pageMainBody['transperant']==Y)		
			{
			$mainBodySectionColor = "background-color:".$pageMainBody['mbcolor'].";";
			}
			else
			{
			$mainBodySectionColor = "background-color:".$pageMainBody['sbcolor'].";";
			}
		}	
		
		if($pageMainBody['txtfont']){				
			$userHeader = "  FONT-FAMILY:".$pageMainBody['txtfont'].";";
		}	
		if($pageMainBody['txtfontsize']){				
			$userHeader 	 =  $userHeader."  FONT-SIZE:".$pageMainBody['txtfontsize']."pt;";
		}	
		if($pageMainBody['color']){				
			$userHeader 	 =  $userHeader."  COLOR:".$pageMainBody['color'].";";
		}	
		
		if(!$userHeader){
		$userHeader="FONT-WEIGHT: bold; 
					FONT-SIZE: 12px; 
					COLOR: #000000; 
					FONT-FAMILY: Arial; 
					TEXT-DECORATION: none;";
		}
		if(!$bodyText){
		$bodyText="FONT-WEIGHT: normal; 
					FONT-SIZE: 11px; 
					COLOR: #000000; 
					FONT-FAMILY: Verdana; 
					TEXT-DECORATION: none;";
		}
		if(!PageBg)
		{
			$PageBg=" background-position:top center;background-color: #FFFFFF;";
		}
		$Array=$blog->getPagewidth($blog_Id);
			if($Array['page_width'])
			{
				$pagewidth=$Array['page_width'];
			}else
			{$pagewidth='777';}
			
			if($Array['page_align'])
			{
				$pagealign=$Array['page_align'];
			}else
			{$pagealign='center';}
		
		$framework->tpl->assign("PAGE_MAINBODY", $mainBodyBg);
		$framework->tpl->assign("USER_HEADER", $userHeader);		
		$framework->tpl->assign("PAGE_SECTIONBOX", $mainBodySectionColor);
		$framework->tpl->assign("PAGE_BG", $PageBg);
		$framework->tpl->assign("LEFT_BG", $leftBg);
		$framework->tpl->assign("HEADER_BORDER", $headerBg);
		$framework->tpl->assign("HEADER_TEXT", $headerHeading);
		$framework->tpl->assign("TAG_TEXT", $headerTag);
		$framework->tpl->assign("LEFT_BORDER", $leftBorder);	
		$framework->tpl->assign("LEFT_INTERIOR", $leftInterior);
		$framework->tpl->assign("TABLE_BG", $mainTablebg);
		$framework->tpl->assign("BODY_TEXT", $bodyText);
		$framework->tpl->assign("TEXT_LINK", $normalLink);
		$framework->tpl->assign("TEXT_VISIT", $visitedLink);
		$framework->tpl->assign("TEXT_ACTIVE", $activeLink);
		$framework->tpl->assign("TEXT_HOVER", $hoverLink);
		$framework->tpl->assign("PAGEWIDTH", $pagewidth);
		$framework->tpl->assign("PAGEALIGN", $pagealign);
		$framework->tpl->assign("PAGEHEADER", $blog->getHeader($blog_Id));
		$framework->tpl->assign("TEXTLINK", $blog->getText($blog_Id));
		$framework->tpl->assign("MUSIC", $blog->getMusic($blog_Id));
		$framework->tpl->assign("SEARCHBAR", $blog->getSearchbar($blog_Id));
?>