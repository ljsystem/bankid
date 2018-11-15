<?php

namespace LJSystem\BankID;

return [
    'environment' => env('BANKID_ENVIRONMENT', BankID::ENVIRONMENT_TEST),

    'environments' => [
        BankID::ENVIRONMENT_PRODUCTION => [
            'certificate' => env('BANKID_CERTIFICATE', storage_path('/certificates/bankid/private/prod.pem')),
            'root_certificate' => env('BANKID_ROOT_CERTIFICATE', storage_path('/certificates/bankid/public/prod_cacert.pem')),
        ],

        BankID::ENVIRONMENT_TEST => [
            'certificate' => env('BANKID_CERTIFICATE', resource_path('/certificates/bankid/public/test.pem')),
            'root_certificate' => env('BANKID_ROOT_CERTIFICATE', resource_path('/certificates/bankid/public/test_cacert.pem')),
        ],
    ],
];
