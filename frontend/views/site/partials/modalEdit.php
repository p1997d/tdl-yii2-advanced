<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Notes;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
$model = new Notes;
?>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['site/edit-notes'],
                'options' => ['data-pjax' => true, 'class' => 'needs-validation notesModalForm', 'novalidate'],
                'id' => 'notesFormEditNotes',
            ]);
            ?>
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Редактировать</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <?= Html::label('Введите название', 'editTitle', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'title')->textInput([
                        'type' => "text",
                        'id' => 'editTitle',
                        'class' => 'form-control',
                        'required' => true,
                    ])->label(false); ?>
                </div>
                <div class="mb-3">
                    <?= Html::label('Введите описание', 'editDescription', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'description')->textarea([
                        'id' => 'editDescription',
                        'class' => 'form-control',
                        'required' => true,
                    ])->label(false); ?>
                </div>
                <div class="mb-3">
                    <?= Html::label('Введите дату окончания', 'editDatepicker', ['class' => 'col-form-label']) ?>
                    <?= $form->field($model, 'time')->textInput([
                        'type' => "datetime-local",
                        'id' => 'editDatepicker',
                        'class' => 'form-control',
                        'required' => false,
                    ])->label(false); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <?= $form->field($model, 'id', ['options' => ['style' => 'display: none']])->hiddenInput([
                    'value' => '',
                    'id' => 'editID'
                ])->label(false); ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#editModal']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>