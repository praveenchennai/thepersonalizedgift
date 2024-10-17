<?php
class editor extends FrameWork
{
	# This function is used to save the xml data of product in to the product_xml table
	function saveXml($user_id,$product_id,$xmlData)
	{
		$array 			= 	array("user_id"=>$user_id,"product_id"=>$product_id,"xml"=>$xmlData);
		$this->db->insert("product_xml", $array);
		$id = $this->db->insert_id;
		return true;
	}
	# This function is used to insert the data for the display images of corresponding user
	function editorImageSave($req)
	{	$array 			= 	array("user_id"=>$req['user_id'],"image_name"=>$req['image'],"new_img_name"=>$req['fileName']);
		$this->db->insert("art_editor_image", $array);
		return true;
	}
	# this function is used for getting the xml formatted data of a particular product
	function getXml($xml_id)
	{
		$Qry	=	"SELECT * FROM product_xml where xml_id='$xml_id'";
		$row 	= 	$this->db->get_row($Qry,ARRAY_A);
		return $row['xml_data'];
	}
	# this function is used for getting the xml formatted data of a particular product
	function getSavedXml($id)
	{
		$Qry	=	"SELECT * FROM product_saved_work where pro_save_id='$id'";
		$row 	= 	$this->db->get_row($Qry,ARRAY_A);
		$xml	=	stripslashes($row['xml']);
		return $xml;
	}

	
# this function is used for getting the xml formatted data of a particular product
	function editgetSavedXml($id)
	{
		
		$Qry	=	"SELECT * FROM product_saved_work where id='$id'";
		$row 	= 	$this->db->get_row($Qry,ARRAY_A);
		$xml	=	stripslashes($row['xml']);
		return $xml;
	}
	# return the count of child category
	function childCount($catId)
	{
		$countQry	=	"SELECT * FROM ".FIRST_TABLE." where active='Y'  AND parent_id='$catId' ORDER BY category_name ASC ";
		$countRes	=	mysql_query($countQry); 
		$Count		=	mysql_num_rows($countRes);		
		return $Count;
	}
	# return count of product under a particular category
	function productCount($catId)
	{
		$countQry	=	"SELECT * FROM ".FOURTH_TABLE." where  category_id ='$catId' ";
		$countRes	=	mysql_query($countQry); 
		$Count		=	mysql_num_rows($countRes);		
		return $Count;
	}

	# return xml formatted product list under a category
	function products($catId)
	{	
		$photoPath		= SITE_URL."/modules/product/images/";
		header("Content-Type:text/xml");
		$p_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$p_xml .="<xml>\r\n";
		$productCatQry	=	"SELECT * FROM ".FOURTH_TABLE." where  category_id ='$catId' "; 
		$productCatRes	=	mysql_query($productCatQry);
		while($productCatRow=mysql_fetch_array($productCatRes))
		{
			
			$product_id	=	$productCatRow["product_id"]; 
			$productQry	=	"SELECT * FROM products where   id = $product_id "; 
			$productRes	=	mysql_query($productQry); 
			while($productRow=mysql_fetch_array($productRes))
			{
				if($productRow["active"]== "Y")
				{	
						if($productRow["image_extension"] != "")
						{	
							$photoPath	= SITE_URL."/modules/product/images/".$product_id.".".$productRow["image_extension"]; 	
						}
						else
						{	
							$photoPath	="";	
						}
						if($productRow["swf"] != "")
						{	
							$swfPath	= SITE_URL."/modules/product/images/swf_".$product_id.".swf"; 	
						}
						else
						{	
							$swfPath	="";	
						}
						$p_xml .="<node label=\"".$productRow["name"]."\" id=\"".$productRow["id"]."\" price=\"".$productRow["price"]."\"  description=\"".$productRow["description"]."\" swf_path=\"".$swfPath."\"  image_path=\"".
					$photoPath."\"  node_type=\"p\"/> \r\n";
				}
			}
			
		}
		
			$acceQry	=	"SELECT * FROM ".THRID_TABLE." where   category_id ='$catId' ";
			//$acceQry	=	"SELECT a.* FROM product_accessories a,category_accessory b WHERE  a.id=b.accessory_id AND b.category_id ='$catId' ";
			$acceRes	=	mysql_query($acceQry);
			while($acceRow=mysql_fetch_array($acceRes))
			{
				if($acceRow["image_extension"] != "")
				{	$photoPath	= SITE_URL."/modules/product/images/accessory/".$acceRow["id"].$acceRow["image_extension"]; 	}
				else
				{	$photoPath	=	"";	}
				
				$p_xml .="<node label=\"".$acceRow["name"]."\" id=\"".$acceRow["id"]."\" price=\"".$acceRow["adjust_price"]."\" type=\"".$acceRow["type"]."\" description=\"".$acceRow["description"]."\"  image_path=\"".
			$photoPath."\"  node_type=\"a\"/> \r\n";
			}
			
		$p_xml .="</xml>\r\n";
		return $p_xml;
	}
	//For  Getting Accessory
	function accessory($catId,$uId='')
	{	
		//$photoPath		= SITE_URL."/modules/product/images/";		
		header("Content-Type:text/xml");
		$p_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$p_xml .="<xml>\r\n";
			
				list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a');
				list($qry_cs)=$this->getCustomQry('product_accessories',"user_id","$uId","=",'a','d');	
				$acceQry	=	"SELECT a.*,$qry1 FROM product_accessories a $join_qry,category_accessory b  WHERE  a.id=b.accessory_id";
				if($catId){
					$acceQry.= " AND b.category_id ='$catId'";
				}
				if($uId!=''){
					$acceQry.=" AND ".$qry_cs;
				}
			//$acceQry	=	"SELECT * FROM ".THRID_TABLE." where   category_id ='$catId' ";
			$acceRes	=	mysql_query($acceQry);
			while($acceRow=mysql_fetch_array($acceRes)){			
					if($acceRow["image_extension"] != "")
						{	$photoPath	= SITE_URL."/modules/product/images/accessory/".$acceRow["id"].".".$acceRow["image_extension"]; 	}
						else
						{	$photoPath	=	"";	}
					
						$p_xml .="<node label=\"".$acceRow["name"]."\" id=\"".$acceRow["id"]."\" price=\"".$acceRow["adjust_price"]."\" type=\"".$acceRow["type"]."\" description=\"".$acceRow["description"]."\"  image_path=\"".
					$photoPath."\"  node_type=\"a\"/> \r\n";
			}
			
		$p_xml .="</xml>\r\n";		
		return $p_xml;
	}
	
	//For  Getting Myfolder Images
	/*function myFolderArt($uId)
	{	
		header("Content-Type:text/xml");
		$p_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$p_xml .="<xml>\r\n";
		
			$myFoldQry	=	"SELECT * FROM art_editor_image   WHERE  user_id=$uId";
			$myFoldRes	=	mysql_query($myFoldQry);
			while($myFoldRow=mysql_fetch_array($myFoldRes)){			
					if($myFoldRow["new_img_name"] != "")
						{	$photoPath	= SITE_URL."/modules/editor/uploadImages/".$myFoldRow['new_img_name']; 	}
						else
						{	$photoPath	=	"";	}
					
						$p_xml .="<node label=\"".$myFoldRow["name"]."\" id=\"".$myFoldRow["id"]."\" price=\"".$myFoldRow["adjust_price"]."\" type=\"".$myFoldRow["type"]."\" description=\"".$myFoldRow["description"]."\"  image_path=\"".
					$photoPath."\"  node_type=\"a\"/> \r\n";
			}
			
		$p_xml .="</xml>\r\n";		
		return $p_xml;
	}*/
	function myFolderArt($uId)
	{	
		header("Content-Type:text/xml");
		$p_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$p_xml .="<xml>\r\n";
			$myFoldQry	=	"SELECT * FROM  product_saved_work WHERE   user_id=$uId AND product_type='A'";
			$myFoldRes	=	mysql_query($myFoldQry);
			while($myFoldRow=mysql_fetch_array($myFoldRes)){			
					
						$photoPath	= SITE_URL."/modules/product/images/saved_work/".$myFoldRow['id'].".jpg"; 	
						
					
						$p_xml .="<node label=\"".$myFoldRow["name"]."\" id=\"".$myFoldRow["id"]."\" price=\"".$myFoldRow["product_price"]."\" type=\"".$myFoldRow["product_type"]."\" description=\"".$myFoldRow["description"]."\"  image_path=\"".
					$photoPath."\"  node_type=\"a\"/> \r\n";
			}
			
		$p_xml .="</xml>\r\n";		
		return $p_xml;
	}
	
	# return xml formatted subcategory list of a category
	function childGet($catId,$type)
	{
	//"SELECT * FROM ".FIRST_TABLE." INNER JOIN (".FIFTH_TABLE." INNER JOIN".SIXTH_TABLE." ON ".FIFTH_TABLE.".module_id =".SIXTH_TABLE.".id AND".SIXTH_TABLE.".folder = 'accessory') ON ".FIFTH_TABLE.".category_id = ".FIRST_TABLE.".category_id"
		//$childQry	=	"SELECT * FROM ".FIRST_TABLE." where active='Y' AND show_in_editor='Y' AND parent_id='$catId' ORDER BY category_name ASC ";
		$childQry	=	"SELECT * FROM ".FIRST_TABLE." where active='Y'  AND parent_id='$catId' ORDER BY category_name ASC ";
		//$childQry	=	"SELECT * FROM ".FIRST_TABLE." INNER JOIN (".FIFTH_TABLE." INNER JOIN".SIXTH_TABLE." ON ".FIFTH_TABLE.".module_id =".SIXTH_TABLE.".id AND".SIXTH_TABLE.".folder = 'accessory') ON ".FIFTH_TABLE.".category_id = ".FIRST_TABLE.".category_id AND active='Y' AND parent_id='$catId' ORDER BY category_name ASC ";
		//print_r($childQry);exit;
		$childRes	=	mysql_query($childQry);
		while($childRow=mysql_fetch_array($childRes))
		{ 		
			if($childRow["category_image"] != "")
			{	$imagePath1	= SITE_URL."/modules/category/images/".$childRow[FIRST_TABLE_FIELD5].".".$childRow["category_image"]; 	}
			else
			{	$imagePath1	="";
	
			}

			if($childRow["category_type"]!="")
			{ $type	=	$childRow["category_type"]; }
			$xml .="<node label=\"".htmlspecialchars($childRow[FIRST_TABLE_FIELD1])."\" cid=\"".$childRow[FIRST_TABLE_FIELD5]."\" description=\"".htmlspecialchars($childRow[FIRST_TABLE_FIELD2])."\" image_path=\"".
			$imagePath1."\" category_type=\"".$type."\" node_type=\"c\"> \r\n";
			
			$childCount	=	$this->childCount($childRow[FIRST_TABLE_FIELD5]);
			if($childCount!=0)
			{
				$xml .=$this->childGet($childRow[FIRST_TABLE_FIELD5],$type);
			}
			$xml .="</node>\r\n";
		}

		return $xml;
	}
	# return xml formatted category and its products
	### Last modified on 26/09/07....by Jipson Thomas......
	function getClipart()
	{
		//$qry	=	"SELECT * FROM ".FIRST_TABLE." WHERE active='Y' AND parent_id='0' AND is_private='N' ORDER BY category_name ASC ";	
		$qry	=	"SELECT * FROM ".FIRST_TABLE." INNER JOIN (".FIFTH_TABLE." INNER JOIN ".SIXTH_TABLE." ON ".FIFTH_TABLE.".module_id =".SIXTH_TABLE.".id AND ".SIXTH_TABLE.".folder = 'accessory') ON ".FIFTH_TABLE.".category_id = ".FIRST_TABLE.".category_id AND ".FIRST_TABLE.".active='Y' AND ".FIRST_TABLE.".parent_id='0' ORDER BY ".FIRST_TABLE.".category_name ASC ";
	//print_r($qry);exit;
		$res	=	mysql_query($qry);
		header("Content-Type:text/xml");
		$_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .="<xml>\r\n";					
		while($row=mysql_fetch_array($res))
		{
			if($row["category_image"] != "")
			{	$imagePath	= SITE_URL."/modules/category/images/".$row[FIRST_TABLE_FIELD5].".".$row["category_image"]; 	}
			else
			{	$imagePath="";
			
			}
			$type	=	$row["category_type"];
			$_xml .="<node label=\"".htmlspecialchars($row[FIRST_TABLE_FIELD1])."\" cid=\"".htmlspecialchars($row[FIRST_TABLE_FIELD5])."\" description=\"".$row[FIRST_TABLE_FIELD2]."\"  image_path=\"".
			$imagePath."\" category_type=\"".$type."\" node_type=\"c\"> \r\n";
			$childCount	=	$this->childCount($row["category_id"]);
			if($childCount!=0)
			{
				$xml=$this->childGet($row["category_id"],$type);
				$_xml=$_xml.$xml;
			}
			$_xml .="</node>\r\n";
		}
		$_xml .="</xml>";
		return $_xml;
	}
	
	
	/**
  	 * This function is used for certain category and its products
  	 * Author   : Vipin
  	 * Created  : 08/Nov/2007
  	 * Modified : 08/Nov/2007 By Vipin
  	 */
	 function getClipartcat()
	{
		//$qry	=	"SELECT * FROM ".FIRST_TABLE." WHERE active='Y' AND parent_id='0' AND is_private='N' ORDER BY category_name ASC ";	
		$qry	=	"SELECT * FROM ".FIRST_TABLE." INNER JOIN (".FIFTH_TABLE." INNER JOIN ".SIXTH_TABLE." ON ".FIFTH_TABLE.".module_id =".SIXTH_TABLE.".id AND ".SIXTH_TABLE.".folder = 'accessory') ON ".FIFTH_TABLE.".category_id = ".FIRST_TABLE.".category_id AND ".FIRST_TABLE.".active='Y' AND ".FIRST_TABLE.".parent_id='0' ORDER BY ".FIRST_TABLE.".display_order ASC ";
		//print_r($qry);exit;
		$res	=	mysql_query($qry);
		
		header("Content-Type:text/xml");
		$_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .="<xml>\r\n";					
		while($row=mysql_fetch_array($res))
		{
			if (htmlspecialchars($row[FIRST_TABLE_FIELD1])=='Fine Art Collections' or htmlspecialchars($row[FIRST_TABLE_FIELD1])=='My Folder' or htmlspecialchars($row[FIRST_TABLE_FIELD1])=='Upload Art File'){
			if($row["category_image"] != "")
			{	$imagePath	= SITE_URL."/modules/category/images/".$row[FIRST_TABLE_FIELD5].".".$row["category_image"]; 	}
			else
			{	$imagePath	="";	}
			$type	=	$row["category_type"];
			$_xml .="<node label=\"".htmlspecialchars($row[FIRST_TABLE_FIELD1])."\" cid=\"".htmlspecialchars($row[FIRST_TABLE_FIELD5])."\" description=\"".$row[FIRST_TABLE_FIELD2]."\"  image_path=\"".
			$imagePath."\" category_type=\"".$type."\" node_type=\"c\"> \r\n";
			$childCount	=	$this->childCount($row["category_id"]);
			if($childCount!=0)
			{
				$xml=$this->childGet($row["category_id"],$type);
				$_xml=$_xml.$xml;
			}
			$_xml .="</node>\r\n";
			}//if
		}
		$_xml .="</xml>";
		return $_xml;
	}
	
	//end of getclipartcat function
	 
	
	//This function used for drawing think lines
		function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
   /* this way it works well only for orthogonal lines
   imagesetthickness($image, $thick);
   return imageline($image, $x1, $y1, $x2, $y2, $color);
   */
   if ($thick == 1) {
       return imageline($image, $x1, $y1, $x2, $y2, $color);
   }
   $t = $thick / 2 - 0.5;
   if ($x1 == $x2 || $y1 == $y2) {
       return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
   }
   $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
   $a = $t / sqrt(1 + pow($k, 2));
   $points = array(
       round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
       round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
       round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
       round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
   );
   imagefilledpolygon($image, $points, 4, $color);
   return imagepolygon($image, $points, 4, $color);
}

//This function used to get font
function getMyFont($font){
	switch($font){
	case "Arial":
		$font = 'font/arial.ttf';
	break;
	case "Monotype Corsiva":
		$font="font/mtcorsva.ttf";
	break;
	case "Trebuchet MS":
		$font="font/trebuc.ttf";
	break;
	case "Haettenschweiler":
		$font="font/hatten.ttf";
	break;
	case "Times New Roman":
		$font="font/hatten.ttf";
	break;
	}
	return $font;
	
	}
	// This will return color code
	function getColor($image,$code)
	{
			$hex=$code;
			$red = hexdec(substr($hex,0,2)); 
			$green = hexdec(substr($hex,2,2)); 
			$blue = hexdec(substr($hex,4,2)); 
			$color= ImageColorAllocate($image,$red,$green,$blue);
			return $color;
	}
	
	// This function used for creating rounded rectangle
	function ImageRectangleWithRoundedCorners($im, $x1, $y1, $x2, $y2, $radius, $color)
		{
		   // Draw rectangle without corners
		 ImageFilledRectangle($im, $x1+$radius, $y1, $x2-$radius, $y2, $color);
		 ImageFilledRectangle($im, $x1, $y1+$radius, $x2, $y2-$radius, $color);
		   // Draw circled corners
		   ImageFilledEllipse($im, $x1+$radius, $y1+$radius, $radius*2, $radius*2, $color);
		   ImageFilledEllipse($im, $x2-$radius, $y1+$radius, $radius*2, $radius*2, $color);
		   ImageFilledEllipse($im, $x1+$radius, $y2-$radius, $radius*2, $radius*2, $color);
		   ImageFilledEllipse($im, $x2-$radius, $y2-$radius, $radius*2, $radius*2, $color);
		}
		
//This is used for streching image

function StrechImage($nw, $nh, $source, $stype, $dest) 
{
 
    $size = getimagesize($source);
    $w = $size[0];
    $h = $size[1];
 
    switch($stype) {
        case 'gif':
        $simg = imagecreatefromgif($source);
        break;
        case 'jpg':
        $simg = imagecreatefromjpeg($source);
        break;
        case 'png':
        $simg = imagecreatefrompng($source);
        break;
    }
 
    $dimg = imagecreatetruecolor($nw, $nh);
 
    $wm = $w/$nw;
    $hm = $h/$nh;
 
    $h_height = $nh/2;
    $w_height = $nw/2;
 
    if($w> $h) {
 
        $adjusted_width = $w / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;
 
        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
 
    } elseif(($w <$h) || ($w == $h)) {
 
        $adjusted_height = $h / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;
 
        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
 
    } else {
        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
    }
 
    imagejpeg($dimg,$dest,100);
} 

//This function used for croping an image

function  CropImage($sourse,$dst,$x1,$y1,$x2,$y2)
 {
	$file = $sourse;
	$image_file = $file;
	$src_img = imagecreatefromjpeg($image_file);
	$dst_img = imagecreatetruecolor($x2,$y2);	//	GD 2.0!!!
	//$dst_img = ImageCreate($x2,$y2) or die("<h1>Cannot create image</h1>");
	imagecopy ($dst_img, $src_img, 0, 0, $x1, $y1, $x2, $y2);
	 //$dimg = imagecreatetruecolor(100, 100);
	imagejpeg($dst_img,$dst,100);
}

//This function will return the file extention

function findType($string)
{
	$sringArray=explode(".",$string);
	$cnt=count($sringArray);
	return $sringArray[$cnt-1];
}
function GetSides($proId)
{
	$qry	=	"SELECT dual_side from products where id=$proId ";
	$rs		=	 $this->db->get_row($qry, ARRAY_A);
	$side	=	$rs['dual_side'];
	if($side=="Y")
	{ $dualside="1";}
	else
	{ $dualside="0";}
	return $dualside;
}

function moduleID()
	{
		$Qry	=	"SELECT id FROM module where  folder='product' ";
		$rs = $this->db->get_row($Qry, ARRAY_A);
		$module_id		=	$rs['id'];		
		return $module_id;
	}
function accessoryImagePathxml($catId,$uId='')
	{	
		//$photoPath		= SITE_URL."/modules/product/images/";		
		header("Content-Type:text/xml");
		$p_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$p_xml .="<xml>\r\n";
				$acceQry	=	"SELECT T1.*,T2.* FROM product_accessories T1 LEFT JOIN category_accessory T2 ON(T1.id=T2.accessory_id)  WHERE  T2.category_id=$catId";
				$acceRes	=	mysql_query($acceQry);
			if(mysql_num_rows($acceRes)>0){
			while($acceRow=mysql_fetch_array($acceRes)){			
					if($acceRow["image_extension"] != "")
						{	$photoPath	= SITE_URL."/modules/product/images/accessory/".$acceRow["id"].".".$acceRow["image_extension"]; 	}
						else
						{	$photoPath	=	"";	}
					
						$p_xml .="<node label=\"".$acceRow["name"]."\" id=\"".$acceRow["id"]."\" price=\"".$acceRow["adjust_price"]."\" type=\"".$acceRow["type"]."\" description=\"".$acceRow["description"]."\"  image_path=\"".
						$photoPath."\"  node_type=\"a\"/> \r\n";
			}
			}else{
			    $productQry	=	"SELECT T1.*,T2.*,T2.product_id AS prod_id FROM products T1 LEFT JOIN category_product T2 ON(T1.id=T2.product_id)  WHERE  T2.category_id=$catId";
				$productRow	=	mysql_query($productQry);
				for($i=0;$i<mysql_num_rows($productRow);$i++){
					$product_id=mysql_result($productRow,$i,'prod_id');
					if(mysql_result($productRow,$i,'image_extension')!= "")
						{	
							$photoPath	= SITE_URL."/modules/product/images/".$product_id.".".mysql_result($productRow,$i,'image_extension'); 	
						}
						else
						{	
							$photoPath	="";	
						}
						if(mysql_result($productRow,$i,'swf')!= "")
						{	
							$swfPath	= SITE_URL."/modules/product/images/swf_".$product_id.".swf"; 	
						}
						else
						{	
							$swfPath	="";	
						}
						$p_xml .="<node label=\"".mysql_result($productRow,$i,'name')."\" id=\"".mysql_result($productRow,$i,'id')."\" price=\"".mysql_result($productRow,$i,'price')."\"  swf_path=\"".$swfPath."\"  image_path=\"".$photoPath."\"  node_type=\"p\"/> \r\n";
					
				}
						
			
			}
			
		$p_xml .="</xml>\r\n";		
		return $p_xml;
	}
}
?>