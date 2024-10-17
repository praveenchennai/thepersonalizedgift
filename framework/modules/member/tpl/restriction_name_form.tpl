{literal}
<script language="javascript">
	function check()
	{
		if (document.pckFrm.upload_file_type.value=="")
		{
			alert("Please specify a Upload File Type");
			document.pckFrm.upload_file_type.focus();
			return false;
		}
		else if (document.pckFrm.reg_pack_name.value=="")
		{
			alert("Please specify a Registeration Type");
			document.pckFrm.reg_pack_name.focus();
			return false;
		}
		else if (document.pckFrm.restriction_name.value==0)
		{
			return confirm("Please specify a Restriction Name");
			document.pckFrm.restriction_name.focus();
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
  <form method="POST" name="pckFrm" action="" style="margin: 0px;" onSubmit="return check()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Restriction Name</td> 
      <td align="right"><a href="{makeLink mod="member" pg="user"}act=restriction_name_list{/makeLink}">List All Restriction Names</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Restriction Name Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style">Upload File Type</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">
		  <select name="upload_file_type">
		  	<option value="">-Select-</option>
			{foreach item=upl from=$UPLOAD_TYPE_LIST}
				<option value="{$upl->upload_file_id}">{$upl->upload_file_type}</option>
			{/foreach}
		  </select>
	  </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Registration Type</div></td> 
      <td valign=top>:</td> 
      <td><select name="reg_pack_name">
		  	<option value="">-Select-</option>
			{foreach item=reg from=$REG_PACK_LIST}
				<option value="{$reg->id}">{$reg->package_name}</option>
			{/foreach}
		  </select>
		</td> 
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>Restriction Name </td>
      <td valign=top>:</td>
      <td> <input type="text" name="restriction_name" value="{$smarty.request.restriction_name}"> </td>
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Active</div></td> 
      <td valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if $smarty.request.active=='Y'} checked {/if}></td> 
    </tr> 
    {if $TYPE_LIST}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Assign Member Types</span></td> 
    </tr> 
    {foreach item=mem from=$TYPE_LIST}
    <tr class={cycle values="naGrid2,naGrid1"}> 
      <td valign=top><div align=right class="element_style">{$mem->type}</div></td> 
      <td valign=top>:</td> 
      <td><span class="formCheckbox"> 
        <input type=checkbox name="mem_type[]" value="{$mem->id}" {if $mem->chk} checked{/if}> 
        </span> </td> 
    </tr> 
    {/foreach}
    {/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>
