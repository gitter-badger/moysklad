<?php

/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopMoyskladPluginSettingsAction extends waViewAction
{

    public function execute()
    {
        // Общие настройки
        $settings = shopMoyskladHelper::getSettings();

        // Файл стилей CSS
        $path = shopMoyskladPluginHelper::getCssPath();
        $css = file_get_contents($path);

        // Файлы шаблонов
        $templates = shopMoyskladHelper::getTemplates();
        $template_changed = array();
        foreach ($templates as $t) {
            $template_changed[$t] = shopMoyskladPluginHelper::isTemplateChanged($t);
        }
        $this->view->assign('template_changed', $template_changed);

        $this->view->assign('settings', $settings);
        $this->view->assign('css', $css);
        $this->view->assign('css_changed', shopMoyskladPluginHelper::isCssChanged());
        $this->view->assign('js_changed', shopMoyskladPluginHelper::isFrontendLocaleJSChanged());
        $this->view->assign('lang', substr(wa()->getLocale(), 0, 2));
        $this->view->assign('csrf', waRequest::cookie('_csrf', ''));

        $this->view->assign('plugin_url', wa()->getPlugin('moysklad')->getPluginStaticUrl());
    }

}
