{% import 'macro/settings.tpl' as settingstpl %}
{% import 'macro/notify.tpl' as notifytpl %}
<h1>{{ extension.title }}<small>{{ language.admin_components_weather_settings }}</small></h1>
<hr />
{% if notify.save_success %}
    {{ notifytpl.success(language.admin_extension_config_update_success) }}
{% endif %}
<form method="post" action="" class="form-horizontal">
    <fieldset>
        {{ settingstpl.textgroup('city_id_list', config.city_id_list, language.admin_components_weather_settings_ids_title, language.admin_components_weather_settings_ids_desc ) }}
        {{ settingstpl.textgroup('map_center_lat', config.map_center_lat, language.admin_components_weather_lat_title, language.admin_components_weather_lat_desc ) }}
        {{ settingstpl.textgroup('map_center_lon', config.map_center_lon, language.admin_components_weather_lon_title, language.admin_components_weather_lon_desc ) }}
        {{ settingstpl.textgroup('map_zoom', config.map_zoom, language.admin_components_weather_zoom_title, language.admin_components_weather_zoom_desc ) }}
        <input type="submit" name="submit" value="{{ language.admin_extension_save_button }}" class="btn btn-success"/>
    </fieldset>
</form>