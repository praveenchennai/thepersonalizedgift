<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Event Details</td> 
      <td align="right"><a href="{makeLink mod=event pg=event_list}act={if $smarty.request.type == 'featured'}featured{else}event_list{/if}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">View All</a></td> 
    </tr> 
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{messageBox}</span></div>
      </td>
    </tr>
    <tr> 
      <td colspan=2 class="naGridTitle"><span class="group_style">Event Details</span></td> 
      <td class="naGridTitle" style="text-align:right">
	  <input name="Button2" type="submit" class="formbutton" value="{if $smarty.request.type == 'featured'}Remove from Featured{else}Add to Featured{/if}">	  </td>
    </tr> 
    <tr class=naGrid2>
      <td valign=top><div align=right class="element_style"> Event Name </div></td>
      <td valign=top>:</td>
      <td>{$EVENT_DET->event_name}</td>
    </tr>
    <tr class=naGrid2>
      <td valign=top><div align=right class="element_style"> Posted By </div></td>
      <td valign=top>:</td>
      <td>{$EVENT_DET->event_organizer}</td>
    </tr>
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Event Date </div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">{$EVENT_DET->event_date}</td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style">Event Time </div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">{$EVENT_DET->event_time} </td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Contace Email </div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_email} </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> Place of venue </div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_place}</td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Address</div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_address}</td> 
    </tr> 
	
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">City</div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_city}</td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">State</div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_state}</td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Zip</div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->event_zip}</td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Country</div></td> 
      <td valign=top>:</td> 
      <td>{$EVENT_DET->country_name}</td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"></div></td> 
      <td valign=top>&nbsp;</td> 
      <td>{$EVENT_DET->event_sh_descr}</td> 
    </tr>
	<tr align="center" class=naGrid1>
	  <td colspan="3" valign=top>{$EVENT_DET->event_long_descr}</td>
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign="center"><div align=center> 
          <input name="Button" type=button class="formbutton" value="Go Back" onClick="window.location.href='index.php?mod=event&pg=event_list&act={if $smarty.request.type != 'featured'}event_list{else}featured{/if}&sId={$smarty.request.sId}&fId={$smarty.request.fId}'"> 
        </div></td> 
    </tr> 
  </form> 
</table>
