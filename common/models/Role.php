<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $editable
 *
 * @property UserRole[] $userRoles
 */
class Role extends \yii\db\ActiveRecord
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32],
            ['status', 'default', 'value' => self::STATUS_DISABLED],
            ['status', 'in', 'range' => [self::STATUS_ENABLED, self::STATUS_DISABLED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'editable' => 'Editable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['role_id' => 'id']);
    }
}
