<style type="text/css">
{literal}
<!--
.style7 {COLOR: #000000; FONT-FAMILY: Arial; TEXT-DECORATION: none; font-weight: normal; font-size: 11px}
-->
{/literal}
</style>
<form name="frmcnt" method="post" action="">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td width="5%" height="39" class="blackboldtext">&nbsp;</td>
    <td width="90%" height="39" class="blackboldtext">&nbsp;</td>
    <td width="5%" class="blackboldtext">&nbsp;</td>
  </tr>
  <tr>
    <td width="4%" height="244">&nbsp;</td>
    <td align="center" valign="top" class="blacktext"><div align="justify">
        <table width="100%" height="150"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
          <tr>

            <td width="100%" height="80" align="center" valign="middle" bgcolor="#EEEEEE">
                <input type="hidden" name="to" value="{$CONTACT_NAME}">
				{if isset($MESSAGE)}
					<div><span class="smalltext" style="color:#FF0000"><strong>{$MESSAGE}&nbsp; </strong></span></div>
					<div class="blackboldtext" align="center">&nbsp;</div>
					<div class="blackboldtext" align="center"><a href="{makeLink mod=member pg=search}act=list{/makeLink}" class="middlelink">Go Back to Profile Search</a></div>
				{else}	
					<div class="blackboldtext" align="center"> Do you want to add '{$CONTACT_NAME}' to your Contact List? </div>
					<div class="blackboldtext" align="center">&nbsp;</div>
					<div class="blackboldtext" align="center"><input type="image" src="{$GLOBAL.tpl_url}/images/yes.jpg">&nbsp;<a href="index.php?{$smarty.request.ret_url}"><img border="0" src="{$GLOBAL.tpl_url}/images/no.jpg"></a></div>
				{/if}
            </td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="left">&nbsp;</p>
    </div></td>
    <td align="center" valign="top" class="blacktext">&nbsp;</td>
  </tr>
</table>
</form>