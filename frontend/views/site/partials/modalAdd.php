<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Notes;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
$model = new Notes;
?>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['site/add-notes'],
                'options' => ['data-pjax' => true, 'class' => 'needs-validation notesModalForm', 'novalidate'],
                'id' => 'notesFormAddNotes',
            ]);
            ?>
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Добавить</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <?= Html::label('Введите название', 'addTitle', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'title')->textInput([
                        'type' => "text",
                        'class' => 'form-control',
                        'required' => true,
                    ])->label(false); ?>
                </div>
                <div class="mb-3">
                    <?= Html::label('Введите описание', 'addDescription', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'description')->textArea([
                        'class' => 'form-control',
                        'required' => true,
                    ])->label(false); ?>
                </div>
                <div class="mb-3">
                    <?= Html::label('Введите дату окончания', 'addDatepicker', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'time')->textInput([
                        'class' => 'form-control',
                        'type' => 'datetime-local',
                        'required' => false,
                    ])->label(false); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#addModal']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>