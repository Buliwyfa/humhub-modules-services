<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\services;

use Yii;
use yii\helpers\Url;
use humhub\modules\services\models\ServicesMessage;
use humhub\modules\services\models\ServicesMessageEntry;
use humhub\modules\services\models\UserMessage;
use humhub\modules\services\widgets\NewMessageButton;
use humhub\modules\services\widgets\Notifications;

/**
 * Description of Events
 *
 * @author luke
 */
class Events extends \yii\base\Object
{

    /**
     * On User delete, also delete all comments
     *
     * @param type $event
     */
    public static function onUserDelete($event)
    {

        foreach (ServicesMessageEntry::findAll(array('user_id' => $event->sender->id)) as $messageEntry) {
            $messageEntry->delete();
        }
        foreach (UserMessage::findAll(array('user_id' => $event->sender->id)) as $userMessage) {
            $userMessage->message->leave($event->sender->id);
        }

        return true;
    }

    /**
     * On build of the TopMenu, check if module is enabled
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onTopMenuInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addItem(array(
            'label' => Yii::t('ServicesModule.base', 'Services'),
            'url' => Url::to(['/services/services/index']),
            'icon' => '<i class="fa fa-play"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'services'),
            'sortOrder' => 300,
        ));
    }

    public static function onNotificationAddonInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addWidget(Notifications::className(), array(), array('sortOrder' => 90));
    }

    public static function onProfileHeaderControlsInit($event)
    {
        if (Yii::$app->user->isGuest || $event->sender->user->id == Yii::$app->user->id) {
            return;
        }

        $event->sender->addWidget(NewMessageButton::className(), array('guid' => $event->sender->user->guid, 'type' => 'info'), array('sortOrder' => 90));
    }

}
