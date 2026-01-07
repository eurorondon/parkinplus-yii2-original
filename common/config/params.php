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
        'fuc' => '999008881',
        'terminal' => '001',
        'currency' => '978',
        'paymentUrl' => 'https://sis-t.redsys.es:25443/sis/realizarPago',
        'merchantKey' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
    ],
    'user.passwordResetTokenExpire' => 3600,
    'cronEmailLimitPerHour' => 100,
];
