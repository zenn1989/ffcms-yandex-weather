<?php

use engine\system;
use engine\extension;
use engine\admin;
use engine\template;
use engine\language;
use engine\database;
use engine\property;

class components_weather_back extends engine\singleton {

	public function _update($from_version) {
        // now have now changes in db so skip
        database::getInstance()->con()->query("UPDATE ".property::getInstance()->get('db_prefix')."_extensions SET `version` = '1.0.1', `compatable` = '2.0.4' WHERE `type` = 'components' AND dir = 'weather'");
    }
	
	public function _version() {
        return '1.0.1';
    }

    public function _compatable() {
        return '2.0.4';
    }

    public function _install() {
		$lang_ru = array(
			'ru' => array(
				'front' => array(
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
				), 
				'back' => array(
					'admin_components_weather.name' => 'Погода Яндекс',
					'admin_components_weather.desc' => 'Вывод данных на страницу /weather/ о погоде в городах',
					'admin_components_weather_settings_ids_title' => 'ID Городов',
					'admin_components_weather_settings_ids_desc' => 'Список id городов, которые будут доступны для пользователей. Пример: 33989, 34522. См. http://weather.yandex.ru/static/cities.xml атрибут id.',
                    'admin_components_weather_lat_title' => 'Центр карты: широта',
                    'admin_components_weather_lat_desc' => 'Указатель широты центра координат для отображаемой карты',
                    'admin_components_weather_lon_title' => 'Центр карты: долгота',
                    'admin_components_weather_lon_desc' => 'Указатель долготы центра координат для отображаемой карты',
                    'admin_components_weather_zoom_title' => 'Масштабировование',
                    'admin_components_weather_zoom_desc' => 'Параметр масштабирования карты от 1 до 12 (чем больше значение - тем детальней карта)',
					'admin_components_weather_settings' => 'Настройки'
				)
			)
		);
		$lang_en = array(
			'en' => array(
				'front' => array(
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
				),
				'back' => array(
					'admin_components_weather.name' => 'Yandex weather',
					'admin_components_weather.desc' => 'Display data about citys weather on page /weather/',
					'admin_components_weather_settings_ids_title' => 'City ids',
					'admin_components_weather_settings_ids_desc' => 'City id list what be available for users. Ex: 33989, 34522. Look at http://weather.yandex.ru/static/cities.xml attribute id.',
                    'admin_components_weather_lat_title' => 'Map center: latitude',
                    'admin_components_weather_lat_desc' => 'A pointer to the latitude of the center coordinates for the displayed map',
                    'admin_components_weather_lon_title' => 'Map center: longitude',
                    'admin_components_weather_lon_desc' => 'A pointer to the longitude of the center coordinates for the displayed map',
                    'admin_components_weather_zoom_title' => 'Zoom index',
                    'admin_components_weather_zoom_desc' => 'The zoom setting of the cards from 1 to 12 (the higher the value, the more detailed the map)',
					'admin_components_weather_settings' => 'Settings'
				)
			)
		);
        language::getInstance()->add($lang_ru);
        language::getInstance()->add($lang_en);
        $default_cfg = 'a:4:{s:12:"city_id_list";s:11:"27612,27613";s:14:"map_center_lat";s:5:"55.75";s:14:"map_center_lon";s:5:"37.61";s:8:"map_zoom";s:1:"7";}';
        $stmt = database::getInstance()->con()->prepare("UPDATE ".property::getInstance()->get('db_prefix')."_extensions SET configs = ? WHERE `type` = 'components' AND `dir` = 'weather'");
        $stmt->bindParam(1, $default_cfg, \PDO::PARAM_STR);
        $stmt->execute();
        $stmt = null;
    }

    public function make() {
        $params = array();

        if(system::getInstance()->post('submit')) {
            if(admin::getInstance()->saveExtensionConfigs()) {
                $params['notify']['save_success'] = true;
            }
        }

        $params['config']['city_id_list'] = extension::getInstance()->getConfig('city_id_list', 'weather', extension::TYPE_COMPONENT, 'str');
        $params['config']['map_center_lat'] = extension::getInstance()->getConfig('map_center_lat', 'weather', extension::TYPE_COMPONENT, 'str');
        $params['config']['map_center_lon'] = extension::getInstance()->getConfig('map_center_lon', 'weather', extension::TYPE_COMPONENT, 'str');
        $params['config']['map_zoom'] = extension::getInstance()->getConfig('map_zoom', 'weather', extension::TYPE_COMPONENT, 'int');

        $params['extension']['title'] = admin::getInstance()->viewCurrentExtensionTitle();

        return template::getInstance()->twigRender('components/weather/settings.tpl', $params);
    }
	
	public function _accessData() {
        return array(
            'admin/components/weather'
        );
    }


}