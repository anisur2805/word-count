<?php
 $args = array(
  'type'              => 'text',
  'description'       => __( 'Type your extra data here', 'text-domain' ),
  'sanitize_callback' => 'sanitize_text_field',
  'single'            => true,
  'show_in_rest'      => true,
 );
 register_meta( 'post', 'extra_info_meta', $args );

 function wc_category_add_form_field() {
 ?>
	<div class="form-field form-required term-name-wrap">
		<label for="extra-info"><?php _e( 'Extra Info *', 'text-domain' )?></label>
		<input name="extra-info" id="extra-info" type="text" value="" size="40" aria-required="true">
		<p><?php _e( 'The extra info is how it appears on your site.', 'text-domain' )?></p>
	</div>
<?php
 }

 add_action( 'category_add_form_fields', 'wc_category_add_form_field' );

 function wc_category_edit_form_field( $term ) {
  $extra_info = get_term_meta( $term->term_id, 'extra_info_meta', true );
 ?>

	<tr class="form-field form-required term-name-wrap">
		<th scope="row">
			<label for="extra-info"><?php _e( 'Extra Info *', 'text-domain' )?></label>
		</th>
		<td>
			<input name="extra-info" id="extra-info" type="text" value="<?php echo esc_attr( $extra_info ); ?>" size="40" aria-required="true" />
			<p><?php _e( 'The extra info is how it appears on your site.', 'text-domain' )?></p>
		</td>
	</tr>
<?php
 }

 add_action( 'category_edit_form_fields', 'wc_category_edit_form_field' );

 function taxm_save_category_meta( $term ) {
  if ( wp_verify_nonce( $_POST['_wpnonce_add-tag'], 'add-tag' ) ) {
   $extra_info = sanitize_text_field( $_POST['extra-info'] );
   update_term_meta( $term->term_id, 'extra_info_meta', $extra_info );
  }
 }

add_action( 'create_category', 'taxm_save_category_meta' );