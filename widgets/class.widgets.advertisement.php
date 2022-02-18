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

            $ad_title     = isset($instance['ad_title']) ? $instance['ad_title'] : __('Advertisement Block', 'advertisement-widget');
            $ad_image  = isset($instance['ad_image']) ? $instance['ad_image'] : '';
            $ad_url = isset($instance['ad_url']) ? $instance['ad_url'] : 'https://google.com/';

            ?>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('ad_title')); ?>"><?php _e('Title', 'demo-widget'); ?></label>
                  <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_name('ad_title')); ?>" id="<?php echo esc_attr($this->get_field_id('ad_title')); ?>" value="<?php echo esc_attr($ad_title); ?>" />
            </p>

            <div>
                  <label for="<?php echo esc_attr($this->get_field_id('ad_image')); ?>"><?php _e('Advertisement Image', 'advertisement-widget'); ?></label>
                  <p id="ad_image_preview"></p>
                  <input class="imgph" type="hidden" id="<?php echo esc_attr($this->get_field_id('ad_image')); ?>" name="<?php echo esc_attr($this->get_field_name('ad_image')); ?>" value="<?php echo esc_attr($ad_image); ?>" />
                  <input type="button" class="button-add-media" id="ad_image_upload" value="<?php _e('Add Image', 'advertisement-widget'); ?>" />

            </div>
            <p>
                  <label for="<?php echo esc_attr($this->get_field_id('ad_url')); ?>"><?php _e('URL', 'advertisement-widget'); ?></label>
                  <input class="widefat" type="url" name="<?php echo esc_attr($this->get_field_name('ad_url')); ?>" id="<?php echo esc_attr($this->get_field_id('ad_url')); ?>" value="<?php echo esc_url($ad_url); ?>" />
            </p>


            <?php
      }

      public function widget($args, $instance) {
            
            $ad_title = apply_filters('widget_title', $instance['ad_title']);
            $ad_url = isset($instance['ad_url']) ? $instance['ad_url'] : '';
            $ad_id = isset($instance['ad_image']) ? $instance['ad_image'] : '';
            $ad_img_url = wp_get_attachment_image_src($ad_id);
            
            echo $args['before_widget'];
            if (!empty($ad_title)) {
                  echo $args['before_title'] . $ad_title . $args['after_title'];
            }
            ?>
            <div class="advertisement-widget">
                  <a target="_blank" href="<?php echo esc_url($ad_url); ?>">
                        <h3><?php echo esc_attr($ad_title); ?></h3>
                        <img src="<?php echo esc_url($ad_img_url[0]); ?>" alt="Test" />
                  </a>
            </div>
            <?php
            echo $args['after_widget'];
      }

      public function update($new_instance, $old_instance) {

            $instance = array();
            $instance['ad_title'] = sanitize_text_field($new_instance['ad_title']);
            $instance['ad_image'] = sanitize_text_field($new_instance['ad_image']);
            $instance['ad_url'] = sanitize_text_field($new_instance['ad_url']);

            return $instance;
      }
}
