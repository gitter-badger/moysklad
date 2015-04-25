<?php

class shopMoyskladOrderPluginModel extends waModel{


    /**
     * @param $order_id int
     */
    public function putOrder($order_id){
        if((int)$order_id) {
            $som = new shopOrderModel();
            $order = $som->getById($order_id);

            $xmlOrder='<?xml version="1.0" encoding="UTF-8"?><customerOrder></customerOrder>';
            $xmlOrder.= $this->prepareOrderFields($order);
            $mskClient = new shopMoyskladClient();
            $mskClient->putOrder($xmlOrder->asXML());
        }

    }


    /**
     * @param $order shopOrderModel
     * @TODO: Заменить все константы на значения с настроек Плагина
     */
    protected function prepareOrderFields($order){

        $data= new SimpleXMLElement('<customerOrder></customerOrder>');

        //номер заказа
        $data->addAttribute('name','100'.$order['id']);

        $data->addAttribute('sourceStoreUuid',DEFAULT_SOURCE_STORE);

        $data->addAttribute('targetAgentUuid',DEFAULT_TARGET_AGENT);
        $data->addAttribute('sourceAgentUuid',$order->customerUuid);

        $data->addAttribute('moment', $order['create_datetime']);
        $data->addAttribute('vatIncluded',"false");
        $data->addAttribute('applicable',"true");
        $data->addAttribute('payerVat',"false");
        $sum=$data->addChild('sum');
        $sum->addAttribute('sum',$order->orderAmount*100);
        $sum->addAttribute('sumInCurrency',$order->orderAmount*100);
        if($order->orderUuid) {
            $data->uuid = $order->orderUuid;
        }
        if($order->orderExternalCode){
            $data->externalcode=$order->orderExternalCode;
        }
        if($order->paymentUuid) {
            $payment = $data->addChild('attribute');
            $payment->addAttribute('metadataUuid', MW_ORDER_ATTRIBUTE_PAYMENT_TYPE);
            $payment->addAttribute('entityValueUuid', $order->paymentUuid);
        }
        if($order->prepayment) {
            $prePayment = $data->addChild('attribute');
            $prePayment->addAttribute('metadataUuid', MW_ORDER_ATTRIBUTE_PAYMENT_TYPE);
            $prePayment->addAttribute('doubleValue', $order->prepayment);
        }
        if($order->deliveryAddress) {
            $deliveryAddress = $data->addChild('attribute');
            $deliveryAddress->addAttribute('metadataUuid', MW_ORDER_ATTRIBUTE_DELIVERY_ADDRESS);
            $deliveryAddress->addAttribute('valueText', $order->deliveryAddress);
        }
        if($order->customerComment) {
            $customerComment = $data->addChild('attribute');
            $customerComment->addAttribute('metadataUuid', MW_ORDER_ATTRIBUTE_CUSTOMER_COMMENT);
            $customerComment->addAttribute('valueText', $order->customerComment);
        }
        if($order->orderId){
            $siteNumber=$data->addChild('attribute');
            $siteNumber->addAttribute('metadataUuid',MW_ORDER_ATTRIBUTE_SITE_ORDER_NUMBER);
            $siteNumber->addAttribute('valueString',$order->orderId);
        }
    }


    /**
     * @param $orderItems shopOrderItemsModel
     */
    protected function prepareOrderItems($orderItems){

    }

    /**
     * @param $orderCustomer contactsShopBackend_customers_listHandler
     */
    protected function prepareOrderCustomer($orderCustomer){

    }








}