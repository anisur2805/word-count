<?php

/**
 * text-domain: omb-metabox
 */
class SelectPageMetaBox {
  public function __construct() {
    add_action('admin_menu', array($this, 'select_post_metabox'));
    add_action('save_post', array($this, 'select_post_save_metabox'));
    add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
  }

  public function admin_assets() {
    wp_enqueue_style('metabox-style', WC_DIR_URL_ADMIN . '/css/metabox.css', time());
  }

  private function is_secured($nonce_field, $action, $post_id) {
    $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] : '';

    if ($nonce == '') {
      return false;
    }

    if (!wp_verify_nonce($nonce, $action)) {
      return false;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return false;
    }

    if (wp_is_post_autosave($post_id)) {
      return false;
    }

    if (wp_is_post_revision($post_id)) {
      return false;
    }

    return true;
  }

  public function select_post_save_metabox($post_id) {

    if (!$this->is_secured('select_post_field', 'select_post', $post_id)) {
      return $post_id;
    }

    $selected_post_id    = $_POST['select_post'];

    if ($selected_post_id > 0) {
      update_post_meta($post_id, 'select_post', $selected_post_id);
    }

    return $post_id;
  }

  public function select_post_metabox() {
    add_meta_box('select_post_metabox', __('Select a Post', 'text-domain'), array($this, 'select_post_render_metabox'), 'page', 'advanced');
  }

  public function select_post_render_metabox($post) {
    $select_post = get_post_meta($post->ID, 'select_post', true);
    $label = __('Select Post', 'text-domain'); 

    wp_nonce_field('select_post', 'select_post_field');
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
    );
    $metabox_html = '';

    $_posts = new WP_Query($args);

    $dropdown_html = '';
    while ($_posts->have_posts()) {
      $selected = '';
      $_posts->the_post();
      if (get_the_ID() == $select_post) {
        $selected = 'selected';
      }

      $dropdown_html .= sprintf("<option %s value='%s'>%s</option>", $selected, get_the_ID(), get_the_title());
    }
    wp_reset_postdata();



    $metabox_html = <<<EOD
<label for="omb_dp">{$label}</label>
<select name="select_post" id="select_post">
<option value="0">--</option>
{$dropdown_html}
</select>
EOD;

    echo $metabox_html;
  }
}

new SelectPageMetaBox();
