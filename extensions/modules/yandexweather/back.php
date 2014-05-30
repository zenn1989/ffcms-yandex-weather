<?php

use engine\admin;
use engine\extension;
use engine\system;
use engine\template;
use engine\language;

class modules_yandexweather_back {

    protected static $instance = null;

    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function install() {
		$lang = array(
			'ru' => array(
				'front' => array(
					'yandexweather_city_name' => 'Город',
					'yandexweather_wind_temp' => 'Воздух',
					'yandexweather_water_temp' => 'Вода',
					'yandexweather_all' => 'Все города...'
				),
				'back' = >array(
					'admin_modules_yandexweather.name' => 'Погода Яндекс',
					'admin_modules_yandexweather.desc' => 'Вывод данных на сайт с помощью хука yandexweather',
					'admin_modules_yandexweather_settings' => 'Настройки',
					'admin_modules_yandexweather_settings_ids_title' => 'ID Городов',
					'admin_modules_yandexweather_settings_ids_desc' => 'Список id городов, которые нужно выводить в блоке на сайте. Пример: 33989, 34522. См. http://weather.yandex.ru/static/cities.xml атрибут id.'
				)
			),
			'en' => array(
				'front' => array(
					'yandexweather_city_name' => 'City',
					'yandexweather_wind_temp' => 'Air',
					'yandexweather_water_temp' => 'Water',
					'yandexweather_all' => 'All citys...'
				),
				'back' = >array(
					'admin_modules_yandexweather.name' => 'Yandex weather',
					'admin_modules_yandexweather.desc' => 'Display weather data according hook yandexweather',
					'admin_modules_yandexweather_settings' => 'Settings',
					'admin_modules_yandexweather_settings_ids_title' => 'City ids',
					'admin_modules_yandexweather_settings_ids_desc' => 'List of city id what should be displayed. Example: 33989, 34522. Look on http://weather.yandex.ru/static/cities.xml attribute id.'
				)
			)
		);
        language::getInstance()->add($lang);
    }

    public function make() {
        $params = array();

        if(system::getInstance()->post('submit')) {
            if(admin::getInstance()->saveExtensionConfigs()) {
                $params['notify']['save_success'] = true;
            }
        }

        $params['config']['city_ids'] = extension::getInstance()->getConfig('city_ids', 'yandexweather', extension::TYPE_MODULE, 'str');

        $params['extension']['title'] = admin::getInstance()->viewCurrentExtensionTitle();

        return template::getInstance()->twigRender('modules/yandexweather/settings.tpl', $params);
    }
}