<table width="100%" cellpadding="0" cellspacing="2">
  <tr>
    <td align="center" valign="top" class="tr-betweenht"></td>
    <td valign="top"></td>
  </tr>
  <tr>
    <td width="21%" valign="top"><table width="100%" cellpadding="0" cellspacing="4"  class="border">
      <tr>
        <td style="text-align:left;padding-left:5px" class="bodytexttitle_event">Events Links        </td>
      </tr>
      <tr>
        <td class="event_left_td"><a href="{makeLink mod=event pg=event}act=eventmail{/makeLink}" class="toplink">Event Invites</a></td>
      </tr>
     
      <tr>
        <td class="event_left_td"> <a href="{makeLink mod=event pg=event}act=evposted{/makeLink}" class="toplink">Events I've Posted </a> </td>
      </tr>
    
      <tr>
        <td class="event_left_td"> <a href="{makeLink mod=event pg=event}act=evattend{/makeLink}" class="toplink">Events I'm Attending </a> </td>
      </tr>
    
      <tr>
        <td class="event_left_td"> <a href="{makeLink mod=event pg=event}{/makeLink}" class="toplink">Create New Event </a> </td>
      </tr>
    </table></td>
    <td width="79%" valign="top">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class = "border">
      <tr>
        <td  class="bodytexttitle_event" style="padding-left:5px">Featured Events</td>
      </tr>
      <tr>
        <td>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		 <tr><td style="height:10px"></td></tr>
          <tr>
		 {$FEATURED}
		  </tr>
        </table>
		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="49%" class="bodytexttitle_event" style="padding-left:5px">Search Events </td>
        <td width="49%" align="right" class="bodytexttitle_event" style="padding-left:5px">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7">
          <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="tdbg">
            <form name="form1" method="post" action="">
              <tr>
                <td colspan="4" style="height:10px"></td>
              </tr>
              <tr>
                <td width="2%">&nbsp;</td>
                <td width="27%" class="bodytext">Keywords</td>
                <td width="19%" class="bodytext">Category</td>
                <td width="52%" class="bodytext">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="text" name="txtSearch" style="width:200px"></td>
                <td>
                  <select name="public_cat">
                    <option value="0">All Categories</option>
                    
					  	{html_options  options=$pb selected=$EVENT_DET->event_cat_id}
                      
                  </select>
                </td>
                <td><input type="submit" name="Submit" value="Search" class="button_class"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </form>
        </table></td>
      </tr>
      <tr>
        <td colspan="7">
          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td colspan="4" align="center">&nbsp;</td>
              <td align="right">{$LIMITLIST}</td>
            </tr>
            <tr>
              <td class="bodytexttitle_event">{makeLink mod=event pg=event orderBy="event_date" display="Date"}act=evhome{/makeLink}</td>
              <td class="bodytexttitle_event">{makeLink mod=event pg=event orderBy="event_time" display="Time"}act=evhome{/makeLink}</td>
              <td class="bodytexttitle_event">{makeLink mod=event pg=event orderBy="event_name" display="Event"}act=evhome{/makeLink}</td>
              <td class="bodytexttitle_event">{makeLink mod=event pg=event orderBy="cat_name" display="Category"}act=evhome{/makeLink}</td>
              <td class="bodytexttitle_event">{makeLink mod=event pg=event orderBy="event_place" display="Place &amp; Location"}act=evhome{/makeLink}</td>
            </tr>
        {foreach from=$EVENTDET item=event}
        <tr bgcolor="{cycle values="#FFFFFF,#EDF4FA"}">
          <td class="bodytext" style="padding-left:5px">{$event->event_date|date_format}</td>
          <td class="bodytext" style="padding-left:5px">{$event->event_time|date_format:"%r"}</td>
          <td class="bodytext" style="padding-left:5px"><a href="{makeLink mod=event pg=event}act=details&eventid={$event->event_id}{/makeLink}" class="toplinknormal">{$event->event_name|truncate:60:"...":true}</a></td>
          <td class="bodytext" style="padding-left:5px">{$event->cat_name}</td>
          <td class="bodytext" style="padding-left:5px">{$event->event_place}</td>
        </tr>
        <tr>
          <td style="height:5px"></td>
        </tr>
        {/foreach}
        </table></td>
      </tr>
      <tr>
        <td colspan="7" align="center" class="bodytext">{$NUMPAD}</td>
      </tr>
    </table></td>
  </tr>
</table>
