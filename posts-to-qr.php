<?php

/**
 * text-domain: qrc-qrcode
 *
 * @param [type] $content
 * @return void
 */

$qrc_countries = array(
  'None', 'Afghanistan', 'Bangladesh', 'Bhutan', 'India', 'Maldives', 'Nepal', 'Pakistan', 'Sri Lanka',
);

function qrc_init() {
  global $qrc_countries;
  $qrc_countries = apply_filters('qrc_countries', $qrc_countries);
}

add_action('init', 'qrc_init');

function posts_to_qr($content) {
  $current_post_id = get_the_ID();
  $post_title      = urlencode(get_the_title($current_post_id));
  $post_url        = get_the_permalink($current_post_id);
  $post_types      = get_post_type($current_post_id);

  $image_attr = apply_filters('pqrcode_qrcode_image_attr', '');
  $height     = get_option('qrc_height');
  $height     = $height ? $height : 180;

  $width = get_option('qrc_width');
  $width = $width ? $width : 180;

  $image_dimension    = apply_filters('pqrcode_qrcode_dimension', "{$height}x{$width}");
  $exclude_post_types = apply_filters('pqrcode_exclude_post_types', array());

  if (in_array($post_types, $exclude_post_types)) {
    return $content;
  }

  $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $image_dimension, $post_url);
  $content .= sprintf('<div><img %s src="%s" alt="%s"  /></div>', $image_attr, $image_src, $post_title);

  return $content;
}

add_filter('the_content', 'posts_to_qr');

function qrc_qrcode_admin_init() {

  add_settings_section('qrc_settings_section', __('QR Code', 'qrc-qrcode'), 'qrc_settings_section_cb', 'general');

  add_settings_field('qrc_height', __('QR Code Height', 'qrc-qrcode'), 'qrc_fields_cb', 'general', 'qrc_settings_section', array('qrc_height'));
  add_settings_field('qrc_width', __('QR Code Width', 'qrc-qrcode'), 'qrc_fields_cb', 'general', 'qrc_settings_section', array('qrc_width'));
  add_settings_field('qrc_extra', __('Extra', 'qrc-qrcode'), 'qrc_fields_cb', 'general', 'qrc_settings_section', array('qrc_extra'));
  add_settings_field('qrc_extra2', __('Another Extra', 'qrc-qrcode'), 'qrc_fields_cb', 'general', 'qrc_settings_section', array('qrc_extra2'));
  add_settings_field('qrc_select', __('Select Saarc Countries', 'qrc-qrcode'), 'qrc_countries_field_cb', 'general', 'qrc_settings_section');
  add_settings_field('qrc_checkbox', __('Select Countries', 'qrc-qrcode'), 'qrc_countries_checkbox_cb', 'general', 'qrc_settings_section');
  add_settings_field('qrc_toggle', __('Toggle Field', 'qrc-qrcode'), 'qrc_toggle_cb', 'general', 'qrc_settings_section');

  register_setting('general', 'qrc_height', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_width', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_extra', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_extra2', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_extra2', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_select', array('sanitize_callback' => 'esc_attr'));
  register_setting('general', 'qrc_checkbox');
  register_setting('general', 'qrc_toggle');
}

function qrc_toggle_cb() {
  $option = get_option('qrc_toggle');

  echo "HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH" . $option;

  echo '<div class="toggle" id="qrc_tgl"></div>';
  echo '<input type="hidden" id="qrc_toggle" name="qrc_toggle" value="' . $option . '" />';
}

add_action('admin_init', 'qrc_qrcode_admin_init');

function qrc_settings_section_cb() {
  echo '<p>' . __('QR Code all information goes here.', 'qrc-qrcode') . '</p>';
}

function qrc_fields_cb($args) {
  $option = get_option($args[0]);
  printf("<input type='text' id='%s' name='%s' value='%s' />", $args[0], $args[0], $option);
}

function qrc_countries_field_cb() {
  global $qrc_countries;
  $option = get_option('qrc_select');

  printf('<select name="%s" id="%s">', 'qrc_select', 'qrc_select');
  foreach ($qrc_countries as $country) {
    $selected = '';
    if ($option == $country) {
      $selected = 'selected';
    }
    printf('<option value="%s" %s>%s</option>', $country, $selected, $country);
  }
  echo '</select>';
}

function qrc_countries_checkbox_cb() {
  global $qrc_countries;
  $option = get_option('qrc_checkbox');

  foreach ($qrc_countries as $country) {
    $checked = '';
    if (is_array($option) && in_array($country, $option)) {
      $checked = 'checked';
    }
    printf('<input name="qrc_checkbox[]" id="qrc_checkbox" type="checkbox" value="%s" %s>%s<br />', $country, $checked, $country);
  }
}

function qrc_assets($screen) {
  if ('options-general.php' == $screen) {
    wp_enqueue_style('qrc-minitoggle', WC_DIR_URL_ADMIN . '/css/minitoggle.css');
    //   wp_dequeue_script('jquery');
    wp_enqueue_script('jquery2', '//code.jquery.com/jquery-3.2.1.slim.min.js', null, '3.2.1', true);
    wp_enqueue_script('qrc-minitoggle-js', WC_DIR_URL_ADMIN . '/js/minitoggle.js', array('jquery2'), '1.0', true);
    wp_enqueue_script('qrc-main-js', WC_DIR_URL_ADMIN . '/js/qrc-main.js', array('jquery2', 'qrc-minitoggle-js'), time(), true);
  }
}

add_action('admin_enqueue_scripts', 'qrc_assets');
