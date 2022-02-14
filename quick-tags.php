<?php
function quick_tags_assets($screen) {
      if ('post.php' == $screen) {
            wp_enqueue_script('wc-quick-tags', WC_DIR_URL_ADMIN . '/js/quick-tags.js', array('quicktags', 'thickbox'), time(), true);
            wp_localize_script('wc-quick-tags', 'qtls', array('preview'=> plugin_dir_url(__FILE__).'/fap.php'));
      }
}

add_action('admin_enqueue_scripts', 'quick_tags_assets');
