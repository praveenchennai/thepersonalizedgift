<?php
class Ajax_Editor extends FrameWork 
{
	function Ajax_Editor()
	{
		$this->FrameWork();
	}
	
	function getColor($image,$code)
	{
		$hex=$code;
		$red = hexdec(substr($hex,0,2)); 
		$green = hexdec(substr($hex,2,2)); 
		$blue = hexdec(substr($hex,4,2)); 
		$color= ImageColorAllocate($image,$red,$green,$blue);
		return $color;
	}
	
	function getFontType($type)
	{
		$sql = "select field_name,$type from font_types";
		$rs  = $this->db->get_col($sql,1);
		return $rs;
	}
	function getFontName($font)
	{
		if ($font==1)
		{
			$font = SITE_PATH."/modules/ajax_editor/font/aridi33_.ttf";
		}
		elseif ($font==2)
		{
			$font = SITE_PATH."/modules/ajax_editor/font/zafchami.ttf";
		}
		elseif ($font==3)
		{
			$font = SITE_PATH."/modules/ajax_editor/font/Georgia.ttf";
		}
		else
		{
			$font = SITE_PATH."/modules/ajax_editor/font/PTGraphics.ttf";
		}
		return $font;
	}
	
	function checkTextLength($text,$font,$font_type)
	{
		$type_arr    = array();
		$type_arr[0] = "tiny";
		$type_arr[1] = "very_small";
		$type_arr[2] = "small";
		$type_arr[3] = "medium";
		$type_arr[4] = "large";
		$font  = $this->getFontName($font);
		
		$font_col = $this->getFontType($type_arr[$font_type]);
		
		$text_size = imagettfbbox($font_col[$font_size],0,$font,$text);
		
		$text_width = $text_size[2]-$text_size[0];
		return $text_width;
	}
	
	function checkTextSize($image,$text,$font,$font_size,$y,$x='',$font_type,$custom_size=0)
	{
		$type_arr    = array();
		$type_arr[0] = "tiny";
		$type_arr[1] = "very_small";
		$type_arr[2] = "small";
		$type_arr[3] = "medium";
		$type_arr[4] = "large";
		
		$font  = $this->getFontName($font);
		$check = 1;
		($custom_size==0)? $fnt_size = $font_col[$font_size] : $fnt_size = $font_size;
		
		while($check==1)
		{
			
			$font_col = $this->getFontType($type_arr[$font_type]);
			
			$text_size = imagettfbbox($fnt_size,0,$font,$text);
			
			$text_width = $text_size[2]-$text_size[0];
			if ($x=="")
			{
				$x_val = (imagesx($image)-$text_width)/2;
			}
			else 
			{	
				//$x_val = ($x-$text_width)/2;
				$x_val = $x-($text_width/2);
			}
			
			$max_width = imagesx($image)- $text_width;
			/*if ($font_type==2)
			{
				if ($x_val<0)
				{
				print $x_val;
					print $font_col[$font_size];
					//print $text_width;
					exit;
				}
				
			}*/
			
			if ( ($x_val<0) || ($x_val>$max_width))
			{
				
				if ($font_type>0)
				{
					
					$font_type = $font_type - 1;
					
				}
				else
				{
					$check = 0;
				}
			}
			else 
			{
				$check = 0;
			}
		}	
		$arr[0] = $font_type;
		$arr[1] = $text_size;
		$arr[2] = $y;
			
		return $arr;	
	}
	
	function printCenteredText($image,$text,$font,$font_size,$y,$x='',$color='000000')
	{
		$font = $this->getFontName($font);
		
		
		
		$text_size = imagettfbbox($font_size,0,$font,$text);
		
		
		//$y = $y + $text_size[7];
		$text_width = $text_size[2]-$text_size[0];
		if ($x=="")
		{
			$x = (imagesx($image)-$text_width)/2;
		}
		else 
		{	
			//$x = ($x-$text_width)/2;
			$x = $x-($text_width/2);
		}	
		/*if ($text=="his family is always number one with him")
				{
					print $x;
					print "yes";
					exit;
				}*/
		//$x = 100-$text_width/2;
		//$x = (100-$text_width)/2;
		$image= $this->addImageText($image,$text,$color,$font,$font_size,$x,$y);
		$arr = array();
		$arr[0] = $image;
		$arr[1] = $text_size;
		$arr[2] = $y;
		return $arr;
	}
	
	function getMeaning($first_name,$gender='M')
	{
		$sql = "select * from FirstNames where Firstname='$first_name' and Gender='$gender'";
		$rs  = $this->db->get_row($sql);
		$MAX_M = 0;
		$MAX_F = 0;
		if (count($rs)==0)
		{
			
			/*$x0 = 200;
			$a = 1140671485;
			$c = 12820163;

			$x1 = ($x0 * $a + $c);
			//$x1 = $x1 % pow(2,24);
			$p = pow(2,24);
			
			//print $x1."\n".$p;
			print $x1 % $p/$p;
			exit;
			
			print $res;
			
			//print floatval($X1);
			exit;*/
			$name_str = strtolower($first_name);
			
			$sum = 0;
			for ($i=1;$i<=strlen($name_str);$i++)
			{
				$sum +=ord(substr($name_str,$i-1,1));
				
			}
			$sum+=ord(strtolower($gender));
			
			
			$sql = "select max(num) as max_m from smart_add where gender='M'";
			$rs1 = $this->db->get_row($sql);
			$MAX_M = $rs1->max_m;
			
			$sql = "select max(num) as max_f from smart_add where gender='F'";
			$rs1 = $this->db->get_row($sql);
			$MAX_F = $rs1->max_f;
			//srand($sum);
			
			
			if ($gender=='M')
			{
				$i_search = ($sum % $MAX_M);
				if ($i_search==0)	
				{
					$i_search = $MAX_M;
				}
			}
			else 
			{
				$i_search = ($sum % $MAX_F);
				if ($i_search==0)	
				{
					$i_search = $MAX_F;
				}	
			}
			
			$sql = "select * from smart_add where num=$i_search and gender='M'";
			
			$rs1 = $this->db->get_row($sql);
			$rs->Meaning=$rs1->meaning;
			$rs->SpanishMeaning = $rs1->spanishmeaning;
			$rs->Gender = $gender;
			//print $i_search;
			$lines = $this->creatLines($first_name);
			
			
			for ($k=0;$k<8;$k++)
			{
				
				$rs->{Line.($k+1)} = $lines[$k]; 	
				
			}
			$rs->Origin = "'unique or given'";
			$rs->SpanishOrigin = "'único o dado'";
			//print "\n";
			
			
		}
		
		return $rs;
	}
	
	// function name : getSurname
	//Author : Robin
	//Description: Getting surnames from surnames database
	//Prameters : $surname
	//created Date : April 25 2008
	
	function getSurName($surname)
	{
		$sql = "select * from Surnames where Name = '$surname' ";
		$rs  = $this->db->get_row($sql);
		
		
		return $rs;
	}
	
	
	function creatLines($name)
	{
	
		$name = strrev(strtoupper($name));
		
		$new_name  = "";
		
		for($i=0;$i<strlen($name);$i++)
		{
			
			if ((ord(substr($name,$i,1))>=65) && (ord(substr($name,$i,1))<=90))			
			{
				$new_name .= substr($name,$i,1);
			}
		}
		
		$name = $new_name;
	
		if (strlen($name)>0)
		{
			$name = $this->generateFullName($name);
			$lines = $this->getLineNumbers($name);
			return $lines;
		}
	}
	
	function generateFullName($name)
	{
		if (strlen($name)<11)
		{
			$append_name = $name;
			while (strlen($name)<11)
			{
				$name .= $append_name;
			}
		}
		
		$name = substr($name,0,11);
		
		return $name;
	}
	
	function getLineNumbers($name)
	{
		
		$arr = array();
		for ($i=1;$i<=8;$i++)
		{
			$v=0;
			
			for ($j=$i;$j<=$i+3;$j++)
			{
				
				$v = $v + (ord(substr($name,$j-1,1))-64);
				
			}
			
			$v = $v-4;
			
			if ($v<1)
			{
				$v = 1;
			}
			
			if ($v>100)
			{
				$v = 100;
			}
			$arr[$i-1] =$v;
		}
		return $arr;
	}
	
	function searchDb($sql)
	{
		$rs = $this->db->get_row($sql);
		if (count($rs)>0)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	function getNum()
	{
		return rand(1,100);
	}
	function getLineText($line_number,$text_number,$gender,$language=1)
	{
		if ($language==1)
		{
			$table_name = "LineText";
		}
		else 
		{
			$table_name = "LineText_sp";
		}
		$sql = "select text_descr from $table_name where line_number=$line_number and text_number=$text_number and gender='$gender'";
		
		$rs  = $this->db->get_row($sql);
		
		return $rs->text_descr;
	}
	
	function addImageText($image,$image_string='',$color='000000',$font,$font_size=10,$x='',$y='')
	{
		$color = $this->getColor($image,$color);
		
		
		$text = $image_string;
		/*$limit = 100;
	
	
		for ($i=0;$i<strlen($image_string);$i+=$limit)
		{
			$text .= "\n".substr($image_string,$i,$limit);
		}*/
		
		@imagettftext($image,$font_size,0,$x,$y,0,$font,$text);
		return $image;
	}	
	
	function GetPoemText($poem)
	{
	
				
				
				
			$p_txt = explode("\n",$poem);
			//print_r($p_txt);
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
					
				}
				
				$p_txt[$i]=$text;
			}
			
			
			
			//$poem_str="<div></div>";
			for($i=0;$i<count($p_txt);$i++)
			{
				$poem_str.="<font style='font:Verdana, Arial, Helvetica, sans-serif; font-size:16px; '>".$p_txt[$i]."</font>";
			}
			return $poem_str;
	}
	 /**
      * Author   : Salim
      * Created  : 22/Apr/2008
      * Modified : 
      * Get the openings lines from the poems.
      */

	function GetTextBoxValuesO($poem,$cnt)
	{
			$newstring = '';
				
			$p_txt = explode("\n",$poem);
						
			for ($i=0;$i<count($p_txt);$i++)
			{
				$text = $p_txt[$i];
				
				for ($t=0;$t<8;$t++)
				{
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				if(strstr(strtolower($text),'<opening'))
				{
					$piece = explode(":",$text);
					$newstring .= $piece[1];
				}
			}
			return $newstring;
	}
	
	function GetTOpeningLines($poem,$cnt)
	{
			$newstring = '';
				
			$p_txt = explode("\n",$poem);
						
			for ($i=0;$i<count($p_txt);$i++)
			{
				$text = $p_txt[$i];
				
				for ($t=0;$t<8;$t++)
				{
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				if(strstr(strtolower($text),'<opening'))
				{
					$piece = explode(":",$text);
					$newstring[] = preg_replace("/,>|>/","",$piece[1]);
				}
			}
			return $newstring;
	}
	
	
	/**
      * Author   : Salim
      * Created  : 23/Apr/2008
      * Modified : 
      * Get the closing lines from the poems.
      */
	function GetTextBoxValuesC($poem,$cnt)
	{
			$newstring = '';
				
			$p_txt = explode("\n",$poem);
			
			for ($i=0;$i<count($p_txt);$i++)
			{
				
				$text = $p_txt[$i];
				
				for ($t=0;$t<8;$t++)
				{
					
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				
				if ((strstr(strtolower($text),'<closing')))
				{
					$piece = explode(":",$text);
					$newstring .= $piece[1];
				}
			}
			return $newstring;
	}
	
	function GetTClosingLines($poem,$cnt)
	{
			$newstring = '';
				
			$p_txt = explode("\n",$poem);
			
			for ($i=0;$i<count($p_txt);$i++)
			{
				
				$text = $p_txt[$i];
				
				for ($t=0;$t<8;$t++)
				{
					
					$text = str_replace("<$t>","",$text);
					$text = str_replace("</$t>","",$text);
				}
				
				if ((strstr(strtolower($text),'<closing')))
				{
					$piece = explode(":",$text);
					$newstring[] = preg_replace("/,>|>/","",$piece[1]);
				}
			}
			return $newstring;
	}
	
	
	
	function getMyFont($font=''){
		switch($font)
		{
			case "Arial":
				$font = SITE_PATH."/modules/ajax_editor/font/arial.ttf";
			break;
			case "Monotype Corsiva":
				$font = SITE_PATH."/modules/ajax_editor/font/mtcorsva.ttf";
			break;
			case "Trebuchet MS":
				$font= SITE_PATH."/modules/ajax_editor/font/trebuc.ttf";
			break;
			case "Haettenschweiler":
				$font = SITE_PATH."/modules/ajax_editor/font/hatten.ttf";
			break;
			
			default:
				$font = SITE_PATH."/modules/ajax_editor/font/aridi33_.ttf";
		}
		return $font;
	
	}
	
	function getJustifyText($text)
	{
		$length='120';
		
		$textArray=explode("\n",$text);
		$arrCount=count($textArray);
		for($i=0;$i<$arrCount;$i++)
			{
				$strlength=strlen($arrCount);
				if($strlength<$length)
				{
				
				}
			}
		//print_r($textArray);
			
		
		return $text;
	}
	
	function printText($image,$s,$font,$x,$y,$text_size)
	{
		$angle = 0;
		
		foreach($s as $key=>$val){
				$_b = imageTTFBbox(15,0,$font,$val);
				$_W = abs($_b[2]-$_b[0]); 
				//Defining the X coordinate.
				 $_X = abs($W/2)-abs($_W/2)+$x;
				//Defining the Y coordinate.
				$_H = abs($_b[5]-$_b[3]);  
				$__H += $_H; 
				@imagettftext($image,$text_size, $angle, $_X, $__H+$y, $text_color, $font, $val);
				$__H += 6;
		}
		
		$arr = array();
		$arr[0] = $image;
		$arr[1] = $text_size;
		$arr[2] = $__H;
		return $arr;
		
	}
	
}//End Class
?>