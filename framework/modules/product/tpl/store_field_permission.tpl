{literal}

<style type="text/css">
<!--
.border1 {
	border: 1px solid #FFFFFF;
	background-color:#EFEFEF;
}
-->
</style>


{/literal}
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0" >
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this);"> 

<input type="hidden" name="tmpcount" value="1"  />

 	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="check" value="no">
	<input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
  <table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr>
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
          <tr>
            <td nowrap class="naH1">{$smarty.request.sId}</td>
            <td nowrap align="right" class="titleLink"></td>
			<td nowrap align="right" class="titleLink">{if $smarty.request.fId eq 56}<select name="sel_acc" onchange="window.location.href='{makeLink mod=accessory pg=accessory}act=field_permission&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&did='+this.value">
				
				{foreach from=$DROPDOWN item=foo}
       {html_options values=$foo.value output=$foo.disp selected=`$smarty.request.did`}
	   {/foreach}
	   </select>{/if}</td>
          </tr>
      </table></td>
    </tr>
	
	
   <!-- <tr>
    <td colspan=3 class="naGridTitle"><span class="group_style">Edit Fields in Product Details</span></td>
  </tr> -->
  <tr class=naGrid1>
    <td height="10">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td class="naGridTitle" align="left" width="33%">Field Names</td>
  <td class="naGridTitle" align="left" width="10%">&nbsp;</td>
  <td class="naGridTitle" align="left" width="10%">Enable</td>
  <td class="naGridTitle" align="left" width="10%">Disable</td>
  <td class="naGridTitle" align="left" width="10%">Hide</td>
  </tr>
  {counter name=one start=0 print=false}{counter name=two start=0 print=false}{counter name=three start=0 print=false}{counter name=four start=0 print=false}{counter name=five start=0 print=false}
  {if $FIELD_PERMISSIONS == 'products'}
  {foreach from=$FIELD_MANAGE_STORE item=foo}
  <tr class="{cycle values=naGrid1,naGrid2}">
    <td height="10" valign=top width="33%">
	<div align=left class="element_style">{$foo.name|capitalize}</div></td>
    <td valign=top width="10%" align="left">:</td>
	<td valign="middle" align="left"><input type="radio" name="hide{counter name=one}" {if $foo.manage_store eq 'Y'} checked{/if} value="1"></td>
	<td valign="middle" align="left"><input type="radio" name="hide{counter name=two}" {if $foo.manage_store eq 'N'} checked{/if} value="0"></td> 
    <td valign="middle" align="left"><input type="radio" name="hide{counter name=five}" {if $foo.manage_store eq 'H'} checked{/if} value="2"></td>
    <input type="hidden" name="id{counter name=three}" value="{$foo.id}" />
	<input type="hidden" name="tablename{counter name=four}" value="{$foo.tablename}">
  </tr>
 {/foreach}
 {else}
 {foreach from=$FIELD_MANAGE_STORE item=foo}
  <tr class="{cycle values=naGrid1,naGrid2}">
    <td height="10" valign=top width="33%">
	<div align=left class="element_style">{$foo.name|capitalize}</div></td>
    <td valign=top width="10%" align="left">:</td>
	<td valign="middle" align="left"><input type="radio" name="hide{counter name=one}" {if $foo.$ACCNAME eq 'Y'} checked{/if} value="1"></td>
	<td valign="middle" align="left"><input type="radio" name="hide{counter name=two}" {if $foo.$ACCNAME eq 'N'} checked{/if} value="0"></td> 
    <td valign="middle" align="left"><input type="radio" name="hide{counter name=five}" {if $foo.$ACCNAME eq 'H'} checked{/if} value="2"></td>
    <input type="hidden" name="id{counter name=three}" value="{$foo.id}" />
	<input type="hidden" name="tablename{counter name=four}" value="{$foo.tablename}">
  </tr>
 {/foreach}
 {/if}
<tr> 
 <input type="hidden" name="count" value="{counter name=one}" />
      <td colspan=5 valign=center><div align=center>
	  
		<input type=submit value="Submit" class="naBtn" name="pro_submit" >&nbsp; 
		<input type=reset value="Reset" class="naBtn">
      </div>
	  </td> 
 </tr>
	<tr><td colspan=5 valign=center>&nbsp;</td></tr>
</table>

</form>
