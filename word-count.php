<?php

/**
 * Plugin Name: Word count
 * Description: Simple Word count
 * Plugin URI:  http://github.com/word-count
 * Version:     1.0
 * Author:      Anisur Rahman
 * Author URI:  http://github.com/anisur2805/
 * Text Domain: word-count
 * Languages: '/languages/
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
	exit;
}

// Constant Variables
define("WC_DIR_URL", plugin_dir_url(__FILE__) . "assets");
define("WC_DIR_URL_PUBLIC", WC_DIR_URL . "/public");
define("WC_DIR_URL_ADMIN", WC_DIR_URL . "/admin");

include_once "posts-to-qr.php";
include_once "tiny-slider/tiny-slider.php";
include_once "class-metabox.php";
include_once "class-select-page-metabox.php";
include_once "meta-fields-texanomy.php";
include_once "posts-list-table.php";
include_once "options-demo.php";
include_once "options-demo-two.php";
include_once "quick-tags.php";
include_once "recipe-cpt.php";
include_once "data-table/class-data-table.php";

// Widgets file load 
require_once "widgets/widgets.php"; 

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plugin_action_links');
function plugin_action_links($links) {
	$newLink = sprintf("<a href='%s'>%s</a>", 'options-general.php?page=options_demo_page', __('Settings', 'options-demo'));
	$links[] = $newLink;
	return $links;
}

/**
 * Load plugin text-date_interval_create_from_date_string
 *
 * @return void
 */
function wdc_word_count() {
	load_plugin_textdomain('word-count', false, dirname(__FILE__) . '/languages');
}

add_action('plugins_loaded', 'wdc_word_count');

/**
 * Word Count title, tag
 *
 * @param [type] $content
 * @return void
 */
function wdc_counts_word($content) {
	$stripped_content = strip_tags($content);
	$count_words = str_word_count($stripped_content);
	$label = apply_filters('wdc_word_count_heading', __('Total Words', 'word-count'));
	$tag = apply_filters('wdc_word_count_tag', "h2");

	// $content .= sprintf( "<h2>%s: %s </h2>", $label, $count_words );
	$content .= sprintf("<%s>%s: %s</%s>", $tag, $label, $count_words, $tag);

	return $content;
}
add_filter('the_content', 'wdc_counts_word');


/**
 * Word Count Reading Time
 *
 * @param [type] $content
 * @return void
 */
function wdc_counts_word_reading_time($content) {
	$stripped_content = strip_tags($content);
	$wordn = str_word_count($stripped_content);
	$reading_minutes = floor($wordn / 200);
	$reading_seconds = floor($wordn % 200 / (200 / 60));
	$is_visible = apply_filters('wdc_word_count_reading_display', 1);

	if ($is_visible) {
		$label = apply_filters('wdc_word_count_reading_heading', __('Total reading time', 'word-count'));
		$tag = apply_filters('wdc_word_count_reading_heading_tag', "h2");
		$content .= sprintf("<%s>%s: %s minutes %s seconds </%s>", $tag, $label, $reading_minutes, $reading_seconds, $tag);
	}
	return $content;
}
add_filter('the_content', 'wdc_counts_word_reading_time');
