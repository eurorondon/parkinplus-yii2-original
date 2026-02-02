<?php

use yii\helpers\Html;

$this->title = 'ParkingPlus — Eliminación de cuenta y datos';
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<section class="policy-app" style="max-width: 900px; margin: 0 auto; padding: 30px 15px; font-family: 'Open Sans', sans-serif;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5em; text-align: center;">ParkingPlus — Eliminación de cuenta y datos</h1>
    <p style="text-align: center; margin-bottom: 2em;">Última actualización: <?= date('d/m/Y') ?></p>

    <h2 style="font-size: 1.5rem; margin-top: 1.5em;">1. Solicitud de eliminación</h2>
    <p>
        Esta página describe el proceso para solicitar la eliminación de tu cuenta de ParkingPlus y los datos asociados.
        Si deseas borrar tu cuenta, sigue los pasos indicados a continuación.
    </p>

    <h2 style="font-size: 1.5rem; margin-top: 1.5em;">2. Pasos para solicitar la eliminación</h2>
    <ol>
        <li>
            Envía un correo a
            <a href="mailto:contacto@parkingplus.es?subject=Solicitud%20de%20eliminaci%C3%B3n%20de%20cuenta%20ParkingPlus">
                contacto@parkingplus.es
            </a>
            con el asunto <strong>“Solicitud de eliminación de cuenta ParkingPlus”</strong>.
        </li>
        <li>
            Incluye tu nombre completo, el correo con el que registraste la cuenta y, si es posible, tu número de teléfono
            de contacto.
        </li>
        <li>
            Verificaremos tu identidad y te confirmaremos la solicitud. El proceso puede tardar hasta 30 días naturales,
            dependiendo del volumen de solicitudes.
        </li>
    </ol>

    <h2 style="font-size: 1.5rem; margin-top: 1.5em;">3. Datos que se eliminarán</h2>
    <p>Al aprobarse la solicitud, eliminaremos o anonimizaremos los siguientes datos asociados a tu cuenta:</p>
    <ul>
        <li>Datos de perfil (nombre, correo electrónico, teléfono).</li>
        <li>Vehículos registrados.</li>
        <li>Historial de reservas y preferencias dentro de la app.</li>
        <li>Comunicaciones de soporte vinculadas a tu cuenta.</li>
    </ul>

    <h2 style="font-size: 1.5rem; margin-top: 1.5em;">4. Datos que podrían conservarse</h2>
    <p>
        Algunos datos deben conservarse por obligaciones legales o de seguridad:
    </p>
    <ul>
        <li>
            Registros contables y de facturación asociados a reservas pagadas, conservados por el plazo legal aplicable
            (por ejemplo, hasta 6 años según normativa contable/fiscal).
        </li>
        <li>
            Registros mínimos de auditoría necesarios para prevenir fraudes o resolver reclamaciones legales durante los
            plazos legales de prescripción.
        </li>
    </ul>

    <h2 style="font-size: 1.5rem; margin-top: 1.5em;">5. Contacto</h2>
    <p>
        Si tienes dudas sobre este proceso, puedes escribirnos a:
        <strong><a href="mailto:contacto@parkingplus.es">contacto@parkingplus.es</a></strong>.
    </p>
    <p>
        ParkingPlus (Marichal 4 Parking S.L.) atenderá tu solicitud conforme a la normativa vigente.
    </p>
</section>
