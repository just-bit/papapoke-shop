<?php
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema;




/**
 * Shipping Map Extend Store API.
 */
class SZBD_Shipping_Map_Extend_Store_Endpoint {
	
	const IDENTIFIER = 'szbd-shipping-map';

	
	public static function init() {
		
		self::extend_store();
	
	}

	
	public static function extend_store() {

		

		$args = array(
			'endpoint'        => CartSchema::IDENTIFIER,
			'namespace'       => 'szbd-shipping-map',
			'data_callback'   => function() {
	
				
				
				$m =   WC()
				->session
				->get('szbd_delivery_address');
				$formatted_address = szbd_get_customer_formatted_address();
				
				return array(
					'shipping_point' =>  $m,
					'formatted_address' => $formatted_address
				);
			},
			'schema_callback' => function() {
				return array(
					'properties' => array(
						'shipping_point' => array(
							'type' => 'string',
						),
						'formatted_address' => array(
							'type' => 'string',
						),
					),
				);
			},
			'schema_type'     => ARRAY_A,
		);

		
		woocommerce_store_api_register_update_callback(
			$args
		);


		$debug_args = array(
			'endpoint' => CartSchema::IDENTIFIER,
			'namespace' => 'szbd-shipping-debug',
			'data_callback' => function () {
	
				$m = WC()
					->session
					->get('szbd_server_request_debug');
				return array(
					'debug' => [$m],
				);
			},
			'schema_callback' => function () {
				return array(
					'properties' => array(
						'debug' => array(
							'type' => 'string',
						),
					),
				);
			},
			'schema_type' => ARRAY_A,
		);
	
	
		woocommerce_store_api_register_update_callback(
			$debug_args
		);

		

		
	}


	
}

