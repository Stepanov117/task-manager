<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'My Task Tracker - Task View';
?>

<h1 style="text-align:center;">Task View</h1>

<?php
if(empty($task)){
    echo '<div class="alert alert-danger" style="margin-top:35px;">No such task!</div>';
    return;
}
?>

<h2><?= $task->name ?></h2>
<div class="row">
    <div class="col-md-6">
        <div>Status: <b><?= $task->status->name ?></b></div>
        <div style="margin-top: 20px;">Created: <?= $task->getCreated() ?></div>
        <div>Updated: <?= $task->getUpdated() ?></div>
        <?php
            if(!empty($task->completed_at)){
                echo '<div>Completed: '.$task->getCompleted().'</div>';
            }
        ?>
        <div style="margin-top: 20px;"><pre><?= $task->desc ?></pre></div>

    </div>
</div>

<?php
if(!empty($msg) && $msg == 'status-updated'){
    echo '<div class="alert alert-success" style="margin-top:35px;">Task\'s status has been updated!</div>';
}
if(!empty($task->completed_at)){
    return;
}
if(empty($statuses)){
    echo '<div class="alert alert-warning" style="margin-top:35px;">There is no available statuses!</div>';
    return;
}
$form = ActiveForm::begin(['id' => 'form-task-add']);
?>

<div class="row" style="margin-top:15px;">
    <div class="col-md-3">
        <h3>Change status</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($task, 'status_id')->dropDownList($statuses, $statusesParams)->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= Html::submitButton('Update task', ['class' => 'btn btn-success', 'name' => 'task_edit_status-button', 'value' => 'true']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>