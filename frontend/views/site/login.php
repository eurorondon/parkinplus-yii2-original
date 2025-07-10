<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name.' | Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
            <div class="login-content">
                <h3>Iniciar Sesión</h3>
                <hr> 

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
      
                    <div style="color:#999;margin:1em 0">
                        <?= Html::a('¿ Olvido la Contraseña ?', ['site/resetPassword']) ?>
                        <br>
                        ¿Necesita un nuevo correo electrónico de verificación ? <?= Html::a('Reenviar', ['site/resend-verification-email']) ?>
                    </div>

                    <div align="right" class="form-group">
                        <?= Html::submitButton('Entrar', ['class' => 'btn btn-warning btn-block', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
