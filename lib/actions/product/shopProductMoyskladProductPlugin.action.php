<?php
/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopProductMoyskladProductPluginAction extends waViewAction
{

    public function execute()
    {
        waLocale::loadByDomain(array('shop', 'moysklad'));

        $this->setTemplate('ProductMoyskladPlugin');
        $product_id = waRequest::get('id', 0, waRequest::TYPE_INT);

        $psm = new shopProductSkusModel();
        $skus = $psm->select("id,product_id,sku,name,moysklad_external_code,moysklad_uuid,price,primary_price,purchase_price,compare_price,count,available")->order("sort ASC")->where("product_id = i:id", array('id'=>$product_id))->fetchAll('id');

        $ppm = new shopProductModel();
        $product_name = $ppm->select("name")->where('id=i:id',array('id'=>$product_id))->fetch();

        if(!empty($skus)){
            $this->view->assign('skus', $skus);
        }
        else{
            $this->view->assign('empty_skus',1);
        }
        $this->view->assign('domain_url', 'shop_moysklad');
        $this->view->assign('product_name', $product_name['name']);
    }

}
