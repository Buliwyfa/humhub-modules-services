<?php

namespace humhub\modules\services\widgets;

use humhub\components\Widget;
use humhub\modules\services\models\ServicesUserMessage;

/**
 * @package humhub.modules.services
 * @since 0.5
 */
class Notifications extends Widget
{

    /**
     * Creates the Wall Widget
     */
    public function run()
    {
        return $this->render('services_notifications', array(
                    'newServicesMessageCount' => ServicesUserMessage::getNewServicesMessageCount()
        ));
    }

}

?>
