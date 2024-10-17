<?php
	/**
	* Member Module Language
	*
	* @author Jipson thomas.
	* @package defaultPackage
	*/
	
	
	# ALL LABELS 
	$MOD_LABELS = array ( 
		"LBL_PROPERTY_TITLE"=> "Property Title",
		"LBL_BASIC_PRICE"=> "Basic Price",
		"LBL_SP_PRICE"=> "Special Price",
		"LBL_SP_DTPRICE"=> "Special Date Price",
		"LBL_FROM"=> "From",
		"LBL_TO"=> "To",
		"LBL_IMPORTCONTACT"=> "Import Contacts",
		"LBL_SUB"=> "Subject",
		"LBL_MSG"=> "Message",
		"LBL_PRPTY_NAME"=> "Property Name",
		"LBL_REQUIRED"=> "required",
		"LBL_EG_HTL_RADSON"=> "eg: Hotel Radisson",
		"LBL_ACCOMODATION"=>"Accommodation",
		"LBL_EG_DLXSUITE"=> "eg: Deluxe suite",
		"LBL_DESC"=>"Description",
		"LBL_IFUHAVE_MULT_PRPTY_INSAME"=>"Use this box if you have multiple number of properties within the same Property",
		"LBL_QTY"=>"Quantity",
		"LBL_REM_TITLE"=>"Remove Title",
		"LBL_ADD_TITLE"=>"Add Title",
		"LBL_MULYUNIT_SAMEPROPERTY"=>"( Only put a quantity of more than 1 if you have multiple units within the same property with the exact same features. If you have other units at this address with different unit features, please create a new property for each different unit. <strong>for example:</strong> if you have 2 1 bedroom units in the same property, choose quantity:2 if you have 2 units in the same property but one unit is a 1 bedroom and the other is a 2 bedroom, choose quantity:1 and create another property for the other unit. )",
		"LBL_TITLE"=>"Title",
		"LBL_FREE_FLOATING_PRICE_CALENDER"=> "Free Floating Pricing Calendar",
		"LBL_ENTER_BASIC_PRICE"=> "Enter your Basic Price for this Property and duration for that price",
		"LBL_RESTRICT_PROPERTY_BOOKDAYS"=> "Restrict your property from being booked in advance before a specific number of days",
		"LBL_MIN_BOOKLENGTH"=>"Minimum Booking Length",
		"LBL_MAX_BOOKLENGTH"=>"Maximum Booking Length",
		"LBL_MIN_RENTALDUR"=>"Minimum Rental Duration",
		"LBL_BOOKDEPOSIT"=>"Booking Deposit",
		"LBL_DATE_U_BLOCK_PROPERTY"=>"Click and drag this over the dates you would like to block your property from being booked on",
		"LBL_FIXCALENDERRATE"=>"Fixed Calendar Rates",
		"LBL_ADDFIXCALENDERRATE"=>"Add Fixed Calendar Rates",
		"LBL_SELECT_COLOR_BLOCK"=>"Select a color and Block dates in the Calender",
		"LBL_STARTDATE"=>"START DATE",
		"LBL_ENDDATE"=>"END DATE",
		"LBL_PRICE"=>"PRICE",
		"LBL_BLOCKDATE"=>"Blocked Dates",
		"LBL_DATEFROM"=>"Date from",
		"LBL_DATETO"=>"Date To",
		"LBL_PRICE_DURATION"=>"Price/Duration",
		"LBL_UR_SEARCH4"=>"Your search for",
		"LBL_DIDNTMATCHLOCATION"=>"did not match any locations",
		"LBL_CLOSE"=>"Close",	
		"LBL_PLEASEWAIT"=>"Please wait ... Searching",	
		"LBL_SWITCH_PROP_POSITION"=>"Switch Property Position via LOCATION INFORMATION",	
		"LBL_ACCOMODATION"=>"Accommodation",	
		"LBL_STREETADD"=>"Street Address",
		"LBL_STATE"=>"State",	
		"LBL_COUNTRY"=>"Country",	
		"LBL_ZIP"=>"Zip",
		"LBL_PCODE"=>"Postalcode",	
		"LBL_CITY"=>"City",	
		"LBL_NEIGHBORHOOD"=>"Neighborhood",	
		"LBL_NAME"=>"Name",	
		"LBL_FNAME"=>"First Name",
		"LBL_LNAME"=>"Last Name",
		"LBL_PHONE"=>"Phone",
		"LBL_PHONENUM"=>"Phone Number",	
		"LBL_ADD"=>"Address",	
		"LBL_EMAIL"=>"Email",	
		"LBL_REPLYFROM"=>"Replay From",	
		"LBL_EMAILON"=>"Email On",
		"LBL_EMAILUR"=>"Email Your",
		"LBL_SHOW"=>"Show",
		"LBL_VIDEO"=>"Video",
		"LBL_PHOTO"=>"Photo",
		"LBL_MAP"=>"Map",
		"LBL_VIEWREC"=>"View Record",
		"LBL_LIST"=>"List",
		"LBL_EMAILCPTION"=>"Type in the email addresses of the contacts you would like to forward this",
		"LBL_ACTVTRPORT"=>"Activity Report",	
		"LBL_FREEFLOATING_PRICECALENDER"=>"Free Floating Pricing Calendar",		
		"LBL_PRICE"=>"PRICE",
		"LBL_MODIFYSEARCH"=>"Modify Search",
		"LBL_SELECTVIDEO"=>"Select Video",
		"LBL_BORGERG"=>"Borgrege",
		"LBL_GRUP4EVER"=>"Groups for ever",
		"LBL_4INPOLICY"=>"Forgin Policy",
		"LBL_FUTBOL"=>"Foot ball",
		"LBL_MOREVIDEOS"=>"More Videos...",
		"LBL_BIGANKERS"=>"Big Tankers",
		"LBL_SINO"=>"SI No.",
		"LBL_ALBMTITLE"=>"Album Title",
		"LBL_SMLPRICE"=>"Price",
		"LBL_CHKDT"=>"Check-Date",
		"LBL_CHKIN"=>"Check In",
		"LBL_CHKOUT"=>"Check Out",
		"LBL_CHOOSE"=>"Choose",
		"LBL_URDEST"=>"Your Destination",
		"LBL_PROPTYPE"=>"Property Type",
		"LBL_AMENTITE"=>"Amentities",
		"LBL_GETRATE"=>"Get Rate",
		"LBL_DET"=>"Details",
		"LBL_ADDFAVT"=>"Add To Favourite",
		"LBL_LISTPROPBETWNPRICE"=>"List Properties between prices",
		"LBL_BOOKPRICE"=>"Booking Price",	
		"LBL_DURATION"=>"DURATION",
		"LBL_MINDURATION"=>"Minimum DURATION",
		"LBL_UNIT"=>"UNIT",
		"LBL_FIXCALENDERRATE"=>"Fixed Calendar Rates",
		"LBL_STARTBID"=>"START BID",
		"LBL_RESERVEBID"=>"RESERVE BID",
		"LBL_AUCTIONEND"=>"AUCTION ENDS",
		"LBL_SHOWMAP"=>"Show Map",
		"LBL_SHOWADD"=>"Show Address",
		"LBL_QTY_TITLE"=>"Qty Title",
		"LBL_CONTMNGR"=>"Contact Manager",
		"LBL_MNGRCOMM"=>"Manager Commission",
		"LBL_DEPOSITFEE"=>"Deposit Fee",
		"LBL_VIEW"=>"View",
		"LBL_RATING"=>"Rating",
		"LBL_NORATING"=>"No Rating",
		"LBL_AVAIL"=>"Availability",
		"LBL_CONTOWNER"=>"Contact Owner",
		"LBL_CHKPRICE"=>"Check Price",
		"LBL_OWNINFO"=>"Owner Informations",
		"LBL_UINFO"=>"Your Informations",
		"LBL_PROPASSIGNED2"=>"Property Assigned To",
		"LBL_ALLPROPASSIGNED2"=>"Property Already Assigned To",
		"LBL_BUTNOTACCEPT"=>"But Not Accepted",
		"LBL_NOPUBPROP"=>"No Published Property Found",
		"LBL_PUBPROP2BROK"=>"Publish the property in order to assign the property to Broker",	
		"LBL_UHAV2PAYFEE2BROK"=>"You have to pay the Deposit Fee in order to assign property to a Broker",	
		"LBL_CONTBRKR"=>"Contact Broker",
		"LBL_ABTBRKR"=>"About Broker",
		"LBL_BRKRSEARCHFORMNULLMSG"=>"<p class='bodytext'>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Ut tortor felis, tempor sed, commodo vel, ultrices sit amet, felis. Fusce metus est, varius eget, venenatis id, placerat at, dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Integer eu justo quis odio imperdiet cursus. Aliquam scelerisque ipsum in odio. Duis tortor metus, sagittis a, feugiat vitae, eleifend lobortis, lacus. Ut in ipsum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum varius, lorem a tincidunt ornare, pede dolor semper tellus, sit amet ullamcorper ligula urna quis sapien. Donec augue massa, malesuada a, varius a, iaculis vel, lacus.</p>
  <p class='bodytext'>Aliquam hendrerit. Morbi sodales erat nec nibh. Sed nisl. Vestibulum elit velit, volutpat ac, imperdiet eget, rhoncus ac, tellus. In convallis rutrum erat. Donec leo sapien, faucibus eu, bibendum eu, nonummy a, sem. Sed dictum dolor ullamcorper lacus tempus facilisis. Pellentesque fermentum felis sit amet augue. Phasellus nulla. Sed lectus. Nulla scelerisque sem vel metus. Pellentesque nibh pede, vulputate auctor, ultricies in, iaculis quis, dui. Vivamus nonummy tortor sit amet dui. Mauris viverra leo quis metus.</p>
  <p class='bodytext'>Sed dui sem, tristique vitae, adipiscing sit amet, placerat in, ipsum. Mauris consectetuer turpis sit amet tortor. Donec ac quam ut orci tristique accumsan. Vivamus sed diam eget mauris scelerisque condimentum. Pellentesque porttitor nisi sed dolor. In placerat tincidunt est. Maecenas nec leo sit amet pede sodales tempus. Donec sed quam. Praesent consectetuer. Proin felis est, lobortis sit amet, congue nec, adipiscing vitae, neque. Nullam nunc dolor, bibendum in, posuere pharetra, consectetuer quis, dolor. Cras sit amet arcu id sapien fringilla accumsan. Aliquam erat volutpat. Suspendisse porttitor lobortis enim. Praesent auctor tincidunt odio. Proin sit amet nulla ut lacus tempor feugiat. Cras lorem dui, luctus id, blandit id, venenatis sit amet, ligula. Morbi sapien.<br />  </p>",
  		"LBL_BRKRSEARCHRESULTNULLMSG"=>"No Brokers Found",
		"LBL_BRKRCOMM"=>"Broker Commission",
		"LBL_SEARCHBRKR"=>"Searching Brokers",
		"LBL_THX"=>"Thanks",
		"LBL_EDT"=>"Edit",
		"LBL_CRAIGLISTCODE"=>"Craigslist Code",
		"LBL_EBAYCODE"=>"Ebay Code",
		"LBL_HTMLCODE"=>"HTML Code",
		"LBL_SEARCHBRKR_RESULT"=>"Broker Search Result",
		"LBL_OUTPUT"=>"Output",
		"LBL_CPY2CLPBORD"=>"Copy To Clipboard",
		"LBL_PRVW"=>"Preview",
		"LBL_NOTE"=>"Note",
		"LBL_ADDATRIB"=>"Add Atributes",
		"LBL_ADDFEATURE"=>"Add Features",
		"LBL_EMAILNOTE"=>"Use commas or returns to seperate multiple email addresses",
		"LBL_SETUPPHOTOGALLERY"=>"Setup Photo Gallery",
		"LBL_UPDATEMAINPHOTO"=>"Update Main Photo",
		"LBL_ADDLINK"=>"Add Link",
		"LBL_PUBLISH"=>"Publish",
		"LBL_UNPUBLISH"=>"Unpublished",
		"LBL_EXPDAT"=>"Expiry Date",
		"LBL_NOPROPFOUND"=>"No Properties Found",
		"LBL_NOPHOTO"=>"No Photos",
		"LBL_NOVIDEO"=>"No Videos",
		"LBL_NOREC"=>"No Records",
		"LBL_CREATPROP"=>"Create A Property",
		"LBL_SEARCHKEY"=>"Search Keywords",
		"LBL_DIST2LANDMARK"=>"Distance to Landmark",
		"LBL_MILES4M"=>"Miles From",
		"LBL_VIDEODISPLAYMSG"=>"232<span class='grayboltext'> of </span>480<span class='grayboltext'> shown (</span><span class='orange2'>show all</span><span class='grayboltext'>) - </span><span class='bodytext'>New York, NY, Tue 8th Dec 2007 1 guest in 1 room</span><span class='grayboltext'>",
		"LBL_EXPMSG"=>"are set by default to expire in 30 days.<br>
      &nbsp;Remove date value if you want to disable auto expiry"
	);
	
	
	# ALL ERRORS
	$MOD_ERRORS = array ( 
		"ERR_EMPTY"=> "Field Empty"
	);
	
	
	# ALL HEADINGS
	$MOD_HEADS = array ( 
		"HD_MAPS"=> "MAPS",
		"HD_DESC"=> "DESCRIPTION",
		"HD_PROPERTY_INFO"=> "PROPERTY INFORMATION",
		"HD_PRICE_INFO"=> "PRICE INFORMATION",
		"HD_PROPERTY_ID"=> "PROPERTY ID",
		"HD_PROPERTY_DESC"=> "PROPERTY DESCRIPTION",
		"HD_QTY_INFO"=> "QUANTITY INFORMATION",
		"HD_AD_BOOKINGDAYS"=> "ADVANCE BOOKING DAYS",
		"HD_BLOCK_PROP"=> "BLOCKING YOUR PROPERTY",
		"HD_BLOCK_DATE"=> "BLOCKED DATES",
		"HD_CONFORMPROPERTYLOCATIONMAP"=> "CONFIRM PROPERTY LOCATION ON MAPS",
		"HD_PROP_INFO"=> "PROPERTY INFORMATION",
		"HD_LOC_INFO"=> "LOCATION INFORMATION",
		"HD_CONTACT_INFO"=> "CONTACT INFORMATION",
		"HD_PRICE_INFO"=> "PRICE INFORMATION",
		"HD_PHOTOS"=> "PHOTOS",
		"HD_VIDEOS"=> "VIDEOS",
		"HD_PHOTOGRPH"=> "PHOTOGRAPHS",
		"HD_ROL"=> "ROLE",
		"HD_OWN"=> "OWNER",
		"HD_MYCALNDR"=> "My Calender",
		"HD_AVAILCALENDER"=> "Avialability Calendar",
		"HD_COD4CARGLST"=> "CODE FOR CRAIGSLIST",
		"HD_COD4EBAY"=> "CODE FOR EBAY",
		"HD_EMAILADD"=> "EMAIL ADDRESSES",
		"HD_TMPLTLUKFEEL"=> "TEMPLATE (LOOK-AND-FEEL)",
		"HD_LINKS"=> "LINKS",
		"HD_SETTINGS"=> "SETTINGS"
	);
	
	
	# ALL HINTS
	$MOD_HINTS = array ( 
		"HT_TEST"=> "Test Name"
	);
	
	
	# ALL BUTTON TEXT
	$MOD_BUTTONS = array ( 
		"BT_TEST"=> "Test Name"
	);

		
	# PARENT ASSOCIATIVE ARRAY
	$MOD_VARIABLES = array ("MOD_LABELS"=>$MOD_LABELS,"MOD_ERRORS"=>$MOD_ERRORS,"MOD_HEADS"=>$MOD_HEADS,"MOD_HINTS"=>$MOD_HINTS,"MOD_BUTTONS"=>$MOD_BUTTONS);

?>