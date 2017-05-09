<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

// access to the template object
global $gk_tpl;

?>

<script type="text/javascript">
	$GK_TEMPLATE_UPDATE_NAME = '<?php echo $gk_tpl->update_name; ?>';
	$GK_TEMPLATE_UPDATE_VERSION = '<?php echo $gk_tpl->version; ?>';
</script>

<div class="gkWrap wrap">
	<h2><?php _e('Updates', GKTPLNAME); ?></h2>
	
	<dl>
		<dt>
			<h3><?php _e('Template version: ', GKTPLNAME); echo $gk_tpl->version; ?></h3>
		</dt>
		<dd>
			<div id="gkTemplateUpdates"></div>
		</dd>
	</dl>
</div>