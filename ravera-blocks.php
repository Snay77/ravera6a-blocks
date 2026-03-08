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
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register blocks from the metadata collection (WP 6.8+).
 */
function create_block_ravera_blocks_block_init() {
	wp_register_block_types_from_metadata_collection(
		__DIR__ . '/build',
		__DIR__ . '/build/blocks-manifest.php'
	);
}
add_action( 'init', 'create_block_ravera_blocks_block_init' );

/**
 * REST endpoint for progressive "load more" gallery.
 *
 * POST /wp-json/ravera/v1/gallery
 * Body: { ids: number[], page: number, perPage: number, size: string }
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