<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">General Messages</td> 
        </tr> 
		<tr>
          <td nowrap>{$CATEGORY_PATH}</td>
          <td nowrap align="right" class="titleLink">
		  
		  </td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($GENERAL_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="35%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="name" display="Message Name"}act=messagelist{/makeLink}</td> 
          <td width="60%" nowrap class="naGridTitle" align="left">{makeLink mod=$MOD pg=$PG orderBy="description" display="Description"}act=messagelist{/makeLink}</td>
        </tr>
        {foreach from=$GENERAL_LIST item=general}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=messageform&id={$general->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=messageform&id={$general->id}{/makeLink}">{$general->name}</a></td> 
          <td height="24" align="left" valign="middle">{$general->description}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$GENERAL_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>