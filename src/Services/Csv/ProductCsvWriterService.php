<?php
declare(strict_types=1);

/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under the GNU Affero General Public License version 3
 * For full copyright and license information, please see LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 4.1.0
 * @license       https://opensource.org/licenses/AGPL-3.0
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
namespace App\Services\Csv;

use Cake\Datasource\FactoryLocator;
use App\Services\DomDocumentService;
use App\Model\Entity\Product;
use Cake\Core\Configure;

class ProductCsvWriterService extends BaseCsvWriterService
{

    private $productIds;

    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
    }

    public function getHeader()
    {
        return [
            __('Id'),
            __('Product'),
            __('Manufacturer'),
            __('Unit'),
            __('Amount'),
            __('Gross_price'),
            __('for'),
            __('Stock_value'),
        ];
    }

    public function getRecords()
    {
        $productsTable =FactoryLocator::get('Table')->get('Products');
        $products = $productsTable->getProductsForBackend(
            productIds: $this->productIds,
            manufacturerId: 'all',
            active: 'all',
            addProductNameToAttributes: true,
        );

        $domDocumentService = new DomDocumentService();
        $records = [];
        foreach ($products as $product) {

            $isMainProduct = $productsTable->isMainProduct($product);
            if ($isMainProduct && !empty($product->product_attributes)) {
                continue;
            }

            $domDocumentService->loadHTML($product->name);
            $productName = $domDocumentService->getItemByClass('product-name')->item(0)?->nodeValue;
            $unit = $domDocumentService->getItemByClass('unity-for-dialog')->item(0)?->nodeValue ?? $domDocumentService->getItemByClass('quantity-in-units')->item(0)?->nodeValue ?? '';
            $availableQuantity = $product->stock_available->quantity;
            
            if (!$isMainProduct) {
                $explodedName = explode(Product::NAME_SEPARATOR, $product->name);
                if (count($explodedName) == 2) {
                    $unit = $explodedName[1];
                }   
                if ($product->unit && $product->unit->price_per_unit_enabled) {
                    $productName = $product->unchanged_name;
                    $unit = $product->name;
                }
            }

            $sellingPriceGross = $this->getSellingPriceGross($product);

            $records[] = [
                $product->id_product,
                $productName,
                $product->manufacturer->name,
                $unit,
                $availableQuantity,
                Configure::read('app.numberHelper')->formatAsDecimal($sellingPriceGross),
                $this->getUnitForPrice($product),
                Configure::read('app.numberHelper')->formatAsDecimal($this->getStockValue($product, $sellingPriceGross, $availableQuantity)),
            ];
        }

        return $records;
    }

    private function getSellingPriceGross($product)
    {
        $sellingPriceGross = $product->gross_price;
        if ($product->unit && $product->unit->price_per_unit_enabled) {
            $sellingPriceGross = $product->unit->price_incl_per_unit;
        }
        return $sellingPriceGross;
    }

    private function getStockValue($product, $price, $availableQuantity)
    {
        if ($availableQuantity <= 0) {
            return 0;
        }

        if ($product->unit && $product->unit->price_per_unit_enabled) {
            $price = Configure::read('app.pricePerUnitHelper')->getPricePerUnit($product->unit->price_incl_per_unit, $product->unit->quantity_in_units, $product->unit->amount);
        }

        $stockValue = $price * $availableQuantity;
        
        return $stockValue;
    }

    private function getUnitForPrice($product) {
        $unitForPrice = '';
        if ($product->unit && $product->unit->price_per_unit_enabled) {
            $unitForPrice = $product->unit->amount . ' ' . $product->unit->name;
        }
        return $unitForPrice;
    }

}