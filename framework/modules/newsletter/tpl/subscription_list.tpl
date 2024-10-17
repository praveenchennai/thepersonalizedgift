<script language="javascript">
{literal}
function showSearch() {
	document.getElementById("searchDiv").style.display = (document.getElementById("searchDiv").style.display == 'none') ? 'inline' : 'none';
}
{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Subscribers</td> 
          <td nowrap align="right" class="titleLink"><a href="#" onClick="showSearch();return false;">Search</a> | <a href="{makeLink mod=$MOD pg=$PG}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr>
  	<td class="naGrid2" align="center">
	  <div id="searchDiv" style="display:none;">
	  	<form name="form1" method="post" action="" style="margin:0px; ">
	  	  <table width="500" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td width="49%" align="right">Mailing List </td>
              <td width="1%">:</td>
              <td width="50%"><select name="list_id">
				<option value="">All Mailing List</option>
				{html_options values=$MAILINGLIST.id output=$MAILINGLIST.name selected=$smarty.request.list_id}
				</select></td>
            </tr>
            <tr>
              <td align="right">Email</td>
              <td>:</td>
              <td><input type="text" name="email" value="{$smarty.request.email}" size="40"></td>
            </tr>
            <tr>
              <td align="right">Format</td>
              <td>:</td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input name="format" type="radio" value="H" id="H"{if $smarty.request.format eq 'H'} checked{/if}></td>
                  <td><label for="H">HTML</label></td>
                  <td>&nbsp;</td>
                  <td><input name="format" type="radio" value="T" id="T"{if $smarty.request.format eq 'T'} checked{/if}></td>
                  <td><label for="T">Text</label></td>
                  <td>&nbsp;</td>
                  <td><input name="format" type="radio" value="" id="B"{if $smarty.request.format eq ''} checked{/if}></td>
                  <td><label for="B">Both</label></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td align="right">Confirmed</td>
              <td>:</td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input name="confirmed" type="radio" value="Y" id="Y"{if $smarty.request.confirmed eq 'Y'} checked{/if}></td>
                  <td><label for="Y">Yes</label></td>
                  <td>&nbsp;</td>
                  <td><input name="confirmed" type="radio" value="N" id="N"{if $smarty.request.confirmed eq 'N'} checked{/if}></td>
                  <td><label for="N">No</label></td>
                  <td>&nbsp;</td>
                  <td><input name="confirmed" type="radio" value="" id="BB"{if $smarty.request.confirmed eq ''} checked{/if}></td>
                  <td><label for="BB">Both</label></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td align="right">Active</td>
              <td>:</td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input name="active" type="radio" value="Y" id="radio"{if $smarty.request.active eq 'Y'} checked{/if}></td>
                  <td><label for="radio">Yes</label></td>
                  <td>&nbsp;</td>
                  <td><input name="active" type="radio" value="N" id="radio2"{if $smarty.request.active eq 'N'} checked{/if}></td>
                  <td><label for="radio2">No</label></td>
                  <td>&nbsp;</td>
                  <td><input name="active" type="radio" value="" id="radio3"{if $smarty.request.active eq ''} checked{/if}></td>
                  <td><label for="radio3">Both</label></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="submit" value="Submit" class="naBtn"></td>
            </tr>
          </table>
  	    </form>
  	  </div>
	</td>
  </tr>
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($SUBSCRIPTION_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="45%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="name" display="Mailing List"}act=list&list_id={$smarty.request.list_id}&email={$smarty.request.email}&format={$smarty.request.format}&confirmed={$smarty.request.confirmed}&active={$smarty.request.active}{/makeLink}</td> 
          <td width="45%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="email" display="Email"}act=list&list_id={$smarty.request.list_id}&email={$smarty.request.email}&format={$smarty.request.format}&confirmed={$smarty.request.confirmed}&active={$smarty.request.active}{/makeLink}</td> 
        </tr>
        {foreach from=$SUBSCRIPTION_LIST item=subscription}
        <tr> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$subscription->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=delete&id={$subscription->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left">{$subscription->name}</td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$subscription->id}{/makeLink}">{$subscription->email}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$SUBSCRIPTION_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>