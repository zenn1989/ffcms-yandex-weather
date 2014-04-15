<ol class="breadcrumb">
    <li><a href="{{ system.url }}">{{ language.global_main }}</a></li>
    <li><a href="{{ system.url }}/weather/">{{ language.yandexweather_seo_title }}</a></li>
    <li class="active">{% if system.lang == 'ru' %}{{ weather.total.ru_name }}{% else %}{{ weather.total.name }}{% endif %}</li>
</ol>
<h1>{{ language.yandexweather_seo_title }} - {% if system.lang == 'ru' %}{{ weather.total.ru_name }}{% else %}{{ weather.total.name }}{% endif %}</h1>
<hr />
<div class="row">
    <div class="col-md-6">
        <h2>{% if system.lang == 'ru' %}{{ weather.total.ru_name }}{% else %}{{ weather.total.name }}{% endif %} сейчас</h2>
        <table class="table table-responsive table-striped">
            <tbody>
            <tr>
                <td>{{ language.yandexweather_city_air }}</td>
                <td><img src="{{ system.script_url }}/upload/weather/{{ weather.total.image }}.png" width="30px" /> {{ weather.total.mid_temperature }}</td>
            </tr>
            <tr>
                <td>{{ language.yandexweather_city_wind }}</td>
                <td>{{ weather.total.wind_speed }}<img src="{{ system.script_url }}/upload/weather/{{ weather.total.wind_type }}.gif" /></td>
            </tr>
            <tr>
                <td>{{ language.yandexweather_city_water }}</td>
                <td>{{ weather.total.water_temperature }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru_RU" type="text/javascript"></script>
        <script type="text/javascript">
            ymaps.ready(init);
            var myMap,myPlacemark;
            var mark = [];

            function init(){
                myMap = new ymaps.Map ("map", {
                    center: [{{ weather.total.lat }}, {{ weather.total.lon }}],
                    zoom: 9
                });
                myMap.behaviors
                        .disable('ruler', 'scrollZoom', 'drag');
                var tmp = new ymaps.Placemark([{{ weather.total.lat }}, {{ weather.total.lon }}], {
                    iconContent: '<img src="{{ system.script_url }}/upload/weather/{{ weather.total.image }}.png" width="16px" /> {{ weather.total.mid_temperature }}'
                }, {
                    preset: 'twirl#blueStretchyIcon'
                });
                mark.push(tmp);
                tmp = null;

                for(i = 0;i<=mark.length;i++) {
                    myMap.geoObjects.add(mark[i]);
                }
            }
        </script>
        <div id="map" style="width: 100%;height: 160px"></div>
    </div>
</div>
{% for day in weather.day %}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    {% if system.lang == 'ru' %}{{ weather.total.ru_name }}{% else %}{{ weather.total.name }}{% endif %} - {{ day.date.unixtime|date('d.m.Y') }}
                </div>
                <div class="panel-body">
                    <table class="table table-responsive table-striped text-center">
                        <thead>
                        <tr>
                            <th class="text-center">{{ language.yandexweather_list_period }}</th>
                            <th class="text-center">{{ language.yandexweather_city_air }}, t &deg;C</th>
                            <th class="text-center">{{ language.yandexweather_city_pressure }}</th>
                            <th class="text-center">{{ language.yandexweather_city_wind }}</th>
                            <th class="text-center">{{ language.yandexweather_city_humal }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for i in 0..3 %}
                            <tr>
                                <td>
                                    {% if i == 0 %}
                                        {{ language.yandexweather_timeline_morn }}
                                    {% elseif i == 1 %}
                                        {{ language.yandexweather_timeline_mid }}
                                    {% elseif i == 2 %}
                                        {{ language.yandexweather_timeline_evng }}
                                    {% else %}
                                        {{ language.yandexweather_timeline_night }}
                                    {% endif %}
                                    <img src="{{ system.script_url }}/upload/weather/{{ day[i]['image'] }}.png" width="30px" />
                                </td>
                                <td>{% if day[i]['temp_from'] == '*?' or day[i]['temp_to'] == '*?' %}
                                        {{ day[i]['temp_avg'] }}
                                    {% else %}
                                        {{ day[i]['temp_from'] }} {{ day[i]['temp_to'] }}
                                    {% endif %}
                                </td>
                                <td>{{ day[i]['pressure'] }}{{ language.yandexweather_city_pres_type }}</td>
                                <td>{{ day[i]['wind_speed'] }}<img src="{{ system.script_url }}/upload/weather/{{ day[i]['wind_type'] }}.gif" /></td>
                                <td>{{ day[i]['humidity'] }}%</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
{% endfor %}