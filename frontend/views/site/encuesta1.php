<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfaccion';
$opciones = [
    1 => 'excelente',
    2 => 'buena',
    3 => 'normal',
    4 => 'mala',
    5 => 'muy mala',
];
?>
<div class="container mt-5 mb-5 pt-3">
    <div class="card shadow rounded overflow-hidden">
        <div class="card-header bg-success text-white py-3 px-4">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body p-4">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'reserva_id')->hiddenInput()->label(false) ?>

            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="mb-4">
                    <label class="form-label fw-bold">Pregunta <?= $i ?>:</label>
                    <?= $form->field($model, 'pregunta' . $i, ['template' => '{input}{error}'])->radioList($opciones) ?>
                </div>
            <?php endfor; ?>

            <div id="div-sugerencias" class="mb-4" style="display:none;">
                <?= $form->field($model, 'sugerencias')->textarea(['rows' => 4])->label('Tus sugerencias') ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<'JS'
function checkSugerencias(){
    var mostrar = false;
    for(var i=1;i<=5;i++){
        var val = $('input[name="EncuestaInicial[pregunta'+i+']"]:checked').val();
        if(val >= 4){
            mostrar = true;
        }
    }
    if(mostrar){
        $('#div-sugerencias').slideDown();
    } else {
        $('#div-sugerencias').slideUp();
    }
}
$('input[type=radio]').on('change', checkSugerencias);
checkSugerencias();
JS
);
?>

