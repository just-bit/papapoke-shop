/**
 * External dependencies
 */

import { CART_STORE_KEY } from '@woocommerce/block-data';
import { CHECKOUT_STORE_KEY } from '@woocommerce/block-data';
import { extensionCartUpdate } from '@woocommerce/blocks-checkout';
import { useEffect, useState, useCallback } from '@wordpress/element';
import { find,isUndefined} from "lodash";
import { getBlockType } from '@wordpress/blocks';

import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { getSetting } from '@woocommerce/settings';



export const Block = ( { checkoutExtensionData, extensions } ) => {

	const { map_feature_active } = getSetting('szbd-method-selection_data', '');
	
	const _ = require('lodash');
	const { setExtensionData } = checkoutExtensionData;

	

	const validationErrorId = 'szbd-no-picked-location';

	const { setValidationErrors, clearValidationError, showValidationError } =
		useDispatch( 'wc/store/validation' );

	const validationError = useSelect( ( select ) => {
		const store = select( 'wc/store/validation' );

		return store.getValidationError( validationErrorId );
	} );

	const prefersCollection = useSelect( ( select ) => {
		let store = select( CHECKOUT_STORE_KEY );

		return store.prefersCollection();
	} );
	const getShippingRates = useSelect( ( select ) => {
		let store = select( CART_STORE_KEY );

		return store.getShippingRates();
	} );

	const { selectShippingRate, setIsCartDataStale, setCartData,shippingRatesBeingSelected } =
		useDispatch( CART_STORE_KEY );

	useEffect( () => {
		
		//When shipping rates changes, check if it is not collection and then select neew method
		if (  prefersCollection === false  ) {
			if(_.isUndefined(getShippingRates[ 0 ])){
				return;
			}
			const localPickupIsSelected = _.find(
				getShippingRates[ 0 ].shipping_rates,
				{ method_id: 'pickup_location', selected: true }
			);

			

			
			const legal_method = _.find(
				getShippingRates[ 0 ].shipping_rates,
				function ( rate ) {
					return rate.method_id != 'pickup_location';
				}
			);
		
			if (
			! _.isUndefined( localPickupIsSelected )   &&
				! _.isUndefined( legal_method )
			) {
				
				// If mode is shipping and there are valid shipping rates -> select first one
				selectShippingRate(
					legal_method.rate_id,
					getShippingRates[ 0 ].package_id
				);
			}
		}
	}, [ getShippingRates ] );
	

	useEffect( () => {
		
		// Clear shipping data when it is pickup location mode
		if ( prefersCollection ) {
			try{

			if(map_feature_active ){
				
				setExtensionData( 'szbd', 'point', null );
				setExtensionData( 'szbd', 'pluscode', '' );
				extensionCartUpdate( {
					namespace: 'szbd-shipping-map-update',
					data: {
						lat: null,
						lng: null,
					},
				} ).then(function(e){});
			}
			
		}catch(e){}
			if ( validationError ) {
				clearValidationError( validationErrorId );
			}
		}
	}, [
		prefersCollection,
		validationError,
		setValidationErrors,
		validationErrorId,
		clearValidationError,
	] );

	return null;
};

