<?php
/**
* Class Google Map
* Author   : Aneesh Aravindan
* Created  : 01/Oct/2007
* Modified : 31/Oct/2007 By Aneesh Aravindan
*/
include_once(FRAMEWORK_PATH."/modules/map/lib/class.map.php");
class G_Maps extends FrameWork{

  # *******************   CONFIGAURATION CONSTANT VALUES ****************** #
  
  var $GOOGLE_API_URL 	=  'http://maps.google.com/maps?file=api&amp;v=2&amp;key={key}';
  var $GEONAMES_URL 	=  'http://ws.geonames.org/search?q={query}&maxRows={rows}&style=LONG';
  var $GEOCODES_URL 	=  'http://maps.google.com/maps/geo?q={query}&output={c_format}&key={key}';


  # *******************   EDITABLE CONFIGURATION ************************* #
  var $GOOGLE_MAP_KEY 	= 	'ABQIAAAATRMNgZGkjqDZnBdOwI-MkBTVlbaUlGBn8yhIAwv4xN3VuLXsGhS0iTwvRyQoPC7CpVeNRqqxNS5Crw';
  var $MAX_RESULTS 		= 	 10;        # A maximum number of results to find
  var $WIDTH			=   '100%';    # Width of map area in pixels (px)  or percents (%)   
  var $HEIGHT 			= 	'500px';   # Height of map area in pixels (px) or percents (%)      
  
  var $SHOW_CONTROL 	=    true;     # Show map manipulation controls (move, zoom)
  var $SHOW_TYPE 		=	 true;     # Show map type selection (map, satellite,Hybrid)  
  
  var $SHOW_OVERLAY 	= 	 false;    # Show overlay markers of the query results along with basic info on them (i.e. population)
                                
  var $SECONDARY_SEARCH =	 true;     # If search by specified query produces no results, repeat the search without query
                                       # just the specified country.
      
  var $SHOW_CURR_LTLN   = 	 true;    
  var $SHOW_CURR_MARK   = 	 false;     # Show Marker Current Position if 1 marker                  
  
  var $USE_SOCKETS 		= 	 false;    # TRUE 
  										 /*
										   geocode data will be fetched by opening direct socket connection to HTTP port
										   of the geocode webservice.
										 */
									   # FALSE
									    /*
										   function file_get_contents() will be used. PHP ini value of allow_url_fopen must
										   be set to on.
										*/	 														
  
  var $ELEMENT_ID 		=    '';    # Map Element ID
  
  var $GEO_CODE_FORM 	=  	 'csv';  # XML KML JSON	

/**
     * default map type (G_NORMAL_MAP/G_SATELLITE_MAP/G_HYBRID_MAP)
     *
     * @var boolean
     */
  var $MAP_TYPE			=	 'G_HYBRID_MAP';
  var $MAP_ZOOM			=	 8;
  # ********************  Miscs Variables ******************** #
  var $query 			=  	 '';
  var $country 			= 	 '';
  var $results 			= 	 '';
  var $default			= 	 '';

  var $SHOW_LOCALSEARCH	=	false;
  
  
  
  # ********** Begin Constructor  ****************
  function G_Maps ($element = 'map', $query = '', $country = '') {


	$map	 		= 	 new Map();
	$CONFIG_DETAILS = $map->getConfiguration();
	foreach ($CONFIG_DETAILS as $Config) {
	  if ( $Config['map_field'] == 'key_value' ) {
		 $this->GOOGLE_MAP_KEY    =   $Config['map_value'];
	  }
	  if ( $Config['map_field'] == 'map_api_url' ) {
		  $this->GOOGLE_API_URL    =   $Config['map_value'];
	  }
	  if ( $Config['map_field'] == 'geo_code_url' ) {
		  $this->GEOCODES_URL    =   $Config['map_value'];
	  }
	}

    # set element + query + country
	$this->ELEMENT_ID    = $element;
    $this->query 		 = $query;
    $this->country 		 = $country;
    # set default (if query produces zero results) - default New York
    $this->default 		=  array(
								  'name'        => 'New York', 
								  'lat'         => '43.00028',
								  'lng'         => '-75.50028',
								  'geonameId'   => '5128638', 
								  'countryCode' => 'US', 
								  'countryName' => 'United States', 
								  'fcl'         => 'A', 
								  'fcode'       => 'ADM1', 
								  'fclName'     => 'country, state, region,...', 
								  'fcodeName'   => 'first-order administrative division', 
								  'population'  => '19274244' 
						   );
  }
  # ********** End Constructor  ****************


  function setConfig ($key, $val) {
		$this->$key = $val;
		return true;
  }

  function setCountry ($country) {
		$this->country .= ' '.$country;
		return true;
  }

  function setQuery ($query) {
		$this->query = $query;
		return true;
  }

  function setMaxResults ($max) {
		$this->MAX_RESULTS = $max;
		return true;
  }

	function setMapType($type) {
		switch($type) {
			case 'hybrid':
				$this->MAP_TYPE = 'G_HYBRID_MAP';
				break;
			case 'satellite':
				$this->MAP_TYPE = 'G_SATELLITE_MAP';
				break;
			case 'map':
			default:
				$this->MAP_TYPE = 'G_NORMAL_MAP';
				break;
		}       
	}    

  function setMpZoom ($mzoom) {
		$this->MAP_ZOOM = $mzoom;
		return true;
  }

  function getResults () {
		# check if results were already fetched
		if (!is_array($this->results)) {
		  $this->fetchResults();
		}
		return $this->results;
  }

  function getGeoCod ($query) {
  	$res	=	file('http://maps.google.com/maps/geo?q='. $query .'&output=csv&key='.$this->GOOGLE_MAP_KEY.'');
  	return $res;
  }	
  function showMap () {
		 
		$map_Store = ''; 
		$current = $this->default;
		$this->results[] = $current;

		# determine correct zoom level
		#$zoom = $this->calcZoom($current);
		$zoom =	$this->MAP_ZOOM;
		# prepare url
		$url = str_replace('{key}',$this->GOOGLE_MAP_KEY, $this->GOOGLE_API_URL);
    
		# start map code
		$map_Store = '<script src="'.$url.'" type="text/javascript"></script>'."\r\n".
			'<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>'."\r\n".
			'<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script>'."\r\n".
			 '<script type="text/javascript">'."\r\n".
			 '//<![CDATA['."\r\n".
			 #'var bounds;'."\r\n".SetMapElem
			 ' var map = null;'."\r\n".
			 ' var baseIcon =  new GIcon();'."\r\n".            
			 ' var DEF_ICO_SEL = G_DEFAULT_ICON;'."\r\n".
			 ' var CURR_CENTER_SAVE = null;'."\r\n".
             ' var geocoder = null;'."\r\n";
		/* create a function for adding markers
		$map_Store .= 'function createMarker(point, descr) {'."\r\n".
			 '  var marker = new GMarker(point);'."\r\n".
			 '  GEvent.addListener(marker, "click", function() {'."\r\n".
			 '    marker.openInfoWindowHtml(descr);'."\r\n".
			 '  });'."\r\n".
			 '  return marker;'."\r\n".
			 '}'."\r\n";
               */
		# begin main function
		$map_Store .= 'function G_Maps_load() {'."\r\n".
			 '  if (GBrowserIsCompatible()) {'."\r\n";
       
		# create object and center it  
		$map_Store .=  '    map = new GMap2(document.getElementById("'.$this->ELEMENT_ID.'"));'."\r\n".
			  '    map.setCenter(new GLatLng('.$current['lat'].', '.$current['lng'].'), '.$zoom.','.$this->MAP_TYPE.');'."\r\n".	
			  '    baseIcon = new GIcon();'."\r\n".		
			  'geocoder = new GClientGeocoder();'."\r\n";
			  # '    bounds = map.getBounds();'."\r\n";
		
			
			
		
		#add Local Search
		if ($this->SHOW_LOCALSEARCH) {
			
			$map_Store .=  'var options = {resultList : document.getElementById("resultsHide"),searchFormHint : "Neighborhood Places",suppressInitialResultSelection : true};'."\r\n";
		
			$map_Store .=  '  map.addControl(new google.maps.LocalSearch(options), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));'."\r\n";
			
		}
 
		# add controls
		if ($this->SHOW_CONTROL) {
		  $map_Store .= '    map.addControl(new GLargeMapControl());'."\r\n";
		}
    
		if ($this->SHOW_TYPE) {
		  $map_Store .= '    map.addControl(new GMapTypeControl());'."\r\n";
		  $map_Store .= '    map.addControl(new GScaleControl());'."\r\n";
		  #echo '    map.addOverlay(createMarker(new GLatLng(20.0, 77.0), ""));'."\r\n";	  
		}
    
		# add result overlay markers
		if ($this->SHOW_OVERLAY) {
		  $cnt = sizeof($this->results);
			  for ($i = 0; $i < $cnt; $i++) {
				$location = &$this->results[$i];
				$description = '<strong>'.$this->javaScriptEncode($location['name']).'</strong><br />';
				if ($location['fcode'] == 'PPLC') {
				  $description .= 'A capital of '.$location['countryName'].'<br />';
				}
				if (isset($location['population']) && ($location['population'] > 0)) {
				  $description .= 'Population '.number_format($location['population'], 0, '.', ',').'<br />';
				}
				// enclose caption with style
				$description = '<span style="color: #000000;">'.$description.'</span>';
				$map_Store .= 'map.addOverlay(createMarker(new GLatLng('.$location['lat'].', '.$location['lng'].'), \''.$description.'\'));'."\r\n";
			  }
		}
    
		# end map code
		$map_Store .= '  }'."\r\n".
			 '}'."\r\n".
			 '//]]>'."\r\n".
			 '</script>'."\r\n";
        $map_Store .=  '<div id="resultsHide" style="display:none"></div>';
		# put div
		if ($this->SHOW_CURR_MARK) {
		$map_Store .=  '<input type="text" class="textauto" style="width: '.$this->WIDTH.'" name="curr_coords" id="curr_coords" value="" readonly>';
		} else {
		$map_Store .=  '<input type="hidden" class="textauto" style="width: '.$this->WIDTH.'" name="curr_coords" id="curr_coords" value="" readonly>';
		}
		$map_Store .= '<div id="'.$this->ELEMENT_ID.'" style="visibility:hidden;width: '.$this->WIDTH.'; height: '.$this->HEIGHT.'"></div>';
			 if ($this->SHOW_CURR_LTLN)	{
			 $LatLon = "Lat:" .$current['lat'] . "Lon:" . $current['lng'];
			 $map_Store .=  '<input type="text" class="textauto" style="width: '.$this->WIDTH.'" name="curLatLong" id="curLatLong" value='.$LatLon.' readonly>';
			 } else {
			 $map_Store .=  '<input type="hidden" id="curLatLong" name="curLatLong" readonly>';
			 }
			 
			 $map_Store .=  '<input type="hidden" id="hid_'.$this->ELEMENT_ID.'" name="hid_'.$this->ELEMENT_ID.'" readonly>';
			 $map_Store .=  '<div style="visibility:hidden" id="hidPop_'.$this->ELEMENT_ID.'" name="hidPop_'.$this->ELEMENT_ID.'"></div>';
		# execute event
		$map_Store .= '<script type="text/javascript">'."\r\n".
			 'window.onload = G_Maps_load;'."\r\n".
			 'window.onunload = GUnload;'."\r\n".
			 'addLoadEvent(load);</script>';    
		return $map_Store;
  }

  function showMap11 () {
		# check if results were already fetched
		if (!is_array($this->results)) {
		  $this->fetchResults();
		}
		
		# get coordinates of the first location
		if (isset($this->results[0])) {
		  $current = &$this->results[0];
		}
		else {
		  $current = $this->default;
		  $this->results[] = $current;
		}
    
		# determine correct zoom level
		$zoom = $this->calcZoom($current);
	
		# prepare url
		$url = str_replace('{key}',$this->GOOGLE_MAP_KEY, $this->GOOGLE_API_URL);
    
		# start map code
		echo '<script src="'.$url.'" type="text/javascript"></script>'."\r\n".
			 '<script type="text/javascript">'."\r\n".
			 '//<![CDATA['."\r\n".
			 #'var bounds;'."\r\n".SetMapElem
			 ' var map = null;'."\r\n".
			 ' var map = null;'."\r\n".
             ' var geocoder = null;'."\r\n";
		# create a function for adding markers
		echo 'function createMarker(point, descr) {'."\r\n".
			 '  var marker = new GMarker(point);'."\r\n".
			 '  GEvent.addListener(marker, "click", function() {'."\r\n".
			 '    marker.openInfoWindowHtml(descr);'."\r\n".
			 '  });'."\r\n".
			 '  return marker;'."\r\n".
			 '}'."\r\n";

		# begin main function
		echo 'function G_Maps_load() {'."\r\n".
			 '  if (GBrowserIsCompatible()) {'."\r\n";
       
		# create object and center it  
		echo  '    map = new GMap2(document.getElementById("'.$this->ELEMENT_ID.'"));'."\r\n".
			  '    map.setCenter(new GLatLng('.$current['lat'].', '.$current['lng'].'), '.$zoom.','.$this->MAP_TYPE.');'."\r\n".		
			  'geocoder = new GClientGeocoder();'."\r\n";
			  # '    bounds = map.getBounds();'."\r\n";
			 
		# add controls
		if ($this->SHOW_CONTROL) {
		  echo '    map.addControl(new GLargeMapControl());'."\r\n";
		}
    
		if ($this->SHOW_TYPE) {
		  echo '    map.addControl(new GMapTypeControl());'."\r\n";
		  echo '    map.addControl(new GScaleControl());'."\r\n";
		  #echo '    map.addOverlay(createMarker(new GLatLng(20.0, 77.0), ""));'."\r\n";	  
		}
    
		# add result overlay markers
		if ($this->SHOW_OVERLAY) {
		  $cnt = sizeof($this->results);
			  for ($i = 0; $i < $cnt; $i++) {
				$location = &$this->results[$i];
				$description = '<strong>'.$this->javaScriptEncode($location['name']).'</strong><br />';
				if ($location['fcode'] == 'PPLC') {
				  $description .= 'A capital of '.$location['countryName'].'<br />';
				}
				if (isset($location['population']) && ($location['population'] > 0)) {
				  $description .= 'Population '.number_format($location['population'], 0, '.', ',').'<br />';
				}
				// enclose caption with style
				$description = '<span style="color: #000000;">'.$description.'</span>';
				echo 'map.addOverlay(createMarker(new GLatLng('.$location['lat'].', '.$location['lng'].'), \''.$description.'\'));'."\r\n";
			  }
		}
    
		# end map code
		echo '  }'."\r\n".
			 '}'."\r\n".
			 '//]]>'."\r\n".
			 '</script>'."\r\n";
    
		# put div
		if ($this->SHOW_CURR_MARK) {
		echo  '<input type="text" class="textauto" style="width: '.$this->WIDTH.'" name="curr_coords" id="curr_coords" value="" readonly>';
		} else {
		echo  '<input type="hidden" class="textauto" style="width: '.$this->WIDTH.'" name="curr_coords" id="curr_coords" value="" readonly>';
		}
		echo '<div id="'.$this->ELEMENT_ID.'" style="visibility:hidden;width: '.$this->WIDTH.'; height: '.$this->HEIGHT.'"></div>';
			 if ($this->SHOW_CURR_LTLN)	{
			 $LatLon = "Lat:" .$current['lat'] . "Lon:" . $current['lng'];
			 echo  '<input type="text" class="textauto" style="width: '.$this->WIDTH.'" name="curLatLong" id="curLatLong" value='.$LatLon.' readonly>';
			 } else {
			 echo  '<input type="hidden" id="curLatLong" name="curLatLong" readonly>';
			 }
			 
			 echo  '<input type="hidden" id="hid_'.$this->ELEMENT_ID.'" name="hid_'.$this->ELEMENT_ID.'" readonly>';
		# execute event
		echo '<script type="text/javascript">'."\r\n".
			 'window.onload = G_Maps_load;'."\r\n".
			 'window.onunload = GUnload;'."\r\n".
			 'addLoadEvent(load);</script>';    
		return true;
  }

  function showLocationControl ($properties = '') {
  
		# check if results were already fetched
		if (!is_array($this->results)) {
		  $this->fetchResults();
		}
    
		# prepent properties with whitespace
		if ($properties != '') {
		  $properties = ' '.$properties;
		}
    
		# create function
		echo '<script type="text/javascript">'."\r\n".
			 'function changeMarker (pos) {'."\r\n".
			 '  dta = pos.split(\' \');'."\r\n".
			 '  map.panTo(new GLatLng(dta[0], dta[1]));'."\r\n".
			 '  map.setZoom(dta[2]);'."\r\n".
			 '}'."\r\n".
			 '</script>'."\r\n";    
			 
		# begin
		echo '<select'.$properties.' onchange="changeMarker(this.options[this.selectedIndex].value);">'."\r\n";
    
		# show options
		$cnt = sizeof($this->results);
		for ($i = 0; $i < $cnt; $i++) {
		  $location = &$this->results[$i];
		  echo '<option value="'.$location['lat'].' '.$location['lng'].' '.$this->calcZoom($location).'">'.$location['name'].' ('.$location['countryName'].')</option>'."\r\n"; 
		}
		
		# show a no-results sign
		if ($cnt == 0) {
		  echo '<option value="">-- no results --</option>'."\r\n";
		}
		
		# end
		echo '</select>'."\r\n";
		
		return true;
  }
  function calcZoom ($location) {
    // get primary zoom level based on location type
    switch ($location['fcl']) {
      case 'A':
        $zoom = 10;
        break;
      case 'P':
        $zoom = 13;
        break;
      default:
        $zoom = 16;
        break;
    }
    
    // modify zoom type based on population
    $mod = 0;
    if (isset($location['population'])) {
      $mod = floor($location['population'] / 5000000);
      if ($mod > 2) {
        $mod = 2;
      }
    }
    
    return $zoom - $mod;
  }
  // }}}
  // {{{
  function fetchResults ($repeat = false) {
    // prepare fetch url
    if ($repeat) {
      $url = str_replace(
        array('{query}', '{rows}'),
        array($this->country, $this->MAX_RESULTS),
        $this->GEONAMES_URL);
    }
    else {
      $url = str_replace(
        array('{query}', '{rows}'),
        array(urlencode($this->query), $this->MAX_RESULTS),
        $this->GEONAMES_URL);
    }
    
    // add country filtering
    if ($this->country != '') {
      $url .= '&country='.$this->country;
    }
    
    // fetch url
    if ($this->USE_SOCKETS) {
      $xml = $this->fetchUrl($url);
    }
    else {
      $xml = file_get_contents($url);
    }
    
    // chech if file was actually fetched
    if ($xml === false) {
      $this->results = array();
      return false;
    }
    
    // parse fetched XML
    
    // get all items
    $this->results = array(); 
    preg_match_all('/<geoname>(.*)<\/geoname>/isU', $xml, $arr, PREG_SET_ORDER);
    
    // parse each individual item
    while (list(, $item) = each($arr)) {
      preg_match_all('/<([a-z]+)>(.*)<\/[a-z]+>/isU', $item[1], $params, PREG_SET_ORDER);
      $location = array();
      while (list(, $param) = each($params)) {
        $location[$param[1]] = $param[2];
      }
      $this->results[] = $location;
    }
    
    // check if search shoud be repeated with less restrictive query
    if (sizeof($this->results) == 0 && $this->SECONDARY_SEARCH && !$repeat) {
      $this->fetchResults(true);
    }
    
    return true;
  }
  // }}}
  // {{{
  function javaScriptEncode ($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("'", "\\'", $str);
    $str = str_replace("\r\n", '\r\n', $str);
    $str = str_replace("\n", '\r\n', $str);
    return $str;
  }
  // }}}
  // {{{
  function fetchUrl ($url) {
    // parse URL
    if (!$elements = @parse_url($url)) {
      return '';
    }
    
    // add default port
    if (!isset($elements['port'])) {
      $elements['port'] = 80;
    }
    
    // open socket
    $fp = fsockopen($elements['host'], $elements['port'], $errno, $errstr, 20);
    if (!$fp) {
      return '';
    }
    
    // assemble path
    $path = $elements['path'];
    if (isset($elements['query'])) {
      $path .= '?'.$elements['query'];
    }    
    // assemble HTTP request header
    $request  = "GET $path HTTP/1.1\r\n";
    $request .= "Host: ".$elements['host']."\r\n";
    $request .= "Connection: Close\r\n\r\n";    
    // send HTTP request header and read output
    $result = '';
    fwrite($fp, $request);
    while (!feof($fp)) {
      $result .= fgets($fp, 128);
    }
      // close socket connection
    fclose($fp);    
    // strip extra text from result
    return preg_replace('/^[^<>]*(<.*>)[^<>]*$/s', '$1', $result);
  }
  // }}}
  
  
  /**
     * get distance between to geocoords using great circle distance formula
     * 
     * @param float $lat1
     * @param float $lat2
     * @param float $lon1
     * @param float $lon2
     * @param float $unit   M=miles, K=kilometers, N=nautical miles, I=inches, F=feet
     */
    function geoGetDistance($lat1,$lon1,$lat2,$lon2,$unit='M') {
        
      // calculate miles
      $M =  69.09 * rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2)))); 

      switch(strtoupper($unit))
      {
        case 'K':
          // kilometers
          return $M * 1.609344;
          break;
        case 'N':
          // nautical miles
          return $M * 0.868976242;
          break;
        case 'F':
          // feet
          return $M * 5280;
          break;            
        case 'I':
          // inches
          return $M * 63360;
          break;            
        case 'M':
        default:
          // miles
          return $M;
          break;
      }
      
    }    
    
    
    
      /**
     * get geocode lat/lon points for given address from Yahoo
     * 
     * @param string $address
     */
    function geoGetCoords($address,$depth=0) {        
                //file('http://maps.google.com/maps/geo?q='. $query .'&output=csv&key='.$this->GOOGLE_MAP_KEY.'');
  	               
                $_url = sprintf('http://%s/maps/geo?&q=%s&output=csv&key=%s','maps.google.com',rawurlencode($address),$this->GOOGLE_MAP_KEY);

                $_result = false;
                
                if($_result = $this->fetchURLNew($_url)) {
                    $_result_parts = explode(',',$_result);
                    if($_result_parts[0] != 200)
                        return false;
                    $_coords['lat'] = $_result_parts[2];
                    $_coords['lon'] = $_result_parts[3];
                    
                    $_coords = $_result_parts[2] . ',' . $_result_parts[3];
                }
   
     
        return $_coords;       
    }
    
    

    /**
     * fetch a URL. Override this method to change the way URLs are fetched.
     * 
     * @param string $url
     */
    function fetchURLNew($url) {

        return file_get_contents($url);

    }

}   
?>