<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Prefix_Filter {

  static function generate($current_filter = '', $depth = False, $taxonomy_term = False){
    # Explode Filter string
    $arr_current_filter = (MB_StrLen($current_filter)) ? PReg_Split('/(?<!^)(?!$)/u', $current_filter) : Array();
    Array_Unshift($arr_current_filter, '');

		$arr_filter = Array(); # This will be the function result
    $filter_part = '';

		foreach ($arr_current_filter as $filter_letter){
			$filter_part .= $filter_letter;
			$arr_available_filters = self::getFilters($filter_part, $taxonomy_term);
			if (count($arr_available_filters) < 2) break;
			$active_filter_part = MB_SubStr(implode($arr_current_filter), 0, MB_StrLen($filter_part) + 1);

			$arr_filter_line = Array();
			foreach ($arr_available_filters as $available_filter){
				$arr_filter_line[$available_filter] = (Object) Array(
          'filter' => MB_StrToUpper(MB_SubStr($available_filter, 0, 1)) . MB_SubStr($available_filter, 1), # UCFirst Workaround for multibyte chars
          'link' => Post_Type::getArchiveLink($available_filter, $taxonomy_term),
          'active' => $active_filter_part == $available_filter,
          'disabled' => False
        );
			}
			$arr_filter[] = $arr_filter_line;

      # Check filter depth limit
      if ($depth && count($arr_filter) >= $depth) break;
		}

    # Run a filter
    $arr_filter = apply_Filters('encyclopedia_prefix_filter_links', $arr_filter, $depth);

		return $arr_filter;
	}

  static function getFilters($prefix = '', $taxonomy_term = False){
    global $wpdb;

    $prefix_length = MB_StrLen($prefix) + 1;
    $tables = Array($wpdb->posts.' AS posts');
    $where = Array(
      'posts.post_status  =     "publish"',
      'posts.post_type    =     "'.Post_Type::post_type_name.'"',
      'posts.post_title   !=    ""',
      'posts.post_title   LIKE  "'.$prefix.'%"'
    );

    if ($taxonomy_term){
      $tables[] = $wpdb->term_relationships.' AS term_relationships';
      $where[] = 'term_relationships.object_id = posts.id';
      $where[] = 'term_relationships.term_taxonomy_id = '.$taxonomy_term->term_taxonomy_id;
    }

    $stmt = 'SELECT   LOWER(SUBSTRING(posts.post_title,1,'.$prefix_length.')) subword
             FROM     '.join($tables, ',').'
             WHERE    '.join($where, ' AND ').'
             GROUP BY subword
             ORDER BY subword ASC';

    $arr_filter = $wpdb->get_Col($stmt);
    $arr_filter = apply_Filters('encyclopedia_available_prefix_filters', $arr_filter, $prefix, $taxonomy_term);
    return $arr_filter;
	}

  static function printFilter($current_filter = '', $filter_depth = False, $taxonomy_term = False){
    $prefix_filter = self::generate($current_filter, $filter_depth, $taxonomy_term);

    if (!empty($prefix_filter))
      echo Template::load('encyclopedia-prefix-filter.php', Array('filter' => $prefix_filter));
    else
      return False;
  }

}
