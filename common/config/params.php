<?php
return [
    'adminEmail' => 'administrador@parkingplus.es',
    'contactEmail' => 'contacto@parkingplus.es',
    'supportEmail' => 'sistemas@parkingplus.es',
    'reservasEmail' => 'reservas@parkingplus.es',
    'facturacionEmail' => 'facturas@parkingplus.es',
    'senderEmail' => 'noreply@parkingplus.es',
    'senderName' => 'Parking Plus',
    'frontendBaseUrl' => getenv('FRONTEND_BASE_URL') ?: 'https://ngplus.es/aparcamiento',
    'user.passwordResetTokenExpire' => 3600,
    'cronEmailLimitPerHour' => 20,
];
