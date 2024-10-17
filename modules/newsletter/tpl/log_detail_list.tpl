<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Newsletter History / Detail View</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=newsletter pg=log}act=list{/makeLink}">History Home</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($LOG_LIST) > 0}
        <tr>
          <td width="80%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=newsletter pg=log orderBy="email" display="Email"}act=detail&id={$smarty.request.id}{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=newsletter pg=log orderBy="date_sent" display="Scheduled"}act=detail&id={$smarty.request.id}{/makeLink}</td> 
        </tr>
        {foreach from=$LOG_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="left">{$row->email}</td> 
          <td valign="middle" height="24" align="left">{$row->date_created_f}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$LOG_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>