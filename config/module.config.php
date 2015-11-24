<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'Zf1Module\DispatchListener' => 'Zf1Module\Listener\DispatchListener',
            'Zf1Module\RenderListener'   => 'Zf1Module\Listener\RenderListener',
        ),
        'factories' => array(
            'Zf1Module\Bootstrap'   => 'Zf1Module\Service\BootstrapFactory',
            'Zf1Module\Application' => 'Zf1Module\Service\ApplicationFactory',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Zf1Module\Controller' => 'Zf1Module\Controller',
        ),
    ),
);
