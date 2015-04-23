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
            // Дефолтные настройки плагина
            if ($settings) {
                self::$settings = $settings;
                self::$settings['output_places'] = isset($settings['output_places']) ? (array) $settings['output_places'] : array();
            } else {
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