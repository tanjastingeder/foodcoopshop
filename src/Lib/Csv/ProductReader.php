<?php
declare(strict_types=1);

/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under the GNU Affero General Public License version 3
 * For full copyright and license information, please see LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 3.7.0
 * @license       https://opensource.org/licenses/AGPL-3.0
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
namespace App\Lib\Csv;

use League\Csv\Reader;
use Cake\Datasource\FactoryLocator;
use Cake\Core\Configure;

class ProductReader extends Reader {

    public const COLUMN_COUNT = 11;

    public function configureType(): void
    {
        $this->setHeaderOffset(0);
    }

    public function getPreparedRecords(): array
    {
        $records = $this->getRecords();
        $records = iterator_to_array($records);
        $records = array_values($records); // reindex array as 0 is dropped by iterator_to_array
        $preparedRecords = array_map([$this, 'formatColumns'], $records);
        return $preparedRecords;
    }

    private function formatColumns($record) {
        $record[__('Gross_price')] = Configure::read('app.numberHelper')->parseFloatRespectingLocale($record[__('Gross_price')]);
        $record[__('Tax_rate')] = Configure::read('app.numberHelper')->parseFloatRespectingLocale($record[__('Tax_rate')]);
        $record[__('Deposit')] = Configure::read('app.numberHelper')->parseFloatRespectingLocale($record[__('Deposit')]);
        return $record;
    }

    public function getAllErrors($entities)
    {
        $errors = [];
        foreach($entities as $entity) {
            if ($entity->hasErrors()) {
                $errors[] = $entity->getErrors();
            }
        }
        return $errors;
    }

    public function areAllEntitiesValid($entities)
    {
        $allEntitiesValid = true;
        foreach($entities as $entity) {
            if ($entity->hasErrors()) {
                $allEntitiesValid = false;
            }
        }
        return $allEntitiesValid;
    }

    public function import($manufacturerId)
    {
        $records = $this->getPreparedRecords();
        $productTable = FactoryLocator::get('Table')->get('Products');

        $validatedProductEntities = [];
        foreach($records as $record) {
            $validatedProductEntities[] = $productTable->getValidatedEntity(
                $manufacturerId,
                $record[__('Name')],
                $record[__('Description_short')],
                $record[__('Description')],
                $record[__('Unit')],
                $record[__('Product_declaration')],
                $record[__('Storage_location')],
                $record[__('Status')],
                $record[__('Gross_price')],
                $record[__('Tax_rate')],
                $record[__('Deposit')],
                $record[__('Amount')],
            );
        }

        $allProductEntitiesValid = $this->areAllEntitiesValid($validatedProductEntities);
        if ($allProductEntitiesValid) {
            $savedProductEntities = [];
            foreach($validatedProductEntities as $validatedProductEntity) {
                $savedProductEntities[] = $productTable->save($validatedProductEntity);
            }
            return $savedProductEntities;
        }

        return $validatedProductEntities;
    }

}

?>