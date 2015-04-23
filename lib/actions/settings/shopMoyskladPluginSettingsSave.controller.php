<?php

/*
 * @author Maleev Artem <maleev777@gmail.com>
 */

class shopMoyskladPluginSettingsSaveController extends waJsonController
{

    public function execute()
    {
        // Сохранение файла CSS
        $css = waRequest::post('css', '');
        $paths = shopMoyskladPluginHelper::getCssPaths();
        file_put_contents($paths['changed'], $css);

        // Сохранение файла JS локализации
        $js = waRequest::post('js', null);
        if ($js !== null) {
            $js_paths = shopMoyskladPluginHelper::getFrontendLocaleJSPaths();
            file_put_contents($js_paths['changed'], $js);
        }

        // Сохранение файла шаблона
        $template = waRequest::post('template', null);
        if ($template !== null && is_array($template)) {
            foreach ($template as $template_name => $t) {
                $template_paths = shopMoyskladPluginHelper::getTemplatePaths($template_name);
                file_put_contents($template_paths['changed'], $t);
            }
        }

        $sm = new shopMoyskladSettingsPluginModel();
        $settings = waRequest::post('settings', array());
        // Удаляем старые настройки
        $sm->deleteByField('field', 'settings');
        $settings['enable'] = isset($settings['enable']) ? 1 : 0;
        $sm->save('settings', $settings);
    }

}
