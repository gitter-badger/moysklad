<?php

class shopMoyskladClient{

    protected $msLogin;
    protected $msPassword;

    protected $msUrl;
    protected $msRestUrl;
    protected $msStockUrl;
    protected $msHost;

    function __construct()
    {
        $settings=shopMoyskladHelper::getSettings();
        if($settings['login'] && $settings['password']){
            $this->msLogin = $settings['login'];
            $this->msPassword = $settings['password'];
            $this->msHost = 'online.moysklad.ru';

            $this->msRestUrl = 'https://online.moysklad.ru/exchange/rest/ms/xml/';
            $this->msStockUrl = 'https://online.moysklad.ru/exchange/rest/stock/xml?stockMode=ALL_STOCK';
            $this->msUrl = 'ssl://online.moysklad.ru';
        } else {
            throw new Exception('Login or Password is not set');
        }
    }


    /*
   * @param $data string
   * @throw $socket Exception
   * @return string
   */
    protected function socketSender($data){
        $errno=0;
        $errstr="";
        $tokensocket=fsockopen($this->msUrl,443,$errno,$errstr,10);
        if(!$tokensocket){
            $socket =new Exception($errno." ".$errstr);
            throw $socket;
        }
        //Отправляем данные в МойСклад
        fputs($tokensocket, $data);
        while (($buffer = trim(fgets($tokensocket, 4096)))) {
        }
        //Получаем ответ
        $response='';
        while (!feof($tokensocket)){
            $response.= fgets($tokensocket, 4096);
        }
        //Закрываем соединение
        fclose($tokensocket);
        return $response;
    }

    /**
     * @param $body string
     * @param $request string
     * @return string
     */
    protected function sendData($request,$body=""){
        $sendHead=$request." HTTP/1.0\r\n";
        $sendHead .= "Host: ".$this->msHost."\r\n";
        $sendHead .= "Authorization: Basic ".base64_encode($this->msLogin.':'.$this->msPassword)."\r\n";
        $sendHead .= "Content-length: ".strlen($body)."\r\n";
        $sendHead .= "Content-type: application/xml\r\n";
        $sendHead .= "Connection: close\r\n";
        $sendHead .= "\r\n";
        if($body) {
            $sendHead .= $body . "\r\n\r\n";
        }
        try {
            $result=$this->socketSender($sendHead);
            return $result;
        }
        catch(Exception $exception){
            /*@TODO Написать обработчик Исключения socketSender()*/
            echo "</br> SOCKET NOT OPENED </br>";
            return "";
        }
    }

    /**
     * @param $type
     * @param $uuid
     * @return string
     */
    public function getItemByUuid($type,$uuid){
        $request="GET ".$this->msRestUrl."".$type."/$uuid";
        $response=$this->sendData($request);
        return $response;
    }

    /**
     * @param $start int
     * @param $type string
     * @return string
     */
    public function getItemsList($type,$start=0,$count=1000){
        $request="GET ".$this->msRestUrl."".$type."/list?start=$start&count=$count";
        $response=$this->sendData($request);
        return $response;
    }

    /**
     * @param $type
     * @param $goodUuid
     * @param int $start
     * @return string
     */
    public function getFeaturesByGoodUuid($type,$goodUuid,$start=0){
        $request="GET ".$this->msRestUrl."".$type."/list?filter=good%3D".$goodUuid."&start=".$start;
        $response=$this->sendData($request);
        return $response;
    }

    public function putOrder($data){
        $request="PUT ".$this->msRestUrl."CustomerOrder";
        $response=$this->sendData($request,$data);
        return $response;
    }
}