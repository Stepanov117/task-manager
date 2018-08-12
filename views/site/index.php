<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Task Tracker';
?>
<h1 style="text-align:center;">Task list</h1>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <?php
                $fields = ['Name', 'Status', 'Created', 'Updated', 'Completed'];
                foreach($fields as $field){
                    $up = '▲';
                    $down = '▼';
                    $name = $field;
                    $upHtml = Html::a($up, Url::to(['index', 'field' => $field, 'sort' => 'up']));
                    $downHtml = Html::a($down, Url::to(['index', 'field' => $field, 'sort' => 'down']));
                    if($field == $fieldSort){
                        $name = '<span style="color: green;">'.$name.'</span>';
                        switch($sort){
                            case 'up':
                                $upHtml = '<span style="color: green;">'.$up.'</span>';
                                break;
                            case 'down':
                                $downHtml = '<span style="color: green;">'.$down.'</span>';
                                break;
                        }
                    }
                    echo "<th scope=\"col\">{$name} {$upHtml} / {$downHtml}</th>";
                }
            ?>
            <th scope="col">View and Edit</th>
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
        $viewLink = Url::to(['task-view', 'id' => $task->id]);
        $tr.= '<td>'.Html::a('View', $viewLink).'</td>';
        echo '<tr>'.$tr.'</tr>';
    }
?>
    </tbody>
</table>