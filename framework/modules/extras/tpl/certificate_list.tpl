<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_coupon" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td width="12%" nowrap class="naH1">Gift Certificate</td> 
          <td nowrap align="right" class="titleLink" width="88%">&nbsp;</td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><strong>No: of items per page</strong> {$CERTIFICATE_LIMIT}</td>
        </tr> 
      </table>
	  </td> 
  </tr> 
  <tr> 
    <td>
	<table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($CERTIFICATE_LIST) > 0}
        <tr>
          <td width="15%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=extras pg=certificate orderBy="name" display="Certificate Name"}act=list{/makeLink}</td> 
          <td width="15%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="date_created" display="Date Created"}act=list{/makeLink}</td>
          <td width="25%" nowrap class="naGridTitle"></td>
	  	  <td width="10%" nowrap class="naGridTitle"></td>
		  <td width="10%" nowrap class="naGridTitle"></td>
	    </tr>
        {foreach from=$CERTIFICATE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
         <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=viewcertificate&id={$row->id}{/makeLink}">{$row->name}</a></td> 
          <td height="24" align="left" valign="middle">{$row->date_created}</td> 
		  <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=viewusercertificate&id={$row->id}{/makeLink}">View User Certificates</a></td> 
     	  <td height="24" align="left" valign="middle"></td> 
	      <td height="24" align="left" valign="middle"></td>
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$CERTIFICATE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>	  
	  </td> 
  </tr> 
</table>
</form>