<?php
/**
 * Grid display
 **/

/**
 * Class to model a column
 **/

class column{

	function column($name, $align, $fieldName, $sortField, $editField, $url, $javaScriptConfirm, $icon, $isFieldImage, $callFunction, $urlAppend, $searchField, $checkField, $evalFunction, $width)	{
		$this->name = $name;
		$this->align = $align;
		$this->fieldName = $fieldName;
		$this->sortField = $sortField; // Parameter to sort the fields
		$this->editField = $editField; // Parameter to be passed to the next page
		$this->url = $url;
		$this->javaScriptConfirm = $javaScriptConfirm; //Javasctipt confirmation for delet etc after clickng the column.
		$this->icon = $icon;	//icon for column
		$this->isFieldImage = $isFieldImage;	//is field an image.. useful to show active inactive
		$this->callFunction = $callFunction;	//all an external php funvtion to display column
		$this->urlAppend	= $urlAppend;	//url to be appended in the links of the grid.. useful for search results
		$this->searchField = $searchField;	//Is the column a searchable field
		$this->checkField = $checkField;
		$this->evalFunction = $evalFunction;
		$this->width = $width;
	}
}

/**
 * Grid Class
 **/
class grid
{
	/**
	* Constructor
	* $sql - 1			[Sql for the Grid]
	* $orderBy - 2		[orderBy String]
	* $pageNo - 3		[Page No]
	* $pageOffset - 4	[Page Offset]
	* $emptyMsg - 5		[Empty Message for page]
	* $urlAppend - 6	[Url to be appeneded]
	* $needSearch - 7	[Does the Page need Search? false/true]
	* $rowHeight - 8	[Height of a row]
	* $imgPath - 9		[Image path]
	* $numPad - 10		[Number Pad true/false]
	* $actionPage - 11	[Action Page if the Multiple Checkboxes]
	* $submitBtn - 12	[Submit button message]
	* $hiddenArray - 13	[Array of hidden Ids]
	*/
	function grid($sql, $orderBy="", $pageNo="", $urlAppend="", $pageOffset="", $emptyMsg="", $needSearch="", $rowHeight="", $imgPath="", $numPad=true, $actionPage="", $submitBtn="", $hiddenArray="")
	{
		$this->sql = $sql;
		$this->orderBy = $orderBy;
		$this->pageNo = $pageNo ? $pageNo : 1;
		$this->pageOffset = $pageOffset ? $pageOffset : 10;
		$this->emptyMsg = $emptyMsg;
		$this->urlAppend = $urlAppend;
		$this->needSearch = $needSearch===true ? true : false;
		$this->rowHeight = $rowHeight ? $rowHeight : 24;
		$this->imgPath = $imgPath ? $imgPath : SITE_URL."/images/grid";
		$this->numPad = $numPad===false ? false : true;
		$this->actionPage = $actionPage;
		$this->submitBtn = $submitBtn;
		$this->hiddenArray = $hiddenArray;
		$this->submitIcon =  $imgPath."/grid.search.gif";
		$this->class1 = "naGrid1";
		$this->class2 = "naGrid2";
		$this->linkClass = "naGridTitle";
		$this->headClass = "naH1";
		$this->nRowCount = 0;
		$this->columnSettings = array();
	}

	/**
	* @return void
	* @param $name [ColumnName/Heading]		1
	* @param $align [HorizontalAlignment-left/center/right]		2
	* @param $fieldName [FieldNameOf/inTheSelectQuery] [{value=-1}->Display serial number]	3
	* @param $sortField [Sort By Field] {default value = false}		4
	* @param $editField [EditField] {default value = false}		5
	* @param $url unknown [Link] {default value = false}	6
	* @param $javaScriptConfirm [JavasciptConfirmation] {default value = false}		7
	* @param $icon [IconIfRequired] {value = false}		8
	* @param $isFieldImage [IsTheFieldAnImageName] {default value = false}		9	
	* @param $jsFunction [jsFunction With FieldName] {default value = false}	10
	* @param $urlAppend [urlAppend] {default value = false}		11
	* @param $searchField [Search Box Field] {default value = false - if value="SEARCH" then search the field - if field is "SUBMIT" keep it submit icon} 12
	* @param $checkField [CheckField - denotes whether checkbox is req] {default value = false} 13
	* @param $evalFunction [Evaluate the function with the value]; 14
	* @param $width [width]; 15
	* @desc Adds a column to the grid
    */
	function addColumn($name, $align, $fieldName, $sortField=false, $editField=false, $url=false, $javaScriptConfirm=false, $icon=false, $isFieldImage=false, $jsFunction=false, $urlAppend=false, $searchField=false, $checkField=false, $evalFunction=false, $width=false){
		array_push($this->columnSettings, new column($name, $align, $fieldName, $sortField, $editField, $url, $javaScriptConfirm, $icon, $isFieldImage, $jsFunction, $urlAppend, $searchField, $checkField, $evalFunction, $width));
	}

	function init(){
		global $db, $srhSbmt;
		$this->orgUrlAppend = $this->urlAppend;

		/*******************To Handle Order By************/
		if($this->orderBy && !strstr(trim($this->orderBy), ' ')){
			$order_arr = split (":", $this->orderBy);
			$this->sql = $this->sql. " ORDER BY ". $order_arr[0]. " ". $order_arr[1];
		}
		/***********************************************/
		/*************** To get total count ************/
		$rs = $db->query($this->sql);

		$this->totCount = $db->query($this->sql);
		if($this->numPad){
			list($this->rs, $numpad) = $db->get_results_pagewise($this->sql, $this->pageNo, $this->pageOffset, $this->urlAppend."&orderBy={$this->orderBy}");
		}
		else{
			$this->rs = $db->last_result;
		}
		/*********************************************/
		/************************************************************ Handles page 1 2 3 ****************************************************************************/
		$this->searchLink = $numpad;
		/************************************************************************************************************************************************************************************************************/
	}

	/**
	 * Display Grid
	 *
	 * @param [Heading of the Grid] $title
	 * @param [To Display Add Link in most Cases] $rightSideHtml
	 * @return HTML
	 */
	function display($title='', $rightSideHtml='') {
		$checkField = "";
		$imgPath = $this->imgPath;
		$urlAppend = $this->urlAppend;
		$orgUrlAppend = $this->orgUrlAppend;
		$rs = $this->rs;
		$count = count($this->columnSettings);
		if($count<=0){
			return false;
		}
		echo '<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
		  		<tr>
		  		  <td>
		  		  	<table width="98%" align="center">
		  		  	  <tr>
						<td nowrap class="'.$this->headClass.'">'.$title.'</td>
						<td nowrap align="right" class="titleLink">'.$rightSideHtml.'</td>
				  	  </tr>
		  		  	</table>
		  		  </td>
		  		</tr>
				<tr>
				  <td>
				    <table border=0 width=100%>
			<SCRIPT LANGUAGE=\"JavaScript\">
			var flag = false;
			function check_all(chkVal){
				flag = chkVal = !flag ;
				for (var i=0;i < document.grid_form.elements.length;i++){
					var e = document.grid_form.elements[i];
					if (e.type == "checkbox")e.checked = chkVal;
				}
				return false;
			}
			</SCRIPT>';

		if(count($rs)) {
			echo "<tr>";
			foreach ($this->columnSettings as $column) {
				$name = ($column->icon) ? "" : $column->name;
				$order_arr = split (":", $this->orderBy);
				if (in_array ("ASC", $order_arr)){
					$img = "grid.upArrow.gif";
					$sort = ":DESC";
				}else{
					$img = "grid.downArrow.gif";
					$sort = ":ASC";
				}
				$alignStr = $column->align ? $column->align : "center" ;

				$url = "?orderBy=".$column->sortField.$sort.$urlAppend;

				echo "<td nowrap class=\"{$this->linkClass}\" height=\"".$this->rowHeight."\" align=\"$alignStr\">";
				if($this->needSearch==false && $column->checkField) {
					if(!count($rs)) {
						echo " <a href=\"#\"  title=\"Select All\" onClick=\"return check_all(true);\"><img alt=\"Select All\" src=\"$imgPath/buttons/grid.check.gif\" border=\"0\"></a>";
					}
				}
				if($column->sortField){
					echo "<a class=\"".$this->linkClass."\" href=\"" . $url . "\"><u>" . $name . "</u></a>";
					if ($this->orderBy && in_array($column->sortField, $order_arr)){
						echo " <img  src=\"$imgPath/".$img ."\" border=\"0\">";
					}
				}else{
					echo $name;
				}
				echo "</td>";
			}
			echo "</tr>";
			reset($this->columnSettings);

			if($this->needSearch) {
				echo "<form action=\"?s01=03".$orgUrlAppend."\" method=\"post\" name=\"form2\">";
				echo "<tr>";
				foreach ($this->columnSettings as $column){
					if($column->width){
						$width="width=".$column->width;
					}elseif($column->icon){
						$width="width=20";
					}else{
						$width="";
					}

					$alignStr = $column->align ? $column->align : "center" ;
					echo "<td $width height=\"20\" nowrap class=\"naGrid2\" align=\"$alignStr\">";
					if($column->searchField == "SUBMIT"){
						if(!$hasSrchIcon){
							echo "<input align=\"middle\" class=\"img\" type=\"Image\" title=\"Search\"  border=\"0\" src=\"".$this->submitIcon."\">&nbsp;";
							$hasSrchIcon=true;
						}
					}elseif($column->searchField){
						global ${$column->searchField};
						$value = (${$column->searchField}) ? (${$column->searchField}) : $_POST[$column->searchField] ;
						if(!$hasSrchIcon){
							echo "<input align=\"middle\" class=\"img\" type=\"Image\" title=\"Search\"  border=\"0\" src=\"".$this->submitIcon."\">&nbsp;";
							$hasSrchIcon=true;
						}
						echo "<input type=\"Text\" maxlength=\"50\" size=\"15\" value=\"".$value."\" name=\"".$column->searchField."\">";
					}elseif($column->checkField){
						$checkField = $column->checkField;
						if(!$rs->EOF){
							// there is no image .
							// so disabled the code
							//echo " <a href=\"#\"  title=\"Select All\" onClick=\"return check_all(true);\"><img alt=\"Select All\" src=\"$imgPath/buttons/grid.check.gif\" border=\"0\"></a>";
						}
					}
					else{
						echo " ";
					}
					echo "</td>";

				}
				echo "</tr>";
				if($this->hiddenArray){
					foreach($this->hiddenArray  as $key => $value){
						if(is_array($value)){
							foreach($value  as $arrKey => $arrValue){
								echo  "<input type=\"hidden\" name=\"$key"."[".$arrKey."]\" value=\"$arrValue\">";
							}
						} else{
							echo  "<input type=\"hidden\" name=\"$key\" value=\"$value\">";
						}
					}
				}
				echo "<input type=\"Hidden\" name=\"srhSbmt\" value=\"Y\"></form>";
			}

			echo "<form action=\"".$this->actionPage."?1".$this->urlAppend."\" method=\"post\" name=\"grid_form\">";
			foreach ($rs as $row){
				$this->nRowCount++;
				reset ($this->columnSettings);
				$class=(($class==$this->class1) ? $this->class2 : $this->class1);
				echo "<tr>";
				foreach ($this->columnSettings as $column){
					$url = $column->url;
					$editField = $column->editField;

					if($column->width){
						$width="width=".$column->width;
					}elseif($column->icon){
						$width="width=20";
					}else{
						$width="";
					}

					echo "<td $width valign=\"middle\" class=\"$class\" height=\"".$this->rowHeight."\" align=\"".$column->align."\">";
					if($url){
						echo"<a class=\"linkOneActive\" href=\"$url?$editField=".$row->$editField.$column->urlAppend."\"";
						if($column->javaScriptConfirm){
							echo "onclick=\"javascript: return confirm('".$column->javaScriptConfirm."')\"";
						}
						echo ">";
					}
					if($column->callFunction){
						$callFunction = $column->callFunction;
						if(strstr($callFunction, ',')){
							$paramsArr = explode(",", $callFunction);
							$function = $paramsArr[0];
							echo "<a class=\"linkOneActive\" onclick=\"javascript: ".$function."('";
							$param = trim($paramsArr[1]);
							if($param){
								echo $row->$param."'";
							}

							for ($i=2; $i<count($paramsArr);$i++){
								$param = trim($paramsArr[$i]);
								if($param){
									echo ", '".$row->$param."'";
								}
							}
							echo ")\" href=\"#\">";
						}else{
							echo"<a class=\"linkOneActive\" onclick=\"javascript: ".$column->callFunction."('".$row->$editField."')\" href=\"#\">";
						}
					}

					if($column->icon){
						$icon = $column->icon;
						$ext = substr($icon,-3);
						if($ext=="gif" || $ext=="jpg" || $ext=="png" || $ext=="peg"){
							echo "<img title=\"".$column->name."\" alt=\"".$column->name."\" src=\"$imgPath/".$column->icon."\" border=\"0\">";
						}else{
							echo $icon;
						}
					}elseif($column->fieldName == -1){
						echo ++$rowcount;
					}elseif($column->fieldName && $column->isFieldImage){
						echo "<img src=\"$imgPath/".$row->{$column->fieldName}."\" border=\"0\">";
					}elseif($column->checkField){
						$chkField=explode(",", $column->checkField);
						if($chkField[1] && $chkField[2]){
							eval("$"."checked=".$row->{$chkField[1]}.$chkField[2].";");
							if($checked)$checked=" checked";
						}
						if($row->status=="1")
						$checked=" checked";
						else
						$checked="";
						$aid=$row->id;
						echo '<input type="hidden" name="all_id[]" value="'.$aid.'">';
						echo '<input class="checkbox" type="checkbox" value="'.$row->{$column->editField}.'" name="'.$chkField[0].'"'.$checked.'>';
					}elseif($column->evalFunction){
						$evalFunction = $column->evalFunction;
						if(strstr($evalFunction, ',')){
							$paramsArr = explode(",", $evalFunction);
							$function = $paramsArr[0];
							$eval =  $function."('";
							$param = trim($paramsArr[1]);
							if($param){
								$eval .= addslashes($row->$param)."'";
							}
							for ($i=2; $i<count($paramsArr);$i++){
								$param = trim($paramsArr[$i]);
								if($param){
									$eval .= ", '".addslashes($row->$param)."'";
								}
							}
							$eval .= ")";
						}else{
							$eval  =  $column->evalFunction."('".$row->$editField."')";
						}
						$eval = '$retVal='.$eval.";" ;
						eval($eval);
						echo $retVal;
					}
					elseif($column->fieldName){
						echo $row->{$column->fieldName};
					}
					if($url || $column->callFunction){
						echo "</a>";
					}
					echo "</td>";
				}
				echo "</tr>";
			}
			if($this->searchLink && $this->numPad){
				if($this->submitBtn){
					echo "<tr><td colspan=\"$count\" class=\"msg\" align=\"center\" height=\"30\">";
					echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\"><tr>";
					echo "<td width=\"10%\" class=\"msg\">&nbsp;&nbsp;<input type=\"hidden\" name=\"grid_list\" value=\"1\"><INPUT type=\"Submit\" name=\"submitBtn\" class=\"btn\" value=\"".$this->submitBtn."\"></td>";
					echo "<td colspan=\"".($count-1)."\" class=\"msg\" align=\"center\" height=\"30\">".$this->searchLink."</td>";
					echo "</tr></table>";
					echo "<td></tr>";
				}
				else{
					echo "<tr><td colspan=\"$count\" class=\"msg\" align=\"center\" height=\"30\">".$this->searchLink."</td></tr>";
				}
			}
			elseif($this->submitBtn){
				echo "<tr><td class=\"msg\" colspan=\"$count\">&nbsp;&nbsp;<input type=\"hidden\" name=\"grid_list\" value=\"1\"><INPUT border=\"0\"type=\"Submit\" name=\"submitBtn\" class=\"btn\" value=\"".$this->submitBtn."\"></td></tr>";
			}
			if($this->hiddenArray){
				foreach($this->hiddenArray  as $key => $value){
					if(is_array($value)){
						foreach($value  as $arrKey => $arrValue){
							echo  "<input type=\"hidden\" name=\"$key"."[".$arrKey."]\" value=\"$arrValue\">";
						}
					}
					else{
						echo  "<input type=\"hidden\" name=\"$key\" value=\"$value\">";
					}
				}
			}
		} else {
			if(!$this->emptyMsg) {
				$this->emptyMsg = "No Entry Found";
			}
			echo "<tr><td colspan=\"$count\" class=\"naGrid2\" align=\"left\" height=\"30\"><span class=\"txtOne\">&nbsp;".$this->emptyMsg."  </span></td></tr>";
		}
		echo "</table>";
		echo "</td></tr></table>";

	}
	function rowCount() {
		return $this->nRowCount;
	}
}
