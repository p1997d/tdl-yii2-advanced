<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar bg-body-tertiary navbar-expand-md fixed-top',
            ],
        ]);

        $menuItems = [];

        if (!Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '<input class="form-control me-2" type="search" placeholder="Поиск" aria-label="Search" id="searchInput">', 'encode' => false, 'url' => null];
        }
        $menuItems[] = ['label' => '<button class="btn" id="btnSwitch"><i class="bi bi-sun-fill"></i></button>', 'encode' => false, 'url' => null];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right ms-auto'],
            'items' => $menuItems,
        ]);
        if (!Yii::$app->user->isGuest) {
                echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex']) .
                Html::submitButton(
                    '<i class="bi bi-box-arrow-right"></i>',
                    ['class' => 'btn', 'id' => 'logout']
                ) .
                Html::endForm();
            }
        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="mt-auto py-3 text-muted bg-body-tertiary">
        <div class="container">
            <p class="float-start">&copy;
                <?= Html::encode(Yii::$app->name) ?>
                <?= date('Y') ?>
            </p>
            <p class="float-end">
                <?= Yii::powered() ?>
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();