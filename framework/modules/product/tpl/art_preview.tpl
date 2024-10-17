<script type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/swfobject.js"></script>
{literal}

	<script type="text/javascript">
		var flashvars = {};
		var params = {
		  allowscriptaccess : "always"
		};
		var attributes = {};
		swfobject.embedSWF("{/literal}{$smarty.const.SITE_URL}/{literal}imageloader.swf", "imageLoader", "515", "410", "9.0.0", "expressInstall.swf", flashvars, params, attributes);
	</script>
	
	<script type="text/javascript">
	
		function displayImage()
		{
			document.getElementById('imageLoader').sendTextFromHtml('{/literal}{$smarty.const.SITE_URL}{literal}/modules/product/images/accessory/{/literal}{$smarty.request.id}{literal}.{/literal}{$ACCESSORY.image_extension}{literal}');
		}
	</script>

	{/literal}
	
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
	<tr>
		<td colspan="4" align="center"><div id="msg_div"></div></td>
		</tr>
        <tr> 
          <td nowrap >&nbsp;</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
          <td nowrap align="right" class="titleLink">&nbsp; </td>
          <td nowrap align="right" class="titleLink">&nbsp;</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
 
        <tr> 
          <td colspan="4" class="msg" align="center" height="30"><div id="imageLoader">
	<h1>Alternative content</h1>
	<p><a href="http://www.adobe.com/go/getflashplayer">
	<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
</div><!--<div class="ic-w300 ic-cntr"><div ><center><img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/{$ACCESSORY.id}.{$ACCESSORY.image_extension}"></center></div><a class="ic-cp" href="javascript:;" ></a><div >
	
	</div></div>--></td> 
        </tr>
      
      </table></td> 
  </tr>
  <tr><td>&nbsp;</td></tr> 
    <tr><td>&nbsp;</td></tr> 
	
</table>
<br />
