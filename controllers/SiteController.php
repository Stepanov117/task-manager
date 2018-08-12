<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Status;
use app\models\Task;
use app\models\User;

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

    /**
     * Displays task list.
     *
     * @return string
     */
    public function actionIndex()
    {
        $field = Yii::$app->request->get('field');
        $sort = Yii::$app->request->get('sort');
        if(empty($field) && empty($sort)){
            $tasks = Task::find()->all();
            return $this->render('index', ['tasks' => $tasks]);
        }
        switch($field) {
            case 'Name':
                $order = 'name';
                break;
            case 'Status':
                $order = Status::tableName().'.name';
                break;
            case 'Created':
                $order = 'created_at';
                break;
            case 'Updated':
                $order = 'updated_at';
                break;
            case 'Completed':
                $order = 'completed_at';
                break;
            default:
                $order = 'id';
        }
        $order.= ($sort == 'down') ? ' DESC' : ' ASC';
        switch($field){
            case 'Status':
                $tasks = Task::find()->joinWith(['status'])->orderBy($order)->all();
                break;
            default:
                $tasks = Task::find()->orderBy($order)->all();
        }
        return $this->render('index', ['tasks' => $tasks, 'fieldSort' => $field, 'sort' => $sort]);
    }

    public function actionTaskView()
    {
        $id = (int) Yii::$app->request->get('id');
        if(empty($id)){
            return $this->render('taskView');
        }
        $task = Task::find()->where(['id' => $id])->one();
        if(empty($task)){
            return $this->render('taskView');
        }
        if(Yii::$app->request->post('task_edit_status-button') === 'true'){
            $task->load(Yii::$app->request->post());
            $task->update();
            $params['msg'] = 'status-updated';  
        }
        $params['task'] = $task;
        if(empty($task->completed_at)) {
            $statuses = Status::find()->where(['not', ['order' => null]])->orderBy(['order' => SORT_ASC])->all();
            $params['statuses'] = ArrayHelper::map($statuses, 'id', 'name');
            $params['statusesParams'] = [
                'prompt' => 'Select...',
                'options' => [ $task->status_id => ["selected" => true] ],
            ];
        }
        return $this->render('taskView', $params);
    }

    /**
     * Login action.
     *
     * @return Response|string
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
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $dataPost = Yii::$app->request->post();
        if (!$model->load($dataPost)) {
            return $this->render('signup', ['model' => $model]);
        }
        $user = $model->signup();
        if (is_null($user)) {
            return $this->render('signup', ['model' => $model]);
        }
        if (Yii::$app->getUser()->login($user)) {
            return $this->goHome();
        }
 
        return $this->render('signup', ['model' => $model]);
    }
}