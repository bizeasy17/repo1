<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

$frontend=$_POST['front_end_length'];
$backend=$_POST['back_end_length'];

$subCharLength=array(
		'frontend'=>$frontend,
		'backend'=>$backend
);
update_option('wpsp_ticket_list_subject_char_length',$subCharLength);

?>
