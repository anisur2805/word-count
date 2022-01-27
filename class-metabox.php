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
		add_action( 'save_post', array( $this, 'omb_save_location' ) );
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

	public function omb_save_location( $post_id ) {

		if ( !$this->is_secured( 'omb_location_field', 'omb_location', $post_id ) ) {
			return $post_id;
		}

		$location = isset( $_POST['obm_location'] ) ? $_POST['obm_location'] : '';
		$country = isset( $_POST['obm_country'] ) ? $_POST['obm_country'] : '';
		
		if ( '' == $location || '' == $country) {
			return $post_id;
		}

		$location = sanitize_text_field( $location );
		$country = sanitize_text_field( $country );

		update_post_meta( $post_id, 'obm_location', $location );
		update_post_meta( $post_id, 'obm_country', $country );
	}

	public function omb_metabox() {
		add_meta_box( 'omb_metabox', __( 'Metabox', 'omb-metabox' ), array( $this, 'omb_metabox_render' ), 'post', 'advanced' );
	}

	public function omb_metabox_render( $post ) {
		$location = get_post_meta( $post->ID, 'obm_location', true );
		$country = get_post_meta( $post->ID, 'obm_country', true );
		
		$label1    = __( 'Location', 'omb-metabox' );
		$label2    = __( 'Country', 'omb-metabox' );
		wp_nonce_field( 'omb_location', 'omb_location_field' );

		$metabox_html = <<<EOD
		<label for="obm_location">{$label1}</label>
		<input type="text" name="obm_location" id="obm_location" value="{$location}"  />
		<br />
		<label for="obm_country">{$label2}</label>
		<input type="text" name="obm_country" id="obm_country" value="{$country}"  />
EOD;

		echo $metabox_html;
	}
}

new OMBMetaBox();
