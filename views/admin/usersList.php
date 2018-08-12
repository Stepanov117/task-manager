<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Admin: users list';
$this->params['breadcrumbs'][] = $this->title;
$adminLink = Html::a('Admin', Url::to(['admin/index']));
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>This is users list.</p>
<ul class="nav nav-pills">
  <li><?= $adminLink ?></li>
  <li class="active"><a href="#">Users list</a></li>
</ul>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Created</th>
            <th scope="col">Last update</th>
            <th scope="col">Edit or delete</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($users as $k => $user){
        $created_at = date('Y-m-d H:i:s', $user->created_at);
        $updated_at = date('Y-m-d H:i:s', $user->updated_at);
        $tr = '<td>'.++$k.'</td><td>'.$user->username.'</td><td>'.$created_at.'</td><td>'.$updated_at.'</td>';
        $redactLink = Url::to(['admin/redact-user', 'id' => $user->id]);
        $tr.= '<td>'.Html::a('edit', $redactLink).'</td>';
        echo '<tr>'.$tr.'</tr>';
    }
?>
    </tbody>
</table>