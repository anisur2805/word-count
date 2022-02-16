<?php

class DemoWidget extends WP_Widget {

      public function __construct() {
            parent::__construct(
                  'demowidget',
                  __('Demo Widget', 'demo-widget'),
                  array('description' => __('This is a simple widget.')),
            );
      }

      public function form($instance) {
            $title     = isset($instance['title']) ? $instance['title'] : __('Demo Widget', 'demo-widget');
            $latitude  = isset($instance['latitude']) ? $instance['latitude'] : 40.12;
            $longitude = isset($instance['longitude']) ? $instance['longitude'] : 80.42;
            $email = isset($instance['longitude']) ? $instance['email'] : 80.42;
?>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'demo-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('latitude')); ?>"><?php _e('Latitude', 'demo-widget'); ?></label>
                  <input class="widefat" type="number" name="<?php echo esc_attr($this->get_field_name('latitude')); ?>" id="<?php echo esc_attr($this->get_field_id('latitude')); ?>" value="<?php echo esc_attr($latitude); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('longitude')); ?>"><?php _e('Longitude', 'demo-widget'); ?></label>
                  <input class="widefat" type="number" name="<?php echo esc_attr($this->get_field_name('longitude')); ?>" id="<?php echo esc_attr($this->get_field_id('longitude')); ?>" value="<?php echo esc_attr($longitude); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php _e('Email', 'demo-widget'); ?></label>
                  <input class="widefat" type="email" name="<?php echo esc_attr($this->get_field_name('email')); ?>" id="<?php echo esc_attr($this->get_field_id('email')); ?>" value="<?php echo esc_attr($email); ?>" />
            </p>
      <?php
      }

      public function widget($args, $instance) {

            $title = apply_filters('widget_title', $instance['title']);
            echo $args['before_widget'];
            if (!empty($title)) {
                  echo $args['before_title'] . $title . $args['after_title'];
            }
      ?>
            <div class="demo-widget">
                  <p>Latitude: <?php echo isset($instance['latitude']) ? $instance['latitude'] : ''; ?></p>
                  <p>Longitude: <?php echo isset($instance['longitude']) ? $instance['longitude'] : ''; ?></p>
                  <p>Email: <?php echo isset($instance['email']) ? $instance['email'] : ''; ?></p>
            </div>
<?php
            echo $args['after_widget'];
      }

      public function update($new_instance, $old_instance) {
            $instance = $new_instance;
            $instance['title'] = sanitize_text_field($instance['title']);

            $email = $new_instance['email'];
            $latitude = $new_instance['latitude'];
            $longitude = $new_instance['longitude'];

            if ( !is_email($email)) {
                  $instance['email'] = $old_instance['email'];
            }

            if (!is_numeric($latitude)) {
                  $instance['latitude'] = $old_instance['latitude'];
            }

            if (!is_numeric($longitude)) {
                  $instance['longitude'] = $old_instance['longitude'];
            }
            return $instance;
      }
}
