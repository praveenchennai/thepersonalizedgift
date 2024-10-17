{literal}
<script language="javascript">
function browser_check(){
if (navigator.appName=="Netscape"){
history.back();
}else{
history.go(-1);
}
}
	function checkEntry(){
	if(document.frmReg.agreed.checked==true)
	{
	return true;
	}
	else{
	alert("Please confirm by checking the checkbox");
	return false;}
	}
</script>
{/literal}
<form name="frmReg" id="frmReg" method="post" action="" onsubmit="return checkEntry()" > 
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td height="45" colspan="2" align="left" valign="middle" class="greyboldext" >Retail Web-Store Registration Details</td>
              </tr>
			   <tr>
   					 <td height="2" colspan="3" valign="top"><div class="hrline"></div></td>
		  </tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="18" colspan="6" class="bodytext"><div align="center">{messageBox}</div></td>
                  </tr>
				  <tr align="left">
				    <td height="18" colspan="6" class="styletext"  >Hello and Welcome {$FIRST_NAME|capitalize},</td>
			      </tr>
				  <tr align="left">
				    <td height="18" colspan="6" class="bodytext"  >&nbsp;</td>
			      </tr>
				  <tr align="left">
                    <!-- <td height="18" colspan="6" class="styletext" ><font style="font-size:12px; text-align:justify">Thank you for your interest in {$GLOBAL.site_name}. {if $ARTIST=='Y'}Your own website would be <a target="_blank" href="{$WEBSITE}" class="styletext"><u>{$WEBSITE}</u></a>. Setup, manage and update your own website with style.{/if} {if $ENDDATE}Your account will be valid till <strong>{$ENDDATE|date_format}</strong>.{/if} You will receive an email with all information after registration is completed.</font> </td> -->
					<td height="18" colspan="6" class="styletext" >Thank you for registering for your new {$GLOBAL.site_name} web-store. Your web-store is almost ready to launch and will be set-up promptly upon receipt of your initial payment. You will receive a confirmation e-mail as soon including additional information upon completing your registration. You should also print this webpage for your records. </td>
                  </tr>
				  <tr>
				    <td height="18" colspan="6" class="bodytext"  >&nbsp;</td>
			      </tr>
				  <tr>
                    <td height="18" colspan="6" class="styletext" ><table width="100%"  border="0" cellpadding="5" cellspacing="0"  class="styletext">
					<tr>
							<td width="25%" align="right" colspan="2"><b>Store Name</b></td>
							<td width="25%" align="left">{$STORE_DET->name}</td>
							