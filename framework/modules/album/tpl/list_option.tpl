<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Option</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=addoption&link=review{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($OPTIONLIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
		  <td width="40%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="question" display="Question"}act=listoption&link=review{/makeLink}</td> 
          <td width="45%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="a.option" display="Option"}act=listoption&link=review{/makeLink}</td> 
		  <td width="20%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="value" display="Value"}act=listoption&link=review{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="position" display="Position"}act=listoption&link=review{/makeLink}</td> 

        </tr>
        {foreach from=$OPTIONLIST item=option}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=addoption&id={$option->id}&link=review{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=optiondelete&id={$option->id}v{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
		  <td valign="middle" height="24" align="center">{$option->question}</td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=addoption&id={$option->id}&link=review{/makeLink}">{$option->option}</a></td> 
		  <td valign="middle" height="24" align="center">{$option->value}</td> 
          <td valign="middle" height="24" align="center">{$option->position}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$OPTION_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>