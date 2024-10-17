<script language="javascript">
{literal}
function getKeyCode(e) {
 if (window.event)
    return window.event.keyCode;
 else if (e)
    return e.which;
 else
    return null;
}
function _keyCheck(e) {
	key = getKeyCode(e);
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
		return true;
	else if ((key > 96 && key < 123) || (key > 47 && key < 58) || key == 95)
		return true;
	else
		return false;
}
function _seoURL(val) {
	document.menuFrm.seo_url.value = '';
	for(i=0; i<val.length; i++) {
		key = val.charAt(i).charCodeAt(0);
		str = val.charAt(i);
		if ((key > 96 && key < 123) || (key > 64 && key < 91) || (key > 47 && key < 58) || key == 95 || key == 32) {
			if (key == 32) str = '_';
			if (key > 64 && key < 91) str = String.fromCharCode(key+32);
			document.menuFrm.seo_url.value += str;
		}
	}
}
{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"> 
    <tr>
      <td width="3%">&nbsp;</td>
      <td width="30%" valign="top">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">Drop Downs</td> 
                  <td nowrap align="right" class="titleLink"><!-- <a href="{makeLink mod=$smarty.request.mod pg=menu}act=list&section_id={$smarty.request.section_id}{/makeLink}&sId={$SUBNAME}&mId={$MID} ">Add New</a> --></td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="5" cellspacing="0"> 
				<tr>
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td nowrap class="naGridTitle" height="24" align="left">Drop Down Names</td> 
				  <td width="10%" nowrap class="naGridTitle" align="center">&nbsp;</td>
                </tr>
				{if $HEALTH_CARE eq '1'}
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=1{/makeLink}">Medication	</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr> 
              </tr>
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=2{/makeLink}">Quantity</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr> 
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=3{/makeLink}">SIG</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr>
				</tr>
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=4{/makeLink}">Brand/Generic</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
               </tr>  
              </tr>
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=5{/makeLink}">Refill</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr>
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=6{/makeLink}">State</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr>
<!--
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=7{/makeLink}">Height foot</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr>
				<tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&id=8{/makeLink}">Height inches</a></td> 
				  <td nowrap align="center">&nbsp;</td> 
                </tr>-->
				{/if} 
              </table></td> 
          </tr> 
        </table>      </td>
      <td width="3%">&nbsp;</td>
      <td width="61%" valign="top">
	  <form name="menuFrm" action="" method="post">
	  {if $smarty.request.id}
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td width="80%" nowrap class="naH1">Values<!--  of {$MENU.name} --></td> 
                  <td width="20%" align="right" nowrap class="titleLink"><!-- <a href="{makeLink mod=$smarty.request.mod pg=page}act=form&menu_id={$smarty.request.id}{/makeLink}">Add New</a> --></td> 
                </tr> 
              </table></td> 
          </tr>
          <tr> 
            <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
                {if count($PAGE_LIST) > 0}
                <tr>
                  <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
                  <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
                  <td nowrap class="naGridTitle" height="24" align="left">Values </td> 
                  <td width="10%" nowrap class="naGridTitle" align="center">&nbsp;</td> 
                </tr>
                {foreach from=$PAGE_LIST item=page}
                <tr class="{cycle name=a values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&&value_id={$page->drop_down_id}&id={$smarty.request.id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
                  <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=delete&value_id={$page->drop_down_id}&id={$smarty.request.id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=drop_down}act=list&&value_id={$page->drop_down_id}&id={$smarty.request.id}{/makeLink}">{$page->value}</a></td> 
                  <td valign="middle" height="24" align="center">&nbsp;</td> 
				  <input type="hidden" name="group_id" value="{$page->group_id}">
                </tr> 
                {/foreach}
                <tr> 
                  <td colspan="4" class="msg" align="center" height="30">{$PAGE_NUMPAD}</td> 
                </tr>
                {else}
                 <tr class="naGrid2"> 
                  <td colspan="4" class="naError" align="center" height="30">No Records</td> 
                </tr>
                {/if}
              </table></td> 
          </tr> 
        </table><br>
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">{if $smarty.request.value_id}Edit{else}Add{/if} Values</td> 
                </tr> 
              </table></td> 
          </tr> 
		  {if isset($MESSAGE)}
		  <tr class="naGrid2">
			<td height="25" colspan="3"><div align=center class="element_style">
		    <span class="naError">{$MESSAGE}</span></td>
		  </tr>
		  {/if}
          <tr> 
            <td><table width="100%" border="0" cellpadding="5" cellspacing="0">
			 <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Value</td>
                <td>:</td>
                <td>
					{foreach from=$VALUE_LIST item=value}{/foreach}
						<input type="hidden" name="drop_down_id" value="{$value->drop_down_id}">
						<input name="name" type="text" size="30" value="{$value->value}" oncontextmenu="return false;" autocomplete="off" onkeyup="_seoURL(this.value);">
				</td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td colspan="3" align="center"><input type="submit" name="Submit" value="Submit" class="naBtn">
	                <!-- <input type="reset" name="Submit2" value="Reset" class="naBtn"> --></td>
              </tr>
            </table></td> 
          </tr> 
        </table>
  		{/if}
		</form>
		</td>
      <td width="3%">&nbsp;</td>
    </tr>
</table>