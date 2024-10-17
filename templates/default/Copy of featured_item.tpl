{if count($RIGHT_COLUMN_ITEMS)>0}
<script type="text/javascript" src="{$GLOBAL.tpl_url}/js/javascript.js"></script>
<table width="208" align="center" border="0" cellspacing="0" cellpadding="0" class="redborder">
  <tr>
    <td><div align="center">
{foreach from=$RIGHT_COLUMN_ITEMS item=FEATURED_ITEMS name=loop1}
<div id="acToggler_page47_A{$FEATURED_ITEMS.head.id}" style="cursor: pointer;" align="center">
<table width="208" height="28" border="0" cellpadding="0" cellspacing="0">
                                                                  <tr>
                                                                    <td width="3%" align="left" valign="middle" background="{$GLOBAL.tpl_url}/images/body_botton.jpg">&nbsp;</td>
                                                                   <td width="97%" align="left" valign="middle" background="{$GLOBAL.tpl_url}/images/body_botton.jpg"><span class="whiteboltext">{$FEATURED_ITEMS.head.name}</span></td>
                                                                  </tr>
                                                              </table></div>
 <div style="overflow: hidden; height: 0px;" id="acStretcher_page47_A{$FEATURED_ITEMS.head.id}" align="center">
  <table width="208" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr align="center" valign="top" class="bodytext">
  {foreach from=$FEATURED_ITEMS.data item=items name=foo}
  {if $smarty.foreach.foo.index is div by 2}
</tr><tr align="center" class="bodytext">
{/if}
   <td height="13" valign="top">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center" valign="bottom" class="bodytext">
    <td height="13" align="center" valign="top">&nbsp;{$items.name}</td>
  </tr>
  <tr align="center" class="bodytext">
    <td height="82" valign="top" align="center"><div align="center"><table width="85" height="82"  border="0" cellpadding="0" cellspacing="0" background="{$GLOBAL.tpl_url}/images/thumb_box_bg.jpg" onMouseOut="this.style.background='url({$GLOBAL.tpl_url}/images/thumb_box_bg.jpg)';" onMouseOver="this.style.background='url({$GLOBAL.tpl_url}/images/thumb_box_bg2.jpg)';">
        <tr>
          <td width="89" align="center"><table width="91%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="44" align="center"><div align="center"><a href="{makeLink mod=product pg=list}act=desc&id={$items.id}{/makeLink}"><img src="{if $items.image_extension ne ''}{$GLOBAL.modbase_url}/product/images/thumb/{$items.id}.{$items.image_extension}{else}{$GLOBAL.modbase_url}/product/images/no-images.jpg{/if}" width="67" height="58" border="0"></a></div></td>
              </tr>
          </table></td>
        </tr>
    </table></div></td>
  </tr>
  <tr>
    <td height="13" valign="top">&nbsp;</td>
  </tr>
</table></td>
  {/foreach}
    </tr>
</table></div>
{/foreach}
<script type="text/javascript" src="{$GLOBAL.tpl_url}/js/mootools.js"></script>  
					<script type="text/javascript">
						var acStretchers_page47 = [ {foreach from=$RIGHT_COLUMN_ITEMS item=FEATURED_ITEMS name=loop11}'acStretcher_page47_A{$FEATURED_ITEMS.head.id}'{if $smarty.foreach.loop11.index<(count($RIGHT_COLUMN_ITEMS)-1)},{/if}{/foreach} ];
						var acTogglers_page47 = [ {foreach from=$RIGHT_COLUMN_ITEMS item=FEATURED_ITEMS name=loop12}'acToggler_page47_A{$FEATURED_ITEMS.head.id}'{if $smarty.foreach.loop12.index<(count($RIGHT_COLUMN_ITEMS)-1)},{/if} {/foreach} ];  
					</script>
					{literal}
					<script type="text/javascript">
						var accordion_page47 = new yhfx.Accordion(	
																	acTogglers_page47, 
																	acStretchers_page47, 
																	{
																		start: 'closed', 
																		alwaysHide: false, 
																		opacity: false, 
																		duration: 400 , 
																		onActive: function (el) 
																					{  
																					$(el).setStyle ('cursor', 'default');    
																					}, 
																		onBackground: function (el) 
																					{  
																					$(el).setStyle ('cursor', 'pointer');
																					}
																	}
																);
						</script>{/literal}
						</div></td>
  </tr>
</table>{/if}