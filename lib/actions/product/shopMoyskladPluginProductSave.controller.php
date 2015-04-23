<?php
/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopMoyskladPluginProductSaveController extends waJsonController
{

    public function execute()
    {
        //@TODO дописать алгоритм сохрания SKU продута после добавления Внешнего кода и Идентификатора
        $items = waRequest::post('items', array());

    }

}