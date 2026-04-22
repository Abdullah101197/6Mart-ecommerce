<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vendor types allowlist (feature keys)
    |--------------------------------------------------------------------------
    |
    | This file defines which feature-keys are allowed for a given store
    | vendor_type. Subscription packages still further restrict access via
    | the existing `subscription:` middleware. Effective access should be:
    | allowed_by_vendor_type && allowed_by_subscription.
    |
    */

    'default' => 'shopkeeper',

    'types' => [
        'shopkeeper' => [
            // Existing vendor capabilities (core)
            'order',
            'item',
            'pos',
            'category',
            'addon',
            'campaign',
            'banner',
            'coupon',
            'wallet',
            'wallet_method',
            'employee',
            'deliveryman',
            'deliveryman_list',
            'role',
            'advertisement',
            'advertisement_list',
            'store_setup',
            'notification_setup',
            'profile',
            'my_shop',
            'expense_report',
            'disbursement_report',
            'vat_report',
            'reviews',
            'chat',
        ],

        'manufacturer' => [
            // Unified vendor panel modules (same routes, gated by vendor_type + subscription)
            'order',
            'item',
            'pos',
            'ai_pulse',
            'omnichannel',
            'returns',
            'helpcenter',
            'category',
            'addon',
            'banner',
            'campaign',
            'coupon',
            'wallet',
            'wallet_method',
            'profile',
            'my_shop',
        ],

        'wholesale' => [
            // Start same as shopkeeper (tighten later as you add B2B modules)
            'order',
            'item',
            'pos',
            'ai_pulse',
            'omnichannel',
            'returns',
            'helpcenter',
            'category',
            'addon',
            'campaign',
            'banner',
            'coupon',
            'wallet',
            'wallet_method',
            'employee',
            'deliveryman',
            'deliveryman_list',
            'role',
            'advertisement',
            'advertisement_list',
            'store_setup',
            'notification_setup',
            'profile',
            'my_shop',
            'expense_report',
            'disbursement_report',
            'vat_report',
            'reviews',
            'chat',
        ],

        'b2b' => [
            // Start same as wholesale (tighten later as you add B2B modules)
            'order',
            'item',
            'pos',
            'ai_pulse',
            'omnichannel',
            'returns',
            'helpcenter',
            'category',
            'addon',
            'campaign',
            'banner',
            'coupon',
            'wallet',
            'wallet_method',
            'employee',
            'deliveryman',
            'deliveryman_list',
            'role',
            'advertisement',
            'advertisement_list',
            'store_setup',
            'notification_setup',
            'profile',
            'my_shop',
            'expense_report',
            'disbursement_report',
            'vat_report',
            'reviews',
            'chat',
        ],
    ],
];

