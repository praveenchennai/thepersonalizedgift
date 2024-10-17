<?php

/**
 * @description The following class abstracts the operation related with the Feed autosubmission
 *
 *
 *
 */


class Feed extends FrameWork
{
	
	var		$FlyerObj;
	var		$XMLFeedText;
	var		$FlyerXMLFeedData;
	var		$NodeLevel;
	
	/**
	 * Constructor	
	 */
	function Feed($FlyerObj)
	{
		$this->FlyerObj		=	$FlyerObj;
		$this->FrameWork();
	}
	
	
	/**
	 *
	 * @description the following method returns the data for listing
	 *
	 *
	 *
	 */
	
	function getFeeds($pageNo,$limit,$param,$output, $orderBy)
	{
		$Qry	=	"SELECT * FROM feed_details ";
		$Result	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $param, $output, $orderBy);
		return $Result;
	}

	
	/**
	 * @description The following methid returns the second levek categories
	 *
	 */	
	function GetAllSecondLevelCategory()
	{
		$qry				=	"SELECT  * FROM master_category WHERE level='0' and active='y'";
		$rs['cat_id'] 		= 	$this->db->get_col($qry, 0);
		$rs['cat_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	
	
	/**
	 * @description		Validation method
	 *
	 *
	 *
	 */
	function validateFeedForm($REQUEST)
	{
		$msg	=	'';
		extract($REQUEST);
		
		if(trim($feed_title) == '') 
			$msg	.=	'Feed Title required<br>';
		
		if(trim($feed_headertext) == '') 
			$msg	.=	'Feed Header Text required<br>';
		
		/*if(trim($feed_footertext) == '') 
			$msg	.=	'Feed Footer Text required<br>';*/
		
		if($msg == '')
			return TRUE;
		else
			return $msg;
	}
	
	
	
	/**
	 * @description		add Edit method
	 *
	 *
	 *
	 */
	function feedAddEdit($REQUEST)
	{
		$msg	=	'';
		extract($REQUEST);
		
		if($feed_id != '') {
			$Qry	=	"UPDATE feed_details SET feed_title = '$feed_title', listing_category = '$listing_category', 
						feed_headertext = '$feed_headertext', feed_footertext = '$feed_footertext', feed_status = '$feed_status' 
						WHERE feed_id = '$feed_id'";
			$msg	=	'Feed Updated Successfully';
			$this->db->query($Qry);
		} else {
			$Qry	=	"INSERT INTO feed_details (feed_title, listing_category, feed_headertext, feed_footertext, feed_status) 
						VALUES ('$feed_title','$listing_category','$feed_headertext','$feed_footertext','$feed_status')";
			$msg		=	'Feed Added Successfully';
			$this->db->query($Qry);
			$feed_id	=	$this->db->insert_id;
			
			$FeedPath	=	SITE_URL.'/html/feedfiles/'.$feed_id.'_feed.xml';
			$FeedFile	=	SITE_PATH.'/html/feedfiles/'.$feed_id.'_feed.xml';
			$fp			=	fopen($FeedFile,'w');  
			fwrite($fp,'');
			fclose($fp);
			
			$Qry1	=	"UPDATE feed_details SET feed_file = '$FeedPath' WHERE feed_id = '$feed_id'";
			$this->db->query($Qry1);
		}

		return $msg;
		
	}
	
	/**
	 * @description  The following methid returns the feed details from a feed_id
	 *
	 *
	 *
	 */
	function getFeedDetailsFromFeedId($feed_id)
	{
		$Qry	=	"SELECT * FROM feed_details WHERE feed_id = '$feed_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	
	
	/**
	 * @description The following method returns the node list associate with a fEED Feed page wise
	 *
	 *
	 *
	 */
	function getNodesOfFeed($feed_id,$pageNo,$limit,$param,$output, $orderBy)
	{
		$Qry	=	"SELECT * FROM feed_nodes WHERE feed_id = '$feed_id' ";
		$Result	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $param, $output, $orderBy);
		return $Result;	
	}

	
	/**
	 * @description The following method returns the combo for filling the static nodes related with flyers
	 *
	 *
	 *
	 */
	function getStaticFieldsForComboFilling()
	{
		$StaticFieldCombo				=	array();
		$Qry							=	"SELECT id, fieldname FROM feed_static_nodes WHERE active = 'Y'";
		$StaticFieldCombo['id']			=	$this->db->get_col($Qry, 0);
		$StaticFieldCombo['fieldname']	=	$this->db->get_col($Qry, 1);
		
		return $StaticFieldCombo;
		
	}
	
	
	/**
	 * @description The following method returns the  parent node list for cmbo filling
	 * 
	 *
	 *
	 */
	function getParentNodeCombo($feed_id)
	{
		$ParentNodes	=	array();
		$Qry			=	"SELECT node_id, node_name FROM feed_nodes WHERE haschild_nodes = 'Y' AND feed_id = '$feed_id'";
		$ParentNodes['node_id']		=	$this->db->get_col($Qry, 0);
		$ParentNodes['node_name']	=	$this->db->get_col($Qry, 1);
		
		return $ParentNodes;

	}
	
	
	/**
	 * @description 	The following method validates the node form
	 *
	 *
	 *
	 */
	function validateNodeAddEditForm($REQUEST)
	{
		$msg	=	'';
		extract($REQUEST);
		
		if($node_name	==	'')
			$msg	.=	'Node Name Required<br>';
		
		if($haschild_nodes	==	'N') {
			if($staticfield_id == '' && $dynamicfield_id == '')
				$msg	.=	'Flyer field name required<br>';
			
			if($parent_nodeid == '') 
				$msg	.=	'Parent Node required<br>';
		}
		
		if($listing_container == 'Y') {
			if(trim($node_id) != '')
				$Qry2	=	"SELECT COUNT(*) AS TotCount FROM feed_nodes WHERE listing_container = 'Y' AND feed_id = '$feed_id' 
							AND node_id != '$node_id' ";
			else
				$Qry2	=	"SELECT COUNT(*) AS TotCount FROM feed_nodes WHERE listing_container = 'Y' AND feed_id = '' ";

			$Row2	=	$this->db->get_row($Qry2, ARRAY_A);
			
			if($Row2['TotCount'] > 0)
				$msg	.=	'List Container Already defined';
		}
		
		
		
		if($haschild_nodes	==	'Y' && ($staticfield_id != '' || $dynamicfield_id != ''))
			$msg	.=	'Parent Node can not contain features<br>';
		
		
		if($haschild_nodes	==	'Y' && $parent_nodeid == '') {
			
			if(trim($node_id) != '')
				$Qry	=	"SELECT COUNT(*) AS TotCount FROM feed_nodes WHERE haschild_nodes = 'Y' AND parent_nodeid = '0' 
							AND feed_id = '$feed_id' AND node_id !='$node_id'";
			else
				$Qry	=	"SELECT COUNT(*) AS TotCount FROM feed_nodes WHERE haschild_nodes = 'Y' AND parent_nodeid = '0' 
							AND feed_id = '$feed_id'";
							
							
							
			$Row	=	$this->db->get_row($Qry, ARRAY_A);
			if($Row['TotCount'] > 0)
				$msg	.=	'Root Node already defined<br>';
		}
		
		if($msg == '')
			return TRUE;
		else
			return $msg;	
	
	}
	
	
	/**
	 * @description	The following method adds and edits the node item
	 *
	 *
	 *
	 *
	 */
	function addEditNodeItem($REQUEST)
	{
		extract($REQUEST);
		
		if(trim($node_id) == '') {
			$InsertArr	=	array('feed_id' => $feed_id, 'node_name' => $node_name, 'haschild_nodes' => $haschild_nodes, 'listing_container' => $listing_container);
			
			if($parent_nodeid != '')
				$InsertArr	=	array_merge($InsertArr, array('parent_nodeid' => $parent_nodeid));
			
			if($haschild_nodes == 'Y')
				$InsertArr	=	array_merge($InsertArr, array('field_id' => '', 'field_status' => ''));
			
			if($haschild_nodes == 'N') {
				if($staticfield_id != '')
					$InsertArr	=	array_merge($InsertArr, array('field_id' => $staticfield_id, 'field_status' => 'S'));
				if($dynamicfield_id != '')
					$InsertArr	=	array_merge($InsertArr, array('field_id' => $dynamicfield_id, 'field_status' => 'D'));
			}
		
			$this->db->insert("feed_nodes", $InsertArr);
			
		}
		
		if(trim($node_id) != '') {
			$UpdtArr	=	array('feed_id' => $feed_id, 'node_name' => $node_name, 'haschild_nodes' => $haschild_nodes, 'listing_container' => $listing_container);
			
			if($parent_nodeid != '')
				$UpdtArr	=	array_merge($UpdtArr, array('parent_nodeid' => $parent_nodeid));
			else
				$UpdtArr	=	array_merge($UpdtArr, array('parent_nodeid' => 0));	
			
			if($haschild_nodes == 'Y')
				$UpdtArr	=	array_merge($UpdtArr, array('field_id' => '', 'field_status' => ''));
			
			if($haschild_nodes == 'N') {
				if($staticfield_id != '')
					$UpdtArr	=	array_merge($UpdtArr, array('field_id' => $staticfield_id, 'field_status' => 'S'));
				if($dynamicfield_id != '')
					$UpdtArr	=	array_merge($UpdtArr, array('field_id' => $dynamicfield_id, 'field_status' => 'D'));
			}
			
			$this->db->update("feed_nodes", $UpdtArr, " node_id = '$node_id' ");
		}
		
		return TRUE;
		
	}
	
	
	
	/**
	 * @description 	The following method removes the selected nodes from the feed nodes list
	 *
	 *
	 *
	 */
	function removeNodes($REQUEST)
	{
		extract($REQUEST);
		
		if(count($node_ids > 0)) {
			foreach($node_ids as $node_id) {
				$Qry	=	"DELETE FROM feed_nodes WHERE node_id = '$node_id'";
				$this->db->query($Qry);
			}
		}
		return TRUE;
	}
	
	
	/**
	 * @description	The following method returns the node details corresponding to a node
	 *
	 *
	 *
	 */
	function getNodeDetailsFromId($node_id)
	{
		$NodeDetails	=	array();
		$Qry	=	"SELECT * FROM feed_nodes WHERE node_id = '$node_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		
		$NodeDetails['node_name']			=	$Row['node_name'];
		$NodeDetails['haschild_nodes']		=	$Row['haschild_nodes'];
		$NodeDetails['parent_nodeid']		=	$Row['parent_nodeid'];
		$NodeDetails['listing_container']	=	$Row['listing_container'];

		if($Row['field_status'] == 'S')
			$NodeDetails['staticfield_id']	=	$Row['field_id'];
		else
			$NodeDetails['staticfield_id']	=	'';	

		
		if($Row['field_status'] == 'D')
			$NodeDetails['dynamicfield_id']	=	$Row['field_id'];
		else
			$NodeDetails['dynamicfield_id']	=	'';		
			
		return $NodeDetails;
		
	}
	
	
	/**
	 * @description		The following method returns the node list values with the field name attached
	 *
	 *
	 */
	function processNodeList($Result)
	{
		$ResultArr	=	array();
		$Arrindx	=	0;

		foreach($Result as $RowObj) {
			$TmpObj		=	$RowObj;
			if($RowObj->haschild_nodes == 'N' && $RowObj->field_status == 'S') {
				$Qry	=	"SELECT fieldname FROM feed_static_nodes WHERE id = '{$RowObj->field_id}'";
				$Row	=	$this->db->get_row($Qry, ARRAY_A);
				$TmpObj->fieldname	=	$Row['fieldname'];
			}
			if($RowObj->haschild_nodes == 'D' && $RowObj->field_status == 'S') {
				$Qry	=	"SELECT label FROM flyer_form_features WHERE feature_id = '{$RowObj->field_id}'";
				$Row	=	$this->db->get_row($Qry, ARRAY_A);
				$TmpObj->fieldname	=	$Row['label'];
			}
			
			$ResultArr[$Arrindx]	=	$TmpObj;
			$Arrindx++;
		}
		return $ResultArr;
	}
	
	
	/**
	 * @description		The following method removes the selected feeds from the database
	 *
	 *
	 *
	 */
	function removeFeeds($REQUEST)
	{
		extract($REQUEST);
		
		if(count($feed_ids) > 0) {
			foreach($feed_ids as $feed_id) {
				$Qry1	=	"DELETE FROM feed_details WHERE feed_id = '$feed_id'";
				$Qry2	=	"DELETE FROM feed_nodes WHERE feed_id = '$feed_id'";
				$this->db->query($Qry1);
				$this->db->query($Qry2);
			}
		}
		
		return TRUE;	
	}
	
	
	
	/**
	 * @description 	The following method returns the nodes associated with the feed for Feed creation
	 *
	 *
	 *
	 */
	function getFeedNodes($feed_id)
	{
		$FeedNodes		=	array();
		$ArrIndx		=	0;
		
		$Qry1	=	"SELECT node_name, field_id, field_status FROM feed_nodes WHERE feed_id = '$feed_id' AND haschild_nodes = 'N'";
		$Rows1	=	$this->db->get_results($Qry1, ARRAY_A);
		
		foreach($Rows1 as $Row) {
			
			$field_status	=	$Row['field_status'];
			$field_id		=	$Row['field_id'];
			
			if($field_status == 'S') {
				$Qry2	=	"SELECT db_fieldname FROM feed_static_nodes WHERE id = '$field_id'";
				$Row2	=	$this->db->get_row($Qry2, ARRAY_A);	

				$FeedNodes[$ArrIndx]['node_name']	=	$Row['node_name'];
				$FeedNodes[$ArrIndx]['field_name']	=	$Row2['db_fieldname'];
				
				$ArrIndx++;
			}	
			
			if($field_status == 'D') {
				$Qry3	=	"SELECT label FROM flyer_form_features WHERE feature_id = '$field_id'";
				$Row3	=	$this->db->get_row($Qry3, ARRAY_A);	

				$FeedNodes[$ArrIndx]['node_name']	=	$Row['node_name'];
				$FeedNodes[$ArrIndx]['field_name']	=	$Row2['label'];
				
				$ArrIndx++;
			}
		}
		
		return $FeedNodes;
	
	}
	
	
	/**
	 * @description 	The following method returns the flyers corresponding to the category of feed
	 *
	 *
	 *
	 *
	 */
	function getListingsOfFeedCategory($feed_id)
	{
		$Listings	=	array();
		$ArrIndx	=	0;
		
		$Qry1		=	"SELECT listing_category FROM feed_details WHERE feed_id = '$feed_id'";
		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);	
		$listing_category	=	$Row1['listing_category'];
		
		if($listing_category == 0)
			$Qry2	=	"SELECT flyer_id FROM flyer_data_basic WHERE publish = 'Y' AND active = 'Y' AND expire_date > CURDATE()";
		else
			$Qry2	=	"SELECT flyer_id FROM flyer_data_basic WHERE 
						form_id IN (SELECT form_id FROM flyer_form_master WHERE category_name = '$listing_category') 
						AND publish = 'Y' AND active = 'Y' AND expire_date >= CURDATE()";
		
		$Rows2	=	$this->db->get_results($Qry2, ARRAY_A);

		foreach($Rows2 as $Row2) {
			$Listings[$ArrIndx]	=	$Row2['flyer_id'];
			$ArrIndx++;
		}
		
		return $Listings;
	}
	
	
	/**
	 * @description		The following method generates feed for Auto submission 
	 *
	 *
	 *
	 */
	function generateFeedForAutoSubmission($feed_id, $ListingIds)
	{
		
		$this->XMLFeedText	=	'';
		$FeedFile	=	SITE_PATH.'/html/feedfiles/'.$feed_id.'_feed.xml';
		
		$Qry0		=	"SELECT COUNT(*) AS TotCount FROM feed_nodes WHERE feed_id = '$feed_id'";
		$Row0		=	$this->db->get_row($Qry0, ARRAY_A);	
		$TotCount	=	$Row0['TotCount'];
		/**
		 * The following is the condition where no node wxists for a feed. Then creating an empty Document
		 */
		if($TotCount == 0) { 
			if(file_exists($FeedFile))
				@unlink($FeedFile);
			$fp	=	fopen($FeedFile,'w');
			fwrite($fp,'');
			fclose($fp);
			chmod($FeedFile, 0777); 	
			return $FeedFile;
		}
		
		
		$Qry1	=	"SELECT feed_headertext, feed_footertext FROM feed_details WHERE feed_id = '$feed_id'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		$this->XMLFeedText	.=	$Row1['feed_headertext']."\n";
		
		$Qry2				=	"SELECT node_name AS RootnodeName,listing_container AS listing_container FROM feed_nodes WHERE parent_nodeid = '0' AND haschild_nodes = 'Y' AND feed_id = '$feed_id'";
		$Row2				=	$this->db->get_row($Qry2, ARRAY_A);	
		$RootnodeName		=	$Row2['RootnodeName'];
		$listing_container	=	$Row2['listing_container'];
		
		if($listing_container == 'N')
			$this->XMLFeedText	.=	'<'.$RootnodeName.">";

		$Qry3			=	"SELECT node_id FROM feed_nodes WHERE haschild_nodes = 'Y' 
							AND listing_container = 'Y' AND feed_id = '$feed_id'";
		$Row3			=	$this->db->get_row($Qry3, ARRAY_A);	
		$StartNodeId	=	trim($Row3['node_id']);
		
		if($StartNodeId != '') {
			foreach($ListingIds as $ListingId) {
				$FlyerData		=	$this->FlyerObj->getFlyerDataForPreview($ListingId);
				$ListingData	=	$this->processFlyerDataForFeedGeneration($FlyerData);
				$this->FlyerXMLFeedData	=	'';
				$this->getFlyerXMLForFeedCreation($StartNodeId, $ListingData, $feed_id);
				$this->XMLFeedText	.=	$this->FlyerXMLFeedData;
			}
		} #Close if condition
		
		if($listing_container == 'N')
			$this->XMLFeedText	.=	"\n</".$RootnodeName.">";
		
		$this->XMLFeedText	.=	"\n".$Row1['feed_footertext'];
		
		$FeedFile	=	SITE_PATH.'/html/feedfiles/'.$feed_id.'_feed.xml';
		if(file_exists($FeedFile))
			@unlink($FeedFile);

		$fp	=	fopen($FeedFile,'w');
		fwrite($fp,$this->XMLFeedText);
		fclose($fp);
		chmod($FeedFile, 0777); 
		
		return $FeedFile;
		
	}	
	
	
	
	/**
	 * @description		The following method processes the flyer data for feed creation
	 *
	 *
	 *
	 *
	 */
	function processFlyerDataForFeedGeneration($FlyerData)
	{
		$ListingData					=	array();
		$ListingData					=	array_merge($ListingData, $FlyerData['ContactInfo']);
		$ListingData['title']			=	$FlyerData['title'];
		$ListingData['description']		=	$FlyerData['description'];
		$ListingData['flyer_id']		=	$FlyerData['flyer_id'];
		$ListingData['category']		=	$FlyerData['category'];
		$ListingData['flyer_url']		=	$FlyerData['flyer_url'];
		$ListingData['listing_url']		=	$FlyerData['listing_url'];
		$ListingData['image_url']		=	$FlyerData['image_url'];
		$ListingData['expire_date']		=	$FlyerData['expire_date'];
		$ListingData['modified_date']	=	$FlyerData['modified_date'];
		
		foreach($FlyerData['blocks'] as $Key => $ItemArray) {
			if(count($ItemArray['features']) > 0)
				$ListingData	=	array_merge($ListingData, $ItemArray['features']);
		}
		
		return $ListingData;
		
	}
	
	
	
	/**
	 * @description		The following method returns the single flyer XML data for feed creation
	 *
	 *
	 */
	function getFlyerXMLForFeedCreation($StartNodeId, $ListingData, $feed_id)
	{
		$Qry0			=	"SELECT node_name FROM feed_nodes WHERE node_id = '$StartNodeId'";
		$Row0			=	$this->db->get_row($Qry0, ARRAY_A);
		$StartNodeName	=	$Row0['node_name'];

		$NodeLevel0		=	$this->getNodePositionLevel($StartNodeId);
		$NextLine0		=	str_repeat("\t", $NodeLevel0 - 1);
		
		$Qry1	=	"SELECT node_id,node_name,field_id,haschild_nodes,field_status FROM feed_nodes WHERE 
					parent_nodeid = '$StartNodeId' AND feed_id= '$feed_id'";
		$Rows1	=	$this->db->get_results($Qry1, ARRAY_A);			
		
		$this->FlyerXMLFeedData	.=	"\n$NextLine0<".$StartNodeName.'>';
		
		foreach($Rows1 as $Row1) {
			$haschild_nodes		=	$Row1['haschild_nodes'];
			$field_id			=	$Row1['field_id'];
			$field_status		=	$Row1['field_status'];
			$node_id			=	$Row1['node_id'];
			$node_name			=	$Row1['node_name'];
			
			if($haschild_nodes == 'N' && $field_id != 0 && $field_id != '') {
				
				$NodeLevel1	=	$this->getNodePositionLevel($node_id);
				$NextLine1	=	str_repeat("\t", $NodeLevel1 - 1);
				
				if($field_status == 'S')
					$Qry2	=	"SELECT db_fieldname AS FieldName FROM feed_static_nodes WHERE id = '$field_id'";
				else
					$Qry2	=	"SELECT label AS FieldName FROM flyer_form_features WHERE feature_id = '$field_id'";
				
				$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
				$FieldName	=	$Row2['FieldName'];
				
				if(array_key_exists($FieldName,$ListingData))
					$NodeData	=	htmlspecialchars($ListingData[$FieldName], ENT_QUOTES);
				else
					$NodeData	=	'';	
				
				$this->FlyerXMLFeedData	.=	"\n$NextLine1<".$node_name.'>'.$NodeData.'</'.$node_name.">";
				
			} else {
				$this->getFlyerXMLForFeedCreation($node_id, $ListingData, $feed_id);
			}
			
		} #Close foreach
					
		$this->FlyerXMLFeedData	.=	"\n$NextLine0</".$StartNodeName.'>';
		
	}
	
	
	
	/**
	 * @description The following method reurns the position of the current node from that of the root node
	 *
	 *
	 *
	 *
	 */
	function getNodePositionLevel($node_id)
	{
		$this->NodeLevel	=	0;
		$this->getNodeLevel($node_id);
		return $this->NodeLevel;
	}
	
	
	/**
	 * @description	 The following is a reccursive function for finding the level of a node in an xml document
	 *
	 *
	 *
	 */
	function getNodeLevel($node_id)
	{
		$Qry1			=	"SELECT parent_nodeid FROM feed_nodes WHERE node_id = '$node_id'";
		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
		$parent_nodeid	=	trim($Row1['parent_nodeid']);
		if( $parent_nodeid == 0 || $parent_nodeid == '' ) {
			return;
		} else {
			$this->getNodeLevel($parent_nodeid);
			$this->NodeLevel++;
		}	
		
	}
	
	
	
	/**
	 * @description		The following is a function cron task processing. The following method included in a cron task file
	 *
	 *
	 *
	 *
	 *
	 */
	function generateAutoSubmissionFeeds()
	{
		$FeedIds		=	$this->getActiveFeedIdsForFeedGeneration();
		foreach($FeedIds as $FeedId) {
			$ListingIds		=	$this->getListingsOfFeedCategory($FeedId);
			$this->generateFeedForAutoSubmission($FeedId, $ListingIds);
		}	
		return;
	}
	
	
	
	/**
	 * @description		The following method returns the active feed ids for feed generation
	 *
	 *
	 *
	 *
	 */
	function getActiveFeedIdsForFeedGeneration()
	{
		$Ids	=	array();
		$Qry	=	"SELECT feed_id FROM feed_details WHERE feed_status = 'Y'";
		$Rows1	=	$this->db->get_results($Qry, ARRAY_A);

		$ArrIndx	=	0;
		foreach($Rows1 as $Row1)
			$Ids[$ArrIndx++]	=	$Row1['feed_id'];

		return 	$Ids;
		
	}
	
	
	
}
?>