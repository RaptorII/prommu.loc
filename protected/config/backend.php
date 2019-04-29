<?php
return CMap::mergeArray(

    require_once(dirname(__FILE__).'/main.php'),

    array(


        // стандартный контроллер
        //'defaultController' => 'posts',

        // компоненты
        'components'=>array(

            // пользователь
            'user'=>array(
                'loginUrl'=>array('/users/login'),
            ),

            // mailer
            'mailer'=>array(
                'pathViews' => 'application.views.backend.email',
                'pathLayouts' => 'application.views.email.backend.layouts'
            ),

        ),

        'import' => array(
            'application.models.admin.*',
            'application.models.mailing.*',
            'application.extensions.yiichat.*',
        ),
    )
);
?>