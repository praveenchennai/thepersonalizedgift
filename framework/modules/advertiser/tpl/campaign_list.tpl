<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Add New Campaign</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=banner pg=banner_campaign}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100%> 
        {if count($CAMPAIGN_LIST) >0}				
        <tr>
          <td width="9%" height="24" align="center" nowrap class="naGridTitle"></td> 
		  <td width="9%" height="24" align="center" nowrap class="naGridTitle"></td> 
          <td width="8%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_campaign orderBy="camp_name" display="Campaigns Name"}act=list{/makeLink}</td> 
		  <td width="21%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_campaign orderBy="camp_width" display="Width"}act=list{/makeLink}</td>
          <td width="20%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_campaign orderBy="camp_height" display="Height"}act=list{/makeLink}</td>
         <td width="21%" height="24" align="center" nowrap class="naGridTitle"> {makeLink mod=banner pg=banner_campaign orderBy="camp_limit" display="Limit"}act=list{/makeLink}</td>
		</tr>     
   		{foreach from=$CAMPAIGN_LIST item=campaign}
       <tr>
	   	 <td  height="24" align="center" nowrap class="naGrid1" ><a class="linkOneActive" href="{makeLink mod=banner pg=banner_campaign}act=delete&id={$campaign->id}{/makeLink}" onClick="return confirm('Do you really want to delete the record?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
          <td width="9%" height="24" align="center" nowrap  class="naGrid1"><a class="linkOneActive" href="{makeLink mod=banner pg=banner_campaign}act=form&id={$campaign->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td width="8%" height="24" align="center" nowrap class="naGrid1">{$campaign->camp_name}</td>           
          <td width="21%" height="24" align="center" nowrap class="naGrid1">{$campaign->camp_width}</td>
          <td width="20%" height="24" align="center" nowrap class="naGrid1">{$campaign->camp_height}</td>
		  <td width="21%" height="24" align="center" nowrap class="naGrid1">{$campaign->camp_limit}</td>
       </tr>   
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$CAMPAIGN_NUMPAD}</td> 
        </tr>
        {else}			
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>