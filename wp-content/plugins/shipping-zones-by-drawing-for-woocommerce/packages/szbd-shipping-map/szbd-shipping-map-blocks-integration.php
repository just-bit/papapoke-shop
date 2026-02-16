<?php
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 */
class SZBD_Shipping_Map_Blocks_Integration implements IntegrationInterface
{

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name()
	{
		return 'szbd-shipping-map';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize()
	{
		require_once __DIR__ . '/szbd-shipping-map-extend-store-endpoint.php';
		$this->register_szbd_shipping_map_block_frontend_scripts();
		$this->register_szbd_shipping_map_block_editor_scripts();
		$this->register_szbd_shipping_map_block_editor_styles();


		$this->register_main_integration();
		$this->extend_store_api();

		$this->save_shipping_instructions();


	}

	/**
	 * Extends the cart schema to include the shipping-workshop value.
	 */
	private function extend_store_api()
	{
		SZBD_Shipping_Map_Extend_Store_Endpoint::init();
	}



	/**
	 * Registers the main JS file required to add filters and Slot/Fills.
	 */
	private function register_main_integration()
	{
		$script_path = '/build/index.js';
		$style_path = '/build/style-index.css';

		$script_url = plugins_url($script_path, __FILE__);
		$style_url = plugins_url($style_path, __FILE__);

		$script_asset_path = dirname(__FILE__) . '/build/index.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_path),
			];

		wp_enqueue_style(
			'szbd-shipping-map-blocks-integration',
			$style_url,
			[],
			$this->get_file_version($style_path)
		);

		wp_register_script(
			'szbd-shipping-map-blocks-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'szbd-shhipping-map-blocks-integration',
			'szbd',
			dirname(__FILE__) . '/languages'
		);


	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles()
	{
		return ['szbd-shipping-map-blocks-integration', 'szbd-shipping-map-block-frontend'];
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles()
	{
		return ['szbd-shipping-map-blocks-integration', 'szbd-shipping-map-block-editor'];
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data()
	{
		$data = [
			'szbd-shipping-map-active' => true,
			'szbd_precise_address_mandatory' => get_option('szbd_precise_address_mandatory', 'no'),
			'szbd_precise_address' => get_option('szbd_precise_address', 'no'),
			'szbd_debug' => get_option('szbd_debug', 'no'),
			'szbd_precise_address_plus_code' => get_option('szbd_precise_address_plus_code', 'no'),
			


			'defaultLabelText' => __('Shipping Zones by drawing map', 'szbd'),
		];

		return $data;

	}

	public function register_szbd_shipping_map_block_editor_styles()
	{
		$style_path = '/build/style-szbd-shipping-map-block.css';

		$style_url = plugins_url($style_path, __FILE__);
		wp_enqueue_style(
			'szbd-shipping-map-block',
			$style_url,
			[],
			$this->get_file_version($style_path)
		);
	}

	public function register_szbd_shipping_map_block_editor_scripts()
	{
		$script_path = '/build/szbd-shipping-map-block.js';
		$script_url = plugins_url($script_path, __FILE__);
		$script_asset_path = dirname(__FILE__) . '/build/szbd-shipping-map-block.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_asset_path),
			];

		wp_register_script(
			'szbd-shipping-map-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'szbd-shipping-map-block-editor',
			'szbd-shipping-map',
			dirname(__FILE__) . '/languages'
		);
	}

	public function register_szbd_shipping_map_block_frontend_scripts()
	{
		$script_path = '/build/szbd-shipping-map-block-frontend.js';
		$script_url = plugins_url($script_path, __FILE__);
		$script_asset_path = dirname(__FILE__) . '/build/szbd-shipping-map-block-frontend.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_asset_path),
			];


		wp_register_script(
			'szbd-shipping-map-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'szbd-shipping-map-block-frontend',
			'szbd',
			dirname(__FILE__) . '/languages'
		);
	}

	private function save_shipping_instructions()
	{

		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			function ($order, $request) {
			//	return;
			//	$shipping_workshop_request_data = $request['extensions'][$this->get_name()];

			//	$latlng = $shipping_workshop_request_data['point'];

				//$order->update_meta_data('szbd_picked_delivery_location_', stripslashes($latlng));





				//$order->save();
			},
			10,
			2
		);
	}


	protected function get_file_version($file)
	{
		if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG && file_exists($file)) {
			return filemtime($file);
		}
		return SZBD_SHIPPING_MAP_VERSION;
	}
}