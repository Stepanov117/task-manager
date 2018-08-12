<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Admin: status edit';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
$statusesListLink = Html::a('Statuses list', Url::to(['admin/statuses-list']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is status edit.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li><?= $statusesListLink ?></li>
</ul>
<?php
if(empty($status)) {
    echo '<div class="alert alert-danger">This status does not exist!</div>';
    return;
}
if(!empty($msg) && $msg === 'delete') {
    echo '<div class="alert alert-danger"">Status "'.$status->name.'" has been deleted!</div>';
    return;
}
?>
<div class="row" style="margin-top:35px;">
    <?php $form = ActiveForm::begin(['id' => 'form-status-edit']); ?>
    <div class="col-md-4">
        <?= $form->field($status, 'name')->textInput()->label('New status name') ?>
        <?= $form->field($status, 'order')->dropDownList($order, $orderParams) ?>
        <?= Html::submitButton('Edit status', ['class' => 'btn btn-success', 'name' => 'status_edit-button', 'value' => 'true']) ?>
        <div style="margin-top:50px">
            <?= Html::submitButton('Delete status', ['class' => 'btn btn-danger', 'name' => 'status_delete-button', 'value' => 'true']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
if(!empty($msg) && $msg === 'update') {
    echo '<div class="alert alert-info" style="margin-top:20px;">The status has been updated!</div>';
}
?>