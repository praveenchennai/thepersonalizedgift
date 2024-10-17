{literal}
<script language="javascript">
function chkval()
{
	var rit=document.forms[0].settings_all.options;
	
	for (var i=0;i<rit.length;i++)
	{
		if (i>0)
		{
			document.forms[0].hf1.value=document.forms[0].hf1.value + "," + rit[i].value;
		}
		else
		{
			document.forms[0].hf1.value=rit[i].value;
		}		
	}
	
	
}
function addSrcToDestList() 
	{	
		
		destList	= window.document.forms[0].settings_all;
		srcList 	= window.document.forms[0].product_all; 
		
	
	var len = destList.length;
	for(var i = 0; i < srcList.length; i++) 
	{
	if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
	
	var found = false;
	for(var count = 0; count < len; count++) 
	{
		if (destList.options[count] != null) 
		{
			if (srcList.options[i].text == destList.options[count].text) 
			{
			found = true;
			break;				
			}
		}
	}
	
	if (found != true) 
	{
	destList.options[len] = new Option(srcList.options[i].text,srcList.options[i].value); 
	len++;
	
	
				 }
		  }
	   }
	  return false;
	}
		
	function deleteFromDestList() 
	{	
		
		var destList  = window.document.forms[0].settings_all;
		
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--) {
	if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
	destList.options[i] = null;
		  }
	   }
	     return false;
	}
</script>
{/literal}

<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
   
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=settings&mId={$MID}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">{$PRD_SETTINGS.name} </span></td> 
    </tr> 
    <tr class=naGrid1>
      <td width="394" align="right" valign=top>&nbsp;</td>
      <td colspan="2" valign=top>&nbsp;</td>
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>All Accessories </td>
      <td width="84" valign=top>&nbsp;</td>
      <td width="484">{$PRD_SETTINGS.name}</td>
    </tr>
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">
        <select name="product_all" size="10" multiple id="product_all" style="width:200">
          
	 {html_options values=$PRODUCT_LIST.id output=$PRODUCT_LIST.name selected=$SELECTEDIDS.id}
	   
        
        </select>
      </div></td>
      <td align="center" valign=middle><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">{if $STORE_PERMISSION.edit == 'Y'}<input type="button" class="naBtn" name="Submit" value="Add &gt;&gt;" style="width:90px; " onClick="return addSrcToDestList()">{/if}</td>
        </tr>
        <tr>
          <td height="5" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">{if $STORE_PERMISSION.edit == 'Y'}<input type="button" class="naBtn" name="Submit2" value="&lt;&lt; Remove" onClick=" return deleteFromDestList()">{/if}</td>
        </tr>
      </table></td>
      <td><select name="settings_all" size="10" multiple id="settings_all" style="width:200" >
	 {html_options values=$PRODUCT_LIST_SETTINGS.id output=$PRODUCT_LIST_SETTINGS.name selected=$SELECTEDIDS.id}
	   
      </select>
	  <input type="hidden" name="hf1" value="">
	  </td>
    </tr>
    <tr class=naGrid1>
      <td valign=top>&nbsp;</td>
      <td align="center" valign=middle>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	{if $STORE_PERMISSION.edit == 'Y'}
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	       <input type=submit value="Submit" class="naBtn" onClick="chkval()">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>{/if}</form> 
	{if ($smarty.request.id) gt 0}
    <tr>
      <td colspan=3 valign=center>&nbsp;</td>
    </tr>
{/if}
</table>
