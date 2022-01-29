<?php
/**
 * text-domain: omb-metabox
 */

/**
 * Undocumented class
 */
class OMBMetaBox {
 public function __construct() {
  add_action( 'admin_menu', array( $this, 'omb_metabox' ) );
  add_action( 'save_post', array( $this, 'omb_save_metabox' ) );
  add_action( 'save_post', array( $this, 'omb_save_image_metabox' ) );
  add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
 }

 public function admin_assets() {
  wp_enqueue_style( 'metabox-style', WC_DIR_URL_ADMIN . '/css/metabox.css', time() );
  wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
  wp_enqueue_script( 'omb-metabox-js', WC_DIR_URL_ADMIN . '/js/metabox.js', array( 'jquery', 'jquery-ui-datepicker' ), time(), true );
 }

 private function is_secured( $nonce_field, $action, $post_id ) {
  $nonce = isset( $_POST[$nonce_field] ) ? $_POST[$nonce_field] : '';

  if ( $nonce == '' ) {
   return false;
  }

  if ( !wp_verify_nonce( $nonce, $action ) ) {
   return false;
  }

  if ( !current_user_can( 'edit_post', $post_id ) ) {
   return false;
  }

  if ( wp_is_post_autosave( $post_id ) ) {
   return false;
  }

  if ( wp_is_post_revision( $post_id ) ) {
   return false;
  }

  return true;

 }

 public function omb_save_image_metabox( $post_id ) {
  if ( !$this->is_secured( 'omb_image_nonce_field', 'omb_image_action', $post_id ) ) {
   return $post_id;
  }

  $image_id  = isset( $_POST['obm_image_id'] ) ? $_POST['obm_image_id'] : '';
  $image_url = isset( $_POST['obm_image_url'] ) ? $_POST['obm_image_url'] : '';

  update_post_meta( $post_id, 'obm_image_id', $image_id );
  update_post_meta( $post_id, 'obm_image_url', $image_url );

 }

 public function omb_save_metabox( $post_id ) {

  if ( !$this->is_secured( 'omb_location_field', 'omb_location', $post_id ) ) {
   return $post_id;
  }

  $location    = isset( $_POST['obm_location'] ) ? $_POST['obm_location'] : '';
  $country     = isset( $_POST['obm_country'] ) ? $_POST['obm_country'] : '';
  $is_favorite = isset( $_POST['obm_is_favorite'] ) ? $_POST['obm_is_favorite'] : '';
  $colors      = isset( $_POST['omb_clr'] ) ? $_POST['omb_clr'] : array();
  $color       = isset( $_POST['omb_color'] ) ? $_POST['omb_color'] : '';

  $omb_dp        = isset( $_POST['omb_dp'] ) ? $_POST['omb_dp'] : '';
  $omb_fav_color = isset( $_POST['omb_fav_color'] ) ? $_POST['omb_fav_color'] : '';

  if ( '' == $location || '' == $country ) {
   return $post_id;
  }

  $location = sanitize_text_field( $location );
  $country  = sanitize_text_field( $country );

  update_post_meta( $post_id, 'obm_location', $location );
  update_post_meta( $post_id, 'obm_country', $country );
  update_post_meta( $post_id, 'obm_is_favorite', $is_favorite );
  update_post_meta( $post_id, 'omb_clr', $colors );
  update_post_meta( $post_id, 'omb_color', $color );

  update_post_meta( $post_id, 'omb_dp', $omb_dp );
  update_post_meta( $post_id, 'omb_fav_color', $omb_fav_color );
 }

 public function omb_metabox() {
  add_meta_box( 'omb_metabox', __( 'Metabox', 'omb-metabox' ), array( $this, 'omb_render_metabox' ), 'post', 'advanced' );
  add_meta_box( 'omb_image_info', __( 'Image Info', 'omb-metabox' ), array( $this, 'omb_render_image' ), 'post', 'advanced' );
 }

 public function omb_render_image( $post ) {
  $image_id  = get_post_meta( $post->ID, 'obm_image_id', true );
  $image_url = get_post_meta( $post->ID, 'obm_image_url', true );
  
//   echo "<pre>";
// 	  var_dump($image_id);
//   die();

  $label = __( 'Upload Image', 'omb-metabox' );
  wp_nonce_field( 'omb_image_action', 'omb_image_nonce_field' );

  $image_info_html = <<<EOD
		<div id="myImageMetaBox">
		<label for="obm_location">{$label}</label>
		<button class="button" id="upload_image">{$label}</button>
		<button class="hidden button" name="obm_image_remove" id="delete_custom_img">Remove Image</button>
		<input type="hidden" name="obm_image_id" id="obm_image_id" value={$image_id} />
		<input type="hidden" name="obm_image_url" id="obm_image_url" value={$image_url} />
		<div id="image_container"></div></div>
EOD;
  echo $image_info_html;
 }

 public function omb_render_metabox( $post ) {
  $location = get_post_meta( $post->ID, 'obm_location', true );
  $country  = get_post_meta( $post->ID, 'obm_country', true );

  $is_favorite = get_post_meta( $post->ID, 'obm_is_favorite', true );
  $is_checked  = $is_favorite == 1 ? 'checked' : 0;

  $saved_colors = get_post_meta( $post->ID, 'omb_clr', true );
  $saved_color  = get_post_meta( $post->ID, 'omb_color', true );

  $omb_dp          = get_post_meta( $post->ID, 'omb_dp', true );
  $saved_fav_color = get_post_meta( $post->ID, 'omb_fav_color', true );

  $label1 = __( 'Location', 'omb-metabox' );
  $label2 = __( 'Country', 'omb-metabox' );
  $label3 = __( 'Is Favorite', 'omb-metabox' );

  $label4   = __( 'Colors', 'omb-metabox' );
  $dp_label = __( 'Date', 'omb-metabox' );
  $colors   = array( 'red', 'green', 'blue', 'black' );

  wp_nonce_field( 'omb_location', 'omb_location_field' );

  $metabox_html = <<<EOD
		<label for="obm_location">{$label1}</label>
		<input type="text" name="obm_location" id="obm_location" value="{$location}"  />
		<br />
		<label for="obm_country">{$label2}</label>
		<input type="text" name="obm_country" id="obm_country" value="{$country}"  />
		<br />
		<label for="obm_is_favorite">{$label3}</label>
		<input type="checkbox" name="obm_is_favorite" id="obm_is_favorite" value="1" {$is_checked} />
	<br />
			<label>{$label4}</label>
EOD;
  $saved_colors = is_array( $saved_colors ) ? $saved_colors : array();
  foreach ( $colors as $color ) {
   $_color  = ucwords( $color );
   $checked = in_array( $color, $saved_colors ) ? 'checked' : '';
   $metabox_html .= <<<EOD
<label for="omb_clr_{$color}">{$_color}</label>
<input type="checkbox" name="omb_clr[]" id="omb_clr_{$color}" value="{$color}" {$checked} />

EOD;
  }
  foreach ( $colors as $color ) {
   $_color  = ucwords( $color );
   $checked = ( $color == $saved_color ) ? "checked='checked'" : '';
   $metabox_html .= <<<EOD
		<p>
<label for="omb_color_{$color}">{$_color}</label>
<input type="radio" name="omb_color" id="omb_color_{$color}" value="{$color}" {$checked} />
</p>
EOD;
  }

  $metabox_html .= <<<EOD
<label for="omb_dp">{$dp_label}</label>
<input type="text" name="omb_dp" id="omb_dp" value="{$omb_dp}" />
EOD;

  $dropdown_html = "<option>" . __( 'Select a color', 'text-domain' ) . "</option>";
  foreach ( $colors as $color ) {
   $_color   = ucwords( $color );
   $selected = ( $color == $saved_fav_color ) ? "selected" : '';
   $dropdown_html .= sprintf( "<option %s value='%s'>%s</option>", $selected, $color, $_color );
  }
  $metabox_html .= <<<EOD

<label for="omb_dp">{$label4}</label>
<select name="omb_fav_color" id="omb_fav_color">
{$dropdown_html}
</select>
EOD;

  echo $metabox_html;
 }
}

new OMBMetaBox();
