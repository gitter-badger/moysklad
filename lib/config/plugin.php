<?php

/*
* @author Maleev Artem <maleev777@gmail.com>
*/

return array(
    'name' => 'MoySklad-sinc',
    'description' => 'Plug-in synchronization with the service MoySklad',
    'img' => 'img/moysklad.png',
    'vendor' => '969712',
    'version' => '1.0.0',
    'frontend' => true,
    'icons' => array(
        16 => 'img/moysklad.png',
        24 => 'img/moysklad24.png',
    ),
    //Включаем отображения плагина в настройках
    'shop_settings' => true,
    //Привязываем методы плагина к хукам
    'handlers' => array(
        'product_save' => 'productSave',
        'product_delete' => 'productDelete',
        'backend_product' => 'backendProduct',
        'order_action.create' => 'orderActionCreate',
    ),
);