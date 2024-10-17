{literal}
<script language="javascript" type="text/javascript">
function deleteSelected()
{
	document.frm_feed.action	=	'{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete_feeds&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}{literal}';
	document.frm_feed.submit();
}
</script>
{/literal}

<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_feed" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=feedform&sId={$smarty.request.sId}&forms_id={$FORM_ID}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit}{/makeLink}">Add New {$smarty.request.sId}</a> 
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="7" align="left" class="naGrid1"><!-- {$DISPLAY_PATH} --></td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($FEEDS_LIST) > 0}<div align=center class="titleLink"><a href="#" onClick="javascript: deleteSelected();">Delete</a></div>{/if}    </td>
    	<td colspan=6 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$FEED_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="5%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_feed,'feed_ids[]')"></td>
          <td width="25%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=feed_title display="RSS Feed Title"}act=feedlist&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="10%" align="left" nowrap class="naGridTitle">Feed File </td>
          <td width="43%" align="left" nowrap class="naGridTitle">Feed URL </td>
          <td width="10%" align="left" nowrap class="naGridTitle">Status</td>
              <td width="7%" align="left" nowrap class="naGridTitle">Nodes</td>
	    </tr>
        {if count($FEEDS_LIST) > 0}
        {foreach from=$FEEDS_LIST item=feed name=foo}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td height="30" align="center" valign="middle"><input type="checkbox" name="feed_ids[]" value="{$feed->feed_id }" ></td> 
          <td height="30" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=feedform&feed_id={$feed->feed_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}">{$feed->feed_title} </a></td> 
          <td height="30" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=feed_download&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&feed_id={$feed->feed_id }{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/feedicon.jpg" width="22" height="23" ></a></td>
          <td height="30" align="left" valign="middle"><input name="path" type="text" style="background:#F3EBFC" value="{$feed->feed_file}" size="50" /></td>
          <td height="30" align="left" valign="middle">{if $feed->feed_status eq 'Y'} Active {else} InActive{/if}</td>
          <td height="30" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=node_list&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&feed_id={$feed->feed_id }{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" alt="Edit" border="0" {popup text="[Click here to view the Nodes of thid Feed]" fgcolor="#eeffaa" }></a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="7" class="msg" align="center" height="30">{$FEED_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="7" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
</table><input type="hidden" name="keysearch" value="N">
</form>