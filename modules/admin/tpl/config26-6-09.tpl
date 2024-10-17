<script language="javascript">
{literal}
function show (opt)	{
      	document.getElementById("div_new").style.display='inline';
		document.getElementById("div_new1").style.display='inline';
	  
  }
  function donotshow (opt)	{
      	document.getElementById("div_new1").style.display='none';
		document.getElementById("div_new").style.display='none';
	 
  }

{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width=80% border=0 align="center" cellpadding=5 cellspacing="0" class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="4" align="left" nowrap class="naH1">{$smarty.request.sId}</td> 
    </tr>
	{foreach from=$CONFIG item=row}
	{if $temp == $row->category}
		{assign var=show value=""}
	{else}
		{assign var=temp value=$row->category}
		{assign var=show value=$row->category}
	{/if}
	{if $show!=""}
    <tr> 
      <td colspan=4 class="naGrid1"><strong>{$show}</strong></td> 
    </tr>
	{/if}
	{if $row->field eq "mail_authenticate"}
	 <tr class=naGrid2> 
      <td width="30%" align="right" valign=top>{$row->title}</td> 
      <td width="2%" valign=top>:</td> 
      <td width="26%" valign="top">
	  	<input type="radio" name="{$row->field}" value="Y" {if $row->value eq "Y"} checked{/if} id="{$row->field}" class="formText" onClick="show(this);">Yes
		 <input type="radio" name="{$row->field}" value="N" {if $row->value eq "N"} checked{/if} id="{$row->field}" class="formText" onClick="donotshow(this);">No </td> 
      <td width="42%" ></td>
    </tr>{elseif $row->field eq "mail_password"}
	<tr class=naGrid2     > 
		<td width="100%" colspan="4"><div id="div_new1" style="display:none; ">
		<table>
		<tr>
	<td width="30%" align="right" valign=top>{$row->title}</td> 
      <td width="2%" valign=top >:</td> 
      <td width="26%" valign="top" >
	  	<input type="text" name="{$row->field}" value="{$row->value}" class="formText" size="30" maxlength="255" >
		</td> 
      <td width="42%" ></td>
	  </tr>
		</table></div>
	</td>
 	</tr> 
	{elseif $row->field eq "mail_username"}
	<tr class=naGrid2     > 
		<td width="100%" colspan="4"><div id="div_new" style="display:none; ">
		<table>
		<tr>
			<td width="30%" align="right" valign=top>{$row->title}</td> 
      <td width="2%" valign=top >:</td> 
      <td width="26%" valign="top" >
	  	<input type="text" name="{$row->field}" value="{$row->value}" class="formText" size="30" maxlength="255" >
		</td> 
       <td width="42%" ></td>
 	</tr>
		</table></div>
	</td>
	</tr> 
	{else}
	
    <tr class=naGrid2> 
      <td width="30%" align="right" valign=top>{$row->title}</td> 
      <td width="2%" valign=top>:</td> 
      <td width="26%" valign="top">{if $row->possible_values}
	  	<select name="{$row->field}">{html_options options=$row->possible_values selected=$row->value}</select>
		{else}
		
		 	<input type="text" name="{$row->field}" value="{$row->value}" class="formText" size="30" maxlength="255" >
		{/if}
	  </td> 
      <td width="42%">{if $row->description!=""}<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" {popup text=" `$row->description`" width="300" fgcolor="#eeffaa"} />{/if}&nbsp;</td>
    </tr> 
	{/if}
	{/foreach}
    <tr class="naGrid1"> 
      <td colspan=4 align="center" valign=center><input type="submit" class="naBtn" value="Submit" />
        <input type="reset" class="naBtn" value="Reset" /></td> 
    </tr> 
  </form> 
</table>
