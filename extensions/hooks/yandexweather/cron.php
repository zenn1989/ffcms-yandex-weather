<?php

use engine\extension;
use engine\system;

class cron_yandexweather extends engine\singleton {

    public function make() {
        $city_array = array();
        $city_idlist = extension::getInstance()->getConfig('city_id_list', 'weather', extension::TYPE_COMPONENT, 'str');
        if(system::getInstance()->isIntList($city_idlist)) {
            foreach(system::getInstance()->altexplode(',', $city_idlist) as $city_id) {
                $city_array[] = (int)trim($city_id);
            }
        }
        foreach($city_array as $city_id) {
            extension::getInstance()->call(extension::TYPE_HOOK, 'yandexweather')->loadCityXML($city_id);
        }
    }
}