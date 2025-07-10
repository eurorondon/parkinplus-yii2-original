<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
	<style type="text/css">
		body, html {
			background-color: #ececec;
			color: #000;
		}	
		.notificacion {
			text-align: center;
			margin-left: auto; 
			margin-right: auto;	
			display: flex;
		  	justify-content: center;
		}

		table.tablenot {
			width: 600px;
			border-spacing: 0px !important;
			border-collapse: collapse;
			border: none;
		}

		td.celda {
			padding: 0px;
			background-color: #fff;
			display: block;
			margin-top: -5px;
		}

		.content {
			display: block;
			border-left: 1px solid #ccc; 
			border-right: 1px solid #ccc; 
			margin-top: -5px;		
		}		

		.inf-reserva {
			text-align: left;
			padding: 20px;
			margin-top: -5px;
		}

		.saludo {
			font-size: 1.4em;
			text-align: left;
			text-transform: capitalize;
			padding: 0 20px 30px 20px;
		}

		.mensaje {
			font-size: 1em;
			padding: 0 20px;
			text-align: justify !important;
		}

		.op {
			font-weight: bold;
			margin-top: 30px;
			padding: 0 20px;
			font-size: 1em;
			text-align: left;
		}

		.evalua {
			font-weight: bold;
			padding: 15px 20px;
			margin: 30px 20px;
			background-color: #333;
			color: #fff;
			font-size: 1em;
			text-align: left;
			border-radius: 6px;
		}

		.evalua > a {
			color: #fff;
			text-decoration: none;
		}

		.fin {
			font-size: 1em;
			padding: 0 20px;
			text-align: justify !important;
			margin-top: 50px;
		}

		.inf-footer {
			background-image: url("https://parkingplus.es/aparcamiento/backend/web/images/footer.png");
			padding: 20px 20px;
		}

		.ul {
			padding: 0;
			font-weight:normal;
			font-size:14px;
			color:#4e5256;
			margin-bottom:25px;
			margin:0;
			line-height:1
		}

		.li {
			padding: 0;
			list-style-position:inside;
			color:#4e5256;
			display:inline-block;
			margin :0 0 0 35px;
		}

		.vinculo {
			padding: 0;
			color:#fff;
			display:inline-block;
			font-size: 13px;
			margin:0;
			width: 25px;
		}

		.icon-enlace {
			padding: 0;
			max-width: 120%;
			border: none;
		}		
	</style>
</head>
<body>
	<?php $this->beginBody() ?>
	<div class="evaluacion-email">
		<div class="notificacion">
			<table class="tablenot">
				<tr>
					<td class="celda"><img src="https://parkingplus.es/aparcamiento/backend/web/images/header.jpg"></td>
				</tr>
				<tr>
				    <td class="celda content">
				    	<div class="inf-reserva">
				 			N° de Reserva: <?= Html::encode($num_reserva) ?>
				 			<hr>
				    	</div>

				    	<div class="mensaje">
				    		Se ha realizado una solicitud de factura a traves del sitio web<br>
				    		https://parkingplus.es<br>
				    		<p>A continuación se indican los datos para la facturación :</p><br>
				    		<p><b>NIF: </b> <?= $nif ?></p>
				    		<p><b>Razón Social: </b> <?= $razon_social ?></p>
				    		<p><b>Dirección: </b> <?= $direccion ?></p>
				    		<p><b>Código Postal: </b> <?= $cod_postal ?></p>
				    		<p><b>Ciudad: </b> <?= $ciudad ?></p>
				    		<p><b>Provincia: </b> <?= $provincia ?></p>
				    		<p><b>País: </b> <?= $pais ?></p>
				    	</div>			    				    			    	
				    	<br><br>		    	
				    </td>
				</tr>
				<tr>
				    <td class="celda">
				    	<div class="inf-footer">
							<ul class="ul"> 
								<li class="li"> 
									<a href="mailto:contacto@parkingplus.es" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/correo.png" alt="Contacto Parking Plus" class="icon-enlace">
									</a> 
								</li> 
								<li class="li">
									<a href="https://www.facebook.com/parkingplus/" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/face.png" alt="Facebook Parking Plus" class="icon-enlace">
									</a>
								</li>
								<li class="li">
									<a href="https://www.instagram.com/parkingplus1/" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/insta.png" alt="Instagram Parking Plus" class="icon-enlace">
									</a>
								</li>
								<li class="li">
									<a href="https://twitter.com/plusparking" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/twitter.png" alt="Twitter Parking Plus" class="icon-enlace">
									</a>
								</li>
								<li class="li">
									<a href="tel:+34912147984" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/telefono.png" alt="Teléfono Parking Plus" class="icon-enlace">
									</a>
								</li>						
								<li class="li">
									<a href="https://api.whatsapp.com/send?phone=+34603284800&text=Estoy Interesado en los Servicios que Ofrecen !" class="vinculo" rel="noreferrer noreferrer" target="_blank">
										<img src="https://parkingplus.es/aparcamiento/backend/web/images/ws.png" alt="Whatsapp Parking Plus" class="icon-enlace">
									</a>
								</li>						
							</ul>
						</div>	
				    </td>
				</tr>
			</table>
		</div>

	</div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
