<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<table width="100%"  border="0">
  <tr>
    <td>
	<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
	<table width=60% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
    <tr align="left">
      <td width="413" valign=top><table width="140%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Module Details </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=extras}act=list{/makeLink}"></a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>		</td>
    </tr>   
    {/if}
	 <tr class=naGrid2>
      <td valign=top>
	  <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr> 
						{if count($MODULE_LIST) > 0} 
							{foreach from=$MODULE_LIST item=module name=module}
								<td width="42%" align="right"> 
								<input name="modules[]" id="{$module->id}" type="checkbox"  value="{$module->id}" {if $module->check=='T'} checked{/if}>&nbsp;&nbsp;</td>
								<td width="58%" align="left">{$module->name}</td>
					  {if $smarty.foreach.module.index % 2 == 1}					  
					  </tr><tr align="center">
								 {/if} 
							 {/foreach} 
						 {/if}
						
						 </tr>
				  </table>
	  
	  </td>
    </tr>
    <tr class="naGridTitle"> 
      <td valign=center><div align=center>	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>