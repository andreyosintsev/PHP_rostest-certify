=== YaMaps for WordPress Plugin ===
Contributors: yhunter
Donate link: https://yoomoney.ru/to/41001278340150
Tags: yandex, яндекс, карты, карта, maps, placemark, elementor
Requires at least: 4.7
Tested up to: 6.4.3
Stable tag: 0.6.27
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: yamaps

The plugin allows you to add Yandex Maps (Яндекс Карты) to pages of your site using a WordPress visual editor.

== Description ==

YaMaps plugin is the simplest way to insert Yandex maps on your site. The plugin has a user-friendly interface. You can visually put placemarks on your Yandex map, move them with your mouse, change icons and much more.

For use with the new Gutenberg editor, you need add the classic editor block first!

For the map search to work correctly and find routes, you may need to set an API key (JavaScript API и HTTP Geocoder) on the plugin settings page.

= Plugin Highlights: =

* You can add maps to pages without coding.
* Or you can visually edit the shortcodes in the editor.
* You can add any number of maps to one page.
* You can add multiple markers to one card.
* You can add markers with hyperlinks.
* You can select the icon and it's color of the marker in the colorpicker.
* You can select type of map (Map, Satellite, Hybrid), map zoom, map controls in the visual editor.

https://www.youtube.com/watch?v=m7YncsBrL5g

= Shortcodes Structure =

* yamap center - Map center coordinates
* yamap height - Map height
* yamap zoom - Map zoom (0 to 19)
* yamap scrollzoom - Scrollwheel zoom lock (scrollzoom="0" for lock)
* yamap mobiledrag - Map dragging can be disabled for mobile devices (mobiledrag="0" for lock)
* yamap type - Map type (yandex#map, yandex#satellite, yandex#hybrid)
* yamap controls - Map controls separated by a semicolon (typeSelector;zoomControl;searchControl;routeEditor;trafficControl;fullscreenControl;geolocationControl)
* yamap container - ID of the existing block in the WP template. The map will be placed in the block with this ID. The new block in the content will not be created.

* yaplacemark coord - Placemark coordinates
* yaplacemark icon - Placemark icon (Yandex.Map icon type or url of your own image)
* yaplacemark color - Marker color
* yaplacemark name - Placemark hint or content
* yaplacemark url - Linked URL or post with ID will be opened by click on the placemark

* You can insert multiple placemark codes inside the maps's shortcode.

= Shortcode Example =

[yamap center="55.7532,37.6225" height="15rem" zoom="12" type="yandex#map" controls="typeSelector;zoomControl"][yaplacemark coord="55.7532,37.6225" icon="islands#blueRailwayIcon" color="#ff751f" name="Placemark"][/yamap]

== Installation ==

1. Upload `yamaps` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Do I need a Yandex Map API key for using YaMaps? =

At the moment, the key is needed only for the search on the map. The rest of the plugin functionality will work without a key. You can get the key (https://developer.tech.yandex.ru/services/) and enter it in the plugin settings page.

= How to choose the type and zoom of the map? =

Just set type and zoom of the map in the visual editor window. On the site it will be displayed identically.

= How to use the plugin with new Gutenberg editor?  =

You can add a block with a classic editor and add a map with it. Later, native support can be added if most users start to use Gutenberg.

= How to insert a map into my template as PHP code? =

Use the tag "echo do_shortcode('')" with your map shortcode insde.

= How to set an icon that is not in the drop-down list? =

You can chose icon at https://tech.yandex.com/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/ and set it manually to the "Icon" field. For example "islands#blueRailwayIcon". Also you can insert the URL of your file in the field. For example, PNG-image with transparency.

= Why a can't change color of StretchyIcon? =

This is the limitation of Yandex.Map API. You can select a stretchy icon of the desired color at https://tech.yandex.com/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/ and set it manually.

= Russian description =
https://www.yhunter.ru/portfolio/dev/yamaps/

= GitHub Project =
https://github.com/yhunter-ru/yamaps

 == Screenshots == 

1. Visual shortcode dialog window.
2. Shortcode in TinyMCE editor.
3. Map on the blog page.
4. Shortcode button.
5. Visual selecting the marker color.

== Changelog ==

= 0.6.27 =
* Fixed: Bugfix. Map outside the main WordPress loop caused an error: Invalid API key.

= 0.6.26 =
* Fixed: Bugfix.

= 0.6.25 =
* Fixed: WP 6.0 support

= 0.6.24 =
* Fixed: Bugfix.

= 0.6.23 =
* New: Yandex Map Api is called only for pages with a map.