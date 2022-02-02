<?php
$woocom_category = 'woocom';
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

function woocom_save_extra_category_fields($term_id) {
	if (isset($_POST['selected_page'])) {
		$selected_page = intval($_POST['selected_page']);
		update_term_meta($term_id, 'selected_page', $selected_page );
	}

	if (isset($_POST['extra_info'])) {
		$extra_info = $_POST['extra_info'];
		update_term_meta($term_id, 'extra_info', $extra_info );
	}
}

add_action('created_category', 'woocom_save_extra_category_fields');
add_action('edited_category', 'woocom_save_extra_category_fields');