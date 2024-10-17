{literal}
<script language="javascript">
	function check()
	{
		if (document.pckFrm.package_name.value=="")
		{
			alert("Please specify a Package Name");
			document.pckFrm.package_name.focus();
			return false;
		}
		else if ((document.pckFrm.fee.value=="") || (isNaN(document.pckFrm.fee.value)))
		{
			alert("Please specify a valid Registration Fee");
			document.pckFrm.fee.focus();
			return false;
		}
		else
		{
			if (document.pckFrm.fee.value==0)
			{
				return confirm("You are about to save this package with no registration fees.Please Conifrm");
			}
			else
			{
				return true;
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
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="pckFrm" action="" style="margin: 0px;" onSubmit="return check()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Registration Packages</td> 
      <td align="right"><a href="{makeLink mod="member" pg="user"}act=pack_list{/makeLink}">List All Packages</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Package Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style">Package Name</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><input type="text" name="package_name" value="{$smarty.request.package_name}" class="formText" size="30" maxlength="25" > </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Registration Fee</div></td> 
      <td valign=top>:</td> 
      <td><input type="text" name="fee" value="{$smarty.request.fee}" class="formText" size="30" maxlength="255" >
        USD </td> 
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>Description</td>
      <td valign=top>:</td>
      <td> <textarea name="description" cols="29" rows="6">{$smarty.request.description}</textarea> </td>
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
