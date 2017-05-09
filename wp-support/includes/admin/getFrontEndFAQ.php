<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

$category_id = sanitize_text_field($_POST['category']);
if($category_id!='all'){
    $category_id = intval($category_id);
}
$search_txt  = sanitize_text_field($_POST['search']);
$page_no     = intval(sanitize_text_field($_POST['page_no']));

$sql="select f.id,f.question,f.answer,f.category_id,c.name as category from {$wpdb->prefix}wpsp_faq f INNER JOIN {$wpdb->prefix}wpsp_faq_catagories c ON f.category_id=c.id";

$where=" ";
$flagUseWhere=true;
if(isset($category_id) && $category_id!='all'){
	$where.=($flagUseWhere)?'AND ':'';
	$flagUseWhere=true;
	$where.="f.category_id='".$category_id."' ";
}

if(isset($search_txt) && $search_txt!=''){
	$where.=($flagUseWhere)?'AND ':'';
	$flagUseWhere=true;
	//custome fields
	
	$where.="f.id IN (SELECT DISTINCT f.id from {$wpdb->prefix}wpsp_faq f WHERE f.question LIKE '%".$search_txt."%' OR f.answer LIKE '%".$search_txt."%' ) ";
}

$limit_start=$page_no*20;
$limit="LIMIT ".$limit_start.",20 ";

$sql.=$where;

$faqs = $wpdb->get_results( $sql );
$current_page=$page_no+1;
$total_pages=ceil($wpdb->num_rows/20);

$sql.=$limit;
$faqs = $wpdb->get_results( $sql );
?>
<div class="table-responsive">
	<table id="tblFontEndTickets" class="table table-striped table-hover">
	  <tr>
		  <th><?php echo __("FAQ's", 'wp-support-plus-responsive-ticket-system');?></th>
		  <th><?php echo __("Category", 'wp-support-plus-responsive-ticket-system');?></th>
	  </tr>
	  <?php foreach ($faqs as $faq){?>
	  	<tr style="cursor:pointer;" onclick="openFAQ(<?php echo $faq->id;?>);">
	  		<td><?php echo stripcslashes($faq->question);?></td>
			<td><?php echo $faq->category;?></td>
	  	</tr>
	  <?php }?>
	 </table>
	<?php
	$prev_page_no=$current_page-1;
	$prev_class=(!$prev_page_no)?'disabled':'';
	$next_page_no=($total_pages==$current_page)?$current_page-1:$current_page;
	$next_class=($total_pages==$current_page)?'disabled':'';
	?>
	<ul class="pager" style="<?php echo ($total_pages==0)? 'display: none;':'';?>">
	  <li class="previous <?php echo $prev_class;?>"><a href="javascript:load_prev_page_faq(<?php echo $prev_page_no;?>);">&larr; <?php _e('Newer','wp-support-plus-responsive-ticket-system');?></a></li>
	  <li><?php echo $current_page;?> of <?php echo $total_pages;?> Pages</li>
	  <li class="next <?php echo $next_class;?>"><a href="javascript:load_next_page_faq(<?php echo $next_page_no;?>);"><?php _e('Older','wp-support-plus-responsive-ticket-system');?> &rarr;</a></li>
	</ul>
	<div style="text-align: center;<?php echo ($total_pages==0)? '':'display: none;';?>"><?php _e('No Faqs Found','wp-support-plus-responsive-ticket-system');?></div>
	<hr style="<?php echo ($total_pages==0)? '':'display: none;';?>">
</div>
