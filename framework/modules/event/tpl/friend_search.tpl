<script language="javascript" type="text/javascript">
{literal}
	function addToFriends(val,id)
	{
		
		var intTargetLen = opener.document.frm.invite_list.length++;      
		opener.document.frm.invite_list.options[intTargetLen].text = val;              
		opener.document.frm.invite_list.options[intTargetLen].value = id;
		window.close();
	}
{/literal}
</script>
   <form name="form1" method="post" action="">
      <table width="98%"  align="center" cellpadding="0" cellspacing="0" class="border">
        <tr>
          <td align="center">
		  {if !isset($SERARCH[0])}
		  <table width="90%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="bodytext">&nbsp;</td>
            </tr>
            <tr>
              <td class="toplink">Find a Friend </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="toplinkblck"> Search Jewish to invite a user </td>
            </tr>
            <tr>
              <td style="height:5px"></td>
            </tr>
            <tr>
              <td class="bodytext"> Enter Name or E-Mail:
                  <input type="text" name="search">
                  <input type="submit" name="Submit" value="Find"></td>
            </tr>
            <tr>
              <td class="bodytext"> Example: Peter john or Peter or peter@hotmail.com </td>
            </tr>
            <tr>
              <td class="blacktext_medium">&nbsp;</td>
            </tr>
          </table>
		  {/if}
		  </td>
        </tr>
        <tr>
          <td align="center">
		  <table width="90%"  border="0" cellspacing="0" cellpadding="0">
		  {if isset($SERARCH[1])}
            <tr>
              <td class="bodytext">Search Results </td>
            </tr>
            <tr>
              <td class="bodytext">{$SERARCH[1]}</td>
            </tr>
            <tr>
              <td class="bodytext"><hr size="1" style="color:#999999"></td>
            </tr>
			{/if}
            <tr>
              <td class="blacktext_medium">
			  {foreach from=$SERARCH[0] item= friend}
			   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="17%" align="left" valign="top"><img src="" style="width:130px;height:120px"></td>
                  <td width="1%" align="left" valign="top">&nbsp;</td>
                  <td width="69%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="bodytext">Name : {$friend.first_name}</td>
                    </tr>
                    <tr>
                      <td class="trHeight"></td>
                    </tr>
                    <tr>
                      <td class="bodytext">Age : {$friend.age}</td>
                    </tr>
                    <tr>
                      <td class="trHeight"></td>
                    </tr>
                    <tr>
                      <td class="bodytext">Location : {$friend.country},{$friend.state},{$friend.city}</td>
                    </tr>
                  </table>
				  </td>
                  <td width="13%"><input type="button" name="button" value="Add" onClick="addToFriends('{$friend.first_name}','{$friend.id}')"></td>
                </tr>
                <tr>
                  <td colspan="4"><hr size="1" style="color:#999999"></td>
                 </tr>
              </table>
		      {/foreach}			  </td>
            </tr>
			 <tr>
              <td class="bodytext">{$SERARCH[1]}</td>
            </tr>
			 <tr>
			   <td align="left" class="blacktext_medium">
			    {if isset($SERARCH[0])}
			   <table width="21%"  border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="33%"><input type="submit" name="Submit2" value="New Search"></td>
                   <td width="5%">&nbsp;</td>
                   <td width="62%"><input type="button" name="Submit22" value="Close Window" onClick="window.close()"></td>
                 </tr>
               </table>
			   {/if}			   </td>
		    </tr>
          </table></td>
        </tr>
      </table>
      </form>