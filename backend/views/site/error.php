<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <div class="title-margin">
        <h3 style="display: inline"><?= Html::encode($this->title) ?></h3> 
    </div>

    <div class="text-index">

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            El error anterior ocurrió mientras el servidor web procesaba su solicitud.
        </p>
        <p>
            Póngase en contacto con nosotros si cree que se trata de un error del servidor. Gracias.
        </p>
        
        <br><br>
    </div>

</div>
