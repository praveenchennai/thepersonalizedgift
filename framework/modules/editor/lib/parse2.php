<?
Header("Content-type: image/jpeg");
$dom = new DOMDocument();
$dom->load( 'paddletemplate.xml' );
$paddle = $dom->getElementsByTagName('OBJ');
$resultArray;
$i=0;
foreach ($paddle as $param) {
       	$resultArray[$i][0]= $param -> getAttribute('TYPE');
	   	 $resultArray[$i][1]= $param -> getAttribute('POSX');
		 $resultArray[$i][2]= $param -> getAttribute('POSY');
		 $resultArray[$i][3]= $param -> getAttribute('CTRLX');
		 $resultArray[$i][4]= $param -> getAttribute('CTRLY');
		 $resultArray[$i][5]= $param -> getAttribute('POSXE');
		 $resultArray[$i][6]= $param -> getAttribute('POSYE');
		 $resultArray[$i][7]= $param -> getAttribute('ROTATION');
		 $resultArray[$i][8]= $param -> getAttribute('TXTVALUE');
		 $resultArray[$i][9]= $param -> getAttribute('TXTFONT');
		 $resultArray[$i][10]= $param -> getAttribute('TXTSIZE');
		 $resultArray[$i][11]= $param -> getAttribute('OBJCOLOR');
		 $resultArray[$i][12]= $param -> getAttribute('OBJFILLCOLOR');
		 $resultArray[$i][13]= $param -> getAttribute('OBJALPHA');
		 $resultArray[$i][14]= $param -> getAttribute('ARTPATH');
		 $resultArray[$i][15]= $param -> getAttribute('OBJWIDTH');
		 $resultArray[$i][16]= $param -> getAttribute('OBJHEIGHT');
		 $resultArray[$i][17]= $param -> getAttribute('LINEWIDTH');
		 $resultArray[$i][18]= $param -> getAttribute('CIRCLERADIUSX');
		 $resultArray[$i][19]= $param -> getAttribute('CIRCLERADIUSY');
		 $resultArray[$i][20]= $param -> getAttribute('CORNERRADIUS');
	   $i++;
}
$countArray=count($resultArray);
$image = ImageCreate(300,300);
$back = ImageColorAllocate($image,230,255,255);
$white= ImageColorAllocate($image,255,255,255);

	for($i=0;$i<$countArray;$i++)
	{
		$objType=$resultArray[$i][0];
		switch ($objType)
		{
			case "TEXTOBJ":
				$color=getColor($image,$resultArray[$i][11]);
				$font=getMyFont($resultArray[$i][9]);
				@imagettftext($image,$resultArray[$i][10],0,$resultArray[$i][2],$resultArray[$i][2],$color, $font, $resultArray[$i][8]);
			   break;
			case "ARTOBJ":
				$src_im=imagecreatefromjpeg($resultArray[$i][14]);
			 	imagecopymerge($image, $src_im, 10, 180, 0, 0, 100, 100, 100 );

			   break;
			case "LINEOBJ":
			  // imageline($image,0,0,50, 50, $white);
			  	$color=getColor($image,$resultArray[$i][11]);
			   	imagelinethick($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5], $resultArray[$i][6], $color, $resultArray[$i][17]);
			   break;
			   
			 case "RECTOBJ":
			  // imageline($image,0,0,50, 50, $white);
			  $color=getColor($image,$resultArray[$i][12]);
			  	imagerectangle($image,$resultArray[$i][1],$resultArray[$i][2],$resultArray[$i][5], $resultArray[$i][6], $color);
				imagefilledrectangle($image,$resultArray[$i][1],$resultArray[$i][2],$resultArray[$i][5], $resultArray[$i][6], $color);
			   break;
			   
			   case "ARCOBJ":
			  // imageline($image,0,0,50, 50, $white);
			  $color=getColor($image,$resultArray[$i][11]);
			  	imagearc($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5],$resultArray[$i][6],  0, 180, $color);
			   break;
			   
			  case "ROUNDRECTOBJ":
			  // imageline($image,0,0,50, 50, $white);
			  $color=getColor($image,$resultArray[$i][12]);
			  ImageRectangleWithRoundedCorners($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5],$resultArray[$i][6], 5, $color);
			  break;

		}
		
		
	}
	
	
	
	@imagejpeg($image); 
	
	
	
	//functions
	
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
	
	function getColor($image,$code)
	{
			$hex=$code;
			$red = hexdec(substr($hex,0,2)); 
			$green = hexdec(substr($hex,2,2)); 
			$blue = hexdec(substr($hex,4,2)); 
			$color= ImageColorAllocate($image,$red,$green,$blue);
			return $color;
	}
	
	
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
?>
