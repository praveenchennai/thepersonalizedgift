<?php
//header("Content-type: image/jpeg");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");

$ajax_editor = new Ajax_Editor();
$objAccessory = new Accessory();

$acc_det = $objAccessory->GetAccessory($_REQUEST["art_image"]);


$first_name  = ucfirst($_REQUEST["first_name"]);

$first_name2 = ucfirst($_REQUEST["first_name2"]);

$gift_type  = $_REQUEST["gift_type"];

$poem_id  = $_REQUEST["poem_id"];
if ($poem_id)
{
	$rs = $objAccessory->getAccessoryBySearch("id","$poem_id");
	$first_name = $rs[0]->name;
}
/*$image1 = imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/mat_1.gif");
//$im_w1  = imagecreate
$im_w1  = imagecreate(100,100);
imagecolorallocatealpha($im_w1,255,255,255,127);
imagecopyresampled($im_w1,$image1,0,0,0,0,100,100,imagesx($image1),imagesy($image1));
$image1 = $im_w1 ;
//imagecop
imagegif($image1);
exit;*/


($_REQUEST["lang"])? $lang = $_REQUEST["lang"] : $lang=1;
($_REQUEST["gender"])? $gender = $_REQUEST["gender"] : $gender='M';
($_REQUEST["gender2"])? $gender2 = $_REQUEST["gender2"] : $gender2='M';	
	

	$art_image = $_REQUEST["art_image"].".".$_REQUEST["extension"];
	$extension = strtolower($_REQUEST["extension"]);
	$image_name = $_REQUEST["image_name"];
	
	if ($art_image)
	{
		if (($extension=="jpeg") || ($extension=="jpg"))
		{
			$image1 = imagecreatefromjpeg(SITE_PATH."/modules/product/images/accessory/$art_image");
		}
		elseif (($extension=="png"))
		{
			$image1 = imagecreatefrompng(SITE_PATH."/modules/product/images/accessory/$art_image");
		}
		elseif (($extension=="gif"))
		{
			$image1 = imagecreatefromgif(SITE_PATH."/modules/product/images/accessory/$art_image");
		}
	}
	else 
	{
		$image1 = imagecreatefromjpeg(SITE_PATH."/modules/ajax_editor/images/beach.jpg");
	}	
	//$im_w1  = imagecreatetruecolor(792,612);
	//imagecopyresampled($im_w1,$image1,0,0,0,0,792,612,imagesx($image1),imagesy($image1));
	$im_w1  = imagecreatetruecolor(1056,816);
	imagecopyresampled($im_w1,$image1,0,0,0,0,1056,816,imagesx($image1),imagesy($image1));
	$image1 = $im_w1 ;
	
	$xval  = ($acc_det["x"])/ 15;
	$y_val = ($acc_det["y"])/15;
	$y     = $y_val;
	
	
	if (($first_name!="")|| ($first_name2!=""))
	{
	
		
		if ($first_name2!="")
		{
			$font_type = 2;
			$xval = $xval-($xval/4);
			$x_val = $x_val - 50;
			$new_x1 = $ajax_editor->checkTextLength($first_name,1,$font_type);
			$new_x2 = $ajax_editor->checkTextLength($first_name2,1,$font_type);
			//$xval = $xval - ($new_x1+50);
			
			$max = 2;
			$font_col  = $ajax_editor->getFontType("small");
		}
		else 
		{
			$font_type = 3;
			$max = 1;
			$font_col = $ajax_editor->getFontType("medium");
		}
		
		for($j=1;$j<=$max;$j++)
		{
			if ($j>1)
			{
				$xval = $xval + 50;
				$y = $y_val;
				$fname = $first_name2;
				$gender = $gender2;
			}
			else 
			{
				$fname = $first_name;
			}
			
			if ($gift_type=="poem")
			{
							//Printing First Name
			list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$fname,2,1,$y,$xval,$font_type);

				
				$p_txt = explode("\n",$rs[0]->poem);
				
				for ($i=0;$i<sizeof($p_txt);$i++)
				{
					//($i==1) ? $space =20 : $space=10;
					$y = $y + ($text_size[1]-$text_size[7]);
					$text = $p_txt[$i];
					list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,3,$y,$xval,$font_type);
				}
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,4,$y,$xval,$font_type);
				
			}
			else 
			{	
							//Printing First Name
			list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$fname,1,0,$y,$xval,$font_type);

			//Printing Origin Label
				if ($j==1)
				{
					$space1 =40;
				}
				$y = $y + $space1;
				if ($lang==1)
				{
					$text = "Origin";
				}
				else 
				{
					$text = "Origen";
				}
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,1,$y,$xval,$font_type);
				
				//Printing Origin from Database
				if ($j==1)
				{
					$space2 =($text_size[1]-$text_size[7])+10;
				}
				$y = $y + $space2;
				$rs=$ajax_editor->getMeaning($fname,$gender);
				if ($lang==1)
				{
					$text = $rs->Origin;
				}
				else 
				{
					$text = $rs->SpanishOrigin;
				}
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,2,$y,$xval,$font_type);
				
				//Printing Meaning Label
				if ($j==1)
				{
					$space3 =  ($text_size[1]-$text_size[7]);
				}
				
				$y = $y + $space3;
				if ($lang==1)
				{
					$text = "Meaning";
				}
				else 
				{
					$text = "Significado";
				}
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,1,$y,$xval,$font_type);
				
				//Printing Meaning from Database
				if ($j==1)
				{
					$space4 = ($text_size[1]-$text_size[7])+5;
				}
				$y = $y + $space4 ;
				if ($lang==1)
				{
					$text = "'".$rs->Meaning."'";
				}
				else 
				{
					$text = "'".$rs->SpanishMeaning."'";
				}
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,2,$y,$xval,$font_type);
				
				//Printing a Line
				if ($j==1)
				{
					$space5 = ($text_size[1]-$text_size[7]);
				}
				$y = $y + $space5;
				$text = "2";
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,3,3,$y,$xval,$font_type);
				
				$y +=10;
				//Printing 8 lines from Database
				for ($i=1;$i<=8;$i++)
				{
					//($i==1) ? $space =20 : $space=10;
					$y = $y + ($text_size[1]-$text_size[7]);
					$text = $ajax_editor->getLineText($i,$rs->{Line.$i},$gender,$lang);
					$text = str_replace("%NAME%",$first_name,$text); //Replcaing Name variable with the First Name
					list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,4,$y,$xval,$font_type);
				}
				
				
				//Printing a line
				$y = $y + ($text_size[1]-$text_size[7]);
				$text = "2";
				list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,3,5,$y,$xval,$font_type);
			
		}
		
		
		
		$y     = $y_val - (($y-$y_val)/2);
		
		
		
		$type_arr    = array();
		$type_arr[0] = "tiny";
		$type_arr[1] = "very_small";
		$type_arr[2] = "small";
		$type_arr[3] = "medium";
		$type_arr[4] = "large";
				
		if ($first_name2!="")
		{
			//$font_type = 0;
			$font_col  = $ajax_editor->getFontType($type_arr[$font_type]);
			$max = 2;
			$font_head  = $font_col[0];
			$font_label = $font_col[1];
			$font_label_text = $font_col[2];
			$font_line1 = $font_col[3];
			$font_text  = $font_col[4];
			$font_line2 = $font_col[5];
			
		}
		else 
		{
			//$font_type = 3;
			$font_col = $ajax_editor->getFontType($type_arr[$font_type]);
			$max = 1;
			$font_head  = $font_col[0];
			$font_label = $font_col[1];
			$font_label_text = $font_col[2];
			$font_line1 = $font_col[3];
			$font_text  = $font_col[4];
			$font_line2 = $font_col[5];
		}
		
		
			if ($j>1)
			{
				$xval = $xval + 350;
				//$y = $y_val;
				$first_name = $first_name2;
			}
			//Printing First Name
			
			
			if ($gift_type=="poem")
			{
				list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$first_name,2,$font_head,$y,$xval);
				
				if ($j==1)
				{
					$space2 =($text_size[1]-$text_size[7]);
				}
				//$y = $y + $space2;
				
				$p_txt = explode("\n",$rs[0]->poem);
				for ($i=0;$i<sizeof($p_txt);$i++)
				{
					//($i==1) ? $space =20 : $space=10;
					$y = $y + ($text_size[1]-$text_size[7]);
					$text = $p_txt[$i];
					list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label_text,$y,$xval);
				}
			}
			else 
			{
				list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$first_name,1,$font_head,$y,$xval);
			
				//Printing Origin Label
				if ($j==1)
				{
					//$space1 =($text_size[1]-$text_size[7])-20;
					$space1 =40;
				}
				$y = $y + $space1;
				if ($lang==1)
				{
					$text = "Origin";
				}
				else 
				{
					$text = "Origen";
				}
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label,$y,$xval);
				
				//Printing Origin from Database
				if ($j==1)
				{
					$space2 =($text_size[1]-$text_size[7])+10;
				}
				$y = $y + $space2;
				$rs=$ajax_editor->getMeaning($first_name,$gender);
				if ($lang==1)
				{
					$text = $rs->Origin;
				}
				else 
				{
					$text = $rs->SpanishOrigin;
				}
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label_text,$y,$xval);
				
				//Printing Meaning Label
				if ($j==1)
				{
					$space3 =  ($text_size[1]-$text_size[7]);
				}
				
				$y = $y + $space3;
				if ($lang==1)
				{
					$text = "Meaning";
				}
				else 
				{
					$text = "Significado";
				}
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label,$y,$xval);
				
				//Printing Meaning from Database
				if ($j==1)
				{
					$space4 = ($text_size[1]-$text_size[7])+5;
				}
				$y = $y + $space4 ;
				if ($lang==1)
				{
					$text = "'".$rs->Meaning."'";
				}
				else 
				{
					$text = "'".$rs->SpanishMeaning."'";
				}
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label_text,$y,$xval);
				
				//Printing a Line
				if ($j==1)
				{
					$space5 = ($text_size[1]-$text_size[7]);
				}
				$y = $y + $space5;
				$text = "2";
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,3,$font_line1,$y,$xval);
				
				$y +=10;
				//Printing 8 lines from Database
				for ($i=1;$i<=8;$i++)
				{
					//($i==1) ? $space =20 : $space=10;
					$y = $y + ($text_size[1]-$text_size[7]);
					$text = $ajax_editor->getLineText($i,$rs->{Line.$i},$gender,$lang);
					$text = str_replace("%NAME%",$first_name,$text); //Replcaing Name variable with the First Name
					list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);
				}
				
				
				//Printing a line
				$y = $y + ($text_size[1]-$text_size[7]);
				$text = "2";
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,3,$font_line2,$y,$xval);
			}	
		}
	}
	$im_w1  = imagecreatetruecolor(504,392);
	imagecopyresampled($im_w1,$image1,0,0,0,0,504,392,imagesx($image1),imagesy($image1));
	$image1 = $im_w1 ;
	
if ($_REQUEST["mat_id"]!="")
{			
	/*if ($_REQUEST["mat"]!="true")
	{
		$image2 = imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/mat_tr.gif");
	}
	else 
	{
		$image2 = imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/mat_1.gif");
		/*$im_w1= imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/beachmat-tr.gif");
		imagecopyresampled($im_w1,$image2,0,0,0,0,915,720,imagesx($image2),imagesy($image2));
		$image2 = $im_w1;*/
	//}
		
	//$image2 = imagecreatefrompng(SITE_PATH."/modules/ajax_editor/images/mat1.png");
	//$image3 = imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/frame1.gif");
	$mat_image = $_REQUEST["mat_id"];
	$image2 = imagecreatefromgif(SITE_PATH."/modules/product/images/accessory/$mat_image");
	if ($_REQUEST["frame"]=="true")
	{
		$im= $image3;
		$x1 = 120;
		$y1 = 109;
		$x2 = 60;
		$y2 = 60;
	}
	else 
	{
		$im = $image2;
		//$x1 = 70;
		//$y1 = 60;
		$x1 = (imagesx($im) - imagesx($image1))/2-3;
		$y1 = (imagesy($im) - imagesy($image1))/2-5;
		$x2 = 0;
		$y2 = 0;
	}
	$image  = imagecreatetruecolor(imagesx($im),imagesy($im));
	//$image  = imagecreatetruecolor(915,720);
	imagecolorallocate($image,255,255,255);
	imagecopy($image,$image1,$x1,$y1,0,0,imagesx($image),imagesy($image));
	
	
	//$image2 = $image_2;
	imagecopy($image,$image2,$x2,$y2,0,0,imagesx($image),imagesy($image));
	
	//if ($_REQUEST["frame"]=="true")
	//{
		
		//imagecopy($image,$image3,0,0,0,0,imagesx($image),imagesy($image));
		//imagecopy($image,$image3,0,0,0,0,300,200);
	//}	

}
else 
{
	$image = $image1;
}
	//imagejpeg($image,SITE_PATH."/modules/ajax_editor/images/test123.jpg",100);
	if ( (imagesx($image)>648) || (imagesy($image)>521))
	{
		$dest_image = imagecreatetruecolor(648,521);
		imagecopyresampled($dest_image,$image,0,0,0,0,648,521,imagesx($image),imagesy($image));
	}
	else 
	{
		$dest_image = $image;
	}
	imagejpeg($dest_image,SITE_PATH."/modules/ajax_editor/images/$image_name.jpg",100);
	imagejpeg($dest_image);
	
	imagedestroy($dest_image);
exit;
?>