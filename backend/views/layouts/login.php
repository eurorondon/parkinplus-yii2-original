<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background: #f4f7f9;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        .login-page-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            /* Ancho ideal de la tarjeta */
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .brand-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .brand-icon {
            height: 60px;
            width: auto;
        }

        .brand-name {
            text-align: left;
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1.1;
            margin: 0;
            text-transform: uppercase;
        }

        .brand-name span {
            color: #b72025;
            /* El rojo de tu logo */
        }

        /* --- CORRECCIÓN DE LOS INPUTS --- */
        .login-content {
            width: 100%;
        }

        .site-login .row {
            margin-left: 0;
            margin-right: 0;
        }

        .site-login .col-lg-4 {
            float: none;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .site-login .col-lg-offset-4 {
            margin-left: 0;
        }

        /* Forzamos a que el contenedor de Yii y el input ocupen todo el ancho */
        .form-group,
        .field-loginform-username,
        .field-loginform-password {
            width: 100% !important;
            margin-bottom: 20px !important;
            text-align: left;
            /* Etiquetas a la izquierda */
        }

        .form-control {
            width: 100% !important;
            /* Esto soluciona lo angosto */
            display: block;
            height: 50px !important;
            border-radius: 8px !important;
            border: 1px solid #d1d5db !important;
            padding: 10px 15px !important;
            font-size: 1rem !important;
            box-sizing: border-box;
            /* Asegura que el padding no rompa el ancho */
        }

        .form-control:focus {
            border-color: #b72025 !important;
            box-shadow: 0 0 0 3px rgba(183, 32, 37, 0.1) !important;
            outline: none;
        }

        .control-label {
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 6px;
            display: block;
        }

        /* --- BOTÓN ROJO PROFESIONAL --- */
        .btn-primary,
        button[type="submit"] {
            width: 100% !important;
            height: 50px;
            background-color: #b72025 !important;
            /* Rojo corporativo */
            border: none !important;
            border-radius: 8px !important;
            color: white !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #931a1e !important;
        }

        /* Estilo para las alertas de error de Yii2 */
        .help-block-error {
            font-size: 13px;
            color: #dc2626;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="login-page-wrapper">
        <div class="login-card">

            <header class="login-header">
                <div class="brand-container">
                    <?= Html::img('@web/images/logo_login.png', ['class' => 'brand-icon']); ?>
                    <h1 class="brand-name">PARKING<br><span>PLUS</span></h1>
                </div>
                <p style="color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-top: -20px; margin-bottom: 30px;">Gestión de Aparcamiento</p>
            </header>

            <section class="login-content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </section>

            <footer style="margin-top: 30px; font-size: 10px; color: #9ca3af; text-transform: uppercase;">
                &copy; <?= date('Y') ?> Madrid Parking System
            </footer>

        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
