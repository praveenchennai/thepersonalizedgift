{literal} 
<script language="javascript">
function chkXml()
{
	if(document.getElementById('article_xml').value=='')
	{
		alert("Please specify one xml file");
		return false;
	}
}
	
</script>
{/literal}
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chkXml()">  
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Add Article </td> 
	  <td align="right">&nbsp;</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">&nbsp;</td> 
    </tr>
    <tr class=naGrid1> 
      <td width=39% height="120" align="right" >XML file</td> 
      <td width=5% >:</td> 
      <td width="56%"><input type="file" id="article_xml" name="article_xml" size="30"></td> 
    </tr>
		
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
