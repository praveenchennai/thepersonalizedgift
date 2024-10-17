<script language="javascript1.1">
{literal}
function submit_form()
{
	document.frmPass.submit();
}
{/literal}
</script>

<style type="text/css">
{literal}
<!--
.style7 {COLOR: #000000; FONT-FAMILY: Arial; TEXT-DECORATION: none; font-weight: normal; font-size: 11px}
-->
{/literal}
</style>
<form name="frmPass" enctype="multipart/form-data" action="" method="post">
<div style="width:auto; ">
	<div class="greyboldext" >Customer Login</div>
	<br>
		<div class="hrline"></div> 
	<div><span class="smalltext" style="color:#FF0000;"><strong><br>{messageBox}&nbsp; </strong></span></div>
	<div class="bodytext">{if !(isset($ACT))}{$MOD_VARIABLES.MOD_HEADS.HD_ALREADY_MEMBER} {/if}</div><br>
	<div class="bodytext">
		<strong>{$MOD_VARIABLES.MOD_COMM.COMM_CMN_USERNAME} </strong>
		<input name="txtuname" type="text" id="txtuname" style="width:160px" class="input">
	</div><br>
	<div class="bodytext">
		<strong>{$MOD_VARIABLES.MOD_COMM.COMM_CMN_PASSWORD} &nbsp;</strong>
		<input name="txtpswd" type="password" id="txtpswd" style="width:160px" class="input">
	</div><br>
	<div class="smalltext"> 
      <!--<input type="submit" name="Submit" value="Login" class="button_class">		
      <input name="button" type="button"  value="Cancel" class="button_class" onClick="history.go(-1)">-->
	  
	{$SUBMITBUTTON1}<div class="space_div">&nbsp;</div>{$CANCEL}
	</div>
	<div style="clear:both"></div>
	
	<div class="bodytext" style="float:left">{$MOD_VARIABLES.MOD_LABELS.LBL_FORGOT_PASSWORD} <a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_CLICK_HERE}</u></a><br><span class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_NEW_CUSTOMER}</span> <strong><span class="footerlink"><a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_REGISTER}</u></a></span></strong>
	</div>
	
	
</div>
</form>