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
                  <td nowrap class="naH1">Images</td> 
                  <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$smarty.request.mod pg=image}act=list&section_id={$smarty.request.section_id}{/makeLink}&sId={$SUBNAME}&mId={$MID} ">Add New</a></td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="5" cellspacing="0"> 
                <tr>
                  <td colspan="4" align="center" class="naGrid1" height="25">Section :
                  <select name=section_id onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=image}act=list{/makeLink}&section_id='+this.value">
                  <option value="">-- SELECT A SECTION --</option>
                  {html_options values=$SECTION_LIST.id output=$SECTION_LIST.display selected=`$smarty.request.section_id`}
                  </select>
                  </td>
                </tr>
                {if count($IMAGE_LIST) > 0}
                <tr>
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td width="7%" nowrap class="naGridTitle" height="24" align="center">&nbsp;</td> 
                  <td nowrap class="naGridTitle" height="24" align="left">Image Name</td> 
				  <td width="10%" nowrap class="naGridTitle" align="center">#</td>
                </tr>
                {foreach from=$IMAGE_LIST item=menu}
                <tr class="{cycle name=s values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=image}act=list&id={$menu->id}&section_id={$menu->image_area_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
                  <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=image}act=delete&id={$menu->id}&section_id={$menu->image_area_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
                  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=image}act=list&id={$menu->id}&section_id={$menu->image_area_id}{/makeLink}">{$menu->title}</a></td> 
				  <td></td>
				    </tr> 
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
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
           
		  {if isset($MESSAGE)}
		  <tr class="naGrid2">
			<td height="25" colspan="3"><div align=center class="element_style">
		    <span class="naError">{$MESSAGE}</span></td>
		  </tr>
		  {/if}
          <tr> 
            <td>
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">{if $smarty.request.id}Edit{else}Add{/if} Image</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top">
			<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
              <table width="100%" border="0" cellspacing="0" cellpadding="3" class="naGrid1">
                <tr>
                  <td height="5" colspan="2" align="right"><div></div></td>
                </tr>
				<tr>
                  <td align="right" class="naGrid1" height="25">Section :</td>
                 <td> <select name=section_id  onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=image}act=list{/makeLink}&section_id='+this.value">
                  <option value="0">-- SELECT A SECTION --</option>
                  {html_options values=$SECTION_LIST.id output=$SECTION_LIST.display selected=`$smarty.request.section_id`}
                  </select>
                  </td>
                </tr>
				{if $smarty.request.id}
				{foreach from=$IMAGE_DETAIL item=row name=homesub} 
						
				
               <tr>
                  <td width="27%" align="right">Title : </td>
                  <td width="73%"><input name="title" type="text" size="30" maxlength="255" value="{$row->title}"/></td>
                </tr>
				 <tr>
                  <td align="right">Image</td>
                  <td><input type="file" name="cms_image" ></td>
                </tr>
				
				{if $row->image!=''}
                <tr>
                  <td align="right">&nbsp;</td>
                  <td><img src="{$GLOBAL.modbase_url}cms/images/dynamic/thumb/{$row->id}.{$row->image}"  /></td>
                </tr>
				{/if}
				{/foreach}
				{else}
					<tr>
                  <td width="27%" align="right">Title : </td>
                  <td width="73%"><input name="title" type="text" size="30" maxlength="255" value="{$row->title}"/></td>
                </tr>
				 <tr>
                  <td align="right">Image</td>
                  <td><input type="file" name="cms_image" ></td>
                </tr>
				{/if}
				<tr>
                  <td align="right">&nbsp;</td>
                  <td><input type="submit" name="Submit3" class="naBtn" value="{if $smarty.request.id}Edit{else}Add{/if} Image" /></td>
                </tr>
              </table>
            </form></td>
          </tr>
        </table>
		</td> 
          </tr> 
        </table>
        <br>
	    
      </td>
      <td width="3%">&nbsp;</td>
    </tr>
</table>