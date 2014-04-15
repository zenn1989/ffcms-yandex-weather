<?php

use engine\system;
use engine\extension;
use engine\admin;
use engine\template;
use engine\language;

class components_weather_back {
    protected static $instance = null;

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function install() {
        $lang_write_back = array();
        $lang_write_front = array();
        $lang_write_back['ru'] = array(
            'admin_components_weather.name' => 'Погода Яндекс',
            'admin_components_weather.desc' => 'Вывод данных на страницу /weather/ о погоде в городах',
            'admin_components_weather_settings_ids_title' => 'ID Городов',
            'admin_components_weather_settings_ids_desc' => 'Список id городов, которые будут доступны для пользователей. Пример: 33989, 34522. См. http://weather.yandex.ru/static/cities.xml атрибут id.',
            'admin_components_weather_settings' => 'Настройки'
        );
        $lang_write_back['en'] = array(
            'admin_components_weather.name' => 'Yandex weather',
            'admin_components_weather.desc' => 'Display data about citys weather on page /weather/',
            'admin_components_weather_settings_ids_title' => 'City ids',
            'admin_components_weather_settings_ids_desc' => 'City id list what be available for users. Ex: 33989, 34522. Look at http://weather.yandex.ru/static/cities.xml attribute id.',
            'admin_components_weather_settings' => 'Settings'
        );
        $lang_write_front['ru'] = array(
            'yandexweather_timeline_morn' => 'Утро',
            'yandexweather_timeline_mid' => 'День',
            'yandexweather_timeline_evng' => 'Вечер',
            'yandexweather_timeline_night' => 'Ночь',
            'yandexweather_map_title' => 'Погодная карта',
            'yandexweather_list_date' => 'Погода на',
            'yandexweather_list_now' => 'Сейчас',
            'yandexweather_list_period' => 'Время',
            'yandexweather_list_wind' => 'Ветер',
            'yandexweather_list_more' => 'Подробный прогноз',
            'yandexweather_seo_title' => 'Прогноз погоды',
            'yandexweather_copyrights' => 'На данной странице представлен краткий прогноз погоды для различных городов и областей. Данные обновляются каждый час. Технология создана при использовании <a href="http://pogoda.yandex.ru/" target="_blank">Yandex.погода API</a>',
            'yandexweather_city_air' => 'Воздух',
            'yandexweather_city_wind' => 'Ветер',
            'yandexweather_city_water' => 'Вода',
            'yandexweather_city_pressure' => 'Давление',
            'yandexweather_city_humal' => 'Влажность',
            'yandexweather_city_pres_type' => 'мм рт ст',
        );
        $lang_write_front['en'] = array(
            'yandexweather_timeline_morn' => 'Morn',
            'yandexweather_timeline_mid' => 'Noon',
            'yandexweather_timeline_evng' => 'Even',
            'yandexweather_timeline_night' => 'Night',
            'yandexweather_map_title' => 'Weather map',
            'yandexweather_list_date' => 'Weather on',
            'yandexweather_list_now' => 'Now',
            'yandexweather_list_period' => 'Period',
            'yandexweather_list_wind' => 'Wind',
            'yandexweather_list_more' => 'Detail forecast',
            'yandexweather_seo_title' => 'Weather forecast',
            'yandexweather_copyrights' => 'This page provides a brief weather forecast for various cities and regions. The data is updated every hour. Technology created using <a href="http://pogoda.yandex.ru/" target="_blank">Yandex.pogoda API</a>',
            'yandexweather_city_air' => 'Air',
            'yandexweather_city_wind' => 'Wind',
            'yandexweather_city_water' => 'Water',
            'yandexweather_city_pressure' => 'Pressure',
            'yandexweather_city_humal' => 'Humidity',
            'yandexweather_city_pres_type' => 'mm Hg',
        );
        language::getInstance()->add($lang_write_back, true);
        language::getInstance()->add($lang_write_front);
    }

    public function make() {
        $params = array();

        if(system::getInstance()->post('submit')) {
            if(admin::getInstance()->saveExtensionConfigs()) {
                $params['notify']['save_success'] = true;
            }
        }

        $params['config']['city_id_list'] = extension::getInstance()->getConfig('city_id_list', 'weather', extension::TYPE_COMPONENT, 'str');

        $params['extension']['title'] = admin::getInstance()->viewCurrentExtensionTitle();

        return template::getInstance()->twigRender('components/weather/settings.tpl', $params);
    }


}