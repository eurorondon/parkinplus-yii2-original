<?php

use yii\helpers\Html;

$this->title = Yii::$app->name.' | Precios';
?>
<div class="precios-index">
  <div class="section-reserva">
    <div class="row">
      <div class=" col-lg-12">

        <div class="col-lg-7 col-md-12 col-xs-12"></div>          

        <div class="col-lg-5 col-md-12 col-xs-12">
          <?= Html::img('@web/images/24hours.png', ['class'=>'img img-responsive img-premium2', 'style'=>'display:inline; padding: 10px']);?>
          <div class="panel panel-default reservation" style="padding: 10px">
            <div class="panel-heading caja-title">Servicio Premium</div>
            <div class="panel-body caja">  
              <p margin-top: 25px">Recogida y entrega de su coche en la puerta de la Terminal Salidas del Aeropuerto Barajas.</p>
              <p>El mejor precio del mercado con servicio de conductores en la puerta del aeropuerto de cualquiera de las terminales de Madrid T1, T2 ó T4.</p>
            </div>
            <div class="panel-heading caja-title">Servicio 24/7</div>
            <div class="panel-body caja">  
              <p style="margin-top: 5px">Nuestro servicio funciona las 24 horas continuamente, sea cual sea el horario de su vuelo tanto en la ida como a su regreso con Parking Plus nunca tendrá problemas de esperas.</p>
            </div>            
          </div>

        </div>


      </div>
    </div>
  </div>
  <div class="row">

    <div class="col-lg-12">

      <div class="title-s-extras">
        <h3 style="display: inline">Servicios Extras para su Coche</h3> 
      </div>
    </div>

    <div class="col-lg-12 col-xs-12"><div class="tit-precios">LIMPIEZA DE SU COCHE</div></div>

    <div class="col-lg-3 col-xs-12">
      <?= Html::img('@web/images/lavado_auto.jpg', ['class'=>'img img-responsive img-premium-service', 'style'=>'display:inline; padding: 5px']);?>
    </div>

    <div class="col-lg-5 col-xs-12">
      <p class="interior"><b>Limpieza Exterior</b> - Lavado a presión de la carrocería, llantas y cristales de todo el exterior.</p><div class="price1"><?= $model[0]->costo ?>&nbsp;€</div>
    </div> 

    <div class="col-lg-12"></div>

    <div class="col-lg-3"> 
      <?= Html::img('@web/images/interior.jpg', ['class'=>'img img-responsive img-premium-service2', 'style'=>'display:inline; padding: 5px']);?>
    </div>

    <div class="col-lg-5 col-xs-12">
      <p class="interior-exterior"><b>Limpieza Interior / Exterior</b>  - Lavado a presión de la carrocería, llantas y cristales. Limpieza interior salpicadero, aspirado completo incluído maletero. Aplicación de brillo en neumáticos. No incluye tapicerías.</p><div class="price2"><?= $model[1]->costo ?>&nbsp;€</div>
    </div>

    <div class="col-xs-12"></div>

    <div class="col-lg-3 col-xs-12">
      <?= Html::img('@web/images/limpieza_interior.jpg', ['class'=>'img img-responsive img-premium-service3', 'style'=>'display:inline; padding: 5px']);?>    				
    </div>

    <div class="col-lg-9 col-xs-12">
      <p class="completa"><b>Limpieza Completa y Tapicería (Solo asientos)</b> - Lavado a presión de la carrocería, llantas y cristales.Limpieza interior salpicadero, aspirado completo incluído maletero. Aplicación de brillo en neumáticos. Limpieza Tapicería SOLO ASIENTOS. Tratamiento higiene habitáculo. Acaros y Bacterias</p><div class="price3"><?= $model[2]->costo ?>&nbsp;€</div>
    </div>

    <div class="col-lg-12"></div>

    <div class="col-lg-6 col-xs-12 service4">
      <div class="col-lg-12">
        <hr style="border: 1px solid #ccc;">
        <div class="tit-precios2">FUNDA PROTECTORA COCHE</div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <?= Html::img('@web/images/fundascoches.jpg', ['class'=>'img img-responsive img-premium-service4', 'style'=>'display:inline; padding: 5px']);?>				
      </div>
      <div class="col-lg-6 col-xs-12">
        <p class="funda">Protección contra inclemencias climatológicas o roces - (Precio por Día).</p><div class="price4">1.5&nbsp;€</div>
      </div>
    </div>

    <div class="col-lg-6 col-xs-12 service4">
      <div class="col-lg-12">
        <hr style="border: 1px solid #ccc">
        <div class="tit-precios3">SERVICIO CONDUCTOR</div>
      </div> 
      <div class="col-lg-6 col-xs-12">
        <p class="ser-conductor">Servicio de recogida ó entrega de su coche en la terminal.</p><div class="price5">15.00&nbsp;€</div>
      </div>        
      <div class="col-lg-6 col-xs-12">
        <?= Html::img('@web/images/chofer.jpg', ['class'=>'img img-responsive img-premium-service5', 'style'=>'display:inline; padding: 5px']);?>				
      </div>
    </div>
  </div>

</div><!-- precios-index -->
