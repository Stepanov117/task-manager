<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Admin: user redact';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
$usersListLink = Html::a('Users list', Url::to(['admin/users-list']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is user redact.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li><?= $usersListLink ?></li>
</ul>
<?php
if($result === 'delete'){
    echo '<div class="alert alert-danger">User <b>'.$user->username.'</b> deleted!</div>';
    return;
}
if(empty($user)){
    echo '<div class="alert alert-danger">No such user!</div>';
    return;
}
?>
<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'form-user-redact']); ?>
            <?= $form->field($user, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($user, 'password')->passwordInput(['value'=>'', 'skipOnEmpty' => true])->label('New password'); ?>
            <div class="form-group">
                <?= Html::submitButton('Edit user', ['class' => 'btn btn-primary', 'name' => 'user_redact-button']) ?>
                <?= Html::submitButton('Delete user', ['class' => 'btn btn-danger', 'name' => 'user_delete-button', 'value' => 'true']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
switch($result){
    case 'error':
        echo '<div class="alert alert-danger">Can not update user!</div>';
        break;
    case 'update':
        echo '<div class="alert alert-success">Fields updated: '.implode(', ', $fields).'!</div>';
        break;
    case 'nothing':
        echo '<div class="alert alert-warning">Fields not changed!</div>';
        break;
}
?>