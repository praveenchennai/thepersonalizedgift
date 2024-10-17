{literal}

<style type="text/css">
<!--
.border1 {
	border: 1px solid #FFFFFF;
	background-color:#EFEFEF;
}
-->
</style>


{/literal}
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>

<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0" >
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this);"> 

<input type="hidden" name="tmpcount" value="1"  />

 	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="check" value="no">
	<input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
	<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
  <table border=0 width=85% cellpadding=5 cellspacing=0 class=naBrDr>
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
          <tr>
            <td nowrap class="naH1">{$smarty.request.sId}</td>
            <td nowrap align="right" class="titleLink"></td>
			<td nowrap align="right" class="titleLink"></td>
			
          </tr>
      </table></td>
   
	
  <tr class=naGrid1>
    <td height="10">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td align="right"><a href="#" onClick="javascript:popUp('{makeLink mod=product pg=download}{/makeLink}','')">Download</a></td> 
	<!--<td>&nbsp;</td>-->
  </tr>
  <tr>
	  <td class="naGridTitle" align="left" width="29%">Upload Inventry file (CSV format) </td>
	  <td class="naGridTitle" align="left" width="25%">&nbsp;</td>
	  <td class="naGridTitle" align="left" width="19%">&nbsp;</td>
	  <td class="naGridTitle" align="left" width="27%">&nbsp;</td>
  </tr>
  <tr class="naGrid1">
  	 <td align="right" width="29%">Locate File to Upload :</td>
  	 <td align="left" width="25%"><input type="file"  name="invnetry" id="inventry"></td>
  	  <td class=""  colspan="2" align="left"><input type="submit" value="Upload File" class="naBtn" /></td>
		 
  </tr>
  <tr>
  	 <td class="naGridTitle"  colspan="5" align="left">Review File Status and History </td>
  </tr>
  <tr class="{cycle values= naGrid2,naGrid1}">
  	 
	 <td align="center" width="25%" class="subHeading">Name</td>
	 <td align="center" width="19%" class="subHeading">Added Records</td>
	 <td align="center" width="27%" class="subHeading">Updated Records</td>
	 <td align="center" width="29%" class="subHeading">Date And Time</td>
  </tr>
  {foreach from =$LOG item=foo}
  <tr class="{cycle values= naGrid2,naGrid1}">
   	
	 <td align="center" width="25%"><a href="" onClick="javascript:popUp('{makeLink mod=product pg=download}act=excel&file={$foo.name}{/makeLink}','')">{$foo.name}</a></td>
	 <td align="center" width="19%">{$foo.added}</td>
	 <td align="center" width="27%">{$foo.updated}</td>
	 <td align="center" width="29%">{$foo.time_added}</td>
  </tr>
  {/foreach}

<!-- <tr> 
 <input type="hidden" name="count" value="{counter name=one}" />
      <td colspan=4 valign=center><div align=center>
	  
		<input type=submit value="Submit" class="naBtn" name="pro_submit" >&nbsp; 
		<input type=reset value="Reset" class="naBtn">
      </div>
	  </td> 
 </tr> -->
	<tr><td colspan=4 valign=center>&nbsp;</td></tr>
</table>

</form>
