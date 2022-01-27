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

if ( !defined( 'ABSPATH' ) ) {
 exit;
}

include_once "posts-to-qr.php";
include_once "tiny-slider/tiny-slider.php";
include_once "class-metabox.php";

/**
 * Load plugin text-date_interval_create_from_date_string
 *
 * @return void
 */
function wdc_word_count( ) {
	load_plugin_textdomain('word-count', false, dirname( __FILE__ ) . '/languages' );
}

add_action( 'plugins_loaded', 'wdc_word_count' );

/**
 * Word Count title, tag
 *
 * @param [type] $content
 * @return void
 */
function wdc_counts_word( $content ) {
	$stripped_content = strip_tags( $content );
	$count_words = str_word_count( $stripped_content );
	$label = apply_filters( 'wdc_word_count_heading', __('Total Words', 'word-count') );
	$tag = apply_filters( 'wdc_word_count_tag', "h2" );
	
	// $content .= sprintf( "<h2>%s: %s </h2>", $label, $count_words );
	$content .= sprintf("<%s>%s: %s</%s>", $tag, $label, $count_words, $tag );
	
	return $content;
}
add_filter( 'the_content', 'wdc_counts_word' );


/**
 * Word Count Reading Time
 *
 * @param [type] $content
 * @return void
 */
function wdc_counts_word_reading_time( $content ) {
	$stripped_content = strip_tags( $content );
	$wordn = str_word_count( $stripped_content );
	$reading_minutes = floor( $wordn / 200 );
	$reading_seconds = floor( $wordn % 200 / ( 200 / 60 ) );
	$is_visible = apply_filters( 'wdc_word_count_reading_display', 1);
	
	if( $is_visible ) {
		$label = apply_filters( 'wdc_word_count_reading_heading', __('Total reading time', 'word-count') );
		$tag = apply_filters( 'wdc_word_count_reading_heading_tag', "h2" ); 
		$content .= sprintf("<%s>%s: %s minutes %s seconds </%s>", $tag, $label, $reading_minutes, $reading_seconds, $tag );
	}
	return $content;
}
add_filter( 'the_content', 'wdc_counts_word_reading_time' );