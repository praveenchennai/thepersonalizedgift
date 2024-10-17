{literal}
<script language="javascript">
	function check()
	{
		if (document.pckFrm.name.value=="")
		{
			alert("Please specify a Package Name");
			document.pckFrm.name.focus();
			return false;
		}
		else if ((document.pckFrm.duration.value=="") || (isNaN(document.pckFrm.duration.value)) || (document.pckFrm.duration.value==0))
		{
			alert("Please specify a valid Duration");
			document.pckFrm.duration.focus();
			return false;
		}
		else if (document.pckFrm.type.value=="")
		{
			alert("Please specify a Duration Type");
			document.pckFrm.type.focus();
			return false;
		}
		else if ((document.pckFrm.fees.value=="") || (isNaN(document.pckFrm.fees.value)))
		{
			alert("Please specify a valid Registration Fee");
			document.pckFrm.fees.focus();
			return false;
		}
		
		else
		{
			if (document.pckFrm.fees.value==0)
			{
				return confirm("You are about to save this package with no registration fees.Please Conifrm");
			}
			else
			{
				return true;
			}
		}
		return true;
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
      <td colspan="2" align="left" nowrap class="naH1">Membertype Price</td> 
      <td align="right"><a href="{makeLink mod="member" pg="user"}act=mem_price_list{/makeLink}&sId={$SUBNAME}&mId={$MID}">View All</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Percentage Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style">Member Type</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><input name="type" type="text" class="formText" id="type" value="{$smarty.request.type}" size="30" maxlength="25" > </td> 
    </tr> 
    <tr class=naGrid1>
      <td valign=top><div align="right">Percentage</div></td>
      <td valign=top>:</td>
      <td><select name="p_type" class="formText" id="p_type" style="width:115px">
        <option value="">Select Type</option>
        <option value="Less" {if $smarty.request.p_type=='Less'} selected {/if}>Less</option>
        <option value="More" {if $smarty.request.p_type=='More'} selected {/if}>More</option>
      
      </select>        <input name="percentage" type="text" class="formText" id="percentage" value="{$smarty.request.percentage}" size="8" maxlength="20" >
      % </td>
    </tr>
    {if $REG_LIST}
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
