<script type="text/javascript" src="{$smarty.const.SITE_URL}/includes/areaedit/popups/popup.js"></script>
{literal}
<script language="javascript">
window.resizeTo(600, 500);
window.onload = Init;

function Init() {
  HTMLArea = window.opener.areaedit_editors.body; // load the HTMLArea plugin and lang file
  __dlg_translate('HTMLArea');
  __dlg_init();
};

function onInsert(val,numb) {
var num=numb;
var msgtxt = document.getElementById("txt_" + num);
	if(msgtxt.value!=""){
		var msgtxt = val + ' +%'+msgtxt.value+'%+';
	}else{
		msgtxt=val;
	}
	__dlg_close(msgtxt);
  return false;
};

</script>
{/literal}
<form method="POST" name="admFrm" action="" style="margin: 0px;">
  <table width=96% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="4">General Message - {$GENERAL.name}</td> 
    </tr>
	<tr class=naGrid1> 
      <td  colspan="2">Field Name</td> 
      <td width="39%">Code</td> 
	   <td width="39%">Default Value</td> 
      <td width="20%"></td>
    </tr>
	{if $DB_VARIABLES}
	{foreach from=$DB_VARIABLES item=row}
    <tr class=naGrid1> 
      <td width=40%>{$row.desc}</td> 
      <td width=1%>:</td> 
      <td width="39%">{$row.key}</td> 
	   <td width="39%"><input type="text" name="txt_{$row.numb}" id="txt_{$row.numb}" >  </td> 
      <td width="20%"><input type="button" name="Button" class="naBtn" onclick="return onInsert('{$row.key}','{$row.numb}');" value="Insert" /></td>
    </tr>
	{/foreach}
    <tr class="naGrid1">
      <td colspan="5" align="center" valign="top"><input type="button" class="naBtn" name="Button" value="Close" onclick="__dlg_close(null);" /></td>
    </tr> 
	{else}
	<tr>
      <td colspan="4" valign="top" class="naError">No database variables available for this Message.</td>
    </tr> 
	{/if}
  </table>
</form> 
