<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\widgets\NotesWidget;
use common\widgets\ButtonsWidget;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\widgets\ActiveForm $form */
$this->title = 'To-Do-List';
?>
<div class="notes-index">
    <?= Html::button('<i class="bi bi-plus-lg"></i>', [
        'class' => 'position-fixed bottom-0 end-0 me-4 btn btn-danger rounded-circle p-3 lh-1 z-3',
        'style' => 'margin-bottom: 4rem !important;',
        'type' => 'button',
        'data-bs-toggle' => 'modal',
        'data-bs-target' => '#addModal'
    ]) ?>

    <?php
    Pjax::begin([
        'id' => 'notesButtons',
        'enablePushState' => false,
        'options' => ['class' => 'd-flex flex-wrap my-3 justify-content-between'],
    ]);
    ?>    
    <?php $form = ActiveForm::begin([
        'action' => ['site/set-filter'],
        'options' => ['data-pjax' => true, 'class' => 'notesForm'],
        'id' => 'formFilter',
    ]); ?>
    <?= ButtonsWidget::widget([
        'id' => 'buttonsFilter',
        'name' => 'Notes[notesFilter]',
        'activeButton' => $notesFilter,
        'defaultActive' => 'active',
        'buttons' => [
            (object) ['name' => 'Все', 'id' => 'allButton', 'value' => ''],
            (object) ['name' => 'Активные', 'id' => 'activeButton', 'value' => 'active'],
            (object) ['name' => 'Завершенные', 'id' => 'endButton', 'value' => 'end'],
        ]
    ]) ?>    
    <?php ActiveForm::end(); ?>
    <?php $form = ActiveForm::begin([
        'action' => ['site/set-view'],
        'options' => ['data-pjax' => true, 'class' => 'notesForm'],
        'id' => 'formView',
    ]); ?>
    <?= ButtonsWidget::widget([
        'id' => 'buttonsView',
        'name' => 'Notes[notesView]',
        'activeButton' => $notesView,
        'defaultActive' => 'grid',
        'buttons' => [
            (object) ['name' => '<i class="bi bi-grid"></i>', 'id' => 'gridButton', 'value' => 'grid'],
            (object) ['name' => '<i class="bi bi-view-stacked"></i>', 'id' => 'listButton', 'value' => 'list'],
        ]
    ]) ?>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

    <?php
    Pjax::begin([
        'id' => 'notesList',
        'enablePushState' => false,
    ]);
    ?>
    <?php 
    if(isset($message)) {
        echo \yii\bootstrap5\Alert::widget([
            'options' => [
                'class' => 'alert-' . $message['type'],
                'id' => 'alert',
            ],
            'body' => '<i class="bi bi-' . $message['icon'] . '"></i> ' . $message['text'],
        ]);
    }
    ?>
    <?= Yii::$app->controller->renderPartial('partials/modalAdd') ?>
    <?= Yii::$app->controller->renderPartial('partials/modalEdit') ?>

    <?= Html::beginTag('div', ['class' => 'd-flex flex-wrap mb-3', 'id' => 'notesSort']); ?>
    <?php foreach ($dataProvider->getModels() as $note): ?>
        <?= NotesWidget::widget([
            'note' => $note,
            'id' => $note->id,
            'title' => $note->title,
            'description' => $note->description,
            'status' => $note->status,
            'date_created' => $note->date_created,
            'date_updated' => $note->date_updated,
            'date_execute' => $note->date_execute,
            'date_started' => $note->date_started,
            'notesView' => $notesView,
        ]) ?>
    <?php endforeach; ?>
    <?= Html::endTag('div'); ?>
    <?php Pjax::end(); ?>
</div>