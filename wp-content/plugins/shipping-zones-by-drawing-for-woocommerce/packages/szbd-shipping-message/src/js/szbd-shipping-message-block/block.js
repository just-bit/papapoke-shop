/**
 * External dependencies
 */
import { CART_STORE_KEY } from "@woocommerce/block-data";
import { CHECKOUT_STORE_KEY } from "@woocommerce/block-data";
import { extensionCartUpdate } from "@woocommerce/blocks-checkout";
import { useEffect, useState, useCallback } from "@wordpress/element";
import { SelectControl, TextareaControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useSelect, useDispatch } from "@wordpress/data";
import _  from "lodash";


import { sprintf } from "@wordpress/i18n";

import { createInterpolateElement, renderToString } from "@wordpress/element";



export const Block = ({ checkoutExtensionData, extensions }) => {
  

  const shippingMessage = useSelect((select) => {
    try{
    const store = select(CART_STORE_KEY);

    return store.getCartData().extensions["szbd-shipping-message"].message;
    }catch(e){
      return '';
    }
  });

  const noShippingmethods = useSelect((select) => {
    const store = select(CART_STORE_KEY);

    const shippingPackages = store.getShippingRates();

    /*const packagesWithRates = shippingPackages.find(function (p) {
      if (p.shipping_rates.length !== 0) {
        return true;
      }
    });*/
   
    const rates = _.reject(_.first(shippingPackages)
      .shipping_rates, function (p) {

        return p.method_id == 'pickup_location';


      });



    return _.isEmpty(rates);
  });



  if (noShippingmethods && shippingMessage != '') {
    return (
      <div className="wc-block-components-shipping-rates-control__no-results-notice wc-block-components-notice-banner is-warning">
        {shippingMessage}
      </div>
    );
  }
};
