<?php

namespace humhub\modules\services\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\file\models\File;
use humhub\modules\User\models\User;
use humhub\modules\services\models\Service;

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
	$listServices = Service::find()
		->all();
        $keyword = Yii::$app->request->get('keyword', "");
        return $this->render('findServices', array(
            'keyword' => $keyword,
            'services' => 1,
	    'list_services' => $listServices
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

    public function actionGetServicesJson()
    {

	$toView = array();
    $lat = Yii::$app->request->get('lat', "");
	$long = Yii::$app->request->get('long', "");
	$radius = Yii::$app->request->get('radius', "");

	Yii::$app->response->format = 'json';

        $json = array();
        $listServices = Service::find()
            ->all();


        //$json['services'] = Services::find()
        //    ->all();
	
	$redis = Yii::$app->redis;
	//GEORADIUS servizio lat long radius km WITHCOORD
	foreach ($listServices as $item){
        $service = array();
        $service['name'] = $item->name;
        $service['id'] = $item->id;
		$result = $redis->executeCommand('GEORADIUS', [$item['name'], $lat, $long, $radius,'km', 'WITHCOORD']);
        $points = array();
        foreach($result as $tmp){
            $one_point = array();
            $one_point['name'] = $tmp[0];
            $one_point['lat'] = $tmp[1][0];
            $one_point['long'] = $tmp[1][1];
            array_push($points, $one_point);
        }

		$service['points'] = $points;
		array_push($toView, $service);
	}

        return $toView;

    }

}
