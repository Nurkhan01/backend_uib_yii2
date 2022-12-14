<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

//    /**
//     * Displays homepage.
//     *
//     * @return string
//     */
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }

    /**
     * Login action.
     *
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '')) {

            $user = $model->getUser();

            if (is_null($user)) {
                Yii::$app->response->statusCode = 422;
                return [
                    'errors' => ['login' => ['Неправильный логин или пароль']]
                ];
            }

            if ($model->login()) {
                return [
                    'user' => $user,
                    'roles' => Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())
                ];
            }

        }
        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if(Yii::$app->user->logout()){
            return true;
        }

//        return $this->goHome();
    }

//    /**
//     * Displays contact page.
//     *
//     * @return Response|string
//     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Displays about page.
//     *
//     * @return string
//     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
}
