<script src="{$smarty.const.SITE_URL}/includes/DragDrop/prototype.js" type="text/javascript"></script>
<script src="{$smarty.const.SITE_URL}/includes/DragDrop/scriptaculous.js" type="text/javascript"></script>
<style type="text/css">
{literal}
.handle {
	cursor:move;
}
{/literal}
</style>
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
                  <td nowrap class="naH1">Link Area</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="5" cellspacing="0"> 
                <tr>
                  <td width="24%" height="25" align="center" class="naGrid1">Area :
                    <select name=section_id onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=link}{/makeLink}&parent_id={$smarty.request.parent_id}&cms_id={$smarty.request.cms_id}&area='+this.value">
                		 {html_options values=$LINK_AREA_LIST.value output=$LINK_AREA_LIST.display selected=`$smarty.request.area`}
                  </select></td>
                </tr>
				
				{if $LINK_AREA_LIMITS.limit_display}
                <tr>
                  <td height="25" align="center" class="naGrid1">Limit :
                  <input name="linkLimit" style="background-color:#E5E5E5;border:1px solid #AAAAAA;padding-left:2px" type="text" size="23" value="{$LINK_AREA_LIMITS.limit_display}" readonly /></td>
                </tr>
				{/if}
				
              </table></td> 
          </tr> 
        </table>      
        <br /><table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">{if $smarty.request.id}Edit{else}Add{/if} Link</td> 
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
                  <td align="right">Type : </td>
                  <td><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="type_link" type="radio" value="texts" id="type_link"{if $LINK.type_link ne 'images'} checked{/if}></td>
                    <td><label for="radio">Text</label></td>
                    <td>&nbsp;</td>
                    <td><input name="type_link" type="radio" value="images" id="type_link"{if $LINK.type_link eq 'images'} checked{/if}></td>
                    <td><label for="radio2">Image</label></td>
                  </tr>
                </table></td>
                </tr>
				
                <tr>
                  <td width="27%" align="right">Title : </td>
                  <td width="73%"><input name="title" type="text" size="30" maxlength="255" value="{$LINK.title}" /></td>
                </tr>
				
				{if $AREA=="snapshot"}
				<tr>
                  <td width="27%" align="right">Description : </td>
                  <td width="73%"><textarea rows="3" cols="27" name="description" >{$LINK.description}</textarea></td>
                </tr>
				{/if}
				{counter name=one start=0 print=false}{counter name=two start=0 print=false}{counter name=three start=0 print=false}
				    {foreach from=$TEMPLATE item=temp name=foo}
                <tr>
                  <td align="right">{$temp}&nbsp;Image</td>
                  <td><input name="link_image{counter name=one}" type="file" id="link_image"></td>
                </tr>
				{if $smarty.request.area=="center" OR $smarty.request.area=="store_center"}
				<tr>
                  <td width="27%" align="right"> </td>
                  <td width="73%">240 Pixels 135 pixels</td>
                </tr>
				{/if}
				{if $LINK.link_image}
                <tr>
                  <td align="right">&nbsp;</td>
                  <td><img src="{$GLOBAL.mod_url}/images/thumb/{$temp}_{$LINK.id}.{$LINK.link_image}?{$smarty.now|date_format:"%H:%M:%S"}" width="100" height="75"></td>
                </tr>
				{/if}
				
                <tr>
                  <td align="right"> {$temp}&nbsp;Image(M)</td>
                  <td><input name="link_oimage{counter name=two}" type="file" id="link_oimage"></td>
				  <input type="hidden" name="template_id{counter name=three}" value="{$temp}">
				 </tr>
				
				{if $LINK.link_oimage}
                <tr>
                  <td align="right">&nbsp;</td>
                  <td><img src="{$GLOBAL.mod_url}/images/thumb/{$temp}_m_{$LINK.id}.{$LINK.link_oimage}?{$smarty.now|date_format:"%H:%M:%S"}"  width="100" height="75"></td>
                </tr>
				{/if}
				{/foreach}
				<input type="hidden" name="count" value="{counter name=one}">
<!-- for green template  start--->
             
<!-- for green template  end--->




                <tr>
                  <td align="right">URL : </td>
                  <td>
					  <input name="url" type="text" size="30" value="{$LINK.url}" />
					  <input type="hidden" name="store_id" value="{$smarty.session.store_id}">
				  </td>
                </tr>
				   <tr>
                  <td align="right"><input name="window" type="checkbox" value="1" {if $LINK.window eq '1'} checked {/if}></td>
                  <td>
					 Open in a new Window
				  </td>
                </tr>
				
				{if $FEATURED}
				  <tr>
                  <td align="right"><input name="featured" type="checkbox" value="1" {if $LINK.featured eq '1'} checked {/if}></td>
                  <td>
					 Show featured items
				  </td>
                </tr>
				{/if}
                <tr>
                  <td align="right">&nbsp;</td>
                  <td><input type="submit" name="Submit3" class="naBtn" value="{if $smarty.request.id}Edit{else}Add{/if} Link" /></td>
                </tr>
              </table>
            </form></td>
          </tr>
        </table>
        <br />
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">Add CMS Page</td> 
                </tr>
              </table></td> 
          </tr> 
		  <tr>
			<td align="center" height="25" nowrap class="naGrid1">Section :
			  <select name="select" onchange="window.location.href='{makeLink mod=$smarty.request.mod pg=link}{/makeLink}&parent_id={$smarty.request.parent_id}&area={$smarty.request.area}&cms_id='+this.value">
                  <option value="">-- SELECT A SECTION --</option>
                  {html_options values=$CMS_LIST.id output=$CMS_LIST.name selected=`$smarty.request.cms_id`}
			</select></td>
		  </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="2" cellspacing="0"> 
                {if count($MENU_LIST) > 0}
                <tr>
                  <td nowrap class="naGridTitle" height="24" align="left">Menu Name</td> 
				  <td width="10%" nowrap class="naGridTitle" align="center">&nbsp;</td>
                </tr>
                {foreach from=$MENU_LIST item=row name=pages}
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td valign="middle" height="24" align="left">&nbsp;<a class="linkOneActive">{$row->name}</a></td>
                <td nowrap="nowrap" align="center"><a href="{makeLink mod=$smarty.request.mod pg=link}act=add&cms_id={$smarty.request.cms_id}&area={$smarty.request.area}&menu_id={$row->id}&parent_id={$smarty.request.parent_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/add.gif" alt="Add" width="24" height="24" border="0" /></a></td>
              </tr>
              {/foreach}
                {else}
                 <tr class="naGrid2"> 
                  <td colspan="2" class="naError" align="center" height="29">No Records</td> 
                </tr>
                {/if}
              </table></td> 
          </tr> 
        </table><br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">Add Category</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><table border="0" width="100%" cellpadding="2" cellspacing="0"> 
               
				 <tr>
                  <td height="24" colspan="3" align="left" nowrap class="naGrid1">{$DISPLAY_PATH}</td> 
			    </tr>
				 {if count($CAT_LIST) > 0}
                <tr>
                  <td width="81%" height="24" align="left" nowrap class="naGridTitle">Category Name</td> 
				  <td width="9%" height="24" align="left" nowrap class="naGridTitle">&nbsp;</td>
				  <td width="10%" nowrap class="naGridTitle" align="center">&nbsp;</td>
                </tr>
                {foreach from=$CAT_LIST item=row}
                <tr class="{cycle values="naGrid1,naGrid2"}"> 
                  <td valign="middle" height="24" align="left">&nbsp;<a class="linkOneActive">{$row->category_name}</a></td> 
				  <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=cms pg=link}act=list&parent_id={$row->category_id}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&area={$smarty.request.area}&cms_id={$smarty.request.cms_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" alt="Edit" border="0" {popup text="[Click here to explore the child level of the Category]" fgcolor="#eeffaa" width="340"}></a></td>
				  <td nowrap align="center"><a href="{makeLink mod=$smarty.request.mod pg=link}act=add&cms_id={$smarty.request.cms_id}&area={$smarty.request.area}&cat_id={$row->category_id}&parent_id={$smarty.request.parent_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/add.gif" alt="Add" width="24" height="24" border="0" /></a></td> 
                </tr> 
                {/foreach}
                {else}
                 <tr class="naGrid2"> 
                  <td colspan="3" class="naError" align="center" height="29">No Records</td> 
                </tr>
                {/if}
              </table></td> 
          </tr> 
        </table>
		</td>
      <td width="3%">&nbsp;</td>
      <td width="61%" valign="top">
	    {if count($LINK_LIST) > 0}
			<form action="" method="post" style="margin:0px;" name="orderFrm">
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
			  <tr> 
				<td height="30"><table width="98%" align="center"> 
					<tr> 
					  <td nowrap class="naH1">Order {$smarty.request.area} menu - drag and drop to order </td> 
					  <td align="right" nowrap><a href="../{makeLink}{/makeLink}" target="_blank">Preview</a></td>
					</tr> 
				  </table></td> 
			  </tr> 
			  <tr>
				<td class="naGrid1" height="1"><div></div></td>
			  </tr>
			  <tr> 
				<td>
					<table border="0" width="100%" cellpadding="5" cellspacing="0">
						<tr>
							<td width="20" nowrap="nowrap" class="naGridTitle" height="24" align="center">&nbsp;</td>
							<td width="20" nowrap="nowrap" class="naGridTitle" height="24" align="center">&nbsp;</td>
							<td nowrap="nowrap" class="naGridTitle" height="24" align="left">Link Title</td>
							<td width="20" nowrap="nowrap" class="naGridTitle" align="center">#</td>
					  </tr>
				  </table>
					<div id="linkOrder">
					  {foreach from=$LINK_LIST item=row name=linklist}
					   <div id="test_{$row->id}">
						  <table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr class="naGrid1">
							  <td width="20" valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=link}area={$smarty.request.area}&cms_id={$smarty.request.cms_id}&id={$row->id}&parent_id={$smarty.request.parent_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0" /></a></td>
							  <td width="20" valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=link}area={$smarty.request.area}&cms_id={$smarty.request.cms_id}&id={$row->id}&act=delete&parent_id={$smarty.request.parent_id}{/makeLink}" onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0" /></a></td>
							  <td valign="middle" height="24" align="left" class="handle">{$row->title}</td>
							  <td width="20" nowrap="nowrap" align="center"><span>{$row->position}</span></td>
							</tr>
						</table>
					   </div>
				  {/foreach}
				</div>
				</td>
			  </tr>
			  <tr>
			    <td >	</td>
			  </tr>
			  <tr>
			  	<td height="30" align="center"><input type="submit" name="Submit"  value="Save Changes" class="naBtn" />
				<input type="hidden" name="act" value="sort" /><input type="hidden" name="sortOrder" value="" />
				<!--
				{if $STORE_AREA_FLAG eq '1'}
				<input type="hidden" name="hid_menudetails" value="{$LINK_LIST}" />
				<input type="submit" name="btn_updateexisting" value="Update Menu for all Existing Stores" class="naBtn" />				
				{/if}			-->
				</td>
			  </tr>
			</table></form><div id="aa"></div>
	  {/if}
	  </td>
      <td width="3%">&nbsp;</td>
    </tr>
	
	 
</table>
 <script type="text/javascript">
 {literal}
 // <![CDATA[
   Sortable.create("linkOrder",
	 {tag:'div',onUpdate:saveOrder,dropOnEmpty:true,containment:["linkOrder"],handle:'handle',constraint:false});
 // ]]>
 function saveOrder() {
 	document.orderFrm.Submit.style.color = "#FF0000";
 	document.orderFrm.sortOrder.value = Sortable.serialize('linkOrder');
 }
 {/literal}
 </script>