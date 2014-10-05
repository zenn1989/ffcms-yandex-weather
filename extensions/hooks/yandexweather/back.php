<?php

use engine\language;
use engine\template;
use engine\database;
use engine\property;

class hooks_yandexweather_back {
    protected static $instance = null;

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

	public function _update($from_version) {
        // now have now changes in db so skip
        database::getInstance()->con()->query("UPDATE ".property::getInstance()->get('db_prefix')."_extensions SET `version` = '1.0.1', `compatable` = '2.0.3' WHERE `type` = 'hooks' AND dir = 'yandexweather'");
    }
	
	public function _version() {
        return '1.0.1';
    }

    public function _compatable() {
        return '2.0.3';
    }

    public function install() {
        $lang_write = array(
			'ru' => array(
				'back' => array(
					'admin_hooks_yandexweather.name' => 'Яндекс погода API',
					'admin_hooks_yandexweather.desc' => 'Предоставление подключения к API яндекс погоды и разбор XML листов'
				)
			),
			'en' => array(
				'back' => array(
					'admin_hooks_yandexweather.name' => 'Yandex weather API',
					'admin_hooks_yandexweather.desc' => 'Provide access to yandex weather API and control XML data'					
				)
			)
		);
        language::getInstance()->add($lang_write);
    }

    public function make() {
        template::getInstance()->set(template::TYPE_CONTENT, 'body', template::getInstance()->twigRender('miss_settings.tpl', array()));
    }
}