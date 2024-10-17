
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="left" width="75%" border="0" cellspacing="0" cellpadding="0"> 
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="1%" valign="top">&nbsp;</td>
      <td width="94%" valign="top">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
          <tr> 
            <td><table width="98%" align="center"> 
                <tr> 
                  <td nowrap class="naH1">Gang Run List </td> 
                </tr> 
              </table></td> 
          </tr> 
		  {if isset($MESSAGE)}
		  <tr class="naGrid2">
			<td height="25" colspan="3"><div align=center class="element_style">
		    <span class="naError">{$MESSAGE}</span></td>
		  </tr>
		  {/if}
		 
          <tr> 
            <td><table width="100%" border="0" cellpadding="5" cellspacing="0">
			  {foreach from=$GNGLIST item=row}
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">&nbsp;</td>
                <td align="left"><a href="{makeLink mod=order pg=order}act=gangrundet&date_to={$row.enddate}{/makeLink}" >{$row.gangname}</a></td>
              </tr>
              {/foreach}
              
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			  <tr class="{cycle values="naGrid1,naGrid2"}">
                <td align="right">&nbsp;</td>
                <td align="right">{$NUMPAD}</td>
              </tr>
            </table></td> 
          </tr> 
        </table>
       </td>
      <td width="3%">&nbsp;</td>
    </tr>
</table>
