<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_accessory" method="post" action="" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory-->{$smarty.request.sId}
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
		<tr> 
          <td nowrap >&nbsp;</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr>


  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="7%">{if $STORE_OWNER eq "store"}Store:{/if}</td>
   <td width="60%">
   {if $STORE_OWNER eq "store"}
	  <select name=store_name onchange="window.location.href='{makeLink mod=payment pg=index}act=paymentlist&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}&store_name='+this.value">
       {html_options values=$STORES.name output=$STORES.heading selected=`$SELECTED_STORE_NAME`}
	   </select>
	<font color="#666666" size="-4">Store admin indicates that the payment receiver is admin</font>
	{/if}
	</td>
	  <td width="30%" align="right" >{if $STORE_OWNER eq "admin"} Payment By Admin  {/if}
	  </td>
	  </tr>
</table></td>
    </tr> 


  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($PAY_METHODS) > 0}
	    <tr>
	      <td width="4%" height="25" align="center" nowrap class="naGridTitle">&nbsp;</td>
          <td width="24%" height="25" align="left" nowrap class="naGridTitle">{makeLink mod=payment pg=index orderBy=paymethod_name display="`$smarty.request.sId` Name" } act=paymentlist&subact=paymentlist&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td> 
          <td width="72%" height="25" align="left" nowrap class="naGridTitle">Description</td>
          </tr>
        {foreach from=$PAY_METHODS item=method}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td height="25" align="center"  valign="middle">
		  {if $method.paymethod_id == $PAY_METHODID}
		  	<a href="{makeLink mod=payment pg=index}act=form&storeowner={$STORE_OWNER}&storename={$SELECTED_STORE_NAME}&Action=DeActivate&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure you want to disable this payment method?');"><img title="UnSelect" alt="UnSelect" src="{$GLOBAL.tpl_url}/images/grid/Active.gif" border="0"></a>
		  {else}
		  	<a href="{makeLink mod=payment pg=index}act=form&storeowner={$STORE_OWNER}&storename={$SELECTED_STORE_NAME}&paymethod_id={$method.paymethod_id}&Action=Activate&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure you want to enable this payment method?');"><img title="Select" alt="Select" src="{$GLOBAL.tpl_url}/images/grid/Inactive.gif" border="0"></a>
		  {/if}
		  </td> 
          <td height="25" align="left"><a href="{if $method.paymethod_id == $PAY_METHODID}{makeLink mod=payment pg=index}act=paymentdetailsform&storeowner={$STORE_OWNER}&store_name={$SELECTED_STORE_NAME}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}{else}#{/if}" class="linkOneActive">{$method.paymethod_name}</a></td>
		  <td height="25" align="left">{$method.paymethod_description}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
  </table><br>
  <br />
</form>