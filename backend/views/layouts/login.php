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
<div class="wrap-login">
    <div class="nav navbar-inverse fixed-top barra">
        <a href=<?= Url::toRoute('site/index'); ?>>
            <?= Html::img('@web/images/txtparking.png', ['class'=>'img img-responsive parking-login']);?>
            <span class="text-lema-login">Aparcamiento en Madrid</span>
            <?= Html::img('@web/images/logo_login.png', ['class'=>'img img-responsive logo-login']);?>
            <?= Html::img('@web/images/txtplus.png', ['class'=>'img img-responsive plus-login']);?>
        </a>
    </div>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
