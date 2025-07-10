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
		body,
		html {
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

		.evalua>a {
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
			padding: 25px 0 0 0;
			font-weight: normal;
			font-size: 14px;
			color: #4e5256;
			margin-bottom: 25px;
			margin: 0;
			line-height: 1
		}

		.li {
			padding: 0;
			list-style-position: inside;
			color: #4e5256;
			display: inline-block;
			margin: 0 0 0 35px;
		}

		.vinculo {
			padding: 0;
			color: #fff;
			display: inline-block;
			font-size: 13px;
			margin: 0;
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

	<table style="width:570px;height:auto;background:#fff;margin:0 auto">
		<tbody>
			<tr>
				<td>
					<table style="border:1px solid #961007;box-sizing:border-box;margin:0 auto;width:100%" border="0"
						cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td colspan="3" align="center">
									<div
										style="width:100%;height:100px;background:url(https://parkingplus.es/aparcamiento/backend/web/images/headerpplus.jpg) no-repeat 40%">
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<div
										style="padding-top:18px;height:auto;font-weight:600;font-size:33px;line-height:40px;letter-spacing:.01em;font-style:normal;text-align:center;color:#000">
										¡La siguiente reserva requiere su Factura!
									</div>
								</td>
							</tr>
							<tr>
								<td width="50%" colspan="3">
									<div
										style="font-style:normal;text-align:center;color:#000;height:30px;font-weight:300;font-size:18px;line-height:22px;letter-spacing:-.03em;padding-top:10px;padding-bottom:15px">
										N° de Reserva:
										<div style="color:#961007;padding-left:1px;display:inline;font-weight:700">
											<?= Html::encode($nro_reserva) ?></div>
									</div>
								</td>
							</tr>

							<tr>
								<td colspan="3" height="10px"></td>
							</tr>
							<tr>
								<td align="center" colspan="3">
									<div
										style="padding: 0px;width:100%;height:80px;background:url(https://parkingplus.es/aparcamiento/backend/web/images/footer.png) no-repeat 100%">
										<ul class="ul">
											<a href="mailto:contacto@parkingplus.es" class="vinculo"
												rel="noreferrer noreferrer" target="_blank">
												<img src="https://parkingplus.es/aparcamiento/backend/web/images/correo.png"
													alt="Contacto Parking Plus"
													style="padding: 0;	max-width: 120%;border: none;">
											</a>
											</li>
											<li class="li">
												<a href="https://www.facebook.com/parkingplus/" class="vinculo"
													rel="noreferrer noreferrer" target="_blank">
													<img src="https://parkingplus.es/aparcamiento/backend/web/images/face.png"
														alt="Facebook Parking Plus" class="icon-enlace">
												</a>
											</li>
											<li class="li">
												<a href="https://www.instagram.com/parkingplus1/" class="vinculo"
													rel="noreferrer noreferrer" target="_blank">
													<img src="https://parkingplus.es/aparcamiento/backend/web/images/insta.png"
														alt="Instagram Parking Plus"
														style="padding: 0;	max-width: 120%;border: none;">
												</a>
											</li>
											<li class="li">
												<a href="https://twitter.com/plusparking" class="vinculo"
													rel="noreferrer noreferrer" target="_blank">
													<img src="https://parkingplus.es/aparcamiento/backend/web/images/twitter.png"
														alt="Twitter Parking Plus"
														style="padding: 0;	max-width: 120%;border: none;">
												</a>
											</li>
											<li class="li">
												<a href="tel:+34912147984" class="vinculo" rel="noreferrer noreferrer"
													target="_blank">
													<img src="https://parkingplus.es/aparcamiento/backend/web/images/telefono.png"
														alt="Teléfono Parking Plus"
														style="padding: 0; max-width: 120%;border: none;">
												</a>
											</li>
											<li class="li">
												<a href="https://api.whatsapp.com/send?phone=+34603284800&text=Estoy Interesado en los Servicios que Ofrecen !"
													class="vinculo" rel="noreferrer noreferrer" target="_blank">
													<img src="https://parkingplus.es/aparcamiento/backend/web/images/ws.png"
														alt="Whatsapp Parking Plus"
														style="padding: 0;	max-width: 120%;border: none;">
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>