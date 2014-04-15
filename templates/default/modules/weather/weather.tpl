{% if weathers %}
<table class="table table-responsive table-striped no-margin">
    <thead>
    <tr>
        <th>{{ language.yandexweather_city_name }}</th>
        <th>{{ language.yandexweather_wind_temp }}</th>
        <th>{{ language.yandexweather_water_temp }}</th>
    </tr>
    </thead>
    <tbody>
    {% for weather in weathers %}
    <tr>
        <td>{% if system.lang == 'ru' %}{{ weather.name_ru }}{% else %}{{ weather.name }}{% endif %}</td>
        <td>{{ weather.temperature }} <img src="{{ system.script_url }}/upload/weather/{{ weather.image }}.png" width="16px" /> </td>
        <td>{{ weather.water_temperature }}</td>
    </tr>
    {% endfor %}
    </tbody>
</table>
<div class="pull-right"><a href="{{ system.url }}/weather/">{{ language.yandexweather_all }}</a></div>
{% endif %}