<?php

/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopMoyskladProductPluginModel extends waModel{

    protected $table ="shop_moysklad_products";

    public function getMoyskladProductSkus($product_id){
        $products = array();
        $sql = "SELECT * FROM shop_moysklad_products WHERE product_id = '" . $this->escape($product_id) . "'";
        $result = $this->query($sql);
        if ($result) {
            foreach ($result as $r) {
                if (!empty($r['text'])) {
                    $r['value'] = $r['text'];
                }
                if (isset($products[$r['ext']])) {
                    $products[$r['ext']] = (array) $products[$r['ext']];
                    $products[$r['ext']][] = $r['value'];
                } else {
                    $products[$r['ext']] = $r['value'];
                }
            }
        }
        return $products;
    }

    public function countItems($product){
        $sql="SELECT COUNT(*) FROM shop_moysklad_products smp WHERE smp.product_id={$product}";
        return $this->query($sql)->fetchField();
    }
}
