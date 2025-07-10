<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);

?>
<div class="verify-email">
    <p>Bienvenidos a Parking Plus,</p>

    <p>Estimado Cliente hemos recibido una reservacion a través de nuestro sistema en linea.</p>

    <p>Si desea verificar sus reservas u otros productos asociados a la misma puede iniciar sesión en nuestro sistema con los siquientes datos:</p>

    <p>Usuario : <?= $user->username ?></p>
    <p>Contraseña : N° de Documento de Identidad</p>

    <p>Para cualquier duda o sugerencia no dude en contactarnos</p>

    <p>Reservaciones: reservas@parkingplus.es<br> +34 603 28 26 60</p>
</div>
