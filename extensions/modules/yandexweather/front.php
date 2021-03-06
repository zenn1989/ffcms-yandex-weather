<?php

use engine\template;
use engine\extension;
use engine\system;
use engine\cache;
use engine\language;

class modules_yandexweather_front extends engine\singleton {

    const CACHE_TIME = 1200; // 20 min

    public function make() {
        template::getInstance()->set(template::TYPE_MODULE, 'yandexweather', $this->getWeather());
    }

    private function getWeather() {
        $cache_file_name = system::getInstance()->getProtocol() . "_mod_weather_" . language::getInstance()->getUseLanguage(); // support HTTP(S) protocol
        if(cache::getInstance()->get($cache_file_name, self::CACHE_TIME)) {
            return cache::getInstance()->get($cache_file_name, self::CACHE_TIME);
        }
        $city_list = extension::getInstance()->getConfig('city_ids', 'yandexweather', 'modules', 'str');
        $citys = array();
        foreach(system::getInstance()->altexplode(',', $city_list) as $city_single) {
            $city_single = (int)trim($city_single);
            if(system::getInstance()->isInt($city_single))
                $citys[] = $city_single;
        }
        $params = array();
        foreach($citys as $city_id) {
            $data = extension::getInstance()->call(extension::TYPE_HOOK, 'yandexweather')->getWeatherById($city_id);
            if(!file_exists(root . '/upload/weather/' . $data['total']['image'] . '.png')) {
                $image_content = system::getInstance()->url_get_contents('http://yandex.st/weather/1.2.21/i/icons/30x30/' . $data['total']['image'] . '.png');
                if($image_content != null)
                    system::getInstance()->putFile($image_content, root . '/upload/weather/' . $data['total']['image'] . '.png');
            }
            $params['weathers'][] = array(
                'name' => $data['total']['name'],
                'name_ru' => $data['total']['ru_name'],
                'temperature' => $data['total']['mid_temperature'],
                'water_temperature' => $data['total']['water_temperature'],
                'image' => $data['total']['image']
            );
        }
        $temp = template::getInstance()->twigRender('modules/weather/weather.tpl', $params);
        cache::getInstance()->store($cache_file_name, $temp);
        return $temp;
    }

}