<?php
/**
 * - this file contains the specific configuration for your foodcoop
 * - configurations in config.php can be overriden in this file
 * - please rename it to "custom_config.php" to use it
 *
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

return [
    'debug' => false,
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => '',
            'port' => 25,
            'timeout' => 30,
            'username' => '',
            'password' => '',
            'client' => null,
            'tls' => null,
        ]
    ],
    'Email' => [
        'default' => [
            'from' => [], // [email-address => name] syntax necessary (not only [email]
        ]
    ],
    'Datasources' => [
        'default' => [
            'host' => 'database-dev',
            'username' => 'user',
            'password' => 'secret',
            'database' => 'foodcoopshop-dev',
            'port' => 3310,
        ],
        'test' => [
            'host' => 'database-test',
            'username' => 'user',
            'password' => 'secret',
            'database' => 'foodcoopshop-test',
            'port' => 3311,
        ],
    ],

    /**
     * A random string used in security hashing methods.
     */
    'Security' => [
        'salt' => ''
    ],

    'Cache' => [
        'default' => [
            'prefix' => 'example_com_',
        ],
        'short' => [
            'prefix' => 'example_com_',
        ],
        '_cake_core_' => [
            'prefix' => 'example_com_',
        ],
        '_cake_model_' => [
            'prefix' => 'example_com_',
        ],
    ],

    'app' => [

        'discourseSsoEnabled' => false,

        /**
         * A random string used for Discourse SSO
         */
        'discourseSsoSecret' => '',

        /**
         * your host's name, eg. http://www.yourfoodcoop.com
         */
        'cakeServerName' => 'https://localhost',

        /**
         * cronjob needs to be activated / deactivated too if you change emailOrderReminderEnabled
         * @see https://foodcoopshop.github.io/en/cronjobs
         */
        'emailOrderReminderEnabled' => true,

        /**
         * valid options of array: 'cashless' or 'cash' (or both but this is not recommended)
         */
        'paymentMethods' => [
            'cashless'
        ]

    ]
];
