<table width="96%" height="90%"  border="0" cellspacing="0" cellpadding="0" align="center">
  <tr valign="middle">
    <td valign="top" class="blackboldtext"><table width="100%" height="145"  border="0" cellpadding="0" cellspacing="0">
		<tr>
          <td height="145" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	{if $row->active=='Y'}
              <tr>
                <td class="bodytext"><strong>{$row->page_name}</strong></td>
              </tr>
              <tr>
                <td height="5"><div></div></td>
              </tr>
              <tr>
                <td height="1" bgcolor="#0058a8"><img src="images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td height="5"><div></div></td>
              </tr>

  <tr>
    <td class="bodytext">&nbsp;</td>
  </tr>
  <tr>
    <td class="bodytext">{$row->content}</td>
  </tr>
  <tr>
    <td height="25" valign="middle" class="middlelink">&nbsp;</td>
  </tr>
 
          </table></td>
        {/if}        </tr>
    </table></td>
  </tr>
</table>