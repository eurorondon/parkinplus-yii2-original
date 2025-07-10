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
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        'brandLabel' => Html::img('@web/images/logo_login.png', ['class'=>'img img-responsive logo-main']).Html::tag('div', Html::encode(Yii::$app->name), ['class' => 'titulo']).Html::tag('div', Html::encode('¡ Estaciona con Confianza !'), ['class' => 'minititulo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            ['label' => 'Quiénes Sómos', 'url' => ['/site/about']],
            ['label' => 'Servicios', 'url' => ['/site/services']],
            ['label' => 'Precios', 'url' => ['/site/prices']],
            ['label' => 'Blog', 'url' => ['/site/blog']],
            ['label' => 'Contacto', 'url' => ['/site/contact']],
        ];
    } else {
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            ['label' => 'Coches', 'url' => ['/coches/index']],
            ['label' => 'Clientes', 'url' => ['/clientes/index']],
            ['label' => 'Planes', 'url' => ['/listas-precios/index']],
            ['label' => 'Servicios', 'url' => ['/servicios/index']],
            ['label' => 'Reservas', 'url' => ['/reservas/index']],
            ['label' => 'Facturas', 'url' => ['/facturas/index']],
            //['label' => 'Usuarios', 'url' => ['/user/index']],
            //['label' => 'Mis Datos', 'url' => ['/user/datos']],
        ];        
    }
    ?>
    <div class="padmin">
        <span class="panel-admin">Panel Administrativo</span>
    </div>
    <div class="otherItems info">
        <p>
            <a href="mailto:reservas@parkingplus.es">
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
    <div class="container">
        <div class="footer-page" align="center">Copyright © Parking Plus 2019. Todos los derechos reservados.</div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
