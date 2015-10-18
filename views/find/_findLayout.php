<?php

use yii\helpers\Url;
?>

<div class="container">

    <div class="row">
        <div class="col-md-2">
            <!-- show directory menu widget -->
            <div class="panel panel-default">
                <div
                    class="panel-heading"><?php echo Yii::t('DirectoryModule.views_directory_layout', '<strong>Searching</strong> menu'); ?></div>

                <div class="list-group">
                   
                    <a href="<?php echo Url::to(['/services/find/services']); ?>"
                       class="list-group-item <?php
                       if ($this->context->action->id == "services") {
                           echo "active";
                       }
                       ?>">
                        <div>
                            <div class="user_details"><?php echo Yii::t('DirectoryModule.views_directory_layout', 'Services'); ?></div>
                        </div>
                    </a>

                    <a href="<?php echo Url::to(['/services/find/data']); ?>"
                       class="list-group-item <?php
                       if ($this->context->action->id == "data") {
                           echo "active";
                       }
                       ?>">
                        <div>
                            <div class="workspaces"><?php echo Yii::t('DirectoryModule.views_directory_layout', 'Data'); ?></div>
                        </div>
                    </a>


                 </div>
            </div>
        </div>
        <div class="col-md-7">
            <!-- show content -->
            <?php echo $content; ?>
        </div>
        <div class="col-md-3">
            <!-- show directory sidebar stream -->
            <?php echo \humhub\modules\directory\widgets\Sidebar::widget(); ?>
        </div>
    </div>

</div>

