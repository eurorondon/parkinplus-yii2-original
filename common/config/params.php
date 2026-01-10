<?php
return [
    'adminEmail' => 'administrador@parkingplus.es',
    'contactEmail' => 'contacto@parkingplus.es',
    'supportEmail' => 'sistemas@parkingplus.es',
    'reservasEmail' => 'reservas@parkingplus.es',
    'facturacionEmail' => 'facturas@parkingplus.es',
    'senderEmail' => 'noreply@parkingplus.es',
    'senderName' => 'Parking Plus',
    'frontendBaseUrl' => getenv('FRONTEND_BASE_URL') ?: 'https://parkingplus.es/aparcamiento',
    'redsys' => [
        'fuc' => getenv('REDSYS_FUC') ?: '',
        'terminal' => getenv('REDSYS_TERMINAL') ?: '',
        'currency' => getenv('REDSYS_CURRENCY') ?: '',
        'merchantKey' => getenv('REDSYS_MERCHANT_KEY') ?: '',
        'paymentUrl' => getenv('REDSYS_PAYMENT_URL') ?: 'https://sis-t.redsys.es:25443/sis/realizarPago',
        'testMaxAmount' => getenv('REDSYS_TEST_MAX_AMOUNT') !== false
            ? (float) getenv('REDSYS_TEST_MAX_AMOUNT')
            : 5.00,
    ],
    'user.passwordResetTokenExpire' => 3600,
    'cronEmailLimitPerHour' => 100,
];
