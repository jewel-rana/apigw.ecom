<?php

return [
    'name' => 'Customer',
    'default_status' => 'pending',
    'statuses' => ['pending', 'active', 'banned', 'inactive'],
    'restricted' => [
        'accounts' => explode(',', env('KARTAT_RESTRICTED_ACCOUNTS_MARKETPLACE', '+9647704768290,+96474963210000')),
        'service_type_ids' => explode(',', env('KARTAT_RESTRICTED_SERVICE_MARKETPLACE', '3,4,6'))
    ]
];
