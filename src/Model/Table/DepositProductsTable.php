<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Traits\ProductCacheClearAfterSaveAndDeleteTrait;
use Cake\Validation\Validator;
use App\Model\Traits\NumberRangeValidatorTrait;

/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under the GNU Affero General Public License version 3
 * For full copyright and license information, please see LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 1.0.0
 * @license       https://opensource.org/licenses/AGPL-3.0
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
class DepositProductsTable extends AppTable
{

    use ProductCacheClearAfterSaveAndDeleteTrait;
    use NumberRangeValidatorTrait;

    public function initialize(array $config): void
    {
        $this->setTable('deposits');
        parent::initialize($config);
        $this->setPrimaryKey('id_product');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator = $this->getNumberRangeValidator($validator, 'deposit', 0, 100);
        return $validator;
    }

}
