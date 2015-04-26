<?php

/*
* @author Maleev Artem <maleev777@gmail.com>
*/

class shopMoyskladHelper{

    private static $settings = array();


    /**
     * Get plugin settings
     *
     * @return array
     */
    public static function getSettings()
    {
        if (!self::$settings) {
            $sm = new shopMoyskladSettingsPluginModel();
            $settings = $sm->get('settings');
            //Настройки из базы
            if ($settings) {
                self::$settings = $settings;
            } else {
                // Дефолтные настройки
                $config = include shopMoyskladPluginHelper::path('lib/config/config.php', true);

                self::$settings = $config['settings'];
            }
        }
        return self::$settings;
    }

    /**
     * Get plugin templates
     *
     * @return array
     */
    public static function getTemplates()
    {
        static $templates = array();

        if (!$templates) {
            $settings = include shopMoyskladPluginHelper::path('lib/config/config.php', true);
            $templates = $settings['files'];
        }
        return $templates;
    }

}