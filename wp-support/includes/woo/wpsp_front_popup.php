<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="wpsp_front_popup" onclick="wpsp_close_front_popup();" style="display: none;"></div>
<div id="wpsp_front_popup_inner" style="display: none;">
    <div id="wpsp_front_popup_blank"></div>
    <div id="wpsp_front_popup_body" style="display: none;"></div>
</div>
<img id="wpsp_front_popup_close_btn" alt="Close Button" onclick="wpsp_close_front_popup();" style="display: none;" src="<?php echo WCE_PLUGIN_URL.'asset/images/close_btn.png';?>"/>
<img id="wpsp_front_popup_loading_img" alt="Loading Image" style="display: none;" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"/>