
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>

<script language="javascript">
	var fields=new Array('table_id','max_records_user');
	var msgs=new Array('Section','Maximum Records');
</script>

{/literal}
<table width="70%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=70% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="pckFrm" action="" style="margin: 0px;" onSubmit="return chk(this)"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">{$HEADING}</td> 
      <td align="right">&nbsp;</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Section Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="36%"><div align=right class="element_style">Section</div></td> 
      <td width="4%" valign=top>:</td> 
      <td width="60%"><select name="table_id" class="formText" id="table_id" style="width:145px">
		<option value="">---Select Section---</option>
		{html_options values=$SECTION_LIST.id output=$SECTION_LIST.display_name selected=$smarty.request.table_id}
      </select> </td> 
    </tr> 
    <tr class=naGrid1>
      <td valign=top><div align="right">Maximum Number of Files</div></td>
      <td valign=top>:</td>
      <td><input name="max_records_user" type="text" class="formText" id="fees2" value="{$smarty.request.max_records_user}" size="22"  >
        (put zero for unlimited)</td>
    </tr>
    
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>
