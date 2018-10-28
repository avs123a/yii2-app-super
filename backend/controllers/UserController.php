<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Role;
use common\models\UserRole;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        	'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                            return User::isAdmin(Yii::$app->user->identity->email);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'add-role' => ['POST'],
                    'ban' => ['POST'],
                    'unban' => ['POST'],
                    'clear-roles' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$roles = Role::find(['status' => Role::STATUS_ENABLED])->asArray()->all();
    	//$user_roles = UserRole::find(['user_id' => $id])->asArray()->all();
    	//$user_role_names = [];
    	
    	$user_roles = $model->getRoleNames()->asArray()->all();
    	$role_names = '';
    	foreach($user_roles as $user_role)
    		$role_names .= $user_role['name'] . ' ';
    		
    	unset($user_roles);
    	
        return $this->render('view', [
            'model' => $model,
            'roles' => $roles,
            'user_roles' => $role_names,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		if($model->id == Yii::$app->user->identity->id)
			return $this->redirect(['view', 'id' => $model->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	if($model->id != Yii::$app->user->identity->id)
        	$model->delete();

        return $this->redirect(['index']);
    }
    
    /**
	* Adding role for user
	* @param undefined $id
	* @return
	*/
    public function actionAddRole($id)
    {
		$role_id = Yii::$app->request->post('role_id');
		
		$userRole = new UserRole();
		$userRole->user_id = $id;
		$userRole->role_id = $role_id;
		
		if($userRole->save())
			Yii::$app->session->addFlash('success', 'Role added!');
		else
			Yii::$app->session->addFlash('error', 'Role was not added');
		
		return $this->redirect(['view', 'id' => $model->id]);
	}
	
	/**
	* Ban user by id
	* @param undefined $id
	* @return
	*/
	public function actionBan($id)
	{
		if($id == Yii::$app->user->identity->id)
			return $this->redirect(['view', 'id' => $model->id]);
			
		$model = $this->findModel($id);
		$model->status = User::STATUS_DELETED;
		$model->save();
		Yii::$app->session->addFlash('success', 'This user was Banned!');
		return $this->redirect(['view', 'id' => $model->id]);
	}
	
	/**
	* Unblock user by id
	* @param undefined $id
	* @return
	*/
	public function actionUnban($id)
	{
		if($id == Yii::$app->user->identity->id)
			return $this->redirect(['view', 'id' => $model->id]);
			
		$model = $this->findModel($id);
		$model->status = User::STATUS_ACTIVE;
		$model->save();
		Yii::$app->session->addFlash('success', 'This user was Unblocked!');
		return $this->redirect(['view', 'id' => $model->id]);
	}
	
	/**
	* Reset roles for user by id
	* @param undefined $id
	* @return
	*/
	public function actionClearRoles($id)
	{
		if($id == Yii::$app->user->identity->id)
			return $this->redirect(['view', 'id' => $id]);
			
		UserRole::deleteAll(['user_id' => $id]);
		$userRole = new UserRole();
		$userRole->user_id = $id;
		$userRole->role_id = Role::findOne(['name' => 'user'])->id;
		
		$userRole->save();
		Yii::$app->session->addFlash('success', 'Roles for this user was cleard!');
		return $this->redirect(['view', 'id' => $id]);
	}

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
