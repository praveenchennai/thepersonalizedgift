{literal}
<script language="javascript" type="text/javascript">
function check()
{	
	if(document.admFrm.chkcfrm.checked==true)
	{
		return confirm("Do you want to update the content in cms page of all stores");
	}
	else
	{
		return true;
	}
}


</script>
{/literal}
<form method="POST" name="admFrm" action="" onSubmit="return check()" style="margin: 0px;">
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="1">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table class="naBrdr" border="0" width="80%" cellpadding="5" cellspacing="1"  >
    <tr>
      <td nowrap class="naH1" colspan="2">CMS Page Management</td>
      <td nowrap align="right"><a href="{makeLink mod=$smarty.request.mod pg='home_menu'}act=list&id={$smarty.request.menu_id}&section_id={$SECTION_ID}{/makeLink}">Menu Page</a></td>
    </tr>
    {if isset($MESSAGE)}
    <tr class=naGrid2>
      <td valign=top colspan="3"><span class="naError">{$MESSAGE}</span></td>
    </tr>
    {/if}
    <tr>
      <td colspan=3 class="naGridTitle"><span class="group_style">Search Engine Page Title and Meta Tags</span></td>
    </tr>
	 {if $smarty.request.mod neq 'store'}
    <tr class="naGrid1">
      <td width="35%" align="right">CMS Menu: </td>
      <td width="1%">&nbsp;</td>
      <td width="67%">{$MENU_NAME}</td>
    </tr>
	 <tr class=naGrid2>
      <td align="right" valign="top">Page Name:</td>
      <td valign=top>&nbsp;</td>
      <td><input type="text" name="page_name" value="{$PAGE.page_name}" class="formText" size="60" style="width:300px; " maxlength="255"></td>
    </tr>
	{/if}
    <tr class=naGrid1>
      <td align="right" width="35%" valign="top">HTML Title:</td>
      <td width="1%" valign=top>&nbsp;*</td>
      <td width="67%"><input type="text" name="title" value="{$PAGE.title}" class="formText" size="60" style="width:300px; " maxlength="255"></td>
    </tr>
	 <tr class=naGrid2>
      <td align="right" valign="top">Meta Keywords:</td>
      <td valign=top>&nbsp;</td>
      <td><textarea name="meta_keywords" cols="45" rows="4" style="width:300px; ">{$PAGE.meta_keywords}</textarea></td>
    </tr>
	 <tr class=naGrid1>
      <td align="right" valign="top">Meta Description:</td>
      <td valign=top>&nbsp;</td>
      <td><textarea name="meta_description" cols="45" rows="4" style="width:300px; ">{$PAGE.meta_description}</textarea></td>
    </tr>
	 {if $smarty.request.mod neq 'store'}
    <tr class="naGrid2">
      <td align="right">Position:</td>
      <td valign=top>&nbsp;</td>
      <td><input type="text" name="position" value="{$PAGE.position}" class="formText" size="3" maxlength="3" >
      (put 1 for ordering in date wise order)</td>
    </tr>
    <tr class="naGrid1">
      <td align="right">Active:</td>
      <td>&nbsp;</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="active" type="radio" value="Y" id="Y"{if $PAGE.active ne 'N'} checked{/if}></td>
          <td><label for="Y">Yes</label></td>
          <td>&nbsp;</td>
          <td><input name="active" type="radio" value="N" id="N"{if $PAGE.active eq 'N'} checked{/if}></td>
          <td><label for="N">No</label></td>
        </tr>   
      </table></td>
    </tr>
	 {/if}
    <tr class="naGridTitle">
      <td align="center" colspan="3"><div align="left">Page Content  {if $smarty.request.mod eq 'store'}- {$PAGE.page_name} {/if} </div></td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3">
        <table border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="20">
			 {if $smarty.request.mod ne 'store'}
				This content is displayed on the details page
			{/if}</td>
          </tr>
          <tr>
            <td><textarea id="content" name="content" rows="20" cols="60" style="width:100%">{$PAGE.content}</textarea>
				</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr class="naGridTitle">
      <td colspan=3 valign=center>
	  {if $smarty.request.mod eq 'store'}
		<input type="hidden" name="position" value="1">
	 {/if}
	<div align=center>
	  {if $smarty.request.manage neq '' &&  $EDIT_PAGE.storeowner_edit_pages eq 'N'}   
	  <img src="{$GLOBAL.tpl_url}/images/submit-lock.jpg" alt="" title="Locked">
	  {else}
	  <input name="submit" type=submit class="naBtn" value="Submit">&nbsp;
	  <input name="reset" type=reset class="naBtn" value="Reset">
	  {/if}
	  <input type="hidden" name="section_id" value="{$SECTION_ID}">
	  <input type="hidden" name="store_id" value="{$smarty.session.store_id}">        	
   </div>
</td>
    </tr>
  </table>
</form>

