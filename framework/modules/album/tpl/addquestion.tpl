<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Add Question</td> 
	  <td align="right"><a href="{makeLink mod=album pg=album_admin}act=listquestion&link=review{/makeLink}">List Question</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Question Details</td> 
    </tr> 
	 <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Select Topic</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"> <select name="topic_id" class="formText">
	 		{foreach from=$TOPICARR item=row}
			{if $REVIEWQ.topic_id}
			{html_options values=$row->id output=$row->topic_name  selected=$REVIEWQ.topic_id }
			{else}
			{html_options values=$row->id output=$row->topic_name}
			{/if}
			{/foreach}
       </select></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Question</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="question" value="{$REVIEWQ.question}" class="formText" size="50" maxlength="255"></td> 
    </tr>
	<tr class=naGrid1> 
      <td width=40% align="right" valign=top>Value</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="value" value="{$REVIEWQ.value}" class="formText" size="20" maxlength="255" ></td> 
    </tr>
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Position</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="position" value="{$REVIEWQ.position}" class="formText" size="20" maxlength="255" ></td> 
    </tr>
		
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
