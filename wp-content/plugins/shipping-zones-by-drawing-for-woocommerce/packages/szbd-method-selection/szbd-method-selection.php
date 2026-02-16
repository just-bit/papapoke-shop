<?php
/**
 * Plugin Name:     SZBD Method selection
 * Version:         1.1
 * Author:          Arosoft.se
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     szbd
 *
 * @package         create-block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define SHIPPING_WORKSHOP_VERSION.
$plugin_data = get_file_data( __FILE__, array( 'version' => 'version' ) );
define( 'SHIPPING_METHOD_SELECTION_VERSION', $plugin_data['version'] );

/**
 * Include the dependencies needed to instantiate the block.
 */
add_action(
	'woocommerce_blocks_loaded',
	function() {
		require_once __DIR__ . '/szbd-method-selection-blocks-integration.php';
		add_action(
			'woocommerce_blocks_checkout_block_registration',
			function( $integration_registry ) {
				$integration_registry->register( new Szbd_Method_Selection_Blocks_Integration() );
			}
		);
	}
);

/**
 * Registers the slug as a block category with WordPress.
 */
function register_Szbd_Method_Selection_block_category( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'szbd-method-selection',
				'title' => __( 'Szbd_method_selection Blocks', 'szbd' ),
			],
		]
	);
}
add_action( 'block_categories_all', 'register_Szbd_Method_Selection_block_category', 10, 2 );
