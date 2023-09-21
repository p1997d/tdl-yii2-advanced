<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Вход';
?>

<div class="d-flex align-items-center py-4 mt-5">
    <div class="site-login w-50 m-auto card p-4">        
        <div class="d-flex align-items-center justify-content-between w-100">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row justify-content-center">
            <div class=".col-xxl-5 m-1">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя') ?>

                    <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                    <?= $form->field($model, 'rememberMe')->checkbox()->label('Сохранить вход') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-light w-100 mt-3', 'name' => 'login-button']) ?>
                        <?= Html::a('Зарегистрироваться', ['/site/signup'], ['class' => 'btn btn-success w-100 mt-3']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>