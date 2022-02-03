<?php
$wc_category = 'woocom';
$args = array(
	'type'              => 'text',
	'description'       => __('Type your extra data here', 'text-domain'),
	'sanitize_callback' => 'sanitize_text_field',
	'single'            => true,
	'show_in_rest'      => true,
);
register_meta('post', 'extra_info_meta', $args);

function wc_category_add_form_field() {
?>
	<div class="form-field form-required term-name-wrap">
		<label for="extra_info"><?php _e('Extra Info *', 'text-domain') ?></label>
		<input name="extra_info" id="extra_info" type="text" value="">
		<p><?php _e('The extra info is how it appears on your site.', 'text-domain') ?></p>
	</div>


	<div class="form-field">
		<label for="selected_page"><?php esc_attr_e('Page select', 'mytheme'); ?></label>
		<?php wp_dropdown_pages(array('name' => 'selected_page', 'id' => 'selected_page', 'show_option_none' => '--')); ?>
		<p><?php esc_attr_e('Select the page you want to show the content of in your category.', 'mytheme'); ?></p>
	</div>

<?php
}

add_action('category_add_form_fields', 'wc_category_add_form_field');
add_action('post_tag_add_form_fields', 'wc_category_add_form_field');

function wc_category_edit_form_field($term) {
	$selected_page = get_term_meta($term->term_id, 'selected_page', true);
	$extra_info = get_term_meta($term->term_id, 'extra_info', true);
?>

	<tr class="form-field form-required term-name-wrap">
		<th scope="row"><label for="extra_info"><?php _e('Extra Info *', 'text-domain') ?></label></th>
		<td>
			<input name="extra_info" id="extra_info" type="text" value="<?php echo $extra_info; ?>">
			<p><?php _e(' The extra info is how it appears on your site.', 'text-domain') ?></p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="selected_page"><?php esc_attr_e('Page select', 'mytheme'); ?></label></th>
		<td>
			<?php wp_dropdown_pages(array('name' => 'selected_page', 'id' => 'selected_page', 'selected' => $selected_page, 'show_option_none' => '--')); ?>
			<p class="description"><?php esc_attr_e('Select the page you want to show the content of in your category.', 'mytheme'); ?></p>
		</td>
	</tr>

<?php
}

add_action('category_edit_form_fields', 'wc_category_edit_form_field');
add_action('post_tag_edit_form_fields', 'wc_category_edit_form_field');

function wc_save_extra_category_fields($term_id) {
	if( wp_verify_nonce( $_POST['_wpnonce'], "update-tag_{$term_id}" )) {
		// if (isset($_POST['selected_page'])) {
			$selected_page = intval($_POST['selected_page']);
			update_term_meta($term_id, 'selected_page', $selected_page );
		// }

		// if (isset($_POST['extra_info'])) {
			$extra_info = $_POST['extra_info'];
			update_term_meta($term_id, 'extra_info', $extra_info );
		// }
	}
}

add_action('created_category', 'wc_save_extra_category_fields');
add_action('created_post_tag', 'wc_save_extra_category_fields');
add_action('edited_post_tag', 'wc_save_extra_category_fields');
add_action('edited_category', 'wc_save_extra_category_fields');

/**
 * Adding custom column to taxonomy
 *
 * @var     array     $cat_columns     Columns array in custom taxonomy
 * @return  array     Returns updated category columns
 * @since   1.0.0
 */
if( ! function_exists( 'wc_manage_edit_category_columns' ) ) {
	function wc_manage_edit_category_columns( $cat_columns ) {
		$cat_columns['selected_page'] = esc_attr__('Selected Page', 'woocom');
		return $cat_columns;
	}
	
}

/**
 * Managing custom column in taxonomy display
 *
 * @var     array     $deprecated
 * @var     array     $column_name     Column name
 * @var     array     $term_id         Taxonomy term id
 * @since   1.0.0
 */
add_filter( 'manage_edit-category_columns', 'wc_manage_edit_category_columns'); 
add_filter( 'manage_edit-post_tag_columns', 'wc_manage_edit_category_columns'); 

if( !function_exists( 'wc_manage_category_custom_column' ) ) {
	function wc_manage_category_custom_column( $deprecated, $column_name, $term_id ) {
		if( $column_name == 'selected_page' ) {
			$selected_page = get_term_meta( $term_id, 'selected_page', true );
			if( isset( $selected_page ) && $selected_page != '' ) {
				echo get_the_title( $selected_page );
			} 
		}
	}
}

/**
 * Make column sortable
 *
 * @param  array $sortable Sortable columns array
 * @return array           Updated sortable columns array
 * @since   1.0.0
 */
add_filter( 'manage_category_custom_column', 'wc_manage_category_custom_column', 10, 3 );
add_filter( 'manage_post_tag_custom_column', 'wc_manage_category_custom_column', 10, 3 );

if( ! function_exists( 'wc_manage_sortable_columns' ) ) {
	function wc_manage_sortable_columns( $sortable) {
		$sortable['selected_page'] = 'selected_page';
		return $sortable;
	}
}
add_filter( 'manage_edit-category_sortable_columns', 'wc_manage_sortable_columns');
add_filter( 'manage_edit-post_tag_sortable_columns', 'wc_manage_sortable_columns');

/**
 * Column sortable query
 *
 * @param  array $pieces     Terms query SQL clauses.
 * @param  array $taxonomies An array of taxonomies.
 * @param  array $args       An array of terms query arguments.
 * @return array             Modified query array
 * @since   1.0.0
 */

if( !function_exists( 'wc_terms_clauses' ) ) {
	function wc_terms_clauses( $pieces, $taxonomies, $args ) {
		global $wpdb;
		
		$orderby = isset( $_REQUEST['orderby']) ? trim( wp_unslash( $_REQUEST['orderby']) ) : 'selected_page';
		if( $orderby == 'selected_page') {
			$pieces['fields'] .= ", trm.*";
			$pieces['join'] .= " INNER JOIN {$wpdb->termmeta} AS trm ON tt.term_id = trm.term_id";
			$pieces['orderby'] = "ORDER BY trm.meta_value";
		}
		
		return $pieces;
	}
}
add_filter( 'terms_clauses', 'wc_terms_clauses', 10, 3 );