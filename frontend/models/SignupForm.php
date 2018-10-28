<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use common\models\Role;
use common\models\UserRole;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $first_name;
	public $last_name;
    public $email;
    public $phone;
    public $password;
	public $password2;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		    ['first_name', 'required'],
		    ['first_name', 'string', 'max' => 25],
			
			['last_name', 'required'],
		    ['last_name', 'string', 'max' => 35],

			['phone', 'required'],
		    ['phone', 'string', 'min' => 12, 'max' => 15],
			
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
			
			['password2', 'required'],
            ['password2', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $transaction = $user->getDb()->beginTransaction();
        
        $user->first_name = $this->first_name;
		$user->last_name = $this->last_name;
		$user->phone = $this->phone;
        $user->email = $this->email;
		
		if($this->password == $this->password2){
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if($user->save(false)){
				$userRole = new UserRole();
				$userRole->user_id = $user->id;
				$userRole->role_id = Role::findOne(['name' => 'user'])->id;
				if($userRole->save(false)){
					$transaction->commit();
					return $user;
				}else{
					\Yii::$app->session->addFlash('error', \Yii::t('app', 'User was not saved'));
					$transaction->rollback();
					return null;
				}
			}else
				return null;
            //return $user->save() ? $user : null;
		}else{
			\Yii::$app->session->addFlash('error', \Yii::t('app', 'Passwords must be similar!'));
			return null;
		}
    }
}
