<?php

/*
* @author Maleev Artem <maleev777@gmail.com>
*/

class shopMoyskladPlugin extends waPlugin
{

    public function backendProduct($product)
    {
        $settings = shopMoyskladHelper::getSettings();
        if ($settings['enable']) {
            $psm = new shopProductSkusModel();
            $count_skus= $psm->countByField('product_id',$product['id']);
            $mpp = new shopMoyskladProductPluginModel();
            $count_skus_moysklad=$mpp->countByField('product_id',$product['id']);
            return array('edit_section_li' => '<li class="moyskladPlugin'. (!$count_skus_moysklad ? " grey" : "") . '">
            <a href="#/product/'.(empty($product['id'])?'new':$product['id']).'/edit/moyskladProductPlugin" id ="moyskladProduct-tab">
            ' . _wp('Moysklad Product Params') . '
            <span class="hint">'.$count_skus.'</span>
            <span class="s-product-edit-tab-status"></span>
            </a>
              <link rel="stylesheet" href="' . $this->getPluginStaticUrl() . 'css/moysklad.css?v=' . $this->info['version'] . '" />
                <script src="' . $this->getPluginStaticUrl() . 'js/moysklad.js?v=' . $this->info['version'] . '"></script>
            </li>');
        }
    }

    public function productSave($params)
    {

    }

    public function orderActionCreate($params)
    {
        $settings = shopMoyskladHelper::getSettings();
        if ($settings['enable'] && $params['order_id']) {
            $order_id=$params['order_id'];
            $mpo = new shopMoyskladOrderPluginModel();
            $mpo->sendOrderToMoysklad($order_id);

        }
    }
}