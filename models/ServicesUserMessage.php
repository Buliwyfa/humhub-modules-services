<?php

namespace humhub\modules\services\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\modules\services\models\ServicesMessage;
use humhub\modules\services\models\User;

/**
 * This is the model class for table "services_user_message".
 *
 * The followings are the available columns in table 'services_user_message':
 * @property integer $message_id
 * @property integer $user_id
 * @property integer $is_originator
 * @property string $last_viewed
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @package humhub.modules.services.models
 * @since 0.5
 */
class ServicesUserMessage extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'services_user_message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['message_id', 'user_id'], 'required'),
            array(['message_id', 'user_id', 'is_originator', 'created_by', 'updated_by'], 'integer'),
            array(['last_viewed', 'created_at', 'updated_at'], 'safe'),
        );
    }

    public function getServices_message()
    {
        return $this->hasOne(ServicesMessage::className(), ['id' => 'message_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'message_id' => 'Message',
            'user_id' => 'User',
            'is_originator' => 'Is Originator',
            'last_viewed' => 'Last Viewed',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        );
    }

    /**
     * Returns the new message count for given User Id
     *
     * @param int $userId
     * @return int
     */
    public static function getNewServicesMessageCount($userId = null)
    {
        if ($userId === null) {
            $userId = Yii::$app->user->id;
        }

        $json = array();

        $query = self::find();
        $query->joinWith('services_message');
        $query->where(['services_user_message.user_id' => $userId]);
        $query->andWhere("services_message.updated_at > services_user_message.last_viewed OR services_user_message.last_viewed IS NULL");
        $query->andWhere(["<>", 'services_message.updated_by', $userId]);

        return $query->count();
    }

}
