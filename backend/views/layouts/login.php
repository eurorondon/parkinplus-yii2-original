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
        /* Estilos globales */
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-page-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 15px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .brand-icon {
            height: 64px;
            width: auto;
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1;
            margin: 0;
            text-transform: uppercase;
            text-align: left;
        }

        /* Cambio de color a Rojo para PLUS */
        .brand-name span {
            color: #e74c3c;
        }

        .brand-tagline {
            font-size: 0.8rem;
            color: #6c757d;
            letter-spacing: 1.5px;
            margin: 0;
            text-transform: uppercase;
        }

        /* Estilización de Formulario Yii2 */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-control {
            height: 48px !important;
            border-radius: 8px !important;
            border: 1px solid #ced4da !important;
            padding: 10px 15px !important;
        }

        /* Borde de foco también en rojo suave */
        .form-control:focus {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.1) !important;
        }

        /* Botón principal en ROJO para hacer match */
        .btn-primary {
            width: 100%;
            height: 48px;
            border-radius: 8px !important;
            font-weight: 600 !important;
            background-color: #e74c3c !important;
            border: none !important;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff !important;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #c0392b !important;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
        }

        .help-block-error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="login-page-wrapper">
        <div class="login-card">

            <header class="login-header">
                <div class="brand-container">
                    <?= Html::img('@web/images/logo_login.png', ['class' => 'brand-icon', 'alt' => 'Logo']); ?>
                    <h1 class="brand-name">PARKING<br><span>PLUS</span></h1>
                </div>
                <p class="brand-tagline">Gestión de Aparcamiento</p>
            </header>

            <section class="login-content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </section>

            <footer style="margin-top: 2.5rem; text-align: center; font-size: 0.7rem; color: #adb5bd; letter-spacing: 1px;">
                &copy; <?= date('Y') ?> MADRID PARKING SYSTEM
            </footer>

        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>