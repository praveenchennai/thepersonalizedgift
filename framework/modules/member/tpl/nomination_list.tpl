<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="70%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$SUBNAME} (For the week Started on {$SDATE})</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($NOM_LIST) > 0}
		<tr>
		  <td  align="right" colspan="3" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr>
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="70%" nowrap class="naGridTitle" height="24" align="left">Email</td> 
		  <td width="25%" nowrap class="naGridTitle" height="24" align="left">Nominations Received</td> 
	    </tr>
        {foreach from=$NOM_LIST item=sub}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
          <td valign="middle"  height="24" align="center">&nbsp;</td> 
          <td valign="middle"  height="24" align="left">{$sub->username}</td> 
		  <td valign="middle"  height="24" align="center">{$sub->cnt}</td> 
	    </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="right" height="30">{$NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>