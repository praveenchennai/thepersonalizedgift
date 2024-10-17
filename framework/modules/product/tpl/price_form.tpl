<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">{$smarty.request.sId} </td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=list{/makeLink}&sId={$smarty.request.sId}&limit={$smarty.request.limit}&fId={$smarty.request.fId} ">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	<tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">{$smarty.request.sId} Details</span></td> 
    </tr> 
	{if $FIELDS.0==Y}
    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Name</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="name" value="{$PRICE.name}" class="formText" size="30" maxlength="150"> </td> 
    </tr>
	{/if}
	{if $STORE_PERMISSION.edit == 'Y'}
  <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	        <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr> 
	{/if}
  </form> 
</table>
