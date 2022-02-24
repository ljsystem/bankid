<?php

namespace LJSystem\BankID;

return [
    'environment' => env('BANKID_ENVIRONMENT', BankID::ENVIRONMENT_TEST),

    'environments' => [
        BankID::ENVIRONMENT_PRODUCTION => [
            'certificate' => env('BANKID_CERTIFICATE', storage_path('/certificates/bankid/private/prod.cer')),
            'root_certificate' => env('BANKID_ROOT_CERTIFICATE', storage_path('/certificates/bankid/public/prod_cacert.pem')),
            'key' => env('BANKID_KEY', storage_path('certificates/bankid/private/prod.key')),
            'passphrase' => env('BANKID_PASSPHRASE'),
        ],
    ],
];
