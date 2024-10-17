<?php

Header("Content-type: image/jpeg");
drawRectangle($width, $height,$radius);

function drawRectangle($width, $height,$radius) {
  
   $width = 100;
   $height = 100;


   $image = ImageCreate($width,$height);
   

   // Define base colors
   $back = ImageColorAllocate($image,255,255,255);
   $blur = ImageColorAllocate($image,230,223,188);
    
       $radius = floor($height/10);       
       ImageRectangleWithRoundedCorners($image, 1, 1, $width, $height, $radius, $blur);
	   
   ImagePNG($image);
   ImageDestroy($image);
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