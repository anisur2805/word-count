<?php
require_once 'class.widgets.php';
require_once 'class.widgets.advertisement.php';

function wordcount_register_widgets() {
      register_widget('DemoWidget');
      register_widget('AdvertisementWidget');
}
add_action('widgets_init', 'wordcount_register_widgets');

function advertisement_assets($screen) {
      if ('widgets.php' == $screen) {
            wp_enqueue_media();
            wp_enqueue_script('advertisement-script', WC_DIR_URL_ADMIN .'/js/advertisement.js', array('jquery'), time(), true);
            wp_enqueue_style('advertisement-style', WC_DIR_URL_ADMIN .'/css/advertisement.css', time() );
      }
}
add_action('admin_enqueue_scripts', 'advertisement_assets');
