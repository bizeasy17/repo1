<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="show_canned_reply">     
    <?php         
    include( WCE_PLUGIN_DIR.'includes/admin/showcanned.php' );     
    ?> 
</div>

<div id="wpsp_canned_reply_container">     
    <div class="add_canned_reply"></div>     
    <div class="edit_canned_reply"></div>     
    <div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div> 
</div>
