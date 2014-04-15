<?php

use engine\cache;
use engine\system;

class hooks_yandexweather_front {
    protected static $instance = null;
    const CACHE_TIME = 3600; // 1 hour

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function getWeatherById($city_id, $day_limit = 7) {
        $xml = $this->loadCityXML($city_id);
        $result = array();
        $result['total'] = array(
            'name' => (string)$xml['slug'], // en slug
            'ru_name' => (string)$xml['city'],
            'mid_temperature' => $this->toMath($xml->fact->{'temperature'}), // middle temperature for current day
            'lat' => (float)$xml['lat'], // latitude
            'lon' => (float)$xml['lon'], // longitude
            'image' => (string)$xml->fact->{'image-v3'}, // for display small img weather
            'wind_type' => (string)$xml->fact->{'wind_direction'},
            'wind_speed' => (float)$xml->fact->{'wind_speed'},
            'water_temperature' => $this->toMath($xml->fact->{'water_temperature'})
        );
        $increment = 0;
        foreach($xml->day as $day) {
            if($increment === $day_limit)
                break;
            $date_split = explode('-', $day['date']);
            $unixtime = strtotime($day['date']);
            $result['day'][$increment]['date'] = array('day' => $date_split[2], 'month' => $date_split[1], 'year' => $date_split[0], 'unixtime' => $unixtime);

            // 4 periods in day
            for($i=0;$i<=3;$i++) {
                $result['day'][$increment][$i] = array(
                    'temp_from' => $this->toMath($day->day_part[$i]->temperature_from),
                    'temp_to' => $this->toMath($day->day_part[$i]->temperature_to),
                    'temp_avg' => $this->toMath($day->day_part[$i]->temperature),
                    'image' => (string)$day->day_part[$i]->{'image-v3'},
                    'wind_type' => (string)$day->day_part[$i]->{'wind_direction'},
                    'wind_speed' => (float)$day->day_part[$i]->{'wind_speed'},
                    'pressure' => (int)$day->day_part[$i]->{'pressure'},
                    'humidity' => (int)$day->day_part[$i]->{'humidity'}
                );
            }
            $increment++;
        }
        return $result;
    }

    private function toMath($int) {
        $integer = (int)$int;
        if($integer > 0)
            $integer = '+'.$integer;
        elseif($integer == 0)
            $integer = '*?';
        return $integer;
    }

    public function loadCityXML($city_id) {
        $xml = null;
        if(cache::getInstance()->get('yaweather_'.$city_id, self::CACHE_TIME)) {
            $xml = simplexml_load_string(cache::getInstance()->get('yaweather_'.$city_id, self::CACHE_TIME));
        } else {
            $file = system::getInstance()->url_get_contents('http://export.yandex.ru/weather-ng/forecasts/'.$city_id.'.xml');
            if($file != null && strlen($file) > 0) {
                cache::getInstance()->store('yaweather_'.$city_id, $file);
                $xml = simplexml_load_string($file);
            } else {
                $xml = simplexml_load_string(cache::getInstance()->get('yaweather_'.$city_id, self::CACHE_TIME * 10));
            }
        }
        return $xml;
    }
}