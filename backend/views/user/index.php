<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <br><p>List</p><br><br><br><br><br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            'id',
            [
			    'format' => ['date', 'dd.MM.Y'],
				'attribute' => 'created_at',
				'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_normal',
                    'template' => '{addon}{input}',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ],
			    ]),
            ],
            [
			    'format' => ['date', 'dd.MM.Y'],
				'attribute' => 'updated_at',
				'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_normal',
                    'template' => '{addon}{input}',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ],
			    ]),
            ],
            'first_name',
            'last_name',
            'email:email',
            [
			    'attribute' => 'status',
				'filter' => array(
				    User::STATUS_ACTIVE => Yii::t('app', 'Active'),
 					User::STATUS_DELETED => Yii::t('app', 'Banned'),
					User::STATUS_GUEST => Yii::t('app', 'Guest(Not Registered)'),
				),
				'value' => function($model){
					switch($model['status']){
						case User::STATUS_ACTIVE: return Yii::t('app', 'Active');
						case User::STATUS_DELETED: return Yii::t('app', 'Banned');
						case User::STATUS_GUEST: return Yii::t('app', 'Guest(Not Registered)');
					}
				},
			],
            [
			    'class' => 'yii\grid\ActionColumn',
				'template' => '{view}, {delete}',
			],
        ],
        
    ]); ?>
</div>
