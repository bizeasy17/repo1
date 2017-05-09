<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb,$cu;
$cu=wp_get_current_user();
$statusses = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_status" );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$count=1;
?>
<form id="wpsp_dashboard_stat">
<ul class="wpsp_stats_box">
<?php
foreach($statusses as $status){
    if($cu->has_cap('manage_options') || $cu->has_cap('manage_support_plus_agent')){
        $sql="select count(id) from {$wpdb->prefix}wpsp_ticket where status='".strtolower($status->name)."' AND active=1";
    }
    else if(!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket')){
        $sql="select count(id) from {$wpdb->prefix}wpsp_ticket where 
        status='".strtolower($status->name)."' AND active=1 AND (FIND_IN_SET('".$cu->ID."',assigned_to)>0 OR assigned_to=0) ";
    }else{
        $sql="select count(id) from {$wpdb->prefix}wpsp_ticket where status='".strtolower($status->name)."' AND active=1 AND created_by=".$cu->ID;
    }
    $sql=apply_filters('wpsp_backend_dashboard_ticket_statistics_sql',$sql,$status);
    $total_no_tickets = $wpdb->get_var( $sql );
    ?>
    <li onclick="wpsp_filter_ticket_list_by_stats(<?php echo $status->id;?>,<?php echo $cu->ID;?>);">
        <div class="wpsp_stat_count" style="background-color: <?php echo $status->color?>"><?php echo $total_no_tickets;?></div>
        <div class="wpsp_stat_text">
          <?php echo $status->name." ".__($advancedSettings['ticket_label_alice'][2],'wp-support-plus-responsive-ticket-system');?>
        </div>
    </li>
    <?php
}
?>
</ul>
</form>
<?php do_action('wpsp_after_statistics_dashboard_backend');?>