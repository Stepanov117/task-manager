<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
$usersListLink = Html::a('Users list', Url::to(['admin/users-list']));
$statusesListLink = Html::a('Statuses list', Url::to(['admin/statuses-list']));
$taskListLink = Html::a('Task list', Url::to(['admin/task-list']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is the Admin page.</p>
<ul class="nav nav-pills">
  <li class="active"><a href="#">Admin</a></li>
  <li><?= $usersListLink ?></li>
  <li><?= $statusesListLink ?></li>
  <li><?= $taskListLink ?></li>
</ul>