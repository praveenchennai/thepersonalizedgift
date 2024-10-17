<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="3%"  height="45" align="left" nowrap class="whiteboltext">&nbsp;</td>
    <td width="97%" align="left" nowrap class="whiteboltext">Templates</td>
  </tr>
  <tr>
    <td height="2" colspan="2" valign="top" bgcolor="#b20d13"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td colspan="2"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
      <tr>
        <td width="60%" align="left" valign="top">&nbsp;     </td>
        <td width="40%" align="left" valign="top">&nbsp;          </td>
      </tr>
      <tr>
        <td height="59" valign="top"> 
		 <div  STYLE=" width:450px;height:450px; overflow: auto;">
		   <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
		    {if count($TEMPLATE_LIST) > 0}
				{foreach from=$TEMPLATE_LIST item=template}					
					 <tr>
					   <td width="31%" align="left"><img src="{$GLOBAL.mod_url}/images/template/thumb/temp_{$template->id}.gif" border="0"></td>
					   <td width="69%">					     <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
							 {foreach from=$template->cssList item=css}
								 <tr>
								   <td width="14%" align="left">&nbsp;</td>
								   <td width="86%">{$css->css_caption}</td>
								 </tr>
							 {/foreach}
						   </table>
					   </td>
					 </tr>
					 <tr>
					 <td colspan="2" height="10">
					 </td>
					 </tr>
				{/foreach} 
			{/if}
           </table>			 	
		 </div>
		</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
