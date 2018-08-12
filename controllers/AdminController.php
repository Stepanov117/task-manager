<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

use app\models\EditUserForm;
use app\models\Status;
use app\models\Task;
use app\models\User;


class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
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

    /** Display admin page. */
    public function actionIndex()
    {
        return $this->render('admin');
    }

    /** Display admin: users list. */
    public function actionUsersList()
    {
        $users = User::find()->all();
        $params = ['users' => $users];
        return $this->render('usersList', $params);
    }
    
    /** Display admin: user redact. */
    public function actionRedactUser()
    {
        $id = Yii::$app->request->get('id');
        $user = User::find()->where(['id' => $id])->one();
        if(empty($user)){
            return $this->render('userRedact');
        }
        if(Yii::$app->request->post('user_delete-button') === 'true'){
            $user->delete();
            return $this->render('userRedact', ['user' => $user, 'result' => 'delete']);
        }
        $model = new EditUserForm();
        $model->username = $user->username;
        $dataPost = Yii::$app->request->post();     
        $msg = 'none';
        $updFields = [];
        if(!empty($dataPost) && $model->load($dataPost)){
            $update = $model->editUser($id);
            $msg = 'error';
            if($update){
                $msg = 'update';
                if($model->usernameUpdate){
                    $updFields[] = 'username';
                }
                if($model->passwordUpdate){
                    $updFields[] = 'password';
                }
                if(empty($updFields)){
                    $msg = 'nothing';
                }
            }
        }
        return $this->render('userRedact', ['user' => $model, 'result' => $msg, 'fields' => $updFields]);
    }

    /** Display admin: statuses list. */
    public function actionStatusesList()
    {
        $newStatus = new Status();
        $params = [];
        if(Yii::$app->request->post('status_add-button') === 'true'){
            $newStatus->load(Yii::$app->request->post());
            $add = $newStatus->add();
            if($add){
                $params['newStatusName'] = $newStatus->name;
                $newStatus = new Status();
            }
        }
        $params['statusAdd'] = $newStatus;
        $params['statuses'] = Status::find()->orderBy(['order' => SORT_ASC])->all(); 
        return $this->render('statusesList', $params);
    }

    /** Display admin: status edit. */
    public function actionEditStatus()
    {
        $id = Yii::$app->request->get('id');
        $status = Status::find()->where(['id' => $id])->one();
        if(empty($status)){
            return $this->render('statusEdit');
        }
        if(Yii::$app->request->post('status_delete-button') === 'true'){
            $status->delete();
            return $this->render('statusEdit', ['status' => $status, 'msg' => 'delete']);
        }
        if(Yii::$app->request->post('status_edit-button') === 'true'){
            $originalStatus = clone $status;
            $status->load(Yii::$app->request->post());
            $edit = $status->edit($originalStatus);
            if($edit){
                $params['msg'] = 'update';
            }
        }
        $order = [0 => 'Make first'];
        $statusesOrder = Status::find()->where(['not', ['order' => null]])->orderBy(['order' => SORT_ASC])->all();
        foreach($statusesOrder as $statusOrder){
            $order[$statusOrder->order] = 'After: '.$statusOrder->name;
        }
        $order[$status->order] = $status->name;
        $params['order'] = $order;
        $params['status'] = $status;
        $params['orderParams'] = [
            'prompt' => 'Select...',
            'options' => [
                $status->order => ["selected" => true],
                $status->order - 1 => ["disabled" => true]
            ]
        ];
        return $this->render('statusEdit', $params);
    }

    /** Display admin: show task list. */
    public function actionTaskList()
    {
        $tasks = Task::find()->all();
        return $this->render('taskList', ['tasks' => $tasks]);
    }

    /** Display admin: add new task. */
    public function actionTaskAdd()
    {
        $newTask = new Task();
        if(Yii::$app->request->post('task_add-button') === 'true'){
            $newTask->load(Yii::$app->request->post());
            $newTask->add();
        }
        $statuses = Status::find()->where(['not', ['order' => null]])->orderBy(['order' => SORT_ASC])->all();
        $params['newTask'] = $newTask;
        $params['statuses'] = ArrayHelper::map($statuses, 'order', 'name');
        $params['statusesParams'] = [
            'prompt' => 'Select...',
            'options' => [ $newTask->status_id => ["selected" => true] ],
        ];
        return $this->render('taskAdd', $params);
    }

    /** Display admin: edit task. */
    public function actionTaskEdit()
    {
        $id = Yii::$app->request->get('id');
        $task = Task::find()->where(['id' => $id])->one();
        if(empty($task)){
            return $this->render('taskEdit');
        }
        if(Yii::$app->request->post('task_edit-button') === 'true'){
            $task->load(Yii::$app->request->post());
            $completed = (int) Yii::$app->request->post('Task')['completed'];
            if($completed === 1 && empty($task->completed_at)) {
                $task->completed_at = time();
            }
            if($completed === 0 && !empty($task->completed_at)) {
                $task->completed_at = null;
            }
            $task->update();
            $params['msg'] = 'updated';
        }
        if(Yii::$app->request->post('task_delete-button') === 'true'){
            $task->delete();
            return $this->render('taskEdit', ['task' => $task, 'msg' => 'deleted']);
        }
        $params['task'] = $task;
        $statuses = Status::find()->where(['not', ['order' => null]])->orderBy(['order' => SORT_ASC])->all();
        $params['statuses'] = ArrayHelper::map($statuses, 'id', 'name');
        $params['statusesParams'] = [
            'prompt' => 'Select...',
            'options' => [ $task->status_id => ["selected" => true] ],
        ];
        return $this->render('taskEdit', $params);
    }
}
