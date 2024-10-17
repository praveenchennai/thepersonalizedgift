{literal}
<script language="javascript">
	function check()
	{
		if (document.mapconfig.key_value.value=="")
		{
			alert("Please specify Key Value");
			document.mapconfig.key_value.focus();
			return false;
		}
		else
		{
				return true;
		}
	}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="mapconfig" action="" style="margin: 0px;" onSubmit="return check()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Settings</td> 
      <td align="right">&nbsp;</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Map Settings</span></td> 
    </tr> 
	
	{foreach name=outer item=config_det from=$CONFIG_DETAILS}
		<tr class={cycle values="naGrid2,naGrid1"}> 
		  <td valign=top width="30%"><div align=right class="element_style">{$config_det.map_name}</div></td> 
		  <td width="1%" valign=top>:</td> 
		  <td width="69%"><input name="{$config_det.map_field}" type="text" class="formText" id="{$config_det.map_field}" value="{$config_det.map_value}" size="70" {if $config_det.editable eq 'N'} readonly {/if}> </td> 
		</tr> 
	{/foreach}

	
	<!--
	<tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Default Location</span></td> 
    </tr>
	
	
	{foreach name=outer item=config_det from=$LOCATION_DETAILS}
		<tr class={cycle values="naGrid2,naGrid1"}> 
		  <td valign=top width="30%"><div align=right class="element_style">{$config_det.map_name}</div></td> 
		  <td width="1%" valign=top>:</td> 
		  <td width="69%"><input name="{$config_det.map_field}" type="text" class="formText" id="{$config_det.map_field}" value="{$config_det.map_value}" size="70"> </td> 
		</tr> 
	{/foreach}
-->
	
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center align="right"><a href="{makeLink mod=map pg=config}act=admin_view&sId=Map%20View&fId={$smarty.request.fId}{/makeLink}">View Map</a></td></tr>
  </form> 
</table>
