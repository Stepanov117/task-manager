<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Admin: new task';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
$taskListLink = Html::a('Task list', Url::to(['admin/task-list']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is task list.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li><?= $taskListLink ?></li>
  <li class="active"><a href="#">Task add</a></li>
</ul>
<?php
if($newTask->id > 0) {
  echo '<div class="alert alert-success" style="margin-top:35px;">New task "<b>'.$newTask->name.'</b>" add to task list!</div>';
} 
$form = ActiveForm::begin(['id' => 'form-task-add']);
?>
<div class="row">
  <div class="col-md-6">
    <?= $form->field($newTask, 'name')->textInput() ?>
    <?= $form->field($newTask, 'desc')->textarea(['rows' => '6'])->label('Description') ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <?= $form->field($newTask, 'status_id')->dropDownList($statuses, $statusesParams)->label('Status') ?>
    <?= Html::submitButton('Add task', ['class' => 'btn btn-success', 'name' => 'task_add-button', 'value' => 'true']) ?>
  </div>
</div>
<?php ActiveForm::end(); ?>