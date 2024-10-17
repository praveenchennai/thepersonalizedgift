	<table width="175" height="100%"  border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="11" height="8" background="{$GLOBAL.tpl_url}/images/innerbox-leftborder.jpg"><img src="{$GLOBAL.tpl_url}/images/innerbox-leftborder.jpg" width="11" height="8" alt=""></td>
            <td width="200" bgcolor="#a09a8c"></td>
          </tr>
          <tr>
		  
            <td height="100%" background="{$GLOBAL.tpl_url}/images/innerbox-leftborder.jpg">&nbsp;</td>
            <td valign="top" bgcolor="#a09a8c"><table width="157" border="0" cellpadding="0" cellspacing="0"   background="{$GLOBAL.tpl_url}/images/leftmenu-bg.jpg">
              <tr>
                <td colspan="3" valign="top"><img src="{$GLOBAL.tpl_url}/images/leftmenu-top.jpg" width="157" height="12" alt=""></td>
              </tr>
			  
              <!-- main left menu starts here -->
  {foreach from=$LEFT_MENU item=leftrow name=left_menu}
  <tr align="center" height="25" valign="middle">
    <td width="6"></td>
    <td width="145" class="leftmenu" background="{$GLOBAL.tpl_url}/images/left_main_background.jpg">&nbsp;&nbsp;<a style="text-decoration:none" href="{$leftrow->url}">{$leftrow->title}</a></td>
    <td width="8">&nbsp;</td>
  </tr>
  <!-- sub menu 1 -->
  {if $leftrow->title eq 'CREATE A NEW GIFT'} {foreach from=$LEFT_SUB_MENU1 item=row name=left_sub_menu1}
  <tr >
    <td width="150" class="leftmenu" colspan="3" height="22"><a href="#"></a><a href="{$row->url}" class="leftMenuLink">{$row->title}</a></td>
  </tr>
  {/foreach} {/if}
  <!-- end sub menu 1 -->
  <!-- sub menu 2 -->
  {if $leftrow->title eq 'FEATURED ITEM'} {foreach from=$LEFT_SUB_MENU2 item=row2 name=left_sub_menu2}
  <tr >
    <td width="150" class="leftmenu" colspan="3" height="22"><a href="#"></a><a href="{$row->url}" class="leftMenuLink">{$row2->title}</a></td>
  </tr>
  {/foreach} {/if}
  <!-- end sub menu 2 -->
  <!-- sub menu 3 -->
  {if $leftrow->title eq 'QUICK FIND'}
  <tr >
    <td width="150" colspan="3">
      <table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0" class="commonText">
        <tr>
          <td height="33" colspan="2" class="commonText">Use Keywords to find the product you are looking for</td>
        </tr>
        <tr>
          <td width="79%" height="25"><input name="textfield" type="text" class="inputTextBox" size="15"></td>
          <td width="21%"><a href="#"><img src="{$GLOBAL.tpl_url}/images/go.jpg" width="22" height="17" border="0"></a></td>
        </tr>
        <tr>
          <td height="21" colspan="2"><a href="#"><img src="{$GLOBAL.tpl_url}/images/advanced-search.jpg" width="96" height="11" border="0"></a></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  {/if}
  <!-- end sub menu 3 -->
  {/foreach}
  <!-- main left menu end here -->
  
  <tr>
                <td height="19">&nbsp;</td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
              </tr>
  <tr>
    <td colspan="3" valign="bottom"><img src="{$GLOBAL.tpl_url}/images/leftmenu-bottom.jpg" width="157" height="9" alt=""></td>
  </tr>
            </table>			  </td>
          </tr>
         <tr>
            <td height="16" align="left" valign="bottom" background="{$GLOBAL.tpl_url}/images/innerbox-leftbottom.jpg"><img src="{$GLOBAL.tpl_url}/images/innerbox-leftbottom.jpg" width="11" height="16"></td>
            <td background="{$GLOBAL.tpl_url}/images/menu_bottom.jpg" bgcolor="#a09a8c"></td>
          </tr>
        </table>