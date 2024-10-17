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
function confirmUpdate(url)
{

	if(confirm('Are you sure want to update this content to all stores?'))
	{
		window.location=url;
	}
	else
	{
	return false;
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
                  <td nowrap class="naH1">Menus</td> 
                  <td nowrap align="right" class="titleLink"> {if $HIDE_OPR eq 0}<a href="{makeLink mod=$smarty.request.mod pg=menu}act=list&section_id={$smarty.request.section_id}{/makeLink}&sId={$SUBNAME}&mId={$MID} ">Add New</a>{/if}</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="5" cellspacing="0"> 
                <tr>
                  <td colspan="4" align="center" class="naGrid1" height="25">Section :
				  { if $smarty.request.manage}
				  {assign var='menuurl' value='home_menu'}
				  {assign var='pageurl' value='home_page'}
				  {else}
				  {assign var='menuurl' value='menu'}
				  {assign var='pageurl' value='page'}
				  {/if}
				  
                  <select name=section_id onchange="window.location.href='{makeLink mod=$smarty.request.mod 		pg=$menuurl}act=list{/makeLink}&section_id='+this.value">
                  <option value="">-- SELECT A SECTION --</option>
                  {html_options values=$SECTION_LIST.id output=$SECTION_LIST.name selected=`$smarty.request.section_id`}
                  </select>
                  </td>
                </tr>
                {if count($MENU_LIST) > 0}
                <tr>
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td nowrap class="naGridTitle" height="24" align="left">Menu Name</td> 
				  <td width="10%" nowrap class="naGridTitle" align="center">#</td>
                </tr>
                {foreach from=$MENU_LIST item=menu}
				{ if $smarty.request.manage neq '' and $menu->storeowner_edit_pages eq 'N' }
				{else}
                <tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center">{if $HIDE_OPR eq 0}<a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=menu}act=list&id={$menu->id}&section_id={$menu->section_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a>{/if}</td> 
                  <td valign="middle" height="24" align="center"> {if $HIDE_OPR eq 0}<a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=menu}act=delete&id={$menu->id}&section_id={$menu->section_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a>{/if}</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$menuurl}act=list&id={$menu->id}&section_id={$menu->section_id}{/makeLink}">{$menu->name}</a></td> 
				  <td nowrap align="center">{$menu->position}</td> 
                </tr> 
				{/if}
                {/foreach}
                {else}
                 <tr class="naGrid2"> 
                  <td colspan="4" class="naError" align="center" height="30">No Records</td> 
                </tr>
                {/if}
              </table></td> 
          </tr> 
        </table>      </td>
      <td width="3%">&nbsp;</td>
      <td width="61%" valign="top">
	   {if $HIDE_OPR eq 0}
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">{if $smarty.request.id}Edit{else}Add{/if} Menu</td> 
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
              <form name="menuFrm" action="" method="post">
			  <tr class="{cycle values="naGrid1,naGrid2"}">
                <td width="40%" align="right">Section</td>
                <td width="1%">:</td>
                <td><select name="section_id">
                  <option value="">-- SELECT A SECTION --</option>
				  {html_options values=$SECTION_LIST.id output=$SECTION_LIST.name selected=`$smarty.request.section_id`}
                </select></td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Name</td>
                <td>:</td>
                <td><input name="name" type="text" size="30" value="{$MENU.name}" oncontextmenu="return false;" autocomplete="off" onkeyup="_seoURL(this.value);"></td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Type</td>
                <td>:</td>
                <td><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
				   <td><input name="type_tip" type="radio" value="home" id="type_tip"{if $MENU.type_link eq 'home'} checked{/if}></td>
                    <td><label for="radio">Home</label></td>
                    <td>&nbsp;</td>
                    <td><input name="type_tip" type="radio" value="content" id="type_tip"{if $MENU.type_link eq 'content'} checked{/if}></td>
                    <td><label for="radio">Content</label></td>
                    <td>&nbsp;</td>
                    <td><input name="type_tip" type="radio" value="tip" id="type_tip"{if $MENU.type_link eq 'tip'} checked{/if}></td>
                    <td><label for="radio2">Tips</label></td>
                  </tr>
                </table></td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">SEO URL</td>
                <td>:</td>
                <td><input type="text" name="seo_url" value="{$MENU.seo_url}" size="30" oncontextmenu="return false;" autocomplete="off" onkeypress="return _keyCheck(event);"> <strong>.</strong> php</td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Position</td>
                <td>:</td>
                <td><input name="position" type="text" size="3" maxlength="3" value="{$MENU.position}"></td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Active</td>
                <td>:</td>
                <td><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="active" type="radio" value="Y" id="Y"{if $MENU.active ne 'N'} checked{/if}></td>
                    <td><label for="Y">Yes</label></td>
                    <td>&nbsp;</td>
                    <td><input name="active" type="radio" value="N" id="N"{if $MENU.active eq 'N'} checked{/if}></td>
                    <td><label for="N">No</label></td>
                  </tr>
                </table>
                  <input type="hidden" name="store_id" value="{$smarty.session.store_id}">
				 </td>
              </tr>
			    <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">Store Owner can Edit</td>
                <td>:</td>
                <td><input  type="checkbox" name="storeowner_edit_pages" value="Y" {if $MENU.storeowner_edit_pages=='Y'} checked {/if}></td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td colspan="3" align="center"><input type="submit" name="Submit" value="Submit" class="naBtn">
                  <input type="reset" name="Submit2" value="Reset" class="naBtn">
				  {if $smarty.request.id and $smarty.request.section_id==22}
				 <input type="button" name="btn_updateexisting" value="Update {$MENU.name} for all Existing Stores" class="naBtn" onClick="return confirmUpdate('{makeLink mod=$smarty.request.mod pg=menu}act=updateStore&id={$smarty.request.id}&section_id={$smarty.request.section_id}{/makeLink}')" />
				 {/if}
				 </td>
              </tr>
			  </form>
            </table></td> 
          </tr> 
		 
        </table>
		 {/if}
        <br>
	    {if $smarty.request.id}
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td width="80%" nowrap class="naH1">Pages of {$MENU.name}</td> 
                  <td width="20%" align="right" nowrap class="titleLink">{if $HIDE_OPR eq 0}<a href="{makeLink mod=$smarty.request.mod pg=$pageurl}act=form&menu_id={$smarty.request.id}{/makeLink}">Add New</a>{/if}</td> 
                </tr> 
              </table></td> 
          </tr>
          <tr> 
            <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
                {if count($PAGE_LIST) > 0}
                <tr>
                  <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
                  <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
                  <td nowrap class="naGridTitle" height="24" align="left">Page Name</td> 
                  <td width="10%" nowrap class="naGridTitle" align="center">#</td> 
                </tr>
                {foreach from=$PAGE_LIST item=page}
                <tr class="{cycle name=a values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=home_page}act=form&id={$page->id}&menu_id={$page->menu_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
                  <td valign="middle" height="24" align="center">{if $HIDE_OPR eq 0}<a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=home_page}act=delete&id={$page->id}&menu_id={$page->menu_id}&section_id={$smarty.request.section_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a>{/if}</td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$pageurl}act=form&id={$page->id}&menu_id={$page->menu_id}{/makeLink}">{$page->title}</a></td> 
                  <td valign="middle" height="24" align="center">{$page->position}</td> 
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
        </table>
  		{/if}
      </td>
      <td width="3%">&nbsp;</td>
    </tr>
</table>