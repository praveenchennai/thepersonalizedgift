<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/callendar/callendar.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/callendar/dom-drag.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/popup_new.js"></script>
<script language="javascript" type="text/javascript">
{literal}
var pri_id=0;
function placeBid()
{
	var bid_amt = document.getElementById('bid_amt').value;
	var start_bid_amt = document.getElementById('start_bid'+pri_id).value;
	{/literal}
	var user_id = '{$smarty.session.memberid}';
	var numeric_msg = '{$MOD_VARIABLES.MOD_JS.JS_BID_NUMERIC}';
	var sess_exp_msg = '{$MOD_VARIABLES.MOD_JS.JS_BID_SESSION_EXP}';
	var start_bid_msg = '{$MOD_VARIABLES.MOD_JS.JS_START_BID}';
	{literal}
	if  ( (bid_amt=='')|| (isNaN(bid_amt)))
	{
		alert(numeric_msg);
		document.getElementById('bid_amt').focus();
		return false;
	}
	else if(eval(bid_amt)<eval(start_bid_amt))
	{
		alert(start_bid_msg);
		document.getElementById('bid_amt').focus();
		return false;
	}
	else if(user_id=="")
	{
		alert(sess_exp_msg);
		return false;
	}
	document.getElementById('submit_div').innerHTML = "<span class='bodytext'>Please wait....</span>" ;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResponseBid);
{/literal}

str = "pricing_id="+ pri_id+"&bid_amount="+bid_amt+"&user_id="+user_id;

			//str="year="+year+"&month="+month+"&day="+day+"&type=ajax&propid={$smarty.request.propid}";
			req1.open("POST", "{makeLink mod=flyer pg=ajax_flyer}act=place_bid{/makeLink}&"+Math.random());
{literal}
			req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
		}
function serverResponseBid(_var) {
		_var = _var.split('|');
		if (_var[1]==0)
		{
			document.getElementById('submit_div').innerHTML = "<strong class='ajxMessage-normal'>" +  _var[0] + "</strong>";
		}
		else
		{
popupPosition_Hide('nameFieldPopup');hideShow(false);
			document.getElementById('bid_err_div').innerHTML = "<strong class='ajxMessage-green'>" +  _var[0] + "</strong>";
			document.getElementById('max_bid'+pri_id).value = _var[2];
			document.getElementById('last_bid'+pri_id).value = document.getElementById('bid_amt').value;
		}
		//javascript:popupPosition_Hide('nameFieldPopup');hideShow(false);
		
}

function showPopup(name,evt,startDt,endDt,grtVal,pr_id,max_bid)
{
	{/literal}
	var start_bid_lbl = '{$MOD_VARIABLES.MOD_LABELS.LBL_START_BID}';
	var high_bid_lbl = '{$MOD_VARIABLES.MOD_LABELS.LBL_HIGH_BID}';
	var no_bid_lbl = '{$MOD_VARIABLES.MOD_LABELS.LBL_NO_BID}';
	var sub_bid_txt = '{$MOD_VARIABLES.MOD_BUTTONS.BT_SUBMIT_BID}';
	var upd_bid_txt = '{$MOD_VARIABLES.MOD_BUTTONS.BT_UPDATE_BID}';
	var hd_bid_box  = '{$MOD_VARIABLES.MOD_HEADS.HD_BID_BOX}';
	var hd_bid_box_upd  = '{$MOD_VARIABLES.MOD_HEADS.HD_BID_BOX_UPD}';
	var enter_bid = '{$MOD_VARIABLES.MOD_LABELS.LBL_ENTER_BID}';
	var upd_bid = '{$MOD_VARIABLES.MOD_LABELS.LBL_UPD_BID}';
	var bt_text = "";
	{literal}
	document.getElementById('divDt1').innerHTML=startDt;
	document.getElementById('divDt2').innerHTML=endDt;
	document.getElementById('divStart').innerHTML = "<strong>"+start_bid_lbl+"</strong> $"+grtVal;
	var	max_bid = document.getElementById('max_bid'+pr_id).value;
	var	last_bid = document.getElementById('last_bid'+pr_id).value;
	var disp_msg="";
	
	(max_bid>0) ? disp_msg = "<strong>"+high_bid_lbl+"</strong> $" + max_bid  : disp_msg= "<strong>"+no_bid_lbl+"</strong>";
	
	document.getElementById('grtDiv').innerHTML=disp_msg;
	
	if (last_bid>0)
	{
		document.getElementById('head_upd').innerHTML = hd_bid_box_upd;
		document.getElementById('bid_text').innerHTML = upd_bid;
		document.getElementById('bid_amt').value=last_bid;
		bt_text = upd_bid_txt;
	}
	else
	{
		document.getElementById('head_upd').innerHTML = hd_bid_box;
		document.getElementById('bid_text').innerHTML = enter_bid;
		document.getElementById('bid_amt').value="";
		bt_text = sub_bid_txt;
	}
		
	
	document.getElementById('submit_div').innerHTML = "<input  name='place_bid' type='button' value='"+bt_text+"' class='button_class' style='width:80px;'  onclick='placeBid();'/>";
	pri_id = pr_id;
	popupPosition_visible(name,evt)
}

function showPlusBlockedCont_Price(id) {
	if(document.getElementById('splitBlockPriceDiv_'+id).style.display=='none'){
	   document.getElementById('splitBlockPriceDiv_'+id).style.display='inline';
	   //document.getElementById('bidDivSh1_'+id).style.display='none';
	    //document.getElementById('bidDivSh2_'+id).style.display='none';
	   
	   document.getElementById('plusminusPriceDiv_'+id).innerHTML = "[-]";
   }else{
       document.getElementById('splitBlockPriceDiv_'+id).style.display='none';
	   //document.getElementById('bidDivSh1_'+id).style.display='inline';
	  // document.getElementById('bidDivSh2_'+id).style.display='inline';
	   document.getElementById('plusminusPriceDiv_'+id).innerHTML = "[+]";
   }
}
/* Tree View of different Block of Blocked Property */
	function showPlusBlockedDate() {
		
		
		if(document.getElementById('splitBlockdDiv_1').style.display=='none'){
		   document.getElementById('splitBlockdDiv_1').style.display='inline';
		   document.getElementById('plusblockedDiv_1').innerHTML = "[-]";
	   }else{
		   document.getElementById('splitBlockdDiv_1').style.display='none';
		   document.getElementById('plusblockedDiv_1').innerHTML = "[+]";
	   }
	}

	function popertyPhotoLoad(id){	
			var photoArr = new Array();
		{/literal}
			{foreach from=$PHOTO_LIST item=rowPh key = index}
				photoArr[{$index}] = '{$smarty.const.SITE_URL}/modules/album/photos/resized/{$rowPh.id}{$rowPh.img_extension}';
			{/foreach}
		{literal}
			document.imgbig.src= photoArr[id];
			
			 document.getElementById('photoval').value=id;
	   document.getElementById('startID').innerHTML=eval(id) +1;
		if(document.getElementById('startID').innerHTML>1)
		{
	    document.getElementById('pervious').style.display='block';
		 document.getElementById('hide').style.display='none';
	    }else
		{
		document.getElementById('pervious').style.display='none';
		 document.getElementById('hide').style.display='block';
		}
		
		if(id==cnt-1)
		{
		document.getElementById('next').style.display='none';
		//document.getElementById('photoval').value= eval(document.getElementById('photoval').value) - 1;
		}else
		{
		document.getElementById('next').style.display='block';
		}
			
			
			
}


{/literal}
	

	{foreach from=$PHOTO_LIST item=rowPh key=index}
	
		pic{$index}= new Image(); 
		pic{$index}.src='{$smarty.const.SITE_URL}/modules/album/photos/resized/{$rowPh->id}{$rowPh->img_extension}';
	
		
	{/foreach}

	picDefault = new Image(); 
	picDefault.src = '{$smarty.const.SITE_URL}/modules/album/photos/resized/{$DEFAULT_IMG}{$DEF_IMG_EXT}';
	
	{foreach from=$VIDEO_LIST item=rowVh name =foo key=index}
		picVd{$index} = new Image();
		picvd{$index} = '{$smarty.const.SITE_URL}/modules/album/video/thumb/{$rowVh.id}.jpg"';
	{/foreach}
{literal}



function redirectVideoDisplay(id){
{/literal}
	document.getElementById("ifvdo").src = '{makeLink mod=flyer pg=list}act=video&vdoid='+id+'&propid={$smarty.request.propid}{/makeLink}';
{literal}
}
function showAvialabilityCalShow(){
	document.getElementById("avCal").style.display="block";
	document.getElementById("calCap").innerHTML = "<a href=\"javascript:void(0);\" class=\"mediumlink\" onClick=\"showAvialabilityCalHide();\">Hide</a>";
}
function showAvialabilityCalHide(){
	document.getElementById("avCal").style.display="none";
	document.getElementById("calCap").innerHTML = "<a href=\"javascript:void(0);\" class=\"mediumlink\" onClick=\"showAvialabilityCalShow();\">Show</a>";
}

		function dispMonthView(year,month,day) 
		{
			document.getElementById("loadCal").style.display="inline";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverRese2);
{/literal}
			str="year="+year+"&month="+month+"&day="+day+"&type=ajax&propid={$smarty.request.propid}";
			req1.open("POST", "{makeLink mod=album pg=booking}act=mycalendar_month{/makeLink}&"+Math.random());
{literal}
			req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
		}
		function serverRese2(_var) {
				_var = _var.split('|');
				document.getElementById("calendar").innerHTML = _var[0];
				document.getElementById("loadCal").style.display="none";
		}
		
		function dispYearView(caseval) 
		{
			document.getElementById("loadCal").style.display="inline";
			if(caseval == "")
			dates = "";
			
			if (caseval == 'n'){
			var dates = document.getElementById('hidnext_calendarList');
			}
			else if(caseval == 'p'){
			var dates = document.getElementById('hidprev_calendarList');
			}
		
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverRese3);
{/literal}
			if(dates == "")
			str="type=ajax&propid={$smarty.request.propid}";
			else
			str="dates="+dates.value+"&type=ajax&propid={$smarty.request.propid}";
			
			req1.open("POST", "{makeLink mod=album pg=booking}act=mycalendar_year{/makeLink}&"+Math.random());
{literal}
			req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			req1.send(str);
			
			
		}
		function serverRese3(_var) {
		 _var = _var.split('|');
		 document.getElementById("calendar").innerHTML = _var[0];
		 document.getElementById("loadCal").style.display="none";
		}
				
		function movetoAvialable(){
			//window.location.href="";
			window.location.href = "{/literal}{makeLink mod=album pg=booking}act=booking_payment&propid={$smarty.request.propid}{/makeLink}{literal}";
		}	
		function viewBidList()
		{
			if(document.getElementById("bid_view").style.display=='none')
			document.getElementById("bid_view").style.display="block";
			else
			document.getElementById("bid_view").style.display="none";
			
		}
		
function showreview()
 {
 
 	 document.getElementById('loadDyn2').style.display="inline";
	
	 e= document.getElementById('loadDyn1').event;
	
 }		
 function popupHide_dragOpt(ELEM,defcolor)
  {
	/* Clear Selected Range */
	document.getElementById(ELEM).style.display='none'; 
	return true;
 }
 function pagination(page,limit,orderBy,opt)
{
var propid=document.getElementById('propid').value;
var req1  = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverReseResult);
	str	 = "pageNo="+page+"&limit="+limit+"&orderBy="+orderBy+"&prptyid="+propid;
	

	{/literal}
	req1.open("POST", "{makeLink mod=flyer pg=list}act=feedback_list_ajax{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);

}	
function serverReseResult(_var)
{
//alert(_var);
//document.getElementById('feedbackdiv').innerHTML ='';
//alert(document.getElementById('feedbackdiv').innerHTML);
document.getElementById('feedbackdiv').innerHTML = _var; 

}

function chgPhoto(id,cnt,rtststus)
{

	   if(rtststus==1)
	   {
	   document.getElementById('photoval').value= eval(document.getElementById('photoval').value) + 1;
	   document.getElementById('startID').innerHTML=parseInt(document.getElementById('startID').innerHTML) +1;
	   document.getElementById('pervious').style.display='block';
	    document.getElementById('hide').style.display='none';
	   }
	   else{
		document.getElementById('photoval').value= eval(document.getElementById('photoval').value)-1 ;
		
		document.getElementById('startID').innerHTML=parseInt(document.getElementById('startID').innerHTML) -1;
		
		document.getElementById('next').style.display='block';
		}
		
  
	id=document.getElementById('photoval').value;
	//alert(id);
	
		if(id==cnt-1)
		{
		document.getElementById('next').style.display='none';
		//document.getElementById('photoval').value= eval(document.getElementById('photoval').value) - 1;
		}
		if(document.getElementById('startID').innerHTML==1)
		{
		 document.getElementById('pervious').style.display='none';
		   document.getElementById('hide').style.display='block';
		 
		}
	
	
	var photoArr = new Array();
	{/literal}
	{foreach from=$PHOTO_LIST item=rowPh key = index}
			photoArr[{$index}] = '{$smarty.const.SITE_URL}/modules/album/photos/resized/{$rowPh.id}{$rowPh.img_extension}';
		{/foreach}
	{literal}
		document.imgbig.src= photoArr[id];
	
	
}

				
{/literal}
</script>
<link type="text/css" href="{$GLOBAL.tpl_url}/css/star_rating.css" rel="stylesheet" />
<div align="center">
<form name="frmQty" method="post" action="" style="margin:0px;">
	<input type="hidden" name="flyer_id" value="{$smarty.request.flyer_id}" />
   <input type="hidden" name="theQty" id="theQty" value="0"/>
   <input type="hidden" name="propid" id="propid" value="{$smarty.request.propid}" />
   <input type="hidden" id="photoval" name="photoval" value="0">
<div align="center" style="border:1px solid #afafaf;width:910px;">

	<div class="divSpc"></div>
<div style="width:96%">

	<div class="sepertor10px"><!-- --></div>
	<div style="width:100%;">
		<div class="floatleft meroon" style="width:80%;" id="divError" align="left"><a href="{makeLink mod=flyer  pg=flyer}act=view_property&parent_id={$smarty.request.parent_id}&propid={$smarty.request.propid}&sId={$smarty.request.sId}&details={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}">View Property Booking </a></div>
		<!--<div class="floatright"><input name="" type="button" value="Add to advertisement" class="button_class" onClick="AddtoAdvertisement();"></div>-->
		<div class="floatright"><a href="{makeLink mod=flyer  pg=flyer}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&details={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}">Property List</a>
	</div>
	<div class="sepertor10px"><!-- --></div>

	<div class="divSpc"></div>
	
	<!-- Map Details STRAT ************************* -->
	{if $CHECK!=det}
	<div style="width:100%">
		<div class="divSpc"></div>
		<div>{if file_exists($MAP)} {include file="$MAP"}{/if}</div>
		<div class="divSpc"></div>
	</div>
	{/if}
	<!-- Map Details END  ************************* -->

	<div class="sepertor10px"><!-- --></div>
	<div class="sepertor10px"><!-- --></div>

	<!-- Property Information START HERE--->
	<div style="width:99%">
	
		<div style="height:40px" class="naFooter">
			<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%" align="left"><span class="naGridTitle">PROPERTY INFORMATION</span></div>
			<div class="floatright" style="margin-top:15px;margin-left:10px;width:48%" align="right"><span style="margin-right:10px;"></span></div>
		</div>
		<div class="sepertor10px"><!-- --></div>
		
		<div class="floatleft">{if $DEFAULT_IMG eq "" || $DEF_IMG_EXT eq ""}<img  src="{$GLOBAL.tpl_url}/images/no_image.jpg">{else}<img  src="{$smarty.const.SITE_URL}/modules/album/photos/thumb/{$DEFAULT_IMG}{$DEF_IMG_EXT}">{/if}</div>
		<div class="floatleft">&nbsp;&nbsp;</div>
		
		                        <div class="floatleft" style="width:447px;">
									<div style="text-align:left;width:100%;"><span class="meroon">{$FLYER_BASIC.flyer_name}</span></div>
									{if $FLYER_BASIC.title!=''}
									<div class="floatleft">
									<div style="width:415px;text-align:left;width:100%;"><span class="bodytext"><i>({$FLYER_BASIC.title})</i></span></div>
									</div>
									{/if}
									<div style="float:left;width:100%;text-align:justify;vertical-align:top;" ><span class="bodytext" style="text-align:justify;">{$FLYER_BASIC.description}</span></div>
								</div>
								
								<div style="float:right;vertical-align:top;width:188px;height:135px;">
									
												<div  style="width:187px;vertical-align:top; border:1px solid #6D0506;" align="right">
													<div class="sepertor5px"><!-- --></div>
														<div style="width:170px" align="left" class="browntext">{$MOD_VARIABLES.MOD_LABELS.LBL_RANK} :<span class="bodytext"> {$RANK} </span></div>
														<div class="sepertor10px"><!-- --></div>
														<div style="width:170px" align="left" class="browntext">{$MOD_VARIABLES.MOD_LABELS.LBL_SCORE} :<span class="bodytext"> {$SCORE} </span></div>
														<div class="sepertor10px"><!-- --></div>
														<div style="width:170px; ">
															<div style="float:left; width:60px;"  align="left" class="browntext">{$MOD_VARIABLES.MOD_LABELS.LBL_RATING} :&nbsp;</div>
																	<div align="left" class="bodytext" >
																	{if $PRATE!=''}
																		<ul class="star-rating small-star">
																						<li class="current-rating" style="width:{$PRATE}%">Currently {$PRORATE}/5 Stars.</li>
																						<li><a title="1 stars out of 1" >1</a></li>
																						<li><a title="2 stars out of 2" >2</a></li>
																						<li><a title="3 stars out of 3" >3</a></li>
																						<li><a title="4 stars out of 4" >4</a></li>
																						<li><a title="5 stars out of 5" >5</a></li>
																					  </ul>
																		{else}
																		No Ratings
																		{/if}
																	</div>
															
														</div>
														{if $COUNTS >0}
														<div style="width:170px" align="left" class="browntext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="bodytext" onClick="showreview();" style="cursor:pointer"><font color="#999933">{$COUNTS} {$MOD_VARIABLES.MOD_LABELS.LBL_REVIEW}</font></span></div>
														{/if}
														<div class="sepertor10px"><!-- --></div>
														<div style="width:170px" align="left" class="browntext">{$MOD_VARIABLES.MOD_LABELS.LBL_EARNYEAR} :<span class="bodytext">{if $YEAREARN.yearamt > 0} $ {$YEAREARN.yearamt}{else} $ 0 {/if}</span></div>
														<div class="sepertor10px"><!-- --></div>
														<div style="width:170px" align="left" class="browntext">{$MOD_VARIABLES.MOD_LABELS.LBL_EARNALL} :<span class="bodytext">{if $TOTEARN.totamt > 0} $ {$TOTEARN.totamt}{else} $ 0 {/if}</span></div>
														
																		
													<div class="sepertor5px"><!-- --></div>
												  </div>
										 </div>
								
								
								
								
								
		<div class="divSpc"></div>
	</div>
		<div class="sepertor10px"><!-- --></div>
		<div class="sepertor10px"><!-- --></div>
	<!-- Property Information END HERE--->
	
	<!-- PHOTO LISTING START HERE -->
	 <div style="width:100%;">

	 	<div style="height:40px;" class="naFooter">
		<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%;" align="left"><span class="naGridTitle">PHOTOS</span></div>
		<div class="floatright" style="margin-top:15px;margin-left:10px;width:48%" align="right"><span style="margin-right:10px;"></span></div>

		</div>
		
		
		<div class="sepertor10px"><!-- --></div>
			{if $DEFAULT_IMG > 0}
			<div style="float:left;width:480px;height:310px; ">
					<div  style="width:480px;height:310px; " >
						{if $DEFAULT_IMG eq "0"}<img  src="{$GLOBAL.tpl_url}/images/imgNoPhoto1.gif">{else}<img  src="{$smarty.const.SITE_URL}/modules/album/photos/resized/{$DEFAULT_IMG}{$DEF_IMG_EXT}" name="imgbig">{/if}
					</div>
					
					{if $PCOUNT >1}
						<div style="width:480px; vertical-align:top;" align="center"  >
						
							<div style="width:250px; " align="center" >
									<div style="float:left;" class="meroon" id="hide">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div style="float:left; display:none" class="meroon" id="pervious"><a onClick="javascript:chgPhoto(document.getElementById('photoval').value,{$PCOUNT},-1)" style="cursor:pointer" class="meroon" >&lt; Previous</a> |</div>
									<div style="float:left; width:70px;" class="meroon"><span id="startID">1</span> of <span id="endID" >{$PCOUNT}</span></div>
									<div class="meroon" id="next" style="width:80px; float:left;">| <a onClick="javascript:chgPhoto(document.getElementById('photoval').value,{$PCOUNT},1)" style="cursor:pointer" class="meroon" >Next &gt;</a></div>
						   </div>
					   </div>
					{/if}
			</div>
				<div class="floatright" style="width:390px;">
				{foreach from=$PHOTO_LIST item=rowPh name =foo key=id}
							
							{if $smarty.foreach.foo.index is div by 3}
							<div class="divSpc"></div>
							<div class="sepertor10px"><!-- --></div>
							{/if}

					<div class="floatleft" style="width:130px;"><img  src="{$smarty.const.SITE_URL}/modules/album/photos/thumb/{$rowPh.id}_thumb2{$rowPh.img_extension}" onClick="return popertyPhotoLoad('{$id}',{$PCOUNT})" style="cursor:pointer;"></div>
				{/foreach}
			</div>
			
			{/if}
		<div class="divSpc" ></div>
		<div style="height:15px"><!-- --></div>
		
	</div>
	<!-- PHOTO LISTING END HERE -->
	
	
	
	{if $CHECK!=det}
	<!-- Contact and Location Information START HERE-->
	<div style="width:100%">
	
		<div style="height:40px" class="naFooter">
			<div class="floatleft" style="margin-top:15px;margin-left:10px;width:50%;" align="left"><span class="naGridTitle">LOCATION INFORMATION</span></div>
			<div class="floatleft" style="margin-top:15px;margin-left:10px;" align="left"><span class="naGridTitle">CONTACT INFORMATION</span></div>
			<div class="floatright" style="margin-top:15px;"><span style="margin-right:10px;"></span></div>
		</div>
		
		<div class="divSpc"></div>
		<div class="floatleft">&nbsp;&nbsp;&nbsp;&nbsp;</div>
		
		<div class="floatleftwidth400normal" >
		
			<div>&nbsp;</div>
			<div style="float:left; height:78px; width:90px; vertical-align:top;"><span class="meroon" >Address :&nbsp; </span></div>
			{if $CHECK!=det}
			<div align="left">
				<div  ><span class="bodytext">{$FLYER_BASIC.location_street_address}</span></div>
				<div ><span class="bodytext">{$FLYER_BASIC.location_city},</span></div>
				<div  ><span class="bodytext">{$FLYER_BASIC.location_state},</span></div>
				<div><span class="bodytext">{$FLYER_BASIC.location_country},</span></div>
				<div ><span class="bodytext">{$FLYER_BASIC.location_zip}.</span></div>
			</div>
			{else}
			
				<div style="background-image:url({$GLOBAL.tpl_url}/images/blocked_loc.jpg); background-repeat:no-repeat; height:92px; width:231px; float:left;">
				  <div style="height:70px; width:180px;text-align:center;padding-top:25px;" class="grayboltext1">{$MOD_VARIABLES.MOD_LABELS.LBL_BLOCK}</div>						
				</div>
				
			
			{/if}
		</div>
		
		<div class="floatrightwidth400normal">
		
			<div>&nbsp;</div>
			
			<div  style="float:left; height:75px;  vertical-align:top;" ><span class="meroon" >Contact Information :&nbsp;</span></div>
			{if $CHECK!=det}
			<div align="left">
				<div  ><span class="bodytext">{$FLYER_BASIC.contact_name}</span></div>
				<div ><span class="bodytext">{$FLYER_BASIC.contact_phone}</span></div>
				<div  ><span class="bodytext">{$FLYER_BASIC.contact_email}</span></div>
				
			</div>
			{else}
			<div style="background-image:url({$GLOBAL.tpl_url}/images/blocked_loc.jpg);background-repeat:no-repeat; height:92px; width:231px; float:left;" >
						  <div style="height:70px; width:180px;text-align:center;padding-top:25px;" class="grayboltext1">{$MOD_VARIABLES.MOD_LABELS.LBL_BLOCK}</div>
			</div>

			{/if}
						
		</div>		
		
		<div class="divSpc"></div>
		
	</div>
		
	</div>
	<div class="sepertor10px"><!-- --></div>
	<div class="sepertor10px"><!-- --></div>
	{/if}
	<!-- Contact and Location Information END HERE -->
	
	
	
	
	
	
	
	<!-- Features and attributes  Start From here -->
{foreach key=key from=$FLYER_FEATURES.blocks item=block}
	<div style="width:100%">
			<div style=";height:40px" class="naFooter">
				<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%" align="left"><span class="naGridTitle">{$key}</span></div>
				<div class="floatright" style="margin-top:15px;margin-left:10px;width:48%" align="right"><span style="margin-right:10px;"></span></div>

			</div>
			
			<!-- Features Start From Here -->
			<div class="divSpc"></div>
			<div class="floatleft">&nbsp;&nbsp;&nbsp;&nbsp;</div>
			{if $block.features|@count > 0}	
			<div  style="width:855px;">
				 <div>&nbsp;</div>
			
				
				{foreach key =label from=$block.features item=value}
					{if $label neq "" && $value neq ""}
				<div style="width:210px;float:left; text-align:left" >
									<span class="meroon">{$label}&nbsp;&nbsp;:&nbsp;</span>
									<span class="bodytext" >{$value}</span>
							 </div>
					{/if}
				{/foreach}
				
			</div>
			{/if}
			<!-- features End Here -->
			
			
			
			<!-- Attributes Start From Here -->
			{if $block.attributes|@count > 1}	
			<div class="floatleftwidth400normal">
				 <div>&nbsp;</div>
				
				{foreach key=groupname item=group from=$block.attributes}
				<div class="floatleft"><span class="meroon">{$groupname}</span></div>
				
				<div class="divSpc"></div>
				
					<div  style="width:850px; float:left;text-align:left; vertical-align:top;">
					
						<span class="bodytext" style="vertical-align:top ">
							<div style="vertical-align:top;">
										
								{foreach key=itemindex item=itemname from=$group name=group_name}
										
										{if $smarty.foreach.group_name.index is div by 4}
											
											<div class="sepertor10px"><!-- --></div>
											<div class="floatleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									    {/if}
								{if $group|@count -1  eq  $smarty.foreach.group_name.index}
									<img src="{$GLOBAL.tpl_url}/images/dot1.gif">&nbsp;&nbsp;{$itemname}&nbsp;
								{else}
								   <div  style="width:200px; float:left;">
									<img src="{$GLOBAL.tpl_url}/images/dot1.gif">&nbsp;&nbsp;{$itemname}
									</div>
								{/if}
									
								{/foreach}
							</div>
						</span>
					</div>
					 <div >&nbsp;</div>
				
				{/foreach}
				
			</div>
			{/if}
			<div class="divSpc"></div>
			<!-- Attributes End Here -->
			
	</div>
	<div class="sepertor10px"><!-- --></div>
{/foreach}
	
	<div class="sepertor10px"><!-- --></div>

	
<div id="bid_err_div" style="padding-left:30px;text-align:center">&nbsp;</div>
	
	<div class="divSpc"></div>
	<div class="sepertor10px"><!-- --></div> 	
	<div id="bid_view" style="display:none; position:absolute; width:800px">{include file="`$smarty.const.FRAMEWORK_PATH`/modules/flyer/tpl/bid_view.tpl"}</div>
	<!-- BEGIN PRICE INFORMATION 1 -->
	<div style="width:100%;">
		<div style="height:40px" class="naFooter">
		<div class="floatleft" style="margin-top:15px;margin-left:10px;width:50%;" align="left"><span class="naGridTitle">PRICE INFORMATION</span></div>
		<div class="floatleft" style="margin-top:15px;margin-left:10px;" align="left"><span class="meroon"></span></div>
		<div class="floatright" style="margin-top:15px;"><span style="margin-right:10px;"></span></div>
		</div>

        <div style="height:3px"><!-- --></div>

        <div class="meroon" align="left" style="padding-left:20px;height:25px; float:left"> Free Floating Pricing Calendar</div>
		 <div class="meroon" align="left" style="padding-left:710px;height:25px"><a href="javascript:void(0);" onClick="viewBidList();">{$MOD_VARIABLES.MOD_LABELS.LBL_VIEW_BIDS}</a></div>
		<div style="padding-left:20px;padding-right:20px">
			<div class="bodytext" align="left" style="border:1px solid #6D0506;background-color:#F1F1F1">
				<div style="float:left;width:20%" align="center"><strong>PRICE</strong></div>
				<div style="float:left;width:20%" align="center"><strong>Booking Price</strong></div>
				<div style="float:left;width:20%" align="center"><strong>DURATION</strong></div>
				<div style="float:left;width:20%" align="center"><strong>UNIT</strong></div>
				<div style="float:left;width:20%" align="center"><strong>Minimun DURATION</strong></div>
				<div style="clear:both"></div>
			</div>
			<div class="bodytext" align="left" style="border-left:1px solid #6D0506;border-right:1px solid #6D0506;border-bottom:1px solid #6D0506;background-color:#FDFDFD">
					<div style="float:left;width:20%" align="center">{$FLAT_BLOCK.0.price}</div>
					<div style="float:left;width:20%" align="center">{$FLAT_BLOCK.0.booking_price}</div>
					<div style="float:left;width:20%" align="center">{$FLAT_BLOCK.0.duration}</div>
					<div style="float:left;width:20%" align="center">{$FLAT_BLOCK.0.unit|upper}</div>
					<div style="float:left;width:20%" align="center">{$FLAT_BLOCK.0.min_duration}</div>
					<div style="clear:both"></div>
			</div>
			<div style="height:3px"><!-- --></div>
    	</div>




		<div style="height:3px"><!-- --></div>
		
		<div class="meroon" align="left" style="padding-left:20px;height:25px"><div id="col1" style="float:left;width:48%">Fixed Calendar Rates</div><div style="float:left;width:10px;" align="center" class="bodytext"><!--<img src="{$GLOBAL.tpl_url}/images/bid.png">--></div><div style="float:left;width:10%;" class="bodytext">&nbsp; <!--<strong>- Bid Now</strong>--></div><div style="float:left;width:3%;background-color:#FF7575;border:1px solid #000000;" align="center">&nbsp;</div><div id="col2" class="bodytext" style="padding-left:3px;float:left"><strong> - Auction Ended</strong></div><div style="width:10px;float:left">&nbsp;</div><div style="float:left;width:3%;background-color:#FFFFCC;border:1px solid #000000;" align="center">&nbsp;</div><div id="col2" class="bodytext" style="padding-left:3px;float:left"><strong> - Auction Enabled</strong></div></div>
		<div style="padding-left:20px;padding-right:20px">
				<div class="bodytext" align="left" style="border:1px solid #6D0506;background-color:#F1F1F1">
					<div style="float:left;width:5%" align="center">&nbsp;</div>
					<div style="float:left;width:20%" align="center"><strong>START DATE</strong></div>
					<div style="float:left;width:20%" align="center"><strong>END DATE</strong></div>
					<div style="float:left;width:15%" align="center"><strong>PRICE</strong></div>
					<div style="float:left;width:15%" align="center"><strong>DURATION</strong></div>
					<div style="float:left;width:15%" align="center"><strong>UNIT</strong></div>
					
					
					<div style="float:left;width:10%" align="center">&nbsp;</div>
					<div style="clear:both"></div>
				</div>

			{foreach from=$FIXED_BLOCK item=fixRow name=fixname}
				<div class="bodytext" align="left" style="border-left:1px solid #6D0506;border-right:1px solid #6D0506;border-bottom:1px solid #6D0506;{if $fixRow.auction eq 'Y'} {if $fixRow.auction_closed=='Y'}background-color:#FF7575;{else}background-color:#FFFFCC; {/if}{else}background-color:#FDFDFD;{/if}">
				
					<div style="float:left;width:5%;cursor:pointer;" align="center" id="plusminusPriceDiv_{$fixRow.id}" onclick="javascript:showPlusBlockedCont_Price({$fixRow.id});">[+]</div>
					<div style="float:left;width:2%;cursor:pointer;" align="center" id="divBid_{$fixRow.id}" onclick="javascript:showPopup('nameFieldPopup',event,'{$fixRow.start_date_format}','{$fixRow.rental_end_date_format}','{$fixRow.start_bid}','{$fixRow.id}','{$fixRow.max_bid}');hideShow(true);" >{if $fixRow.auction eq "Y"}{if $fixRow.auction_closed!="Y"}<!--<img title="Bid Now" height="15px"; alt="Bid Now" src="{$GLOBAL.tpl_url}/images/bid.png">-->&nbsp;{else} &nbsp;{/if}<input type="hidden" name="max_bid{$fixRow.id}" id="max_bid{$fixRow.id}" value="{$fixRow.max_bid}" /><input type="hidden" name="last_bid{$fixRow.id}" id="last_bid{$fixRow.id}" value="{$fixRow.last_bid}" /><input type="hidden" name="start_bid{$fixRow.id}" id="start_bid{$fixRow.id}" value="{$fixRow.start_bid}" />{else}&nbsp;{/if}</div>
					<div style="float:left;width:18%" align="center">{$fixRow.start_date_format}</div>
					<div style="float:left;width:20%" align="center">{$fixRow.rental_end_date_format}</div>
					<div style="float:left;width:15%" align="center">{$fixRow.price}</div>
					<div style="float:left;width:15%" align="center">{$fixRow.duration}</div>
					<div style="float:left;width:15%" align="center">{$fixRow.unit|upper}</div>
					<div style="float:left;width:10%;background-color:{$fixRow.color_code}" align="center">&nbsp;</div>
					<div style="clear:both"></div>
					{if $fixRow.auction eq "Y"}
					
					{if $fixRow.auction_closed=="Y"}
					<div style="float:left;width:5%;"><!-- --></div>
						<div style="float:left;width:708px;" align="center" class="bodytext">
						{$fixRow.auction_close_log}</div>
						<div style="float:right;width:10%;background-color:{$fixRow.color_code};" align="center">&nbsp;</div>
						<div style="clear:both"><!-- --></div>
					{else}
						<div style="float:left;width:5%;">&nbsp;</div>
						<div style="float:left;width:25%" align="center"><strong>START BID :</strong> ${$fixRow.start_bid}</div>
						<div style="float:left;width:30%" align="center"><strong>AUCTION ENDS :</strong> {$fixRow.auctionFormat_date}</div>
						<div style="float:left;width:30%">&nbsp;</div>
						
						<div style="float:right;width:10%;background-color:{$fixRow.color_code};" align="center">&nbsp;</div>
						<div style="clear:both"><!-- --></div>
{/if}
					
				{/if}
				</div>
				<!-- Blocked View -->
				<div id="splitBlockPriceDiv_{$fixRow.id}" style="display:none">
				{foreach key=key  from=$fixRow.Blocks item=fixBRow name=fixBname}
				<div class="bodytext" align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;">
				
					<div style="float:left;width:25%" align="center">{$smarty.foreach.fixBname.index+1} ]</div>
					<div style="float:left;width:65%" align="left">{$fixBRow}</div>
					<div style="float:left;width:10%;background-color:{$fixRow.color_code}" align="right">&nbsp;</div>
					<div style="clear:both"></div>
				</div>

				{/foreach}
				<!-- Bid Div Was here -->

				</div>
				
				
			{/foreach}
		</div>
		<!-- Blocked View ****************************************************************************** -->
	<div style="clear:both"></div>
	<div style="height:3px"><!-- --></div>
	<div class="meroon" align="left" style="padding-left:20px;height:25px">Blocked Dates</div>
		
		<div style="padding-left:20px;padding-right:20px">
				<div class="bodytext" align="left" style="border:1px solid #6D0506;background-color:#F1F1F1">
					<div style="float:left;width:5%;cursor:pointer;font-size:11px;" align="center" id="plusblockedDiv_1" onClick="javascript:showPlusBlockedDate();">[+]</div>
					<div style="float:left;width:5%" align="center">&nbsp;</div>
					<div style="float:left;width:20%" align="center"><strong>START DATE</strong></div>
					<div style="float:left;width:20%" align="center"><strong>END DATE</strong></div>
					<div style="float:right;width:10%;" align="center">&nbsp;</div>
					<div style="clear:both"></div>
				</div>
				
					<div id="splitBlockdDiv_1" style="display:none">
						{foreach key=key  from=$BLOCKD_QUANTITY item=fixBRow name=fixBname}
						<div class="bodytext" align="left" style="border-left:1px solid #6D0506;border-right:1px solid #6D0506;border-bottom:1px solid #6D0506;background-color:#F1F1F1">
							<div style="float:left;width:5%" align="center" class="bodytext">{$smarty.foreach.fixBname.index+1} ]</div>
							<div style="float:left;width:5%" align="center">&nbsp;</div>
							<div style="float:left;width:20%" align="center" class="bodytext">{$fixBRow.from_date|date_format:"%e, %B %Y"}</div>
							<div style="float:left;width:20%" align="center" class="bodytext">&nbsp;{$fixBRow.to_date|date_format:"%e, %B %Y"}</div>
							<div style="float:left;width:40%;">&nbsp;</div>
							<div style="float:left;width:10%;background-color:#FF0000;" align="center" class="bodytext">&nbsp;</div>
							<div style="clear:both"></div>
						</div>
						{/foreach}
					</div>
		</div>
		
	<div style="clear:both"></div>
	</div>	
	
	<div class="divSpc"></div>
	<div class="sepertor10px"><!-- --></div>
	<!-- END PRICE INFORMATION 1 -->
	
	
	
	
	<div class="divSpc"></div>
	<div class="sepertor10px"><!-- --></div>
	
	<!-- VIDEO LISTING START HERE -->
	{if count($VIDEO_LIST) > 0}<div style="width:100%;">
		
		<div style="height:40px;" class="naFooter">
		<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%;" align="left"><span class="naGridTitle">VIDEOS</span></div>
		<div class="floatright" style="margin-top:15px;margin-left:10px;width:48%" align="right"><span style="margin-right:10px;"></span></div>

		</div>
		
			<div class="sepertor10px"><!-- --></div>
			<div class="floatleft" style="width:420px;">
				{foreach from=$VIDEO_LIST item=rowVh name =foo key=id}
							
							{if $smarty.foreach.foo.index is div by 3}
							<div class="divSpc"></div>
							<div class="sepertor10px"><!-- --></div>
							{/if}

					<div class="floatleft" style="width:140px;"><img  src="{$smarty.const.SITE_URL}/modules/album/video/thumb/{$rowVh.id}.jpg" onClick="redirectVideoDisplay('{$rowVh.id}')" style="cursor:pointer;"></div>
				{/foreach}
			</div>
			<div class="floatright" style="width:450px;height:310px;text-align:right;">
				<div class="divSpc"></div>
				<!--<iframe src='{makeLink mod=flyer pg=list}act=video&propid={$smarty.request.propid}{/makeLink}' framespacing='0' frameborder='no' scrolling='no' width='420' height="325" id="ifvdo"></iframe>-->
				<iframe src='' framespacing='0' frameborder='no' scrolling='no' width='420' height="325" id="ifvdo"></iframe>
			</div>
		<div class="divSpc"></div>

	
	</div>
	{/if}
	<!-- VIDEO LISTING END HERE -->
	
	
	
	
	<!-- My Avialability Calendar Start Here-->
	
	<div style="width:100%;text-align:center;display:block;">
		<div style="height:40px;"  class="naFooter">
			<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%;" align="left"><span class="naGridTitle">Avialability Calendar</span></div>
		    </div>
		
		<div class="sepertor10px"><!-- --></div>
		
		<div  id="avCal" style="margin-left:50px;">
		<div class="divSpc"></div>
		<!-- <iframe src='{makeLink mod=album pg=booking}act=mycalendar_month&propid={$smarty.request.propid}&flyer_id={$smarty.request.flyer_id}&action_from=search&type=search{/makeLink}' framespacing='0' frameborder='no' scrolling='no' width='800px' height="650" id="ifCal"></iframe>-->
		<div style="height:25px;"><img src="{$GLOBAL.tpl_url}/images/loadingB.gif" style="display:none;" id="loadCal"></div>
		<div id="calendar">{$MONTH_VIEW}</div>
		
		</div>
		<div class="divSpc"></div>
	</div>
	
	<!-- My Avialability Calendar End Here-->
	
	
</div>
	<div class="divSpc"></div>
	<div style="clear:both; height:20px;"><!-- --></div>
</div>

</form>
</div>


<!-- End of Popup WIndow -->
		<div style="height:2px"><!-- --></div>
		
		
	</div>
</div>

<div id="loadDyn2" style="position:absolute;display:none;left:200px;top:630px;height:100px" >
	  <table width="100" border="0" cellspacing="0" cellpadding="0">
								
						 		 <tr>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_topleft.png" width="20" height="20" /></td>
									<td width="248" background="{$GLOBAL.tpl_url}/images/popup_top.png">&nbsp;</td>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_topright.png" width="20" height="20" /></td>
							 	 </tr>
								  <tr>
									<td height="30" background="{$GLOBAL.tpl_url}/images/popup_left.png">&nbsp;</td>
									<td width="500" bgcolor="#FFFFFF" >
									<table cellpadding="0" width="100" cellspacing="0" border="0" class="blacktext" height="152">
									<tr>
									
									<td  height="5"  valign="top" align="right" >
									<img src="{$GLOBAL.tpl_url}/images/close12.jpg" border="0" onclick="javascript:popupHide_dragOpt('loadDyn2','#EEEEEE')"; style="cursor:pointer; ">
									</td>
									</tr>
									<tr><td height="20%"  valign="top">
									<div id="con_list1" style="overflow:auto; height:300px; width:650px;">
											
									<!-- My Comments Starts Here -->
										<div style="width:100%;text-align:center;display:block;">
												<div style="background-color:#E8E6E7;height:40px;">
													<div class="floatleft" style="margin-top:15px;margin-left:10px;width:48%;" align="left"><span class="meroon">&nbsp;&nbsp;Feedbacks</span></div>
												</div>
												
													{if COUNT($COMMENTS)>0}
												<div style="padding-left:20px;padding-right:20px">
														<div class="bodytext" align="left" >
															   <div style="float:left;width:5%" align="center">&nbsp;</div>
															   <div class="sepertor10px"><!-- --></div>
															   
														        <div id="feedbackdiv"> 
																   {foreach from=$COMMENTS item=citem}
																	<div class="sepertor10px"><!-- --></div>
																	<div style="border:1px solid #afafaf;">
																	      <div class="sepertor10px"><!-- --></div>
																		  <div  align="left" class="browntext" style="float:left; width:300px; background-color: #F3F3F3; margin-left:10px;" >Posted By : <span class="bodytext">{$citem.fname}{$citem.lname}</span></div>
									                                      <div  align="left" class="browntext" style="background-color:#F3F3F3 ">Posted On : <span class="bodytext">{$citem.postded_date|date_format:"%e, %b %Y"}</span></div>
									                                      <div class="sepertor10px"><!-- --></div>
									                                      <div  align="left" class="browntext" style="margin-left:10px; ">Comments : <span class="bodytext"> {$citem.comment}  </span></div>
									                                      <div class="sepertor10px"><!-- --></div>
							                                        </div>
																   {/foreach}
																    <div style="float:right;width:70%;text-align:right"><span id="searchResNum">{$LIST_NUMPAD}</span>&nbsp;</div>
																	<div style="float:left;width:10%" align="center">&nbsp;</div>
																	<div style="clear:both"></div>
																   
																</div>   
														
														</div>
												</div>	
												{/if}
												
												</div>
												<div class="divSpc"></div>
													<div style="clear:both; height:20px;"></div>
									    </div>
										
												
									<!--My Comments End Here Here -->
											
												
											
									  </div>
									  </td></tr></table>
									</td>
									<td background="{$GLOBAL.tpl_url}/images/popup_right.png">&nbsp;</td>
								  </tr>
								  <tr>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_bottomleft.png" width="20" height="20" /></td>
									<td background="{$GLOBAL.tpl_url}/images/popup_bottom.png">&nbsp;</td>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_bottomright.png" width="20" height="20" /></td>
								  </tr>
								</table>
</div>
	