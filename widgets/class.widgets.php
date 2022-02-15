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
            $title = isset($instance['title']) ? $instance['title'] : __('Demo Widget', 'demo-widget');
            $latitude = isset($instance['latitude']) ? $instance['latitude'] : 40.12;
            $longitude = isset($instance['longitude']) ? $instance['longitude'] : 80.42;
?>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'demo-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_id('title')); ?>" id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('latitude')); ?>"><?php _e('Latitude', 'demo-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_id('latitude')); ?>" id="<?php echo esc_attr($this->get_field_id('latitude')); ?>" value="<?php echo esc_attr($latitude); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('longitude')); ?>"><?php _e('Longitude', 'demo-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_id('longitude')); ?>" id="<?php echo esc_attr($this->get_field_id('longitude')); ?>" value="<?php echo esc_attr($longitude); ?>" />
            </p>
      <?php
      }

      public function widget($args, $instance) {
            print_r($args);
            print_r($instance);

            echo $args['before_widget'];
            if (isset($instance['title']) && $instance['title'] != '') {
                  echo $args['before_title'];
                  echo apply_filters('widget_title', $instance['title']);
                  echo $args['after_title'];
            }

      ?>
            <div class="demo-widget">
                  <p>Latitude: <?php echo isset($instance['latitude']) ? $instance['latitude'] : ''; ?></p>
                  <p>Longitude: <?php echo isset($instance['longitude']) ? $instance['longitude'] : ''; ?></p>
            </div>
<?php
            echo $args['after_widget'];
      }
      // public function update() {

      // }

}
