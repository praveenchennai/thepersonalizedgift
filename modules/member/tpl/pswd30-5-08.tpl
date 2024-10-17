
{literal}<script language="javascript1.1">
function submit_form()
{
	document.frmPass.submit();
}
</script>{/literal}
<style type="text/css">
{literal}
<!--
.style7 {COLOR: #000000; FONT-FAMILY: Arial; TEXT-DECORATION: none; font-weight: normal; font-size: 11px}
-->
{/literal}
</style>
<form name="frmPass" enctype="multipart/form-data" action="" method="post">
<div style="width:auto; ">
	<div class="greyboldext" >Password Retrieval</div>
	<div><span class="smalltext" style="color:#FF0000;"><strong><br>{messageBox}&nbsp; </strong></span></div>
	<div class="hrline"></div> <br>
	 {if ($SEND!=1)}
	<div class="bodytext">Please provide your username and email address which you gave while registering the account</div><br>
	  {/if}
	   {if ($SEND==1)}
	   <div class="bodytext">Your password has been sent to your email address</div><br>
	   <div class="bodytext"><a href="{makeLink mod=member pg=login}{/makeLink}" class="blackboldtext">Go back to Login page</a> </div></div><br>
	   {else}
	   <div class="bodytext"><span class="bodytext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div>
	   {/if}
	<div class="bodytext">
		<strong>User Name: </strong>
		 <input name="username" type="text" class="input" id="username">
	</div><br>
	<div class="bodytext">
		<strong>Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
		 <input name="email" type="text" class="input" id="email">
	</div><br>
	<div class="smalltext">
	{$SUBMITBUTTON1}<div class="space_div">&nbsp;</div>{$CANCEL}
	</div><br>
		
</div>
</form>