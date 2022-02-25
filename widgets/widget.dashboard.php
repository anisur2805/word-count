<?php

function wdb_init() {
      if (current_user_can('edit_dashboard')) {
            wp_add_dashboard_widget('demo_wdb', __('Awesome Dashboard', 'demo-wdb'), 'wdb_render_output', 'demo_wdb_config');
      } else {
            wp_add_dashboard_widget('demo_wdb', __('Awesome Dashboard', 'demo-wdb'), 'wdb_render_output');
      }
}
add_action('wp_dashboard_setup', 'wdb_init');

function wdb_render_output() {
      $nop = get_option('wdb_nop', 5);
      echo "<h4>Hello There</h4>";
      // wp_dashboard_right_now();

      $feeds = array(
            array(
                  'url' => 'https://wptavern.com/feed',
                  'items' => $nop,
                  'show_summery' => 0,
                  'show_author' => 0,
                  'show_date' => 0,
            )
      );

      wp_dashboard_primary_output('demo_wdb', $feeds);
}

function demo_wdb_config() {
      $nop = get_option('wdb_nop', 5);
      if (isset($_POST['dashboard-widget-nonce']) && wp_verify_nonce($_POST['dashboard-widget-nonce'], 'edit-dashboard-widget_demo_wdb')) {
            if (isset($_POST['demo_db_nop']) && $_POST['demo_db_nop'] > 0) {
                  $nop = sanitize_text_field($_POST['demo_db_nop']);
                  update_option('wdb_nop', $nop);
            }
      }
?>
      <p>
            <label>Number of posts</label> <br />
            <input class="widefat" type="text" name="demo_db_nop" id="demo_db_nop" value="<?php echo $nop; ?>" />
      </p>
<?php
}
