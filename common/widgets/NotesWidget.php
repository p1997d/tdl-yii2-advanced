<?php

namespace common\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use yii\widgets\ActiveForm;
use yii\base\InvalidConfigException;

class NotesWidget extends Widget
{
    public $note;
    public $id;
    public $title;
    public $description;
    public $status;
    public $date_created;
    public $date_updated;
    public $date_execute;
    public $date_started;
    public $notesView;

    public function init()
    {
        parent::init();
        if ($this->note === null) {
            throw new InvalidConfigException('The "note" property must be set.');
        }
        if ($this->id === null) {
            throw new InvalidConfigException('The "id" property must be set.');
        }
        if ($this->title === null) {
            $this->title = '(not set)';
        }
        if ($this->description === null) {
            $this->description = '(not set)';
        }
        if ($this->status === null) {
            $this->status = 0;
        }
        if ($this->date_created === null) {
            $this->date_created = 0;
        }
        if ($this->date_updated === null) {
            $this->date_updated = 0;
        }
        if ($this->date_execute === null) {
            $this->date_execute = 0;
        }
        $this->on(Widget::EVENT_AFTER_RUN, [$this, 'runJsAfterRun']);
    }

    public function run()
    {
        if ($this->notesView === 'grid') {
            return $this->gridView();
        } elseif ($this->notesView === 'list') {
            return $this->listView();
        }
        return $this->gridView();
    }
    public function gridView()
    {
        $output = '';
        $output .= Html::beginTag('div', [
            'class' => 'card my-1 mx-1 ' . (($this->status == 1) ? 'end' : 'active'),
            'style' => 'width: 18rem;',
            'data-id' => $this->id
        ]);

        $output .= Html::beginTag('div', ['class' => 'card-header' . (($this->status == 1) ? ' text-secondary' : '')]);
        $output .= Html::tag('span', 'Завершен', ['class' => 'badge text-bg-danger me-1', 'style' => (($this->status == 0) ? 'display: none' : '')]);
        $output .= Html::tag('span', $this->title . '<br>', ['class' => 'title', 'id' => 'title']);
        $output .= Html::beginTag('small');
        $output .= Html::tag(
            'span',
            ($this->status == 0) ?
            ((isset($this->date_started)) ? date('d.m.Y G:i', $this->date_started) : 'Не задано') :
            'завершено: ' . date('d.m.Y G:i', $this->date_execute),
            ['class' => 'fw-light', 'id' => 'dateCreated']
        );
        $output .= Html::endTag('small');
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div', ['class' => 'card-body d-flex flex-column justify-content-between']);
        $output .= Html::tag('p', $this->description, ['class' => 'description card-text' . (($this->status == 1) ? ' text-secondary' : ''), 'id' => 'description']);
        $output .= Html::beginTag('div', ['class' => 'align-self-center d-flex']);
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-arrows-move"></i>', [
            'class' => 'btn btn-outline-secondary ms-2 handle',
            'style' => 'cursor: move'
        ]);
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-pencil"></i>', [
            'class' => 'btn btn-outline-secondary ms-2 editButton',
            'name' => 'edit',
            'onclick' => 'openEditModal(this)',
            'value' => $this->id,
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#editModal'
        ]);
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        ob_start();
        $form = ActiveForm::begin([
            'action' => ['site/set-status'],
            'options' => ['data-pjax' => true, 'class' => 'notesForm'],
            'id' => 'notesFormSetStatus',
        ]);
        echo $form->field($this->note, 'id', ['options' => ['style' => 'display: none']])->hiddenInput(['value' => $this->id])->label(false);
        echo Html::submitButton('<i class="bi bi-check"></i>', ['class' => 'btn ' . (($this->status == 1) ? ' btn-success' : ' btn-outline-success') . ' ms-2']);
        ActiveForm::end();
        $formContent = ob_get_clean();
        $output .= $formContent;
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-trash"></i>', ['class' => 'btn btn-outline-danger ms-2', 'onclick' => 'removeNote(' . $this->id . ')']);
        $output .= Html::endTag('div');
        $output .= Html::endTag('div');
        $output .= Html::endTag('div');
        $output .= Html::tag(
            'div',
            date('d.m.Y G:i', $this->date_created) .
            '<br>' .
            (($this->date_updated > $this->date_created) ? '(изменено) ' . date('d.m.Y G:i', $this->date_updated) : '​'),
            ['class' => 'card-footer' . (($this->status == 1) ? ' text-secondary' : ''), 'style' => 'font-size:0.7rem']
        );
        $output .= Html::endTag('div');

        return $output;
    }

    public function listView()
    {
        $output = '';
        $output .= Html::beginTag('div', [
            'class' => 'card my-1 mx-1 ' . (($this->status == 1) ? 'end' : 'active'),
            'style' => 'width: 100%;',
            'data-id' => $this->id
        ]);
        $output .= Html::beginTag('div', ['class' => 'card-body d-flex flex-row justify-content-between align-self-stretch']);

        $output .= Html::beginTag('div', ['style' => 'width: 25%']);
        $output .= Html::tag('span', 'Завершен', ['class' => 'badge text-bg-danger me-1', 'style' => (($this->status == 0) ? 'display: none' : '')]);
        $output .= Html::tag('span', $this->title . '<br>', ['class' => 'title' . (($this->status == 1) ? ' text-secondary' : ''), 'id' => 'title']);
        $output .= Html::beginTag('small');
        $output .= Html::tag(
            'span',
            ($this->status == 0) ?            
            (isset($this->date_started)) ? date('d.m.Y G:i', $this->date_started) : 'Не задано' :
            'завершено: ' . date('d.m.Y G:i', $this->date_execute),
            ['class' => 'fw-light' . (($this->status == 1) ? ' text-secondary' : ''), 'id' => 'dateCreated']
        );
        $output .= Html::endTag('small');
        $output .= Html::endTag('div');

        $output .= Html::beginTag('div', ['style' => 'width: 35%']);
        $output .= Html::tag('p', $this->description, ['class' => 'description card-text' . (($this->status == 1) ? ' text-secondary' : ''), 'id' => 'description']);
        $output .= Html::endTag('div');

        $output .= Html::beginTag('div', ['style' => 'width: 25%']);
        $output .= Html::tag(
            'div',
            date('d.m.Y G:i', $this->date_created) .
            '<br>' .
            (($this->date_updated > $this->date_created) ? '(изменено) ' . date('d.m.Y G:i', $this->date_updated) : '​'),
            ['style' => 'font-size:0.7rem', 'class' =>  (($this->status == 1) ? ' text-secondary' : '')]
        );
        $output .= Html::endTag('div');

        $output .= Html::beginTag('div', ['class' => 'align-self-center d-flex']);
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-arrows-move"></i>', [
            'class' => 'btn btn-outline-secondary ms-2 handle',
            'style' => 'cursor: move'
        ]);
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-pencil"></i>', [
            'class' => 'btn btn-outline-secondary ms-2 editButton',
            'name' => 'edit',
            'onclick' => 'openEditModal(this)',
            'value' => $this->id,
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#editModal',
            'disabled' => $this->status == 1
        ]);
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        ob_start();
        $form = ActiveForm::begin([
            'action' => ['site/set-status'],
            'options' => ['data-pjax' => true, 'class' => 'notesForm'],
            'id' => 'notesFormSetStatus',
        ]);
        echo $form->field($this->note, 'id', ['options' => ['style' => 'display: none']])->hiddenInput(['value' => $this->id])->label(false);
        echo Html::submitButton('<i class="bi bi-check"></i>', ['class' => 'btn ' . (($this->status == 1) ? ' btn-success' : ' btn-outline-success') . ' ms-2']);
        ActiveForm::end();
        $formContent = ob_get_clean();
        $output .= $formContent;
        $output .= Html::endTag('div');
        $output .= Html::beginTag('div');
        $output .= Html::button('<i class="bi bi-trash"></i>', ['class' => 'btn btn-outline-danger ms-2', 'onclick' => 'removeNote(' . $this->id . ')']);
        $output .= Html::endTag('div');
        $output .= Html::endTag('div');
        $output .= Html::endTag('div');
        $output .= Html::endTag('div');

        return $output;
    }
    public function runJsAfterRun()
    {
        $js = <<<JS
            $('#notesSort').sortable({                
                handle: '.handle',
                animation: 150,
                onSort: () => {
                    var order = $('#notesSort').sortable('toArray');
                    $.post("/site/sort-notes", { 'Notes[order]': order });
                },
            })
        JS;

        $this->getView()->registerJs($js);
    }
}