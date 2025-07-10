<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name.' | Reenviar correo electrónico de verificación';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
            <div class="login-content">
                <h3>Reenviar Correo de Verificación</h3>
                <hr> 

                <p>Por favor complete su correo electrónico. Se enviará un correo electrónico de verificación.</p><br>

                <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div align="right" class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-warning']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
