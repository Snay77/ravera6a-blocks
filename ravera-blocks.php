<?php
/**
 * Plugin Name:       Ravera Blocks
 * Description:       A collection of blocks for the Ravera theme.
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            Ethan
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ravera-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ravera_blocks_init() {
	$dir = __DIR__ . '/build/gallery';

	wp_register_script(
		'ravera-gallery-editor',
		plugins_url( 'build/gallery/index.js', __FILE__ ),
		require $dir . '/index.asset.php',
		filemtime( $dir . '/index.js' )
	);

	if ( file_exists( $dir . '/index.css' ) ) {
		wp_register_style(
			'ravera-gallery-editor',
			plugins_url( 'build/gallery/index.css', __FILE__ ),
			[],
			filemtime( $dir . '/index.css' )
		);
	}

	if ( file_exists( $dir . '/style-index.css' ) ) {
		wp_register_style(
			'ravera-gallery-front',
			plugins_url( 'build/gallery/style-index.css', __FILE__ ),
			[],
			filemtime( $dir . '/style-index.css' )
		);
	}

	if ( file_exists( $dir . '/view.js' ) ) {
		wp_register_script(
			'ravera-gallery-view',
			plugins_url( 'build/gallery/view.js', __FILE__ ),
			[ 'wp-api-fetch' ],
			filemtime( $dir . '/view.js' ),
			true
		);
	}

	register_block_type( $dir . '/block.json', [
		'editor_script' => 'ravera-gallery-editor',
		'editor_style'  => 'ravera-gallery-editor',
		'style'         => 'ravera-gallery-front',
		'view_script'   => 'ravera-gallery-view',
	] );
}
add_action( 'init', 'ravera_blocks_init' );

/**
 * REST endpoint for progressive "load more" gallery.
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ravera/v1', '/gallery', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $request ) {
			$params = $request->get_json_params();
			if ( ! is_array( $params ) ) {
				$params = [];
			}

			$ids      = isset( $params['ids'] ) && is_array( $params['ids'] ) ? array_values( array_filter( array_map( 'intval', $params['ids'] ) ) ) : [];
			$page     = isset( $params['page'] ) ? max( 1, (int) $params['page'] ) : 1;
			$per_page = isset( $params['perPage'] ) ? max( 1, (int) $params['perPage'] ) : 9;
			$size     = isset( $params['size'] ) ? sanitize_key( (string) $params['size'] ) : 'large';

			if ( empty( $ids ) ) {
				return new WP_REST_Response(
					[
						'html'    => '',
						'hasMore' => false,
					],
					200
				);
			}

			$offset = ( $page - 1 ) * $per_page;
			$batch  = array_slice( $ids, $offset, $per_page );

			ob_start();
			foreach ( $batch as $id ) {
				echo '<div class="ravera-gallery__item">';
				echo wp_get_attachment_image( $id, $size );
				echo '</div>';
			}
			$html = ob_get_clean();

			$has_more = ( $offset + $per_page ) < count( $ids );

			return new WP_REST_Response(
				[
					'html'    => $html,
					'hasMore' => $has_more,
				],
				200
			);
		},
	] );
} );