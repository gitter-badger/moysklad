<?php

class shopMoyskladOrderPluginModel extends waModel{

    protected $settings;
    protected $order;

    function __construct($order_id){
        $this->settings=shopMoyskladHelper::getSettings();
        $som = new shopOrderModel();
        $this->order = $som->getById((int)$order_id);
    }


    public function putOrder(){
        if($this->order) {
            $this->prepareOrderCustomer();
            $xmlOrder = $this->prepareOrderFields();
            $mskClient = new shopMoyskladClient();
            $mskClient->putOrder($xmlOrder->asXML());
        }
    }


    /**
     *
     * @TODO: Заменить все константы на значения с настроек Плагина
     */
    protected function prepareOrderFields(){
            $data= new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><customerOrder></customerOrder>');

            //номер заказа
            $data->addAttribute('name','100'.$this->order['id']);

            $data->addAttribute('sourceStoreUuid',$this->settings['store_uuid']);

            $data->addAttribute('targetAgentUuid',$this->settings['organization_uuid']);

            $data->addAttribute('sourceAgentUuid',$this->order['agent_uuid']);

            $data->addAttribute('moment', $order['create_datetime']);
            $data->addAttribute('vatIncluded',"false");
            $data->addAttribute('applicable',"true");
            $data->addAttribute('payerVat',"false");
            $sum=$data->addChild('sum');
            $sum->addAttribute('sum',floatval($order['total'])*100);
            $sum->addAttribute('sumInCurrency',floatval($order['total'])*100);

            if($order["moysklad_uuid"] && $order["moysklad_external_code"]) {
                $data->uuid = $order["moysklad_uuid"];
                $data->externalcode=$order["moysklad_external_code"];
            }


            if($order->deliveryAddress) {
                $deliveryAddress = $data->addChild('attribute');
                $deliveryAddress->addAttribute('metadataUuid', MW_ORDER_ATTRIBUTE_DELIVERY_ADDRESS);
                $deliveryAddress->addAttribute('valueText', $order->deliveryAddress);
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
    protected function prepareOrderCustomer(){
        //Получаем данные контрагента разметившего заказ
        $contact = new waContact($this->order['contact_id']);
        $contactUuid=$contact->get('moysklad_uuid');

        //Если контрагент новый добавляем его в мойсклад
        if(!$contactUuid)
        {
            $mcm = new shopMoyskladContactPluginModel($contact);
            $contactUuid=$mcm->sendContact();
        }
        //Если контрагент не был создан,устанавливаем контрагента по умолчанию
        $contactUuid?$this->order['agent_uuid']=$contactUuid:$this->order['agent_uuid']=$this->settings['default_contragent'];
    }








}