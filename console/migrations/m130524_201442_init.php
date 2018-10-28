<?php

//use Yii;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

     	$this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(20)->notNull(),
			//'main_role_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        
        //creating role table
        $this->createTable('{{%role}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'editable' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);
  		
  		//creating user_role tale
  		$this->createTable('{{%user_role}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-user_role-user_id-user-id', '{{%user_role}}', 'user_id', '{{%user}}', 'id', 'RESTRICT');
        $this->addForeignKey('fk-user_role-role_id-role-id', '{{%user_role}}', 'role_id', '{{%role}}', 'id', 'RESTRICT');
        
         //adding basic roles
        $this->insert('{{%role}}', ['name'=>'admin', 'status' => 1, 'editable' => 0]);
        $this->insert('{{%role}}', ['name'=>'user', 'status' => 1, 'editable' => 0]);
        
        //creating admin user
       	$this->insert('{{%user}}',array(
       		'auth_key' => \Yii::$app->security->generateRandomString(),
       		'first_name' =>'Admin Fname',
    		'last_name' =>'Admin Lname',
    		'email'=>'admin@admin.com',
    		'phone' => '0000000000',
    		'password_hash' => \Yii::$app->getSecurity()->generatePasswordHash('admin1'),
    		//'role' => 20,
    		'status' => 10,
    		'created_at' => time(),
    		'updated_at' => time(),
  		));
  		
  		//adding admin role to admin user
  		$this->insert('{{%user_role}}', [
        	'user_id' => (new \yii\db\Query())->select(['id'])->from('user')->one(),
        	'role_id' => (new \yii\db\Query())->select(['id'])->from('role')->where(['name' => 'admin'])->one(),
  		]);
        
    }

    public function safeDown()
    {
    	$this->dropForeignKey('fk-user_role-user_id-user-id', '{{%user_role}}');
    	$this->dropForeignKey('fk-user_role-role_id-role-id', '{{%user_role}}');
    	$this->dropTable('{{%user_role');
    	$this->dropTable('{{%role');
        $this->dropTable('{{%user}}');
    }
}
