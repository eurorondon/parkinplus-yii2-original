<?php
use yii\helpers\Html;

function getTerminalAbreviada($terminal) {
    $terminal = strtolower(trim($terminal));
    if (strpos($terminal, 'terminal 1') !== false) return 'T1';
    if (strpos($terminal, 'terminal 2') !== false) return 'T2';
    if (strpos($terminal, 'terminal 3') !== false) return 'T3';
    if (strpos($terminal, 'terminal 4') !== false) return 'T4';
    return '';
}

$fecha = date('d-m-Y', strtotime($fecha));
$maxFilasPorColumna = 15;
?>

<!-- Encabezado -->
<!--<div style="position: absolute; top: 0.8cm; left: 15.2cm; font-size: 12px;">-->
<!--    Parkingplus.es<br>Marichal 4 Parking S.L<br>C/Pañeria 38 2do IZQ. CP 28037.<br>Madrid (Madrid).-->
<!--</div>-->

<br><br>
<div style="margin-top: -10px; font-size: 14px; text-align: center; font-weight: bold; text-transform: uppercase;">
    Reporte 2 (<?= $fecha ?>)
</div>

<!-- ENTRADAS -->
<hr style="margin-bottom: 3px; margin-top: 1cm">
<div style="text-transform: uppercase; font-size: 12px; font-weight: bold;">
    Tabla Simplificada de Entrada (<?= $dataProvider->getTotalCount() ?>)
</div>
<hr style="margin-top: 3px;">

<?php
$models = $dataProvider->getModels();
$total = count($models);
$porBloque = $maxFilasPorColumna * 2;
$totalPaginas = ceil($total / $porBloque);
$pagina = 1;

for ($i = 0; $i < $total; $i += $porBloque):
    $bloque = array_slice($models, $i, $porBloque);
    $columnaIzq = array_slice($bloque, 0, $maxFilasPorColumna);
    $columnaDer = array_slice($bloque, $maxFilasPorColumna, $maxFilasPorColumna);
?>

<table style="width: 100%;">
    <tr>
        <!-- Columna Izquierda -->
        <td style="width: 50%; vertical-align: top;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5"
                style="font-size: 14px; text-transform: uppercase; border-collapse: collapse; font-weight: bold;">
                <thead>
                    <tr style="background-color: #ede9e9;">
                        <th style="text-align:center; padding: 10px;">Nº</th>
                        <th style="text-align:center; padding: 10px;">Hora</th>
                        <th style="text-align:center; padding: 10px;">Matrícula</th>
                        <th style="text-align:center; padding: 10px;">Teléfono</th>
                        <th style="text-align:left; padding: 10px;">Terminal</th>
                    </tr>
                </thead>
                <tbody>
                <?php $j = $i + 1; foreach ($columnaIzq as $model):
                    $color = ($j % 2 == 0) ? '#f3f3f3' : '#ffffff'; ?>
                    <tr>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->hora_entrada ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->coche->matricula ?? '' ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->cliente->movil ?? '' ?></td>
                        <td style="text-align:left; background-color: <?= $color ?>; padding: 10px;"><?= getTerminalAbreviada($model->terminal_entrada) ?></td>
                    </tr>
                <?php $j++; endforeach; ?>
                </tbody>
            </table>
        </td>

        <!-- Columna Derecha -->
        <td style="width: 50%; vertical-align: top;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5"
                style="font-size: 14px; text-transform: uppercase; border-collapse: collapse; font-weight: bold;">
                <thead>
                    <tr style="background-color: #ede9e9;">
                        <th style="text-align:center; padding: 10px;">Nº</th>
                        <th style="text-align:center; padding: 10px;">Hora</th>
                        <th style="text-align:center; padding: 10px;">Matrícula</th>
                        <th style="text-align:center; padding: 10px;">Teléfono</th>
                        <th style="text-align:left; padding: 10px;">Terminal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($columnaDer as $model):
                    $color = ($j % 2 == 0) ? '#f3f3f3' : '#ffffff'; ?>
                    <tr>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->hora_entrada ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->coche->matricula ?? '' ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->cliente->movil ?? '' ?></td>
                        <td style="text-align:left; background-color: <?= $color ?>; padding: 10px;"><?= getTerminalAbreviada($model->terminal_entrada) ?></td>
                    </tr>
                <?php $j++; endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<div style="text-align: right; font-size: 12px; font-weight: bold; margin-top: 5px;">
    Página <?= $pagina ?>/<?= $totalPaginas ?>
</div>

<?php if ($i + $porBloque < $total): ?>
<pagebreak />
<?php endif; $pagina++; endfor; ?>


<!-- SALIDAS -->
<pagebreak />
<hr style="margin-bottom: 3px;">
<div style="text-transform: uppercase; font-size: 12px; font-weight: bold;">
    Tabla Simplificada de Salida (<?= $dataProvider1->getTotalCount() ?>)
</div>
<hr style="margin-top: 3px;">

<?php
$models = $dataProvider1->getModels();
$total = count($models);
$porBloque = $maxFilasPorColumna * 2;
$totalPaginas = ceil($total / $porBloque);
$pagina = 1;

for ($i = 0; $i < $total; $i += $porBloque):
    $bloque = array_slice($models, $i, $porBloque);
    $columnaIzq = array_slice($bloque, 0, $maxFilasPorColumna);
    $columnaDer = array_slice($bloque, $maxFilasPorColumna, $maxFilasPorColumna);
?>

<table style="width: 100%;">
    <tr>
        <!-- Repite misma estructura que la sección de entradas -->
        <td style="width: 50%; vertical-align: top;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5"
                style="font-size: 14px; text-transform: uppercase; border-collapse: collapse; font-weight: bold;">
                <thead>
                    <tr style="background-color: #ede9e9;">
                        <th style="text-align:center; padding: 10px;">Nº</th>
                        <th style="text-align:center; padding: 10px;">Hora</th>
                        <th style="text-align:center; padding: 10px;">Matrícula</th>
                        <th style="text-align:center; padding: 10px;">Teléfono</th>
                        <th style="text-align:left; padding: 10px;">Terminal</th>
                    </tr>
                </thead>
                <tbody>
                <?php $j = $i + 1; foreach ($columnaIzq as $model):
                    $color = ($j % 2 == 0) ? '#f3f3f3' : '#ffffff'; ?>
                    <tr>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->hora_salida ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->coche->matricula ?? '' ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->cliente->movil ?? '' ?></td>
                        <td style="text-align:left; background-color: <?= $color ?>; padding: 10px;"><?= getTerminalAbreviada($model->terminal_salida) ?></td>
                    </tr>
                <?php $j++; endforeach; ?>
                </tbody>
            </table>
        </td>

        <!-- Columna derecha salidas -->
        <td style="width: 50%; vertical-align: top;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5"
                style="font-size: 14px; text-transform: uppercase; border-collapse: collapse; font-weight: bold;">
                <thead>
                    <tr style="background-color: #ede9e9;">
                        <th style="text-align:center; padding: 10px;">Nº</th>
                        <th style="text-align:center; padding: 10px;">Hora</th>
                        <th style="text-align:center; padding: 10px;">Matrícula</th>
                        <th style="text-align:center; padding: 10px;">Teléfono</th>
                        <th style="text-align:left; padding: 10px;">Terminal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($columnaDer as $model):
                    $color = ($j % 2 == 0) ? '#f3f3f3' : '#ffffff'; ?>
                    <tr>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->hora_salida ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->coche->matricula ?? '' ?></td>
                        <td style="text-align:center; background-color: <?= $color ?>; padding: 10px;"><?= $model->cliente->movil ?? '' ?></td>
                        <td style="text-align:left; background-color: <?= $color ?>; padding: 10px;"><?= getTerminalAbreviada($model->terminal_salida) ?></td>
                    </tr>
                <?php $j++; endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<div style="text-align: right; font-size: 12px; font-weight: bold; margin-top: 5px;">
    Página <?= $pagina ?>/<?= $totalPaginas ?>
</div>

<?php if ($i + $porBloque < $total): ?>
<pagebreak />
<?php endif; $pagina++; endfor; ?>
