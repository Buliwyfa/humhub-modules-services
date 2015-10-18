<?php
namespace humhub\modules\services\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\modules\services\models\Service;
use humhub\modules\services\models\User;

/**
 * This is the model class for table "services_service".
 *
 * The followings are the available columns in table 'services_user_service':
 * @property integer $service_id
 * @property integer $user_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @package humhub.modules.services.models
 * @since 0.5
 */
class UserMessage extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'services_user_service';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['service_id', 'user_id'], 'required'),
            array(['service_id', 'user_id', 'status'], 'integer'),
            array(['created_at', 'updated_at'], 'safe'),
        );
    }

    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
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
            'service_id' => 'Service',
            'user_id' => 'User',
            'created_at' => 'Created At'
        );
    }
    /**
     * Return the services (active or not) for given User Id
     *
     * @param int $userId
     * @return array $query
     */
     public static function getServices($userId = null)
     {
	 if ($userId === null) {
            $userId = Yii::$app->user->id;
        }

        $json = array();

        $query = self::find();
        $query->joinWith('services_service');
        $query->where(['services_service.user_id' => $userId]);
	$query->all();

        return $query;
     }
}
