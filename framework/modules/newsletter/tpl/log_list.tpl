<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Newsletter Sent History</td> 
          <td nowrap align="right" class="titleLink"></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($LOG_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="newsletter" display="Newsletter"}act=list{/makeLink}</td> 
       <!--   <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="mailing_list" display="Mailing List"}act=list{/makeLink}</td> -->
          <td width="10%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=$MOD pg=$PG orderBy="member_count" display="Member Count"}act=list{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="date_scheduled" display="Scheduled"}act=list{/makeLink}</td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
        </tr>
        {foreach from=$LOG_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=delete&id={$row->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="left">{$row->newsletter}</td> 
        <!--  <td valign="middle" height="24" align="left">{$row->mailing_list}</td> -->
          <td valign="middle" height="24" align="center">{$row->member_sent}/{$row->member_count}{if $row->status != 'Y'} <a href="{makeLink mod=$MOD pg=$PG_RESUME}act=send&id={$row->id}&step=3&batch_email={$row->batch_nos}&delayed_seconds={$row->time_interval}{/makeLink}"><font color="#FF0000"><strong>[Resume]</strong></font></a>{/if}</td> 
          <td valign="middle" height="24" align="left">{$row->date_created_f}</td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=detail&id={$row->id}{/makeLink}">Details</a></td> 
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