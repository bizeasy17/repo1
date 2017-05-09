<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$tab='statistics';
$tab=(empty($_REQUEST['tab']))?'statistics':$_REQUEST['tab'];
?>

<br>
<ul class="nav nav-tabs">
    <li class="<?php echo ($tab=='statistics')?'active':'';?>">
        <a href="<?php echo admin_url( 'admin.php?page=wp-support-plus-statistics&tab=statistics' );?>"><?php _e('Statistics','wp-support-plus-responsive-ticket-system');?></a>
    </li>
    <?php do_action('wpsp_add_tab_statistics',$tab);?>
</ul>

<?php
switch ($tab){
    case 'statistics': include WCE_PLUGIN_DIR.'includes/admin/wpsp_statistics.php';
        break;
    default :
        do_action('wpsp_add_tab_page_reference_statistics',$tab);
        break;
}
?>
