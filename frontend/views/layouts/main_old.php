<!DOCTYPE html>
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

<html lang="<?= Yii::$app->language ?>">

<head>
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PZXT4NX');</script>
        <!-- End Google Tag Manager -->


    <!-- End Google Tag Manager -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" refer>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" async>
    <link href="https://fonts.cdnfonts.com/css/barlow" rel="stylesheet" async>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css" async>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js" async></script>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <style>
        #parking.modal-dialog.modal-content.modal-header {
    display: block !important;
}
    </style>
    
    
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZXT4NX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php $this->beginBody() ?>
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="" style="    position: absolute;    padding-top: 1.5%;"><?= Html::img('@web/images/logoParking.png', ['loading' => 'lazy']) ?></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="https://parkingplus.es/aparcamiento/site/index#skills">COMO FUNCIONA</a></li>
                    <li><a class="nav-link scrollto" href="https://parkingplus.es/aparcamiento/site/index#services_ex">SERVICIOS</a></li>
                    <li><a class="nav-link scrollto" href="https://parkingplus.es/aparcamiento/site/index#atencion_cliente">ATENCION AL CLIENTE</a></li>
                    <li><a class="nav-link   scrollto" href="https://parkingplus.es/aparcamiento/site/index#contact">CONTACTO</a></li>
                    <li>
                        <?= Yii::$app->user->isGuest ? Html::a('INICIAR SESIÓN', ['site/login'], ['class' => 'getstarted scrollto']) : Html::a('CERRAR SESIÓN', ['site/logout'], ['class' => 'getstarted scrollto']) ?>
                      
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->
    <div class="wrape">
        <div class="container-fluid">
           <!-- <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?> -->
            <?= $content ?>
        </div>
    </div>
    <div id="preloader"></div>
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 footer-contact">
                        <div class="con" style="text-align: center;">
                            <div>
                                <a href=""><?= Html::img('@web/images/logofooter.png'); ?></a>
                            </div>
                            <div class="social-links mt-3">
                                <?= Html::img('@web/images/Facebook.png'); ?>
                                <?= Html::img('@web/images/Twitter.png'); ?>
                                <?= Html::img('@web/images/Instagram.png'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Parking Plus</h4> <br />
                        <h5 class="h5">Ha sido creada para entregar a nuestros clientes
                            el mejor servicio a precios muy económicos de corta y larga
                            estancia en el Aeropuerto de Madrid Barajas.</h5>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4><strong>Dirección:</strong></h4>
                        <h5 class="h5">Calle Miguel de Cervantes 10. CP 28860 </h5>
                        <h4><strong>Contacto:</strong></h4> <br />
                        <h5 class="h5">+34 603 28 26 60 / +34 912 12 86 59</h5>
                        <h4><strong>Correo</strong></h4>
                        <h5 class="h5">contacto@parkingplus.es</h5>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <div class="con">
                            <div style="margin-bottom: 5%; text-align: center;">
                                <h4>!RESERVA DESDE LA APP!</h4>
                            </div>
                            <div style="margin-bottom: 5%; text-align: center;">
                                <?= Html::img('@web/images/gplay.png', ['loading' => 'lazy']); ?>
                            </div>
                            <div style="margin-bottom: 5%; text-align: center;">
                                <?= Html::img('@web/images/appstore.png', ['loading' => 'lazy']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container footer-bottom clearfix">
            <div class="copyright">
                Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>. Todos los derechos reservados | <?= Html::a('Política de Privacidad', ['/site/privacidad'], ['target' => '_blank', 'style' => 'color: white;']) ?> | <?= Html::a('Política de Uso de
                Cookies', ['/site/cookies'], ['target' => '_blank', 'style' => 'color: white;']) ?> | <?= Html::a('Condiciones Generales de Contrato y Servicio', ['/site/condiciones'], ['target' => '_blank', 'style' => 'color: white;']) ?>
            </div>
        </div>
    </footer><!-- End Footer -->
    <?php $this->endBody() ?>
   
</body>
</html>
<?php $this->endPage() ?>