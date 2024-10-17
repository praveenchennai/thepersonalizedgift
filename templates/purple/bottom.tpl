<table width="100%" height="125" border="0" align="center" cellpadding="0" cellspacing="0" background="{$GLOBAL.tpl_url}/images/footer_bg.jpg">
        <tr>
          <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="29" align="center" valign="middle" class="bottomtext">
				  <div align="center">
				  {foreach from=$BOTTOM_MENU item=row name=bottommenu}
                    <a href="{$row->url}" class="bottomtext">
                    {$row->title}
                    </a>
					{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}&nbsp;&nbsp; | &nbsp;&nbsp;{/if}
				    {/foreach}
			    </div></td>
              </tr>
              <tr>
                <td height="63" align="center">
					<div align="center">
						<span class="bottomtext">
						  {foreach from=$BOTTOM_SUB_MENU item=row name=bottommenu}
							<a href="{$row->url}" class="bottomtext">{$row->title}</a>
							{if $smarty.foreach.bottommenu.index != count($BOTTOM_SUB_MENU)-1}&nbsp;&nbsp; | &nbsp;&nbsp;{/if}
							{/foreach}
							<br><br>&copy; Copyright 1995-{$smarty.now|date_format:"%Y"} Full Moon Rising, Inc. or our suppliers, all rights reserved. All brand names and product names used on these web pages are trademarks, registered trademarks, or service marks of their respective holders. Not responsible for price or description misprints. All prices, features, products and services offered are subject to change without notice.
						</span>
					</div></td>
              </tr>
          </table></td>
        </tr>
    </table>