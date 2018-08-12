<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use app\models\Status;
use app\models\Task;

$this->title = 'Admin: task list';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
$taskAddLink = Html::a('Add new task', Url::to(['admin/task-add']), ['class' => "btn btn-success"]);
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is task list.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li class="active"><a href="#">Task list</a></li>
  <li><?= $taskAddLink ?></li>
</ul>
<h2>Task list</h2>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Status</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th scope="col">Completed</th>
            <th scope="col">Edit or delete</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($tasks as $k => $task){
        $tr = '<td>'.++$k.'</td>';
        $tr.= '<td>'.$task->name.'</td>';
        $tr.= '<td>'.$task->status->name.'</td>';
        $tr.= '<td>'.$task->getCreated().'</td>';
        $tr.= '<td>'.$task->getUpdated().'</td>';
        $tr.= '<td>'.$task->getCompleted().'</td>';
        $editLink = Url::to(['admin/task-edit', 'id' => $task->id]);
        $tr.= '<td>'.Html::a('edit', $editLink).'</td>';
        echo '<tr>'.$tr.'</tr>';
    }
?>
    </tbody>
</table>