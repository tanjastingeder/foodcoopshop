<?php
/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 2.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
use Cake\Core\Configure;

$this->element('addScript', ['script' =>
    Configure::read('app.jsNamespace').".SelfService.init();".
    Configure::read('app.jsNamespace').".AppFeatherlight.addLightboxToCkeditorImages('.product-wrapper .toggle-content.description img');".
    Configure::read('app.jsNamespace').".AppFeatherlight.initLightboxForImages('.product-wrapper a.lightbox');".
    Configure::read('app.jsNamespace').".Helper.bindToggleLinks();".
    Configure::read('app.jsNamespace').".Helper.initProductAttributesButtons();".
    Configure::read('app.jsNamespace').".Cart.initAddToCartButton();".
    Configure::read('app.jsNamespace').".Cart.initRemoveFromCartLinks();"
]);
echo $this->element('timebasedCurrency/addProductTooltip', ['selectorClass' => 'timebased-currency-product-info']);
?>


<div id="products">
	<div class="header">
    	<h2><a href="/<?php echo __('route_self_service'); ?>"><?php echo __('Self_service_for_stock_products'); ?></a></h2>
    	<h1><span><?php echo count($products); ?> <?php echo __('found'); ?></span></h1>
    	<?php echo $this->element('productSearch', ['action' => __('route_self_service')]); ?>
    	<hr />
    </div>
    <?php
    foreach ($products as $product) {
        echo $this->element('product/product', ['product' => $product]);
    }
?>
</div>