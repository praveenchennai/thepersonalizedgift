<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Question</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=addquestion&link=review{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($QUESTIONLIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
		  <td width="15%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="topic_name" display="Topic Name"}act=listquestion&link=review{/makeLink}</td> 
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="question" display="Question"}act=listquestion&link=review{/makeLink}</td> 
		   <td width="20%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="value" display="Value"}act=listquestion&link=review{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="position" display="Position"}act=listquestion&link=review{/makeLink}</td> 
        </tr>
        {foreach from=$QUESTIONLIST item=topic}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=addquestion&id={$topic->id}&link=review{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=questiondelete&id={$topic->id}&link=review{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
		  <td valign="middle" height="24" align="center">{$topic->topic_name}</td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=addquestion&id={$topic->id}&link=review{/makeLink}">{$topic->question}</a></td> 
		  <td valign="middle" height="24" align="center">{$topic->value}</td> 
          <td valign="middle" height="24" align="center">{$topic->position}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$QUESTION_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>