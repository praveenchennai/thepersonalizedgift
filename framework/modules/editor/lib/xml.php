<?php
ob_start();
include_once("config.php");
include_once(FRAMEWORK_PATH."/includes/functions.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(SITE_PATH."/includes/xmlConfig.php");
include_once(FRAMEWORK_PATH."/modules/editor/lib/class.editor.php");
$framework 	= 	new FrameWork();
$editorObj     =   new editor();
switch($_REQUEST['act']) {
	case "clipart":
	echo trim($editorObj->getClipart());
	break;
	
	case "prod":
	$catId=$_REQUEST["cid"];
	echo $editorObj->products($catId);
	break;	
	
	case "dualside":
	$proId=$_REQUEST["proid"];
	echo $editorObj->GetSides($proId);
	break;	
	
	case "getSavedXml":	
	$art_id=$_REQUEST["sProId"];
	echo $editorObj->getSavedXml($art_id);
	exit;
	break;

	case "editgetSavedXml":	
	$id=$_REQUEST['id'];
	echo $editorObj->editgetSavedXml($id);
	exit;
	break;

	case "getSavedArtXml":	
	$art_id=$_REQUEST["sProId"];
	echo $editorObj->editgetSavedXml($art_id);
	exit;
	
	case "save":	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$text=$_POST['imgXML'];
		$paddleId=$_POST['prodID'];
		$file=time().$paddleId.'.xml';
		//$file='11.xml';
		
		$text=str_replace('\'','"',$text);
		$text=str_replace('\"','"',$text);
						
		if ( !file_exists(SITE_PATH."/modules/editor/xml/".$file))
		{		
		$handle = fopen (SITE_PATH."/modules/editor/xml/".$file, 'w+');
		
		fwrite ($handle, $text); 
		fclose ($handle);
		
		}
		
			//echo "mission=true";
		
	
	//$product_id=1;
	//$xmlData=$editorObj->getClipart();
	
	//$user_id=$_SESSION['member_id'];
	
	//$editorObj->saveXml(1,$paddleId,$text);
	
	
	/*************code for creating image***************/
	
	Header("Content-type: image/jpeg");
	$dom = new DOMDocument();
	$dom->load(SITE_URL."/modules/editor/xml/".$file );
	$paddle = $dom->getElementsByTagName('obj');
		$resultArray;
		$i=0;
			foreach ($paddle as $param) {
				$resultArray[$i][0]= $param -> getAttribute('type');
				 $resultArray[$i][1]= $param -> getAttribute('posx');
				 $resultArray[$i][2]= $param -> getAttribute('posy');
				 $resultArray[$i][3]= $param -> getAttribute('ctrlx');
				 $resultArray[$i][4]= $param -> getAttribute('ctrly');
				 $resultArray[$i][5]= $param -> getAttribute('posxe');
				 $resultArray[$i][6]= $param -> getAttribute('posye');
				 $resultArray[$i][7]= $param -> getAttribute('rotation');
				 $resultArray[$i][8]= $param -> getAttribute('txtvalue');
				 $resultArray[$i][9]= $param -> getAttribute('txtfont');
				 $resultArray[$i][10]= $param -> getAttribute('txtsize');
				 $resultArray[$i][11]= $param -> getAttribute('objcolor');
				 $resultArray[$i][12]= $param -> getAttribute('objfillcolor');
				 $resultArray[$i][13]= $param -> getAttribute('objalpha');
				 $resultArray[$i][14]= $param -> getAttribute('artpath');
				 $resultArray[$i][15]= $param -> getAttribute('objwidth');
				 $resultArray[$i][16]= $param -> getAttribute('objheight');
				 $resultArray[$i][17]= $param -> getAttribute('linewidth');
				 $resultArray[$i][18]= $param -> getAttribute('circlerediusx');
				 $resultArray[$i][19]= $param -> getAttribute('circlerediusx');
				 $resultArray[$i][20]= $param -> getAttribute('cornerradius');
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
							$color=$editorObj->getColor($image,substr($resultArray[$i][11],2,strlen($resultArray[$i][11])));
							$font=$editorObj->getMyFont($resultArray[$i][9]);
							@imagettftext($image,$resultArray[$i][10],0,$resultArray[$i][2],$resultArray[$i][2],$color, $font, $resultArray[$i][8]);
						   break;
						case "ARTOBJ":
							$src_im=imagecreatefromjpeg($resultArray[$i][14]);
							imagecopymerge($image, $src_im, 10, 180, 0, 0, 100, 100, 100 );
			
						   break;
						case "LINEOBJ":
						  // imageline($image,0,0,50, 50, $white);
							$color=$editorObj->getColor($image,substr($resultArray[$i][11],2,strlen($resultArray[$i][11])));
							$editorObj->imagelinethick($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5], $resultArray[$i][6], $color, $resultArray[$i][17]);
						   break;
						   
						 case "RECTOBJ":
						  // imageline($image,0,0,50, 50, $white);
						  $color=$editorObj->getColor($image,substr($resultArray[$i][12],2,strlen($resultArray[$i][12])));
							imagerectangle($image,$resultArray[$i][1],$resultArray[$i][2],$resultArray[$i][5], $resultArray[$i][6], $color);
							imagefilledrectangle($image,$resultArray[$i][1],$resultArray[$i][2],$resultArray[$i][5], $resultArray[$i][6], $color);
						   break;
						   
						   case "ARCOBJ":
						  // imageline($image,0,0,50, 50, $white);
						  $color=$editorObj->getColor($image,substr($resultArray[$i][11],2,strlen($resultArray[$i][11])));
							imagearc($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5],$resultArray[$i][6],  0, 180, $color);
						   break;
						   
						  case "ROUNDRECTOBJ":
						  // imageline($image,0,0,50, 50, $white);
						   $color=$editorObj->getColor($image,substr($resultArray[$i][12],2,strlen($resultArray[$i][12])));
						  $editorObj->ImageRectangleWithRoundedCorners($image, $resultArray[$i][1], $resultArray[$i][2], $resultArray[$i][5],$resultArray[$i][6], 5, $color);
						 
						  break;
			
					}
					
					
				}
				
				
				
					@imagejpeg($image);
		
		/******** end of image create*********/
	}
	
	redirect(makeLink(array("mod"=>"editor", "pg"=>"buildapaddle")));
	break;
	
	case "getxml":
	$xml_id=1;
	echo $editorObj->getXml($xml_id);
	break;
	
	case "upload":
		$fileName=$_REQUEST['fileName'];
		$file='test4.txt';
			if ( !file_exists(SITE_PATH."/modules/editor/xml/".$file))
		{		
		$handle = fopen (SITE_PATH."/modules/editor/xml/".$file, 'w+');
		
		fwrite ($handle, $fileName); 
		fclose ($handle);
		
		}
		if ($_FILES['Filedata']['name']) {
		   $uploadDir = SITE_PATH."/modules/editor/uploadImages/";
		   $uploadFile = $uploadDir . $fileName;
		   move_uploaded_file($_FILES['Filedata']['tmp_name'], $uploadFile);
		 // Author  : Vipin 
		   //Created  : 31/10/2007
		   // To save the uplaoded images name of corespoding users and category
		    	$req = &$_REQUEST;
				$req['image'] = $_FILES['Filedata']['name'];
				$rs=$editorObj->editorImageSave($req);
				/*if( ($editorObj->editorImageSave($req)) === true ) {
					redirect(makeLink(array("mod"=>"editor", "pg"=>"buildapaddle")));
				}*/
		}
		
	break;
	
	case "editgetSavedXml":	
	$art_id=$_REQUEST["sProId"];
	echo $editorObj->editgetSavedXml($art_id);
	exit;
	break;
	case "crop":
		$source=$_POST['clpPath'];
		$orgWidth =$_POST['orgWidth'];
		$orgHeight =$_POST['orgHeight'];
		$x1=$_POST['cropX'];
		$y1=$_POST['cropY'];
		$cropWidth=$_POST['cropWidth'];
		$cropHeight=$_POST['cropHeight'];
		/*
		$source='http://192.168.1.254/taking_art/modules/product/images/accessory/1.jpg';
		$orgWidth =200;
		$orgHeight =200;
		$x1=0;
		$y1=0;
		$cropWidth=50;
		$cropHeight=50;
				
				
				$file="test21.txt";
				$text.=$source.'<br>';
				$text.=$orgWidth.'<br>';
				$text.=$orgHeight.'<br>';
				$text.=$x1.'<br>';
				$text.=$y1.'<br>';
				$text.=$cropWidth.'<br>';
				$text.=$cropHeight.'<br>';
				//$text='test';
		if ( !file_exists(SITE_PATH."/modules/editor/xml/".$file))
		{		
		$handle = fopen (SITE_PATH."/modules/editor/xml/".$file, 'w+');
		
		fwrite ($handle, $text); 
		fclose ($handle);
		
		}*/
		$x2=$cropWidth;
		$y2=$cropHeight;
		$name=rand(0,10000);
		$stype=$editorObj->findType($source);
		
		$dest=SITE_PATH."/modules/editor/cropImages/".$name.".".$stype;
		$clpNewPath=SITE_URL."/modules/editor/cropImages/".$name.".".$stype;
			
		$editorObj->StrechImage($orgWidth, $orgHeight, $source, $stype, $dest);
		$editorObj->CropImage($dest,$dest,$x1,$y1,$x2,$y2);
		echo "&clpNewPath=".$clpNewPath."&bSuccess=1";
	break;
		case "listprodimg":
		$catId=$_REQUEST["cid"];
		echo  $editorObj->accessoryImagePathxml($catId);
	break;	
	break;	
}
?>