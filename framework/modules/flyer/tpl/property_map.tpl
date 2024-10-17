<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript">

	function load() {
		SetMapProperty(true,true);
		document.getElementById('mapContainer').style.display='none';
		document.getElementById('property_map_add').style.visibility='visible';

//document.getElementById('errMsg').style.display='none'
		{/literal}{if $mapSetPosStr} {literal}
		SetMapCoordpopUp ({/literal}{$mapSetPosStr},'{$popStr}'{literal});
		{/literal}{else}{literal}
		setMarkerPositionpopUp ({/literal}'{$query}','{$popStr}'{literal});  //$mapSetPosStr
		verifyAddressprivate({/literal}'{$query}','{$popStr}'{literal});
		{/literal}{/if}{literal}
	}
	
	function AddMapLocation () {
	
	    var PropertyID = document.getElementById('hid_album_id').value;
	 	var MarkPosition = document.getElementById('curr_coords').value;
		var CurrZoom = map.getZoom();
		
		var req1  = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
		str		  = "album_ID="+PropertyID+"&latlon="+MarkPosition+"&currZoom="+CurrZoom+"&propid="+{/literal}{$smarty.request.propid}{literal};
		
{/literal}
		req1.open("POST", "{makeLink mod=flyer pg=list}act=updateAlbumMapPos{/makeLink}&"+Math.random());
{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	
	function serverRese(_var) {
		_var = _var.split('|');
		eval(_var[0]);
	}

	function serverRese1(_var) {
		_var = _var.split('|');
		if (_var[0]) {
			setMarkerPositionpopUp ({/literal}'{$query}','{$popStr}'{literal});  //$mapSetPosStr
		}
	}

	function ClearMapLocation () {
		var PropertyID = document.getElementById('hid_album_id').value;
		var req1  = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverRese1);
		str		  = "album_ID="+PropertyID;
{/literal}
		req1.open("POST", "{makeLink mod=flyer pg=list}act=deleteAlbumMapPos{/makeLink}&"+Math.random());
{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	
	
	function verifyAddressprivate(address) {
		  if (geocoder) {
				geocoder.getLatLng( address, function(point) {
					if (!point) {
						document.getElementById('errMsg').style.display='inline';
					} else {
						return true;
					}
				  }
				 );
		  }
    }
</script>
{/literal}
<div align="center">
	<form name="form1" method="post" action="" enctype="multipart/form-data" style="border:0px">
<div align="center" style="border:1px solid #afafaf;width:910px;">

			<div style="margin-left:12px; margin-top:10px;">{$STEPS_HTML}</div>
			<div class="divSpc"></div>
			
	<div style="width:95%;" align="center" class="border">
	<div class="blocktitle">CONFIRM PROPERTY LOCATION ON MAPS</div>

			<div id="errMsg" align="center" class="orange4" style="border:1px solid #666666;background-color:#FFFFCC;position:absolute;width:500px;text-align:justify;padding:5px;z-index:1000;left:250px;display:none">Your search for '<strong>{$query|truncate:75:"...":true}</strong>' did not match any locations. <div align="right" class="bodytext"><strong><a href="javascript:void(0)"; onclick="javascript:document.getElementById('errMsg').style.display='none'">Close X</a><strong></div></div>
			
			<div  class ="border">
			<div id="mapContainer" class="orange4"><img src="{$GLOBAL.tpl_url}/images/loadingB.gif">Please wait ... Searching "{$query|truncate:75:"...":true}"</div>
			{$My_Map}
			</div>
	</div>
<div style="height:5px"><!-- --></div>
	<div style="width:95%;height:30px">
	<div style="float:left" class="mediumlink">
	<a href="javascript:void(0);" onClick="ClearMapLocation();" style="cursor:pointer" class="textlinknormal">Switch Property Position via LOCATION INFORMATION</a>
	<input name="hid_album_id" id="hid_album_id" type="hidden" value="{$album_id}">
	</div>
	<div style="float:right"><input type="button" name="Submit" value="Continue" class="button_class" onClick="AddMapLocation();" /></div>
	</div>
</div>
</form>
</div>

