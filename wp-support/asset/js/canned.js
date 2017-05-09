jQuery(document).ready(function(){

});
function setShareCanned() {
    jQuery('#wpsp_canned_reply_container .wait').show();     
    jQuery('.show_canned_reply').hide();
    var data = {
        'action': 'shareCanned',
        'cid': currentCannedId,
        'cuid': jQuery('#share_user').val()
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#Modal').modal('hide');
        window.location.reload();
    });
}
function setCurrentCannedId(canned_id,sharedIds){
    currentCannedId=canned_id;
    $options = jQuery('#share_user option');
    $options.each(function(){
        jQuery(this).prop('selected', false);
    });
    
    var sharedIdArr = sharedIds.split(","); 
    $options = jQuery('#share_user option');
    $options.each(function(){
        if(sharedIdArr.indexOf(jQuery(this).val()) > -1){
            jQuery(this).prop('selected', true);
        }
    });
}

function wpsp_add_canned_reply(){         
    jQuery('#wpsp_canned_reply_container .wait').show();         
    jQuery('#wpsp_canned_reply_container .add_canned_reply').hide();         
    jQuery('.show_canned_reply').hide();         
    var data = {             
        'action': 'wpsp_add_canned_reply'        
    };         
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {             
        jQuery('#wpsp_canned_reply_container .wait').hide();            
        jQuery('#wpsp_canned_reply_container .add_canned_reply').html(response);            
        jQuery('#wpsp_canned_reply_container .add_canned_reply').show();            
        jQuery('#canned_reply_body').ckeditor();        
    }); 
} 

function setCannedReply(obj,e){     
    e.preventDefault();
    if(jQuery('#wpsp_title').val()==''){        
        alert(display_ticket_data.insert_canned_reply_title);        
        jQuery('#wpsp_title').val('');         
        jQuery('#wpsp_title').focus();     
        return false;
    }
    var canned_body=CKEDITOR.instances['canned_reply_body'].getData().trim();
    if(canned_body==''){
        return false;
    }
    
    jQuery('#wpsp_canned_reply_container .wait').show();         
    jQuery('#wpsp_canned_reply_container .add_canned_reply').hide();         
    jQuery('.show_canned_reply').hide();
    
    var dataform=new FormData( obj );
    dataform.append("wpsp_canned_reply", canned_body);
    
    jQuery.ajax( {
        url: display_ticket_data.wpsp_ajax_url,
        type: 'POST',
        data: dataform,
        processData: false,
        contentType: false
    }) 
    .done(function( msg ) {
        window.location.reload();
    });
} 

function getEditCannedReply(can_id){     
    jQuery('#wpsp_canned_reply_container .wait').show();     
    jQuery('#wpsp_canned_reply_container .edit_canned_reply').hide();     
    jQuery('.show_canned_reply').hide();         
    var data = {         
        'action': 'wpsp_edit_canned_reply',         
        'can_id':can_id     
    };    
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {         
        jQuery('#wpsp_canned_reply_container .wait').hide();         
        jQuery('#wpsp_canned_reply_container .edit_canned_reply').html(response);        
        jQuery('#wpsp_canned_reply_container .edit_canned_reply').show();      
        jQuery('#canned_reply_edit_body').ckeditor();     
    }); 
} 

function setEditCannedReply(obj,e){     
    e.preventDefault();
    if(jQuery('#wpsp_can_title').val()==''){         
        alert(display_ticket_data.insert_canned_reply_title);         
        jQuery('#wpsp_can_title').val('');         
        jQuery('#wpsp_can_title').focus();     
        return false;
    }
    
    var canned_body=CKEDITOR.instances['canned_reply_edit_body'].getData().trim();
    if(canned_body==''){
        return false;
    }
    
    jQuery('#wpsp_canned_reply_container .wait').show();         
    jQuery('#wpsp_canned_reply_container .edit_canned_reply').hide();         
    jQuery('.show_canned_reply').hide();         
    
    var dataform=new FormData( obj );
    dataform.append("wpsp_canned_reply", canned_body);
    
    jQuery.ajax( {
        url: display_ticket_data.wpsp_ajax_url,
        type: 'POST',
        data: dataform,
        processData: false,
        contentType: false
    }) 
    .done(function( msg ) {
        window.location.reload();
    });
}

function deletCannedReply(can_id){     
    if(confirm("Are you sure?")){         
        jQuery('#wpsp_canned_reply_container .wait').show();         
        jQuery('.show_canned_reply').hide();         
        var data = {             
            'action': 'wpsp_delete_canned_reply',             
            'can_id':can_id         
        };        
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {             
            jQuery('#wpsp_canned_reply_container .wait').hide();   
            window.location.reload();
            jQuery('.show_canned_reply').show();
        });    
    } 
}

function showCannedList(){
    jQuery('.show_canned_reply').show();
    jQuery('#wpsp_canned_reply_container .edit_canned_reply').hide();
    jQuery('#wpsp_canned_reply_container .add_canned_reply').hide();
}