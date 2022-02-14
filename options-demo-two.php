<?php
class OptionsDemoPageTwo {
      /**
       * Holds the values to be used in the fields callbacks
       */
      private $options;

      /**
       * Start up
       */
      public function __construct() {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_post_optionsdemo_admin_page', array( $this, 'optionsdemo_save_form'));
      }
      
      public function optionsdemo_save_form() {
            check_admin_referer('optionsdemo'); 
            
            if( isset( $_POST['optionsdemo_latitude'])) {
                  update_option('optionsdemo_latitude', sanitize_text_field($_POST['optionsdemo_latitude']));
            }
            
            wp_redirect(admin_url('admin.php?page=options_admin_page'));
      }
      

      /**
       * Add options page
       */
      public function add_plugin_page() {
            add_options_page(
                  'Settings Admin',
                  'Settings Admin',
                  'manage_options',
                  'options_admin_page',
                  array($this, 'optionsdemo_page_content')
            );
      }

      public function optionsdemo_page_content() {
            require_once plugin_dir_path(__FILE__) . '/form.php';
      }
}

if (is_admin()) {
      $my_settings_page = new OptionsDemoPageTwo();
}
