<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td><!--{messageBox}--></td>
  </tr>
</table>
<table width="100%"><tr><td>
<form name="frm_made" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Mail a Check Fields 
		    <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink"><!--<a href="{makeLink mod=order pg= 	paymentType}act=mailform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New Mail Check Field </a>--></td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($MAIL_LIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><!--<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=mdelete&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}'; document.frm_made.submit();">Delete</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=mactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Active</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=minactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Inactive</a>-->
	</td>
    <td width="19%" nowrap> Results per page: </td>
	<td width="21%">{$MAIL_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="0" cellspacing="0"> 
		{if count($MAIL_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><!--<input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_made,'mail_field_id[]')">--></td>
          <td width="48%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=paymentType orderBy=field display="Field Name"}act=maillist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td align="center" nowrap class="naGridTitle">Status</td>
		  <td align="center" nowrap class="naGridTitle"><!--{makeLink mod=order pg=paymentType orderBy=active display="Status"}act=maillist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}-->Mandatory</td>
		  </tr>
        {foreach from=$MAIL_LIST item=mail}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><!--<input type="checkbox" name="mail_field_id[]" value="{$mail->id}">
		    {if $mail->active == Y}
		  	<a href="{makeLink mod=order pg=paymentType}act=minactive1&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to disable this mail a check field?');"><img title="UnSelect" alt="UnSelect" src="{$GLOBAL.tpl_url}/images/grid/Active.gif" border="0"></a>
		  {else}
		  	<a href="{makeLink mod=order pg=paymentType}act=mactive1&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to enable this mail a check field?');"><img title="Select" alt="Select" src="{$GLOBAL.tpl_url}/images/grid/Inactive.gif" border="0"></a>
		  {/if}-->
		  </td> 
          <td height="35" align="left" valign="middle">{$mail->field} </td> 
		  <td height="35" align="center" nowrap> {if $mail->active == Y}
		  	<a href="{makeLink mod=order pg=paymentType}act=minactive1&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to disable this mail a check field?');"><img title="UnSelect" alt="UnSelect" src="{$GLOBAL.tpl_url}/images/grid/Active.gif" border="0"></a>
		  {else}
		  	<a href="{makeLink mod=order pg=paymentType}act=mactive1&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to enable this mail a check field?');"><img title="Select" alt="Select" src="{$GLOBAL.tpl_url}/images/grid/Inactive.gif" border="0"></a>
		  {/if}</td>
		  <td height="35" align="center" nowrap><!--{if $mail->active == Y}Active{else} Inactive{/if}-->
		 {if $mail->mandatory == 1}
		  	<a href="{makeLink mod=order pg=paymentType}act=manactive&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to make this field as non-mandatory?');"><img title="UnSelect" alt="UnSelect" src="{$GLOBAL.tpl_url}/images/grid/Active.gif" border="0"></a>
		  {else}
		  	<a href="{makeLink mod=order pg=paymentType}act=maninactive&cmid={$mail->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure to make this field as mandatory?');"><img title="Select" alt="Select" src="{$GLOBAL.tpl_url}/images/grid/Inactive.gif" border="0"></a>
		  {/if}		  </td>
          </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$MAIL_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
</form>
</td></tr></table>