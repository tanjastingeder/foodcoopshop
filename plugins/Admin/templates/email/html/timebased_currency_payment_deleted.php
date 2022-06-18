<?php
/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under the GNU Affero General Public License version 3
 * For full copyright and license information, please see LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 2.1.0
 * @license       https://opensource.org/licenses/AGPL-3.0
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, https://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
use Cake\Core\Configure;

?>
<?php echo $this->element('email/tableHead'); ?>
<tbody>

        <?php echo $this->element('email/greeting', ['data' => $data]); ?>

        <tr>
        <td>

            <p>
                Deine Zeit-Eintragung vom <b>
                    <?php echo $payment->created->i18nFormat(Configure::read('app.timeHelper')->getI18Format('DateNTimeShort')); ?>
                </b>
                <?php if ($payment->working_day) { ?>
                    (Arbeitstag: <b><?php echo $payment->working_day->i18nFormat(Configure::read('app.timeHelper')->getI18Format('DateLong2')); ?></b>)
                <?php } ?>
                wurde von <?php echo $appAuth->getUsername(); ?> gelöscht.
            </p>

            <p>
                Hier der Link zu deinem <?php echo $this->TimebasedCurrency->getName(); ?><br />
                <a href="<?php echo Configure::read('app.cakeServerName').$this->Slug->getMyTimebasedCurrencyBalanceForCustomers(); ?>"><?php echo Configure::read('app.cakeServerName').$this->Slug->getMyTimebasedCurrencyBalanceForCustomers(); ?></a>
            </p>

        </td>

    </tr>

</tbody>
<?php echo $this->element('email/tableFoot'); ?>
