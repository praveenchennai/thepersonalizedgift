<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Registered Users-->{$SUBNAME}</td> 
		  <td nowrap  align="right">Search by event: 
		    <input type="text" name="txtsearch">&nbsp;<input type="submit" border="0" value="Search"></td> 
		  <td align="right" ><a href="{makeLink mod=event pg=event_list}act=event_list{/makeLink}&sId={$SUBNAME}&mId={$MID} ">View All</a></td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		<tr>
		  <td  align="right" colspan="6" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 

        </tr>
        <tr>
          <td width="32" nowrap class="naGridTitle" height="24" align="left"></td> 
		  <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=event pg=event_list orderBy="username" display="User"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=event pg=event_list orderBy="event_date" display="Event Date"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=event pg=event_list orderBy="event_time" display="Event Time"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="100" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=event pg=event_list orderBy="event_name" display="Event"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="100">{makeLink mod=event pg=event_list orderBy="rsvp" display="RSVP"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left" width="150">{makeLink mod=event pg=event_list orderBy="event_place" display="Place"}act=event_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 


        </tr>
        {if count($EVENT_LIST) > 0}
        {foreach from=$EVENT_LIST item=event}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=event pg=event_list}act=event_list&stat={$event->active}&eventid={$event->event_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img border="0" title="Activate/Deactivate"  src="{$GLOBAL.tpl_url}/images/active{$event->active}.gif" /></a></td> 
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=view&id={$event->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$event->username}</a></td> 
          <td valign="middle" height="24" align="left">{$event->event_date|date_format}</td> 
		  <td valign="middle" height="24" align="left">{$event->event_time|date_format:"%r"}</td> 
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=event pg=event_list}eventid={$event->event_id}&act=details&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$event->event_name}</a></td> 
		  <td valign="middle" height="24" align="left">{$event->rsvp}</td> 
		  <td valign="middle" height="24" align="left">{$event->event_place}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="9" class="msg" align="center" height="30">{$USER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="9" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>