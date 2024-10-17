
<style type="text/css">
{literal}
<!--
.style7 {COLOR: #000000; FONT-FAMILY: Arial; TEXT-DECORATION: none; font-weight: normal; font-size: 11px}
-->
{/literal}
</style>
<form name="frmPass" enctype="multipart/form-data" action="" method="post">
<div style="width:auto; margin-top:120px; width:600px" class="naBrdr">
	<div class="greyboldext" style="background-color:#485e85; height:25px;padding-top:6px"><span class="naGridTitle">{$MOD_VARIABLES.MOD_HEADS.HD_PASS_RETRIVE}</span></div>
	<div align="center"><span class="smalltext" style="color:#FF0000; width:300px"><strong><br>{messageBox}&nbsp; </strong></span></div>
	<div class="hrline"></div> <br>
	 {if ($SEND!=1)}
	<div class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_PASS_RETRIVE_MESS_STORE}</div><br>
	  {/if}
	   {if ($SEND==1)}
	   <div class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_PASS_SET}</div><br>
	   <div class="bodytext"><a href="{makeLink mod=member pg=login}{/makeLink}" class="blackboldtext">{$MOD_VARIABLES.MOD_LABELS.LBL_BACK_LOGIN}</a> </div></div><br>
	   {else}
	   <div class="bodytext"><span class="bodytext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div>
	   {/if}
	<!--<div class="bodytext">
		<strong>User Name: </strong>
		 <input name="username" type="text" class="input" id="username">
	</div><br>-->
	{if ($SEND!=1)}
	<div class="bodytext">
		<strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PASS_RETRIVE_EMAIL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
		 <input name="email"  type="text" class="input" id="email">
	</div><br>
	<div class="smalltext" >
	<input type="submit" name="Submit" value="Submit" class="naBtn">&nbsp;&nbsp;&nbsp;<input type="button"  value="Back" class="naBtn"  onClick="window.history.back();">
	</div><br>
	{/if}
		
</div>
</form>