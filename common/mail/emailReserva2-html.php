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
										¡Aquí tienes tu reserva!
									</div>
								</td>
							</tr>
							<tr>
								<td width="50%" colspan="3">
									<div
										style="font-style:normal;text-align:center;color:#000;height:30px;font-weight:300;font-size:18px;line-height:22px;letter-spacing:-.03em;padding-top:10px;padding-bottom:15px">
										N° de Reserva:
										<div style="color:#961007;padding-left:1px;display:inline;font-weight:700">
											<?= Html::encode($nro_reserva) ?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td width="50%" colspan="3">
									<div
										style="font-style:normal;text-align:center;color:#000;height:30px;font-weight:300;font-size:18px;line-height:22px;letter-spacing:-.03em;padding-top:5px;padding-bottom:15px">
										Matricula:
										<div style="color:#961007;padding-left:1px;display:inline;font-weight:700">
											<?= Html::encode($coche_matricula) ?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" height="5px"></td>
							</tr>
							<tr>
								<td width="300px">
									<div
										style="font-style:normal;font-size:18px;line-height:22px;letter-spacing:-.03em!important;color:#000;font-weight:300;text-align:right;padding-right:25px">
										Llegada al Parking
									</div>
								</td>
								<td rowspan="2" style="padding:10px">
									<img width="30px"
										src="https://ci3.googleusercontent.com/proxy/VF9sZwwCAUexY-kjvN6_3NnLmsnrbbuw4nkJ1YD4W_5Lx55E5nWyBf_hoLr1UVeKQ6IXNrRUha2ACNqC9UowbszrgQWySqYaT1uN_bcYF6HUmxiPnjib8KE=s0-d-e1-ft#https://static.parclick.com/assets/img/booking/parclick/icon_arrow.png"
										class="CToWUd">
								</td>
								<td width="288px">
									<div
										style="font-style:normal;font-size:18px;line-height:22px;letter-spacing:-.03em!important;color:#000;font-weight:300;text-align:left;padding-left:25px">
										Salida del Parking
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div
										style="font-weight:700!important;font-style:normal;font-size:18px;line-height:22px;letter-spacing:-.03em!important;color:#000;text-align:right;padding-right:25px">
										<?= $fecha_entrada . ' ' . $hora_entrada ?>
									</div>
								</td>
								<td>
									<div
										style="font-weight:700!important;font-style:normal;font-size:18px;line-height:22px;letter-spacing:-.03em!important;color:#000;text-align:left;padding-left:25px">
										<?= $fecha_salida . ' ' . $hora_salida ?>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" height="20px"></td>
							</tr>
							<tr>
								<td colspan="3"
									style="width:99%;height:60px;margin:0 auto;font-style:normal;font-weight:600;font-size:20px;line-height:20px;text-align:center;letter-spacing:-.03em!important;color:#fff;padding:0 20px;background:#961007">
									<div style="padding-top:5px; padding-bottom: 5px">
										Parking Plus - Valet - Aeropuerto de Madrid Barajas
									</div>
									<div style="font-size:15px;font-weight:lighter;padding-bottom:5px;max-width:580px">
										Aeropuerto, Madrid, España 28042, Madrid
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" height="10px"></td>
							</tr>
							<tr>
								<td colspan="3">
									<table width="70%" align="center" border="0">
										<tbody>
											<tr>
												<td valign="middle" width="35%" align="center">
													<img style="max-width:87px;max-height:52px;vertical-align:middle"
														src="https://static.parclick.com/assets/img/booking/parking_access/icon_pickup.png">
												</td>
												<td valign="middle" width="65%" align="left">
													<span
														style="font-size:15px;color:#484848;display:block;line-height:20px;text-align:justify">Servicio
														de recogida del vehículo en el aeropuerto</span>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="3" height="10px" style="border-bottom:1px solid #961007"></td>
							</tr>
							<tr>
								<td colspan="3"
									style="vertical-align:bottom!important;height:200px!important;border-bottom:1px solid #961007;background-image:url('https://ci4.googleusercontent.com/proxy/0hRLZaFdjmPxUf0ScSWpKpDzk8BjU9NTSAR5PSoeWKCLuRkhL-oORxnjpjjbHXY-E9h6krWZE3ediMNWlbnmUw2MWp6PlrnioBy4tUthpXP1F3eMlhLSKdJzIp9e4S_edCWOjjn61ZoALofk7coF_oyFfj2PkuUjwyTcc_Fj4nI2XHkhEP_c2Pb8HJT2Bom93BZIIapdy0TSv2y1-sDzjoWiyAKux-H9tH1IbRS0jMymejU8F2aBGLY9OvYKPcT20K2y5Iwu9mjg3-2_ZUPw2gREbT7U5TZd2__p2sceV7wC7yYhcSpPmNlr3utGKqtenRiDLQ74WbiCEJJYT4rrfKNjqkkPPesS1OV9xJkay-T2wuuvGgko8s9Rlw=s0-d-e1-ft#https://maps.googleapis.com/maps/api/staticmap?zoom=18\000026scale=false\000026size=588x200\000026maptype=roadmap\000026sensor=false\000026format=png\000026visual_refresh=true-Ws\000026key=AIzaSyDVp3PJw4jyDYFPEcjfU16JN5XmZB95-Ws\000026center=40.49322933989,-3.593633415943\000026markers=40.49322933989,-3.593633415943')">
								</td>
							</tr>
							<tr>
								<td colspan="3" style="border-bottom:1px solid #961007;padding-top:10px!important">
									<table width="97%" border="0" align="center">
										<tbody>
											<tr>
												<td colspan="2">
													<table width="100%" border="0" align="center">
														<tbody>
															<tr>
																<td width="10px" valign="top">
																	<img src="https://parkingplus.es/aparcamiento/backend/web/images/ir.png"
																		align="middle" width="50px">
																</td>
																<th width="99%" valign="middle" align="left">
																	<span
																		style="display:block;font-weight:bold;font-size:16px;line-height:155.14%;letter-spacing:0.01em;color:#171835">Al
																		comienzo de tu viaje:</span>
																</th>
															</tr>
															<tr>
																<td colspan="2" width="100%" valign="middle">
																	<div
																		style="text-align:justify!important;padding:0!important">
																		Llama al parking aproximadamente 20 minutos
																		antes de llegar al aeropuerto. El teléfono al
																		que debes llamar es el +34 603284800. Durante la
																		llamada, una persona te confirmará el punto de
																		encuentro. Al llegar, se realizará una
																		inspección de tu vehículo.
																	</div>
																</td>
															</tr>
															<tr>
																<td colspan="2" height="10px"></td>
															</tr>
															<tr>
																<td width="10px" valign="top">
																	<img src="https://parkingplus.es/aparcamiento/backend/web/images/venir.png"
																		align="middle" width="50px">
																</td>
																<th width="99%" valign="middle" align="left">
																	<span
																		style="display:block;font-weight:bold;font-size:16px;line-height:155.14%;letter-spacing:0.01em;color:#171835">Al
																		regreso de tu viaje</span>
																</th>
															</tr>
															<tr>
																<td colspan="2" width="100%" valign="middle">
																	<div
																		style="text-align:justify!important;padding:0!important">
																		Llama al parking para solicitar la entrega del
																		vehículo. El teléfono al que debes llamar es el
																		+34 603284800. Durante la llamada, una persona
																		te confirmará el punto de encuentro.
																	</div>
																</td>
															</tr>
															<tr>
																<td colspan="2" height="10px"></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="border-top:1px solid #961007;padding:20px 0px">
													<div>Te enviamos adjunto un PDF con el comprobante de tu
														reserva.&nbsp;</div>
													<div><br>
														<div style="font-weight:700!important">Para Modificación /
															Cancelación haga click en el siguiente enlace: <a
																href="https://parkingplus.es/aparcamiento/site/update?codId=<?= Html::encode($nro_reserva) ?>&codValid=<?= $token ?>">Modificar
																/ Cancelar reserva</a> </div>
														<div style="font-weight:700!important">Si se le olvido hacer la solicitud de factura al momento de realizar la reserva haga click en el siguiente enlace: <a
																href="https://parkingplus.es/aparcamiento/site/update?invoice=1&codId=<?= Html::encode($nro_reserva) ?>&codValid=<?= $token ?>">Solicitar factura</a> </div>
														Se puede <b> modificar/cancelar</b> hasta 24 horas antes de la
														hora de llegada.<br>
													</div>
												</td>
											</tr>

										</tbody>
									</table>
								</td>
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

	<div align="justify"><br><br>
		Por favor, no responda a este mensaje, ha sido enviado de forma automática. Si desea ponerse en contacto con
		nosotros para comentarnos alguna incidencia o mejora de este servicio, por favor, escríbanos a:
		contacto@gmail.com.
	</div>
	<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>