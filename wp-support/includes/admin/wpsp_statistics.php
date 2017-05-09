<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
?>
<style>
@-moz-document url-prefix() {
  fieldset { display: table-cell; }
}
</style>
<?php
$statusses = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_status" );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
?>
<div class="wpsp_statistics">
<?php
$count=1;
foreach($statusses as $status){
	$sql="select count(id) from {$wpdb->prefix}wpsp_ticket where status='".strtolower($status->name)."' AND active=1";
	$total_none_tickets = $wpdb->get_var( $sql );
	if($count==5)
	{
		$count=1;
		echo "<br/><br/>";
	}
	?>
	<span class="label stat_heading" style="background-color:<?php echo $status->color?>"><?php echo $status->name." ".__($advancedSettings['ticket_label_alice'][2],'wp-support-plus-responsive-ticket-system').': '.$total_none_tickets;?></span>
	<?php
	$count++;
}
?><br><br>

<div class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th><?php _e('Assigned to','wp-support-plus-responsive-ticket-system');?></th>
			<?php
				foreach($statusses as $status){
					?><th><?php echo $status->name." ".__($advancedSettings['ticket_label_alice'][2],'wp-support-plus-responsive-ticket-system');?></th><?php
				}
			?>
		</tr>
		<tr>
			<td><?php _e('None','wp-support-plus-responsive-ticket-system');?></td>
			<?php
				foreach($statusses as $status){
					$sql="select count(id) from {$wpdb->prefix}wpsp_ticket where status='".strtolower($status->name)."'  and assigned_to=0 and active=1";
					$total_none_tickets = $wpdb->get_var( $sql );
					?><td><span class="label stat_coloumn_lable" style="background-color:<?php echo $status->color;?>"><?php echo $total_none_tickets;?></span></td><?php
                                }
			?>
		</tr>
		<?php 
		$roleManage=get_option( 'wpsp_role_management' );
		$agents=array();
		$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
		$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
		$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'administrator')));
		foreach($roleManage['agents'] as $agentRole)
		{
			$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
		}
		foreach($roleManage['supervisors'] as $supervisorRole)
		{
			$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
		}
		
		foreach ($agents as $agent){
			echo '<tr>
				<td>'.$agent->display_name.'</td>';
			foreach($statusses as $status){
				$sql="select count(id) from {$wpdb->prefix}wpsp_ticket where status='".strtolower($status->name)."' and ". $agent->ID ." IN (assigned_to) AND active=1 ";
				$total_agent_ticket = $wpdb->get_var( $sql );
				echo '<td><span class="label stat_coloumn_lable" style="background-color:'.$status->color.'">'.$total_agent_ticket.'</span></td>';
                        }
			echo '</tr>';
		}
		?>
	</table>
</div>
</div>