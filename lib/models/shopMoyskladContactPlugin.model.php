<?php

class shopMoyskladContactPluginModel extends waModel{

    protected $type="Company";
    protected $contact;
    protected $settings;


    /**
     * @param $contact waContact
     */
    function __construct($contact)
    {
        $this->contact=$contact;
        $this->settings=shopMoyskladHelper::getSettings();
    }

    public function sendContact(){
        if($this->contact->exists()){

            $contactUuid=$this->contact->get('moysklad_uuid');
            $contactExternalCode=$this->contact->get('moysklad_external_code');

            $customerXML= new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><company></company>');

            //Указываем тип контрагента "Физ лицо"
            $customerXML->addAttribute("companyType","FILI");

            //Указываем что контрагент не Архивный
            $customerXML->addAttribute("archived","false");

            //Присваиваем статус указанный в настройках плагина
            $customerXML->addAttribute("stateUuid",$this->settings['contragent_workflow']);

            $customerXML->addAttribute("name", $this->contact->getName());

            //Выставляем тип цен
            if($this->settings['contragent_price_type']) {
                $customerXML->addAttribute("priceTypeUuid", $this->settings['contragent_price_type']);
            }

            if($contactUuid && $contactExternalCode){
                $customerXML->uuid=$contactUuid;
                $customerXML->externalcode=$contactExternalCode;
            }

            $customerXML->code=$this->contact->getId();
            $customerXML->addChild('requisite')->addAttribute('actualAddress',self::fieldDataMerger($this->contact->get('address')));
            $customerXML->addChild("contact")->addAttribute("phones", self::fieldDataMerger($this->contact->get('phone')));
            $customerXML->contact->addAttribute("email", self::fieldDataMerger($this->contact->get('email')));

            $mcc= new shopMoyskladClient();
            $response=$mcc->putItem($this->type,$customerXML->asXML());

            return $this->parsePutResponse($response);
        }
        throw new Exception('Contact is not exist!!!');
    }

    protected function fieldDataMerger($data){
        $result="";
        $devider="";
        foreach ($data as $d) {
            $result.=$d['value']."".$devider;
            $devider="|";
        }
        return $result;
    }

    protected function parsePutResponse($response){
        $result=simplexml_load_string($response);
        if(isset($result->uuid)) {
            $this->contact['moysklad_uuid']=(string)$result->uuid;
            $this->contact['moysklad_external_code']=(string)$result->externalcode;
            if ($errors = $this->contact->save()) {
                //@TODO: Обработать в случае возникновения ошибки при сохранении;
            }
            return (string)$result->uuid;
        }
        return NULL;
    }

}