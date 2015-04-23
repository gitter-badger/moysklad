<?php

class shopMoyskladOrderPluginModel extends waModel{

    public function sendOrderToMoysklad($order_id){
        $settings= shopMoyskladHelper::getSettings();
        $settings['login'];
        $settings['password'];

    }
}