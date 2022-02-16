<?php

class AdvertisementWidget extends WP_Widget {

      public function __construct() {
            parent::__construct(
                  'advertisement-widget',
                  __('Advertisement Widget', 'advertisement-widget'),
                  array('description' => __('This is a simple advertisement widget.')),
            );
      }

      public function form($instance) {
            $title     = isset($instance['title']) ? $instance['title'] : __('Advertisement Block', 'advertisement-widget');
            $ad_image  = isset($instance['ad_image']) ? $instance['ad_image'] : 40.12;
            $ad_url = isset($instance['ad_url']) ? $instance['ad_url'] : 80.42;
?>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'advertisement-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('latitude')); ?>"><?php _e('Image', 'advertisement-widget'); ?></label>
                  <input class="widefat" type="number" name="<?php echo esc_attr($this->get_field_name('latitude')); ?>" id="<?php echo esc_attr($this->get_field_id('latitude')); ?>" value="<?//php echo esc_attr($latitude); ?>" />
            </p>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('ad_url')); ?>"><?php _e('URL', 'advertisement-widget'); ?></label>
                  <input class="widefat" type="url" name="<?php echo esc_attr($this->get_field_name('ad_url')); ?>" id="<?php echo esc_attr($this->get_field_id('ad_url')); ?>" value="<?php echo esc_attr($ad_url); ?>" />
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
            <div class="advertisement-widget">
                  <p>Latitude: <?php echo isset($instance['latitude']) ? $instance['latitude'] : ''; ?></p>
                  <p>Longitude: <?php echo isset($instance['ad_url']) ? $instance['longitude'] : ''; ?></p>
                  <p>Email: <?php echo isset($instance['email']) ? $instance['email'] : ''; ?></p>
            </div>
<?php
            echo $args['after_widget'];
      }

      public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = sanitize_text_field($instance['title']);
            $instance['ad_image'] = sanitize_text_field($instance['ad_image']);
            $instance['ad_url'] = sanitize_text_field($instance['ad_url']);

            return $instance;
      }
}
