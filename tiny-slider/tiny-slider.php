<?php

function ts_image_size() {
	add_image_size( 'tiny-slider', 800, 600 );
}

add_action( 'init', 'ts_image_size' );

function ts_assets() {
	wp_enqueue_style( 'ts-style', WC_DIR_URL_PUBLIC . '/css/tiny-slider.css', '1.0' );
	wp_enqueue_script( 'ts-script', WC_DIR_URL_PUBLIC . '/js/tiny-slider.js', null, '1.0', true );
	wp_enqueue_script( 'ts-main', WC_DIR_URL_PUBLIC . '/js/tn-main.js', array( 'ts-script' ), '1.0', true );
}

add_action( 'wp_enqueue_scripts', 'ts_assets' );

function ts_sliders( $args, $content = null ) {
	$defaults = array(
		'id'     => '',
		'width'  => 800,
		'height' => 600,
	);

	$attrs   = shortcode_atts( $defaults, $args );
	$content = do_shortcode( $content );

	return <<<EOD
<div class="my-slider" id="{$attrs['id']}" style="width: {$attrs['width']}px; height: {$attrs['height']}px;">
	<div class="slider">
	 {$content}
	</div>
	
	<ul>
	</ul>
</div>
EOD;

}

add_shortcode( 'slider', 'ts_sliders' );

function ts_slider( $args ) {
	$defaults = array(
		'id'      => '',
		'caption' => __( 'Hello', 'td' ),
		'size'    => 'tiny-slider',
	);

	$attrs     = shortcode_atts( $defaults, $args );
	$image_src = wp_get_attachment_image_src( $attrs['id'], $attrs['size'] );
	
	return <<<EOD
<div class='slide'>
	<img src="{$image_src[0]}" alt="{$attrs['caption']}" />
	<p>{$attrs['caption']}</p>
</div>
EOD;

}

add_shortcode( 'slide', 'ts_slider' );
