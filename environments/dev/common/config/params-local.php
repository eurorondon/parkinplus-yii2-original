<?php
return [
    'redsys' => [
        'merchantKey' => getenv('REDSYS_MERCHANT_KEY') ?: '',
        'fuc' => getenv('REDSYS_FUC') ?: '',
        'terminal' => getenv('REDSYS_TERMINAL') ?: '',
        'paymentUrl' => getenv('REDSYS_PAYMENT_URL') ?: 'https://sis-t.redsys.es:25443/sis/realizarPago',
        'currency' => getenv('REDSYS_CURRENCY') ?: '978',
    ],
];
