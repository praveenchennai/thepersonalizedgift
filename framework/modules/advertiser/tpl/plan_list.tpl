<script language="javascript">
{literal}
	function planWin(plan_id){	
{/literal}		
		 var mywindow=window.open ("{makeLink mod=banner pg=banner_plans}act=plan{/makeLink}&plan_id="+plan_id,
		"test","menubar=0,toolbar=0,height="+(screen.height-200)+",width="+(screen.width-100)+",resizable=1,scrollbars=1,left=0,top=0");
  {literal} 
  	 }
{/literal}
</script>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Add New Plans</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=banner pg=banner_plans}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100%> 
        {if count($PLAN_LIST) >0}				
        <tr>
          <td width="9%" height="24" align="center" nowrap class="naGridTitle"></td> 
		  <td width="9%" height="24" align="center" nowrap class="naGridTitle"></td> 
          <td width="8%" height="24" align="center" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_plans orderBy="camp_name" display="Campaigns Name"}act=list{/makeLink}</td> 
		  <td width="21%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_plans orderBy="plan_name" display="Plan Name"}act=list{/makeLink}</td>
          <td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=banner pg=banner_plans orderBy="plan_price" display="Plan Price"}act=list{/makeLink}</td>
         <td width="21%" height="24" align="left" nowrap class="naGridTitle"> Duration</td>
		</tr>     
   		{foreach from=$PLAN_LIST item=plans}
       <tr>
	   	 <td  height="24" align="center" nowrap class="naGrid1" ><a class="linkOneActive" href="{makeLink mod=banner pg=banner_plans}act=delete&id={$plans->plan_id}{/makeLink}" onClick="return confirm('Do you really want to delete the record?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
          <td width="9%" height="24" align="center" nowrap  class="naGrid1"><a class="linkOneActive" href="{makeLink mod=banner pg=banner_plans}act=form&id={$plans->plan_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td width="8%" height="24" align="center" nowrap class="naGrid1">{$plans->camp_name}</td>           
          <td width="21%" height="24" align="left" nowrap class="naGrid1"><a class="linkOneActive" href="#" onClick=" return planWin({$plans->plan_id})">{$plans->plan_name}</a></td>
          <td width="20%" height="24" align="left" nowrap class="naGrid1">{$plans->plan_price}</td>
		  <td width="21%" height="24" align="left" nowrap class="naGrid1">{$plans->duration}{if $plans->duration_type|upper=='D'}&nbsp;Day{else}&nbsp;Month{/if}</td>
       </tr>   
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$PLAN_NUMPAD}</td> 
        </tr>
        {else}			
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>