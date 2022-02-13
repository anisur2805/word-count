<?php
class OptionsDemoPage {
      /**
       * Holds the values to be used in the fields callbacks
       */
      private $options;

      /**
       * Start up
       */
      public function __construct() {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
      }

      /**
       * Add options page
       */
      public function add_plugin_page() {
            // This page will be under "Settings"
            add_options_page(
                  'Settings Admin',
                  'Options Demo',
                  'manage_options',
                  'options_demo_page',
                  array($this, 'create_admin_page')
            );
      }

      /**
       * Options page callback
       */
      public function create_admin_page() {
            // Set class property
            $this->options = get_option('my_option_name');
?>
            <div class="wrap">
                  <h1>My Settings</h1>
                  <form method="post" action="options.php">
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields('my_option_group');
                        do_settings_sections('options_demo_page');
                        submit_button();
                        ?>
                  </form>
            </div>
<?php
      }

      /**
       * Register and add settings
       */
      public function page_init() {
            register_setting(
                  'my_option_group', // Option group
                  'my_option_name', // Option name
                  array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                  'setting_section_id', // ID
                  'My Custom Settings', // options_demo_title
                  array($this, 'print_section_info'), // Callback
                  'options_demo_page' // Page
            );

            add_settings_field(
                  'options_demo_lat', // ID
                  'Latitude', // options_demo_title 
                  array($this, 'options_demo_lat_callback'), // Callback
                  'options_demo_page', // Page
                  'setting_section_id' // Section           
            );

            add_settings_field(
                  'options_demo_title',
                  'Longitude',
                  array($this, 'options_demo_title_callback'),
                  'options_demo_page',
                  'setting_section_id'
            );

            add_settings_field(
                  'options_demo_zoom', // ID
                  'Zoom Level', // options_demo_title 
                  array($this, 'options_demo_zoom_callback'), // Callback
                  'options_demo_page', // Page
                  'setting_section_id' // Section           
            );

            add_settings_field(
                  'options_demo_api_key',
                  'API Key',
                  array($this, 'options_demo_api_key_callback'),
                  'options_demo_page',
                  'setting_section_id'
            );
      }

      /**
       * Sanitize each setting field as needed
       *
       * @param array $input Contains all settings fields as array keys
       */
      public function sanitize($input) {
            $new_input = array();
            if (isset($input['options_demo_lat'])) {
                  $new_input['options_demo_lat'] = absint($input['options_demo_lat']);
            }
            if (isset($input['options_demo_title'])) {
                  $new_input['options_demo_title'] = sanitize_text_field($input['options_demo_title']);
            }
            
            if (isset($input['options_demo_zoom'])) {
                  $new_input['options_demo_zoom'] = sanitize_text_field($input['options_demo_zoom']);
            }
            
            if (isset($input['options_demo_api_key'])) {
                  $new_input['options_demo_api_key'] = sanitize_text_field($input['options_demo_api_key']);
            }
            return $new_input;
      }

      /** 
       * Print the Section text
       */
      public function print_section_info() {
            print 'Enter your settings below:';
      }

      /** 
       * Get the settings option array and print one of its values
       */
      public function options_demo_lat_callback() {
            printf(
                  '<input type="text" id="options_demo_lat" name="my_option_name[options_demo_lat]" value="%s" />',
                  isset($this->options['options_demo_lat']) ? esc_attr($this->options['options_demo_lat']) : ''
            );
      }

      /** 
       * Get the settings option array and print one of its values
       */
      public function options_demo_title_callback() {
            printf(
                  '<input type="text" id="options_demo_title" name="my_option_name[options_demo_title]" value="%s" />',
                  isset($this->options['options_demo_title']) ? esc_attr($this->options['options_demo_title']) : ''
            );
      }
      

      /** 
       * Get the settings option array and print one of its values
       */
      public function options_demo_zoom_callback() {
            printf(
                  '<input type="text" id="options_demo_zoom" name="my_option_name[options_demo_zoom]" value="%s" />',
                  isset($this->options['options_demo_zoom']) ? esc_attr($this->options['options_demo_zoom']) : ''
            );
      }
      

      /** 
       * Get the settings option array and print one of its values
       */
      public function options_demo_api_key_callback() {
            printf(
                  '<input type="text" id="options_demo_api_key" name="my_option_name[options_demo_api_key]" value="%s" />',
                  isset($this->options['options_demo_api_key']) ? esc_attr($this->options['options_demo_api_key']) : ''
            );
      }
}

if (is_admin()) {
      $my_settings_page = new OptionsDemoPage();
}
