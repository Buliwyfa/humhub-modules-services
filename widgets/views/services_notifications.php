<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\services\Assets;

$this->registerjsVar('services_loadMessageUrl', Url::to(['/services/services/show', 'id' => '-messageId-']));
$this->registerjsVar('services_viewMessageUrl', Url::to(['/services/services/index', 'id' => '-messageId-']));

Assets::register($this);
?>
<div class="btn-group">
    <a href="#" id="icon-services" class="dropdown-toggle" data-toggle="dropdown"><i
            class="fa fa-play"></i></a>
    <span id="badge-services" style="display:none;"
          class="label label-danger label-notification">1</span>
    <ul id="dropdown-services" class="dropdown-menu">
    </ul>
</div>

<script type="text/javascript">

    /**
     * Refresh New Mail Message Count (Badge)
     */
    reloadMessageCountInterval = 60000;
    setInterval(function () {
        jQuery.getJSON("<?php echo Url::to(['/services/services/get-new-message-count-json']); ?>", function (json) {
            setServicesMessageCount(parseInt(json.newMessages));
        });
    }, reloadMessageCountInterval);

    setServicesMessageCount(<?php echo $newServicesMessageCount; ?>);


    /**
     * Sets current message count
     */
    function setServicesMessageCount(count) {
        // show or hide the badge for new messages
        if (count == 0) {
            $('#badge-services').css('display', 'none');
        } else {
            $('#badge-services').empty();
            $('#badge-services').append(count);
            $('#badge-services').fadeIn('fast');
        }
    }



    // open the messages menu
    $('#icon-services').click(function () {

        // remove all <li> entries from dropdown
        $('#dropdown-services').find('li').remove();
        $('#dropdown-services').find('ul').remove();

        // append title and loader to dropdown
        $('#dropdown-services').append('<li class="dropdown-header"><div class="arrow"></div><?php echo Yii::t('ServicesModule.widgets_views_serviceNotification', 'Service Messages'); ?> <?php echo Html::a(Yii::t('ServicesModule.widgets_views_serviceNotification', 'New service message'), Url::to(['/services/services/create', 'ajax' => 1]), array('class' => 'btn btn-info btn-xs', 'id' => 'create-message-button', 'data-target' => '#globalModal')); ?></li> <ul class="media-list"><li id="loader_messages"><div class="loader"></div></li></ul><li><div class="dropdown-footer"><a class="btn btn-default col-md-12" href="<?php echo Url::to(['/services/services/index']); ?>"><?php echo Yii::t('ServicesModule.widgets_views_serviceNotification', 'Show all service messages'); ?></a></div></li><li><div class="dropdown-footer"><a class="btn btn-default col-md-12" href="<?php echo Url::to(['/services/services/find']); ?>"><?php echo Yii::t('ServicesModule.widgets_views_serviceNotification', 'Find a service'); ?></a></div></li><li><div class="dropdown-footer"><a class="btn btn-default col-md-12" href="<?php echo Url::to(['/services/services/switch']); ?>"><?php echo Yii::t('ServicesModule.widgets_views_serviceNotification', 'Switch ON|OFF your services'); ?></a></div></li>');

        // load newest notifications
        $.ajax({
            'type': 'GET',
            'url': '<?php echo Url::to(['/services/services/notification-list']); ?>',
            'cache': false,
            'data': jQuery(this).parents("form").serialize(),
            'success': function (html) {
                jQuery("#loader_messages").replaceWith(html)
            }});

    })
</script>

