
<table style="margin-top: 30px; margin-left: -3px;">
	<tr>
		<td align="center" style="width: 7cm; text-transform: uppercase;">
			Matrícula
		</td>
	</tr>
	<tr>
		<td align="center" style="padding-top: 15px;">
			<div style="width: 7cm; font-size: 28px;"><?=empty($model->coche->matricula) ? 'N/D' : $model->coche->matricula ?></div>
		</td>
	</tr>

	<tr>
		<td align="center" style="width: 7cm; text-transform: uppercase; padding-top: 25px;">
			Fecha de Salida
		</td>
	</tr>

	<tr>
		<td align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px;">
			<span style="font-size: 30px"><?= date('d/m/Y', strtotime($model->fecha_salida)) ?></span>
		</td>		
	</tr>

	<tr>
		<td align="center" style="width: 7cm; text-transform: uppercase;">
			<span style="font-size: 30px"><?= $model->hora_salida ?></span>
		</td>
	</tr>				
</table>


