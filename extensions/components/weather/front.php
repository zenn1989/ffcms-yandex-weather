<?php

use engine\router;
use engine\template;
use engine\extension;
use engine\system;
use engine\cache;
use engine\meta;
use engine\language;

class components_weather_front {
    protected static $instance = null;
    private static $citys = array();
    const CACHE_TIME = 1200;

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function make() {
        $way = router::getInstance()->shiftUriArray();
        $content = null;

        $city_idlist = extension::getInstance()->getConfig('city_id_list', 'weather', extension::TYPE_COMPONENT, 'str');
        if(system::getInstance()->isIntList($city_idlist)) {
            foreach(system::getInstance()->altexplode(',', $city_idlist) as $city_id) {
                self::$citys[] = (int)trim($city_id);
            }
        }

        switch($way[0]) {
            case 'city':
                $content = $this->viewCityId(system::getInstance()->noextention($way[1]));
                break;
            case null:
                $content = $this->viewCityList();
                break;
        }
        template::getInstance()->set(template::TYPE_CONTENT, 'body', $content);
    }

    private function viewCityId($city_id) {
        $params = array();

        $params['weather'] = extension::getInstance()->call(extension::TYPE_HOOK, 'yandexweather')->getWeatherById($city_id, 14);
        $city_seo = language::getInstance()->getUseLanguage() == 'ru' ? $params['weather']['total']['ru_name'] : $params['weather']['total']['name'];
        meta::getInstance()->add('title', language::getInstance()->get('yandexweather_seo_title') . ' - ' . $city_seo);

        if(cache::getInstance()->get('weather_city_'.$city_id.'_'.language::getInstance()->getUseLanguage(), self::CACHE_TIME))
            return cache::getInstance()->get('weather_city_'.$city_id.'_'.language::getInstance()->getUseLanguage(), self::CACHE_TIME);

        foreach($params['weather']['day'] as $row) {
            for($i=0;$i<=3;$i++) {
                $this->checkWindImage($row[$i]['wind_type']);
                $this->checkWeatherImage($row[$i]['image']);
            }
        }
        $params['city_id'] = $city_id;

        $tmp = template::getInstance()->twigRender('components/weather/city.tpl', $params);
        cache::getInstance()->store('weather_city_'.$city_id.'_'.language::getInstance()->getUseLanguage(), $tmp);

        return $tmp;
    }

    private function viewCityList() {
        meta::getInstance()->add('title', language::getInstance()->get('yandexweather_seo_title'));
        if(cache::getInstance()->get('weather_list_'.language::getInstance()->getUseLanguage(), self::CACHE_TIME))
            return cache::getInstance()->get('weather_list_'.language::getInstance()->getUseLanguage(), self::CACHE_TIME);
        $params = array();

        foreach(self::$citys as $city_id) {
            $params['weather'][$city_id] = extension::getInstance()->call(extension::TYPE_HOOK, 'yandexweather')->getWeatherById($city_id, 1);
            // pictures for wind types. grab some ;D
            for($i=0;$i<=3;$i++) {
                $wind_type = $params['weather'][$city_id]['day'][0][$i]['wind_type'];
                $image = $params['weather'][$city_id]['day'][0][$i]['image'];
                $this->checkWindImage($wind_type);
                $this->checkWeatherImage($image);
            }
        }
        $tmp = template::getInstance()->twigRender('components/weather/list.tpl', $params);
        cache::getInstance()->store('weather_list_'.language::getInstance()->getUseLanguage(), $tmp);
        return $tmp;
    }

    private function checkWindImage($image) {
        if(!file_exists(root . '/upload/weather/' . $image . '.gif')) {
            $image_content = system::getInstance()->url_get_contents('http://yandex.st/weather/1.2.29/i/wind/' . $image . '.gif');
            if($image_content != null)
                system::getInstance()->putFile($image_content, root . '/upload/weather/' . $image . '.gif');
        }
    }

    private function checkWeatherImage($image) {
        if(!file_exists(root . '/upload/weather/' . $image . '.png')) {
            $image_content = system::getInstance()->url_get_contents('http://yandex.st/weather/1.2.21/i/icons/30x30/' . $image . '.png');
            if($image_content != null)
                system::getInstance()->putFile($image_content, root . '/upload/weather/' . $image . '.png');
        }
    }
}

?>