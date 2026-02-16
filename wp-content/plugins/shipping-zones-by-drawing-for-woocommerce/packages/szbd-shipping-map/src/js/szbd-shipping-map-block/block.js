/**
 * External dependencies
 */

import { CART_STORE_KEY } from '@woocommerce/block-data';
import { CHECKOUT_STORE_KEY } from '@woocommerce/block-data';
import { extensionCartUpdate } from '@woocommerce/blocks-checkout';
import { useEffect, useState, useCallback } from '@wordpress/element';

import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { debounce } from 'lodash';
import  _ from 'lodash';

import { sprintf } from '@wordpress/i18n';

import { createInterpolateElement, renderToString } from '@wordpress/element';

import {
	ValidatedTextInput,
	Spinner,
	TextInput,
} from '@woocommerce/blocks-checkout';

import { useEffectOnce, useUpdateEffect } from 'usehooks-ts';
import { useIsMounted } from 'usehooks-ts';

import { getSetting } from '@woocommerce/settings';



export const Block = ({ checkoutExtensionData, extensions }) => {




	// Get data analog 'localized'
	const { szbd_precise_address_mandatory, szbd_precise_address, szbd_precise_address_plus_code } = getSetting('szbd-shipping-map_data', '');




	const { __internalIncrementCalculating, __internalDecrementCalculating } =
		useDispatch('wc/store/checkout');

	const debouncedSetExtensionData = useCallback(
		debounce((namespace, data) => {
			extensionCartUpdate(namespace, data);
		}, 1000),
		[extensionCartUpdate]
	);

	const { setExtensionData } = checkoutExtensionData;

	const [latlng, setLatLng] = useState({});

	const [pluscode, setPluscode] = useState('');

	const [debug, setDebug] = useState('');
	const validationErrorId = 'szbd-no-picked-location';
	const { setValidationErrors, clearValidationError,showValidationError } = useDispatch(
		'wc/store/validation'
	);
	const {setIsCartDataStale, selectShippingRate,updatingCustomerData,applyExtensionCartUpdate, shippingRatesBeingSelected} = useDispatch( CART_STORE_KEY );

	const validationError = useSelect((select) => {
		const store = select('wc/store/validation');

		return store.getValidationError(validationErrorId);


	});
	const cartStore = useSelect((select) => {
		let store = select(CART_STORE_KEY);


		return store;
	});
	const checkoutStore = useSelect((select) => {
		let store = select(CHECKOUT_STORE_KEY);


		return store;
	});
	

	const onChangePluscode = (val) => {
		if (val == 'updateserver') {
			setPluscode('');
			extensionCartUpdate({
				namespace: 'szbd-shipping-map-update',
				data: {
					lat: null,
					lng: null

				},
			});
		}
		else if (val == 'empty') {
			setPluscode('');

			return;
		} else {
			setPluscode(val);
		}


		/* extensionCartUpdate( {
	 namespace: 'szbd-shipping-message-pluscode',
	 data: {
		 pluscode: val,
		
		 
	 },
 } );*/
	};
	
	function isJsonString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}

	// When point changes. Update state variable and maybe send to server.
	const onChangeLatLng = (e) => {

		var location;

		if (e.target.value == 'empty') {
			setLatLng({});
			return;
		} else {
			if (!isJsonString(e.target.value)) {
				return;
			}
			location = JSON.parse(e.target.value);
			setLatLng(location);
		}
		// Check if to send incoming point to server 
		if (e.nativeEvent.data.notToServer) {

			return;
		}
		
		applyExtensionCartUpdate({
			namespace: 'szbd-shipping-map-update',
			data: {
				lat: location.lat,
				lng: location.lng,

			},
		}).then(function(data){
			/*
			const rates = data.shipping_rates;
			
				const localPickupIsSelected = _.find(
					rates[0].shipping_rates,
					{ method_id: 'pickup_location', selected: true }
				);
	
			
	
				
				const legal_method = _.find(
					rates[0].shipping_rates,
					function ( rate ) {
						return rate.method_id != 'pickup_location';
					}
				);
			
				if (
					(! _.isUndefined( localPickupIsSelected ) || ! _.isUndefined( notAnyMethods ) ) &&
					! _.isUndefined( legal_method )
				) {
					alert('set new method');
					// If mode is shipping and there are valid shipping rates -> select first one
					selectShippingRate(
						legal_method.rate_id,
						rates[0].package_id
					);
				}
				*/
			
		});


		/*
		extensionCartUpdate({
			namespace: 'szbd-shipping-map-update',
			data: {
				lat: location.lat,
				lng: location.lng,

			},
		}).then(function(){
			
		});
		*/
	};

	const latlng2 = useSelect((select) => {
		const store = select(CART_STORE_KEY);

		
		return store.getCartData();
	});



	const isMounted = useIsMounted();




	// Set extensiondata when point updates
	useUpdateEffect(() => {
		maybe_set_validation_error();

	}, [latlng, latlng2]);



	// Set extensiondata when point updates
	useEffect(() => {
		
		setExtensionData('szbd', 'pluscode', pluscode);


	}, [setExtensionData, pluscode]);

function maybe_set_validation_error(){

	setExtensionData('szbd', 'point', latlng);

	if (
		szbd_precise_address == 'no' ||
		szbd_precise_address_mandatory == 'no'

	) {
		
		if (validationError) {
			clearValidationError(validationErrorId);
		}
		return;
	}
	
	if (

		Object.keys(latlng).length != 0

	) {
		
		if (validationError) {
			clearValidationError(validationErrorId);
		}
		return;
	}

	// Here loop thru rates and find if selected method is local pickup and then DO NOT add validation error

	const localPickupIsSelected = !_.isUndefined(latlng2.shippingRates[0]) ? _.find(latlng2.shippingRates[0].shipping_rates, { method_id: "local_pickup", selected: true }): undefined;
	

	const test = !_.isUndefined(localPickupIsSelected) || map_hidden();
	

	if (test) {

		

		if (validationError) {
			clearValidationError(validationErrorId);
		}
		return;
	}

	if (validationError) {
		return;
	}

	setValidationErrors({
		[validationErrorId]: {
			message: __('Please pick a shipping location on the map.', 'szbd'),
			hidden: true,
		},
	});
	
		
	
}


	useEffect(() => {

		const handleUpdate = () => {
			

			
			const loc = !_.isUndefined(latlng2) && !_.isNull(latlng2) && !_.isUndefined(latlng2.extensions) && !_.isUndefined((latlng2.extensions)['szbd-shipping-map']) ? (latlng2.extensions)['szbd-shipping-map'].shipping_point : null;
			// Return if location data has it´s origin from user interaction
			if (!_.isNull(loc) && _.has(loc, 'fromUI')) {

				return;
			}
			let has_address = has_full_address(latlng2);

			// Trigger event when a new point comes from the server
			jQuery(document).trigger('szbd_map_update_blocks', [latlng2, has_address]);

		};
		// Perform actions after the document has loaded
		const handleLoad = () => {

	
			if (isMounted()) {

				let has_address = has_full_address(latlng2);
				// Trigger event to update map on page load
				
				jQuery(document).trigger('szbd_map_loaded_blocks', [
					latlng2, has_address
				]);
			}
			maybe_set_validation_error();
		};

		window.addEventListener('load', handleLoad);
		window.addEventListener('szbd_map_loaded', handleLoad);
		handleUpdate();

		return () => {
			window.removeEventListener('load', handleLoad);
			window.removeEventListener('szbd_map_loaded', handleLoad);
		};
	}, [latlng2, isMounted]);

	// Primitive check if address if filled
	function has_full_address(data) {

		return data.shippingAddress.address_1 == '' ? false : true;
	}

	


	useEffectOnce(() => {
		

		if (cartStore.getNeedsShipping()) {

			const event = new Event("szbd_map_loaded");


			window.dispatchEvent(event);
		}



	}, [cartStore]);

	

	function map_hidden() {

		if (szbd_precise_address == 'always') {
			return false;
		}
		
		const el = document.getElementById('szbd_checkout_field');
		const style = window.getComputedStyle(el);
		const mapHidden = (style.display === 'none');
		
		if (mapHidden) {
			return true;
		} else {
			return false;
		}


	}
	

	if (true) {

		const map_title = szbd_precise_address_mandatory == 'yes' ? __('Please Precise Your Shipping Location', 'szbd') : __('Precise Address?', 'szbd');

		// Return the block
		return (
			<div className='szbd-shipping-details-block'>


				<div>
					<input
						id={'szbd-picked'}
						onChange={(e) => {
							onChangeLatLng(e);
						}}
						value={latlng}
						type="text"
						className={'szbd-hidden' +
							(validationError?.hidden === false
								? ' has-error'
								: '')
						}
					/>
				</div>
				
				<div id="szbd_checkout_field" className={(szbd_precise_address == 'at_fail'
					? ' at_fail'
					: '')}>

					{szbd_precise_address_plus_code == 'yes' ? (

						<div className={'szbd-pluscode'}>
							<TextInput
								id="szbd-plus-code"
								type="text"
								required={false}
								className={'szbd-plus-code-form'}
								label={__(
									'Find Location with Google Plus Code',
									'szbd'
								)}
								value={pluscode}
								onChange={(e) => {
									onChangePluscode(e);
								}}
							/>
						</div>
					) : ''}

					{validationError?.hidden ? null : (
					<div className="wc-block-components-validation-error" role="alert">
						<p>
							{validationError?.message}
						</p>

					</div>
				)}

					<div className='wc-block-components-title szbd-map-title-block'>
						{
							map_title
						}
					</div>
					<div id="szbd-pick-content">
						<div className={'szbd-checkout-map'}>
							<div id={'szbd_map'}></div>
						</div>
					</div>
				</div>

			</div>
		);
	}


};






