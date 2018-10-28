<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('app', 'Please fill out the following fields to signup') ?>:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'first_name')->textInput(['autofocus' => true])->label(\Yii::t('app', 'First name')) ?>
                
                <?= $form->field($model, 'last_name')->label(\Yii::t('app', 'Last name')) ?>
                
                <?= $form->field($model, 'phone')->label(\Yii::t('app', 'Phone')) ?>

                <?= $form->field($model, 'email')->label(\Yii::t('app', 'Email')) ?>

                <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
                
                <?= $form->field($model, 'password2')->passwordInput()->label(\Yii::t('app', 'Confirm Password')) ?>

                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
