
  <img id="pfc_minmax" onclick="pfc.swap_minimize_maximize()" src="<?php echo $c->getFileUrlFromTheme('images/'.($start_minimized?'maximize':'minimize').'.gif'); ?>" alt=""/>
  <h2 id="pfc_title" align="center"><?=$title?></h2>
 
  <div id="pfc_content_expandable" style="border:#00FFCC" >                

  <div id="pfc_channels" >
    <ul id="pfc_channels_list"></ul>
    <div id="pfc_channels_content"></div>
  </div>

  <div id="pfc_input_container">

    <table >
    
      <tr>
      <td class="pfc_td1">
        <p id="pfc_handle"
           <?php if (! $frozen_nick) {
             echo ' title="' . _pfc("Enter your nickname here") . '"' 
               . ' onclick="pfc.askNick(\'\')"'
               . ' style="cursor: pointer"';
           }
           ?>
           ><?php echo $u->nick; ?></p>      
      </td>
      <td class="pfc_td2">
        <input type="text"
               id="pfc_words"
               title="<?php echo _pfc("Enter your message here"); ?>"
               maxlength="<?php echo $max_text_len; ?>"/>
      </td>
      <td class="">
        <input type="button"
               id="pfc_send"
               value="<?php echo _pfc("Send"); ?>"
               title="<?php echo _pfc("Click here to send your message"); ?>"
               onclick="pfc.doSendMessage()"/>
      </td>
      </tr>
     
    </table>

   
      <div class="pfc_btn">
        <img src="<?php echo $c->getFileUrlFromTheme('images/logout.gif'); ?>"
             alt="" title=""
             id="pfc_loginlogout"
			 
             />
      </div>

      <div class="pfc_btn">
        <img src="<?php echo $c->getFileUrlFromTheme('images/color-on.gif'); ?>"
             alt="" title=""
             id="pfc_nickmarker"
             onclick="pfc.nickmarker_swap()" />
      </div>

      <div class="pfc_btn">
        <img src="<?php echo $c->getFileUrlFromTheme('images/clock-on.gif'); ?>"
             alt="" title=""
             id="pfc_clock"
             onclick="pfc.clock_swap()" />
      </div>

      <div class="pfc_btn">
        <img src="<?php echo $c->getFileUrlFromTheme('images/sound-on.gif'); ?>"
             alt="" title=""
             id="pfc_sound"
             onclick="pfc.sound_swap()" />
      </div>

   

    </div>

    
      <div id="pfc_colorlist"></div>
    </div> <!-- pfc_bbcode_container -->

  </div>

   

    <div id="pfc_smileys"></div>

  </div>
 <div id="pfc_errors" align="center"  ></div>
  <div id="pfc_sound_container"></div>
