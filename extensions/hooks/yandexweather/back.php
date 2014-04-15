<?php

use engine\language;

class hooks_yandexweather_back {
    protected static $instance = null;

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function install() {
        $lang_write = array();
        $lang_write['ru'] = array(
            'admin_hooks_yandexweather.name' => 'Яндекс погода API',
            'admin_hooks_yandexweather.desc' => 'Предоставление подключения к API яндекс погоды и разбор XML листов'
        );
        $lang_write['en'] = array(
            'admin_hooks_yandexweather.name' => 'Yandex weather API',
            'admin_hooks_yandexweather.desc' => 'Provide access to yandex weather API and control XML data'
        );
        language::getInstance()->add($lang_write, true);
    }

    public function make() {}
}