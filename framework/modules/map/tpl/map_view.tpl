<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript">

	function load() {
	    var SetMapElem = Array ("country");
		SetMapElement (SetMapElem);
		SetMapProperty(true);
		document.getElementById('map_view').style.visibility='visible';
	}

	function set_coord() {
		    var country = document.getElementById("country").value;
			var qury	=	country;
			setMarkerPosition (qury,'no');
	}

</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="mapconfig" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Settings</td> 
      <td align="right"><a href="{makeLink mod=map pg=config}act=config&sId=Map%20Settings&fId={$smarty.request.fId}{/makeLink}">Back</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Map View</span></td> 
    </tr> 
	
		<tr class="naGrid2"> 
		  <td width="10%" align="left" valign=top class="element_style">Move to</td> 
		  <td width="1%" valign=top>:</td> 
		  <td width="69%"><select name="country" class="input" id="country"  onChange="">
          <option>---Select a Country---</option>
							{html_options values=$COUNTRY_LIST.country_name output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
                    </select></td> 
		</tr>
		<tr bgcolor="#ffffff">
		  <td colspan="3" valign=top>{$My_Map}</td>
		</tr> 
  </form> 
</table>
