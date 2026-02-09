<?php
add_action( 'enqueue_block_assets', function () {
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap', array(), null );
	wp_enqueue_style( 'material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200', array(), null );
} );

add_action( 'after_setup_theme', function () {
	$GLOBALS['content_width'] = apply_filters( 'content_width', 856 );
} );

add_filter( 'oembed_min_max_width', function () {
	return array(
		'min' => 200,
		'max' => 856,
	);
} );