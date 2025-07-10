<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name.' | Cambiar Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
            <div class="login-content">
                <h3>Cambiar Contraseña</h3>
                <hr>      
                <p>Por favor elija su nueva contraseña :</p><br>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div align="right" class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-warning']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
