<?php
//header("Content-type: image/jpeg");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
//include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.rwatermark.php");

$ajax_editor = new Ajax_Editor();
$objAccessory = new Accessory();

$_REQUEST = strip_slashes_recursive($_REQUEST);

$acc_det = $objAccessory->GetAccessory($_REQUEST["art_image"]);

$first_name  = ucfirst($_REQUEST["first_name"]);

$first_name2 = ucfirst($_REQUEST["first_name2"]);

$sentiment	 = ucfirst($_REQUEST['sentiment1']);

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



//code by robin 07-12-2007

/* $_SESSION['First_Name']=$first_name;
$_SESSION['First_Name2']=$first_name2;
$_SESSION['gift_type']=$gift_type;
$_SESSION['poem_id']=$poem_id;
$_SESSION['lang']=$lang;
$_SESSION['gender']=$gender;
$_SESSION['gender2']=$gender2; */

//end robin



$art_image = $_REQUEST["art_image"].".".$_REQUEST["extension"];
$extension = strtolower($_REQUEST["extension"]);
$image_name = $_REQUEST["image_name"];

//code modified by robin
	if($_REQUEST['gift_type']=='tree')
		{
			$im_path=SITE_PATH."/modules/product/images/$art_image";
		}elseif($_REQUEST['gift_type']=='surname')
		{
			$im_path=SITE_PATH."/modules/product/images/surname.jpg";
		}else
		{
			$im_path=SITE_PATH."/modules/product/images/accessory/$art_image";
		}
//end
if ($art_image)
{
	if (($extension=="jpeg") || ($extension=="jpg"))
	{
		$image1 = imagecreatefromjpeg($im_path);
	}
	elseif (($extension=="png"))
	{
		$image1 = imagecreatefrompng($im_path);
	}
	elseif (($extension=="gif"))
	{
		$image1 = imagecreatefromgif($im_path);
	}
}
else
{
	$image1 = imagecreatefromjpeg(SITE_PATH."/modules/ajax_editor/images/beach.jpg");
}
//$im_w1  = imagecreatetruecolor(792,612);
//imagecopyresampled($im_w1,$image1,0,0,0,0,792,612,imagesx($image1),imagesy($image1));
//$im_w1  = imagecreatetruecolor(1056,816);
//imagecopyresampled($im_w1,$image1,0,0,0,0,1056,816,imagesx($image1),imagesy($image1));

//*******new code by robin  28-05-2008 *******
$src_size = getimagesize($im_path);


if(($src_size[0]==503 || $src_size[0]>503))
{
	$create_width=503;
	$create_height=392;
	$frame_width=1056;
	$frame_height=816;
	$_SESSION['port']='NO';
}
elseif($_REQUEST['gift_type']=='tree')
{
	$create_width=392;
	$create_height=503;

	//$create_width=503;
	//$create_height=392;


	$frame_width=816;
	$frame_height=1056;
	$_SESSION['port']='YES';
}
else
{
	//$create_width=503;
	//$create_height=392;
	$create_width=392;
	$create_height=503;
	$frame_width=816;
	$frame_height=1056;
	$_SESSION['port']='YES';
	
}
$im_w1  = imagecreatetruecolor($frame_width,$frame_height);
imagecopyresampled($im_w1,$image1,0,0,0,0,$frame_width,$frame_height,imagesx($image1),imagesy($image1));


//*******end code by robin*******
$image1 = $im_w1 ;
if($_REQUEST['gift_type']=='surname')
{
$xval  = ((7967)/ 15);
$y_val = (5922)/15;
$xval=$xval;
$y     = $y_val+50;
}
else{
$xval  = (($acc_det["x"])/ 15);
$y_val = ($acc_det["y"])/15;
$y     = $y_val;
}
//new adjustments for portrait images 
//27/6/2008 
if(($src_size[0]<503) && $gift_type!="poem" && $first_name2!="")
{
	$xval  = $xval-100;
	
}
// end 27/6/2008 

$sentimentX=$xval;
if ($first_name2)
	
	//$y = $y-155;//
$old_x = $xval;//
//if ()
if($_REQUEST['gift_type']=='surname')
{

}
if (($first_name!="")|| ($first_name2!=""))
{

	
	if ($first_name2!="")
	{
		$font_type = $acc_det['fontsizedoublename'] ? $acc_det['fontsizedoublename'] : 2;//takes the font size from admin side.
		$xval = $xval-($xval/3)+5;// $xval/4 changed to $xval/3
		
		//$x_val = $x_val - 50;
		$new_x1 = $ajax_editor->checkTextLength($first_name,1,$font_type);
		$new_x2 = $ajax_editor->checkTextLength($first_name2,1,$font_type);
		//$xval = $xval - ($new_x1+50);

		$max = 2;
		$font_col  = $ajax_editor->getFontType("small");
		$y = $y-155;
	}
	else
	{
		$y = $y -75;
		$font_type = $acc_det['fontsizesinglename'] ? $acc_det['fontsizesinglename'] : 3;//takes the font size from admin side.
		$max = 1;
		$font_col = $ajax_editor->getFontType("medium");
	}

	for($j=1;$j<=$max;$j++)
	{
		if ($j>1)
		{
			$xval = $xval-10 ;
			$y = $y_val;
			$y = $y-138;//
			$fname = $first_name2;
			$gender = $gender2;
		}
		else
		{
			$fname = $first_name;
			if($max>1)
			{
				$y = $y+75;
			}	
		}
		if ($gift_type=="poem")
		{
			//Printing First Name
			list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$fname,2,1,$y,$xval,$font_type);

			
			$p_txt = explode("\n",$rs[0]->poem);
			$op_cnt = 0; 
			$cl_cnt = 0;
			for ($i=0;$i<sizeof($p_txt);$i++)
			{
				/*for($j=1;$j<8;$j++)
				{
					
					if(strstr(strtolower($text),"<$j>"))
					{
						$poem_font = $rs[0]->{font.$j};
						break;
					}
				}	*/	
				$text = $p_txt[$i];
				for ($t=0;$t<8;$t++)
				{
					if(strstr(strtolower($text),"<$t>"))
					{
						$poem_font = $rs[0]->{font_.$t};
						break;
					}
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				if(strstr(strtolower($text),'<opening'))
				{
					$op_cnt++;
					if ($_REQUEST["op{$op_cnt}"])
					{
						$text = $_REQUEST["op{$op_cnt}"];
					}
					else 
					{
						$text = strstr($text,":");
						$text = str_replace(":","",$text);
						$text = str_replace(">","",$text);
					}
					//list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label,$y,$xval);
					//list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,$font_label_text,$y,$xval,$font_type);
					list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,$poem_font,$y,$xval,$font_type,1);
//					$space2 =($text_size[1]-$text_size[7]);
					
					//$y = $y + ($text_size[1]-$text_size[7]);
	
				}
				elseif ((strstr(strtolower($text),'<closing')))
				{
					$cl_cnt++;
					if ($_REQUEST["cl{$cl_cnt}"])
					{
						$text = $_REQUEST["cl{$cl_cnt}"];
					}
					else 
					{
						$text = strstr($text,":");
						$text = str_replace(":","",$text);
						$text = str_replace(">","",$text);
					}
					if ($cl_cnt==1) $y = $y + 50;
					list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,$poem_font,$y,$xval,$font_type,1);
					$y = $y + ($text_size[1]-$text_size[7]);
				}
				else 
				{
					$y = $y + ($text_size[1]-$text_size[7]);
					
					list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,$poem_font,$y,$xval,$font_type,1);
				}
				
			}
			//$y=$y+500;
			//list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,2,4,$y,$xval,$font_type);

		}
		else
		{
			$y = $y+250;
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
				$space4 = ($text_size[1]-$text_size[7])+15;
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
			$y = $y + $space5+3;
			$text = "2";
			list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,5,3,$y,$xval,$font_type);

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
			list($font_type,$text_size) = $ajax_editor->checkTextSize($image1,$text,5,5,$y,$xval,$font_type);



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
			/*
			* Resizing the font for double name
			*/
			$sentiment_adj_x=0;
			if($sentiment !=''){
				if ($acc_det['resize_dn_senti_yes']!=''){
					$sign	=	substr($acc_det['resize_dn_senti_yes'] ,0,1);
					$percentage = substr($acc_det['resize_dn_senti_yes'] ,1);
					if($sign === '-'){
						
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] - $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] - $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] - $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] - $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] - $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] - $font_line2_p;
						$sentiment_adj_x=$percentage*1.4;
					}
					else{
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] + $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] + $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] + $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] + $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] + $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] + $font_line2_p;
					}
				}
				else{
					$font_head  = $font_col[0];
					$font_label = $font_col[1];
					$font_label_text = $font_col[2];
					$font_line1 = $font_col[3];
					$font_text  = $font_col[4];
					$font_line2 = $font_col[5];
				}
			}
			else{
				if ($acc_det['resize_dn_senti_no']!=''){
					$sign	=	substr($acc_det['resize_dn_senti_no'] ,0,1);
					$percentage = substr($acc_det['resize_dn_senti_no'] ,1);
					if($sign === '-'){
						
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] - $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] - $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] - $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] - $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] - $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] - $font_line2_p;
						//$sentiment_adj_x=$percentage*1.4;
					}
					else{
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] + $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] + $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] + $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] + $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] + $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] + $font_line2_p;
					}
				}
				else{
					$font_head  = $font_col[0];
					$font_label = $font_col[1];
					$font_label_text = $font_col[2];
					$font_line1 = $font_col[3];
					$font_text  = $font_col[4];
					$font_line2 = $font_col[5];
				}
		}

		}
		else//SINGLE NAME;
		{
			//$font_type = 3;
			$font_col = $ajax_editor->getFontType($type_arr[$font_type]);
			$max = 1;
			/*
			* Resizing the font for single name
			*/
			
			if($sentiment !=''){//If sentiment lines are present
				if ($acc_det['resize_sn_senti_yes']!=''){
					$sign		=	substr($acc_det['resize_sn_senti_yes'] ,0,1);
					$percentage =	substr($acc_det['resize_sn_senti_yes'] ,1);
					if($sign === '-'){
						
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] - $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] - $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] - $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] - $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] - $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] - $font_line2_p;
						
					}
					else{
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] + $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] + $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] + $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] + $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] + $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] + $font_line2_p;
					}
				}
				else{
					$font_head  = $font_col[0];
					$font_label = $font_col[1];
					$font_label_text = $font_col[2];
					$font_line1 = $font_col[3];
					$font_text  = $font_col[4];
					$font_line2 = $font_col[5];
				}
			}
			else{//If without sentiments lines.
				if ($acc_det['resize_sn_senti_no']!=''){
					$sign	=	substr($acc_det['resize_sn_senti_no'] ,0,1);
					$percentage = substr($acc_det['resize_sn_senti_no'] ,1);
					if($sign === '-'){
						
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] - $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] - $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] - $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] - $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] - $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] - $font_line2_p;
					}
					else{
						$font_head_p  = ($font_col[0]/100)*$percentage;			$font_head  = $font_col[0] + $font_head_p;
						$font_label_p  = ($font_col[1]/100)*$percentage;		$font_label  = $font_col[1] + $font_label_p;
						$font_label_text_p  = ($font_col[2]/100)*$percentage;	$font_label_text  = $font_col[2] + $font_label_text_p;
						$font_line1_p  = ($font_col[3]/100)*$percentage;		$font_line1  = $font_col[3] + $font_line1_p;
						$font_text_p  = ($font_col[4]/100)*$percentage;			$font_text  = $font_col[4] + $font_text_p;
						$font_line2_p  = ($font_col[5]/100)*$percentage;		$font_line2  = $font_col[5] + $font_line2_p;
					}
				}
				else{
					$font_head  = $font_col[0];
					$font_label = $font_col[1];
					$font_label_text = $font_col[2];
					$font_line1 = $font_col[3];
					$font_text  = $font_col[4];
					$font_line2 = $font_col[5];
				}
			}
		}


		if ($j>1)
		{
			$xval = $xval + 350;
			//$y = $y_val;
			$first_name = $first_name2;
		}
		//Printing First Name
		if($_REQUEST['gift_type']=='surname')
		{
			$rs=$ajax_editor->getSurName($fname);
			//print_r($rs);
			
			//$text= $rs->Text;
			//$text=wordwrap($text, 120, "\n");
			//$text=$ajax_editor->getJustifyText($text);
			$text=$fname;
			$surnamearry	=	$ajax_editor->getSurName(strtolower($_REQUEST['first_name']));
			$str			=	substr($surnamearry->Text,0,1000)."...continued";
			
    		$__H=0;
			$newtext = wordwrap($str, 80, "\n");
			$s = split("[\n]+", $newtext);
			$font = SITE_PATH."/modules/ajax_editor/font/Georgia.ttf";
			
			list($image1,$text_size,$_H) =	$ajax_editor->printText($image1,$s,$font,520,250,15);
			
			if(strlen($surnamearry->Arms)>110)
			{
					$str =	substr($surnamearry->Arms,0,110);
			}else{	 		
					$str =	$surnamearry->Arms;
			}
			
			$newtext = wordwrap($surnamearry->Arms, 60, "\n");
			$s = split("[\n]+", $newtext);
			
			list($image1,$text_size) =	$ajax_editor->printText($image1,$s,$font,500,$_H+260,15);
			
			if(strlen($surnamearry->Crest)>110)
			{
					$str=substr($surnamearry->Crest,0,110);
			}else{	 		
					$str=$surnamearry->Crest;
			}
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$str,3,15,$_H+330,500);//Prints the main body of the poem
			$str=	$surnamearry->Origin;
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$str,3,15,$_H+350,500);//Prints the main body of the poem
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text[0],1,36,102,$xval);
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,1,52,$y,$xval);		
		
		}else if ($gift_type=="poem")
		{
			//list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$first_name,2,$font_head,$y,$xval);
					
					//*******************new code for port...images
					if($create_width<503)
					{
					$y=$y+30;
					$xval=$xval+40;
				
					}//***************
					
			
			if ($j==1)
			{
				//$space2 =($text_size[1]-$text_size[7]);
				$y= $y+0;
			}
			
			//$y = $y + $space2;
			
			//check from here $$$$$$$$$mohammad#$$$$$$
			$p_txt = explode("\n",$rs[0]->poem);
						
			$op_cnt = 0; 
			$cl_cnt = 0;
			$txt_cnt =0;
			$space_flag=1;
			for ($i=0;$i<sizeof($p_txt);$i++)
			{
				$text = $p_txt[$i];
				for ($t=0;$t<8;$t++)
				{
					if(strstr(strtolower($text),"<$t>"))
					{
						$poem_font = $rs[0]->{font_.$t};
					}
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				if(strstr(strtolower($text),'<opening'))
				{
					$op_cnt++;
					if ($_REQUEST["op{$op_cnt}"])
					{
						$text = $_REQUEST["op{$op_cnt}"];
						//$y=$y+20;
						//$space_flag=2;
					}
					elseif(isset($_REQUEST["op{$op_cnt}"]))
					{
							$text = '';
							$y=$y+40;
					}
					else 
					{
						$text = strstr($text,":");
						$text = str_replace(":","",$text);
						$text = str_replace(">","",$text);
						
					}
					//list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_label,$y,$xval);NOT REQUIRED ANY MORE.:(
					
					list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$text,2,$poem_font,$y,$xval);//Prints the opening lines of the Poem
//					$space2 =($text_size[1]-$text_size[7]);
						//$y=$y+20;
						
					$y = $y + ($text_size[1]-$text_size[7]);
					
				}
				elseif ((strstr(strtolower($text),'<closing')))
				{
					$cl_cnt++;
					if ($_REQUEST["cl{$cl_cnt}"])
					{
						$text = $_REQUEST["cl{$cl_cnt}"];
					}
					elseif (isset($_REQUEST["cl{$cl_cnt}"]))
					{
						$text = '';
					}
					else 
					{
						$text = strstr($text,":");
						$text = str_replace(":","",$text);
						$text = str_replace(">","",$text);
					}
					//if ($cl_cnt==1) $y = $y + 30;
					list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$text,2,$poem_font,$y,$xval);//Prints the closing lines of the Poem
					//$y=$y+20;
					$y = $y + ($text_size[1]-$text_size[7]);
				}
				else 
				{
					$txt_cnt++;
					//if ($txt_cnt==1) $y = $y-20;
					$y = $y + ($text_size[1]-$text_size[7]);
					
					if(strlen($text)==1)
					{
								
						$y=$y+20;
					}
					if($space_flag==1)
					{
						$y=$y-40;
						$space_flag=0;
					}
			
					//$text=htmlentities($text);
									
					//$text=strstr($text);
					list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$poem_font,$y,$xval);//Prints the main body of the poem
				}	

				//($i==1) ? $space =20 : $space=10;
				/*if ((strtolower($p_txt[$i])) || () )
				{
					
				}*/
				
				
				//($i==1) ? $space =20 : $space=10;
			}
			
		}
		else
		{
		
		//*******************new code for port...images
					if($create_width<503)
					{
					$y=$y+30;
					$xval=$xval+40;
				
					}//***************
		
			list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,$first_name,1,$font_head,$y,$xval);

			//Printing Origin Label
			if ($j==1)
			{
				//$space1 =($text_size[1]-$text_size[7])-20;
				$space1 =33;
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
				$space2 =($text_size[1]-$text_size[7])+15;
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
				$space4 = ($text_size[1]-$text_size[7])+8;
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
			$y = $y + $space5+3;
			//$y += 10;//
			$text = "2";
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,5,$font_line1,$y,$xval);

			$y +=16; //changed to 20 from 10
			//Printing 8 lines from Database
			for ($i=1;$i<=8;$i++)
			{
				//($i==1) ? $space =20 : $space=10;
				$y = $y + ($text_size[1]-$text_size[7])+3.3;
				$text = $ajax_editor->getLineText($i,$rs->{Line.$i},$gender,$lang);
				$text = str_replace("%NAME%",$first_name,$text); //Replcaing Name variable with the First Name
				list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);
			}


			//Printing a line
			$y = $y + ($text_size[1]-$text_size[7]);
			if ($j>1)
			{
				$y=$y+5;
			}
			$text = "2";
			list($image1,$text_size) = $ajax_editor->printCenteredText($image1,$text,5,$font_line2,$y,$xval);


			if ($first_name2=="")
			{
				$y = $y + 35;
				$text = $_REQUEST['sentiment1'];
				
				list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);

				$y = $y + ($text_size[1]-$text_size[7]);
				$y +=5;
				$text = $_REQUEST['sentiment2'];
				list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);

			}
			else
			{


				//$old_x = $x_val;
			}
			/*$y = $y + 35;
			$text = $_REQUEST['sentiment1'];
			list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);

			$y = $y + ($text_size[1]-$text_size[7]);
			$y +=5;
			$text = $_REQUEST['sentiment2'];
			list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$xval);*/

		}
	}
	
	if (($first_name2!="")|| ($gift_type=="poem"))
	{

		$old_x = ($old_x + $xval+($text_size[1]-$text_size[2]))/2;
		($gift_type == 'poem') ? $adj_y = 55 : $adj_y = 35;

		$y = $y + $adj_y;
		$text = $_REQUEST['sentiment1'];
		//$old_x=$old_x-$sentiment_adj_x;
		list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$old_x);

		$y = $y + ($text_size[1]-$text_size[7]);
		$y +=5;
		$text = $_REQUEST['sentiment2'];
		list($font_type,$text_size) = $ajax_editor->printCenteredText($image1,$text,2,$font_text,$y,$old_x);
	}
}
if ($_REQUEST['gift_type']=="surname")
		{
		
			list($image1,$text_size,$y) = $ajax_editor->printCenteredText($image1,"1212112",2,8,800,200);
		}
//imagejpeg(image,SITE_PATH."/modules/ajax_editor/images/ret.jpg",100);

/*$imagepng = imagecreatefrompng(SITE_PATH."/modules/ajax_editor/images/copyright-big.png");
$im = $imagepng;
		
	    $x1 = 70;
		$y1 = 10;
//$image  = imagecreatetruecolor(imagesx($im),imagesy($im));
imagecopy($image1,$imagepng,$x1,$y1,0,0,imagesx($im),imagesy($im));*/


$im_worder  = imagecreatetruecolor($create_width,$create_height);
imagecopyresampled($im_worder,$image1,0,0,0,0,$create_width,$create_height,imagesx($image1),imagesy($image1));
$imageorder = $im_worder ;



//$imagepng = imagecreatefrompng(SITE_PATH."/modules/ajax_editor/images/copyright-big.png");	
//$x1 = 70;
//$y1 = 10;
//imagecopy($image1,$imagepng,0,0,0,0,imagesx($imagepng),imagesy($imagepng));



$im_w1  = imagecreatetruecolor($create_width,$create_height);
imagecopyresampled($im_w1,$image1,0,0,0,0,$create_width,$create_height,imagesx($image1),imagesy($image1));
$image1 = $im_w1 ;

if ($_REQUEST["mat_id"]!="")
{
	
	$mat_image = $_REQUEST["mat_id"];
	$image2 = imagecreatefromgif(SITE_PATH."/modules/product/images/accessory/$mat_image");
	
	//*******************image rotate
	if($create_width<503)
	{
	//$image2 = imagerotate($image2, 90, 0);

	}//***************
	
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
		$y1 = (imagesy($im) - imagesy($image1))/2;
		//$y1 =100
		$x2 = 0;
		$y2 = 0;
		
	}
	$image  = imagecreatetruecolor(imagesx($im),imagesy($im));
	imagecolorallocate($image,255,255,255);
	imagecopy($image,$image1,$x1,$y1,0,0,imagesx($image),imagesy($image));


	//$image2 = $image_2;

	imagecopy($image,$image2,$x2,$y2,0,0,imagesx($image),imagesy($image));
	
	//******* code for portrate images *********
	//if($create_width<503)
	if($create_width<503 && $_REQUEST['gift_type']=='tree')
		{
		$x1 = (imagesx($im) - imagesx($image1))/2-50;
		$y1 = (imagesy($im) - imagesy($image1))/2+50;
		$x2 = 60;
		$y2 = 60;
	$image4  = imagecreatetruecolor(imagesx($image2),imagesy($image2));
	imagecolorallocate($image4,255,255,255);
	$rotate=imagerotate($image1,90,0);
	imagecopy($image4,$rotate,$x1,$y1,0,0,imagesx($image4),imagesy($image4));
	imagecopy($image4,$image2,0,0,0,0,imagesx($image4),imagesy($image4));
	//imagecopyresampled($image4,$image2,0,0,0,0,400,521,imagesx($image2),imagesy($image2));
	$rotate=imagerotate($image4,270,0);
	$image=$rotate;
	}
	
	//******* endcode for portrate images
	
	
	

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

if($_REQUEST["frame_id"])
{
	
	$f_image = $_REQUEST["frame_id"];
	$image12 = imagecreatefromgif(SITE_PATH."/modules/product/images/accessory/$f_image");
	$im = $image12;
	$image  = imagecreatetruecolor(imagesx($im),imagesy($im));
	imagecolorallocate($image,255,255,255);
	
	//$rotate=imagerotate($image,90,0);

	if($create_width<503)
	{
		$x1 = (imagesx($im) - imagesx($image1))/2-50;
		$y1 = (imagesy($im) - imagesy($image1))/0.95;
		$rotate=imagerotate($image1,90,0);
			imagecopy($image,$rotate,$x1,$y1,0,0,imagesx($image),imagesy($image));
	}
	else
	{

		$x1 = (imagesx($im) - imagesx($image1))/2-3;
		$y1 = (imagesy($im) - imagesy($image1))/2;
	
		imagecopy($image,$image1,$x1,$y1,0,0,imagesx($image),imagesy($image));
	

	
	}


	
	$x1 = (imagesx($im) - imagesx($image2))/2-3;
	$y1 = (imagesy($im) - imagesy($image2))/2;
	
	imagecopy($image,$image2,$x1,$y1 ,0,0,imagesx($image2),imagesy($image2));
	imagecopy($image,$image12,0,0,0,0,imagesx($image),imagesy($image));

	if($create_width<503)
	{
		$rotate=imagerotate($image,270,0);
		$image=$rotate;
	}
	
}



if ( (imagesx($image)>648) || (imagesy($image)>521))
{

	if($create_width<503)
	{
		$dest_image = imagecreatetruecolor(518,648);
		imagecopyresampled($dest_image,$image,0,0,0,0,518,648,imagesx($image),imagesy($image));
	
	}
	else
	{
		$dest_image = imagecreatetruecolor(648,521);
		imagecopyresampled($dest_image,$image,0,0,0,0,648,521,imagesx($image),imagesy($image));
	}

	
	
}
else
{
	$dest_image = $image;
}



//$_SESSION['pathim']=SITE_URL."/modules/ajax_editor/images/$image_name"."_c.jpg";
$_SESSION['pathim']=SITE_URL."/modules/ajax_editor/images/$image_name.jpg";
imagejpeg($dest_image,SITE_PATH."/modules/ajax_editor/images/$image_name.jpg",100);

########To display the copy right image on the actual image########
########Added by :Jinson####################
########Added on 25 th Feb,2008########
/*$imagepng = imagecreatefrompng(SITE_PATH."/modules/ajax_editor/images/copyright.png");
$im = $imagepng;
		
	    $x1 = 60;
		$y1 = 50;
$image  = imagecreatetruecolor(imagesx($im),imagesy($im));
imagecopy($dest_image,$imagepng,$x1,$y1,0,0,imagesx($image),imagesy($image));*/


/*$handle = new RWatermark(FILE_JPEG, SITE_PATH."/modules/ajax_editor/images/$image_name.jpg");
	$handle->SetPosition("CM");
	$handle->SetTransparentColor(200, 0, 200);
	$handle->SetTransparency(70);
	$handle->AddWatermark(FILE_PNG, SITE_PATH."/modules/ajax_editor/images/copyright.png");
	Header("Content-type: image/png");
	$handle->GetMarkedImage(IMG_PNG);
	$handle->Destroy();
*/
//imagejpeg($dest_image);
imagedestroy($dest_image);
/*
$im_worder  = imagecreatetruecolor(504,392);
imagecopyresampled($im_worder,$imageorder,0,0,0,0,504,392,imagesx($imageorder),imagesy($imageorder));
$imageorder = $im_worder ;

if ($_REQUEST["mat_id"]!="")
{
	
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
	imagecolorallocate($image,255,255,255);
	imagecopy($image,$imageorder,$x1,$y1,0,0,imagesx($image),imagesy($image));


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

$image = $imageorder;
}



if ( (imagesx($image)>648) || (imagesy($image)>521))
{	

	if($create_width<503)
	{
	
	$dest_imageorder = imagecreatetruecolor(521,648);
		imagecopyresampled($dest_imageorder,$image,0,0,0,0,521,648,imagesx($image),imagesy($image));

	}
	else
	{

	//dest_imageorder = $image;

		$dest_imageorder = imagecreatetruecolor(648,521);
		imagecopyresampled($dest_imageorder,$image,0,0,0,0,648,521,imagesx($image),imagesy($image));
	}
}
else
{
	$dest_imageorder = $image;
}

imagejpeg($dest_imageorder,SITE_PATH."/modules/ajax_editor/images/$image_name.jpg",100);

imagejpeg($dest_imageorder);
imagedestroy($dest_imageorder);*/

exit;
?>