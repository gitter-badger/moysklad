<?php

class shopMoyskladPluginHelper
{

    /**
     * Get public/protected path to file
     *
     * @param string $file - filename or path
     * @param bool $original - if true - return original path to file
     * @return string - protected path to file
     */
    public static function path($file, $original = false)
    {
        $path = wa()->getDataPath('plugins/moysklad/' . $file, true, 'shop', true);
        if ($original) {
            return dirname(__FILE__) . '/../../' . $file;
        }
        if (!file_exists($path)) {
            waFiles::copy(dirname(__FILE__) . '/../../' . $file, $path);
        }
        return $path;
    }

    /**
     * Get path to css files
     *
     * @return string
     */
    public static function getCssPaths()
    {
        static $csspaths = array();
        if (!$csspaths) {
            $csspaths = array(
                "changed" => dirname(__FILE__) . '/../../css/moyskladFrontend.css',
                "original" => dirname(__FILE__) . '/../../css/moyskladFrontendOriginal.css'
            );
        }
        return $csspaths;
    }

    /**
     * Get path to css file
     *
     * @return string
     */
    public static function getCssPath()
    {
        $paths = self::getCssPaths();
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return '';
    }

    /**
     * Check, if original css file was changed
     *
     * @return boolean
     */
    public static function isCssChanged()
    {
        $paths = self::getCssPaths();
        if (file_exists($paths['changed'])) {
            return true;
        }
        return false;
    }

    /**
     * Get url of css file
     *
     * @return string
     */
    public static function getCssUrl()
    {
        $urls = array(
            "changed" => wa()->getAppStaticUrl('shop') . "plugins/moysklad/css/itemsetsFrontend.css",
            "original" => wa()->getAppStaticUrl('shop') . "plugins/moysklad/css/itemsetsFrontendOriginal.css"
        );
        $paths = self::getCssPaths();
        foreach ($paths as $type => $path) {
            if (file_exists($path)) {
                return $urls[$type];
            }
        }
        return '';
    }

    /**
     * Get path to template files
     *
     * @param string $template
     * @return string
     */
    public static function getTemplatePaths($template)
    {
        static $templatepaths = array();
        if (!isset($templatepaths[$template])) {
            $templatepaths[$template] = array(
                "changed" => wa()->getDataPath('plugins/moysklad/templates/lists/') . $template,
                "original" => dirname(__FILE__) . '/../../moysklad/lists/' . $template
            );
        }

        return $templatepaths[$template];
    }

    /**
     * Get path to template file
     *
     * @param string $template
     * @return string
     */
    public static function getTemplatePath($template)
    {
        $paths = self::getTemplatePaths($template);
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return '';
    }

    /**
     * Check, if original template file was changed
     *
     * @param string $template
     * @return boolean
     */
    public static function isTemplateChanged($template)
    {
        $paths = self::getTemplatePaths($template);
        if (file_exists($paths['changed'])) {
            return true;
        }
        return false;
    }

    /**
     * Get path to locale js files
     *
     * @return string
     */
    public static function getFrontendLocaleJSPaths()
    {
        static $jslocalepaths = array();
        if (!$jslocalepaths) {
            $jslocalepaths = array(
                "changed" => dirname(__FILE__) . '/../../js/moyskladFrontendLocale.js',
                "original" => dirname(__FILE__) . '/../../js/moyskladFrontendLocaleOriginal.js'
            );
        }

        return $jslocalepaths;
    }

    /**
     * Get path to locale js file
     *
     * @return string
     */
    public static function getFrontendLocaleJSPath()
    {
        $paths = self::getFrontendLocaleJSPaths();
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return '';
    }

    /**
     * Check, if original locale js file was changed
     *
     * @return boolean
     */
    public static function isFrontendLocaleJSChanged()
    {
        $paths = self::getFrontendLocaleJSPaths();
        if (file_exists($paths['changed'])) {
            return true;
        }
        return false;
    }

    /**
     * Get url of css file
     *
     * @return string
     */
    public static function getFrontendLocaleJSUrl()
    {
        $urls = array(
            "changed" => wa()->getAppStaticUrl('shop') . "plugins/moysklad/js/itemsetsFrontendLocale.js",
            "original" => wa()->getAppStaticUrl('shop') . "plugins/moysklad/js/itemsetsFrontendLocaleOriginal.js"
        );
        $paths = self::getFrontendLocaleJSPaths();
        foreach ($paths as $type => $path) {
            if (file_exists($path)) {
                return $urls[$type];
            }
        }
        return '';
    }

}
