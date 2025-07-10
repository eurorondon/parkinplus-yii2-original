<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Carousel;
use yii\widgets\Breadcrumbs;
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
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'innerContainerOptions' => ['class' => 'container-fluid menu-parking'],
        //'brandLabel' => '<img src="images/minilogo.png"; class="img-responsive">',
        'brandLabel' => Html::img('@web/images/logo_login.png', ['class'=>'img img-responsive logo-main']).Html::tag('div', Html::encode(Yii::$app->name), ['class' => 'titulo']).Html::tag('div', Html::encode('Aparcamiento en Madrid'), ['class' => 'minititulo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            ['label' => 'Quiénes Sómos', 'url' => ['/site/about']],
            ['label' => 'Servicios', 'url' => ['/site/servicios']],
            ['label' => 'Precios', 'url' => ['/site/precios']],
            ['label' => 'Contacto', 'url' => ['/site/contact']],
        ];
    } else {
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/panel']],
            ['label' => 'Mis Reservas', 'url' => ['/site/reservas']],
            ['label' => 'Mis Facturas', 'url' => ['/site/facturas']],
            ['label' => 'Mis Datos', 'url' => ['/site/datos']],
        ];        
    }

    ?>
    <div class="dir">
        <i class="fa fa-map-marker icons"></i>
        <span class="location"> 
            &nbsp; Dirección : Calle Playa de Riazor, 12 - 14<br> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 28042, Barajas - Madrid
        </span>
    </div>
    <div class="otherItems info">
        <p>
            <a class="mail_reserva" href="mailto:reservas@parkingplus.es">
                <span><i class="fa fa-envelope icons" aria-hidden="true"></i></span>&nbsp; reservas@parkingplus.es
            </a>
            <a class="redes" style="margin-left: 40px;" href="https://www.facebook.com/parkingplus/" target="_blank">
                <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a class="redes" href="https://twitter.com/parkingplus" target="_blank">
                <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a class="redes" href="https://www.instagram.com/parkingplus1/" target="_blank">
                <i class="fa fa-instagram" aria-hidden="true"></i>
            </a> 
            <span class="contact">
                <i class="glyphicon glyphicon-phone-alt icons" style="margin-right: 10px" aria-hidden="true"></i>Reservas: +34 60 328 26 60  | Asistencia en Aeropuerto: +34 60 328 48 00
            </span>
            <?php if (Yii::$app->user->isGuest) { ?>
            <a class="redes" style="margin-left: 30px;" href=<?= Url::toRoute('site/login'); ?>>
                <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
            </a>                
            <a href=<?= Url::toRoute('site/login'); ?>>
                <span class="acceso">Iniciar Sesión</span>
            </a>
            <?php } else { ?>
                <?= Html::a('<i class="glyphicon glyphicon-off" aria-hidden="true"></i>', ['site/logout'], ['class'=> 'off', 'data' => ['method' => 'post']]);  ?>               
                <?= Html::a('<span class="salir">Salir</span>', ['site/logout'], ['data' => ['method' => 'post']]);
            } ?>
        </p>
    </div>
    <?php 
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="col-lg-2">
            <p align="center">
                <?= Html::img('@web/images/logo_pplus.jpg', ['class'=>'img img-responsive img-minilogo']);?>
            </p>
        </div>
        <div class="col-lg-3" style="margin-left: 20px">
            <ul>
                <li class="title-footer">Política de Privacidad</li>
                <li class="title-footer">Condiciones Generales de Contrato y Servicio</li>
                <li class="title-footer">Uso de Cookies</li>
                <li class="title-footer">Parking Larga Estancia T2 Madrid</li>
            </ul>
        </div>
        <div class="col-lg-3" style="margin-left: 20px">
            <ul>
                <li class="title-footer">Aparcamiento Larga Estancia Madrid</li>
                <li class="title-footer">Parking Larga Estancia Barajas T4</li>
                <li class="title-footer">Aparcamiento Larga Estancia T4</li>
                <li class="title-footer">Precio Parking Larga Estancia Barajas</li>
            </ul>
        </div>
        <div class="col-lg-3" style="margin-left: 20px">
            <ul>
                <li class="title-footer">Parking Madrid Aeropuerto</li>
                <li class="title-footer">Parking Barato Aeropuerto Madrid</li>
                <li class="title-footer">Parking Larga Estancia Barajas T1</li>
                <?= Html::img('@web/images/payments.png', ['class'=>'img img-responsive img-pay']);?>
            </ul>
        </div> 
        <div class="col-lg-12">
            <hr class="page-footer" />
            <p align="center">Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>. Todos los derechos reservados.</p>
        </div>               
        

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
