<?php
/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 2.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */

echo '<td class="status">';

    if ($product->active == 1) {
        echo $this->Html->link(
            '<i class="fas fa-check-circle ok"></i>',
            'javascript:void(0);',
            [
                'class' => 'btn btn-outline-light set-status-to-inactive product-status-edit',
                'id' => 'product-status-edit-' . $product->id_product,
                'title' => __d('admin', 'deactivate'),
                'escape' => false
            ]
        );
    } else {
        echo $this->Html->link(
            '<i class="fas fa-minus-circle ok"></i>',
            'javascript:void(0);',
            [
                'class' => 'btn btn-outline-light set-status-to-active product-status-edit',
                'id' => 'product-status-edit-' . $product->id_product,
                'title' => __d('admin', 'activate'),
                'escape' => false
            ]
        );
    }

echo '</td>';

?>