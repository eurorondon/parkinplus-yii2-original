<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::$app->name.' | Contacto';
/*
$this->params['breadcrumbs'][] = 'Contacto';
*/
?>
<div class="site-contact">   

  <div class="section-reserva">
    <div class="row">
      <div class=" col-lg-12">

        <div class="col-lg-7 col-md-12 col-xs-12"></div>          

        <div class="col-lg-5 col-md-12 col-xs-12">
          <div class="panel panel-default reservation d">
            <div class="panel-heading caja-title">Contáctenos</div>
            <div class="panel-body caja">  
              <p>
                Ante cualquier consulta o problema no dude en contactarnos vía telefónica o vía email.            
              </p>
              <hr>                         
              <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

              <?= $form->field($model, 'name')->textInput() ?>

              <?= $form->field($model, 'email') ?>

              <?= $form->field($model, 'subject') ?>

              <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

              <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6" style ="margin-left: 15px; margin-top: 7px">{input}</div></div>',
              ]) ?>

              <div class="row"> 
                <div class="col-lg-12 col-xs-12"><hr class="dash dash-contact"></div>
              </div>              

              <div align="right" class="form-group">
                <br>
                <?= Html::submitButton('Enviar Mensaje', ['class' => 'btn btn-success btn-block btn-big', 'name' => 'contact-button']) ?>
              </div>

              <?php ActiveForm::end(); ?>
            </div>
          </div>

        </div>


      </div>
    </div>
  </div>




  <div class="row">
    <div class="col-lg-7 col-xs-12">
   
      <div class="panel panel-default panel-datos2 pd" style="padding-bottom: 3px">
        <div class="panel-heading caja-title">Información de Contacto</div>
        <div class="panel-body cajac" style="padding-bottom: 20px">
          <div class="col-lg-6 col-xs-12">
            <br>
            <i class="glyphicon glyphicon-map-marker iconscontact"></i><span class="text-contact">Dirección: Calle Miguel de Cervantes 10.
              <br>CP 28860.
              <br></span><br><br><br>
              <i class="glyphicon glyphicon-phone iconscontact"></i><span class="text-contact">Asistencia en Aeropuerto: <br>+34 603 28 48 00</span><br><br><br> 
              <i class="fa fa-whatsapp" style="margin-left: 2px; font-size: 32px; color:#961007; margin-top: -2px"></i><span class="text-contact" style="margin-top: 1px"><a style="color: #333" target="_blank" href="https://api.whatsapp.com/send?phone=+34603284800&text=Estoy Interesado en los Servicios que Ofrecen !">+34 603 28 48 00</a></span>                                     
          </div>
          <div class="col-lg-6 col-xs-12">
              <br>
              <i class="glyphicon glyphicon-phone-alt iconscontact"></i><span class="text-contact">Reservas: <br><a class="link2" href="tel:+34912128659">+34 912 12 86 59</a></span><br><br><br>
              <i class="glyphicon glyphicon-phone-alt iconscontact"></i><span class="text-contact">Oficina: <br>+34 603 28 26 60  / +34 912 14 79 84</span><br><br><br>
              <i class="glyphicon glyphicon-envelope iconscontact"></i><span class="text-contact">Correo: <br>contacto@parkingplus.es</span>                         
          </div> 
        </div>
      </div>                       
    </div>


    <div class="col-lg-5 col-xs-12"></div>

    <div class="col-lg-12 col-xs-12 mt-4">
      
      <!--iframe class="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d47995.92021175082!2d-3.5948124381317887!3d40.46393905048995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422fcf3cba8035%3A0x86e010c61baa2eb5!2sParking%20plus!5e1!3m2!1ses!2sve!4v1619487388651!5m2!1ses!2sve" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe -->

      <!--
      <iframe class="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2992.912753717569!2d-3.5649989847675942!3d40.44421876189145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd423038e8e8ea8f%3A0x28a446f5ef13e474!2sCalle%20Mayo%2C%2080%2C%2028022%20Madrid%2C%20Espa%C3%B1a!5e1!3m2!1ses!2sve!4v1584390094214!5m2!1ses!2sve" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
      
      
      <iframe class="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d747.883773548728!2d-3.596656106026312!3d40.47514950786405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422fcf3cba8035%3A0x86e010c61baa2eb5!2sparkingplus!5e1!3m2!1ses-419!2sve!4v1576264358825!5m2!1ses-419!2sve" width="600" height="450" frameborder="0" style="border:0; margin-top: 50px" allowfullscreen=""></iframe>
      -->
    </div>



    </div>

  </div>
