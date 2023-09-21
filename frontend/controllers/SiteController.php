<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Notes;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);
        if (!Yii::$app->user->isGuest) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'position' => SORT_ASC,
                    ]
                ],
                'pagination' => false

            ]);

            [$notesView, $notesFilter] = $this->getCookies();

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'notesView' => $notesView,
                'notesFilter' => $notesFilter,
                'message' => null
            ]);
        } else {    
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }
    
            $model->password = '';
    
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionAddNotes()
    {
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);
        $model = new Notes;

        [$notesView, $notesFilter] = $this->getCookies();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC,
                ]
            ],
            'pagination' => false

        ]);
        
        if ($this->request->isPost) {
            $model->position = $query->count();

            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'notesView' => $notesView,
                        'notesFilter' => $notesFilter,
                        'message' => ['type' => 'success', 'text' => 'Заметка добавлена', 'icon' => 'plus']
                    ]);
                } else {
                    $errors = $model->errors;
                    $errors = array_merge(...array_values($errors));
                    return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'notesView' => $notesView,
                        'notesFilter' => $notesFilter,
                        'message' => ['type' => 'danger', 'text' => $errors[0], 'icon' => 'x-circle']
                    ]);
                }
            }
        }
    }

    public function actionEditNotes()
    {
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC,
                ]
            ],
            'pagination' => false

        ]);

        $id = $this->request->post()['Notes']['id'];
        $model = $this->findModel($id);

        [$notesView, $notesFilter] = $this->getCookies();

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->save()) {
                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'notesView' => $notesView,
                    'notesFilter' => $notesFilter,
                    'message' => ['type' => 'success', 'text' => 'Заметка изменена', 'icon' => 'pencil']
                ]);
            } else {
                $errors = $model->errors;
                $errors = array_merge(...array_values($errors));
                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'notesView' => $notesView,
                    'notesFilter' => $notesFilter,
                    'message' => ['type' => 'danger', 'text' => $errors[0], 'icon' => 'x-circle']
                ]);
            }
        }
    }

    public function actionSetStatus()
    {        
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC,
                ]
            ],
            'pagination' => false

        ]);

        $id = $this->request->post()['Notes']['id'];
        $model = $this->findModel($id);

        if ($model->status == 0) {
            $model->status = 1;
            $model->touch('date_execute');
        } else {
            $model->status = 0;
            $model->date_execute = null;
        }

        [$notesView, $notesFilter] = $this->getCookies();

        if ($model->save()) {
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'notesView' => $notesView,
                'notesFilter' => $notesFilter,
                'message' => ['type' => 'success', 'text' => 'Статус изменен', 'icon' => 'check']
            ]);
        } else {
            $errors = $model->errors;
            $errors = array_merge(...array_values($errors));

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'notesView' => $notesView,
                'notesFilter' => $notesFilter,
                'message' => ['type' => 'danger', 'text' => $errors[0], 'icon' => 'x-circle']
            ]);
        }
    }

    public function actionRemoveNotes($id)
    {
        $result = $this->findModel($id)->delete();

        return $this->asJson($result);
    }

    public function actionSortNotes()
    {
        $order = $this->request->post()['Notes']['order'];
        foreach ($order as $i => $id) {
            $model = Notes::findOne($id);
            $model->position = $i;
            $model->save();
        }
        return;
    }

    public function actionSetView()
    {        
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC,
                ]
            ],
            'pagination' => false

        ]);

        [, $notesFilter] = $this->getCookies();
        $notesView = $this->request->post()['Notes']['notesView'];

        $cookies = $this->response->cookies;
        $cookies->add(new Cookie([
            'name' => 'notesView',
            'value' => $notesView,
        ]));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'notesView' => $notesView,
            'notesFilter' => $notesFilter,
        ]);
    }

    public function actionSetFilter()
    {
        $query = Notes::find()->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC,
                ]
            ],
            'pagination' => false

        ]);
        [$notesView] = $this->getCookies();
        $notesFilter = $this->request->post()['Notes']['notesFilter'];

        $cookies = $this->response->cookies;
        $cookies->add(new Cookie([
            'name' => 'notesFilter',
            'value' => $notesFilter,
        ]));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'notesView' => $notesView,
            'notesFilter' => $notesFilter,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Finds the Notes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Notes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function getCookies()
    {
        $cookies = $this->request->cookies;
        $notesView = $cookies->getValue('notesView', 'grid');
        $notesFilter = $cookies->getValue('notesFilter', 'active');

        return [$notesView, $notesFilter];
    }
}