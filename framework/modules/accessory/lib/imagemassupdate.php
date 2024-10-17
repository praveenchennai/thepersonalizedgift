<?
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");


$qry	=	"select * from product_accessories where image_extension!=''";
$rs	=	mysql_query($qry);
while($row=mysql_fetch_array($rs))
{
	$id	=	$row["id"];
	
	# start converting images in the product/images/accessory
	$dir			=	SITE_PATH."/modules/product/images/accessory/".$id.".".$row["image_extension"];
	if(file_exists($dir))
	{
	$SourceImage=$dir;
	$SourceExtension=$row["image_extension"];
	$DestinationPath=SITE_PATH."/modules/product/images/accessory/".$id.".gif";
	$DestinationExtension="gif";
	
		list($width, $height, $type, $attr) = getimagesize($SourceImage);
	
		if($SourceExtension == 'PNG' || $SourceExtension == 'png') 
			$tmp			=	imagecreatefrompng($SourceImage);
		if($SourceExtension == 'jpg' || $SourceExtension == 'jpeg' || $SourceExtension == 'JPG' || $SourceExtension == 'JPEG') 
			$tmp			=	imagecreatefromjpeg($SourceImage);	
		if($SourceExtension == 'GIF' || $SourceExtension == 'gif') 
			$tmp			=	imagecreatefromgif($SourceImage);	
		
		$dest_image		=	imagecreatetruecolor($width,$height);
		imagecopyresampled($dest_image, $tmp,0,0,0,0,$width, $height,imagesx($tmp), imagesy($tmp));	
	
		
		if($DestinationExtension == 'PNG' || $DestinationExtension == 'png') 
			imagepng($dest_image, $DestinationPath);
		if($DestinationExtension == 'jpg' || $DestinationExtension == 'jpeg' || $DestinationExtension == 'JPG' || $DestinationExtension == 'JPEG') 
			imagejpeg($dest_image, $DestinationPath);	
		if($DestinationExtension == 'GIF' || $DestinationExtension == 'gif') 
			imagegif($dest_image, $DestinationPath);	
			
			
			$qry2	=	"update product_accessories set image_extension='gif' where id='$id'";
			$res2	=	mysql_query($qry2);
		}
	# end converting images in the product/images/accessory
	
	
	
	# start converting images in the product/images/accessory/thumb description img
	$dir2			=	SITE_PATH."/modules/product/images/accessory/thumb/".$id."_des_.".$row["image_extension"];
	if(file_exists($dir2))
	{
	$SourceImage2=$dir2;
	$SourceExtension2=$row["image_extension"];
	$DestinationPath2=SITE_PATH."/modules/product/images/accessory/thumb/".$id."_des_.gif";
	$DestinationExtension2="gif";
	
		list($width2, $height2, $type2, $attr2) = getimagesize($SourceImage2);
	
		if($SourceExtension2 == 'PNG' || $SourceExtension2 == 'png') 
			$tmp2			=	imagecreatefrompng($SourceImage2);
		if($SourceExtension2 == 'jpg' || $SourceExtension2 == 'jpeg' || $SourceExtension2 == 'JPG' || $SourceExtension2 == 'JPEG') 
			$tmp2			=	imagecreatefromjpeg($SourceImage2);	
		if($SourceExtension2 == 'GIF' || $SourceExtension2 == 'gif') 
			$tmp2			=	imagecreatefromgif($SourceImage2);	
		
		$dest_image2		=	imagecreatetruecolor($width2,$height2);
		imagecopyresampled($dest_image2, $tmp2,0,0,0,0,$width2, $height2,imagesx($tmp2), imagesy($tmp2));	
	
		
		if($DestinationExtension2 == 'PNG' || $DestinationExtension2 == 'png') 
			imagepng($dest_image2, $DestinationPath2);
		if($DestinationExtension2 == 'jpg' || $DestinationExtension2 == 'jpeg' || $DestinationExtension2 == 'JPG' || $DestinationExtension2 == 'JPEG') 
			imagejpeg($dest_image2, $DestinationPath2);	
		if($DestinationExtension2 == 'GIF' || $DestinationExtension2 == 'gif') 
			imagegif($dest_image2, $DestinationPath2);	
	}
			
	# end converting images in the product/images/accessory/thumb list img
	
	# start converting images in the product/images/accessory/thumb description img
	$dir3			=	SITE_PATH."/modules/product/images/accessory/thumb/".$id."_List_.".$row["image_extension"];
	if(file_exists($dir3))
	{
	$SourceImage3=$dir3;
	$SourceExtension3=$row["image_extension"];
	$DestinationPath3=SITE_PATH."/modules/product/images/accessory/thumb/".$id."_List_.gif";
	$DestinationExtension3="gif";
	
		list($width3, $height3, $type3, $attr3) = getimagesize($SourceImage3);
	
		if($SourceExtension3 == 'PNG' || $SourceExtension3 == 'png') 
			$tmp3			=	imagecreatefrompng($SourceImage3);
		if($SourceExtension3 == 'jpg' || $SourceExtension3 == 'jpeg' || $SourceExtension3 == 'JPG' || $SourceExtension3 == 'JPEG') 
			$tmp3			=	imagecreatefromjpeg($SourceImage3);	
		if($SourceExtension3 == 'GIF' || $SourceExtension3 == 'gif') 
			$tmp3			=	imagecreatefromgif($SourceImage3);	
		
		$dest_image3		=	imagecreatetruecolor($width3,$height3);
		imagecopyresampled($dest_image3, $tmp3,0,0,0,0,$width3, $height3,imagesx($tmp3), imagesy($tmp3));	
	
		
		if($DestinationExtension3 == 'PNG' || $DestinationExtension3 == 'png') 
			imagepng($dest_image3, $DestinationPath3);
		if($DestinationExtension3 == 'jpg' || $DestinationExtension3 == 'jpeg' || $DestinationExtension3 == 'JPG' || $DestinationExtension3 == 'JPEG') 
			imagejpeg($dest_image3, $DestinationPath3);	
		if($DestinationExtension3 == 'GIF' || $DestinationExtension3 == 'gif') 
			imagegif($dest_image3, $DestinationPath3);	
	}
			
	# end converting images in the product/images/accessory/thumb list img
	
		
	# end converting images in the product/images/accessory/thumb image
	$dir4			=	SITE_PATH."/modules/product/images/accessory/thumb/".$id.".".$row["image_extension"];
	if(file_exists($dir4))
	{
	$SourceImage4=$dir4;
	$SourceExtension4=$row["image_extension"];
	$DestinationPath4=SITE_PATH."/modules/product/images/accessory/thumb/".$id.".gif";
	$DestinationExtension4="gif";
	
		list($width4, $height4, $type4, $attr4) = getimagesize($SourceImage4);
	
		if($SourceExtension4 == 'PNG' || $SourceExtension4 == 'png') 
			$tmp4			=	imagecreatefrompng($SourceImage4);
		if($SourceExtension4 == 'jpg' || $SourceExtension4 == 'jpeg' || $SourceExtension4 == 'JPG' || $SourceExtension4 == 'JPEG') 
			$tmp4			=	imagecreatefromjpeg($SourceImage4);	
		if($SourceExtension4 == 'GIF' || $SourceExtension4 == 'gif') 
			$tmp4			=	imagecreatefromgif($SourceImage4);	
		
		$dest_image4		=	imagecreatetruecolor($width4,$height4);
		imagecopyresampled($dest_image4, $tmp4,0,0,0,0,$width4, $height4,imagesx($tmp4), imagesy($tmp4));	
	
		
		if($DestinationExtension4 == 'PNG' || $DestinationExtension4 == 'png') 
			imagepng($dest_image4, $DestinationPath4);
		if($DestinationExtension4 == 'jpg' || $DestinationExtension4 == 'jpeg' || $DestinationExtension4 == 'JPG' || $DestinationExtension4 == 'JPEG') 
			imagejpeg($dest_image4, $DestinationPath4);	
		if($DestinationExtension4 == 'GIF' || $DestinationExtension4 == 'gif') 
			imagegif($dest_image4, $DestinationPath4);	
	}
			
	# end converting images in the product/images/accessory/thumb image
	

}


?>