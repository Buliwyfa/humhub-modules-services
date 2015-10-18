<?php

namespace humhub\modules\services\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\file\models\File;
use humhub\modules\services\models\ServicesMessage;
use humhub\modules\services\models\ServicesMessageEntry;
use humhub\modules\services\models\ServicesUserMessage;
use humhub\modules\User\models\User;
use humhub\modules\services\models\forms\InviteRecipient;
use humhub\modules\services\models\forms\ReplyMessage;
use humhub\modules\services\models\forms\CreateMessage;

/**
 * ServicesController provides messaging actions.
 *
 * @package humhub.modules.services.controllers
 * @since 0.5
 */
class FindController extends Controller
{

    public $subLayout = "@app/modules/services/views/find/_findLayout";

    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
            ]
        ];
    }

    public function actionServices()
    {
        $keyword = Yii::$app->request->get('keyword', "");
        return $this->render('findServices', array(
            'keyword' => $keyword,
            'services' => 1
        ));

    }

    public function actionData()
    {
        $keyword = Yii::$app->request->get('keyword', "");
        return $this->render('findData', array(
            'keyword' => $keyword,
            'services' => 1
        ));

    }


}
