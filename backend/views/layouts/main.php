<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use common\widgets\Alert;
use common\models\UserAfiliados;


AppAsset::register($this);

$id_usuario = Yii::$app->user->id;

$buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
if (!empty($buscarAfiliado)) {
    $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
} else {
    $tipo_afiliado = 0;     
}

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
        'innerContainerOptions' => ['class' => 'container-fluid'],
        'brandLabel' => Html::img('@web/images/logo_login.png', ['class'=>'img img-responsive logo-main']).Html::tag('div', Html::encode(Yii::$app->name), ['class' => 'titulo']).Html::tag('div', Html::encode('Aparcamiento en Madrid'), ['class' => 'minititulo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

        if ($tipo_afiliado == 0) {
            $menuItems = [
                ['label' => 'INICIO', 'url' => ['/site/index']],
                ['label' => 'CLIENTES', 'url' => ['/clientes/index']],
                ['label' => 'VEHÍCULOS', 'url' => ['/coches/index']],
                ['label' => 'PLANES', 'url' => ['/listas-precios/index']],
                ['label' => 'PRECIOS TEMPORADAS', 'url' => ['/precios-temporadas/index']],
                ['label' => 'SERVICIOS', 'url' => ['/servicios/index']],
                ['label' => 'RESERVAS', 'url' => ['/reservas/index']],
                ['label' => 'FACTURAS', 'url' => ['/facturas/index']],
                //['label' => 'Usuarios', 'url' => ['/user/index']],
                //['label' => 'Mis Datos', 'url' => ['/user/datos']],
            ];
        } else {
            $menuItems = [
                ['label' => 'INICIO', 'url' => ['/site/index']],
                ['label' => 'RESERVAS', 'url' => ['/reservas/index']],
            ];            
        }

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'INICIAR SESIÓN', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'SALIR (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }


    ?>

    <?php 
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
 
        <div class="col-lg-12">
            <div align="center">Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>. Todos los derechos reservados.</div>
        </div>               
        

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
