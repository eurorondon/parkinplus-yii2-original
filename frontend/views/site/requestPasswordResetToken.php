<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name.' | Recuperación de Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
            <div class="login-content">
                <h3>Recuperar Contraseña</h3>
                <hr>     
                <p>Por favor complete su correo electrónico. Allí se enviará un enlace para restablecer la contraseña.</p><br>

                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div align="right" class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-warning']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
