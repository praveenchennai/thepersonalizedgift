<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Add Option </td> 
	  <td align="right"><a href="{makeLink mod=album pg=album_admin}act=listoption&link=review{/makeLink}">List Option </a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Option  Details</td> 
    </tr> 
	 <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Select Question </td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"> <select name="qid" class="formText">
	 		{foreach from=$QARR item=row}
			{if $OPTION.qid}
			{html_options values=$row->id output=$row->question  selected=$OPTION.qid }
			{else}
			{html_options values=$row->id output=$row->question}
			{/if}
			{/foreach}
       </select></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Option</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="option" value="{$OPTION.option}" class="formText" size="50" maxlength="255"></td> 
    </tr>
	<tr class=naGrid1> 
      <td width=40% align="right" valign=top>Value</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="value" value="{$OPTION.value}" class="formText" size="20" maxlength="255" ></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Position</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="position" value="{$OPTION.position}" class="formText" size="20" maxlength="255" ></td> 
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
