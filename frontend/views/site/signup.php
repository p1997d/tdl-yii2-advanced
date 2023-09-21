<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Регистрация';
?>
<div class="d-flex align-items-center py-4 mt-5">
    <div class="site-signup w-50 m-auto card p-4">
        <div class="d-flex align-items-center justify-content-between w-100">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <?= Html::a('', ['/'], ['class' => 'btn-close ms-auto', 'aria-label' => 'Close']) ?>
        </div>
        <div class="row justify-content-center">
            <div class=".col-xxl-5 m-1">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя') ?>

                    <?= $form->field($model, 'email') ->label('Email')?>

                    <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-light w-100 mt-3', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
