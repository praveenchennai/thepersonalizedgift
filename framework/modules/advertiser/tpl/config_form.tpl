<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="advconfig" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Settings</td> 
      <td align="right">&nbsp;</td> 
      <td align="right">&nbsp;</td>
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=4 class="naGridTitle"><span class="group_style">Advertiser Settings</span></td> 
    </tr> 
	
	{foreach name=outer item=config_det from=$CONFIG_DETAILS}
		<tr class={cycle values="naGrid2,naGrid1"}> 
		  <td valign=top width="40%"><div align=right class="element_style">{$config_det.name}</div></td> 
		  <td width="1%" valign=top>:</td> 
		  <td width="39%">
			{if $config_det.possible_values}
			<select name="{$config_det.field}">{html_options options=$config_det.possible_values selected=$config_det.value}</select>
			{else}
			<input name="{$config_det.field}" type="text" class="formText" id="{$config_det.field}" value="{$config_det.value}" size="40" {if $config_det.editable eq 'N'} readonly {/if}> 
			{/if}
		  </td> 
		  <td align="left" width="20%" >{if $config_det.description!=""}<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" {popup text=" `$config_det.description`" width="300" fgcolor="#eeffaa"} />{/if}</td>
		</tr> 
	{/foreach}

	
	
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=4 valign=center align="right">&nbsp;</td></tr>
  </form> 
</table>
