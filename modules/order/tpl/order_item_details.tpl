<style>
{literal}
.accessory td {
	cursor:pointer;
	padding-left:5px;
}
.accessoryHover td {
	cursor:pointer;
	background-color:#aabbdd;
	padding-left:5px;
}
{/literal}
</style>

{literal}

	<script type="text/javascript">
	/*	var flashvars = {};
		var params = {
		  allowscriptaccess : "always"
		};
		var attributes = {};
		swfobject.embedSWF("{/literal}{$smarty.const.SITE_URL}/{literal}imageloader.swf", "imageLoader", "718", "548", "9.0.0", "expressInstall.swf", flashvars, params, attributes);*/
	</script>
	
	<script type="text/javascript">
	/*
		function displayImage()
		{
			document.getElementById('imageLoader').sendTextFromHtml('{/literal}{$smarty.const.SITE_URL}{literal}/modules/cart/images/{/literal}{$ORD_ITEMS.id}{literal}.jpg');
		}*/
	</script>

	{/literal}
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="96%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  <tr>
    <td><table width="98%" align="center">
      <tr>
        <td nowrap="nowrap" class="naH1">Order Item Details </td>
        <td align="right" nowrap="nowrap"><a href="{makeLink mod=order pg=order}act=details&id={$smarty.request.ord_id}{/makeLink}">Back to Order Details</a></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellpadding="5" cellspacing="2">
     
      <tr>
        <td height="24" align="center" nowrap="nowrap"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="18%" valign="top"  class="naFooter">&nbsp;</td>
                        <td width="82%" valign="top"  class="naFooter"><div align="center"></div></td>
                      </tr>
                      <tr>
                        <td valign="top" >&nbsp;</td>
                        <td valign="top" >&nbsp;</td>
                      </tr>
                      <tr>
                        <td valign="top" >&nbsp;</td>
                        <td valign="top" >
                          <div align="center"></div><table width="70%" border="0" align="left" cellpadding="5" cellspacing="0">
                            <tr>
                              <td width="100%" class="naBoldTxt">{$PRD_DET.name}</td>
                            </tr>
                            <tr>
                              <td><table width="100%" border="0" cellpadding="5" cellspacing="0">
       {foreach from=$ORDER_ACCESSORY  item=accessor}
            <tr class="{cycle values="naGrid1,naGrid2"}">
              <td width="40%" height="18" class="bodytext">{$accessor.type|ucfirst}</td>
              <td class="bodytext">:</td>
              <td class="bodytext">{$accessor.name}</td>
            </tr>
        {/foreach}
		
		{assign var="nodata" value=0}
	    {foreach from=$GIFT_DET item=det}
		 
            <tr class="{cycle values="naGrid1,naGrid2"}">
              <td width="40%" height="18" class="bodytext">{$det.label|ucfirst}</td>
              <td class="bodytext">:</td>
              <td class="bodytext">{$det.value}</td>
            </tr>
		
		{if $det.label eq 'openingline1' or $det.label eq 'closingline1'}
		{assign var="nodata" value=1}
		{/if}
			
        {/foreach}
		
		
		{if $POEM_SHOW eq 1 and $nodata ne 1}
		
			  <tr class="{cycle values="naGrid1,naGrid2"}">
              <td height="18" class="bodytext" colspan="3" style="color:#FF0000;"><strong>Unable to retrieve opening and closing lines</strong></td>
            
            </tr>
		
		{/if}
        
        {if $CUSTOM_FIELD_VALUES|@count gt 0}
             <tr class=""> <td height="18" class="bodytext" colspan="3" >&nbsp;</td> </tr>
             <tr class=""> <td height="18" class="naBoldTxt" colspan="3" >User Entered Info:</td> </tr>
            {foreach from=$CUSTOM_FIELD_VALUES item=field}
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td width="40%" height="18" class="bodytext">{$field.field_name|ucfirst|escape:'html'}</td>
                  <td class="bodytext">:</td>
                  <td class="bodytext">{$field.field_value}</td>
                </tr>
            {/foreach}
        {/if}
                
		
		
		
                              </table></td>
                            </tr>
                                                    </table></td>
                      </tr>
                      <tr>
                        <td valign="top" >&nbsp;</td>
                        <td valign="top" >&nbsp;</td>
                      </tr>
                      <tr>
                        <td valign="top" >&nbsp;</td>
                        <td valign="top" ><div id="imageLoader">
	<h1>Alternative content</h1>
	
	<p>
	<div   style="overflow:auto" ><center><img src="{$smarty.const.SITE_URL}/modules/cart/images/{$ORD_ITEMS.id}.jpg"  border="0" align="middle" ></center></div>
	
	
	<!--<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>-->
</div><!--<div class="ic-w300 ic-cntr"><div ><center><img src="{$smarty.const.SITE_URL}/modules/cart/images/{$ORD_ITEMS.id}.jpg"  border="0" align="middle" ></center></div><a class="ic-cp" href="javascript:;" ></a><div >
	
	</div></div>--></td>
                      </tr>
                  </table></td>
        </tr>

    </table></td>
  </tr>
</table>