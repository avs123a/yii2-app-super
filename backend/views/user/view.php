<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1>View user #<?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Block', ['ban', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Unblock', ['unban', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'first_name',
            'last_name',
            'email:email',
            'phone',
            'status',
            'created_at',
            'updated_at',
            [
            	'label' => 'ROLES:',
            	'value' => $user_roles,
            ],
        ],
    ]) ?>
    
    <?= Html::beginForm(['user/add-role', 'id' => $model->id], 'post') ?>
    
    <select name="role_id">
    	<?php foreach($roles as $role): ?>
    		<option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
    	<?php endforeach ?>
    </select>
    <?= Html::submitButton('Add Role', ['class' => 'btn btn-success']) ?>
	<?= Html::endForm() ?>
	<?= Html::a('Reset Roles', ['clear-roles', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to clear roles for this user?',
                'method' => 'post',
            ],
    ]) ?>
    
</div>
