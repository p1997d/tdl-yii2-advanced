<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Notes $model */

$this->title = 'Create Notes';
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
