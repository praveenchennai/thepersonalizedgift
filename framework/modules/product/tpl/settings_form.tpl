
	</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=settings{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
	{if $FIELDS.0==Y}
   <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Name</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="name" value="{$PRD_SETTINGS.name}" class="formText" size="30" > </td> 
    </tr>
	{/if}
	{if $FIELDS.1==Y}
   <tr class=naGrid2>
     <td align="right" valign=top>Display Order </td>
     <td valign=top>:</td>
     <td><input type="text" name="display_order" value="{$PRD_SETTINGS.display_order}" class="formText" size="30" ></td>
   </tr>
   {/if}
   {if $FIELDS.2==Y}
   <tr class=naGrid2>
     <td align="right" valign=top>Display In Right Side </td>
     <td valign=top>:</td>
     <td><input name="right_display" type="checkbox" id="right_display" value="Y" {if $PRD_SETTINGS.right_display=='Y'} checked{/if}></td>
   </tr> 
   {/if}
   <!-- ////////////////////////////-->
   {if $SHOW==1}
   <tr >
   <td colspan="3" >
   
  <table width="100%"  border="0" cellspacing="0" cellpadding="4">
  
      <tr>
        <td colspan="2" class="naGridTitle">Assign Product Groups  to store</td>
        <td colspan="2" class="naGridTitle"></td>
        </tr>
	  {if $smarty.request.manage==''}
	  <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
       <td width="5%"><input type="checkbox" name="mainstore" value="0" {if $MAINSTORE !="" }CHECKED{else}UNCHECKED{/if}></td>
        <td width="37%">Main Store</td>
            <td width="38%">&nbsp;</td>
        <td width="16%"><!--{$MAINSTORE}--></td>
      </tr>
	   <tr>
        <td colspan="4"><div id="store_0" style="display:inline"></div></td>
        </tr>
	{/if}
	   {foreach from=$STORE1 item=stores name=store_loop}
      <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
        <td width="5%">{if $smarty.request.id}{html_checkboxes name='stores_id' values=$stores.id selected=$RELSTORE.id}{else}{html_checkboxes name='stores_id' values=$stores.id selected=$stores.id}{/if}</td>
        <td width="20%">{$stores.name}</td>
        <td width="50%">&nbsp;</td>
        <td width="25%">{$stores.status}</td>
      </tr>
	    <tr>
        <td colspan="4"><div id="store_{$stores.id}" style="display:inline"></div></td>
        </tr>
	  {/foreach}
    </table>
   
   
   </td>
   
   </tr>
   {/if}
   
   
   <!-- //////////////// -->
   
   
   
   {if $STORE_PERMISSION.edit == 'Y'}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  {/if}
  </form> 
</table>
