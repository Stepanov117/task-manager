<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Admin: statuses list';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is statuses list.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li class="active"><a href="#">Statuses list</a></li>
</ul>
<h2 style="margin-top:35px;">Add new status</h2>
<div class="row">
    <?php $form = ActiveForm::begin(['id' => 'form-status-add']); ?>
    <div class="col-md-4">
        <?= $form->field($statusAdd, 'name')->textInput()->label(false) ?>
    </div>
    <div class="col-md-4">
        <?= Html::submitButton('Add status', ['class' => 'btn btn-success', 'name' => 'status_add-button', 'value' => 'true']) ?>        
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
if(!empty($newStatusName)){
    echo '<div class="alert alert-success">New status <b>'.$newStatusName.'</b> add!</div>';
}
?>
<h2>Statuses list</h2>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Order</th>
            <th scope="col">Edit or delete</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($statuses as $k => $status){
        $tr = '<td>'.++$k.'</td><td>'.$status->name.'</td><td>'.$status->order.'</td>';
        $editLink = Url::to(['admin/edit-status', 'id' => $status->id]);
        $tr.= '<td>'.Html::a('edit', $editLink).'</td>';
        echo '<tr>'.$tr.'</tr>';
    }
?>
    </tbody>
</table>