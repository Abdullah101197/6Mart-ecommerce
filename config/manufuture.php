<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Manufuture Portal Feature Flags
    |--------------------------------------------------------------------------
    |
    | Keep legacy admin/vendor portals working during rollout. Turn on the
    | cutover switches only when you’re ready to make Manufuture the default.
    |
    */

    'admin_default' => (bool) env('MANUFUTURE_ADMIN_DEFAULT', false),
    'vendor_default' => (bool) env('MANUFUTURE_VENDOR_DEFAULT', false),
];

