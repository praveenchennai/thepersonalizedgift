{literal}
<script language="javascript">
function ONComplete(param) {
	if(!param) {
		return false;
	}

	areaedit_editors.body.focusEditor();
	var sel = areaedit_editors.body._getSelection();
	if(sel != null) {
	 	var range = areaedit_editors.body._createRange(sel);
	}
	var editor = areaedit_editors.body;	// for nested functions

	txt = document.createTextNode(param);

	if(sel != null) {
		if (HTMLArea.is_ie) {
			range.pasteHTML(txt.nodeValue);
		} else {
			editor.insertNodeAtSelection(txt);
		}
	}
}
</script>
{/literal}
<form method="POST" name="generalFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <tr>
  	<td colspan="3" >
	 <table align="center" width="98%">
	 	<tr> 
      <td nowrap class="naH1"  align="left">General Messages - {$GENERAL.name}</td> 
	  </tr>
	  <tr>
	      <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=messagelist{/makeLink}&sId=General Message&fId={$smarty.request.fId}&mId={$MID}">General Messages List</a></td>
      <!-- <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=messagelist{/makeLink}">List General Messages</a>
	   </td> -->
    </tr> 
   
	 </table>
	</td>
	</tr>
     <tr> 
      <td colspan=3 class="naGridTitle">General Message Details</td> 
    </tr> 
    <tr class=naGrid2> 
      <td colspan="3" valign=top><input type="hidden" name="description" value="{$GENERAL.description}">{$GENERAL.description}</td> 
    </tr>
	<tr class=naGrid1> 
      <td width=40% align="right">Subject</td> 
      <td width=1%>:</td> 
      <td width="59%"><input type="text" name="subject" value="{$GENERAL.subject}" class="formText" size="50" maxlength="255" ></td> 
    </tr>
	  <tr>
      <td colspan=3 class="naGridTitle">Message Body</td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><textarea id="body" name="body" rows="30" cols="60">{$GENERAL.body}</textarea></td>
        </tr>
      </table></td>
    </tr>
 <tr>
      <td colspan="3" class="naGridTitle">Database Variables </td>
    </tr>
    <tr class="naGrid1">
      <td colspan="3" align="center" valign="top"><table width="540" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input type="button" name="Button" value="Insert Database Variables" onclick="Dialog('{makeLink mod=newsletter pg=general}act=data_msg&id={$smarty.request.id}{/makeLink}', ONComplete, null);" style="border:1px solid black; height:30px;" /></td>
            <td>&nbsp;</td>
            <td>Database variables are replaced by their corresponding value, when the order is placed. <br />
            E.g. <strong>%_USERNAME_%</strong> will be replaced by <strong>billgates</strong> </td>
          </tr>
        </table></td>
    </tr> 
    <tr class="naGrid1">
      <td colspan="3" valign="top">&nbsp;</td>
    </tr>
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
