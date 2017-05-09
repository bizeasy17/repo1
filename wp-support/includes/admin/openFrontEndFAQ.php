<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

$faq_id = intval(sanitize_text_field($_POST['id']));
if(!$faq_id) die(); //sql injection

$sql="select * FROM {$wpdb->prefix}wpsp_faq WHERE id=".$faq_id;
$faq = $wpdb->get_row( $sql );
?>
<br>
<button class="btn btn-success" onclick="triggerFAQ();"><?php echo __("Back to FAQs", 'wp-support-plus-responsive-ticket-system'); ?></button>
<h3><?php echo stripcslashes($faq->question);?></h3>
<?php echo stripcslashes(htmlspecialchars_decode($faq->answer,ENT_QUOTES));?>
