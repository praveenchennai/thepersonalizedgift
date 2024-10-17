<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">States & Tax Details </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}">States & Tax List</a></td>
        </tr>
      </table></td>
    </tr>
	<tr class=naGrid2>
      <td  align="right" valign=top>Country</td>
      <td valign=top>:</td>
      <td colspan="2" align="left"><select name='country_id'>
					<option value="">-- SELECT A COUNTRY --</option>
				   {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=`$STATES.country_id`}
				   </select></td>
    </tr>
	<tr class=naGrid2>
      <td  align="right" valign=top>Name</td>
      <td valign=top>:</td>
      <td colspan="2" align="left"><input name="name" type="text" class="input" id="name" value="{$STATES.name}" size="30"/></td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>Code</td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left"><input name="code" type="text" class="input" id="code" value="{$STATES.code}" size="5" /></td> 
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Tax</td>
     <td valign=top>:</td>
     <td colspan="2" align="left"><input name="tax" type="text" class="input" id="tax" value="{$STATES.tax}" size="10"/></td>
   </tr>
   <tr class="naGridTitle"> 
      <td colspan=4 valign=center>
	  {if $STORE_PERMISSION.edit == 'Y'}
	  <div align=center>	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>
		{/if}
		&nbsp; 
	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>