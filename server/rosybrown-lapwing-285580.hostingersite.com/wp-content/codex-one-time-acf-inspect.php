<?php
define( 'WP_USE_THEMES', false );
require_once __DIR__ . '/../wp-load.php';

$post_id = 3174;

header( 'Content-Type: application/json; charset=utf-8' );

$raw_meta = get_post_meta( $post_id );
$public_meta = array();

foreach ( $raw_meta as $key => $values ) {
	if ( strpos( $key, '_' ) === 0 ) {
		continue;
	}

	$public_meta[ $key ] = array_map(
		static function ( $value ) {
			return is_scalar( $value ) ? $value : wp_json_encode( $value );
		},
		$values
	);
}

echo wp_json_encode(
	array(
		'post_id'           => $post_id,
		'post_type'         => get_post_type( $post_id ),
		'template'          => get_post_meta( $post_id, '_wp_page_template', true ),
		'acf_loaded'        => function_exists( 'get_fields' ),
		'acf_fields'        => function_exists( 'get_fields' ) ? get_fields( $post_id ) : null,
		'acf_field_objects' => function_exists( 'get_field_objects' ) ? get_field_objects( $post_id ) : null,
		'public_meta'       => $public_meta,
	),
	JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);
