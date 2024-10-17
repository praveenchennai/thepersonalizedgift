<table width="1000" height="102" border="0" align="center" cellpadding="0" cellspacing="0" background="{$GLOBAL.tpl_url}/images/topheader_bg.jpg">
        <tr>
          <td width="147" height="102" align="center" valign="top"><img src="{$GLOBAL.tpl_url}/images/logo.jpg" width="119" height="102" alt=""></td>
          <td width="714" align="center" valign="bottom"><table width="698" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center"><table border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr align="center">
				  {foreach from=$TOP_MENU item=row name=topmenu}
                    <td height="20"><a href="{$row->url}" class="toplink1" {if $row->window eq '1'} target="_blank" {/if}>{$row->title}</a></td>
					{if $smarty.foreach.topmenu.index != count($TOP_MENU)-1}
                    <td width="25"><div align="center"><img src="{$GLOBAL.tpl_url}/images/seperator.jpg" width="5" height="12"></div></td>
					{/if}
				  {/foreach}
				 </tr>
              </table></td>
            </tr>
            <tr>
              <td bgcolor="#8c8c8c"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr>
              <td height="42"><table width="98%" height="41"  border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr align="center">
                    <td><table height="41"  border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr align="center">
				  {foreach from=$TOP_SUB_MENU item=row name=topmenu}
                    <td><a href="{$row->url}" class="toplink2">{$row->title}</a></td>
					{if $smarty.foreach.topmenu.index != count($TOP_SUB_MENU)-1}
                    <td width="25"><div align="center"><img src="{$GLOBAL.tpl_url}/images/vertical-grey-line.jpg" width="5" height="19"></div></td>
					{/if}
				  {/foreach}
                  </tr>
              </table></td>
                  </tr>
              </table></td>
            </tr>
          </table></td>
          <td width="139"><img src="{$GLOBAL.tpl_url}/images/authorizenet.jpg" width="92" height="102" alt=""></td>
        </tr>
    </table>