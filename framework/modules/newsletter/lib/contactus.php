<?
session_start();
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$user 			=	new User();
$email			= 	new Email();
$album			= 	new Album();
$objCms         =   new Cms();

$rs				=	$user->getUsernameDetails("admin");
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
switch($_REQUEST['act']) {
	case "contact":
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	$framework->tpl->assign("LEFTBOTTOM","social_community" );
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['your_name']))
			{
				$error	=	"Enter Your Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['your_email']))
			{
				$error	=	"Enter Your Email";
				$flag	=	1;
			}
			else if(empty($_REQUEST['message']))
			{
				$error	=	"Enter Your Message";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_address1']))
			{
				$error	=	"Enter Your Street Address";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_city']))
			{
				$error	=	"Enter Your City";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_postalcode']))
			{
				$error	=	"Enter Your Postal Code";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_country']))
			{
				$error	=	"Enter Your Country";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_state']))
			{
				$error	=	"Enter Your State";
				$flag	=	1;
			}
			$country = $user->getCountryName($_REQUEST['billing_country']);
			$date = date("F j, Y");
			if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['your_email'],
											"to"	=>	$rs['email']);
				$dynamic_vars 	=	array(	"YOUR_NAME"	=>	$_REQUEST['your_name'],
											"ADDRESS1"	=>	$_REQUEST['billing_address1'],
											"ADDRESS2"	=>	$_REQUEST['billing_address2'],
											"ADDRESS3"	=>	$_REQUEST['billing_address3'],
											"CITY"		=>	$_REQUEST['billing_city'],
											"ZIP"		=>	$_REQUEST['billing_postalcode'],
											"COUNTRY"	=>	$country['country_name'],
											"STATE"		=>	$_REQUEST['billing_state'],
											"PHONE"		=>	$_REQUEST['shipping_telephone'],
											"DATE"		=>	$date,
											"ADMIN_NAME"=>	$rs['first_name']." ".$rs['last_name'],
											"CONTENT"	=>	trim(stripslashes(nl2br($_REQUEST['message']))));
			if($email->send('contact_us', $mail_header, $dynamic_vars))
					{
					setMessage("Your message  sent successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"newsletter", "pg"=>"contactus"),"act=contact"));
					}
				}//if($flag==0)
			else
				{
				setMessage($error);
				}
		}//if($_SERVER['REQUEST_METHOD'] == "POST")
		
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("STATE_LIST", $objUser->listStateUS());
		$framework->tpl->assign("BILLING_ADDRESS", $_SESSION['BILLING_ADDRESS']);
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/newsletter/tpl/contact_us.tpl");
		break;
		
		case "quote_request":
	
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['name']))
			{
				$error	=	"Enter Your Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['email']))
			{
				$error	=	"Enter Your Email";
				$flag	=	1;
			}
			
			else if(empty($_REQUEST['shipping_telephone']))
			{
				$error	=	"Enter Phone Number";
				$flag	=	1;
			}
			else if(empty($_REQUEST['current_url']))
			{
				$error	=	"Enter Current Url";
				$flag	=	1;
			}
			else if(empty($_REQUEST['project_type']))
			{
				$error	=	"Enter Project Type";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_country']))
			{
				$error	=	"Enter Country";
				$flag	=	1;
			}
			else if(empty($_REQUEST['billing_state']))
			{
				$error	=	"Enter State";
				$flag	=	1;
			}
			else if(empty($_REQUEST['message']))
			{
				$error	=	"Enter Description";
				$flag	=	1;
			}
			$country = $user->getCountryName($_REQUEST['billing_country']);
			$date = date("F j, Y");
			if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['email'],
											"to"	=>	'preeti.patankar@newagesmb.com',
											"subject" => "Request For Quote");
											
				$dynamic_vars 	=	array(	"NAME"	=>	$_REQUEST['name'],
											 "PHONE"	=>	$_REQUEST['shipping_telephone'],
											 "COUNTRY"	=>	$country['country_name'],
											"STATE"		=>	$_REQUEST['billing_state'],
										"Company Name"  =>	$_REQUEST['company_name'],
										"Current Url"	=>	$_REQUEST['current_url'],
									"Type Of Project"	=>	$_REQUEST['project_type'],
								"Estimated Budget"		=>	$_REQUEST['estimated_budget'],
							"Attached File"				=>  $_REQUEST['file_attach'],
											"DATE"		=>	$date,
										"ADMIN_NAME"	=>	$rs['first_name']." ".$rs['last_name'],
								"Project Description"	=>	trim(stripslashes(nl2br($_REQUEST['message']))));
						$array_send = '<br>Hi,<br><br>';
						$array_send .= '<div style="margin-left: 20px;" ><br>NAME:              '.		$_REQUEST['name'].'<br>';
						$array_send .='PHONE:             '.		$_REQUEST['shipping_telephone'].'<br>';
						$array_send .='COUNTRY:           '.		$country['country_name'].'<br>';
						$array_send .='STATE:             '.		$_REQUEST['billing_state'].'<br>';
						$array_send .='Company Name:      '.    	$_REQUEST['company_name'].'<br>';
						$array_send .='Current Url:       '.		$_REQUEST['current_url'].'<br>';
						$array_send .='Type Of Project:   '.		$_REQUEST['project_type'].'<br>';
						$array_send .='Estimated Budget:  '.		$_REQUEST['estimated_budget'].'<br>';
						$array_send .='Attached File:     '.        $_REQUEST['file_attach'].'<br>';
						$array_send .='DATE:              '.		$date.'<br>';
								//"ADMIN_NAME"	=>	$rs['first_name']." ".$rs['last_name'],
						$array_send .='Project Description:'.		trim(stripslashes(nl2br($_REQUEST['message'].'<br></div>')));	
								 $st_file=basename($_FILES['file_attach']['name']);
								$headers = "From: $mail_header[from]";
								if ($st_file)
								{
								$dir=SITE_PATH."/modules/newsletter/files/";
								uploadFile($_FILES["file_attach"],$dir);
								chmod($dir.$_FILES['file_attach']['name'],0777);
								}
							 	$attached_file =  $dir.$st_file;
							$to      = "vipin@newagesmb.com";
							$from    = $dynamic_vars['NAME']." <".$mail_header['from'].">";
							$subject = "Quote Request";
							$message = "{$array_send}\n";
								if (file_exists($attached_file)) {
							 // Read the file to be attached ('rb' = read binary)
							 $file = fopen($attached_file,'rb');
							 $data = fread($file,filesize($attached_file));
							 fclose($file);
							 }
						  // Generate a boundary string
							 $semi_rand = md5(time());
							 $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
							 
							 // Add the headers for a file attachment
							 $headers .= "\nMIME-Version: 1.0\n" .
										 "Content-Type: multipart/mixed;\n" .
										 " boundary=\"{$mime_boundary}\"";
						 // Add a multipart boundary above the plain message
							 $message = "This is a multi-part message in MIME format.\n\n" .
										"--{$mime_boundary}\n" .
										"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
										"Content-Transfer-Encoding: 7bit\n\n" .
										$message . "\n\n"; 
						  // Base64 encode the file data
							 $data = chunk_split(base64_encode($data));
							 
						 // Add file attachment to the message
							 $message .= "--{$mime_boundary}\n" .
										 "Content-Type: application/pdf;\n" .
										 " name=\"{$attached_file}\"\n" .
										 "Content-Disposition: attachment;\n" .
										 " filename=\"{$st_file}\"\n" .
										 "Content-Transfer-Encoding: base64\n\n" .
										 $data . "\n\n" .
										 "--{$mime_boundary}--\n"; 
									 
										 // Send the message
							$ok = @mail($to, $subject, $message, $headers);
								
															
			//mimeMail($mail_header['to'], $mail_header['subject'], $array_send, $array_send, "", $dynamic_vars['NAME']." <".$mail_header['from'].">","","","true",$attached_file);
			    	setMessage("Your message  sent successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"newsletter", "pg"=>"contactus"),"act=quote_request"));
					
				
		}
		}//if($_SERVER['REQUEST_METHOD'] == "POST")
		
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("STATE_LIST", $objUser->listStateUS());
		//$framework->tpl->assign("BILLING_ADDRESS", $_SESSION['BILLING_ADDRESS']);
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/newsletter/tpl/request_quote.tpl");
		break;
		
		
		case "feedback":
		checkLogin();
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	//$framework->tpl->assign("LEFTBOTTOM","social_community" );
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['your_name']))
			{
				$error	=	"Enter Your Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['your_email']))
			{
				$error	=	"Enter Your Email";
				$flag	=	1;
			}
			else if(empty($_REQUEST['title']))
			{
				$error	=	"Enter Message Title";
				$flag	=	1;
			}
			else if(empty($_REQUEST['message']))
			{
				$error	=	"Enter Your Message";
				$flag	=	1;
			}
			
			if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['your_email'],
											"to"	=>	$rs['email']);
				$dynamic_vars 	=	array(	"YOUR_NAME"	=>	$_REQUEST['your_name'],
											"DATE"		=>	$date,
											"ADMIN_NAME"=>	$rs['first_name']." ".$rs['last_name'],
											"MSG_SUBJECT"=>$_REQUEST['title'],
											"CONTENT"	=>	trim(stripslashes(nl2br($_REQUEST['message']))));
			if($email->send('contact_us', $mail_header, $dynamic_vars))
					{
					setMessage("Your message  sent successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"newsletter", "pg"=>"contactus"),"act=contact"));
					}
				}//if($flag==0)
			else
				{
				setMessage($error);
				}
		}//if($_SERVER['REQUEST_METHOD'] == "POST")
		
	   $framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/newsletter/tpl/contact_us.tpl");
		break;
		
	case "contactus":
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['contact_name']))
			{
				$error	=	"Enter Contact Name";
				$flag	=	1;
			}
			else if(empty($_REQUEST['contact_phone']))
			{
				$error	=	"Enter Contact Phone number";
				$flag	=	1;
			}
			else if(empty($_REQUEST['contact_email']))
			{
				$error	=	"Enter Contact Email";
				$flag	=	1;
			}
			if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['contact_email'],
											"to"	=>	$rs['email']);
				$dynamic_vars 	=	array(	"YOUR_NAME"		  =>	$_REQUEST['contact_name'],
											"COMPANY_NAME"	  =>	$_REQUEST['company_name'],
											"MAILING_ADDRESS" =>	$_REQUEST['mailing_address'],
											"PHONE_NUMBER"	  =>	$_REQUEST['contact_phone'],
											"CONTACT_EMAIL"	  =>	$_REQUEST['contact_email'],
											"ADMIN_NAME"	  =>	$rs['first_name']." ".$rs['last_name'],
											"CONTENT"		  =>	trim(stripslashes(nl2br($_REQUEST['product_description']))));
			if($email->send('contact_us', $mail_header, $dynamic_vars))
					{
					setMessage("Your message  sent successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"newsletter", "pg"=>"contactus"),"act=contactus"));
					}
				}//if($flag==0)
			else
				{
				setMessage($error);
				}
		}//if($_SERVER['REQUEST_METHOD'] == "POST")
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/newsletter/tpl/contact_us.tpl");
		break;
		case "email_to_author":
		$aid=$_REQUEST['id'];
		$memberdet  = $user->getUserdetails($_SESSION['memberid']);
		$article    =  $album->getAlbumDetails($_REQUEST['id']);
		
		$mail_header = array();
		$mail_header['from'] = 	$memberdet['email'];
		$mail_header["to"]   =  $article["author_email"];
		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$dynamic_vars = array();
			$dynamic_vars["CONTENT"] 	= $_REQUEST["mail_msg"];
			$dynamic_vars["YOUR_NAME"] 	= $memberdet["username"];
			$dynamic_vars["ARTICLE"] 	= $article["album_name"];
			if($email->send('email_ to_author', $mail_header,$dynamic_vars))
			{
				setMessage("Your message  sent successfully.", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_details&id=".$_REQUEST['id']));
			}
			else
			{
				setMessage($error);
			}
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/email_to_author.tpl");
		break;
		
	}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>