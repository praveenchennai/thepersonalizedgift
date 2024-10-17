 <table border="0" cellpadding="0" cellspacing="0" align="center">
 <tr>
    <td height="126">
	<table width="880" height="126"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="880" height="19"><table width="100%"  border="0" cellpadding="0" cellspacing="0" background="{$GLOBAL.tpl_url}/images/header-topbg.jpg">
          <tr>
            <td width="2%"><img src="{$GLOBAL.tpl_url}/images/header-topleft.jpg" width="20" height="19" alt=""></td>
            <td width="32%" class="headerTop"><SPAN 
                  class=headerTop>Welcome Guest!</SPAN> </td>
            <td width="48%" align="center" class="headerTop"><strong>Create Personalized Gifts that will be Treasured Forever....</strong></td>
            <td width="16%" class="headerTop">{$DATE}</td>
            <td width="2%" align="right"><img src="{$GLOBAL.tpl_url}/images/header-topright.jpg" width="21" height="19" alt=""></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="83"><table width="880"  border="0" cellspacing="0" cellpadding="0">
          <tr valign="top">
            <td width="242"><img src="{$GLOBAL.tpl_url}/images/header1.jpg" width="242" height="83" alt=""></td>
            <td width="362"><img src="{$GLOBAL.tpl_url}/images/header2.jpg" width="362" height="83" alt=""></td>
            <td width="276" background="{$GLOBAL.tpl_url}/images/header3.jpg"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="51" colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td width="14%" align="center"><a href="#"></td>
                <td width="86%" class="blackbold"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="24" valign="top"><table width="880" height="24"  border="0" align="center" cellpadding="0" cellspacing="0" background="{$GLOBAL.tpl_url}/images/menu-bg.jpg">
          <tr>
            <td height="24" align="right" valign="middle">
              <table width="880"  border="0" cellpadding="0" cellspacing="0">
                <tr>
				
	{foreach from=$TOP_MENU item=row name=topmenu}
	{if $row->title eq 'CREATE A NEW GIFT'}
	<td width="174" align="center" style="text-align:center"><a href="{$row->url}" class="toplink2">{$row->title}</a></td>
	{else}
	<td width="120" align="center" style="text-align:center"><a href="{$row->url}" class="toplink2">{$row->title}</a></td>
	{/if}
	{if $smarty.foreach.topmenu.index != count($TOP_MENU)-1}			
	<td width="2"><div align="center"><img src="{$GLOBAL.tpl_url}/images/seperator.jpg" width="2" height="20"></div></td>
	{/if}
	{/foreach}
				  
           
                </tr>
            </table>
			</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  </table>