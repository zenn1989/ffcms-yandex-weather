<ol class="breadcrumb">
    <li><a href="{{ system.url }}">{{ language.global_main }}</a></li>
    <li class="active">{{ language.yandexweather_seo_title }}</li>
</ol>
<h1>{{ language.yandexweather_seo_title }}</h1>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{ language.yandexweather_map_title }}
            </div>
            <div class="panel-body">
                <script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru_RU" type="text/javascript"></script>
                <script type="text/javascript">
                    ymaps.ready(init);
                    var myMap,myPlacemark;
                    var mark = [];

                    function init(){
                        myMap = new ymaps.Map ("map", {
                            center: [44.944101, 34.107904],
                            zoom: 8
                        });
                        myMap.behaviors
                                .disable('ruler')
                                .enable(['drag', 'rightMouseButtonMagnifier', 'scrollZoom']);
                        myMap.controls.add(
                                new ymaps.control.ZoomControl()
                        );
                        {% for city_id,city in weather %}
                        var tmp = new ymaps.Placemark([{{ city.total.lat }}, {{ city.total.lon }}], {
                            iconContent: '<img src="{{ system.script_url }}/upload/weather/{{ city.total.image }}.png" width="16px" /> {{ city.total.mid_temperature }}'
                        }, {
                            preset: 'twirl#blueStretchyIcon'
                        });
                        tmp.events.add('click', function() {
                            $.scrollTo('#city_{{ city.total.name }}', 800, {easing:'swing'} );
                            $('#city_{{ city.total.name }}').removeClass('panel-success').addClass('panel-danger')
                        });
                        mark.push(tmp);
                        tmp = null;
                        {% endfor %}

                        for(i = 0;i<=mark.length;i++) {
                            myMap.geoObjects.add(mark[i]);
                        }
                    }
                </script>
                <div id="map" style="width: 100%;height: 400px"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {% for city_id,city in weather %}
    <div class="col-md-4">
        <div class="panel panel-success" id="city_{{ city.total.name }}">
            <div class="panel-heading">
                {% if system.lang == 'ru' %}{{ city.total.ru_name|upper }}
                {% else %}
                {{ city.total.name|upper }}
                {% endif %}
            </div>
            <div class="panel-body text-center">
                {{ language.yandexweather_list_date }} {{ city.day[0]['date']['unixtime']|date('d.m.Y') }}
                <img src="{{ system.script_url }}/upload/weather/citys/{{ city_id }}.jpg" class="center-block img-responsive" />
                {{ language.yandexweather_list_now }}: <img src="{{ system.script_url }}/upload/weather/{{ city.total.image }}.png" width="16px" /> {{ city.total.mid_temperature }}
                <hr />
                <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>{{ language.yandexweather_list_period }}</th>
                        <th>t &deg;C</th>
                        <th>{{ language.yandexweather_list_wind }}</th>
                    </tr>
                </thead>
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
                            <img src="{{ system.script_url }}/upload/weather/{{ city.day[0][i]['image'] }}.png" width="16px" />
                        </td>
                        <td>{% if city.day[0][i]['temp_from'] == '*?' or city.day[0][i]['temp_to'] == '*?' %}
                            {{ city.day[0][i]['temp_avg'] }}
                            {% else %}
                            {{ city.day[0][i]['temp_from'] }} {{ city.day[0][i]['temp_to'] }}
                            {% endif %}
                        </td>
                        <td>{{ city.day[0][i]['wind_speed'] }}<img src="{{ system.script_url }}/upload/weather/{{ city.day[0][i]['wind_type'] }}.gif" /></td>
                    </tr>
                {% endfor %}
                </table>
                <a href="{{ system.url }}/weather/city/{{ city_id }}.html" class="btn btn-sm btn-success btn-block">{{ language.yandexweather_list_more }}</a>
            </div>
        </div>
    </div>
    {% if loop.index%3 == 0 %}
    </div>
    <div class="row">
    {% endif %}
    {% endfor %}
</div>
<p>{{ language.yandexweather_copyrights }}.</p>