<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Admin: edit task';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
$taskListLink = Html::a('Task list', Url::to(['admin/task-list']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is task list.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li><?= $taskListLink ?></li>
  <li class="active"><a href="#">Task edit</a></li>
</ul>
<?php
if(empty($task)) {
    echo '<div class="alert alert-danger" style="margin-top:35px;">This task does not exist!</div>';
    return;
}
if($msg === 'updated') {
  echo '<div class="alert alert-success" style="margin-top:35px;">Task "<b>'.$task->name.'</b>" updated!</div>';
}
if($msg === 'deleted') {
  echo '<div class="alert alert-danger" style="margin-top:35px;">Task "<b>'.$task->name.'</b>" deleted!</div>';
  return;
}
$form = ActiveForm::begin(['id' => 'form-task-add']);
?>
<div class="row">
  <div class="col-md-6">
    <?= $form->field($task, 'name')->textInput() ?>
    <?= $form->field($task, 'desc')->textarea(['rows' => '6'])->label('Description') ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <?= '<b>Created at:</b> '.$task->getCreated() ?>
    <?= '<b>Updated at:</b> '.$task->getUpdated() ?>
    <?php
    $completed = $form->field($task, 'completed')->checkbox();
    if(!empty($task->getCompleted())) {
      $completed = '<b>Completed at:</b> '.$task->getCompleted();
      $completed.= $form->field($task, 'completed')->checkbox(['checked ' => true]);
    }
    ?>
    <?= $completed ?>
  </div>
</div>
<div class="row" style="margin-top:15px;">
  <div class="col-md-3">
    <?= $form->field($task, 'status_id')->dropDownList($statuses, $statusesParams)->label('Status') ?>
    <?= Html::submitButton('Update task', ['class' => 'btn btn-success', 'name' => 'task_edit-button', 'value' => 'true']) ?>
  </div>
</div>
<div class="row" style="margin-top:35px;">
  <div class="col-md-3">
    <?= Html::submitButton('Delete task', ['class' => 'btn btn-danger', 'name' => 'task_delete-button', 'value' => 'true']) ?>
  </div>
</div>
<?php ActiveForm::end(); ?>