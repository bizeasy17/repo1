<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div id="wpsp_delete_confirm">
  <?php
  if($ticket->active==0){
      echo __('Ticket will get permanently deleted! Delete Ticket?','wp-support-plus-responsive-ticket-system-ticket-system');
  }
  else{
      echo __('Delete Ticket?','wp-support-plus-responsive-ticket-system-ticket-system');
  }
  ?>
</div>
<script>
    ticketid=0;
    jQuery('document').ready(function(){
        jQuery( "#wpsp_delete_confirm" ).dialog({
            resizable: false,
            autoOpen: false,
            height: "auto",
            width: 400,
            modal: true,
            dialogClass: "wpsp-dialog",
            buttons: {
              "<?php _e('Cancel','wp-support-plus-responsive-ticket-system');?>": function() {
                jQuery( this ).dialog( "close" );
              },
              "<?php _e('OK','wp-support-plus-responsive-ticket-system');?>": function() {
                jQuery( this ).dialog( "close" );
                deleteTicket(ticketid);
              }
            }
        });
    });
    
    function wpsp_deleteTicketConfirm(e,ticket_id){
        e.preventDefault();
        ticketid=ticket_id;
        jQuery( "#wpsp_delete_confirm" ).dialog('open');
    }
    
</script>