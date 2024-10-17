<?php
    /** 
    * UPS Shipping Calculation Class 
    * @author Hermawan Haryanto 
    * @date May 26, 2004 
    * @description 
    * This class is returning the cost of shipping using UPS 
    * Tested works for USA 
    * Please notify me if it's work for other contry 

        UPS PRODUCT CODE (this should be in a drop down menu) 
            Next Day Air Early AM    1DM 
            Next Day Air            1DA 
            Next Day Air Saver        1DP 
            2nd Day Air AM            2DM 
            2nd Day Air                2DA 
            3 Day Select            3DS 
            Ground                    GND 
            Canada Standard            STD 
            Worldwide Express        XPR 
            Worldwide Express Plus    XDM 
            Worldwide Expedited        XPD 
         
        UPS RATE CHART 
            Regular+Daily+Pickup 
            On+Call+Air 
            One+Time+Pickup 
            Letter+Center 
            Customer+Counter 
         
        Container Chart 
            Customers Packaging        00 

            UPS Letter Envelope        01 
            or 
            UPS Tube 

            UPS Express Box            21 
            UPS Worldwide 25kg Box    22 
            UPS Worldwide 10 kg Box    23 
         
        ResCom UPS Table 
            Residential                1 
            Commercial                2 
    */ 
class ups 
{ 
    var $_action; 
    var $_delivery_code; 
    var $_src_zip; 
    var $_dst_zip; 
    var $_weight; 
    var $_src_country; 
    var $_dst_country; 
    var $_rate_chart; 
    var $_rate_charts; 
    var $_container; 
    var $_containers; 
    var $_rescom; 
    var $_rescoms; 
    var $_errors; 
    function ups() 
    { 
        $this->_errors = array(); 
        $this->_action = 3; 
        $this->_delivery_code = 'GND'; 
        $this->_src_country = 'US'; 
        $this->_dst_country = 'US'; 
        $this->_rate_chart = 'Regular+Daily+Pickup'; 
        $this->_container = '00'; 
        $this->_rescom = 1; 
        $this->_rate_charts = array('Regular+Daily+Pickup', 
                                    'On+Call+Air', 
                                    'One+Time+Pickup', 
                                    'Letter+Center', 
                                    'Customer+Counter'); 
        $this->_containers = array ('00', '01', '21', '22', '23', '1', '2'); 
        $this->_rescoms = array ('1', '2'); 
         
    } 
    function set_rescom ($int) 
    { 
        $this->_rescom = $int; 
    } 
    function set_container ($int) 
    { 
        $this->_container = $int; 
    } 
    function set_rate_chart($int) 
    { 
        $this->_rate_chart = 1; 
    } 
    function set_action($int) 
    { 
        $this->_action = $int; 
    } 
    function set_delivery_code ($code) 
    { 
        $this->_delivery_code = $code; 
    } 
    function set_src_zip ($zip) 
    { 
        $this->_src_zip = $zip; 
    } 
    function set_dst_zip ($zip) 
    { 
        $this->_dst_zip = $zip; 
    } 
    function set_src_country ($code) 
    { 
        $this->_src_country = $code; 
    } 
    function set_dst_country ($code) 
    { 
        $this->_dst_country = $code; 
    } 
    function set_weight ($int) 
    { 
        $this->_weight = $int; 
    } 
    function get_cost () 
    { 
        if ($this->_action == '') 
            array_push ($this->_errors, 'UPS Action is not defined'); 
        if ($this->_delivery_code == '') 
            array_push ($this->_errors, 'UPS Product Code is not defined'); 
        if ($this->_src_zip == '') 
            array_push ($this->_errors, 'Source Zip Code is unavailable'); 
        if ($this->_src_country == '') 
            array_push ($this->_errors, 'Source Country Code is unavailable'); 
        if ($this->_dst_zip == '') 
            array_push ($this->_errors, 'Destination Zip Code is unavailable'); 
        if ($this->_dst_country == '') 
            array_push ($this->_errors, 'Destination Country Code is unavailable'); 
        if ($this->_weight == '') 
            array_push ($this->_errors, 'Packet weight is not defined'); 
        if ($this->_rate_chart == '') 
            array_push ($this->_errors, 'Rate Chart is not defined'); 
        if ($this->_container == '') 
            array_push ($this->_errors, 'Client Shipping Package Type is not defined'); 
        if ($this->_rescom == '') 
            array_push ($this->_errors, 'Residential or Commercial? define it first!'); 
        if (count ($this->_errors) <= 0) 
        { 
            $url  = 'www.ups.com'; 
            $port = '80'; 
            $file = '/using/services/rave/qcostcgi.cgi'; 
            $qs   = '?'; 
            $qs  .= 'accept_UPS_license_agreement=yes'; 
            $qs  .= '&'; 
            $qs  .= '10_action='.$this->_action; 
            $qs  .= '&'; 
            $qs  .= '13_product='.$this->_delivery_code; 
            $qs  .= '&'; 
            $qs  .= '14_origCountry='. $this->_src_country; 
            $qs  .= '&'; 
            $qs  .= '15_origPostal='. $this->_src_zip; 
            $qs  .= '&'; 
            $qs  .= '19_destPostal='. $this->_dst_zip; 
            $qs  .= '&'; 
            $qs  .= '22_destCountry='. $this->_dst_country; 
            $qs  .= '&'; 
            $qs  .= '23_weight='. $this->_weight; 
            $qs  .= '&'; 
            $qs  .= '47_rateChart='.$this->_rate_charts[$this->_rate_chart]; 
            $qs  .= '&'; 
            $qs  .= '48_container='.$this->_rate_containers[$this->_rate_container]; 
            $qs  .= '&'; 
            $qs  .= '49_residential='.$this->_rescoms[$this->_rescom]; 
            $rqs  = $file.$qs; 
            
            if($buffer = file_get_contents("http://".$url.$rqs)) 
            {
                if (strpos ($buffer, '%')) 
                { 
                    $buffers = explode ('%', $buffer, -1); 
                    $errcode = substr ($buffers[0], -1);
                    switch($errcode){
                    	case 3:
                    		$returnval = $buffers[8];
                    		break;
                    	case 4:
                    		$returnval = $buffers[8];
                    		break;
                    	case 5:
                    		$this->_errors[] = $buffers[1];
                    		break;
                    	case 6:
                    		$this->_errors[] = $buffers[1];
                    		break;
                    }
                    if ($returnval)
                    { 
                        return $returnval; 
                    } 
                }
            } else {
            	array_push ($this->_errors, 'Could not open socket');
            }
            
        } 
    } 
}

?>