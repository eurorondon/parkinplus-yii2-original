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
     <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PZXT4NX');</script>
<!-- End Google Tag Manager -->


    <!-- End Google Tag Manager -->

    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/frontend/web/images/favicon1.ico"
        type="image/x-icon" />



    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/barlow" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style>
        .reserva__price__movil {
            display: none;
        }

        ol.stepper {
            --default-b: lightgrey;
            --default-c: black;
            --active-b: #961007;
            --active-c: white;
            --circle: 3.5em;
            /* size of circle */
            --b: 5px;
            /* line thickness */

            list-style: none;
            justify-content: space-between;
            display: grid;
            gap: 20px;
            background: linear-gradient(var(--default-b) 0 0) no-repeat calc((var(--circle) - var(--b)) / 2) 50% / var(--b) 100%;

            counter-reset: step;
            margin: 20px;
            padding: 0;
            font-size: 16px;
            font-weight: bold;
            counter-reset: step;
            overflow: hidden;
        }

        ol.stepper li {
            display: flex;
            place-items: center;
            gap: 5px;
            font-family: sans-serif;
            position: relative;
        }

        ol.stepper li::before {
            content: counter(step) " ";
            counter-increment: step;
            display: grid;
            place-content: center;
            aspect-ratio: 1;
            height: var(--circle);
            border: 5px solid #fff;
            box-sizing: border-box;
            background: var(--active-b);
            color: var(--active-c);
            border-radius: 50%;
            font-family: monospace;
            z-index: 1;
        }

        ol.stepper li.active~li::before {
            background: var(--default-b);
            color: var(--default-c);
        }

        ol.stepper li.active::after {
            content: "";
            position: absolute;
            width: var(--b);
            bottom: 100%;
            left: calc((var(--circle) - var(--b)) / 2);
            top: auto;
            right: auto;
            height: 100vw;
            background: var(--active-b);
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

            <h1 class="logo me-auto">
                <a href="" style=" position: absolute;padding-top: 2%;">
                    <?= Html::img('@web/images/logoParking.png', ['style'=> 'height:60px; width: 160px']) ?>
                </a>
            </h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

            <nav id="navbar" class="navbar">
                <ul>
                    <?php
                    if (Yii::$app->user->isGuest) {
                        ?>
                        <li><a class="nav-link scrollto active"
                                href="<?php echo Url::base(true) ?>/site/index#skills">COMO FUNCIONA</a></li>
                        <li><a class="nav-link scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#services_ex">SERVICIOS</a></li>
                        <li><a class="nav-link scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#atencion_cliente">ATENCION AL
                                CLIENTE</a></li>
                        <li><a class="nav-link   scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#contact">CONTACTO</a></li>
                        <!-- li><?= Html::a('INICIAR SESIÓN', ['site/login'], ['class' => 'getstarted scrollto']); ?></li-->
                        <?php
                    } else {
                        ?>
                        <li><a class="nav-link scrollto active"
                                href="<?php echo Url::base(true) ?>/site/index#skills">COMO FUNCIONA</a></li>
                        <li><a class="nav-link scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#services_ex">SERVICIOS</a></li>
                        <li><a class="nav-link scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#atencion_cliente">ATENCION AL
                                CLIENTE</a></li>
                        <li><a class="nav-link   scrollto"
                                href="<?php echo Url::base(true) ?>/site/index#contact">CONTACTO</a></li>
                        <li><?= Html::a('CERRAR SESIÓN', ['site/logout'], ['class' => 'getstarted scrollto']); ?></li>
                        <?php
                    }
                    ?>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->
    <div class="wrape">

        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <div id="preloader"></div>
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-md-6 footer-contact">
                        <div class="con col-md-9"
                            style="text-align: center;padding:0 12px;display: flex;flex-direction: column;justify-content: center;">
                            <div>
                                <a href=""><?= Html::img('@web/images/logoParking.png', ['style' => 'width: 70%']); ?></a>
                            </div>
                            <!--div class="social-links mt-3">
                                <?= Html::img('@web/images/Facebook.png'); ?>
                                <?= Html::img('@web/images/Twitter.png'); ?>
                                <?= Html::img('@web/images/Instagram.png'); ?>
                            </div-->
                            <div class="footer-links">
                                <h5 class="h5">Ha sido creada para entregar a nuestros clientes
                                    el mejor servicio a precios muy económicos de corta y larga
                                    estancia en el Aeropuerto de Madrid Barajas.</h5>
                            </div>
                        </div>

                        <div class="footer__social col-md-3">
                            <ul>
                                <li>
                                    <a href="https://www.facebook.com/parkingplus" target="_blank">FaceBook</a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">Twitter</a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">Linkedin</a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/parkingplus" target="_blank">Instagram</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 footer-links">
                        <h4><strong>Dirección:</strong></h4>
                        <h5 class="h5">Calle Miguel de Cervantes 10. CP 28860 </h5>
                        <h4><strong>Contacto:</strong></h4> <br />
                        <h5 class="h5">+34 603 28 26 60 / +34 912 12 86 59</h5>
                        <h4><strong>Correo</strong></h4>
                        <h5 class="h5">contacto@parkingplus.es</h5>
                    </div>

                    <div class="col-lg-3 col-md-3 footer-links">
                        <div class="con">
                            <div style="margin-bottom: 5%; text-align: center;">
                                <h4>!RESERVA DESDE LA APP!</h4>
                            </div>
                            <div style="margin-bottom: 5%; text-align: center;">
                                <?= Html::img('@web/images/gplay.png', ['style' => 'width: 70%%']); ?>
                            </div>
                            <div style="margin-bottom: 5%; text-align: center;">
                                <?= Html::img('@web/images/appstore.png', ['style' => 'width: 70%%']); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container footer-bottom clearfix">
            <div class="copyright">
                Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>. Todos los derechos reservados |
                <?= Html::a('Política de Privacidad', ['/site/privacidad'], ['target' => '_blank', 'style' => 'color: white;']) ?>
                | <?= Html::a('Política de Uso de
                Cookies', ['/site/cookies'], ['target' => '_blank', 'style' => 'color: white;']) ?> |
                <?= Html::a('Condiciones Generales de Contrato y Servicio', ['/site/condiciones'], ['target' => '_blank', 'style' => 'color: white;']) ?>
            </div>
        </div>
    </footer><!-- End Footer -->


    <?php $this->endBody() ?>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
<?php $this->endPage() ?>