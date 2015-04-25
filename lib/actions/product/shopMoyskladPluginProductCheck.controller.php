<?php
/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopMoyskladPluginProductCheckController extends waJsonController
{

    public function execute()
    {
        //@TODO Написать обработчик принимающий Внешний код продукта и возвращающий Good из МойСклада
        $this->errors['messages'][] = _wp('Plugin is not fitished. Contact with developer to get more info<br>Artem Maleev maleev777@gmail.com');
    }

}