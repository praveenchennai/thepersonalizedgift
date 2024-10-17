<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript" type="text/javascript">

function ViewGroup(grpid, category_id,product_id) {
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_accessory);
	{/literal}
	req.open("GET", "{makeLink mod=product pg=store_ajax_accessory}{/makeLink}&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{literal}
	req.send(null);
}

function display_accessory(_var) {
	_var = _var.split('|');
	var e= eval("document.getElementById('grp_"+_var[0]+"')");
	e.innerHTML=_var[1];
}

function selectCheck(category_id,accessory) {
	if(accessory.length>0) {
		var e= eval("document.getElementById('sel_all_"+category_id+"')");
		var accessoryIDS=accessory.split(",");
		for( var sIndex in accessoryIDS ) {
				var accessory_id = accessoryIDS[sIndex];
				var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
				if(e.checked==true)
					elm.checked=true;
				else
					elm.checked=false;
			newSelect(elm.value);
		}
	} else {
		return false;
	}
}

function newSelect(accessory_id) {
	var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
	var e= document.getElementsByName('accessory[]');
	for(i=0;i<e.length;i++) {
		if(e[i].id=='accessory_'+accessory_id) {
			if(elm.checked==true)
				e[i].checked=true;
			else
				e[i].checked=false;
		}
	}
}

</script>
{/literal}

<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;">
	<input type="hidden" name="mod" value="product">
	<input type="hidden" name="pg" value="pop_access_store">
	<input type="hidden" name="act" value="form">
	<input type="hidden" name="store_id" value="{$smarty.request.store_id}">
	<input type="hidden" name="product_id" value="{$smarty.request.product_id}">

<table border=0 width=95% cellpadding=0 cellspacing=0 class=naBrDr> 
	<tr class=naGrid1>
    <td colspan="3" align="center" valign=top><strong>Accessories</strong></td>
    </tr>
	{foreach from=$GROUP item=gp name=gp_loop}
	<tr class=naGrid1>
    <td width="28%" height="40" align="right" valign=middle>{$gp.group.group_name}</td>
    <td width="2%" height="40" align="center" valign=middle>:</td>
    <td width="70%" height="40"><select name="new_group_id" id="new_group_id_{$gp.group.id}" style="width:200" onChange="ViewGroup('{$gp.group.id}',this.value,'{if $smarty.request.id}{$smarty.request.id}{else}0{/if}');">
	<option value="0">---Select Group---</option>
     {html_options values=$gp.group.category_id output=$gp.group.category_name}
	          </select></td>
			  
	</tr>
	
	 <tr class=naGrid1>
    <td colspan="3" align="center" valign=top>
	<div align="center" id="grp_{$gp.group.id}" style="display:inline"></div>
	</td>
    </tr>
	{/foreach}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
		   <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
		  <input type=button value="Close" class="naBtn" onClick="window.close();">  
        </div></td> 
</table>
</form> 

{if $GROUP_ACC && $smarty.request.product_id}
 {literal}
	<script language="javascript">
	{/literal}
	{foreach from=$GROUP_ACC item=acc name=acc_loop}
	ViewGroup('{$acc.group_id}','{$acc.category_id}','{$smarty.request.id}');
	{/foreach}
	{literal}
	</script>
	{/literal}
 {/if}
