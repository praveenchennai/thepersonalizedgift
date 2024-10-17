<?php
/**
* Class Map 
* Author   : Aneesh Aravindan
* Created  : 01/Oct/2007
* Modified : 31/Oct/2007 By Aneesh Aravindan
*/
class Map extends FrameWork {
    
	var 	$errorMessage;
		
    function Map() {
        $this->FrameWork();
    }


    function getConfiguration ($types = 'default') {
        $sql		= "SELECT * FROM map_config WHERE show_admin='Y' AND type='$types'";
        $rs = $this->db->get_results($sql, ARRAY_A);
        return $rs;
    }
	
	function editConfiguration (&$req) {				
        extract($req);

		foreach ($req as $key=>$val) {
			$this->db->update("map_config", array("map_value"=>$val), "map_field='$key'");
		}

		return true;
		
	}
	
	
	
	

    /**
	 * Add Edit Map Menu
	 *
	 * @param <POST/GET Array> $req
	 * @return Error Message if Any
	 */
    function menuAddEdit (&$req) {				
        extract($req);
		
		
		global $store_id;				
        if(!trim($section_id)) {
            $message = "Section is required";
        } elseif (!trim($name)) {
            $message = "Name is required";
        } elseif (!trim($seo_url)) {
            $message = "SEO URL is required";
        } elseif (!trim($position)) {
            $message = "Position is required";
        } else {
            $array = array("section_id"=>$section_id, "name"=>$name, "seo_url"=>$seo_url, "position"=>$position, "active"=>$active, "type_tip"=>$type_tip);//changed "type_tip"=>$type_tip 0n 27th sept 
          if($id) {
                $array['id'] = $id;
                $this->db->update("cms_menu", $array, "id='$id'");
            } else {
				$this->db->insert("cms_menu", $array);
                $id = $this->db->insert_id;					
				//print_r($id);exit;
				if(trim($store_id)){					
					$arr_cmsstore	=	array("store_id"=>$store_id,"cms_id"=>$this->db->insert_id, "cms_type"=>'M');
					$this->db->insert("store_cms", $arr_cmsstore);
				}				
            }
            return true;
        }
        return $message;
    }


	function storeGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	
	

	
	
}


?>