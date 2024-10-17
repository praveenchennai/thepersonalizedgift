<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/jquery-1.3.2.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/tooltip.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>

<script language="javascript">
{literal}
 $(function() {
            $('#popup_list').bind("contextmenu", function(e) {
                e.preventDefault();
            });
        }); 


function _pass(id, name,aid,type) {
	
  if (id=='')
  	id = document.getElementById('acc_id').value ;
  if (name=='')
  	name = document.getElementById('acc_name').value ;
  if (type=='')
  	type = document.getElementById('acc_type').value ;

	

	if(aid == 203){
		opener.document.getElementById("art_selected").innerHTML = name;
		opener.document.frmGift.art_id.value = id;
		opener.document.getElementById("art_preview").style.visibility= 'visible';
		opener.document.getElementById("art_sel_btn").style.display='none';
		opener.document.getElementById("art_cal_btn").style.display='block';
	}
	else if(aid == 235) {
	
		if(opener.document.frmGift.frame_id.value && opener.document.frmGift.frame_id.value!=0 )	{
			alert("Please remove the Frame first.");
			window.close();
			return false
		}
		else{
			opener.document.getElementById("mat_selected").innerHTML = name;
			opener.document.frmGift.mat_id.value = id;
			opener.document.getElementById("mat_preview").style.visibility= 'visible';
			opener.document.getElementById("mat_sel_btn").style.display='none';
			opener.document.getElementById("mat_cal_btn").style.display='block';
		}	
	}
	else if(aid == 245) {
	
		if(opener.document.frmGift.mat_id.value && opener.document.frmGift.mat_id.value!=0){
			alert("Please remove the Mat first.");
			window.close();
			return false
		}
		if(opener.document.frmGift.frame_id.value && opener.document.frmGift.frame_id.value!=0 )	{
			alert("Please remove the Wood Frame first.");
			window.close();
			return false
		}
		else{
			opener.document.getElementById("frame_selected").innerHTML = name;
			opener.document.frmGift.frame_id.value = id;
			opener.document.frmGift.frame_type.value = 'frame';
			opener.document.getElementById("frame_preview").style.visibility= 'visible';
			opener.document.getElementById("frame_sel_btn").style.display='none';
			opener.document.getElementById("frame_cal_btn").style.display='block';
		}	
	}
	else if(aid == 252) {
	
		if(opener.document.frmGift.frame_id.value && opener.document.frmGift.frame_id.value!=0 )	{
			alert("Please remove the Frame first.");
			window.close();
			return false
		}
		
		else{
		
			 if(opener.document.frmGift.mat_id.value=='' || opener.document.frmGift.mat_id.value==0 )	{
				alert("Please select a mat first.");
				window.close();
				return false
			}
			opener.document.getElementById("woodframe_selected").innerHTML = name;
			opener.document.frmGift.frame_id.value = id;
			opener.document.frmGift.frame_type.value = 'woodframe';
			opener.document.getElementById("woodframe_preview").style.visibility= 'visible';
			opener.document.getElementById("woodframe_sel_btn").style.display='none';
			opener.document.getElementById("woodframe_cal_btn").style.display='block';
			}
	}
	else if(aid == 174) {
		opener.document.getElementById("poem_selected").innerHTML = name;
		opener.document.frmGift.poem_id.value = id;
		opener.document.getElementById("poem_preview").style.visibility= 'visible';
		opener.document.getElementById("poem_sel_btn").style.display='none';
		opener.document.getElementById("poem_cal_btn").style.display='block';
		opener.showFieldsByProduct('',id);
	}
	window.close();
}


function showpopup(id,key,e,true_val,acc_id,acc_name,acc_type,td,click1)
 {

document.getElementById('acc_id').value = acc_id;
document.getElementById('acc_name').value = acc_name;
document.getElementById('acc_type').value = acc_type;
document.getElementById('closepopup').style.display = 'none';
document.getElementById("popup_list").style.display = "none";
if (td!=1)
document.getElementById("zoomlink"+key).className = 'zoom_link_active1';
 

 if (((true_val=='Y' ) && (document.getElementById('hid_popup').value == 'Y'))){
 
 	document.getElementById('hid_popup').value = '';
	hide_this();
	
	if (document.getElementById("prev_id").value == key)
	return false;
 }	


 if (true_val=='Y'){
   
 	document.getElementById('hid_popup').value = 'Y';
	
 }	
	
 
   if (document.getElementById('hid_popup').value!='Y')
   return false;
   
   
       var posx = 0;
		var posy = 0;
		if (!e) var e = window.event;
		if (e.pageX || e.pageY) 	{
			posx = e.pageX;
			posy = e.pageY;
		}
		else if (e.clientX || e.clientY) 	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}

		
      var windowWidth = document.body.clientWidth;
	  var windowHeight = document.documentElement.clientHeight;
	
	
		 if (window.pageYOffset)
			var ScrollTop = window.pageYOffset;
		else
			var ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
			
			
			var newheight = windowHeight-posy;
		   
		    if (newheight<420){
		    	
			 	var posy_new = 250;
				var posy_new1 = posy-430;
		   
		    }else{
			     posy_new = posy;
			
			}
	
	document.getElementById('img_popup_url').src = '';		
	document.getElementById('img_popup_url').style.display = 'none';
	document.getElementById('loading').style.display = 'block';
	document.getElementById("popup_list").style.left = 300+"px";
	document.getElementById("popup_list").style.top = posy_new-16+"px";
	document.getElementById("popup_list").style.display = "block";
	 setTimeout("popupshow()",1700);
	if (document.getElementById("nagrid"+document.getElementById("prev_id").value)){
		 document.getElementById("nagrid"+document.getElementById("prev_id").value).className = document.getElementById("prev_class").value;
			 
	}
			
			
	document.getElementById("prev_class").value = document.getElementById("nagrid"+key).className;
	document.getElementById("prev_id").value = key;
	document.getElementById("nagrid"+key).className = "naGrid_hover";   
    // window.setTimeout(function(){"popupshow()"},1200);
   
   
	  $.ajax({
 		 url: "{/literal}{makeLink mod=store pg=art_preview_index}act=preview_view{/makeLink}{literal}&id="+id+"&key="+key,
  		 cache: false,
 		 success: function(html){
    	
		    split_val = html.split("|");
	
		
			
			//document.getElementById("popup_pointer").style.top = (posy_new1)+"px";
			
			
			
			
			var count = document.getElementById("popup_count").value;
			
			
			
			
			
			
			
			document.getElementById("img_popup_url").src = split_val[0];
			//window.setTimeout(function(){"test()"},2000);
			setTimeout("test()",1200);
			
			
		
		}
	
	});
 
 
 }

function test()
{
	$('#closepopup').fadeIn('slow');

}

function popupshow()
{

document.getElementById('loading').style.display = 'none';
document.getElementById('img_popup_url').style.display = 'block';



}

function mouseout(key)
{

 document.getElementById("zoomlink"+key).className = 'zoom_link_1';


}


function hide_this(e)
{

	document.getElementById('hid_popup').value = '';
	document.getElementById('closepopup').style.display = 'none';
	
	document.getElementById("popup_list").style.display = "none";
	if (document.getElementById("nagrid"+document.getElementById("prev_id").value))
			document.getElementById("nagrid"+document.getElementById("prev_id").value).className = document.getElementById("prev_class").value;
	

}


function hide_this_main(e)
{
document.getElementById('closepopup').style.display = 'none';
if (!e) var e = window.event;
	var relTarg = e.relatedTarget || e.fromElement;

if (relTarg.id!='closepopup')
	document.getElementById("popup_list").style.display = "none";
if (document.getElementById("nagrid"+document.getElementById("prev_id").value))
			document.getElementById("nagrid"+document.getElementById("prev_id").value).className = document.getElementById("prev_class").value;	
	

}

function hide_this_main_td(e)
{
document.getElementById('closepopup').style.display = 'none';
if (document.getElementById('hid_popup').value == 'Y'){
	document.getElementById('hid_popup').value = '';
	document.getElementById("popup_list").style.display = "none";
if (document.getElementById("nagrid"+document.getElementById("prev_id").value))
			document.getElementById("nagrid"+document.getElementById("prev_id").value).className = document.getElementById("prev_class").value;	
	
}

}
function filterFrames(id)
{
window.location.href = "{/literal}{makeLink mod=store pg=accessory_popup_index}act=artbackground_list{/makeLink}{literal}&aid="+id;
}

{/literal}
</script>




<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center" border="0"> 
	<tr>
		<td colspan="5" align="center"><div id="msg_div"></div></td>
		</tr>
        <tr> 
          <td nowrap class="naH1" width="15%">{if $AID eq 203}Art Backgrounds{elseif $AID eq 235}Mats{elseif $AID eq 245}Frames{elseif $AID eq 174}Poems{/if}</td> 
		   <td nowrap align="right" class="titleLink" width="28%">
		   {if $smarty.request.aid eq 245 || $smarty.request.aid eq 247 || $smarty.request.aid eq 248}
			   <select name="filter" id="filter" onchange="filterFrames(this.value);">
			   <option value="245">--Select A Frame---</option>
			   <option value="247" {if $smarty.request.aid eq 247}selected{/if}>Wood Frame - 8.5x11</option>
			   <option value="248" {if $smarty.request.aid eq 248}selected{/if}>Plaque Frame - 8.5x11</option>
			   </select>
		   {/if}
		   </td>
          <td nowrap align="right" class="titleLink" width="25%"><form name="form1" method="post" action="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=artbackground_list&limit={$smarty.request.limit}&aid={$smarty.request.aid}{/makeLink}" style="margin:0px;">
            Search:
            <input type="text" name="accessory_search" value="{$smarty.request.accessory_search}">
            <input type="submit" name="Submit" value="Submit" class="naBtn">
          </form></td> 
          <td nowrap align="right" class="titleLink" width="15%">{if $ACCESSORY_LIMIT}Results Per Page{/if} </td>
          <td nowrap align="right" class="titleLink" width="7%">{$ACCESSORY_LIMIT}</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($ACCESSORY_LIST) > 0}
        <tr>
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">Name</td> 
<!--          <td width="50%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=product pg=accessory_popup orderBy="cart_name" display="Cart Name"}act=artbackground_list&aid={$smarty.request.aid} &keyword={$smarty.request.keyword}{/makeLink}</td> 
-->        </tr>
        {foreach from=$ACCESSORY_LIST item=acc key=key}
        <tr > 
		
		
		
		 
		
		
		
          <td valign="middle"     class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left" style="position:relative;" id="nagrid{$key}" ><div  class="wd_500"  id="popupdiv{$key}"  >	<div class="">  <a    class="zoom_link_1" id="zoomlink{$key}"    href="#"   onmouseout="mouseout('{$key}');"    onClick="_pass('{$acc->id}', '{$acc->name}',{$AID}, '{$acc->type}');void(0);">{$acc->name} </a></div> <div class="zoom_icon"  onmouseout="mouseout('{$key}');" onclick="showpopup('{$acc->id}','{$key}',event,'Y','{$acc->id}','{$acc->name}','{$acc->type}',1,'Y');return false;"  ></div></div></td> 
<!--          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left">{$acc->cart_name}</td> 
-->        

</tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
<div class="imgpoup_wrap"  onselectstart="return false;"  onmousedown="return false;" id="popup_list" style="display:none; width:504px; height:392px;  "     ><span  class="imgpoup_wrap_inner" id="image_popup" >
						<span style="position:absolute; right:0px; top:0px"><a  href="#" style="cursor:pointer;"><img id="closepopup" src="{$GLOBAL.tpl_url}/images/close.gif" style="display:none;" onclick="hide_this();return false;" alt="" border="0" /></a></span>
						<a href="javascript:void(0);" ><img src="" alt=""  id="img_popup_url" onClick="_pass('','',{$AID},'');"  style="display:none;"  width="504" height="392" /></a><div  id="loading" ><table><tr><td height="392" width="504" align="center" style="vertical-align:middle;"><img src="{$smarty.const.SITE_URL}/templates/blue/images/loading.gif" alt=""    align="middle" style="vertical-align:middle; text-align:center;"  /></td></tr></table></div>
						<!-- <span class="imgpoup_pointer" id="popup_pointer">&nbsp;</span>--></span><span></span>	</div>

<input type="hidden" name="hid_popup" id="hid_popup" value=""  />
<input type="hidden" name="prev_class" id="prev_class" value=""  />
<input type="hidden" name="prev_id" id="prev_id" value=""  />
<input type="hidden" name="click_on" id="click_on" value=""  />

<input type="hidden" name="acc_id" id="acc_id" value=""  />
<input type="hidden" name="acc_name" id="acc_name" value=""  />
<input type="hidden" name="acc_type" id="acc_type" value=""  />

<input type="hidden" name="popup_count" id="popup_count" value="{$ACCESSORY_LIST|@count}"  />

					
	